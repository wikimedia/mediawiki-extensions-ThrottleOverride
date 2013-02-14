<?php

/**
 * Internationalisation file for extension Throttle Override
 *
 * @file
 * @ingroup Extensions
 * @license GNU General Public Licence 3.0 or later
 */

$messages = array();

/**
 * English
 * @author Tyler Romeo <tylerromeo@gmail.com>
 */
$messages['en'] = array(
	'overridethrottle' => 'Override the account creation throttle',
	'throttleoverride-desc' => 'Allows overriding of IP address throttles',
	'throttleoverride-legend' => 'Exemption information',
	'throttleoverride-text' => 'Enter the IP address or range you want to exempt from certain throttles, and how long the exemption should last for.
An optional reason can be given for the logs.',
	'throttleoverride-ipaddress' => 'IP address or range',
	'throttleoverride-success' => 'The exemption was applied.',
	'throttleoverride-types' => 'Throttle types:',
	'throttleoverride-types-actcreate' => 'Account creation',
	'throttleoverride-types-edit' => 'Page edits',
	'throttleoverride-types-move' => 'Page moves',
	'throttleoverride-types-mailpassword' => 'Password recovery e-mails',
	'throttleoverride-types-emailuser' => 'User e-mails'
);

$messages['qqq'] = array(
	'overridethrottle' => 'Title for Special:OverrideThrottle',
	'throttleoverride-desc' => '{{desc}}',
	'throttleoverride-legend' => 'Label for the legend on Special:OverrideThrottle',
	'throttleoverride-text' => 'Intro text on Special:OverrideThrottle',
	'throttleoverride-ipaddress' => 'Label for the IP address field on Special:OverrideThrottle',
	'throttleoverride-success' => 'Text displayed after a successful submission on Special:OverrideThrottle',
	'throttleoverride-types' => 'Label for the types of throttles that can be overridden',
	'throttleoverride-types-actcreate' => 'Label for the throttle type for account creations',
	'throttleoverride-types-edit' => 'Label for the throttle type for page edits',
	'throttleoverride-types-move' => 'Label for the throttle type for page moves',
	'throttleoverride-types-mailpassword' => 'Label for the throttle type for password recovery requests',
	'throttleoverride-types-emailuser' => 'Label for the throttle type for user emails'
);
