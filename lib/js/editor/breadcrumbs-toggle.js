/**
 * Adds a “hide breadcrumbs” checkbox to Power Block Editor sidebar in a
 * Breadcrumbs panel. Unchecked by default.
 *
 * If checked and the post is updated or published, `_power_hide_breadcrumbs`
 * is set to true in post meta.
 *
 * To disable the checkbox, use the PHP `power_breadcrumbs_toggle_enabled`
 * filter: `add_filter( 'power_breadcrumbs_toggle_enabled', '__return_false' );`.
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
import { compose } from '@wordpress/compose';
import { select, withSelect, withDispatch } from '@wordpress/data';
import { CheckboxControl, Fill, PanelBody, PanelRow } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import { PowerBreadcrumbsToggleInfo } from '../components/breadcrumbs-toggle-info.js';

/**
 * Checkbox component for the hide breadcrumbs option.
 *
 * @param {Object} props Component properties.
 * @return {Object} hideBreadcrumbsComponent
 */
function powerHideBreadcrumbsComponent( { hideBreadcrumbs, onUpdate } ) {
	return (
		<Fragment>
			<Fill name="PowerSidebar">
				<PanelBody initialOpen={ true } title={ __( 'Breadcrumbs', 'power' ) }>
					<PanelRow>
						<CheckboxControl
							label={ __( 'Hide Breadcrumbs', 'power' ) }
							checked={ hideBreadcrumbs }
							onChange={ () => onUpdate( ! hideBreadcrumbs ) }
						/>
					</PanelRow>
					<PanelRow>
						<PowerBreadcrumbsToggleInfo />
					</PanelRow>
				</PanelBody>
			</Fill>
		</Fragment>
	);
}

// Retrieves meta from the Block Editor Redux store (withSelect) to set initial checkbox state.
// Persists it to the Redux store on change (withDispatch).
// Changes are only stored in the WordPress database when the post is updated.
const render = compose( [
	withSelect( () => {
		return {
			hideBreadcrumbs: select( 'core/editor' ).getEditedPostAttribute( 'meta' )._power_hide_breadcrumbs,
		};
	} ),
	withDispatch( ( dispatch ) => ( {
		onUpdate( hideBreadcrumbs ) {
			const currentMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			const powerMeta = Object.keys( currentMeta )
				.filter( ( key ) => key.startsWith( '_power' ) )
				.reduce( ( obj, key ) => {
					obj[ key ] = currentMeta[ key ];
					return obj;
				}, {} );
			const newMeta = {
				...powerMeta,
				_power_hide_breadcrumbs: hideBreadcrumbs,
			};
			dispatch( 'core/editor' ).editPost( { meta: newMeta } );
		},
	} ) ),
] )( powerHideBreadcrumbsComponent );

registerPlugin( 'power-breadcrumbs-toggle', { render } );
