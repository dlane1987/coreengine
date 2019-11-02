/**
 * Adds the Power Sidebar to the Block Editor.
 *
 * Exposes a 'PowerSidebar' slot. Other components can use portal rendering
 * to appear inside the Power sidebar by wrapping themselves in a Fill
 * component. First, import the Fill component:
 *
 * `import { Fill } from '@wordpress/components';`
 *
 * Then wrap your own component in a Fill component:
 *
 * `<Fill name="PowerSidebar">I'm in the Power sidebar</Fill>`
 *
 * @since   3.1.0
 * @package Power\JS
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 */

/**
 * WordPress dependencies
 */
import { Fragment } from '@wordpress/element';
import { registerPlugin } from '@wordpress/plugins';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { Slot } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { PowerIcon, PowerIconSmall } from '../components/power-icons';

// Power Sidebar Component
const render = () => {
	return (
		<Fragment>
			<PluginSidebarMoreMenuItem
				target="power-sidebar"
				icon={ <PowerIconSmall /> }
			>
				Power
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="power-sidebar"
				title="Power"
				icon={ <PowerIcon /> }
			>
				<Slot name="PowerSidebar" />
			</PluginSidebar>
		</Fragment>
	);
};

registerPlugin( 'power-sidebar', { render, icon: <PowerIconSmall /> } );
