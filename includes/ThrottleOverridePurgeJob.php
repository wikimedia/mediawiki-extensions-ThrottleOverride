<?php
/**
 * @section LICENSE
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @copyright © 2017 Wikimedia Foundation and contributors
 */

use MediaWiki\MediaWikiServices;

/**
 * Delete expired ThrottleOverride records.
 */
class ThrottleOverridePurgeJob extends Job {
	public function __construct() {
		parent::__construct(
			'ThrottleOverridePurge',
			SpecialPage::getTitleFor( 'OverrideThrottle' )
		);
		$this->removeDuplicates = true;
	}

	public function run() {
		$dbw = ThrottleOverrideUtils::getCentralDB( DB_PRIMARY );
		$expCond = [ 'thr_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ];
		$services = MediaWikiServices::getInstance();
		$lbf = $services->getDBLoadBalancerFactory();
		$ticket = $lbf->getEmptyTransactionTicket( __METHOD__ );
		$cache = $services->getMainWANObjectCache();

		while ( true ) {
			// Find a set of expired records to be deleted
			$ids = [];
			$ips = [];
			$res = $dbw->select(
				'throttle_override',
				[ 'thr_id', 'thr_range_start' ],
				$expCond,
				__METHOD__,
				[ 'FOR UPDATE', 'LIMIT' => 100 ]
			);
			if ( $res->numRows() >= 1 ) {
				foreach ( $res as $row ) {
					$ids[] = $row->thr_id;
					$ips[] = $row->thr_range_start;
				}

				// Delete rows by primary key that we looked up
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'throttle_override' )
					->where( [ 'thr_id' => $ids ] )
					->caller( __METHOD__ )
					->execute();

				// Pause to allow replica servers to catch up
				$lbf->commitAndWaitForReplication( __METHOD__, $ticket );

				// Touch the check key associated with each overrides' bucket
				foreach ( $ips as $ip ) {
					$cache->touchCheckKey(
						ThrottleOverrideUtils::getBucketKey( $cache, $ip )
					);
				}
			} else {
				// No more expired records found, so we are done
				break;
			}
		}

		return true;
	}
}
