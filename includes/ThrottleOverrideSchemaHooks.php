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

use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;

class ThrottleOverrideSchemaHooks implements LoadExtensionSchemaUpdatesHook {
	/**
	 * @param DatabaseUpdater $updater
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		$updater->addExtensionTable(
			'throttle_override',
			__DIR__ . '/../patches/table.sql'
		);
		$updater->addExtensionIndex(
			'throttle_override',
			'thr_expiry',
			__DIR__ . '/../patches/expiry_index.sql'
		);
		$updater->addExtensionField(
			'throttle_override',
			'thr_target',
			__DIR__ . '/../patches/patch-thr_target.sql'
		);
	}
}
