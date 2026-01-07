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
 * PastelComponent interface
 *
 * All Pastel skin components must implement this interface.
 * Components are responsible for generating template data for specific UI elements.
 */
interface PastelComponent {

	/**
	 * Get the template data for this component
	 *
	 * Returns an associative array that will be passed to the Mustache template.
	 * Keys should follow MediaWiki's template data naming conventions:
	 * - 'html-*' for raw HTML content (already escaped)
	 * - 'msg-*' for message keys or Message objects
	 * - 'data-*' for complex objects/component data
	 * - 'array-*' for simple arrays
	 * - 'is-*' or 'has-*' for boolean flags
	 *
	 * @return array Template data
	 */
	public function getTemplateData(): array;
}
