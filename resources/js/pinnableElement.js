/**
 * Pinnable element functionality for Pastel skin
 *
 * Handles moving elements between pinned and unpinned containers.
 */

const features = require( './features.js' );

/**
 * Move a pinnable element to a new container
 * @param {string} elementId ID of the element to move
 * @param {string} newContainerId ID of the target container
 */
function movePinnableElement( elementId, newContainerId ) {
	const element = document.getElementById( elementId );
	const newContainer = document.getElementById( newContainerId );

	if ( element && newContainer ) {
		newContainer.appendChild( element );
		// Remove hidden attribute if present
		element.hidden = false;
	}
}

/**
 * Handle pinnable header click
 * @param {HTMLElement} header The pinnable header element
 */
function pinnableElementClickHandler( header ) {
	const dataset = header.dataset;
	const pinnableElementId = dataset.pinnableElementId;
	const featureName = dataset.featureName;
	const pinnedContainerId = dataset.pinnedContainerId;
	const unpinnedContainerId = dataset.unpinnedContainerId;

	// Toggle the feature
	features.toggle( featureName );

	// Determine new pinned state
	const isPinned = features.isEnabled( featureName );

	// Move element to appropriate container
	const targetContainerId = isPinned ? pinnedContainerId : unpinnedContainerId;
	movePinnableElement( pinnableElementId, targetContainerId );

	// Toggle header classes
	header.classList.toggle( 'pastel-pinnable-header-pinned', isPinned );
	header.classList.toggle( 'pastel-pinnable-header-unpinned', !isPinned );

	// Update button label
	const button = header.querySelector( '.pastel-pinnable-header-toggle' );
	if ( button ) {
		const pinLabel = header.querySelector( '.pastel-pinnable-header-toggle' )
			.getAttribute( 'data-pin-label' );
		const unpinLabel = header.querySelector( '.pastel-pinnable-header-toggle' )
			.getAttribute( 'data-unpin-label' );

		button.setAttribute(
			'aria-label',
			isPinned ? unpinLabel : pinLabel
		);
	}
}

module.exports = {
	movePinnableElement: movePinnableElement,
	pinnableElementClickHandler: pinnableElementClickHandler
};
