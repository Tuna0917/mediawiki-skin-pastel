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

namespace MediaWiki\Skins\Pastel\Services;

use MediaWiki\MediaWikiServices;
use MediaWiki\Skins\Pastel\FeatureManagement\FeatureManager;

/**
 * Service locator for Pastel skin services
 *
 * Provides convenient access to Pastel-specific services registered
 * in ServiceWiring.php.
 */
class PastelServices {

	/**
	 * Get the FeatureManager service
	 *
	 * @return FeatureManager
	 */
	public static function getFeatureManager(): FeatureManager {
		return MediaWikiServices::getInstance()
			->getService( 'Pastel.FeatureManager' );
	}
}
