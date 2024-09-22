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

use MediaWiki\Config\Config;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\LBFactory;

/**
 * Delete expired ThrottleOverride records.
 */
class ThrottleOverridePurgeJob extends Job {
	private LBFactory $lbFactory;
	private WANObjectCache $cache;
	private ThrottleOverrideUtils $utils;

	public function __construct(
		Config $config,
		LBFactory $lbFactory,
		WANObjectCache $cache
	) {
		parent::__construct(
			'ThrottleOverridePurge',
			SpecialPage::getTitleFor( 'OverrideThrottle' )
		);
		$this->removeDuplicates = true;
		$this->lbFactory = $lbFactory;
		$this->cache = $cache;
		$this->utils = new ThrottleOverrideUtils(
			$config,
			$lbFactory
		);
	}

	public function run() {
		$dbw = $this->utils->getCentralDB( DB_PRIMARY );
		$expCond = $dbw->expr( 'thr_expiry', '<', $dbw->timestamp() );
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );

		while ( true ) {
			// Find a set of expired records to be deleted
			$ids = [];
			$ips = [];
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'thr_id', 'thr_range_start' ] )
				->from( 'throttle_override' )
				->where( $expCond )
				->forUpdate()
				->limit( 100 )
				->caller( __METHOD__ )
				->fetchResultSet();
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
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );

				// Touch the check key associated with each overrides' bucket
				foreach ( $ips as $ip ) {
					$this->cache->touchCheckKey(
						$this->utils->getBucketKey( $this->cache, $ip )
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
