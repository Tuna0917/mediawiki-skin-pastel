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

/**
 * Component for wrapping pinnable elements
 *
 * Provides the container and metadata for elements that can be pinned/unpinned.
 */
class PastelComponentPinnableElement implements PastelComponent {

	/** @var string */
	private $id;

	/** @var bool */
	private $isPinned;

	/**
	 * @param string $id Element ID
	 * @param bool $isPinned Whether the element is currently pinned
	 */
	public function __construct(
		string $id,
		bool $isPinned = true
	) {
		$this->id = $id;
		$this->isPinned = $isPinned;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return [
			'id' => $this->id,
			'is-pinned' => $this->isPinned,
		];
	}
}
