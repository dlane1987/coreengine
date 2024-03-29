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

// Output primary sidebar structure.
power_markup(
	[
		'open'    => '<aside %s>' . power_sidebar_title( 'sidebar' ),
		'context' => 'sidebar-primary',
	]
);

/**
 * Fires immediately after the Primary Sidebar opening markup.
 *
 * @since ???
 */
do_action( 'power_before_sidebar_widget_area' );

/**
 * Fires to display the main Primary Sidebar content.
 *
 * @since ???
 */
do_action( 'power_sidebar' );

/**
 * Fires immediately before the Primary Sidebar closing markup.
 *
 * @since ???
 */
do_action( 'power_after_sidebar_widget_area' );

// End .sidebar-primary.
power_markup(
	[
		'close'   => '</aside>',
		'context' => 'sidebar-primary',
	]
);
