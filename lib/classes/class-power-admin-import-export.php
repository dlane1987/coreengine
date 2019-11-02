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
 * Register a new admin page, providing content and corresponding menu item for the Import / Export page.
 *
 * Although this class was added in 1.8.0, some of the methods were originally standalone functions added in previous
 * versions of Power.
 *
 * @package Power\Admin
 *
 * @since 1.8.0
 */
class Power_Admin_Import_Export extends Power_Admin_Basic {

	/**
	 * Create an admin menu item and settings page.
	 *
	 * Also hook in the handling of file imports and exports.
	 *
	 * @since 1.8.0
	 *
	 * @see \Power_Admin_Import_Export::export() Handle settings file exports.
	 * @see \Power_Admin_Import_Export::import() Handle settings file imports.
	 */
	public function __construct() {

		$this->help_base = POWER_VIEWS_DIR . '/help/import-export-';

		$page_id = 'power-import-export';

		$menu_ops = [
			'submenu' => [
				'parent_slug' => 'power',
				'page_title'  => __( 'Power - Import/Export', 'power' ),
				'menu_title'  => __( 'Import/Export', 'power' ),
			],
		];

		$this->create( $page_id, $menu_ops );

		add_action( 'admin_init', [ $this, 'export' ] );
		add_action( 'admin_init', [ $this, 'import' ] );

	}

	/**
	 * Contextual help content.
	 *
	 * @since 2.0.0
	 */
	public function help() {

		$this->add_help_tab( 'general', __( 'Import/Export', 'power' ) );
		$this->add_help_tab( 'import', __( 'Import', 'power' ) );
		$this->add_help_tab( 'export', __( 'Export', 'power' ) );

		// Add help sidebar.
		$this->set_help_sidebar();

	}

	/**
	 * Callback for displaying the Power Import / Export admin page.
	 *
	 * Call the power_import_export_form action after the last default table row.
	 *
	 * @since 1.4.0
	 */
	public function admin() {

		include POWER_VIEWS_DIR . '/pages/power-admin-import-export.php';

	}

	/**
	 * Add custom notices that display after successfully importing or exporting the settings.
	 *
	 * @since 1.4.0
	 *
	 * @return void Return early if not on the correct admin page.
	 */
	public function notices() {

		if ( ! power_is_menu_page( 'power-import-export' ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- We don't need nonce verification here
		if ( isset( $_REQUEST['imported'] ) && 'true' === $_REQUEST['imported'] ) {
			printf( '<div id="message" class="updated" role="alert"><p><strong>%s</strong></p></div>', esc_html__( 'Settings successfully imported.', 'power' ) );
		} elseif ( isset( $_REQUEST['error'] ) && 'true' === $_REQUEST['error'] ) {
			printf( '<div id="message" class="error" role="alert"><p><strong>%s</strong></p></div>', esc_html__( 'There was a problem importing your settings. Please try again.', 'power' ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

	}

	/**
	 * Return array of export options and their arguments.
	 *
	 * Plugins and themes can hook into the power_export_options filter to add
	 * their own settings to the exporter.
	 *
	 * @since 1.6.0
	 *
	 * @return array Export options.
	 */
	protected function get_export_options() {

		$options = [
			'theme' => [
				'label'          => __( 'Theme Settings', 'power' ),
				'settings-field' => POWER_SETTINGS_FIELD,
			],
			'seo'   => [
				'label'          => __( 'SEO Settings', 'power' ),
				'settings-field' => POWER_SEO_SETTINGS_FIELD,
			],
		];

		return (array) apply_filters( 'power_export_options', $options );

	}

	/**
	 * Echo out the checkboxes for the export options.
	 *
	 * @since 1.6.0
	 *
	 * @return void Return early if there are no options to export.
	 */
	protected function export_checkboxes() {

		$options = $this->get_export_options();

		if ( ! $options ) {
			// Not even the Power theme / seo export options were returned from the filter.
			printf( '<p><em>%s</em></p>', esc_html__( 'No export options available.', 'power' ) );
			return;
		}

		foreach ( $options as $name => $args ) {
			// Ensure option item has an array key, and that label and settings-field appear populated.
			if ( is_int( $name ) || ! isset( $args['label'], $args['settings-field'] ) || '' === $args['label'] || '' === $args['settings-field'] ) {
				return;
			}

			printf( '<p><label for="power-export-%1$s"><input id="power-export-%1$s" name="power-export[%1$s]" type="checkbox" value="1" /> %2$s</label></p>', esc_attr( $name ), esc_html( $args['label'] ) );

		}

	}

	/**
	 * Generate the export file, if requested, in JSON format.
	 *
	 * After checking we're on the right page, and trying to export, loop through the list of requested options to
	 * export, grabbing the settings from the database, and building up a file name that represents that collection of
	 * settings.
	 *
	 * A .json file is then sent to the browser, named with "power" at the start and ending with the current
	 * date-time.
	 *
	 * The power_export action is fired after checking we can proceed, but before the array of export options are
	 * retrieved.
	 *
	 * @since 1.4.0
	 *
	 * @return null Return early if not on the correct page, or we're not exporting settings.
	 */
	public function export() {

		if ( ! power_is_menu_page( 'power-import-export' ) ) {
			return;
		}

		if ( empty( $_REQUEST['power-export'] ) ) {
			return;
		}

		check_admin_referer( 'power-export', 'power-export-nonce' );

		$export_data = $_REQUEST['power-export'];

		/**
		 * Fires before export happens, after admin referer has been checked.
		 *
		 * @since 1.4.0
		 *
		 * @param string Value of `power-export` request variable, containing a list of which settings to export.
		 */
		do_action( 'power_export', $export_data );

		$options = $this->get_export_options();

		$settings = [];

		// Exported file name always starts with "power".
		$prefix = [ 'power' ];

		// Loop through set(s) of options.
		foreach ( (array) $export_data as $export => $value ) {
			// Grab settings field name (key).
			$settings_field = $options[ $export ]['settings-field'];

			// Grab all of the settings from the database under that key.
			$settings[ $settings_field ] = get_option( $settings_field );

			// Add name of option set to build up export file name.
			$prefix[] = $export;
		}

		if ( ! $settings ) {
			return;
		}

		// Complete the export file name by joining parts together.
		$prefix = implode( '-', $prefix );

		$output = wp_json_encode( $settings );

		// Prepare and send the export file to the browser.
		header( 'Content-Description: File Transfer' );
		header( 'Cache-Control: public, must-revalidate' );
		header( 'Pragma: hack' );
		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="' . $prefix . '-' . date( 'Ymd-His' ) . '.json"' );
		header( 'Content-Length: ' . mb_strlen( $output ) );
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;

	}

	/**
	 * Handle the file uploaded to import settings.
	 *
	 * Upon upload, the file contents are JSON-decoded. If there were errors, or no options to import, then reload the
	 * page to show an error message.
	 *
	 * Otherwise, loop through the array of option sets, and update the data under those keys in the database.
	 * Afterwards, reload the page with a success message.
	 *
	 * Calls power_import action is fired after checking we can proceed, but before attempting to extract the contents
	 * from the uploaded file.
	 *
	 * @since 1.4.0
	 *
	 * @return null Return early if not on the correct admin page, or we're not importing settings.
	 */
	public function import() {

		if ( ! power_is_menu_page( 'power-import-export' ) ) {
			return;
		}

		if ( empty( $_REQUEST['power-import'] ) ) {
			return;
		}

		check_admin_referer( 'power-import', 'power-import-nonce' );

		/**
		 * Fires before importing settings, but after admin referer has been checked
		 *
		 * @since 1.4.0
		 *
		 * @param string $import Value of `power-import` request variable, containing list of which settings to import.
		 * @param array  $file   Reference to uploaded file to import.
		 */
		do_action( 'power_import', $_REQUEST['power-import'], $_FILES['power-import-upload'] );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$upload = file_get_contents( $_FILES['power-import-upload']['tmp_name'] );

		$options = json_decode( $upload, true );

		// Check for errors.
		if ( ! $options || $_FILES['power-import-upload']['error'] ) {
			power_admin_redirect(
				'power-import-export',
				[
					'error' => 'true',
				]
			);
			exit;
		}

		// Identify the settings keys that we should import.
		$exportables     = $this->get_export_options();
		$importable_keys = [];
		foreach ( $exportables as $exportable ) {
			$importable_keys[] = $exportable['settings-field'];
		}

		// Cycle through data, import Power settings.
		foreach ( (array) $options as $key => $settings ) {
			if ( in_array( $key, $importable_keys, true ) ) {
				update_option( $key, $settings );
			}
		}

		// Redirect, add success flag to the URI.
		power_admin_redirect(
			'power-import-export',
			[
				'imported' => 'true',
			]
		);
		exit;

	}

}