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

/** Message documentation (Message documentation)
 */
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
	'throttleoverride-types-emailuser' => 'Label for the throttle type for user emails',
);

/** German (Deutsch)
 * @author Metalhead64
 */
$messages['de'] = array(
	'overridethrottle' => 'Die Kontenerstellungsbeschränkung umgehen',
	'throttleoverride-desc' => 'Ermöglicht das Umgehen von IP-Adressbeschränkungen',
	'throttleoverride-legend' => 'Ausnahmeinformationen',
	'throttleoverride-text' => 'Gib die IP-Adresse oder den Adressbereich an, die du von bestimmten Beschränkungen ausnehmen willst und wie lange die Ausnahme gelten soll.
Für die Logbücher kann eine optionale Begründung angegeben werden.',
	'throttleoverride-ipaddress' => 'IP-Adresse oder Adressenbereich',
	'throttleoverride-success' => 'Die Ausnahme wurde angewandt.',
	'throttleoverride-types' => 'Beschränkungstypen:',
	'throttleoverride-types-actcreate' => 'Kontenerstellung',
	'throttleoverride-types-edit' => 'Seitenbearbeitungen',
	'throttleoverride-types-move' => 'Seitenverschiebungen',
	'throttleoverride-types-mailpassword' => 'Passwortwiederherstellungs-Mails',
	'throttleoverride-types-emailuser' => 'Benutzer-Mails',
);

/** French (français)
 * @author Gomoko
 */
$messages['fr'] = array(
	'overridethrottle' => 'Écraser la restriction de création de compte',
	'throttleoverride-desc' => 'Permet l’écrasement des restrictions d’adresses IP',
	'throttleoverride-legend' => 'Information sur l’exemption',
	'throttleoverride-text' => 'Entrez l’adresse IP ou la plage que vous voulez exempter de certaines restrictions, et la durée de vie de l’exemption.
Un motif facultatif peut être fourni pour les journaux.',
	'throttleoverride-ipaddress' => 'Adresse IP ou plage',
	'throttleoverride-success' => 'L’exemption a été appliquée.',
	'throttleoverride-types' => 'Types de restriction:',
	'throttleoverride-types-actcreate' => 'Création de compte',
	'throttleoverride-types-edit' => 'Modifications de page',
	'throttleoverride-types-move' => 'Déplacements de page',
	'throttleoverride-types-mailpassword' => 'Courriels de récupération de mot de passe',
	'throttleoverride-types-emailuser' => 'Courriels utilisateur',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'throttleoverride-types-actcreate' => 'ఖాతా సృష్టింపు',
	'throttleoverride-types-edit' => 'పేజీ మార్పులు',
	'throttleoverride-types-move' => 'పేజీల తరలింపులు',
);
