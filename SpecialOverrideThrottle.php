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
				'filter-callback' => 'IP::parseRange',
				'validation-callback' => __CLASS__ . '::validateTargetField'
			),
			'Expiry' => array(
				'type' => SpecialBlock::getSuggestedDurations() ? 'selectorother' : 'text',
				'label-message' => 'ipbexpiry',
				'required' => true,
				'options' => SpecialBlock::getSuggestedDurations(),
				'other' => $this->msg( 'ipbother' )->text(),
				'filter-callback' => 'SpecialBlock::parseExpiryInput',
				'validation-callback' => __CLASS__ . '::validateExpiryField',
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
		return wfGetDB( DB_MASTER )->insert(
			'throttle_override',
			array(
				'thr_range_start' => $data['Target'][0],
				'thr_range_end' => $data['Target'][1],
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

	public static function validateTargetField( $value, array $allData, HTMLForm $form ) {
		return $value !== array( false, false );
	}

	public static function validateExpiryField( $value, array $allDatam, HTMLForm $form ) {
		return (bool)$value;
	}
}
