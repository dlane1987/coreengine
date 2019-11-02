/**
 * Adds a “hide featured image” checkbox to Power Block Editor sidebar in an
 * Image panel. Unchecked by default.
 *
 * If checked and the post is updated or published,
 * `_power_hide_singular_image` is set to true in post meta.
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
import { PowerImageToggleInfo } from '../components/image-toggle-info.js';

/**
 * Checkbox component for the hide title option.
 *
 * @param {Object} props Component properties.
 * @return {Object} PowerHideFeaturedImageComponent.
 */
function PowerHideFeaturedImageComponent( { hideFeaturedImage, onUpdate } ) {
	return (
		<Fragment>
			<Fill name="PowerSidebar">
				<PanelBody initialOpen={ true } title={ __( 'Images', 'power' ) }>
					<PanelRow>
						<CheckboxControl
							label={ __( 'Hide Featured Image', 'power' ) }
							checked={ hideFeaturedImage }
							onChange={ () => onUpdate( ! hideFeaturedImage ) }
						/>
					</PanelRow>
					<PanelRow>
						<PowerImageToggleInfo />
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
			hideFeaturedImage: select( 'core/editor' ).getEditedPostAttribute( 'meta' )._power_hide_singular_image,
		};
	} ),
	withDispatch( ( dispatch ) => ( {
		onUpdate( hideFeaturedImage ) {
			const currentMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			const powerMeta = Object.keys( currentMeta )
				.filter( ( key ) => key.startsWith( '_power' ) )
				.reduce( ( obj, key ) => {
					obj[ key ] = currentMeta[ key ];
					return obj;
				}, {} );
			const newMeta = {
				...powerMeta,
				_power_hide_singular_image: hideFeaturedImage,
			};
			dispatch( 'core/editor' ).editPost( { meta: newMeta } );
		},
	} ) ),
] )( PowerHideFeaturedImageComponent );

registerPlugin( 'power-image-toggle', { render } );
