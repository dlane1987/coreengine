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
 * Upgrade class. Called when `db_version` Power setting is below 101.
 *
 * @since 3.1.0
 */
class Upgrade_DB_101 implements Upgrade_DB_Interface {
	/**
	 * Upgrade method.
	 *
	 * @since 1.0.1
	 * @since 3.1.0 Moved to class method.
	 */
	public function upgrade() {
		power_update_settings(
			[
				'nav_home'         => 1,
				'nav_twitter_text' => 'Follow me on Twitter',
				'subnav_home'      => 1,
			]
		);
	}
}
