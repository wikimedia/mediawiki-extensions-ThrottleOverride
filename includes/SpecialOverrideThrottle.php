<?php

/**
 * MediaWiki extension to temporarily lift throttles.
 * Copyright (C) 2013 Tyler Romeo <tylerromeo@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Specials\SpecialBlock;
use MediaWiki\Title\Title;
use Wikimedia\IPUtils;

class SpecialOverrideThrottle extends FormSpecialPage {
	/** @var string Sanitized target IP address or range */
	protected $target;

	/** @var int value of thr_id */
	protected $throttleId;

	public function __construct() {
		parent::__construct( 'OverrideThrottle', 'throttleoverride' );
	}

	public function getMessagePrefix() {
		return 'throttleoverride';
	}

	public function requiresWrite() {
		return true;
	}

	public function execute( $par ) {
		$this->getOutput()->addModuleStyles( 'ext.throttleoverride.styles' );
		parent::execute( $par );
	}

	public function getFormFields() {
		$config = $this->getConfig();
		// The types are:
		// actcreate - An account is created (not ping-limiter)
		// edit - A page is edited (ping-limiter)
		// move - A page is moved (ping-limiter)
		// mailpassword - User requests a password recovery (ping-limiter)
		// emailuser - User emails another user (ping-limiter)
		$throttleTypes = array_keys( array_filter( $config->get( 'ThrottleOverrideTypes' ) ) );

		// Construct an array of message => type.
		$throttles = [];
		$rateLimits = $config->get( MainConfigNames::RateLimits );
		foreach ( $throttleTypes as $type ) {
			if ( $type == 'actcreate' || isset( $rateLimits[$type] ) ) {
				// For grepping. The following messages are used here:
				// throttleoverride-types-actcreate, throttleoverride-types-edit,
				// throttleoverride-types-moves, throttleoverride-types-mailpassword,
				// throttleoverride-types-emailuser
				$throttles[$this->msg( "throttleoverride-types-$type" )->escaped()] = $type;
			}
		}

		$data = [
			'Target' => [
				'type' => 'text',
				'label-message' => 'throttleoverride-ipaddress',
				'required' => true,
				'autofocus' => true
			],
			'Expiry' => [
				'type' => SpecialBlock::getSuggestedDurations() ? 'selectorother' : 'text',
				'label-message' => 'throttleoverride-expiry',
				'required' => true,
				'options' => SpecialBlock::getSuggestedDurations(),
				'other' => $this->msg( 'ipbother' )->text(),
				'filter-callback' => 'SpecialBlock::parseExpiryInput',
				'validation-callback' => function ( $input ) {
					if ( !$input ) {
						return $this->msg( 'throttleoverride-validation-expiryinvalid' )->parse();
					}
					return true;
				},
				'default' => $this->msg( 'ipb-default-expiry' )->inContentLanguage()->text()
			],
			'Reason' => [
				'type' => 'text',
				'label-message' => 'ipbreason',
			],
			'Throttles' => [
				'type' => 'multiselect',
				'label-message' => 'throttleoverride-types',
				'options' => $throttles,
				'validation-callback' => function ( $input ) {
					if ( !count( $input ) ) {
						return $this->msg( 'throttleoverride-validation-notypes' )->parse();
					}
					return true;
				},
			],
			'Modify' => [
				'type' => 'hidden',
				'default' => '',
			],
		];

		$request = $this->getRequest();
		// thr_target is sanitized so sanitize wpTarget before checking
		$target = $request->getText( 'wpTarget' );
		$this->target = $target !== ''
			? IPUtils::sanitizeRange( IPUtils::sanitizeIP( $target ) )
			: '';
		// Check for an existing exemption in the master database
		$this->throttleId = self::getThrottleOverrideId( $this->target, DB_PRIMARY );
		if ( $request->wasPosted() && $this->throttleId ) {
			$data['Modify']['default'] = 1;
		}

		// If this site was called as Special:OverrideThrottle/$this->par ...
		if ( $this->par ) {
			// Fill in the given name no matter whether an override is already in the db.
			$data['Target']['default'] = $this->par;

			// We need the most recent data here, we're about to change the throttle.
			$dbw = ThrottleOverrideUtils::getCentralDB( DB_PRIMARY );
			$row = $dbw->newSelectQueryBuilder()
				->select( [ 'thr_expiry', 'thr_reason', 'thr_type' ] )
				->from( 'throttle_override' )
				->where( [ 'thr_target' => $this->par ] )
				->caller( __METHOD__ )
				->fetchRow();

			if ( $row ) {
				$data['Expiry']['default'] = $row->thr_expiry;
				$data['Reason']['default'] = $row->thr_reason;

				$types = explode( ',', $row->thr_type );
				$types = array_intersect( $types, $throttleTypes );
				$data['Throttles']['default'] = $types;

				// If a row exists and we've filled in it's data, don't show
				// the warning about "There already is an exemption".
				$data['Modify']['default'] = 1;
			}
		}
		return $data;
	}

	public function onSubmit( array $data ) {
		$types = implode( ',', $data['Throttles'] );
		$reason = trim( $data['Reason'] );
		$parsedRange = IPUtils::parseRange( $data['Target'] );
		$errors = self::validateFields(
			$data['Target'],
			$data['Expiry'],
			$types,
			$parsedRange
		);

		// Require confirmation if there already is a row for that target.
		if ( !$data['Modify'] && $this->throttleId ) {
			$errors[] = [ 'throttleoverride-rule-alreadyexists', $this->target ];
		}

		if ( $errors ) {
			return $errors;
		}

		// Create a log entry
		$logEntry = new ManualLogEntry( 'throttleoverride', 'created' );
		$logEntry->setPerformer( $this->getUser() );
		$logEntry->setTarget( Title::makeTitle( NS_USER, $this->target ) );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::throttles' => $types,
			'5::expiry' => $data['Expiry'],
		] );
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		[ $rangeStart, $rangeEnd ] = $parsedRange;

		// Save the new exemption
		$dbw = ThrottleOverrideUtils::getCentralDB( DB_PRIMARY );
		$row = [
			'thr_target' => $this->target,
			'thr_expiry' => $dbw->encodeExpiry( $data['Expiry'] ),
			'thr_reason' => $reason,
			'thr_type' => $types,
			'thr_range_start' => $rangeStart,
			'thr_range_end' => $rangeEnd,
		];

		// If there already is an exemption for that target AND the user already confirmed
		// to override it, update the db row. Otherwise insert a new row.
		if ( $data['Modify'] && $this->throttleId ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'throttle_override' )
				->set( $row )
				->where( [ 'thr_id' => $this->throttleId ] )
				->caller( __METHOD__ )
				->execute();
		} else {
			$dbw->newInsertQueryBuilder()
				->insertInto( 'throttle_override' )
				->row( $row )
				->caller( __METHOD__ )
				->execute();
		}

		// Purge the cache
		$services = MediaWikiServices::getInstance();
		$cache = $services->getMainWANObjectCache();
		$cache->touchCheckKey( ThrottleOverrideUtils::getBucketKey( $cache, $rangeStart ) );

		// Queue a job that will delete expired records
		$services->getJobQueueGroup()->lazyPush(
			new ThrottleOverridePurgeJob()
		);

		return true;
	}

	/**
	 * Returns the value of thr_id in the database or returns 0
	 * if it doesn't exist.
	 *
	 * @param string $ip
	 * @param int $dbtype either DB_REPLICA or DB_PRIMARY
	 * @return int
	 */
	public static function getThrottleOverrideId( $ip, $dbtype = DB_REPLICA ) {
		$db = ThrottleOverrideUtils::getCentralDB( $dbtype );
		$field = $db->newSelectQueryBuilder()
			->select( 'thr_id' )
			->from( 'throttle_override' )
			->where( [ 'thr_target' => $ip ] )
			->caller( __METHOD__ )
			->fetchField();
		return $field === false ? 0 : $field;
	}

	/**
	 * Do some basic validation on form fields
	 *
	 * @param string $target IP address or range
	 * @param string|bool $expiry
	 * @param string $types
	 * @param array $parsedRange
	 * @return array
	 */
	public static function validateFields( $target, $expiry, $types, $parsedRange ) {
		global $wgThrottleOverrideCIDRLimit;
		$errors = [];
		$ip = IPUtils::sanitizeIP( $target );
		if ( !IPUtils::isIPAddress( $ip ) ) {
			// Invalid IP address.
			$errors[] = [ 'throttleoverride-validation-ipinvalid', $ip ];
		}

		if ( $expiry === false ) {
			// Invalid expiry.
			$errors[] = [ 'throttleoverride-validation-expiryinvalid' ];
		}

		if ( $types === '' ) {
			// No throttle type given.
			$errors[] = [ 'throttleoverride-validation-typesempty' ];
		}

		if ( $parsedRange[0] !== $parsedRange[1] ) {
			$ip = IPUtils::sanitizeRange( $ip );
			[ $iprange, $range ] = explode( '/', $ip, 2 );
			if (
				( IPUtils::isIPv4( $ip ) && $range > 32 ) ||
				( IPUtils::isIPv6( $ip ) && $range > 128 )
			) {
				// Range exemptions effectively disabled.
				$errors[] = [ 'throttleoverride-validation-rangedisabled' ];
			} elseif ( IPUtils::isIPv4( $iprange ) &&
				$range < $wgThrottleOverrideCIDRLimit['IPv4']
			) {
				// Target range larger than limit.
				$errors[] = [
					'throttleoverride-validation-rangetoolarge',
					$wgThrottleOverrideCIDRLimit['IPv4']
				];
			} elseif ( IPUtils::isIPv6( $iprange ) &&
				$range < $wgThrottleOverrideCIDRLimit['IPv6']
			) {
				$errors[] = [
					'throttleoverride-validation-rangetoolarge',
					$wgThrottleOverrideCIDRLimit['IPv6']
				];
			}
		}

		return $errors;
	}

	public function onSuccess() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'throttleoverride-success-sub' ) );
		$out->addWikiMsg( 'throttleoverride-success', wfEscapeWikiText( $this->target ) );
	}

	protected function getGroupName() {
		return 'users';
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	protected function postHtml() {
		$out = '';
		if ( $this->par ) {
			# Get the relevant extract from the log.
			$ipTitle = Title::makeTitleSafe( NS_USER, $this->par );

			LogEventsList::showLogExtract(
				$out,
				'throttleoverride',
				$ipTitle,
				'',
				[
					'lim' => 10,
					'msgKey' => [ 'throttleoverride-showlog' ],
					'showIfEmpty' => false
				]
			);
		}

		return $out;
	}
}
