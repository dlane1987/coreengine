<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Templates
 * @author  Core Engine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

add_action( 'power_before_loop', 'power_do_search_title' );
/**
 * Echo the title with the search term.
 *
 * @since 1.9.0
 */
function power_do_search_title() {

	$title = sprintf( '<div class="archive-description"><h1 class="archive-title">%s %s</h1></div>', apply_filters( 'power_search_title_text', __( 'Search Results for:', 'power' ) ), get_search_query() );

	echo apply_filters( 'power_search_title_output', $title ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

}

power();