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
use MessageLocalizer;

/**
 * Component for rendering pinnable element headers with pin/unpin buttons
 */
class PastelComponentPinnableHeader implements PastelComponent {

	/** @var MessageLocalizer */
	private $localizer;

	/** @var bool */
	private $isPinned;

	/** @var string */
	private $elementId;

	/** @var string */
	private $featureName;

	/** @var string */
	private $label;

	/** @var string */
	private $labelTagName;

	/** @var string|null */
	private $pinnedContainerId;

	/** @var string|null */
	private $unpinnedContainerId;

	/**
	 * @param MessageLocalizer $localizer
	 * @param bool $isPinned Whether the element is currently pinned
	 * @param string $elementId ID of the pinnable element
	 * @param string $featureName Feature name for preference storage
	 * @param string $label Header label text
	 * @param string $labelTagName HTML tag for label (e.g., 'h2', 'div')
	 * @param string|null $pinnedContainerId ID of pinned container
	 * @param string|null $unpinnedContainerId ID of unpinned container
	 */
	public function __construct(
		MessageLocalizer $localizer,
		bool $isPinned,
		string $elementId,
		string $featureName,
		string $label,
		string $labelTagName = 'h2',
		?string $pinnedContainerId = null,
		?string $unpinnedContainerId = null
	) {
		$this->localizer = $localizer;
		$this->isPinned = $isPinned;
		$this->elementId = $elementId;
		$this->featureName = $featureName;
		$this->label = $label;
		$this->labelTagName = $labelTagName;
		$this->pinnedContainerId = $pinnedContainerId;
		$this->unpinnedContainerId = $unpinnedContainerId;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$pinMsg = $this->localizer->msg( 'pastel-pin-element-label' )->text();
		$unpinMsg = $this->localizer->msg( 'pastel-unpin-element-label' )->text();

		return [
			'is-pinned' => $this->isPinned,
			'label' => $this->label,
			'label-tag-name' => $this->labelTagName,
			'pin-label' => $pinMsg,
			'unpin-label' => $unpinMsg,
			'data-pinnable-element-id' => $this->elementId,
			'data-feature-name' => $this->featureName,
			'data-pinned-container-id' => $this->pinnedContainerId ?? '',
			'data-unpinned-container-id' => $this->unpinnedContainerId ?? '',
			'html-userlangattributes' => '',
		];
	}
}
