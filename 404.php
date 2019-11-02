<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Templates
 * @author  Core Engine / www.daniellane.eu
 * @license GPL-2.0-or-later
 * @link    https://
 */

// Remove default loop.
remove_action( 'power_loop', 'power_do_loop' );

add_action( 'power_loop', 'power_404' );
/**
 * This function outputs a 404 "Not Found" error message.
 *
 * @since 1.6
 */
function power_404() {

	power_markup(
		[
			'open'    => '<article class="entry">',
			'context' => 'entry-404',
		]
	);

	power_markup(
		[
			'open'    => '<h1 %s>',
			'close'   => '</h1>',
			'content' => apply_filters( 'power_404_entry_title', __( 'Not found, error 404', 'power' ) ),
			'context' => 'entry-title',
		]
	);

	$power_404_content = sprintf(
		/* translators: %s: URL for current website. */
		__( 'The page you are looking for no longer exists. Perhaps you can return back to the <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', 'power' ),
		esc_url( trailingslashit( home_url() ) )
	);

	$power_404_content = sprintf( '<p>%s</p>', $power_404_content );

	/**
	 * The 404 content (wrapped in paragraph tags).
	 *
	 * @since 2.2.0
	 *
	 * @param string $power_404_content The content.
	 */
	$power_404_content = apply_filters( 'power_404_entry_content', $power_404_content );

	power_markup(
		[
			'open'    => '<div %s>',
			'close'   => '</div>',
			'content' => $power_404_content . get_search_form( 0 ),
			'context' => 'entry-content',
		]
	);

	power_markup(
		[
			'close'   => '</article>',
			'context' => 'entry-404',
		]
	);

}

power();