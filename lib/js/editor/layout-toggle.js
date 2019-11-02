/**
 * Adds a “layout toggle” to the Block Editor sidebar under the
 * Document sidebar. No layout selected by default.
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
import { Fragment, Component } from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { select, withSelect, withDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';
import { SelectControl, Fill, Panel, PanelBody, Spinner } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

class powerLayoutToggleComponent extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			layouts: [],
		};
	}

	componentDidMount() {
		apiFetch( { path: '/power/v1/layouts/site' } ).then( ( collection ) => {
			const stack = [ { label: __( 'Default Layout', 'power' ), value: '' } ];

			for ( const slug of Object.keys( collection ) ) {
				stack.push( {
					label: collection[ slug ].label,
					value: slug,
				} );
			}

			this.setState( { layouts: stack } );
		} );
	}

	render() {
		return (
			<Fragment>
				<Fill name="PowerSidebar">
					<Panel>
						<PanelBody initialOpen={ true } title={ __( 'Layout', 'power' ) }>
							{
								this.state.layouts.length ?
									<SelectControl
										label={ __( 'Select Layout', 'power' ) }
										value={ this.props.layout }
										options={ this.state.layouts }
										onChange={ ( layout ) => this.props.onChange( layout ) }
									/> :
									<Spinner />
							}
						</PanelBody>
					</Panel>
				</Fill>
			</Fragment>
		);
	}
}

// Retrieves meta from the Block Editor Redux store (withSelect) to set initial checkbox state.
// Persists it to the Redux store on change (withDispatch).
// Changes are only stored in the WordPress database when the post is updated.
const render = compose( [
	withSelect( () => {
		return {
			layout: select( 'core/editor' ).getEditedPostAttribute( 'meta' )._power_layout,
		};
	} ),
	withDispatch( ( dispatch ) => ( {
		onChange( layout ) {
			const currentMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			const powerMeta = Object.keys( currentMeta )
				.filter( ( key ) => key.startsWith( '_power' ) )
				.reduce( ( obj, key ) => {
					obj[ key ] = currentMeta[ key ];
					return obj;
				}, {} );
			const newMeta = {
				...powerMeta,
				_power_layout: layout,
			};
			dispatch( 'core/editor' ).editPost( { meta: newMeta } );
		},
	} ) ),
] )( powerLayoutToggleComponent );

registerPlugin( 'power-layout-toggle', { render } );
