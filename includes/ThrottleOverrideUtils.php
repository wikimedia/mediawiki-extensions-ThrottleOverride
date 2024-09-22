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
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

class ThrottleOverrideUtils {
	private Config $config;
	private LBFactory $lbFactory;

	public function __construct(
		Config $config,
		LBFactory $lbFactory
	) {
		$this->config = $config;
		$this->lbFactory = $lbFactory;
	}

	/**
	 * @return bool
	 */
	public function isCentralWiki() {
		$centralWiki = $this->config->get( 'ThrottleOverrideCentralWiki' );
		return WikiMap::getCurrentWikiId() === $centralWiki;
	}

	/**
	 * @param int $index DB_PRIMARY/DB_REPLICA
	 * @return IDatabase
	 */
	public function getCentralDB( $index ) {
		$centralWiki = $this->config->get( 'ThrottleOverrideCentralWiki' );
		return $this->lbFactory->getMainLB( $centralWiki )->getConnection(
			$index, [], $centralWiki );
	}

	/**
	 * Get the cache bucket key for either:
	 *   - a) The IP address of a user
	 *   - b) A throttle override that happens to include the given IP address
	 *
	 * @param WANObjectCache $cache
	 * @param string $ip A valid IP address (with no pointless CIDR)
	 * @return string
	 */
	public function getBucketKey( WANObjectCache $cache, $ip ) {
		$centralWiki = $this->config->get( 'ThrottleOverrideCentralWiki' );
		$limit = $this->config->get( 'ThrottleOverrideCIDRLimit' );
		// Split the address space into buckets such that any given user IP address
		// or throttle override's IP address range will fall into exactly one bucket.
		$proto = IPUtils::isIPv6( $ip ) ? 'IPv6' : 'IPv4';
		$bucket = IPUtils::sanitizeRange( "$ip/{$limit[$proto]}" );
		// Purge all cache for all IPs in this bucket
		return $cache->makeGlobalKey(
			'throttle-override',
			$centralWiki,
			$bucket
		);
	}
}
