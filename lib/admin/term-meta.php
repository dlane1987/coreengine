<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Admin
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

/**
 * Term meta defaults.
 *
 * @since 2.2.6
 *
 * @return array Default term meta values.
 */
function power_term_meta_defaults() {

	return apply_filters(
		'power_term_meta_defaults',
		[
			'headline'            => '',
			'intro_text'          => '',
			'display_title'       => 0, // Vestigial.
			'display_description' => 0, // Vestigial.
			'doctitle'            => '',
			'description'         => '',
			'keywords'            => '',
			'layout'              => '',
			'noindex'             => 0,
			'nofollow'            => 0,
			'noarchive'           => 0,
		]
	);

}

add_action( 'admin_init', 'power_add_taxonomy_archive_options' );
/**
 * Add the archive options to each custom taxonomy edit screen.
 *
 * @since 1.6.0
 *
 * @see power_taxonomy_archive_options() Callback for headline and introduction fields.
 */
function power_add_taxonomy_archive_options() {

	foreach ( get_taxonomies(
		[
			'public' => true,
		]
	) as $tax_name ) {
		add_action( $tax_name . '_edit_form', 'power_taxonomy_archive_options', 10, 2 );
	}

}

/**
 * Echo headline and introduction fields on the taxonomy term edit form.
 *
 * If populated, the values saved in these fields may display on taxonomy archives.
 *
 * @since 1.6.0
 *
 * @see power_add_taxonomy_archive_options() Callback caller.
 *
 * @param \stdClass $tag      Term object.
 * @param string    $taxonomy Name of the taxonomy.
 */
function power_taxonomy_archive_options( $tag, $taxonomy ) {

	power_meta_boxes()->show_meta_box( 'power-term-meta-settings', $tag );

}

add_action( 'admin_init', 'power_add_taxonomy_seo_options' );
/**
 * Add the SEO options to each custom taxonomy edit screen.
 *
 * @since 1.3.0
 *
 * @see power_taxonomy_seo_options() Callback for SEO fields.
 */
function power_add_taxonomy_seo_options() {

	foreach ( get_taxonomies(
		[
			'public' => true,
		]
	) as $tax_name ) {
		add_action( $tax_name . '_edit_form', 'power_taxonomy_seo_options', 10, 2 );
	}

}

/**
 * Echo title, description, keywords and robots meta SEO fields on the taxonomy term edit form.
 *
 * If populated, the values saved in these fields may be used on taxonomy archives.
 *
 * @since 1.2.0
 *
 * @see power_add-taxonomy_seo_options() Callback caller.
 *
 * @param \stdClass $tag      Term object.
 * @param string    $taxonomy Name of the taxonomy.
 */
function power_taxonomy_seo_options( $tag, $taxonomy ) {

	power_meta_boxes()->show_meta_box( 'power-term-meta-seo', $tag );

}

add_action( 'admin_init', 'power_add_taxonomy_layout_options' );
/**
 * Add the layout options to each custom taxonomy edit screen.
 *
 * @since 1.4.0
 *
 * @see power_taxonomy_layout_options() Callback for layout selector.
 */
function power_add_taxonomy_layout_options() {

	if ( ! current_theme_supports( 'power-archive-layouts' ) ) {
		return;
	}

	if ( ! power_has_multiple_layouts() ) {
		return;
	}

	foreach ( get_taxonomies(
		[
			'public' => true,
		]
	) as $tax_name ) {
		add_action( $tax_name . '_edit_form', 'power_taxonomy_layout_options', 10, 2 );
	}

}

/**
 * Echo the layout options on the taxonomy term edit form.
 *
 * @since 1.4.0
 *
 * @see power_add_taxonomy_layout_options() Callback caller.
 *
 * @param \stdClass $tag      Term object.
 * @param string    $taxonomy Name of the taxonomy.
 */
function power_taxonomy_layout_options( $tag, $taxonomy ) {

	power_meta_boxes()->show_meta_box( 'power-term-meta-layout', $tag );

}

add_filter( 'get_term', 'power_get_term_filter', 10, 2 );
/**
 * For backward compatibility only.
 *
 * Sets $term->meta to empty array. All calls to $term->meta->key will be unset unless force set by `power_term_meta` filter.
 *
 * @since 1.2.0
 *
 * @param object $term     Database row object.
 * @param string $taxonomy Taxonomy name that $term is part of.
 * @return object Database row object.
 */
function power_get_term_filter( $term, $taxonomy ) {

	// Do nothing, if $term is not object.
	if ( ! is_object( $term ) ) {
		return $term;
	}

	// Do nothing, if called in the context of creating a term via an ajax call.
	if ( did_action( 'wp_ajax_add-tag' ) ) {
		return $term;
	}

	// Still set $term->meta and apply filter, for backward compatibility.
	$term->meta = apply_filters( 'power_term_meta', [], $term, $taxonomy );

	return $term;

}

add_filter( 'get_terms', 'power_get_terms_filter', 10, 2 );
/**
 * Add Power term-meta data to functions that return multiple terms.
 *
 * @since 2.0.0
 *
 * @param array  $terms    Database row objects.
 * @param string $taxonomy Taxonomy name that $terms are part of.
 * @return array Database row objects.
 */
function power_get_terms_filter( array $terms, $taxonomy ) {

	foreach ( $terms as $key => $term ) {
		$terms[ $key ] = power_get_term_filter( $term, $taxonomy );
	}

	return $terms;

}

add_filter( 'get_term_metadata', 'power_term_meta_filter', 10, 4 );
/**
 * Maintain backward compatibility with the older `power_term_meta_{$key}` filter so old filter functions will still work.
 *
 * @since 2.3.0
 *
 * @param string|array $value     The term meta value.
 * @param int          $object_id The term ID.
 * @param string       $meta_key  Meta key.
 * @param bool         $single    Whether to return only the first value of the specified $meta_key.
 * @return mixed Filtered term meta value.
 */
function power_term_meta_filter( $value, $object_id, $meta_key, $single ) {

	return apply_filters( "power_term_meta_{$meta_key}", $value, get_term_field( 'slug', $object_id ), null );

}

add_action( 'edit_term', 'power_term_meta_save', 10, 2 );
/**
 * Save term meta data.
 *
 * Fires when a user edits and saves a term.
 *
 * @since 1.2.0
 *
 * @param int $term_id Term ID.
 * @param int $tt_id   Term Taxonomy ID.
 */
function power_term_meta_save( $term_id, $tt_id ) {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- $_POST['power-meta'] is not a nonce field.
	$values = isset( $_POST['power-meta'] ) ? (array) $_POST['power-meta'] : [];

	$values = wp_parse_args( $values, power_term_meta_defaults() );

	if ( isset( $values['archive_description'] ) && ! current_user_can( 'unfiltered_html' ) ) {
		$values['archive_description'] = power_formatting_kses( $values['archive_description'] );
	}

	foreach ( $values as $key => $value ) {
		update_term_meta( $term_id, $key, $value );
	}

}

add_action( 'delete_term', 'power_term_meta_delete', 10, 2 );
/**
 * Delete term meta data.
 *
 * Fires when a user deletes a term.
 *
 * @since 1.2.0
 *
 * @param int $term_id Term ID.
 * @param int $tt_id   Taxonomy Term ID.
 */
function power_term_meta_delete( $term_id, $tt_id ) {

	foreach ( power_term_meta_defaults() as $key => $value ) {
		delete_term_meta( $term_id, $key );
	}

}

add_action( 'split_shared_term', 'power_split_shared_term' );
/**
 * Create new term meta record for split terms.
 *
 * When WordPress splits terms, ensure that the term meta gets preserved for the newly created term.
 *
 * @since 2.2.0
 *
 * @param int $old_term_id The ID of the term being split.
 * @param int $new_term_id The ID of the newly created term.
 */
function power_split_shared_term( $old_term_id, $new_term_id ) {

	$term_meta = (array) get_option( 'power-term-meta' );

	if ( ! isset( $term_meta[ $old_term_id ] ) ) {
		return;
	}

	$term_meta[ $new_term_id ] = $term_meta[ $old_term_id ];

	update_option( 'power-term-meta', $term_meta );

}
