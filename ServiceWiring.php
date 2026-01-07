<?php
/**
 * Service wiring for Pastel skin
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @file
 * @ingroup Skins
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Skins\Pastel\FeatureManagement\FeatureManager;

return [
	'Pastel.FeatureManager' => static function ( MediaWikiServices $services ): FeatureManager {
		return new FeatureManager(
			$services->getUserOptionsLookup(),
			$services->getMainConfig()
		);
	},
];
