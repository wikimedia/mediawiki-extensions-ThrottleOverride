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

class ThrottleOverridePager extends TablePager {
	function __construct( SpecialPage $page, $conds = [] ) {
		parent::__construct( $page->getContext() );
		$this->throttleType = isset( $conds['throttleType'] ) ? $conds['throttleType'] : 'all';
	}

	function getFieldNames() {
		return [
			'thr_range_start' => $this->msg( 'throttleoverride-list-rangestart' )->text(),
			'thr_range_end' => $this->msg( 'throttleoverride-list-rangeend' )->text(),
			'thr_expiry' => $this->msg( 'throttleoverride-list-expiry' )->text(),
			'thr_type' => $this->msg( 'throttleoverride-list-type' )->text(),
			'thr_reason' => $this->msg( 'throttleoverride-list-reason' )->text(),
		];
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
		$a = [
			'tables' => 'throttle_override',
			'fields' => [
				'thr_type',
				'thr_range_start',
				'thr_range_end',
				'thr_expiry',
				'thr_reason',
			],
		];

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
				$types = [];
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
		}
	}
}
