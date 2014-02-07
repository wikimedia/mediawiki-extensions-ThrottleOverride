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

class SpecialOverrideThrottle extends FormSpecialPage {
	function __construct() {
		parent::__construct( 'OverrideThrottle', 'noratelimit' );
	}

	function getMessagePrefix() {
		return 'throttleoverride';
	}

	function requiresWrite() {
		return true;
	}

	function getFormFields() {
		global $wgRateLimits;

		// Construct an array of message => type. The types are:
		// actcreate - An account is created (not ping-limiter)
		// edit - A page is edited (ping-limiter)
		// move - A page is moved (ping-limiter)
		// mailpassword - User requests a password recovery (ping-limiter)
		// emailuser - User emails another user (ping-limiter)
		$throttles = array();
		foreach( array( 'actcreate', 'edit', 'move', 'mailpassword', 'emailuser' ) as $type ) {
			if( $type == 'actcreate' || isset( $wgRateLimits[$type] ) ) {
				// For grepping. The following messages are used here:
				// throttleoverride-types-actcreate, throttleoverride-types-edit,
				// throttleoverride-types-moves, throttleoverride-types-mailpassword,
				// throttleoverride-types-emailuser
				$throttles[$this->msg( "throttleoverride-types-$type" )->text()] = $type;
			}
		}

		return array(
			'Target' => array(
				'type' => 'text',
				'label-message' => 'throttleoverride-ipaddress',
				'required' => true,
			),
			'Expiry' => array(
				'type' => SpecialBlock::getSuggestedDurations() ? 'selectorother' : 'text',
				'label-message' => 'ipbexpiry',
				'required' => true,
				'options' => SpecialBlock::getSuggestedDurations(),
				'other' => $this->msg( 'ipbother' )->text(),
				'filter-callback' => 'SpecialBlock::parseExpiryInput',
				'validation-callback' => function ( $value ) {
						return (bool)$value;
					},
				'default' => $this->msg( 'ipb-default-expiry' )->inContentLanguage()->text()
			),
			'Reason' => array(
				'type' => 'text',
				'label-message' => 'ipbreason',
			),
			'Throttles' => array(
				'type' => 'multiselect',
				'label-message' => 'throttleoverride-types',
				'options' => $throttles
			)
		);
	}

	function onSubmit( array $data ) {
		$status = self::validateTarget( $data['Target'] );

		if ( !$status->isOK() ) {
			return $status;
		}

		return wfGetDB( DB_MASTER )->insert(
			'throttle_override',
			array(
				'thr_range_start' => $status->value[0],
				'thr_range_end' => $status->value[1],
				'thr_expiry' => $data['Expiry'],
				'thr_reason' => $data['Reason'],
				'thr_type' => implode( ',', $data['Throttles'] )
			),
			__METHOD__
		);
	}

	function onSuccess() {
		$this->getOutput()->addWikiMsg( 'throttleoverride-success' );
	}

	/**
	 * @param $target
	 *
	 * @return Status
	 */
	public static function validateTarget( $target ) {
		global $wgThrottleOverrideCIDRLimit;

		$parsedRange = IP::parseRange( $target );

		$status = Status::newGood( $parsedRange );

		if ( $parsedRange === array( false, false ) ) {
			$status->fatal( 'throttleoverride-validation-ipinvalid' );
		} elseif ( $parsedRange[0] !== $parsedRange[1] ) {
			list( $ip, $range ) = explode( '/', IP::sanitizeRange( $target ), 2 );

			if (
				( IP::isIPv4( $ip ) && $wgThrottleOverrideCIDRLimit['IPv4'] == 32 ) ||
				( IP::isIPv6( $ip ) && $wgThrottleOverrideCIDRLimit['IPv6'] == 128 )
			) {
				// Range block effectively disabled
				$status->fatal( 'throttleoverride-validation-rangedisabled' );
			}

			if (
				( IP::isIPv4( $ip ) && $range > 32 ) ||
				( IP::isIPv6( $ip ) && $range > 128 )
			) {
				// Dodgy range
				$status->fatal( 'throttleoverride-validation-ipinvalid' );
			}

			if ( IP::isIPv4( $ip ) && $range < $wgThrottleOverrideCIDRLimit['IPv4'] ) {
				$status->fatal( 'throttleoverride-validation-rangetoolarge',
					$wgThrottleOverrideCIDRLimit['IPv4'] );
			}

			if ( IP::isIPv6( $ip ) && $range < $wgThrottleOverrideCIDRLimit['IPv6'] ) {
				$status->fatal( 'throttleoverride-validation-rangetoolarge',
					$wgThrottleOverrideCIDRLimit['IPv6'] );
			}
		}

		return $status;
	}
}
