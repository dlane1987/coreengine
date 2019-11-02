/**
 * Adds a Classes panel to the Power Block Editor sidebar with body class
 * and post class input fields.
 *
 * Fields are stored in post meta as:
 *
 * - `_power_custom_body_class`
 * - `_power_custom_post_class`
 *
 * These are the same fields used by the original Layout Settings meta box.
 *
 * @since   3.1.0
 * @package Power\JS
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { Fill, Panel, PanelBody } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import { BodyClassTextControl } from '../components/body-class-text-control';
import { PostClassTextControl } from '../components/post-class-text-control';

function PowerCustomClasses() {
	return (
		<Fragment>
			<Fill name="PowerSidebar">
				<Panel>
					<PanelBody initialOpen={ true } title={ __( 'Custom Classes', 'power' ) }>
						<BodyClassTextControl />
						<PostClassTextControl />
					</PanelBody>
				</Panel>
			</Fill>
		</Fragment>
	);
}

registerPlugin( 'power-custom-classes', { render: PowerCustomClasses } );
