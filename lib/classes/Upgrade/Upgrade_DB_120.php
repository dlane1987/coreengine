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
 * Upgrade class. Called when `db_version` Power setting is below 120.
 *
 * @since 3.1.0
 */
class Upgrade_DB_120 implements Upgrade_DB_Interface {
	/**
	 * Upgrade method.
	 *
	 * @since 1.2.0
	 * @since 3.1.0 Moved to class method.
	 */
	public function upgrade() {
		power_update_settings(
			[
				'update' => 1,
			]
		);
	}
}
