<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Power
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

namespace CoreEngine\Power\Upgrade;

/**
 * Upgrade class. Called when `db_version` Power setting is below 1800.
 *
 * @since 3.1.0
 */
class Upgrade_DB_1800 implements Upgrade_DB_Interface {
	/**
	 * Upgrade method.
	 *
	 * @since 1.8.0
	 * @since 3.1.0 Moved to class method.
	 */
	public function upgrade() {
		$this->convert_term_meta();
	}

	/**
	 * Convert term meta for new title/description options.
	 *
	 * @since 2.6.0
	 * @since 3.1.0 Moved to class method.
	 */
	public function convert_term_meta() {
		$term_meta = get_option( 'power-term-meta' );
		$terms     = get_terms(
			get_taxonomies(),
			[
				'hide_empty' => false,
			]
		);

		foreach ( (array) $terms as $term ) {
			if ( ! empty( $term_meta[ $term->term_id ]['display_title'] ) ) {
				$term_meta[ $term->term_id ]['headline'] = $term->name;
			}

			if ( ! empty( $term_meta[ $term->term_id ]['display_description'] ) ) {
				$term_meta[ $term->term_id ]['intro_text'] = $term->description;
			}
		}

		update_option( 'power-term-meta', $term_meta );
	}
}
