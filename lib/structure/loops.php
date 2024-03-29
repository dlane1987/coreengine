<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Loops
 * @author  Core Engine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

add_action( 'power_loop', 'power_do_loop' );
/**
 * Attach a loop to the `power_loop` output hook so we can get some front-end output.
 *
 * @since 1.1.0
 */
function power_do_loop() {

	if ( is_singular( 'page' ) && power_get_custom_field( 'query_args' ) ) {

		$paged = get_query_var( 'paged' ) ?: 1;

		/*
		 * Convert custom field string to args array.
		 */
		$query_args = wp_parse_args(
			power_get_custom_field( 'query_args' ),
			[
				'paged' => $paged,
			]
		);

		power_custom_loop( $query_args );
	} else {
		power_standard_loop();
	}

}

/**
 * Standard loop, meant to be executed without modification in most circumstances where content needs to be displayed.
 *
 * It outputs basic wrapping HTML, but uses hooks to do most of its content output like title, content, post information
 * and comments.
 *
 * The action hooks called are:
 *
 *  - `power_before_entry`
 *  - `power_entry_header`
 *  - `power_before_entry_content`
 *  - `power_entry_content`
 *  - `power_after_entry_content`
 *  - `power_entry_footer`
 *  - `power_after_endwhile`
 *  - `power_loop_else` (only if no posts were found)
 *
 * @since 1.1.0
 *
 * @return void Return early after legacy loop if not supporting HTML5.
 */
function power_standard_loop() {

	if ( have_posts() ) {

		/**
		 * Fires inside the standard loop, before the while() block.
		 *
		 * @since 2.1.0
		 */
		do_action( 'power_before_while' );

		while ( have_posts() ) {

			the_post();

			/**
			 * Fires inside the standard loop, before the entry opening markup.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_before_entry' );

			power_markup(
				[
					'open'    => '<article %s>',
					'context' => 'entry',
				]
			);

			/**
			 * Fires inside the standard loop, to display the entry header.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_entry_header' );

			/**
			 * Fires inside the standard loop, after the entry header action hook, before the entry content.
			 * opening markup.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_before_entry_content' );

			power_markup(
				[
					'open'    => '<div %s>',
					'context' => 'entry-content',
				]
			);
			/**
			 * Fires inside the standard loop, inside the entry content markup.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_entry_content' );
			power_markup(
				[
					'close'   => '</div>',
					'context' => 'entry-content',
				]
			);

			/**
			 * Fires inside the standard loop, before the entry footer action hook, after the entry content.
			 * opening markup.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_after_entry_content' );

			/**
			 * Fires inside the standard loop, to display the entry footer.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_entry_footer' );

			power_markup(
				[
					'close'   => '</article>',
					'context' => 'entry',
				]
			);

			/**
			 * Fires inside the standard loop, after the entry closing markup.
			 *
			 * @since 2.0.0
			 */
			do_action( 'power_after_entry' );

		} // End of one post.

		/**
		 * Fires inside the standard loop, after the while() block.
		 *
		 * @since 1.0.0
		 */
		do_action( 'power_after_endwhile' );

	} else { // If no posts exist.

		/**
		 * Fires inside the standard loop when they are no posts to show.
		 *
		 * @since 1.0.0
		 */
		do_action( 'power_loop_else' );

	} // End loop.

}

/**
 * Custom loop, meant to be executed when a custom query is needed.
 *
 * It accepts arguments in query_posts style format to modify the custom `WP_Query` object.
 *
 * It outputs basic wrapping HTML, but uses hooks to do most of its content output like title, content, post information,
 * and comments.
 *
 * The arguments can be passed in via the `power_custom_loop_args` filter.
 *
 * The action hooks called are the same as {@link power_standard_loop()}.
 *
 * @since 1.1.0
 *
 * @global WP_Query $wp_query Query object.
 * @global int      $more
 *
 * @param array $args Loop configuration.
 */
function power_custom_loop( $args = [] ) {

	global $wp_query, $more;

	$defaults = []; // For forward compatibility.
	$args     = apply_filters( 'power_custom_loop_args', wp_parse_args( $args, $defaults ), $args, $defaults );

	$wp_query = new WP_Query( $args ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Reset later.

	// Only set $more to 0 if we're on an archive.
	$more = is_singular() ? $more : 0; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Handle archives.

	power_standard_loop();

	// Restore original query.
	wp_reset_query(); // phpcs:ignore WordPress.WP.DiscouragedFunctions.wp_reset_query_wp_reset_query -- Making sure the query is really reset.

}

/**
 * The grid loop - a specific implementation of a custom loop.
 *
 * Outputs markup compatible with a Feature + Grid style layout.
 *
 * The arguments can be filtered by the `power_grid_loop_args` filter.
 *
 * @since 1.5.0
 *
 * @global array $_power_loop_args Associative array for grid loop configuration.
 *
 * @param array $args Associative array for grid loop configuration.
 */
function power_grid_loop( $args = [] ) {

	// Global vars.
	global $_power_loop_args;

	// Parse args.
	$args = apply_filters(
		'power_grid_loop_args',
		wp_parse_args(
			$args,
			[
				'features'              => 2,
				'features_on_all'       => false,
				'feature_image_size'    => 0,
				'feature_image_class'   => 'alignleft',
				'feature_content_limit' => 0,
				'grid_image_size'       => 'thumbnail',
				'grid_image_class'      => 'alignleft',
				'grid_content_limit'    => 0,
				'more'                  => __( 'Read more', 'power' ) . '&#x02026;',
			]
		)
	);

	// If user chose more features than posts per page, adjust features.
	if ( get_option( 'posts_per_page' ) < $args['features'] ) {
		$args['features'] = get_option( 'posts_per_page' );
	}

	// What page are we on?
	$paged = get_query_var( 'paged' ) ?: 1;

	// Potentially remove features on page 2+.
	if ( $paged > 1 && ! $args['features_on_all'] ) {
		$args['features'] = 0;
	}

	// Set global loop args.
	$_power_loop_args = $args;

	// Remove some unnecessary stuff from the grid loop.
	remove_action( 'power_entry_header', 'power_do_post_format_image', 4 );
	remove_action( 'power_entry_content', 'power_do_post_image', 8 );
	remove_action( 'power_entry_content', 'power_do_post_content' );
	remove_action( 'power_entry_content', 'power_do_post_content_nav', 12 );
	remove_action( 'power_entry_content', 'power_do_post_permalink', 14 );

	// Custom loop output.
	add_filter( 'post_class', 'power_grid_loop_post_class' );
	add_action( 'power_entry_content', 'power_grid_loop_content' );

	// The loop.
	power_standard_loop();

	// Reset loops.
	power_reset_loops();
	remove_filter( 'post_class', 'power_grid_loop_post_class' );
	remove_action( 'power_entry_content', 'power_grid_loop_content' );

}

/**
 * Filter the post classes to output custom classes for the feature and grid layout.
 *
 * Based on the grid loop args and the loop counter.
 *
 * Applies the `power_grid_loop_post_class` filter.
 *
 * The `&1` is a test to see if it is odd. `2&1 = 0` (even), `3&1 = 1` (odd).
 *
 * @since 1.5.0
 *
 * @global array    $_power_loop_args Associative array for grid loop config.
 * @global WP_Query $wp_query           Query object.
 *
 * @param array $classes Existing post classes.
 * @return array Amended post classes.
 */
function power_grid_loop_post_class( array $classes ) {

	global $_power_loop_args, $wp_query;

	$grid_classes = [];

	if ( $_power_loop_args['features'] && $wp_query->current_post < $_power_loop_args['features'] ) {
		$grid_classes[] = 'power-feature';
		$grid_classes[] = sprintf( 'power-feature-%s', $wp_query->current_post + 1 );
		$grid_classes[] = $wp_query->current_post & 1 ? 'power-feature-even' : 'power-feature-odd';
	} elseif ( $_power_loop_args['features'] & 1 ) {
		$grid_classes[] = 'power-grid';
		$grid_classes[] = sprintf( 'power-grid-%s', $wp_query->current_post - $_power_loop_args['features'] + 1 );
		$grid_classes[] = $wp_query->current_post & 1 ? 'power-grid-odd' : 'power-grid-even';
	} else {
		$grid_classes[] = 'power-grid';
		$grid_classes[] = sprintf( 'power-grid-%s', $wp_query->current_post - $_power_loop_args['features'] + 1 );
		$grid_classes[] = $wp_query->current_post & 1 ? 'power-grid-even' : 'power-grid-odd';
	}

	return array_merge( $classes, apply_filters( 'power_grid_loop_post_class', $grid_classes ) );

}

/**
 * Output specially formatted content, based on the grid loop args.
 *
 * @since 1.5.0
 *
 * @global array $_power_loop_args Associative array for grid loop configuration.
 */
function power_grid_loop_content() {

	global $_power_loop_args;

	if ( in_array( 'power-feature', get_post_class(), true ) ) {
		if ( $_power_loop_args['feature_image_size'] ) {

			$image = power_get_image(
				[
					'size'    => $_power_loop_args['feature_image_size'],
					'context' => 'grid-loop-featured',
					'attr'    => power_parse_attr(
						'entry-image-grid-loop',
						[
							'class' => $_power_loop_args['feature_image_class'],
						]
					),
				]
			);

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $image is html markup.
			printf( '<a href="%s">%s</a>', esc_url( get_permalink() ), $image );

		}

		if ( $_power_loop_args['feature_content_limit'] ) {
			the_content_limit( (int) $_power_loop_args['feature_content_limit'], power_a11y_more_link( esc_html( $_power_loop_args['more'] ) ) );
		} else {
			the_content( power_a11y_more_link( esc_html( $_power_loop_args['more'] ) ) );
		}
	} else {
		if ( $_power_loop_args['grid_image_size'] ) {

			$image = power_get_image(
				[
					'size'    => $_power_loop_args['grid_image_size'],
					'context' => 'grid-loop',
					'attr'    => power_parse_attr(
						'entry-image-grid-loop',
						[
							'class' => $_power_loop_args['grid_image_class'],
						]
					),
				]
			);

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $image is html markup.
			printf( '<a href="%s">%s</a>', esc_url( get_permalink() ), $image );

		}

		if ( $_power_loop_args['grid_content_limit'] ) {
			the_content_limit( (int) $_power_loop_args['grid_content_limit'], power_a11y_more_link( esc_html( $_power_loop_args['more'] ) ) );
		} else {
			the_excerpt();
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- power_a11y_more_link returns html markup.
			printf( '<a href="%s" class="more-link">%s</a>', esc_url( get_permalink() ), power_a11y_more_link( esc_html( $_power_loop_args['more'] ) ) );
		}
	}

}

add_action( 'power_after_entry', 'power_add_id_to_global_exclude', 9 );
/**
 * Modify the global $_power_displayed_ids each time a loop iterates.
 *
 * Keep track of what posts have been shown on any given page by adding each ID to a global array, which can be used any
 * time by other loops to prevent posts from being displayed twice on a page.
 *
 * @since 2.0.0
 *
 * @global array $_power_displayed_ids Array of displayed post IDs.
 */
function power_add_id_to_global_exclude() {

	global $_power_displayed_ids;

	$_power_displayed_ids[] = get_the_ID();

}
