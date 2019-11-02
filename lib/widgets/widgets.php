<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Widgets
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

add_action( 'widgets_init', 'power_load_widgets' );
/**
 * Register widgets for use in the Power theme.
 *
 * @since 1.7.0
 */
function power_load_widgets() {

	register_widget( 'Power_Featured_Page' );
	register_widget( 'Power_Featured_Post' );
	register_widget( 'Power_User_Profile_Widget' );

}

add_action( 'load-themes.php', 'power_remove_default_widgets_from_header_right' );
/**
 * Temporary function to work around the default widgets that get added to
 * Header Right when switching themes.
 *
 * The $defaults array contains a list of the IDs of the widgets that are added
 * to the first sidebar in a new default install. If this exactly matches the
 * widgets in Header Right after switching themes, then they are removed.
 *
 * This works around a perceived WP problem for new installs.
 *
 * If a user amends the list of widgets in the first sidebar before switching to
 * a Power child theme, then this function won't do anything.
 *
 * @since 1.8.0
 *
 * @return void Return early if not just switched to a new theme.
 */
function power_remove_default_widgets_from_header_right() {

	// Some tomfoolery for a faux activation hook.
	if ( ! isset( $_REQUEST['activated'] ) || 'true' !== $_REQUEST['activated'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- No data is being processed.
		return;
	}

	$widgets  = get_option( 'sidebars_widgets' );
	$defaults = [
		0 => 'search-2',
		1 => 'recent-posts-2',
		2 => 'recent-comments-2',
		3 => 'archives-2',
		4 => 'categories-2',
		5 => 'meta-2',
	];

	if ( isset( $widgets['header-right'] ) && $defaults === $widgets['header-right'] ) {
		$widgets['header-right'] = [];
		update_option( 'sidebars_widgets', $widgets );
	}

}