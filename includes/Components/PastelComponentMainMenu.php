<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @file
 * @ingroup Skins
 */

namespace MediaWiki\Skins\Pastel\Components;

use MediaWiki\Skins\Pastel\Constants;
use MediaWiki\Skins\Pastel\FeatureManagement\FeatureManager;
use MessageLocalizer;

/**
 * Component for rendering the main navigation menu
 *
 * Displays the sidebar navigation with pinning support.
 */
class PastelComponentMainMenu implements PastelComponent {

	/** @var MessageLocalizer */
	private $localizer;

	/** @var FeatureManager */
	private $featureManager;

	/** @var array */
	private $menuData;

	/** @var bool */
	private $isPinned;

	/**
	 * @param MessageLocalizer $localizer
	 * @param FeatureManager $featureManager
	 * @param array $menuData Menu portlet data from sidebar
	 */
	public function __construct(
		MessageLocalizer $localizer,
		FeatureManager $featureManager,
		array $menuData
	) {
		$this->localizer = $localizer;
		$this->featureManager = $featureManager;
		$this->menuData = $menuData;
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_MAIN_MENU_PINNED );
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		// Create pinnable header
		$pinnableHeader = new PastelComponentPinnableHeader(
			$this->localizer,
			$this->isPinned,
			Constants::COMPONENT_MAIN_MENU_ID,
			Constants::FEATURE_MAIN_MENU_PINNED,
			$this->localizer->msg( 'pastel-main-menu-label' )->text(),
			'div',
			Constants::MAIN_MENU_PINNED_CONTAINER_ID,
			Constants::MAIN_MENU_UNPINNED_CONTAINER_ID
		);

		// Create pinnable element wrapper
		$pinnableElement = new PastelComponentPinnableElement(
			Constants::COMPONENT_MAIN_MENU_ID,
			$this->isPinned
		);

		return $pinnableElement->getTemplateData() + [
			'id' => Constants::COMPONENT_MAIN_MENU_ID,
			'data-pinnable-header' => $pinnableHeader->getTemplateData(),
			'data-portlets' => $this->menuData,
			'is-pinned' => $this->isPinned,
			'msg-label' => $this->localizer->msg( 'pastel-main-menu-label' )->text(),
		];
	}
}
