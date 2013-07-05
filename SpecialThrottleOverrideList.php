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

 /**
  * Special page for viewing the list of current throttle overrides
  */
class SpecialThrottleOverrideList extends FormSpecialPage {
	function __construct() {
		parent::__construct( 'ThrottleOverrideList' );
	}

	function getMessagePrefix() {
		return 'throttleoverride-list';
	}

	function getFormFields() {
		global $wgRateLimits;

		foreach ( array( 'all', 'actcreate', 'edit', 'move', 'mailpassword', 'emailuser' ) as $type ) {
			if ( $type == 'all' || $type == 'actcreate' || isset( $wgRateLimits[$type] ) ) {
				// For grepping. The following messages are used here:
				// throttleoverride-types-all
				// throttleoverride-types-actcreate, throttleoverride-types-edit,
				// throttleoverride-types-moves, throttleoverride-types-mailpassword,
				// throttleoverride-types-emailuser
				$throttles[$this->msg( "throttleoverride-types-$type" )->text()] = $type;
			}
		}

		return array(
			'ThrottleType' => array(
				'type' => 'select',
				'default' => 'all',
				'label-message' => 'throttleoverride-list-throttletype',
				'options' => $throttles
			)
		);
	}

	function alterForm( HTMLForm $form ) {
		$form->setMethod( 'get' );
		$form->setSubmitTextMsg( 'throttleoverride-list-search' );
	}

	function onSubmit( array $data, HTMLForm $form = null ) {
		if ( !wfReadOnly() && !mt_rand( 0, 10 ) ) {
			// Purge expired entries on one in every 10 queries
			$dbw = wfGetDB( DB_MASTER );
			$method = __METHOD__;
			$dbw->onTransactionIdle( function() use ( $dbw, $method ) {
				$dbw->delete(
					'throttle_override',
					array(
						$dbw->addIdentifierQuotes( 'thr_expiry' ) .
						' < ' .
						$dbw->addQuotes( $dbw->timestamp() )
					),
					$method
				);
			} );
		}

		$pager = new ThrottleOverridePager( $this, array(
			'throttleType' => $data['ThrottleType'],
		) );

		// Add the result as post text so it appears after the form
		if ( !$pager->getNumRows() ) {
			$form->addPostText( $this->msg( 'throttleoverride-list-noresults' )->escaped() );
		} else {
			$form->addPostText(
				$pager->getNavigationBar() .
				$pager->getBody() .
				$pager->getNavigationBar()
			);
		}

		return false;
	}

	function getGroupName() {
		return 'users';
	}
}

class ThrottleOverridePager extends TablePager {
	function __construct( SpecialPage $page, $conds = array() ) {
		parent::__construct( $page->getContext() );
		$this->throttleType = isset( $conds['throttleType'] ) ? $conds['throttleType'] : 'all';
	}

	function getFieldNames() {
		return array(
			'thr_range_start' => $this->msg( 'throttleoverride-list-rangestart' )->text(),
			'thr_range_end' => $this->msg( 'throttleoverride-list-rangeend' )->text(),
			'thr_expiry' => $this->msg( 'throttleoverride-list-expiry' )->text(),
			'thr_type' => $this->msg( 'throttleoverride-list-type' )->text(),
			'thr_reason' => $this->msg( 'throttleoverride-list-reason' )->text(),
		);
	}

	function isFieldSortable( $field ) {
		return $field === 'thr_expiry' || $field === 'thr_range_start';
	}

	function getDefaultSort() {
		return 'thr_expiry';
	}

	function getDefaultDirections() {
		return true;
	}

	function getQueryInfo() {
		$a = array(
			'tables' => 'throttle_override',
			'fields' => array(
				'thr_type',
				'thr_range_start',
				'thr_range_end',
				'thr_expiry',
				'thr_reason',
			),
		);

		if ( $this->throttleType !== 'all' ) {
			$a['conds'][] = $this->mDb->addIdentifierQuotes( 'thr_type' ) .
				$this->mDb->buildLike(
					$this->mDb->anyString(),
					$this->throttleType,
					$this->mDb->anyString()
				);
		}

		return $a;
	}

	function formatValue( $name, $value ) {
		switch ( $name ) {
			case 'thr_type':
				$types = array();
				foreach ( explode( ',', $value ) as $type ) {
					// For grepping. The following messages are used here:
					// throttleoverride-types-actcreate, throttleoverride-types-edit,
					// throttleoverride-types-moves, throttleoverride-types-mailpassword,
					// throttleoverride-types-emailuser
					$types[] = $this->msg( "throttleoverride-types-$type" )->escaped();
				}
				return $this->getLanguage()->commaList( $types );

			case 'thr_range_start':
			case 'thr_range_end':
				return IP::prettifyIP( IP::formatHex( $value ) );

			case 'thr_expiry':
				$ts = $this->getLanguage()->userTimeAndDate( $value, $this->getUser() );
				return htmlspecialchars( $ts );

			case 'thr_reason':
				return Linker::formatComment( $value );

			default:
				throw new MWException( "Unknown field $name." );
				return '';
		}
	}
}