<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Assets
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

/**
 * Script loader class.
 *
 * @since 2.5.0
 *
 * @package Power\Assets
 */
class Power_Script_Loader {

	/**
	 * Holds script file name suffix.
	 *
	 * @since 2.5.0
	 *
	 * @var string suffix
	 */
	private $suffix = '.min';

	/**
	 * Hook into WordPress.
	 *
	 * @since 2.5.0
	 */
	public function add_hooks() {

		// Register scripts early to allow replacement by plugin/child theme.
		add_action( 'wp_enqueue_scripts', [ $this, 'register_front_scripts' ], 0 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_admin_scripts' ], 0 );

		// Enqueue the scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_front_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'maybe_enqueue_admin_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_scripts' ] );

		// Enable extra attributes for enqueued wp_enqueue_scripts.
		add_filter( 'script_loader_tag', [ $this, 'maybe_enable_attrs' ], 10, 3 );

	}

	/**
	 * Register front end scripts used by Power.
	 *
	 * @since 2.5.0
	 */
	public function register_front_scripts() {

		if ( power_is_in_dev_mode() ) {
			$this->suffix = '';
		}

		wp_register_script( 'superfish', POWER_JS_URL . "/menu/superfish{$this->suffix}.js", [ 'jquery', 'hoverIntent' ], '1.7.10', true );
		wp_register_script( 'superfish-args', apply_filters( 'power_superfish_args_url', POWER_JS_URL . "/menu/superfish.args{$this->suffix}.js" ), [ 'superfish' ], PARENT_THEME_VERSION, true );
		wp_register_script( 'skip-links', POWER_JS_URL . "/skip-links{$this->suffix}.js", [], PARENT_THEME_VERSION, true );
		wp_register_script( 'drop-down-menu', POWER_JS_URL . "/drop-down-menu{$this->suffix}.js", [ 'jquery' ], PARENT_THEME_VERSION, true );

	}

	/**
	 * Register admin scripts used by Power.
	 *
	 * @since 2.5.0
	 */
	public function register_admin_scripts() {

		if ( power_is_in_dev_mode() ) {
			$this->suffix = '';
		}

		wp_register_script( 'power_admin_js', POWER_JS_URL . "/admin{$this->suffix}.js", [ 'jquery', 'wp-a11y' ], PARENT_THEME_VERSION, true );
		wp_register_script( 'van11y-accessible-modal-window-aria', POWER_JS_URL . '/vendor/modal/van11y-accessible-modal-window-aria.min.js', '', PARENT_THEME_VERSION, true );

	}

	/**
	 * Enqueue scripts specific to the Block Editor.
	 *
	 * @since 3.1.0
	 */
	public function enqueue_block_editor_scripts() {

		$visible_panels = $this->visible_power_sidebar_panels();

		if ( empty( $visible_panels ) ) {
			return;
		}

		wp_enqueue_script(
			'power-sidebar',
			POWER_JS_URL . '/build/power-sidebar.js',
			[ 'wp-components', 'wp-edit-post', 'wp-element', 'wp-plugins', 'wp-polyfill' ],
			PARENT_THEME_VERSION,
			true
		);

		// Breadcrumbs panel.
		if ( isset( $visible_panels['breadcrumbs'] ) ) {
			wp_enqueue_script(
				'power-breadcrumbs-toggle',
				POWER_JS_URL . '/build/breadcrumbs-toggle.js',
				[ 'wp-a11y', 'wp-api-fetch', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-plugins', 'wp-polyfill' ],
				PARENT_THEME_VERSION,
				true
			);
		}

		// Title panel.
		if ( isset( $visible_panels['title'] ) ) {
			wp_enqueue_script(
				'power-title-toggle',
				POWER_JS_URL . '/build/title-toggle.js',
				[ 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-plugins', 'wp-polyfill' ],
				PARENT_THEME_VERSION,
				true
			);
		}

		// Images panel.
		if ( isset( $visible_panels['images'] ) ) {
			wp_enqueue_script(
				'power-image-toggle',
				POWER_JS_URL . '/build/image-toggle.js',
				[ 'wp-a11y', 'wp-api-fetch', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-plugins', 'wp-polyfill' ],
				PARENT_THEME_VERSION,
				true
			);
		}

		// Layout panel.
		if ( isset( $visible_panels['layout'] ) ) {
			wp_enqueue_script(
				'power-layout-toggle',
				POWER_JS_URL . '/build/layout-toggle.js',
				[ 'wp-api-fetch', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-plugins', 'wp-polyfill' ],
				PARENT_THEME_VERSION,
				true
			);
		}

		// Custom Classes panel.
		if ( isset( $visible_panels['custom-classes'] ) ) {
			wp_enqueue_script(
				'power-custom-classes',
				POWER_JS_URL . '/build/custom-classes.js',
				[ 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-plugins', 'wp-polyfill' ],
				PARENT_THEME_VERSION,
				true
			);
		}

		// Set up translations for all scripts with the wp-i18n dependency.
		wp_set_script_translations( 'power-breadcrumbs-toggle', 'power', POWER_LANGUAGES_DIR );
		wp_set_script_translations( 'power-title-toggle', 'power', POWER_LANGUAGES_DIR );
		wp_set_script_translations( 'power-image-toggle', 'power', POWER_LANGUAGES_DIR );
		wp_set_script_translations( 'power-layout-toggle', 'power', POWER_LANGUAGES_DIR );
		wp_set_script_translations( 'power-custom-classes', 'power', POWER_LANGUAGES_DIR );

	}

	/**
	 * Enqueue the scripts used on the front-end of the site.
	 *
	 * Includes comment-reply, superfish and the superfish arguments.
	 *
	 * Applies the `power_superfish_enabled`, and `power_superfish_args_uri`. filter.
	 *
	 * @since 2.5.0
	 */
	public function enqueue_front_scripts() {

		// Scripts not allowed in AMP.
		if ( power_is_amp() ) {
			return false;
		}

		// If a single post or page, threaded comments are enabled, and comments are open.
		if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// If superfish is enabled.
		if ( power_superfish_enabled() ) {

			wp_enqueue_script( 'superfish' );
			wp_enqueue_script( 'superfish-args' );

		}

		// If accessibility support enabled.
		if ( power_a11y( 'skip-links' ) ) {
			wp_enqueue_script( 'skip-links' );
		}

	}

	/**
	 * Conditionally enqueue the scripts used in the admin.
	 *
	 * Includes Thickbox, theme preview and a Power script (actually enqueued in power_load_admin_js()).
	 *
	 * @since 2.5.0
	 *
	 * @param string $hook_suffix Admin page identifier.
	 */
	public function maybe_enqueue_admin_scripts( $hook_suffix ) {

		if ( power_is_menu_page( 'power-getting-started' ) ) {
			wp_enqueue_script( 'van11y-accessible-modal-window-aria' );
		}

		// Only add thickbox/preview if there is an update to Power available.
		if ( power_update_check() ) {

			add_thickbox();
			wp_enqueue_script( 'theme-preview' );
			$this->enqueue_and_localize_admin_scripts();
			return;

		}

		// If we're on a Power admin screen.
		if ( power_is_menu_page( 'power' ) || power_is_menu_page( 'seo-settings' ) || power_is_menu_page( 'design-settings' ) || power_is_menu_page( 'power-getting-started' ) ) {

			$this->enqueue_and_localize_admin_scripts();
			return;

		}

		// If we're viewing an edit post page, make sure we need Power SEO JS.
		if (
			in_array( $hook_suffix, [ 'post-new.php', 'post.php' ], true )
			&& ! power_seo_disabled()
			&& post_type_supports( get_post_type(), 'power-seo' )
		) {
				$this->enqueue_and_localize_admin_scripts();
		}

	}

	/**
	 * Enqueues the custom script used in the admin, and localizes several strings or values used in the scripts.
	 *
	 * Applies the `power_toggles` filter to toggleable admin settings, so plugin developers can add their own without
	 * having to recreate the whole setup.
	 *
	 * @since 2.5.0
	 */
	public function enqueue_and_localize_admin_scripts() {

		wp_enqueue_script( 'power_admin_js' );

		$strings = [
			'categoryChecklistToggle' => __( 'Select / Deselect All', 'power' ),
			'saveAlert'               => __( 'The changes you made will be lost if you navigate away from this page.', 'power' ),
			'confirmUpgrade'          => __( 'Updating Power will overwrite the current installed version of Power. Are you sure you want to update?. "Cancel" to stop, "OK" to update.', 'power' ),
			'confirmReset'            => __( 'Are you sure you want to reset?', 'power' ),
		];

		wp_localize_script( 'power_admin_js', 'powerL10n', $strings );

		$toggles = [
			// Checkboxes - when checked, show extra settings.
			'update'                    => [ '#power-settings\\[update\\]', '#power_update_notification_setting', '_checked' ],
			'content_archive_thumbnail' => [ '#power-settings\\[content_archive_thumbnail\\]', '#power_image_extras', '_checked' ],
			// Checkboxes - when unchecked, show extra settings.
			'semantic_headings'         => [ '#power-seo-settings\\[semantic_headings\\]', '#power_seo_h1_wrap', '_unchecked' ],
			// Select toggles.
			'nav_extras'                => [ '#power-settings\\[nav_extras\\]', '#power_nav_extras_twitter', 'twitter' ],
			'content_archive'           => [ '#power-settings\\[content_archive\\]', '#power_content_limit_setting', 'full' ],
		];

		wp_localize_script( 'power_admin_js', 'power_toggles', apply_filters( 'power_toggles', $toggles ) );

		$onboarding = [
			'nonce' => wp_create_nonce( 'power-onboarding' ),
			'l10n'  => [
				'a11y' => [
					'onboarding_started'  => esc_html__( 'The website setup process has started.', 'power' ),
					'onboarding_complete' => esc_html__( 'The website setup process has completed.', 'power' ),
					'step_started'        => esc_html__( 'A setup step has started.', 'power' ),
					'step_completed'      => esc_html__( 'A setup step has completed.', 'power' ),
				],
			],
		];

		if ( power_is_menu_page( 'power-getting-started' ) ) {
			$onboarding['tasks'] = power_onboarding_tasks();
		}

		wp_localize_script( 'power_admin_js', 'power_onboarding', $onboarding );
	}

	/**
	 * Enable whitelisted attributes on registered scripts by adding `...=true` to the script URL.
	 *
	 * @since 2.6.0
	 *
	 * @param string $tag    The script tag, generated by WordPress.
	 * @param string $handle The handle for the registered script.
	 * @param string $src    The source URL for the script.
	 * @return string $tag The (maybe) reformatted script tag.
	 */
	public function maybe_enable_attrs( $tag, $handle, $src ) {

		$supported_attributes = [
			'async',
			'defer',
		];

		$decoded_src = wp_specialchars_decode( $src );

		$query = wp_parse_url( $decoded_src, PHP_URL_QUERY );

		if ( ! $query ) {
			return $tag;
		}

		wp_parse_str( $query, $query_args );

		foreach ( $supported_attributes as $attr ) {

			if ( isset( $query_args[ $attr ] ) && 'true' === $query_args[ $attr ] ) {

				$new_src = esc_url( remove_query_arg( $attr, $decoded_src ) );

				$tag = power_strip_attr( $tag, 'script', $attr );
				$tag = str_replace( ' src=', ' ' . esc_attr( $attr ) . ' src=', $tag );
				$tag = str_replace( $src, $new_src, $tag );
			}
		}

		return $tag;

	}

	/**
	 * Determines which Power sidebar panels should be visible for the current post type.
	 *
	 * Intended for use on WordPress admin pages only.
	 *
	 * @since 3.1.1
	 *
	 * @param string $post_type Defaults to current post type if not passed.
	 * @return array Power editor sidebar panels that should be displayed for the given post type, with key as panel name.
	 */
	public function visible_power_sidebar_panels( $post_type = '' ) {

		$visible_panels = [];

		if ( ! $post_type ) {
			$current_screen = get_current_screen();
			$post_type      = $current_screen->post_type ?: '';
		}

		if ( ! post_type_supports( $post_type, 'custom-fields' ) ) {
			return [];
		}

		$post_type_is_public = false;
		$post_type_info      = get_post_type_object( $post_type );

		if ( ! is_null( $post_type_info ) && $post_type_info->public ) {
			$post_type_is_public = true;
		}

		// Breadcrumbs panel.
		$breadcrumbs_toggle_enabled  = apply_filters( 'power_breadcrumbs_toggle_enabled', true );
		$supports_breadcrumbs_toggle = post_type_supports( $post_type, 'power-breadcrumbs-toggle' );

		if (
			current_theme_supports( 'power-breadcrumbs' )
			&& $breadcrumbs_toggle_enabled
			&& $supports_breadcrumbs_toggle
		) {
			$visible_panels['breadcrumbs'] = 1;
		}

		// Title panel.
		$title_toggle_enabled  = apply_filters( 'power_title_toggle_enabled', true );
		$supports_title_toggle = post_type_supports( $post_type, 'power-title-toggle' );

		if ( $title_toggle_enabled && $supports_title_toggle ) {
			$visible_panels['title'] = 1;
		}

		// Image panel.
		$editing_blog_page    = 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_for_posts' ) === get_the_ID();
		$image_toggle_enabled = apply_filters( 'power_singular_image_toggle_enabled', true );

		if (
			$image_toggle_enabled
			&& post_type_supports( $post_type, 'power-singular-images' )
			&& ! $editing_blog_page
		) {
			$visible_panels['images'] = 1;
		}

		// Layout panel.
		$inpost_layouts_supported = current_theme_supports( 'power-inpost-layouts' );
		$supports_power_layouts = post_type_supports( $post_type, 'power-layouts' );

		if ( $inpost_layouts_supported && $supports_power_layouts ) {
			$visible_panels['layout'] = 1;
		}

		// Custom classes panel.
		if ( $post_type_is_public ) {
			$visible_panels['custom-classes'] = 1;
		}

		return $visible_panels;

	}

}
