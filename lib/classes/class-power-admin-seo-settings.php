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
 * Registers a new admin page, providing content and corresponding menu item for the SEO Settings page.
 *
 * Although this class was added in 1.8.0, some of the methods were originally standalone functions added in previous
 * versions of Power.
 *
 * @package Power\Admin
 *
 * @since 1.8.0
 */
class Power_Admin_SEO_Settings extends Power_Admin_Basic {

	/**
	 * Create an admin menu item and settings page.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {

		$this->redirect_to = admin_url( 'customize.php?autofocus[panel]=power-seo' );

		$page_id = 'seo-settings';

		$menu_ops = [
			'submenu' => [
				'parent_slug' => 'power',
				'page_title'  => __( 'Power - SEO Settings', 'power' ),
				'menu_title'  => __( 'SEO Settings', 'power' ),
			],
		];

		$settings_field = POWER_SEO_SETTINGS_FIELD;

		$this->create( $page_id, $menu_ops );
	}

	/**
	 * Required to use `Power_Admin_Basic`.
	 *
	 * @since 3.0
	 */
	public function admin() {}
}