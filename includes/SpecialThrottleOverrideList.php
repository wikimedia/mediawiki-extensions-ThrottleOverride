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

use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\FormSpecialPage;

/**
 * Special page for viewing the list of current throttle overrides
 */
class SpecialThrottleOverrideList extends FormSpecialPage {
	private CommentFormatter $commentFormatter;
	private LinkRenderer $linkRenderer;

	public function __construct(
		CommentFormatter $commentFormatter,
		LinkRenderer $linkRenderer
	) {
		parent::__construct( 'ThrottleOverrideList' );
		$this->commentFormatter = $commentFormatter;
		$this->linkRenderer = $linkRenderer;
	}

	/** @inheritDoc */
	public function getMessagePrefix() {
		return 'throttleoverride-list';
	}

	/** @inheritDoc */
	public function getFormFields() {
		$config = $this->getConfig();
		$throttleTypes = array_keys( array_filter( $config->get( 'ThrottleOverrideTypes' ) ) );
		$throttleTypes = array_merge( [ 'all' ], $throttleTypes );

		$throttles = [];
		$rateLimits = $config->get( MainConfigNames::RateLimits );
		foreach ( $throttleTypes as $type ) {
			if ( $type == 'all' || $type == 'actcreate' || isset( $rateLimits[$type] ) ) {
				// For grepping. The following messages are used here:
				// throttleoverride-types-all
				// throttleoverride-types-actcreate, throttleoverride-types-edit,
				// throttleoverride-types-moves, throttleoverride-types-mailpassword,
				// throttleoverride-types-emailuser
				$throttles[$this->msg( "throttleoverride-types-$type" )->text()] = $type;
			}
		}

		return [
			'ThrottleType' => [
				'type' => 'select',
				'default' => 'all',
				'label-message' => 'throttleoverride-list-throttletype',
				'options' => $throttles
			]
		];
	}

	/** @inheritDoc */
	public function alterForm( HTMLForm $form ) {
		$form->setMethod( 'get' );
		$form->setWrapperLegendMsg( 'throttleoverride-list-legend' );
		$form->setSubmitTextMsg( 'throttleoverride-list-search' );
	}

	/** @inheritDoc */
	public function onSubmit( array $data, ?HTMLForm $form = null ) {
		$pager = new ThrottleOverridePager( $this->commentFormatter, $this->linkRenderer, $this, [
			'throttleType' => $data['ThrottleType'],
		] );

		// Add the result as post text so it appears after the form
		if ( !$pager->getNumRows() ) {
			$form->addPostHtml( $this->msg( 'throttleoverride-list-noresults' )->escaped() );
		} else {
			$form->addPostHtml(
				$pager->getNavigationBar() .
				$pager->getBody() .
				$pager->getNavigationBar()
			);
		}

		return false;
	}

	/** @inheritDoc */
	public function getGroupName() {
		return 'users';
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}
}
