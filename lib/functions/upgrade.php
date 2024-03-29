<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Updates
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

/**
 * Ping https://api.powertheme.com/ asking if a new version of this theme is available.
 *
 * If not, it returns false.
 *
 * If so, the external server passes serialized data back to this function, which gets unserialized and returned for use.
 *
 * Applies `power_update_remote_post_options` filter.
 *
 * Ping occurs at a maximum of once every 24 hours.
 *
 * @since 1.1.0
 *
 * @global string $wp_version WordPress version string.
 *
 * @return array Unserialized data, or empty array if updates are disabled or
 *               theme does not support `power-auto-updates`.
 */
function power_update_check() {

	// If updates are disabled.
	if ( ! power_get_option( 'update' ) || ! current_theme_supports( 'power-auto-updates' ) ) {
		return [];
	}

	// Use cache.
	static $power_update = null;

	// If cache is empty, pull setting.
	if ( ! $power_update ) {
		$power_update = power_get_expiring_setting( 'update' );
	}

	// If setting has expired, do a fresh update check.
	if ( ! $power_update ) {

		$update_config = require POWER_CONFIG_DIR . '/update-check.php';

		/**
		 * Filter the request data sent to the update server.
		 *
		 * @since 1.1.0
		 *
		 * @param array The request data sent to the update server.
		 */
		$update_config['post_args'] = apply_filters(
			'power_update_remote_post_options',
			$update_config['post_args']
		);

		$update_check = new Power_Update_Check( $update_config );

		// If an error occurred, return empty array, store for 1 hour.
		if ( ! $update_check->get_update() ) {
			$power_update = [
				'new_version' => PARENT_THEME_VERSION,
			];
			power_set_expiring_setting( 'update', $power_update, HOUR_IN_SECONDS );
			return [];
		}

		// Else, unserialize.
		$power_update = $update_check->get_update();

		// And store in setting for 24 hours.
		power_set_expiring_setting( 'update', $power_update, DAY_IN_SECONDS );

	}

	// If we're already using the latest version, return empty array.
	if ( version_compare( PARENT_THEME_VERSION, $power_update['new_version'], '>=' ) ) {
		return [];
	}

	return $power_update;

}

/**
 * Upgrade the database to latest version.
 *
 * @since 2.6.0
 */
function power_upgrade_db_latest() {

	// Update Settings.
	power_update_settings(
		[
			'theme_version' => PARENT_THEME_VERSION,
			'db_version'    => PARENT_DB_VERSION,
			'upgrade'       => 1,
		]
	);

}

add_action( 'admin_init', 'power_upgrade', 20 );
/**
 * Update Power to the latest version.
 *
 * This iterative update function will take a Power installation, no matter
 * how old, and update its options to the latest version.
 *
 * It used to iterate over theme version, but now uses a database version
 * system, which allows for changes within pre-releases, too.
 *
 * @since 1.0.1
 *
 * @return void Return early if we're already on the latest version.
 */
function power_upgrade() {

	// Don't do anything if we're on the latest version.
	if ( power_get_db_version() >= PARENT_DB_VERSION ) {
		return;
	}

	global $wp_db_version;

	// If the WP db hasn't been upgraded, make them upgrade first.
	if ( (int) get_option( 'db_version' ) !== (int) $wp_db_version ) {
		wp_safe_redirect( admin_url( 'upgrade.php?_wp_http_referer=' . rawurlencode( wp_unslash( esc_url( $_SERVER['REQUEST_URI'] ) ) ) ) );
		exit;
	}

	$version_map = power_get_config( 'update-versions' );

	foreach ( $version_map as $version ) {
		if ( version_compare( power_get_db_version(), $version, '<' ) ) {
			$upgrader_class = "\CoreEngine\Power\Upgrade\Upgrade_DB_{$version}";

			if ( ! class_exists( $upgrader_class ) ) {
				continue;
			}

			$upgrader = new $upgrader_class();

			if ( ! $upgrader instanceof \CoreEngine\Power\Upgrade\Upgrade_DB_Interface ) {
				continue;
			}

			$upgrader->upgrade();
		}
	}

	// UPDATE DB TO LATEST VERSION.
	if ( power_get_db_version() < PARENT_DB_VERSION ) {
		power_upgrade_db_latest();
	}

	// Clear the cache to prevent a redirect loop in some object caching environments.
	wp_cache_flush();
	wp_cache_delete( 'alloptions', 'options' );

	/**
	 * Fires after upgrade processes have completed.
	 *
	 * @since 1.0.1
	 */
	do_action( 'power_upgrade' );

}

add_action( 'wpmu_upgrade_site', 'power_network_upgrade_site' );
/**
 * Run silent upgrade on each site in the network during a network upgrade.
 *
 * Update Power database settings for all sites in a network during network upgrade process.
 *
 * @since 2.0.0
 *
 * @param int $blog_id Blog ID.
 */
function power_network_upgrade_site( $blog_id ) {

	switch_to_blog( $blog_id );
	$upgrade_url = add_query_arg(
		[
			'action' => 'power-silent-upgrade',
		],
		admin_url( 'admin-ajax.php' )
	);
	restore_current_blog();

	wp_remote_get( esc_url_raw( $upgrade_url ) );

}

add_action( 'wp_ajax_no_priv_power-silent-upgrade', 'power_silent_upgrade' );
/**
 * Power settings upgrade. Silent upgrade (no redirect).
 *
 * Meant to be called via ajax request during network upgrade process.
 *
 * @since 2.0.0
 */
function power_silent_upgrade() {

	remove_action( 'power_upgrade', 'power_upgrade_redirect' );
	power_upgrade();
	exit( 0 );

}

add_action( 'upgrader_process_complete', 'power_update_complete', 10, 2 );
/**
 * Upgrade the Power database after an update has completed.
 *
 * After an update has been completed, send a remote GET request to `admin-ajax.php` to trigger a silent upgrade.
 *
 * @since 2.10.0
 *
 * @param object $upgrader   The upgrader object.
 * @param array  $hook_extra Details about the upgrade process.
 * @return null
 */
function power_update_complete( $upgrader, $hook_extra ) {
	if ( 'update' !== $hook_extra['action'] || 'theme' !== $hook_extra['type'] ) {
		return;
	}

	// Multiple themes are being updated but not Power.
	if ( isset( $hook_extra['themes'] ) && ! in_array( 'power', $hook_extra['themes'], true ) ) {
		return;
	}

	// One theme is being updated but not Power.
	if ( isset( $hook_extra['theme'] ) && 'power' !== $hook_extra['theme'] ) {
		return;
	}

	$silent_upgrade_url = add_query_arg(
		[
			'action' => 'power-silent-upgrade',
		],
		admin_url( 'admin-ajax.php' )
	);

	wp_remote_get(
		$silent_upgrade_url,
		[
			'timeout'  => 0.01,
			'blocking' => false,
		]
	);
}

add_filter( 'update_theme_complete_actions', 'power_update_action_links', 10, 2 );
/**
 * Filter the action links at the end of an update.
 *
 * This function filters the action links that are presented to the user at the end of a theme update. If the theme
 * being updated is not Power, the filter returns the default values. Otherwise, it will provide its own links.
 *
 * @since 1.1.3
 *
 * @param array  $actions Existing array of action links.
 * @param string $theme   Theme name.
 * @return array Replace all existing action links, if Power is the theme being updated.
 *               Otherwise, return existing action links.
 */
function power_update_action_links( array $actions, $theme ) {
	if ( 'power' !== $theme ) {
		return $actions;
	}

	return [
		sprintf(
			'<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
			'https://powerchangelog.com/',
			esc_html__( 'Check out what\'s new', 'power' )
		),
		sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'customize.php?autofocus[panel]=power' ),
			esc_html__( 'Theme Settings', 'power' )
		),
	];
}

add_action( 'admin_notices', 'power_update_nag' );
/**
 * Display the update nag at the top of the dashboard if there is a Power update available.
 *
 * @since 1.1.0
 *
 * @return void Return early if there is no available update, or user is not a site administrator,
 *              or file modifications have been disabled.
 */
function power_update_nag() {

	if ( defined( 'DISALLOW_FILE_MODS' ) && true === DISALLOW_FILE_MODS ) {
		return;
	}

	$power_update = power_update_check();

	if ( ! $power_update || ! is_super_admin() ) {
		return;
	}

	echo '<div id="update-nag">';
	printf(
		/* translators: 1: Power version, 2: URL for change log, 3: URL for updating Power. */
		esc_html__( 'Power %1$s is available. %2$s or %3$s.', 'power' ),
		esc_html( $power_update['new_version'] ),
		/* translators: 1: URL for change log, 2: class attribute for anchor, 3: call to action. */
		sprintf(
			'<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( $power_update['changelog_url'] ),
			esc_attr( 'thickbox thickbox-preview' ),
			esc_html__( 'Check out what\'s new', 'power' )
		),
		/* translators: 1: URL for updating Power, 2: class attribute for anchor, 3: call to action. */
		sprintf(
			'<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=power', 'upgrade-theme_power' ) ),
			esc_attr( 'power-js-confirm-upgrade' ),
			esc_html__( 'update now', 'power' )
		)
	);
	echo '</div>';

}

add_action( 'init', 'power_update_email' );
/**
 * Sends out update notification email.
 *
 * Does several checks before finally sending out a notification email to the
 * specified email address, alerting it to a Power update available for that install.
 *
 * @since 1.1.0
 *
 * @return void Return early if email should not be sent.
 */
function power_update_email() {

	// Pull email options from DB.
	$email_on = power_get_option( 'update_email' );
	$email    = power_get_option( 'update_email_address' );

	// If we're not supposed to send an email, or email is blank / invalid, stop.
	if ( ! $email_on || ! is_email( $email ) ) {
		return;
	}

	// Check for updates.
	$update_check = power_update_check();

	// If no new version is available, stop.
	if ( ! $update_check ) {
		return;
	}

	// If we've already sent an email for this version, stop.
	if ( get_option( 'power-update-email' ) === $update_check['new_version'] ) {
		return;
	}

	// Let's send an email.
	/* translators: 1: Power version, 2: URL for current website. */
	$subject = sprintf( __( 'Power %1$s is available for %2$s', 'power' ), esc_html( $update_check['new_version'] ), home_url() );

	/* translators: %s: Power version. */
	$message  = sprintf( __( 'Power %s is now available. We have provided 1-click updates for this theme, so please log into your dashboard and update at your earliest convenience.', 'power' ), esc_html( $update_check['new_version'] ) );
	$message .= "\n\n" . wp_login_url();

	// Update the option so we don't send emails on every pageload.
	update_option( 'power-update-email', $update_check['new_version'], true );

	// Send that puppy!
	wp_mail( sanitize_email( $email ), $subject, $message );

}

add_filter( 'pre_set_site_transient_update_themes', 'power_disable_wporg_updates' );
add_filter( 'pre_set_transient_update_themes', 'power_disable_wporg_updates' );
/**
 * Disable WordPress from giving update notifications on Power or Power child themes.
 *
 * This function filters the value that is saved after WordPress tries to pull theme update transient data from WordPress.org
 *
 * Its purpose is to disable update notifications for Power and Power child themes.
 * This prevents WordPress.org repo themes from being installed over one of our themes.
 *
 * @since 2.0.2
 *
 * @param object $value Update check object.
 * @return object Update check object.
 */
function power_disable_wporg_updates( $value ) {

	foreach ( wp_get_themes() as $theme ) {

		if ( 'power' === $theme->get( 'Template' ) ) {

			unset( $value->response[ $theme->get_stylesheet() ] );

		}
	}

	return $value;

}

add_filter( 'site_transient_update_themes', 'power_update_push' );
add_filter( 'transient_update_themes', 'power_update_push' );
/**
 * Integrate the Power update check into the WordPress update checks.
 *
 * This function filters the value that is returned when WordPress tries to pull theme update transient data.
 *
 * It uses `power_update_check()` to check to see if we need to do an update, and if so, adds the proper array to the
 * `$value->response` object. WordPress handles the rest.
 *
 * @since 1.1.0
 *
 * @param object $value Update check object.
 * @return object Modified update check object.
 */
function power_update_push( $value ) {

	if ( defined( 'DISALLOW_FILE_MODS' ) && true === DISALLOW_FILE_MODS ) {
		return $value;
	}

	if ( isset( $value->response['power'] ) ) {
		unset( $value->response['power'] );
	}

	$power_update = power_update_check();

	if ( $power_update ) {
		$value->response['power'] = $power_update;
	}

	return $value;

}

add_action( 'load-update-core.php', 'power_clear_update_transient' );
add_action( 'load-themes.php', 'power_clear_update_transient' );
/**
 * Delete Power update transient after updates or when viewing the themes page.
 *
 * The server will then do a fresh version check.
 *
 * It also disables the update nag on those pages as well.
 *
 * @since 1.1.0
 *
 * @see power_update_nag()
 */
function power_clear_update_transient() {

	power_delete_expiring_setting( 'update' );
	remove_action( 'admin_notices', 'power_update_nag' );

}

/**
 * Converts array of keys from Power options to vestigial options.
 *
 * This is done for backwards compatibility.
 *
 * @since 1.6.0
 *
 * @access private
 *
 * @param array  $keys    Array of keys to convert. Default is an empty array.
 * @param string $setting Optional. The settings field the original keys are found under. Default is POWER_SETTINGS_FIELD.
 * @return void Return early if no `$keys` were provided, or no new vestigial options are needed.
 */
function _power_vestige( array $keys = [], $setting = POWER_SETTINGS_FIELD ) {

	// If no $keys passed, do nothing.
	if ( ! $keys ) {
		return;
	}

	// Pull options.
	$options = get_option( $setting );
	$vestige = get_option( 'power-vestige' );

	// Cycle through $keys, creating new vestige array.
	$new_vestige = [];
	foreach ( $keys as $key ) {
		if ( isset( $options[ $key ] ) ) {
			$new_vestige[ $key ] = $options[ $key ];
			unset( $options[ $key ] );
		}
	}

	// If no new vestigial options being pushed, do nothing.
	if ( ! $new_vestige ) {
		return;
	}

	// Merge the arrays, if necessary.
	$vestige = $vestige ? wp_parse_args( $new_vestige, $vestige ) : $new_vestige;

	// Insert into options table.
	update_option( 'power-vestige', $vestige );
	update_option( $setting, $options );

}
