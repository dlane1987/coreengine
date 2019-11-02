<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Options
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

use \CoreEngine\Power\Admin\WidgetImport;

/**
 * Return a cached onboarding config.
 *
 * @since 2.10.0
 *
 * @return array $config The onboarding config.
 */
function power_onboarding_config() {
	static $config = null;

	if ( null === $config ) {
		$config = power_get_config( 'onboarding' );
	}

	return $config;
}

/**
 * Determine if the onboarding feature is properly enabled (via config) in a child theme.
 *
 * @since 2.10.0
 *
 * @return bool True if config exists and at least one feature is configured. False otherwise.
 */
function power_onboarding_active() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return false;
	}

	if ( ! power_onboarding_config() ) {
		return false;
	}

	if (
		power_onboarding_plugins()
		|| power_onboarding_content()
		|| power_onboarding_navigation_menus()
		|| power_onboarding_starter_packs()

	) {
		return true;
	}

	return false;
}

/**
 * Returns an array of onboarding plugins provided by Power or the child theme.
 *
 * @since 2.8.0
 *
 * @return array
 */
function power_onboarding_plugins() {
	$config = power_onboarding_config();

	$starter_packs = power_onboarding_starter_packs();
	$chosen_pack   = get_option( 'power_onboarding_chosen_pack' );

	if ( $starter_packs && $chosen_pack && isset( $starter_packs[ $chosen_pack ]['config']['dependencies']['plugins'] ) ) {
		return $starter_packs[ $chosen_pack ]['config']['dependencies']['plugins'];
	}

	return isset( $config['dependencies']['plugins'] ) ? (array) $config['dependencies']['plugins'] : [];
}

/**
 * Returns an array of widgets provided by Power or the child theme.
 *
 * @since 3.1.0
 *
 * @return array
 */
function power_onboarding_widgets() {
	$config = power_onboarding_config();

	$starter_packs = power_onboarding_starter_packs();
	$chosen_pack   = get_option( 'power_onboarding_chosen_pack' );

	if ( $starter_packs && $chosen_pack && isset( $starter_packs[ $chosen_pack ]['config']['widgets'] ) ) {
		return $starter_packs[ $chosen_pack ]['config']['widgets'];
	}

	return isset( $config['widgets'] ) ? (array) $config['widgets'] : [];
}

/**
 * Returns an unordered list of valid onboarding plugins provided by Power or the child theme.
 *
 * @since 2.9.0
 *
 * @param string $pack The starter pack slug to return the plugins list for.
 * @return string An unordered list of plugins, or empty string if no valid plugins in list.
 */
function power_onboarding_plugins_list( $pack = '' ) {
	$plugins = power_onboarding_plugins();

	if ( $pack ) {
		$config = power_onboarding_config();
		if ( isset( $config['starter_packs'][ $pack ]['config']['dependencies']['plugins'] ) ) {
			$plugins = $config['starter_packs'][ $pack ]['config']['dependencies']['plugins'];
		}
	}

	if ( ! $plugins ) {
		return '';
	}

	$plugin_list = '';

	$link_pattern = '<a href="%s" target="_blank" rel="noopener noreferrer">%s <span class="screen-reader-text">(%s)</span></a>';
	$new_window   = __( 'new window', 'power' );

	foreach ( $plugins as $plugin ) {
		if ( empty( $plugin['name'] ) || empty( $plugin['slug'] ) ) {
			continue;
		}
		$plugin_list_item = isset( $plugin['public_url'] ) ? sprintf( $link_pattern, esc_url( $plugin['public_url'] ), esc_html( $plugin['name'] ), esc_html( $new_window ) ) : esc_html( $plugin['name'] );
		$plugin_list     .= sprintf( '<li>%s</li>', $plugin_list_item );
	}

	if ( $plugin_list ) {
		$plugin_list = sprintf( '<ul>%s</ul>', $plugin_list );
	}

	if ( $pack ) {
		$title       = sprintf( '<h2>%s</h2>', esc_html__( 'Recommended plugins', 'power' ) );
		$plugin_list = $title . $plugin_list;
	}

	return $plugin_list;
}

/**
 * Build HTML for an unordered list of onboarding content provided the child theme.
 *
 * @since 3.1.0
 *
 * @param string $pack The starter pack slug to return the plugins list for.
 * @return string An unordered list of plugins, or empty string if no valid plugins in list.
 */
function power_onboarding_content_list( $pack = '' ) {
	$content = power_onboarding_content();

	if ( $pack ) {
		$config = power_onboarding_config();
		if ( isset( $config['starter_packs'][ $pack ]['config']['content'] ) ) {
			$content = $config['starter_packs'][ $pack ]['config']['content'];
		}
	}

	if ( ! $content ) {
		return '';
	}

	$content_list = '';

	foreach ( $content as $item ) {
		if ( empty( $item['post_title'] ) ) {
			continue;
		}
		$content_list .= sprintf( '<li>%s</li>', $item['post_title'] );
	}

	if ( $content_list ) {
		$content_list = sprintf( '<ul>%s</ul>', $content_list );
	}

	if ( $pack ) {
		$title        = sprintf( '<h2>%s</h2>', esc_html__( 'Demo content', 'power' ) );
		$content_list = $title . $content_list;
	}

	return $content_list;
}

/**
 * Output HTML to show a selection of starter packs if supported by the theme.
 *
 * @since 3.1.0
 */
function power_onboarding_starter_packs_list() {
	$packs = power_onboarding_starter_packs();

	if ( ! $packs ) {
		esc_html_e( 'No Starter Packs found.', 'power' );
	}

	foreach ( $packs as $pack_slug => $pack ) {
		if ( empty( $pack['title'] ) || empty( $pack['thumbnail'] ) ) {
			continue;
		}

		$pack_image = sprintf(
			'<img src="%1s" alt="%2s" />',
			esc_url( $pack['thumbnail'] ),
			/* translators: %s: Starter Pack name, such as “Small Business” */
			sprintf( esc_attr__( 'Learn more about the %s starter pack.', 'power' ), $pack['title'] )
		);

		$pack_install_label = sprintf(
			/* translators: %s: Starter Pack name, such as “Small Business” */
			__( 'Install the %s starter pack.', 'power' ),
			$pack['title']
		);

		$pack_demo_label = sprintf(
			/* translators: %s: Starter Pack name, such as “Small Business” */
			__( 'View the %s starter pack demo (opens in new window).', 'power' ),
			$pack['title']
		);

		include POWER_VIEWS_DIR . '/onboarding/starter-pack-summary.php';
		include POWER_VIEWS_DIR . '/onboarding/starter-pack-info.php';
	}
}

/**
 * Returns an array of onboarding content provided by Power or the child theme.
 *
 * @since 2.8.0
 *
 * @return array
 */
function power_onboarding_content() {
	$config = power_onboarding_config();

	$starter_packs = power_onboarding_starter_packs();
	$chosen_pack   = get_option( 'power_onboarding_chosen_pack' );

	if ( $starter_packs && $chosen_pack && isset( $starter_packs[ $chosen_pack ]['config']['content'] ) ) {
		return $starter_packs[ $chosen_pack ]['config']['content'];
	}

	return isset( $config['content'] ) ? $config['content'] : [];
}

/**
 * Installs plugin language packs during the onboarding process.
 *
 * Hooked to the 'upgrader_process_complete' action.
 *
 * @since 2.8.0
 */
function power_onboarding_install_language_packs() {

	$language_updates = wp_get_translation_updates();

	if ( empty( $language_updates ) ) {
		return;
	}

	$lp_upgrader = new Language_Pack_Upgrader( new Power_Silent_Upgrader_Skin() );
	$lp_upgrader->bulk_upgrade( $language_updates );
}

/**
 * Returns an array of onboarding navigation menu configuration data
 * provided by Power or the child theme.
 *
 * @since 2.9.0
 * @return array
 */
function power_onboarding_navigation_menus() {
	$config = power_onboarding_config();

	$starter_packs = power_onboarding_starter_packs();
	$chosen_pack   = get_option( 'power_onboarding_chosen_pack' );

	if ( $starter_packs && $chosen_pack && isset( $starter_packs[ $chosen_pack ]['config']['navigation_menus'] ) ) {
		return $starter_packs[ $chosen_pack ]['config']['navigation_menus'];
	}

	return isset( $config['navigation_menus'] ) ? $config['navigation_menus'] : [];
}

/**
 * Returns an array of onboarding starter pack configuration data
 * provided by Power or the child theme.
 *
 * @since 3.1.0
 * @return array
 */
function power_onboarding_starter_packs() {
	$config = power_onboarding_config();

	return isset( $config['starter_packs'] ) ? $config['starter_packs'] : [];
}

/**
 * Gets onboarding tasks from those declared in the theme's `onboarding.php`.
 *
 * An onboarding task is a step during the theme setup process, such as
 * installing plugins or adding page content.
 *
 * @since 3.1.0
 *
 * @return array The tasks to run.
 */
function power_onboarding_tasks() {
	$plugins = power_onboarding_plugins();
	$content = power_onboarding_content();
	$tasks   = [];

	if ( ! empty( $plugins ) ) {
		$tasks[] = 'dependencies';
	}

	if ( ! empty( $content ) ) {
		$tasks[] = 'content';
	}

	return $tasks;
}

/**
 * Creates the navigation menus based on the configuration
 * provided in the child theme.
 *
 * @since 2.9.0
 * @return array Empty array if successful, an array of error messages if not.
 */
function power_onboarding_create_navigation_menus() {

	$errors = [];
	$config = power_onboarding_navigation_menus();
	if ( ! $config ) {
		return $errors;
	}
	$menu_locations   = get_theme_mod( 'nav_menu_locations' );
	$registered_menus = (array) get_theme_support( 'power-menus' );
	$registered_menus = reset( $registered_menus );

	foreach ( $registered_menus as $registered_menu => $menu_label ) {
		if ( empty( $menu_label ) || empty( $config[ $registered_menu ] ) ) {
			continue;
		}

		$menu_label = power_unique_menu_name( $menu_label );
		$menu_id    = wp_create_nav_menu( $menu_label );

		if ( is_wp_error( $menu_id ) ) {
			/* translators: 1: Title of the menu, 2: The error message. */
			$errors[] = sprintf( esc_html__( 'There was an error creating the %1$s menu. Error: %2$s', 'power' ), $menu_label, $menu_id->get_error_message() );
			continue;
		}

		$menu_locations[ $registered_menu ] = $menu_id;

		set_theme_mod( 'nav_menu_locations', $menu_locations );
	}

	return $errors;
}

/**
 * Creates the navigation menu items based on the configuration
 * provided in the child theme.
 *
 * @since 2.9.0
 * @return array Empty array if successful, an array of error messages if not.
 */
function power_onboarding_create_navigation_menu_items() {
	$errors         = [];
	$menus_config   = power_onboarding_navigation_menus();
	$menu_locations = get_nav_menu_locations();
	$imported_posts = get_option( 'power_onboarding_imported_post_ids', [] );

	foreach ( $menus_config as $menu_location => $menu_location_config ) {
		if ( ! isset( $menu_locations[ $menu_location ] ) ) {
			continue;
		}

		$menu_id = $menu_locations[ $menu_location ];

		$new_menu_item = [];

		foreach ( $menu_location_config as $slug => $menu_item ) {

			$new_menu_item[ $slug ] = [];

			if ( ! empty( $menu_item['parent'] ) ) {
				$new_menu_item[ $slug ]['parent'] = $menu_item['parent'];
			}

			$post_object = get_post( $imported_posts[ $slug ] );

			if ( empty( $post_object ) ) {
				continue;
			}

			$menu_item_parent_id = ! empty( $menu_item['parent'] ) && ! empty( $new_menu_item[ $menu_item['parent'] ] ) && ! empty( $new_menu_item[ $menu_item['parent'] ]['id'] ) ? $new_menu_item[ $menu_item['parent'] ]['id'] : 0;

			$nav_menu_item_id = wp_update_nav_menu_item(
				$menu_id,
				0,
				[
					'menu-item-title'     => $menu_item['title'],
					'menu-item-status'    => 'publish',
					'menu-item-type'      => 'post_type',
					'menu-item-object'    => $post_object->post_type,
					'menu-item-object-id' => $post_object->ID,
					'menu-item-parent-id' => $menu_item_parent_id,
				]
			);

			if ( is_wp_error( $nav_menu_item_id ) ) {
				/* translators: 1: Title of the menu item, 2: The error message. */
				$errors[] = sprintf( esc_html__( 'There was an error creating the %1$s menu item. Error: %2$s', 'power' ), $menu_item['title'], $nav_menu_item_id->get_error_message() );
				continue;
			}

			$new_menu_item[ $slug ]['id'] = $nav_menu_item_id;
		}
	}

	return $errors;
}


/**
 * Installs the plugin dependencies during onboarding.
 *
 * @param array $dependencies The dependencies config array.
 * @param int   $step The current step being processed.
 * @since 2.9.0
 *
 * @return void|WP_Error
 */
function power_onboarding_install_dependencies( array $dependencies, $step = 0 ) {

	if ( empty( $dependencies ) ) {
		return;
	}

	$step = absint( $step );

	$existing_plugins = get_plugins();

	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$upgrader = new Plugin_Upgrader( new Power_Silent_Upgrader_Skin() );

	$onboarding_plugin = $dependencies[ $step ];

	if ( ! array_key_exists( $onboarding_plugin['slug'], $existing_plugins ) ) {

		remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

		add_action( 'upgrader_process_complete', 'power_onboarding_install_language_packs', 20 );

		$short_slug = strtok( $onboarding_plugin['slug'], '/' );

		$api = plugins_api( 'plugin_information', [ 'slug' => $short_slug ] );

		if ( is_wp_error( $api ) ) {
			/**
			 * Error object from API communication.
			 *
			 * @var WP_Error
			 */
			return $api;
		}

		$installed = $upgrader->install( $api->download_link );

		if ( is_wp_error( $installed ) ) {
			/**
			 * Error object from installation process.
			 *
			 * @var WP_Error
			 */
			return $installed;
		}
	}

	activate_plugin( $onboarding_plugin['slug'], false, false, true );
}

/**
 * Imports the demo content during onboarding.
 *
 * @param array $content The content config array.
 * @since 2.9.0
 *
 * @return array
 */
function power_onboarding_import_content( array $content ) {

	$errors = [];

	$homepage_edit_link = false;

	$post_defaults = [
		'post_content'   => '',
		'post_excerpt'   => '',
		'post_status'    => 'publish',
		'post_title'     => '',
		'post_type'      => 'post',
		'comment_status' => 'closed',
		'ping_status'    => 'closed',
	];

	if ( ! empty( $content ) ) {

		/**
		 * Fire before content is imported.
		 *
		 * @since  2.10.0
		 */
		do_action( 'power_onboarding_before_import_content', $content );

		$imported_post_ids = [];

		foreach ( $content as $key => $post ) {

			$post = wp_parse_args( $post, $post_defaults );

			$post_id = wp_insert_post( $post );

			if ( is_wp_error( $post_id ) ) {
				/* translators: 1: Title of the page, 2: The error message. */
				$errors[] = sprintf( esc_html__( 'There was an error importing the %1$s page. Error: %2$s', 'power' ), $post['post_title'], $post_id->get_error_message() );
				continue;
			}

			$imported_post_ids[ $key ] = $post_id;

			if ( 'homepage' === $key ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $post_id );
				$homepage_edit_link = esc_url_raw( admin_url( 'post.php?action=edit&post=' . $post_id ) );
			}

			if ( 'blog' === $key ) {
				update_option( 'page_for_posts', $post_id );
			}

			if ( ! empty( $post['page_template'] ) ) {
				update_post_meta( $post_id, '_wp_page_template', sanitize_text_field( $post['page_template'] ) );
			}

			if ( ! empty( $post['featured_image'] ) ) {

				$featured_image_url  = esc_url_raw( $post['featured_image'] );
				$remote_image_import = wp_http_validate_url( $featured_image_url );
				$local_image_path    = $featured_image_url;

				if ( $remote_image_import ) {
					$local_image_path = download_url( $featured_image_url );
				}

				if ( is_wp_error( $local_image_path ) ) {
					/* translators: 1: URL of the image, 2: The error message. */
					$errors[] = sprintf( esc_html__( 'There was an error downloading the featured image from %1$s. Error: %2$s', 'power' ), $featured_image_url, $local_image_path->get_error_message() );
					continue;
				}

				if ( ! is_readable( $local_image_path ) ) {
					/* translators: %s: Path to local image file. */
					$errors[] = sprintf( esc_html__( 'Could not read the file: %s.', 'power' ), $local_image_path );
					continue;
				}

				$file = [
					'name'     => basename( $featured_image_url ),
					'tmp_name' => $local_image_path,
				];

				$attachment_id = media_handle_sideload( $file, $post_id );

				if ( is_wp_error( $attachment_id ) ) {
					/* translators: 1: Name of the image, 2: The error message. */
					$errors[] = sprintf( esc_html__( 'There was an error importing the %1$s image. Error: %2$s', 'power' ), $file['name'], $attachment_id->get_error_message() );
					continue;
				}

				set_post_thumbnail( $post_id, $attachment_id );

				if ( $remote_image_import && is_readable( $local_image_path ) ) {
					unlink( $local_image_path );
				}

			}
		}

		/**
		 * Fire after content is imported.
		 *
		 * @since  2.10.0
		 */
		do_action( 'power_onboarding_after_import_content', $content, $imported_post_ids );

		// Save the imported post IDs for use during menu item creation.
		update_option( 'power_onboarding_imported_post_ids', $imported_post_ids, false );

	}

	return [
		'homepage_edit_link' => $homepage_edit_link,
		'errors'             => $errors,
	];
}

add_action( 'power_onboarding_after_import_content', 'power_onboarding_import_widgets', 10, 2 );
/**
 * Inserts widgets from the onboarding config file.
 *
 * @since 3.1.0
 *
 * @param array $content The content config.
 * @param array $imported_posts Imported posts with content short name as keys and IDs as values.
 */
function power_onboarding_import_widgets( $content, $imported_posts ) {
	$widget_areas = power_onboarding_widgets();

	if ( ! $widget_areas ) {
		return;
	}

	// Move widgets in areas we are going to populate to the Inactive Widgets area.
	WidgetImport\clear_widget_areas( array_keys( $widget_areas ) );

	foreach ( $widget_areas as $area => $widgets ) {
		foreach ( $widgets as $widget ) {
			$widget_args = WidgetImport\swap_placeholders( $widget['args'], $imported_posts );
			WidgetImport\insert_widget( $area, $widget['type'], $widget_args );
		}
	}
}