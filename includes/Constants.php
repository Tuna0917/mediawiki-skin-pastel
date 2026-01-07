<?php
/**
 * Constants used throughout the Pastel skin
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @file
 * @ingroup Skins
 */

namespace MediaWiki\Skins\Pastel;

/**
 * A namespace for Pastel constants
 */
final class Constants {

	// Feature flags for pinnable elements and user preferences
	public const FEATURE_TOC_PINNED = 'toc-pinned';
	public const FEATURE_MAIN_MENU_PINNED = 'main-menu-pinned';
	public const FEATURE_PAGE_TOOLS_PINNED = 'page-tools-pinned';
	public const FEATURE_LIMITED_WIDTH = 'limited-width';
	public const FEATURE_THEME = 'theme';
	public const FEATURE_FONT_SIZE = 'font-size';
	public const FEATURE_CLIENT_PREFERENCES = 'client-preferences';

	// Component IDs
	public const COMPONENT_TOC_ID = 'pastel-toc';
	public const COMPONENT_MAIN_MENU_ID = 'pastel-main-menu';
	public const COMPONENT_PAGE_TOOLS_ID = 'pastel-page-tools';
	public const COMPONENT_USER_PREFERENCES_ID = 'pastel-user-preferences';

	// Pinned container IDs
	public const TOC_PINNED_CONTAINER_ID = 'pastel-toc-pinned-container';
	public const TOC_UNPINNED_CONTAINER_ID = 'pastel-toc-unpinned-container';
	public const MAIN_MENU_PINNED_CONTAINER_ID = 'pastel-main-menu-pinned-container';
	public const MAIN_MENU_UNPINNED_CONTAINER_ID = 'pastel-main-menu-unpinned-container';
	public const PAGE_TOOLS_PINNED_CONTAINER_ID = 'pastel-page-tools-pinned-container';
	public const PAGE_TOOLS_UNPINNED_CONTAINER_ID = 'pastel-page-tools-unpinned-container';

	// Config keys
	public const CONFIG_KEY_TOC_COLLAPSE_COUNT = 'PastelTableOfContentsCollapseAtCount';
	public const CONFIG_KEY_RESPONSIVE = 'PastelResponsive';

	// Requirement names
	public const REQUIREMENT_TOC = 'PastelTableOfContents';
	public const REQUIREMENT_MAIN_MENU = 'PastelMainMenu';
	public const REQUIREMENT_PAGE_TOOLS = 'PastelPageTools';
	public const REQUIREMENT_LOGGED_IN = 'PastelLoggedIn';

	// Default values
	public const DEFAULT_TOC_COLLAPSE_COUNT = 3;
	public const DEFAULT_SIDEBAR_VISIBLE = true;

	// Theme options
	public const THEME_LIGHT = 'light';
	public const THEME_DARK = 'dark';
	public const THEME_AUTO = 'auto';

	// Font size options
	public const FONT_SIZE_SMALL = 'small';
	public const FONT_SIZE_MEDIUM = 'medium';
	public const FONT_SIZE_LARGE = 'large';
}
