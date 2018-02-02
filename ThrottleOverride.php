<?php
if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'ThrottleOverride' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['OverrideThrottle'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['OverrideThrottleAlias'] = __DIR__ . '/ThrottleOverride.i18n.alias.php';
	wfWarn(
		'Deprecated PHP entry point used for ThrottleOverride extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the ThrottleOverride extension requires MediaWiki 1.29+' );
}
