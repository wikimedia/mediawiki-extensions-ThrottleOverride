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

use MediaWiki\SpecialPage\FormSpecialPage;

/**
 * Special page for viewing the list of current throttle overrides
 */
class SpecialThrottleOverrideList extends FormSpecialPage {
	public function __construct() {
		parent::__construct( 'ThrottleOverrideList' );
	}

	public function getMessagePrefix() {
		return 'throttleoverride-list';
	}

	public function getFormFields() {
		global $wgRateLimits;
		global $wgThrottleOverrideTypes;
		$throttleTypes = array_keys( array_filter( $wgThrottleOverrideTypes ) );
		$throttleTypes = array_merge( [ 'all' ], $throttleTypes );

		$throttles = [];
		foreach ( $throttleTypes as $type ) {
			if ( $type == 'all' || $type == 'actcreate' || isset( $wgRateLimits[$type] ) ) {
				// For grepping. The following messages are used here:
				// throttleoverride-types-all
				// throttleoverride-types-actcreate, throttleoverride-types-edit,
				// throttleoverride-types-moves, throttleoverride-types-mailpassword,
				// throttleoverride-types-emailuser
				$throttles[$this->msg( "throttleoverride-types-$type" )->text()] = $type;
			}
		}

		return [
			'ThrottleType' => [
				'type' => 'select',
				'default' => 'all',
				'label-message' => 'throttleoverride-list-throttletype',
				'options' => $throttles
			]
		];
	}

	public function alterForm( HTMLForm $form ) {
		$form->setMethod( 'get' );
		$form->setWrapperLegendMsg( 'throttleoverride-list-legend' );
		$form->setSubmitTextMsg( 'throttleoverride-list-search' );
	}

	public function onSubmit( array $data, HTMLForm $form = null ) {
		$pager = new ThrottleOverridePager( $this, [
			'throttleType' => $data['ThrottleType'],
		] );

		// Add the result as post text so it appears after the form
		if ( !$pager->getNumRows() ) {
			$form->addPostHtml( $this->msg( 'throttleoverride-list-noresults' )->escaped() );
		} else {
			$form->addPostHtml(
				$pager->getNavigationBar() .
				$pager->getBody() .
				$pager->getNavigationBar()
			);
		}

		return false;
	}

	public function getGroupName() {
		return 'users';
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}
}
