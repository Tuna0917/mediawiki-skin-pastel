<?php

namespace MediaWiki\Skins\Pastel\Hooks;

use MediaWiki\Hook\BeforePageDisplayHook;
use OutputPage;
use Skin;

class ThemeHooks implements BeforePageDisplayHook {

	/**
	 * Add inline script to prevent FOUC (Flash of Unstyled Content) for dark mode
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		// Only apply to Pastel skin
		if ( $skin->getSkinName() !== 'pastel' ) {
			return;
		}

		// Inline script to apply theme before page renders
		// This runs immediately in <head> before body is parsed
		$inlineScript = <<<'JS'
(function(){
	var t = localStorage.getItem('pastel-theme');
	if (t) {
		document.documentElement.setAttribute('data-theme', t);
	} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
		document.documentElement.setAttribute('data-theme', 'dark');
	}
})();
JS;

		$out->addHeadItem( 'pastel-theme-init', '<script>' . $inlineScript . '</script>' );
	}
}
