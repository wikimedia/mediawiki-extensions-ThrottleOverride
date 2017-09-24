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

class ThrottleOverrideHooks {
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

		$dbr = wfGetDB( DB_REPLICA );

		if ( $user->isAnon() && IP::isValid( $user->getName() ) ) {
			$ip = $user->getName();
		} elseif ( $ip === null ) {
			$ip = RequestContext::getMain()->getRequest()->getIP();
		}

		$quotedIp = $dbr->addQuotes( IP::toHex( $ip ) );
		$conds = [
			"thr_range_start <= $quotedIp",
			"thr_range_end >= $quotedIp",
			'thr_type' . $dbr->buildLike( $dbr->anyString(), $action, $dbr->anyString() )
		];

		$expiry = $dbr->selectField(
			'throttle_override',
			'thr_expiry',
			$conds,
			__METHOD__,
			[ 'ORDER BY' => 'thr_expiry DESC' ]
		);

		if ( $expiry > wfTimestampNow() ) {
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
			wfGetDB( DB_MASTER )->delete(
				'throttle_override',
				$conds,
				__METHOD__
			);
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
}
