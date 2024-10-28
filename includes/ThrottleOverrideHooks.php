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

// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

use MediaWiki\Auth\Hook\ExemptFromAccountCreationThrottleHook;
use MediaWiki\Context\RequestContext;
use MediaWiki\Hook\SetupAfterCacheHook;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\SpecialPage\Hook\SpecialPage_initListHook;
use MediaWiki\User\Hook\PingLimiterHook;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

class ThrottleOverrideHooks implements
	PingLimiterHook,
	ExemptFromAccountCreationThrottleHook,
	SetupAfterCacheHook,
	SpecialPage_initListHook
{

	private const NO_OVERRIDE = -1;

	private WANObjectCache $cache;

	public function __construct(
		WANObjectCache $cache
	) {
		$this->cache = $cache;
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public function onExemptFromAccountCreationThrottle( $ip ) {
		$result = false;
		$user = RequestContext::getMain()->getUser();
		return $this->doPingLimiter( $user, 'actcreate', $result, $ip );
	}

	/**
	 * @throws InvalidArgumentException If $action is invalid
	 *
	 * @param User $user
	 * @param string $action
	 * @param bool &$result
	 * @param int $incrBy
	 *
	 * @return bool
	 */
	public function onPingLimiter( $user, $action, &$result, $incrBy ) {
		return $this->doPingLimiter( $user, $action, $result );
	}

	/**
	 * @param User $user
	 * @param string $action
	 * @param bool &$result
	 * @param null|string $ip
	 *
	 * @return bool
	 */
	public function doPingLimiter( $user, $action, &$result, $ip = null ) {
		global $wgRateLimits, $wgThrottleOverrideCentralWiki;

		if ( $action !== 'actcreate' && !isset( $wgRateLimits[$action] ) ) {
			return true;
		}

		if ( $user->isAnon() && IPUtils::isValid( $user->getName() ) ) {
			$ip = $user->getName();
		} elseif ( $ip === null ) {
			$ip = RequestContext::getMain()->getRequest()->getIP();
		}
		$hexIp = IPUtils::toHex( $ip );

		$fname = __METHOD__;
		$expiry = $this->cache->getWithSetCallback(
			$this->cache->makeGlobalKey(
				'throttle_override',
				$wgThrottleOverrideCentralWiki,
				$action,
				$hexIp
			),
			$this->cache::TTL_HOUR,
			static function ( $cValue, &$ttl, &$setOpts, $asOf ) use ( $hexIp, $action, $fname ) {
				$dbr = ThrottleOverrideUtils::getCentralDB( DB_REPLICA );
				$setOpts += Database::getCacheSetOptions( $dbr );

				$expiry = $dbr->newSelectQueryBuilder()
					->select( 'thr_expiry' )
					->from( 'throttle_override' )
					->where( [
						$dbr->expr( 'thr_range_start', '<=', $hexIp ),
						$dbr->expr( 'thr_range_end', '>=', $hexIp ),
						$dbr->expr( 'thr_expiry', '>', $dbr->timestamp() ),
						$dbr->expr( 'thr_type', IExpression::LIKE,
							new LikeValue( $dbr->anyString(), $action, $dbr->anyString() ) ),
					] )
					->orderBy( 'thr_expiry', SelectQueryBuilder::SORT_DESC )
					->caller( $fname )
					->fetchField();

				if ( $expiry !== false ) {
					// An override exists; cache for the override's
					// current-time-left. Cache will be purged via checkKey
					// updates on record modification. Avoid "0" (infinite)
					// and negative numbers for sanity.
					$ttl = max( (int)wfTimestamp( TS_UNIX, $expiry ) - time(), 1 );
				}

				// If we return false the value will not be cached
				return ( $expiry === false ) ? self::NO_OVERRIDE : $expiry;
			},
			[
				'checkKeys' => [ ThrottleOverrideUtils::getBucketKey( $this->cache, $ip ) ]
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

	public function onSetupAfterCache() {
		global $wgThrottleOverrideCentralWiki;
		if ( $wgThrottleOverrideCentralWiki === false ) {
			$wgThrottleOverrideCentralWiki = WikiMap::getCurrentWikiId();
		}
	}

	public function onSpecialPage_initList( &$specialPages ) {
		if ( ThrottleOverrideUtils::isCentralWiki() ) {
			$specialPages['OverrideThrottle'] = [
				'class' => SpecialOverrideThrottle::class,
				'services' => [
					'ContentLanguage',
					'JobQueueGroup',
					'DBLoadBalancerFactory',
					'MainWANObjectCache',
				],
			];
			$specialPages['ThrottleOverrideList'] = [
				'class' => SpecialThrottleOverrideList::class,
				'services' => [
					'CommentFormatter',
					'LinkRenderer',
				],
			];
		}
	}
}
