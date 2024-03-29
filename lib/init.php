<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Framework
 * @author  Core Engine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

/**
 * Check system requirements before loading the full framework.
 */
require_once __DIR__ . '/functions/requirements.php';
require_once __DIR__ . '/classes/class-power-requirements-views.php';

$power_requirements_messages = power_check_requirements();

if ( true !== $power_requirements_messages ) {
	$power_requirements_views = new Power_Requirements_Views( $power_requirements_messages );
	$power_requirements_views->add_hooks();
}

spl_autoload_register( 'power_autoload_register' );
/**
 * Allow Power_* class and CoreEngine\Power namespaced files to be loaded automatically.
 *
 * @since 2.7.0 Allowed autoloading of namespaced classes.
 *
 * @param string $class_name Class name.
 * @return mixed|null|string Null if the classname format is not recognized otherwise the file path.
 */
function power_autoload_register( $class_name ) {

	// If the class being requested does not start with our prefix, we know it's not one in our project.
	if ( 0 !== strpos( $class_name, 'Power_' ) && 0 !== strpos( $class_name, 'CoreEngine\Power' ) ) {
		return null;
	}

	// class-{classname}.php structure.
	$file_name = strtolower( str_replace( [ 'CoreEngine\Power\\', '\\', '_' ], [ '', '-', '-' ], $class_name ) );
	$file      = get_template_directory() . '/lib/classes/class-' . $file_name . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}

	return $file;
}

spl_autoload_register( 'power_autoload_register_psr4' );
/**
 * Allow CoreEngine\Power namespaced files to be loaded automatically.
 *
 * @since 3.1.0
 *
 * @param string $class_name Class name.
 * @return mixed|null|string Null if the classname format is not recognized otherwise the file path.
 */
function power_autoload_register_psr4( $class_name ) {

	// If the class being requested does not start with our prefix, we know it's not one in our project.
	if ( 0 !== strpos( $class_name, 'CoreEngine\Power' ) ) {
		return null;
	}

	$file_name = str_replace( [ 'CoreEngine\\', 'Power\\', '\\' ], [ '', '', '/' ], $class_name );
	$file      = get_template_directory() . '/lib/classes/' . $file_name . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}

	return $file;
}

/**
 * Fires before init functions are defined, and hooked in.
 *
 * @since 1.2.0
 */
do_action( 'power_pre' );

add_action( 'power_init', 'power_i18n' );
/**
 * Load the Power textdomain for internationalization.
 *
 * @since 1.9.0
 */
function power_i18n() {

	if ( ! defined( 'POWER_LANGUAGES_DIR' ) ) {
		define( 'POWER_LANGUAGES_DIR', get_template_directory() . '/lib/languages' );
	}

	load_theme_textdomain( 'power', POWER_LANGUAGES_DIR );

}

add_action( 'power_init', 'power_theme_support' );
/**
 * Activates default theme features.
 *
 * @since 1.6.0
 */
function power_theme_support() {

	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'body-open' );
	add_theme_support( 'power-inpost-layouts' );
	add_theme_support( 'power-archive-layouts' );
	add_theme_support( 'power-admin-menu' );
	add_theme_support( 'power-seo-settings-menu' );
	add_theme_support( 'power-import-export-menu' );
	add_theme_support( 'power-readme-menu' );
	add_theme_support( 'power-customizer-theme-settings' );
	add_theme_support( 'power-customizer-seo-settings' );
	add_theme_support( 'power-auto-updates' );
	add_theme_support( 'power-breadcrumbs' );

	// Maybe add support for Power menus.
	if ( ! current_theme_supports( 'power-menus' ) ) {

		$menus = [
			'primary'   => __( 'Primary Navigation Menu', 'power' ),
			'secondary' => __( 'Secondary Navigation Menu', 'power' ),
		];

		/**
		 * Filter for the menus that Power supports by default.
		 *
		 * @since 2.3.0
		 *
		 * @param array $menus The array of supported menus.
		 */
		$menus = apply_filters( 'power_theme_support_menus', $menus );

		add_theme_support( 'power-menus', $menus );

	}

	// Maybe add support for structural wraps.
	if ( ! current_theme_supports( 'power-structural-wraps' ) ) {

		$structural_wraps = [ 'header', 'menu-primary', 'menu-secondary', 'footer-widgets', 'footer' ];

		/**
		 * Filter for the structural wraps that Power supports by default.
		 *
		 * @since 2.3.0
		 *
		 * @param array $structural_wraps The array of supported structural wraps.
		 */
		$structural_wraps = apply_filters( 'power_theme_support_structural_wraps', $structural_wraps );

		add_theme_support( 'power-structural-wraps', $structural_wraps );

	}

	// Turn on HTML5 and responsive viewport if Power is active.
	if ( ! is_child_theme() ) {
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
		add_theme_support( 'power-responsive-viewport' );
		add_theme_support(
			'power-accessibility',
			[
				'drop-down-menu',
				'headings',
				'rems',
				'search-form',
				'skip-links',
			]
		);
	}

}

add_action( 'power_init', 'power_post_type_support' );
/**
 * Initialize post type support for Power features (Layout selector, SEO).
 *
 * @since 1.8.0
 */
function power_post_type_support() {

	add_post_type_support( 'post', [ 'power-seo', 'power-scripts', 'power-layouts', 'power-breadcrumbs-toggle', 'power-rel-author' ] );
	add_post_type_support( 'page', [ 'power-seo', 'power-scripts', 'power-layouts', 'power-breadcrumbs-toggle', 'power-title-toggle' ] );

}

add_action( 'init', 'power_post_type_support_post_meta', 11 );
/**
 * Add post type support for post meta to all post types except page.
 *
 * @since 2.2.0
 */
function power_post_type_support_post_meta() {

	$public_post_types = get_post_types( [ 'public' => true ] );

	foreach ( $public_post_types as $post_type ) {
		if ( 'page' !== $post_type ) {
			add_post_type_support( $post_type, 'power-entry-meta-before-content' );
			add_post_type_support( $post_type, 'power-entry-meta-after-content' );
		}
	}

	// For backward compatibility.
	if ( current_theme_supports( 'power-after-entry-widget-area' ) ) {
		add_post_type_support( 'post', 'power-after-entry-widget-area' );
	}

}

add_action( 'power_init', 'power_constants' );
/**
 * This function defines the Power theme constants
 *
 * @since 1.6.0
 */
function power_constants() {

	// Define Theme Info Constants.
	// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
	define( 'PARENT_THEME_NAME', 'Power' );
	define( 'PARENT_THEME_VERSION', '3.1.2' );
	define( 'PARENT_THEME_BRANCH', '3.1' );
	define( 'PARENT_DB_VERSION', '3101' );
	define( 'PARENT_THEME_RELEASE_DATE', date_i18n( 'F j, Y', strtotime( '5 September 2019' ) ) );

	// Define Parent and Child Directory Location and URL Constants.
	define( 'PARENT_DIR', get_template_directory() );
	define( 'CHILD_DIR', get_stylesheet_directory() );
	define( 'PARENT_URL', get_template_directory_uri() );
	define( 'CHILD_URL', get_stylesheet_directory_uri() );
	// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

	// Define URL Location Constants.
	$lib_url = PARENT_URL . '/lib';
	if ( ! defined( 'POWER_IMAGES_URL' ) ) {
		define( 'POWER_IMAGES_URL', PARENT_URL . '/images' );
	}
	if ( ! defined( 'POWER_ADMIN_IMAGES_URL' ) ) {
		define( 'POWER_ADMIN_IMAGES_URL', $lib_url . '/admin/images' );
	}
	if ( ! defined( 'POWER_JS_URL' ) ) {
		define( 'POWER_JS_URL', $lib_url . '/js' );
	}
	if ( ! defined( 'POWER_CSS_URL' ) ) {
		define( 'POWER_CSS_URL', $lib_url . '/css' );
	}

	// Define directory locations constants.
	define( 'POWER_VIEWS_DIR', PARENT_DIR . '/lib/views' );
	define( 'POWER_CONFIG_DIR', PARENT_DIR . '/config' );

	// Define Settings Field Constants (for DB storage).
	define( 'POWER_SETTINGS_FIELD', (string) apply_filters( 'power_settings_field', 'power-settings' ) );
	define( 'POWER_SEO_SETTINGS_FIELD', (string) apply_filters( 'power_seo_settings_field', 'power-seo-settings' ) );
	define( 'POWER_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX', (string) apply_filters( 'power_cpt_archive_settings_field_prefix', 'power-cpt-archive-settings-' ) );

	// Unused in Power, considered deprecated.
	if ( apply_filters( 'power_load_deprecated', true ) ) {
		// Directory Constants.
		$lib_dir = PARENT_DIR . '/lib';
		define( 'POWER_IMAGES_DIR', PARENT_DIR . '/images' );
		define( 'POWER_ADMIN_IMAGES_DIR', $lib_dir . '/admin/images' );
		define( 'POWER_TOOLS_DIR', $lib_dir . '/tools' );
		define( 'POWER_LIB_DIR', $lib_dir );
		define( 'POWER_ADMIN_DIR', $lib_dir . '/admin' );
		define( 'POWER_JS_DIR', $lib_dir . '/js' );
		define( 'POWER_CSS_DIR', $lib_dir . '/css' );
		define( 'POWER_FUNCTIONS_DIR', $lib_dir . '/functions' );
		define( 'POWER_SHORTCODES_DIR', $lib_dir . '/shortcodes' );
		define( 'POWER_STRUCTURE_DIR', $lib_dir . '/structure' );
		define( 'POWER_WIDGETS_DIR', $lib_dir . '/widgets' );

		// URL Constants.
		define( 'POWER_ADMIN_URL', $lib_url . '/admin' );
		define( 'POWER_LIB_URL', $lib_url );
		define( 'POWER_FUNCTIONS_URL', $lib_url . '/functions' );
		define( 'POWER_SHORTCODES_URL', $lib_url . '/shortcodes' );
		define( 'POWER_STRUCTURE_URL', $lib_url . '/structure' );
		define( 'POWER_WIDGETS_URL', $lib_url . '/widgets' );
	}

}

add_action( 'power_init', 'power_load_framework' );
/**
 * Loads all the framework files and features.
 *
 * The function can only be effective once, due to the use of the POWER_LOADED FRAMEWORK constant.
 *
 * The power_pre_framework action hook is called before any of the files are
 * required().
 *
 * If a child theme defines POWER_LOAD_FRAMEWORK as false before requiring
 * this init.php file, then this function will abort before any other framework
 * files are loaded.
 *
 * @since 1.6.0
 *
 * @global array $_power_formatting_allowed_tags Array of allowed tags for output formatting.
 */
function power_load_framework() {

	/**
	 * Fires before the framework files are loaded.
	 *
	 * @since 1.2.0
	 */
	do_action( 'power_pre_framework' );

	// Short circuit, if necessary.
	if ( defined( 'POWER_LOAD_FRAMEWORK' ) && false === POWER_LOAD_FRAMEWORK ) {
		return;
	}

	$lib_dir = trailingslashit( PARENT_DIR ) . 'lib/';

	// Load Framework.
	require_once $lib_dir . 'framework.php';

	// Load Functions.
	$functions_dir = $lib_dir . 'functions/';
	require_once $functions_dir . 'version.php';
	require_once $functions_dir . 'upgrade.php';
	require_once $functions_dir . 'compat.php';
	require_once $functions_dir . 'general.php';
	require_once $functions_dir . 'options.php';
	require_once $functions_dir . 'image.php';
	require_once $functions_dir . 'markup.php';
	require_if_theme_supports( 'power-breadcrumbs', $functions_dir . 'breadcrumb.php' );
	require_once $functions_dir . 'menu.php';
	require_once $functions_dir . 'layout.php';
	require_once $functions_dir . 'formatting.php';
	require_once $functions_dir . 'seo.php';
	require_once $functions_dir . 'widgetize.php';
	require_once $functions_dir . 'feed.php';
	require_once $functions_dir . 'toolbar.php';
	require_once $functions_dir . 'head.php';
	require_once $functions_dir . 'post-meta.php';
	require_once $functions_dir . 'rest.php';

	if ( apply_filters( 'power_load_deprecated', true ) ) {
		require_once $functions_dir . 'deprecated.php';
	}

	// Load Shortcodes.
	$shortcodes_dir = $lib_dir . 'shortcodes/';
	require_once $shortcodes_dir . 'post.php';
	require_once $shortcodes_dir . 'footer.php';

	// Load Structure.
	$structure_dir = $lib_dir . 'structure/';
	require_once $structure_dir . 'header.php';
	require_once $structure_dir . 'footer.php';
	require_once $structure_dir . 'menu.php';
	require_once $structure_dir . 'layout.php';
	require_once $structure_dir . 'post.php';
	require_once $structure_dir . 'loops.php';
	require_once $structure_dir . 'comments.php';
	require_once $structure_dir . 'sidebar.php';
	require_once $structure_dir . 'archive.php';

	// Load Admin.
	$admin_dir = $lib_dir . 'admin/';
	if ( is_admin() ) {
		require_once $admin_dir . 'install.php';
		require_once $admin_dir . 'menu.php';
		require_once $admin_dir . 'dashboard.php';
		require_once $admin_dir . 'admin-functions.php';
		require_once $admin_dir . 'inpost-metaboxes.php';
		require_once $admin_dir . 'use-child-theme.php';
		require_once $admin_dir . 'sanitization.php';
		require_once $admin_dir . 'privacy-requests.php';
		require_once $admin_dir . 'plugin-install.php';
		require_once $admin_dir . 'site-health.php';
		require_once $admin_dir . 'widget-import.php';
		require_once $admin_dir . 'onboarding/theme-activation.php';
		require_once $admin_dir . 'onboarding/ajax-functions.php';
		require_once $functions_dir . 'onboarding.php';
	}
	if ( is_customize_preview() ) {
		require_once $admin_dir . 'customizer.php';
	}
	require_once $admin_dir . 'term-meta.php';
	require_once $admin_dir . 'user-meta.php';

	// Load JavaScript.
	require_once $lib_dir . '/js/load-scripts.php';

	// Load CSS.
	require_once $lib_dir . '/css/load-styles.php';

	// Load Widgets.
	$widgets_dir = $lib_dir . 'widgets/';
	require_once $widgets_dir . 'widgets.php';
	require_once $widgets_dir . 'user-profile-widget.php';
	require_once $widgets_dir . 'featured-post-widget.php';
	require_once $widgets_dir . 'featured-page-widget.php';

	// Load CLI command.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::add_command( 'power db', 'Power_Cli_Db_Command' );
		WP_CLI::add_command( 'power core', 'Power_Cli_Core_Command' );
		WP_CLI::add_command( 'power setting', 'Power_Cli_Settings_Command' );
		WP_CLI::add_command( 'power option', 'Power_Cli_Settings_Command' );
		WP_CLI::add_command( 'power', 'Power_Cli_Command' );
	}

	global $_power_formatting_allowedtags;
	$_power_formatting_allowedtags = power_formatting_allowedtags();

	define( 'POWER_LOADED_FRAMEWORK', true );
}

/**
 * Fires during Power intialization.
 *
 * @since 1.0.0
 */
do_action( 'power_init' );

/**
 * Fires after Power initialization.
 *
 * @since 1.6.0
 */
do_action( 'power_setup' );
