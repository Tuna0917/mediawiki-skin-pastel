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
use Config;

/**
 * Component for rendering the Table of Contents
 *
 * Generates a pinnable table of contents from page sections,
 * with support for collapsible subsections and active section highlighting.
 */
class PastelComponentTableOfContents implements PastelComponent {

	/** @var array */
	private $tocData;

	/** @var MessageLocalizer */
	private $localizer;

	/** @var Config */
	private $config;

	/** @var FeatureManager */
	private $featureManager;

	/** @var bool */
	private $isPinned;

	/**
	 * @param array $tocData TOC data from parent skin's getTemplateData()
	 * @param MessageLocalizer $localizer
	 * @param Config $config
	 * @param FeatureManager $featureManager
	 */
	public function __construct(
		array $tocData,
		MessageLocalizer $localizer,
		Config $config,
		FeatureManager $featureManager
	) {
		$this->tocData = $tocData;
		$this->localizer = $localizer;
		$this->config = $config;
		$this->featureManager = $featureManager;
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_TOC_PINNED );
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$sections = $this->tocData['array-sections'] ?? [];

		// Don't show TOC if there aren't enough sections
		if ( !$this->featureManager->shouldShowToc( $this->tocData ) ) {
			return [];
		}

		// Enhance sections with additional data for collapsible subsections
		$sections = $this->enhanceSections( $sections );

		// Create pinnable header
		$pinnableHeader = new PastelComponentPinnableHeader(
			$this->localizer,
			$this->isPinned,
			Constants::COMPONENT_TOC_ID,
			Constants::FEATURE_TOC_PINNED,
			$this->localizer->msg( 'pastel-toc-label' )->text(),
			'h2',
			Constants::TOC_PINNED_CONTAINER_ID,
			Constants::TOC_UNPINNED_CONTAINER_ID
		);

		// Create pinnable element wrapper
		$pinnableElement = new PastelComponentPinnableElement(
			Constants::COMPONENT_TOC_ID,
			$this->isPinned
		);

		$collapseCount = $this->config->get( Constants::CONFIG_KEY_TOC_COLLAPSE_COUNT );

		return $pinnableElement->getTemplateData() + [
			'id' => Constants::COMPONENT_TOC_ID,
			'array-sections' => $sections,
			'number-section-count' => count( $sections ),
			'is-collapse-sections-enabled' => count( $sections ) > $collapseCount,
			'data-pinnable-header' => $pinnableHeader->getTemplateData(),
			'is-pinned' => $this->isPinned,
			'msg-label' => $this->localizer->msg( 'pastel-toc-label' )->text(),
		];
	}

	/**
	 * Enhance sections with additional data for rendering
	 *
	 * @param array $sections
	 * @return array
	 */
	private function enhanceSections( array $sections ): array {
		$enhanced = [];
		$sectionStack = [];

		foreach ( $sections as $i => $section ) {
			$toclevel = $section['toclevel'] ?? 1;
			$isTopLevel = $toclevel === 1;

			// Determine if this is a parent section (has children)
			$isParent = false;
			if ( isset( $sections[$i + 1] ) ) {
				$nextLevel = $sections[$i + 1]['toclevel'] ?? 1;
				$isParent = $nextLevel > $toclevel;
			}

			// Add toggle button label for parent sections
			$toggleLabel = '';
			if ( $isParent && $isTopLevel ) {
				$toggleLabel = $this->localizer->msg( 'pastel-toc-toggle-button-label' )
					->rawParams( $section['line'] ?? '' )
					->escaped();
			}

			$enhanced[] = $section + [
				'is-top-level-section' => $isTopLevel,
				'is-parent-section' => $isParent,
				'pastel-button-label' => $toggleLabel,
				'toclevel' => $toclevel,
			];
		}

		return $enhanced;
	}
}
