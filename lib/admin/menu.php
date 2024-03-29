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
 * Fires before admin menu items are registered.
 *
 * Hook here (or later) to use the Power Admin classes, to be sure
 * they have been included before use.
 *
 * @since 1.8.0
 */
do_action( 'power_admin_init' );

add_action( 'after_setup_theme', 'power_add_admin_menu' );
/**
 * Add Power top-level item in admin menu.
 *
 * Calls the `power_admin_menu hook` at the end - all submenu items should be attached to that hook to ensure
 * correct ordering.
 *
 * @since 1.0.0
 *
 * @global \Power_Admin_Settings _power_admin_settings          Theme Settings page object.
 * @global string                  _power_theme_settings_pagehook Old backwards-compatible pagehook.
 *
 * @return void Return early if not viewing WP admin, Power menu is disabled, or disabled for current user.
 */
function power_add_admin_menu() {

	if ( ! is_admin() ) {
		return;
	}

	global $_power_admin_settings;

	if ( ! current_theme_supports( 'power-admin-menu' ) ) {
		return;
	}

	// Don't add menu item if disabled for current user.
	$user = wp_get_current_user();
	if ( ! get_the_author_meta( 'power_admin_menu', $user->ID ) ) {
		return;
	}

	$_power_admin_settings = new Power_Admin_Settings();

	// Set the old global pagehook var for backward compatibility.
	global $_power_theme_settings_pagehook;
	$_power_theme_settings_pagehook = $_power_admin_settings->pagehook;

	/**
	 * Fires after Power top-level menu item has been registered.
	 *
	 * @since 1.8.0
	 */
	do_action( 'power_admin_menu' );

}

add_action( 'power_admin_menu', 'power_add_admin_submenus' );
/**
 * Add submenu items under Power item in admin menu.
 *
 * @since 1.0.0
 *
 * @see Power_Admin_SEO_Settings SEO Settings class
 * @see Power_Admin_Import_export Import / Export class
 *
 * @global string $_power_seo_settings_pagehook Old backwards-compatible pagehook.
 * @global string $_power_admin_seo_settings
 * @global string $_power_admin_import_export
 *
 * @return void Return early if not viewing WP admin, or if Power menu is not supported.
 */
function power_add_admin_submenus() {

	if ( ! is_admin() ) {
		return;
	}

	global $_power_admin_seo_settings, $_power_admin_import_export;

	// Don't add submenu items if Power menu is disabled.
	if ( ! current_theme_supports( 'power-admin-menu' ) ) {
		return;
	}

	$user = wp_get_current_user();

	// Add "SEO Settings" submenu item.
	if ( current_theme_supports( 'power-seo-settings-menu' ) && get_the_author_meta( 'power_seo_settings_menu', $user->ID ) ) {

		$_power_admin_seo_settings = new Power_Admin_SEO_Settings();

		// set the old global pagehook var for backward compatibility.
		global $_power_seo_settings_pagehook;
		$_power_seo_settings_pagehook = $_power_admin_seo_settings->pagehook;

	}

	// Add "Import/Export" submenu item.
	if ( current_theme_supports( 'power-import-export-menu' ) && get_the_author_meta( 'power_import_export_menu', $user->ID ) ) {

		$_power_admin_import_export = new Power_Admin_Import_Export();

	}

	// Add the plugin menu (redirects to plugin install screen).
	if ( is_super_admin() ) {
		new Power_Admin_Plugins();
	}

	// Add the upgraded page (no menu).
	new Power_Admin_Upgraded();

	// Create Getting Started onboarding page.
	if ( version_compare( $GLOBALS['wp_version'], '5.0', '>=' ) && is_readable( locate_template( '/config/onboarding.php' ) ) ) {
		new Power_Admin_Onboarding();
	}

}

add_action( 'admin_menu', 'power_add_cpt_archive_page', 5 );
/**
 * Add archive settings page to relevant custom post type registrations.
 *
 * An instance of `Power_Admin_CPT_Archive_Settings` is instantiated for each relevant CPT, assigned to an individual
 * global variable.
 *
 * @since 2.0.0
 */
function power_add_cpt_archive_page() {
	$post_types = power_get_cpt_archive_types();

	foreach ( $post_types as $post_type ) {
		if ( power_has_post_type_archive_support( $post_type->name ) ) {
			$admin_object_name = '_power_admin_cpt_archives_' . $post_type->name;
			// phpcs:ignore PHPCompatibility.Variables.ForbiddenGlobalVariableVariable.NonBareVariableFound -- Programatically generated name of global
			global ${$admin_object_name};
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Programatically generated name of global 
			${$admin_object_name} = new Power_Admin_CPT_Archive_Settings( $post_type );
		}
	}
}
