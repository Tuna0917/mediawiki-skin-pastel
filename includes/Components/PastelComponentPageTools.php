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
 * Component for rendering page tools (edit, history, etc.)
 *
 * Displays page action tools with pinning support.
 */
class PastelComponentPageTools implements PastelComponent {

	/** @var MessageLocalizer */
	private $localizer;

	/** @var FeatureManager */
	private $featureManager;

	/** @var array */
	private $toolsData;

	/** @var bool */
	private $isPinned;

	/**
	 * @param MessageLocalizer $localizer
	 * @param FeatureManager $featureManager
	 * @param array $toolsData Tools portlet data (views, actions, etc.)
	 */
	public function __construct(
		MessageLocalizer $localizer,
		FeatureManager $featureManager,
		array $toolsData
	) {
		$this->localizer = $localizer;
		$this->featureManager = $featureManager;
		$this->toolsData = $toolsData;
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_PAGE_TOOLS_PINNED );
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		// Create pinnable header
		$pinnableHeader = new PastelComponentPinnableHeader(
			$this->localizer,
			$this->isPinned,
			Constants::COMPONENT_PAGE_TOOLS_ID,
			Constants::FEATURE_PAGE_TOOLS_PINNED,
			$this->localizer->msg( 'pastel-page-tools-label' )->text(),
			'div',
			Constants::PAGE_TOOLS_PINNED_CONTAINER_ID,
			Constants::PAGE_TOOLS_UNPINNED_CONTAINER_ID
		);

		// Create pinnable element wrapper
		$pinnableElement = new PastelComponentPinnableElement(
			Constants::COMPONENT_PAGE_TOOLS_ID,
			$this->isPinned
		);

		return $pinnableElement->getTemplateData() + [
			'id' => Constants::COMPONENT_PAGE_TOOLS_ID,
			'data-pinnable-header' => $pinnableHeader->getTemplateData(),
			'array-tools' => $this->toolsData,
			'is-pinned' => $this->isPinned,
			'msg-label' => $this->localizer->msg( 'pastel-page-tools-label' )->text(),
		];
	}
}
