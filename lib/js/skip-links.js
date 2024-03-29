/**
 * This file fixes the browser bug for skip-links: While the visual focus of the browser shifts to the element being linked to, the input focus stays where it was.
 * Affects Internet Explorer and Chrome
 * https://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
 *
 * @package Power\JS
 * @author CoreEngine
 */

/**
 * Callback to fix skip links focus.
 *
 * @since 2.2.0
 */
function ga_skiplinks() {
	'use strict';
	var fragmentID = location.hash.substring( 1 );
	if ( ! fragmentID ) {
		return;
	}

	var element = document.getElementById( fragmentID );
	if ( element ) {
		if ( false === /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
			element.tabIndex = -1;
		}
		element.focus();
	}
}

if ( window.addEventListener ) {
	window.addEventListener( 'hashchange', ga_skiplinks, false );
} else { // IE8 and earlier.
	window.attachEvent( 'onhashchange', ga_skiplinks );
}
