<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\WidgetAreas
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

/**
 * Expedites the widget area registration process by taking common things, before / after_widget, before / after_title,
 * and doing them automatically.
 *
 * See the WP function `register_sidebar()` for the list of supports $args keys.
 *
 * A typical usage is:
 *
 * ~~~
 * power_register_widget_area(
 *     array(
 *         'id'          => 'my-sidebar',
 *         'name'        => __( 'My Sidebar', 'my-theme-text-domain' ),
 *         'description' => __( 'A description of the intended purpose or location', 'my-theme-text-domain' ),
 *     )
 * );
 * ~~~
 *
 * @since 2.1.0
 *
 * @param string|array $args Name, ID, description and other widget area arguments.
 * @return string The sidebar ID that was added.
 */
function power_register_widget_area( $args ) {

	$defaults = [
		'before_widget' => power_markup(
			[
				'open'    => '<section id="%%1$s" class="widget %%2$s"><div class="widget-wrap">',
				'context' => 'widget-wrap',
				'echo'    => false,
			]
		),
		'after_widget'  => power_markup(
			[
				'close'   => '</div></section>' . "\n",
				'context' => 'widget-wrap',
				'echo'    => false,
			]
		),
		'before_title'  => '<h4 class="widget-title widgettitle">',
		'after_title'   => "</h4>\n",
	];

	/**
	 * A filter on the default parameters used by `power_register_widget_area()`. For backward compatibility.
	 *
	 * @since 1.0.1
	 */
	$defaults = apply_filters( 'power_register_sidebar_defaults', $defaults, $args );

	/**
	 * A filter on the default parameters used by `power_register_widget_area()`.
	 *
	 * @since 2.1.0
	 */
	$defaults = apply_filters( 'power_register_widget_area_defaults', $defaults, $args );

	$args = wp_parse_args( $args, $defaults );

	return register_sidebar( $args );

}

/**
 * An alias for `power_register_widget_area()`.
 *
 * @since 1.0.1
 *
 * @param string|array $args Name, ID, description and other widget area arguments.
 * @return string The sidebar ID that was added.
 */
function power_register_sidebar( $args ) {
	return power_register_widget_area( $args );
}

add_action( 'power_setup', 'power_register_default_widget_areas' );
/**
 * Hook the callback that registers the default Power widget areas.
 *
 * @since 1.6.0
 */
function power_register_default_widget_areas() {

	// Temporarily register placeholder widget areas, so that child themes can unregister directly in functions.php.
	power_register_widget_area(
		[
			'id' => 'header-right',
		]
	);
	power_register_widget_area(
		[
			'id' => 'sidebar',
		]
	);
	power_register_widget_area(
		[
			'id' => 'sidebar-alt',
		]
	);

	// Call all final widget area registration after themes setup, so text can be translated.
	add_action( 'after_setup_theme', '_power_register_default_widget_areas_cb' );
	add_action( 'after_setup_theme', 'power_register_footer_widget_areas' );
	add_action( 'after_setup_theme', 'power_register_after_entry_widget_area' );

}

/**
 * Register the default Power widget areas, if the placeholder widget areas are still registered.
 *
 * @since 2.2.0
 */
function _power_register_default_widget_areas_cb() {

	global $wp_registered_sidebars;

	if ( isset( $wp_registered_sidebars['header-right'] ) ) {
		power_register_widget_area(
			[
				'id'               => 'header-right',
				'name'             => is_rtl() ? __( 'Header Left', 'power' ) : __( 'Header Right', 'power' ),
				'description'      => __( 'This is the header widget area. It typically appears next to the site title or logo. This widget area is not suitable to display every type of widget, and works best with a custom menu, a search form, or possibly a text widget.', 'power' ),
				'_power_builtin' => true,
			]
		);
	}

	if ( isset( $wp_registered_sidebars['sidebar'] ) ) {
		power_register_widget_area(
			[
				'id'               => 'sidebar',
				'name'             => __( 'Primary Sidebar', 'power' ),
				'description'      => __( 'This is the primary sidebar if you are using a two or three column site layout option.', 'power' ),
				'_power_builtin' => true,
			]
		);
	}

	if ( isset( $wp_registered_sidebars['sidebar-alt'] ) ) {
		power_register_widget_area(
			[
				'id'               => 'sidebar-alt',
				'name'             => __( 'Secondary Sidebar', 'power' ),
				'description'      => __( 'This is the secondary sidebar if you are using a three column site layout option.', 'power' ),
				'_power_builtin' => true,
			]
		);
	}

}

/**
 * Register footer widget areas based on the number of widget areas the user wishes to create with `add_theme_support()`.
 *
 * @since 1.6.0
 *
 * @return void Return early if there is no theme support for `power-footer-widgets`
 *              or the number of widgets given is not set or not numeric.
 */
function power_register_footer_widget_areas() {

	$footer_widgets = get_theme_support( 'power-footer-widgets' );

	if ( ! $footer_widgets || ! isset( $footer_widgets[0] ) || ! is_numeric( $footer_widgets[0] ) ) {
		return;
	}

	$footer_widgets = (int) $footer_widgets[0];

	$counter = 1;

	while ( $counter <= $footer_widgets ) {
		power_register_widget_area(
			[
				'id'               => sprintf( 'footer-%d', $counter ),
				/* translators: %d: Footer widget counter. */
				'name'             => sprintf( __( 'Footer %d', 'power' ), $counter ),
				/* translators: %d: Footer widget counter. */
				'description'      => sprintf( __( 'Footer %d widget area.', 'power' ), $counter ),
				'_power_builtin' => true,
			]
		);

		$counter++;
	}

}

/**
 * Register after-entry widget area if user specifies in the child theme.
 *
 * @since 2.1.0
 *
 * @return void Return early if there is no theme support for `power-after-entry-widget-area`.
 */
function power_register_after_entry_widget_area() {

	if ( ! current_theme_supports( 'power-after-entry-widget-area' ) ) {
		return;
	}

	power_register_widget_area(
		[
			'id'          => 'after-entry',
			'name'        => __( 'After Entry', 'power' ),
			'description' => __( 'Widgets in this widget area will display after single entries.', 'power' ),
			'_builtin'    => true,
		]
	);

}

/**
 * Conditionally display a sidebar, wrapped in a div by default.
 *
 * The $args array accepts the following keys:
 *
 *  - `before` (markup to be displayed before the widget area output),
 *  - `after` (markup to be displayed after the widget area output),
 *  - `default` (fallback text if the sidebar is not found, or has no widgets, default is an empty string),
 *  - `show_inactive` (flag to show inactive sidebars, default is false),
 *  - `before_sidebar_hook` (hook that fires before the widget area output),
 *  - `after_sidebar_hook` (hook that fires after the widget area output).
 *
 * Return false early if the sidebar is not active and the `show_inactive` argument is false.
 *
 * @since 1.8.0
 *
 * @param string $id   Sidebar ID, as per when it was registered.
 * @param array  $args Arguments.
 * @return bool `false` if `$id` is falsy, or `$args['show_inactive']` is falsy and sidebar
 *              is not currently being used. `true` otherwise.
 */
function power_widget_area( $id, $args = [] ) {

	if ( ! $id ) {
		return false;
	}

	$defaults = apply_filters(
		'power_widget_area_defaults',
		[
			'before'              => power_markup(
				[
					'open'    => '<aside class="widget-area">' . power_sidebar_title( $id ),
					'context' => 'widget-area-wrap',
					'echo'    => false,
					'params'  => [
						'id' => $id,
					],
				]
			),
			'after'               => power_markup(
				[
					'close'   => '</aside>',
					'context' => 'widget-area-wrap',
					'echo'    => false,
				]
			),
			'default'             => '',
			'show_inactive'       => 0,
			'before_sidebar_hook' => 'power_before_' . $id . '_widget_area',
			'after_sidebar_hook'  => 'power_after_' . $id . '_widget_area',
		],
		$id,
		$args
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ! $args['show_inactive'] && ! is_active_sidebar( $id ) ) {
		return false;
	}

	// Opening markup.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $args['before'];

	// Before hook.
	if ( $args['before_sidebar_hook'] ) {
			/**
			 * Fires before widget area is output.
			 *
			 * Default format of hook name is 'power_before_' . $id . '_widget_area'`, where `$id` is the widget area
			 * ID, but this can be changed when registering a new widget area.
			 *
			 * @since ???
			 */
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
			do_action( $args['before_sidebar_hook'] );
	}

	if ( ! dynamic_sidebar( $id ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['default'];
	}

	// After hook.
	if ( $args['after_sidebar_hook'] ) {
		/**
		 * Fires before widget area is output.
		 *
		 * * Default format of hook name is 'power_after_' . $id . '_widget_area'`, where `$id` is the widget area
		 * ID, but this can be changed when registering a new widget area.
		 *
		 * @since ???
		 */
		do_action( $args['after_sidebar_hook'] ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
	}

	// Closing markup.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $args['after'];

	return true;

}

add_filter( 'power_register_sidebar_defaults', 'power_a11y_register_sidebar_defaults' );
/**
 * Widget heading filter, default H4 in Widgets and sidebars modified to an H3 if power_a11y( 'headings' ) support
 *
 * For using a semantic heading structure, improves accessibility
 *
 * @since 2.2.0
 *
 * @param array $args Existing sidebar default arguments.
 * @return array Amended sidebar default arguments.
 */
function power_a11y_register_sidebar_defaults( $args ) {

	if ( power_a11y( 'headings' ) ) {
		$args['before_title'] = '<h3 class="widgettitle widget-title">';
		$args['after_title']  = "</h3>\n";
	}

	return $args;
}

/**
 * Adds an H2 title to widget areas.
 *
 * For using a semantic heading structure, improves accessibility
 *
 * @since 2.2.0
 *
 * @global array $wp_registered_sidebars
 *
 * @param string $id Sidebar ID, as per when it was registered.
 * @return string|null Widget area heading, or `null` if `headings` are not enabled for
 *                     Power accessibility, or `$id` is not registered as a widget area ID.
 */
function power_sidebar_title( $id ) {

	if ( $id && power_a11y( 'headings' ) ) {

		global $wp_registered_sidebars;

		$name = $id;

		if ( array_key_exists( $id, $wp_registered_sidebars ) ) {
			$name = $wp_registered_sidebars[ $id ]['name'];
		}

		$heading = '<h2 class="power-sidebar-title screen-reader-text">' . $name . '</h2>';

		return apply_filters( 'power_sidebar_title_output', $heading, $id );
	}

	return null;

}
