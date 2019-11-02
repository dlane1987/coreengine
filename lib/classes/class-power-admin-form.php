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
 * Abstract subclass of Power_Admin which adds support for displaying a form.
 *
 * This class must be extended when creating an admin page with a form, and the
 * settings_form() method must be defined in the subclass.
 *
 * @since 1.8.0
 *
 * @package Power\Admin
 */
abstract class Power_Admin_Form extends Power_Admin {

	/**
	 * Output settings page form elements.
	 *
	 * Must be overridden in a subclass, or it obviously won't work.
	 *
	 * @since 1.8.0
	 */
	abstract public function form();

	/**
	 * Normal settings page admin.
	 *
	 * Includes the necessary markup, form elements, etc.
	 * Hook to {$this->pagehook}_settings_page_form to insert table and settings form.
	 *
	 * Can be overridden in a child class to achieve complete control over the settings page output.
	 *
	 * @since 1.8.0
	 */
	public function admin() {

		include POWER_VIEWS_DIR . '/pages/power-admin-form.php';

	}

	/**
	 * Initialize the settings page, by hooking the form into the page.
	 *
	 * @since 1.8.0
	 */
	public function settings_init() {

		add_action( "{$this->pagehook}_settings_page_form", [ $this, 'form' ] );

	}

}