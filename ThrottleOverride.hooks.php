<?php
/**
 * MediaWiki extension to temporarily lift account creation throttles.
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

class ThrottleOverrideHooks {

	const NO_OVERRIDE = -1;

	/**
	 * @param string $ip
	 * @return bool
	 */
	public static function onExemptFromAccountCreationThrottle( $ip ) {
		$result = false;
		$user = RequestContext::getMain()->getUser();
		return self::onPingLimiter( $user, 'actcreate', $result, $ip );
	}

	/**
	 * @throws InvalidArgumentException If $action is invalid
	 *
	 * @param User $user
	 * @param string $action
	 * @param $result
	 * @param null|string $ip
	 *
	 * @return bool
	 */
	public static function onPingLimiter( User &$user, $action, &$result, $ip = null ) {
		global $wgRateLimits;

		if ( $action !== 'actcreate' && !isset( $wgRateLimits[$action] ) ) {
			return true;
		}

		if ( $user->isAnon() && IP::isValid( $user->getName() ) ) {
			$ip = $user->getName();
		} elseif ( $ip === null ) {
			$ip = RequestContext::getMain()->getRequest()->getIP();
		}
		$hexIp = IP::toHex( $ip );

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$expiry = $cache->getWithSetCallback(
			$cache->makeKey( 'throttle_override', $action, $hexIp ),
			$cache::TTL_HOUR,
			function ( $cValue, &$ttl, &$setOpts, $asOf ) use ( $hexIp, $action ) {
				$dbr = wfGetDB( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );

				$expiry = $dbr->selectField(
					'throttle_override',
					'thr_expiry',
					ThrottleOverrideHooks::makeConds( $dbr, $hexIp, $action ),
					'ThrottleOverrideHooks::onPingLimiter',
					[ 'ORDER BY' => 'thr_expiry DESC' ]
				);

				// Its tempting to set the TTL to match the expiration we
				// found in the DB, but since the record is editable and we do
				// not purge every key in the range when it changes we will
				// just leave the default cache time alone. The exception to
				// this rule is when we are caching a row which will expire in
				// less than the default TTL.
				// NOTE: this means that changes to an existing record may not
				// effect all IPs in the range equally until the default cache
				// period has elapsed.
				if ( $expiry !== false ) {
					// An override exists; do not cache for more than the
					// override's current-time-left
					$nowUnix = time();
					$overrideCTL = wfTimestamp( TS_UNIX, $expiry ) - $nowUnix;
					$ttl = min( $ttl, max( $overrideCTL, 1 ) );
				}

				// If we return false the value will not be cached
				return ( $expiry === false ) ? self::NO_OVERRIDE : $expiry;
			}
		);

		if ( $expiry === self::NO_OVERRIDE ) {
			// We checked the database and found no record
			return true;
		} elseif ( wfTimestamp( TS_UNIX, $expiry ) > time() ) {
			// Valid exemption. Disable the throttle.
			$logger = LoggerFactory::getInstance( 'throttleOverride' );
			$logger->info( 'User {user} (ip: {ip}) exempted from throttle {action}', [
				'user' => $user,
				'ip' => $ip,
				'action' => $action,
			] );

			$result = false;
			return false;
		} elseif ( $expiry !== false ) {
			// Expired exemption. Delete it from the DB.
			$dbw = wfGetDB( DB_MASTER );
			$dbw->delete(
				'throttle_override',
				self::makeConds( $dbw, $hexIp, $action ),
				__METHOD__
			);
		}

		return true;
	}

	/**
	 * Make SQL query conditions.
	 *
	 * @param \Wikimedia\Rdbms\Database $db Database
	 * @param string $hexIp IP address in hex string format
	 * @param string $action Throttle action
	 * @return array Conditions
	 */
	private static function makeConds( $db, $hexIp, $action ) {
		$quotedIp = $db->addQuotes( $hexIp );
		return [
			"thr_range_start <= $quotedIp",
			"thr_range_end >= $quotedIp",
			'thr_type' . $db->buildLike(
				$db->anyString(), $action, $db->anyString() ),
		];
	}

	/**
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$updater->addExtensionTable(
			'throttle_override',
			__DIR__ . '/patches/table.sql'
		);
		$updater->addExtensionIndex(
			'throttle_override',
			'thr_expiry',
			__DIR__ . '/patches/expiry_index.sql'
		);
		$updater->addExtensionField(
			'throttle_override',
			'thr_target',
			__DIR__ . '/patches/patch-thr_target.sql'
		);

		return true;
	}
}
