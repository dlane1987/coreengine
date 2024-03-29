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
 * Abstract subclass of Power_Admin which adds support for creating a basic
 * admin page that does not make use of a Settings API form or meta boxes.
 *
 * This class must be extended when creating a basic admin page and the admin()
 * method must be redefined.
 *
 * @since 1.8.0
 *
 * @package Power\Admin
 */
abstract class Power_Admin_Basic extends Power_Admin {

	/**
	 * Satisfies the abstract requirements of Power_Admin.
	 *
	 * This method can be redefined within the page-specific implementation
	 * class if you need to hook something into admin_init.
	 *
	 * @since 1.8.0
	 */
	public function settings_init() {}

}
