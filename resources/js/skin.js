/**
 * Main JavaScript initialization for Pastel skin
 */

const pinnableElement = require( './pinnableElement.js' );

/**
 * Initialize pinnable elements
 */
function initPinnableElements() {
	// Find all pinnable headers and attach click handlers
	const headers = document.querySelectorAll( '.pastel-pinnable-header' );

	headers.forEach( function ( header ) {
		const toggleButton = header.querySelector( '.pastel-pinnable-header-toggle' );

		if ( toggleButton ) {
			toggleButton.addEventListener( 'click', function () {
				pinnableElement.pinnableElementClickHandler( header );
			} );
		}
	} );
}

/**
 * Initialize toolbar buttons for unpinned elements
 */
function initToolbarButtons() {
	const toolbarButtons = document.querySelectorAll( '.pastel-toolbar-button' );

	toolbarButtons.forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			const targetId = button.getAttribute( 'data-target' );
			const targetElement = document.getElementById( targetId );

			if ( targetElement ) {
				// Toggle visibility
				const isVisible = !targetElement.hidden;
				targetElement.hidden = isVisible;

				// Update button state
				button.classList.toggle( 'pastel-toolbar-button-active', !isVisible );
			}
		} );
	} );
}

/**
 * Initialize user dropdown menu
 */
function initUserDropdown() {
	const dropdown = document.querySelector( '.pastel-user-dropdown' );
	if ( !dropdown ) {
		return;
	}

	const button = dropdown.querySelector( '.pastel-user-button' );
	const menu = dropdown.querySelector( '.pastel-user-dropdown-menu' );

	if ( !button || !menu ) {
		return;
	}

	// Toggle dropdown on click
	button.addEventListener( 'click', function ( e ) {
		e.stopPropagation();
		dropdown.classList.toggle( 'is-open' );
	} );

	// Close dropdown when clicking outside
	document.addEventListener( 'click', function ( e ) {
		if ( !dropdown.contains( e.target ) ) {
			dropdown.classList.remove( 'is-open' );
		}
	} );

	// Prevent dropdown from closing when clicking inside
	menu.addEventListener( 'click', function ( e ) {
		e.stopPropagation();
	} );
}

/**
 * Ensure search results info is always visible
 */
function initSearchResultsInfo() {
	// Check if we're on a search page
	if ( !document.body.classList.contains( 'mw-special-Search' ) ) {
		return;
	}

	let resultsInfo = document.querySelector( '.results-info' );

	// If element doesn't exist, create it
	if ( !resultsInfo ) {
		const searchTopTable = document.getElementById( 'mw-search-top-table' );
		if ( searchTopTable ) {
			resultsInfo = document.createElement( 'div' );
			resultsInfo.className = 'results-info';
			resultsInfo.setAttribute( 'data-mw-num-results-offset', '0' );
			resultsInfo.setAttribute( 'data-mw-num-results-total', '0' );
			resultsInfo.innerHTML = '결과 <strong>0</strong>개 중 <strong>0</strong>개';
			searchTopTable.appendChild( resultsInfo );
		}
	} else if ( resultsInfo.textContent.trim() === '' ) {
		// If element exists but is empty, add default text
		resultsInfo.setAttribute( 'data-mw-num-results-offset', '0' );
		resultsInfo.setAttribute( 'data-mw-num-results-total', '0' );
		resultsInfo.innerHTML = '결과 <strong>0</strong>개 중 <strong>0</strong>개';
	}
}

/**
 * Main initialization function
 */
function init() {
	initPinnableElements();
	initToolbarButtons();
	initUserDropdown();
	initSearchResultsInfo();
}

// Initialize when DOM is ready
if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', init );
} else {
	init();
}

module.exports = {
	init: init
};
