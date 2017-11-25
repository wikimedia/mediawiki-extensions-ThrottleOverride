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
		global $wgRateLimits, $wgThrottleOverrideCentralWiki;

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
			$cache->makeGlobalKey(
				'throttle_override',
				$wgThrottleOverrideCentralWiki,
				$action,
				$hexIp
			),
			$cache::TTL_HOUR,
			function ( $cValue, &$ttl, &$setOpts, $asOf ) use ( $ip, $hexIp, $action ) {
				$dbr = ThrottleOverrideUtils::getCentralDB( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );

				$quotedIp = $db->addQuotes( $hexIp );
				$expiry = $dbr->selectField(
					'throttle_override',
					'thr_expiry',
					[
						"thr_range_start <= $quotedIp",
						"thr_range_end >= $quotedIp",
						'thr_expiry > ' . $dbr->addQuotes( $dbr->timestamp() ),
						'thr_type' . $dbr->buildLike(
							$dbr->anyString(), $action, $dbr->anyString() ),
					],
					'ThrottleOverrideHooks::onPingLimiter',
					[ 'ORDER BY' => 'thr_expiry DESC' ]
				);

				if ( $expiry !== false ) {
					// An override exists; cache for the override's
					// current-time-left. Cache will be purged via checkKey
					// updates on record modification. Avoid "0" (infinite)
					// and negative numbers for sanity.
					$ttl = max( wfTimestamp( TS_UNIX, $expiry ) - time(), 1 );
				}

				// If we return false the value will not be cached
				return ( $expiry === false ) ? self::NO_OVERRIDE : $expiry;
			},
			[
				'checkKeys' => [ ThrottleOverrideUtils::getBucketKey( $cache, $ip ) ]
			]
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
		}

		return true;
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

	public static function onSetupAfterCache() {
		global $wgThrottleOverrideCentralWiki;
		if ( $wgThrottleOverrideCentralWiki === false ) {
			$wgThrottleOverrideCentralWiki = wfWikiId();
		}
	}

	public static function onSpecialPageInitList( array &$specialPages ) {
		if ( ThrottleOverrideUtils::isCentralWiki() ) {
			$specialPages['OverrideThrottle'] = SpecialOverrideThrottle::class;
			$specialPages['ThrottleOverrideList'] = SpecialThrottleOverrideList::class;
		}
	}
}
