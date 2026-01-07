/**
 * Feature toggle management for Pastel skin
 *
 * Handles client-side feature preferences using MediaWiki's client preferences API
 * or cookies for anonymous users.
 */

/**
 * Check if a feature is enabled
 * @param {string} featureName Feature name (e.g., 'toc-pinned')
 * @return {boolean}
 */
function isEnabled( featureName ) {
	const classes = document.body.classList;
	const enabledClass = 'pastel-feature-' + featureName + '-clientpref-1';
	return classes.contains( enabledClass );
}

/**
 * Toggle a feature on/off
 * @param {string} featureName Feature name
 */
function toggle( featureName ) {
	const isCurrentlyEnabled = isEnabled( featureName );
	const newValue = isCurrentlyEnabled ? '0' : '1';

	// Toggle body classes
	document.body.classList.remove(
		'pastel-feature-' + featureName + '-clientpref-0',
		'pastel-feature-' + featureName + '-clientpref-1'
	);
	document.body.classList.add(
		'pastel-feature-' + featureName + '-clientpref-' + newValue
	);

	// Save preference
	if ( mw.user.isNamed && mw.user.isNamed() ) {
		// Logged-in user: save to user preferences via API
		new mw.Api().saveOption(
			'pastel-' + featureName,
			newValue === '1' ? '1' : '0'
		);
	} else {
		// Anonymous user: save to client preferences (cookies)
		if ( mw.user.clientPrefs ) {
			mw.user.clientPrefs.set(
				'pastel-feature-' + featureName,
				newValue
			);
		}
	}
}

module.exports = {
	isEnabled: isEnabled,
	toggle: toggle
};
