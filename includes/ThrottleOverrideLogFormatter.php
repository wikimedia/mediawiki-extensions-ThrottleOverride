<?php

/**
 * MediaWiki extension to temporarily lift throttles.
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @license GPL-3.0-or-later
 */

use MediaWiki\Context\RequestContext;
use MediaWiki\Message\Message;
use MediaWiki\User\User;

/**
 * This class formats throttleoverride log entries.
 *
 */
class ThrottleOverrideLogFormatter extends LogFormatter {

	/**
	 * @return array
	 */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$context = RequestContext::getMain();
		$language = $context->getLanguage();

		// Link the target ip to the contributions page
		$targetUser = User::newFromName( $this->entry->getTarget()->getText(), false );
		$params[2] = Message::rawParam( $this->makeUserLink( $targetUser ) );

		// Build a (human-readable) list of throttle types
		$types = [];
		foreach ( explode( ',', $params[3] ) as $type ) {
			$types[] = wfMessage( "throttleoverride-log-type-{$type}" )->text();
		}
		$params[3] = $language->listToText( $types );

		// Make the timestamp human-readable.
		$params[4] = $language->formatExpiry( $params[4] );

		// The additional parameter $6 counts the number of throttle types for {{PLURAL:$6|...}} use.
		$params[5] = count( $types );
		return $params;
	}
}
