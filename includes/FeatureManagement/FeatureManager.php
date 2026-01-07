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

namespace MediaWiki\Skins\Pastel\FeatureManagement;

use MediaWiki\Skins\Pastel\Constants;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserIdentity;
use Config;

/**
 * Feature manager for the Pastel skin
 *
 * Manages feature flags and user preferences for pinnable elements,
 * theme selection, and other customizable features.
 */
class FeatureManager {

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var Config */
	private $config;

	/**
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param Config $config
	 */
	public function __construct(
		UserOptionsLookup $userOptionsLookup,
		Config $config
	) {
		$this->userOptionsLookup = $userOptionsLookup;
		$this->config = $config;
	}

	/**
	 * Check if a feature is enabled for the given user
	 *
	 * @param string $feature Feature name (use Constants::FEATURE_*)
	 * @param UserIdentity|null $user User to check (null = current user)
	 * @return bool
	 */
	public function isFeatureEnabled( string $feature, ?UserIdentity $user = null ): bool {
		if ( $user === null ) {
			$user = \RequestContext::getMain()->getUser();
		}

		switch ( $feature ) {
			case Constants::FEATURE_TOC_PINNED:
				return $this->userOptionsLookup->getBoolOption(
					$user,
					'pastel-' . $feature,
					true // Default: TOC is pinned
				);

			case Constants::FEATURE_MAIN_MENU_PINNED:
				return $this->userOptionsLookup->getBoolOption(
					$user,
					'pastel-' . $feature,
					true // Default: Main menu is pinned
				);

			case Constants::FEATURE_PAGE_TOOLS_PINNED:
				return $this->userOptionsLookup->getBoolOption(
					$user,
					'pastel-' . $feature,
					false // Default: Page tools are unpinned
				);

			case Constants::FEATURE_LIMITED_WIDTH:
				return $this->userOptionsLookup->getBoolOption(
					$user,
					'pastel-' . $feature,
					true // Default: Limited width enabled
				);

			default:
				return false;
		}
	}

	/**
	 * Get the current theme preference
	 *
	 * @param UserIdentity|null $user
	 * @return string One of Constants::THEME_*
	 */
	public function getTheme( ?UserIdentity $user = null ): string {
		if ( $user === null ) {
			$user = \RequestContext::getMain()->getUser();
		}

		$theme = $this->userOptionsLookup->getOption(
			$user,
			'pastel-' . Constants::FEATURE_THEME,
			Constants::THEME_AUTO
		);

		// Validate theme value
		$validThemes = [
			Constants::THEME_LIGHT,
			Constants::THEME_DARK,
			Constants::THEME_AUTO
		];

		return in_array( $theme, $validThemes ) ? $theme : Constants::THEME_AUTO;
	}

	/**
	 * Get the current font size preference
	 *
	 * @param UserIdentity|null $user
	 * @return string One of Constants::FONT_SIZE_*
	 */
	public function getFontSize( ?UserIdentity $user = null ): string {
		if ( $user === null ) {
			$user = \RequestContext::getMain()->getUser();
		}

		$fontSize = $this->userOptionsLookup->getOption(
			$user,
			'pastel-' . Constants::FEATURE_FONT_SIZE,
			Constants::FONT_SIZE_MEDIUM
		);

		// Validate font size value
		$validSizes = [
			Constants::FONT_SIZE_SMALL,
			Constants::FONT_SIZE_MEDIUM,
			Constants::FONT_SIZE_LARGE
		];

		return in_array( $fontSize, $validSizes ) ? $fontSize : Constants::FONT_SIZE_MEDIUM;
	}

	/**
	 * Get body CSS classes for all enabled features
	 *
	 * These classes are used by JavaScript and CSS to toggle features.
	 * Format: pastel-feature-{feature-name}-clientpref-{0|1}
	 *
	 * @param UserIdentity|null $user
	 * @return array Array of CSS class names
	 */
	public function getFeatureBodyClasses( ?UserIdentity $user = null ): array {
		$classes = [];

		// Pinnable features
		$pinnableFeatures = [
			Constants::FEATURE_TOC_PINNED,
			Constants::FEATURE_MAIN_MENU_PINNED,
			Constants::FEATURE_PAGE_TOOLS_PINNED,
			Constants::FEATURE_LIMITED_WIDTH,
		];

		foreach ( $pinnableFeatures as $feature ) {
			$enabled = $this->isFeatureEnabled( $feature, $user );
			$suffix = $enabled ? '1' : '0';
			$classes[] = "pastel-feature-{$feature}-clientpref-{$suffix}";
		}

		// Theme preference
		$theme = $this->getTheme( $user );
		$classes[] = "pastel-feature-" . Constants::FEATURE_THEME . "-clientpref-{$theme}";

		// Font size preference
		$fontSize = $this->getFontSize( $user );
		$classes[] = "pastel-feature-" . Constants::FEATURE_FONT_SIZE . "-clientpref-{$fontSize}";

		return $classes;
	}

	/**
	 * Check if Table of Contents should be shown
	 *
	 * @param array $tocData TOC data from parent skin
	 * @return bool
	 */
	public function shouldShowToc( array $tocData ): bool {
		$sections = $tocData['array-sections'] ?? [];
		$minSections = $this->config->get( Constants::CONFIG_KEY_TOC_COLLAPSE_COUNT );

		return count( $sections ) >= $minSections;
	}
}
