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

// Extension information
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Throttle Override',
	'author' => 'Tyler Romeo',
	'url' => 'https://www.mediawiki.org/wiki/Extension:ThrottleOverride',
	'descriptionmsg' => 'throttleoverride-desc',
	'version' => 0.5,
);

// Hooks and classes
$wgAutoloadClasses['ThrottleOverrideHooks'] = __DIR__ . '/ThrottleOverride.hooks.php';
$wgAutoloadClasses['SpecialOverrideThrottle'] = __DIR__ . '/SpecialOverrideThrottle.php';
$wgAutoloadClasses['SpecialThrottleOverrideList'] = __DIR__ . '/SpecialThrottleOverrideList.php';
$wgAutoloadClasses['ThrottleOverridePager'] = __DIR__ . '/SpecialThrottleOverrideList.php';

$wgSpecialPages['OverrideThrottle'] = 'SpecialOverrideThrottle';
$wgSpecialPages['ThrottleOverrideList'] = 'SpecialThrottleOverrideList';
$wgSpecialPageGroups['OverrideThrottle'] = 'users';

Hooks::register( 'PingLimiter', 'ThrottleOverrideHooks::onPingLimiter' );
Hooks::register( 'ExemptFromAccountCreationThrottle', 'ThrottleOverrideHooks::onExemptFromAccountCreationThrottle' );
Hooks::register( 'LoadExtensionSchemaUpdates', 'ThrottleOverrideHooks::onLoadExtensionSchemaUpdates' );

$wgExtensionMessagesFiles['OverrideThrottle'] = __DIR__ . '/ThrottleOverride.i18n.php';
$wgExtensionMessagesFiles['OverrideThrottleAlias'] = __DIR__ . '/ThrottleOverride.i18n.alias.php';
