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
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\DBConnRef;

class ThrottleOverrideUtils {
	/**
	 * @return bool
	 */
	public static function isCentralWiki() {
		global $wgThrottleOverrideCentralWiki;
		return WikiMap::getCurrentWikiId() === $wgThrottleOverrideCentralWiki;
	}

	/**
	 * @param int $index DB_PRIMARY/DB_REPLICA
	 * @return DBConnRef
	 */
	public static function getCentralDB( $index ) {
		global $wgThrottleOverrideCentralWiki;
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		return $lbFactory->getMainLB( $wgThrottleOverrideCentralWiki )->getLazyConnectionRef(
			$index, [], $wgThrottleOverrideCentralWiki );
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
	public static function getBucketKey( WANObjectCache $cache, $ip ) {
		global $wgThrottleOverrideCentralWiki, $wgThrottleOverrideCIDRLimit;
		// Split the address space into buckets such that any given user IP address
		// or throttle override's IP address range will fall into exactly one bucket.
		$proto = IPUtils::isIPv6( $ip ) ? 'IPv6' : 'IPv4';
		$bucket = IPUtils::sanitizeRange( "$ip/{$wgThrottleOverrideCIDRLimit[$proto]}" );
		// Purge all cache for all IPs in this bucket
		return $cache->makeGlobalKey(
			'throttle-override',
			$wgThrottleOverrideCentralWiki,
			$bucket
		);
	}
}
