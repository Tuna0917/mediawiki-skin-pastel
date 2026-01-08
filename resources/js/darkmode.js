/**
 * Pastel Skin - Dark Mode Toggle
 */
( function () {
	'use strict';

	const STORAGE_KEY = 'pastel-theme';
	const THEME_DARK = 'dark';
	const THEME_LIGHT = 'light';

	/**
	 * Get the current theme from storage or system preference
	 * @return {string|null}
	 */
	function getStoredTheme() {
		try {
			return localStorage.getItem( STORAGE_KEY );
		} catch ( e ) {
			return null;
		}
	}

	/**
	 * Save theme preference to storage
	 * @param {string} theme
	 */
	function setStoredTheme( theme ) {
		try {
			localStorage.setItem( STORAGE_KEY, theme );
		} catch ( e ) {
			// localStorage not available
		}
	}

	/**
	 * Check if system prefers dark mode
	 * @return {boolean}
	 */
	function systemPrefersDark() {
		return window.matchMedia &&
			window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
	}

	/**
	 * Get the effective theme
	 * @return {string}
	 */
	function getEffectiveTheme() {
		const stored = getStoredTheme();
		if ( stored ) {
			return stored;
		}
		return systemPrefersDark() ? THEME_DARK : THEME_LIGHT;
	}

	/**
	 * Apply theme to document
	 * @param {string} theme
	 */
	function applyTheme( theme ) {
		document.documentElement.setAttribute( 'data-theme', theme );
	}

	/**
	 * Toggle between light and dark theme
	 */
	function toggleTheme() {
		const current = getEffectiveTheme();
		const newTheme = current === THEME_DARK ? THEME_LIGHT : THEME_DARK;
		setStoredTheme( newTheme );
		applyTheme( newTheme );
	}

	/**
	 * Create the theme toggle button
	 * @return {HTMLButtonElement}
	 */
	function createToggleButton() {
		const button = document.createElement( 'button' );
		button.className = 'pastel-theme-toggle';
		button.setAttribute( 'aria-label', mw.msg( 'pastel-darkmode-toggle' ) );
		button.setAttribute( 'title', mw.msg( 'pastel-darkmode-toggle' ) );

		// Sun icon (for dark mode - click to go light)
		const sunIcon = document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' );
		sunIcon.setAttribute( 'class', 'icon-sun' );
		sunIcon.setAttribute( 'viewBox', '0 0 24 24' );
		sunIcon.setAttribute( 'fill', 'none' );
		sunIcon.setAttribute( 'stroke', 'currentColor' );
		sunIcon.setAttribute( 'stroke-width', '2' );
		sunIcon.setAttribute( 'stroke-linecap', 'round' );
		sunIcon.setAttribute( 'stroke-linejoin', 'round' );
		sunIcon.innerHTML = '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>';

		// Moon icon (for light mode - click to go dark)
		const moonIcon = document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' );
		moonIcon.setAttribute( 'class', 'icon-moon' );
		moonIcon.setAttribute( 'viewBox', '0 0 24 24' );
		moonIcon.setAttribute( 'fill', 'none' );
		moonIcon.setAttribute( 'stroke', 'currentColor' );
		moonIcon.setAttribute( 'stroke-width', '2' );
		moonIcon.setAttribute( 'stroke-linecap', 'round' );
		moonIcon.setAttribute( 'stroke-linejoin', 'round' );
		moonIcon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>';

		button.appendChild( sunIcon );
		button.appendChild( moonIcon );

		button.addEventListener( 'click', toggleTheme );

		return button;
	}

	/**
	 * Initialize dark mode
	 */
	function init() {
		// Apply stored or system theme immediately
		const storedTheme = getStoredTheme();
		if ( storedTheme ) {
			applyTheme( storedTheme );
		}

		// Create and add toggle button when DOM is ready
		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', function () {
				document.body.appendChild( createToggleButton() );
			} );
		} else {
			document.body.appendChild( createToggleButton() );
		}

		// Listen for system theme changes
		if ( window.matchMedia ) {
			window.matchMedia( '(prefers-color-scheme: dark)' ).addEventListener( 'change', function ( e ) {
				// Only auto-switch if user hasn't set a preference
				if ( !getStoredTheme() ) {
					applyTheme( e.matches ? THEME_DARK : THEME_LIGHT );
				}
			} );
		}
	}

	// Initialize
	init();

}() );
