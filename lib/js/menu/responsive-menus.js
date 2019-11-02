/**
 * Add the accessible responsive menu.
 *
 * @version 1.1.3
 *
 * @author CoreEngine
 * @link https://github.com/copyblogger/responsive-menus/
 * @license GPL-2.0+
 * @package Power_AMP\Component\Menu
 */

( function ( document, $, undefined ) {

	'use strict';

	var powerMenuParams     = typeof power_responsive_menu === 'undefined' ? '' : power_responsive_menu,
		powerMenusUnchecked = powerMenuParams.menuClasses,
		powerMenus          = {},
		menusToCombine        = [];

	/**
	 * Validate the menus passed by the theme with what's being loaded on the page,
	 * and pass the new and accurate information to our new data.
	 *
	 * @param {powerMenusUnchecked} Raw data from the localized script in the theme.
	 * @return {array} powerMenus array gets populated with updated data.
	 * @return {array} menusToCombine array gets populated with relevant data.
	 */
	$.each(
		powerMenusUnchecked, function( group ) {

			// Mirror our group object to populate.
			powerMenus[group] = [];

			// Loop through each instance of the specified menu on the page.
			$.each(
				this, function( key, value ) {

					var menuString = value,
					$menu          = $( value );

					// If there is more than one instance, append the index and update array.
					if ( $menu.length > 1 ) {

						$.each(
							$menu, function( key, value ) {

								var newString = menuString + '-' + key;

								$( this ).addClass( newString.replace( '.','' ) );

								powerMenus[group].push( newString );

								if ( 'combine' === group ) {
									menusToCombine.push( newString );
								}

							}
						);

					} else if ( $menu.length == 1 ) {

						powerMenus[group].push( menuString );

						if ( 'combine' === group ) {
							menusToCombine.push( menuString );
						}

					}

				}
			);

		}
	);

	// Make sure there is something to use for the 'others' array.
	if ( typeof powerMenus.others == 'undefined' ) {
		powerMenus.others = [];
	}

	// If there's only one menu on the page for combining, push it to the 'others' array and nullify our 'combine' variable.
	if ( menusToCombine.length == 1 ) {
		powerMenus.others.push( menusToCombine[0] );
		powerMenus.combine = null;
		menusToCombine       = null;
	}

	var powerMenu         = {},
		mainMenuButtonClass = 'menu-toggle',
		subMenuButtonClass  = 'sub-menu-toggle',
		responsiveMenuClass = 'power-responsive-menu';

	// Initialize.
	powerMenu.init = function() {

		// Exit early if there are no menus to do anything.
		if ( $( _getAllMenusArray() ).length == 0 ) {
			return;
		}

		var menuIconClass    = typeof powerMenuParams.menuIconClass !== 'undefined' ? powerMenuParams.menuIconClass : 'dashicons-before dashicons-menu';
		var	subMenuIconClass = typeof powerMenuParams.subMenuIconClass !== 'undefined' ? powerMenuParams.subMenuIconClass : 'dashicons-before dashicons-arrow-down-alt2';
		var	toggleButtons    = {
			menu : $( '<button />', {
					'class' : mainMenuButtonClass,
					'aria-expanded' : false,
					'aria-pressed' : false
				}
			)
			.append( powerMenuParams.mainMenu ),
				submenu : $(
					'<button />', {
					'class' : subMenuButtonClass,
					'aria-expanded' : false,
					'aria-pressed' : false
				}
			)
			.append( $('<span />', {
					'class' : 'screen-reader-text',
					'text' : powerMenuParams.subMenu
				} )
			)
		};

		// Add the responsive menu class to the active menus.
		_addResponsiveMenuClass();

		// Add the main nav button to the primary menu, or exit the plugin.
		_addMenuButtons( toggleButtons );

		// Setup additional classes.
		$( '.' + mainMenuButtonClass ).addClass( menuIconClass );
		$( '.' + subMenuButtonClass ).addClass( subMenuIconClass );
		$( '.' + mainMenuButtonClass ).on( 'click.powerMenu-mainbutton', _mainmenuToggle ).each( _addClassID );
		$( '.' + subMenuButtonClass ).on( 'click.powerMenu-subbutton', _submenuToggle );
		$( window ).on( 'resize.powerMenu', _doResize ).triggerHandler( 'resize.powerMenu' );
	};

	/**
	 * Add menu toggle button to appropriate menus.
	 *
	 * @param {toggleButtons} Object of menu buttons to use for toggles.
	 */
	function _addMenuButtons( toggleButtons ) {

		// Apply sub menu toggle to each sub-menu found in the menuList.
		$( _getMenuSelectorString( powerMenus ) ).find( '.sub-menu' ).before( toggleButtons.submenu );

		if ( menusToCombine !== null ) {

			var menusToToggle = powerMenus.others.concat( menusToCombine[0] );

			// Only add menu button the primary menu and navs NOT in the combine variable.
			$( _getMenuSelectorString( menusToToggle ) ).before( toggleButtons.menu );

		} else {

			// Apply the main menu toggle to all menus in the list.
			$( _getMenuSelectorString( powerMenus.others ) ).before( toggleButtons.menu );

		}

	}

	/**
	 * Add the responsive menu class.
	 */
	function _addResponsiveMenuClass() {
		$( _getMenuSelectorString( powerMenus ) ).addClass( responsiveMenuClass );
	}

	/**
	 * Execute our responsive menu functions on window resizing.
	 */
	function _doResize() {
		var buttons = $( 'button[id^="power-mobile-"]' ).attr( 'id' );
		if ( typeof buttons === 'undefined' ) {
			return;
		}
		_maybeClose( buttons );
		_superfishToggle( buttons );
		_changeSkipLink( buttons );
		_combineMenus( buttons );
	}

	/**
	 * Add the nav- class of the related navigation menu as
	 * an ID to associated button (helps target specific buttons outside of context).
	 */
	function _addClassID() {
		var $this = $( this ),
			nav   = $this.next( 'nav' ),
			id    = 'class';

		$this.attr( 'id', 'power-mobile-' + $( nav ).attr( id ).match( /nav-\w*\b/ ) );
	}

	/**
	 * Combine our menus if the mobile menu is visible.
	 *
	 * @params buttons
	 */
	function _combineMenus( buttons ){

		// Exit early if there are no menus to combine.
		if ( menusToCombine == null ) {
			return;
		}

		// Split up the menus to combine based on order of appearance in the array.
		var primaryMenu   = menusToCombine[0],
			combinedMenus = $( menusToCombine ).filter(
				function(index) { if ( index > 0 ) {
							return index; } }
			);

		// If the responsive menu is active, append items in 'combinedMenus' object to the 'primaryMenu' object.
		if ( 'none' !== _getDisplayValue( buttons ) ) {

			$.each(
				combinedMenus, function( key, value ) {
					$( value ).find( '.menu > li' ).addClass( 'moved-item-' + value.replace( '.','' ) ).appendTo( primaryMenu + ' ul.power-nav-menu' );
				}
			);
			$( _getMenuSelectorString( combinedMenus ) ).hide();

		} else {

			$( _getMenuSelectorString( combinedMenus ) ).show();
			$.each(
				combinedMenus, function( key, value ) {
					$( '.moved-item-' + value.replace( '.','' ) ).appendTo( value + ' ul.power-nav-menu' ).removeClass( 'moved-item-' + value.replace( '.','' ) );
				}
			);

		}

	}

	/**
	 * Action to happen when the main menu button is clicked.
	 */
	function _mainmenuToggle() {
		var $this = $( this );
		_toggleAria( $this, 'aria-pressed' );
		_toggleAria( $this, 'aria-expanded' );
		$this.toggleClass( 'activated' );
		$this.next( 'nav' ).slideToggle( 'fast' );
	}

	/**
	 * Action for submenu toggles.
	 */
	function _submenuToggle() {

		var $this  = $( this ),
			others = $this.closest( '.menu-item' ).siblings();
		_toggleAria( $this, 'aria-pressed' );
		_toggleAria( $this, 'aria-expanded' );
		$this.toggleClass( 'activated' );
		$this.next( '.sub-menu' ).slideToggle( 'fast' );

		others.find( '.' + subMenuButtonClass ).removeClass( 'activated' ).attr( 'aria-pressed', 'false' );
		others.find( '.sub-menu' ).slideUp( 'fast' );

	}

	/**
	 * Activate/deactivate superfish.
	 *
	 * @params buttons
	 */
	function _superfishToggle( buttons ) {
		var _superfish = $( '.' + responsiveMenuClass + ' .js-superfish' ),
			$args      = 'destroy';
		if ( typeof _superfish.superfish !== 'function' ) {
			return;
		}
		if ( 'none' === _getDisplayValue( buttons ) ) {
			$args = {
				'delay': 100,
				'animation': {'opacity': 'show', 'height': 'show'},
				'dropShadows': false,
				'speed': 'fast'
			};
		}
		_superfish.superfish( $args );
	}

	/**
	 * Modify skip link to match mobile buttons.
	 *
	 * @param buttons
	 */
	function _changeSkipLink( buttons ) {

		// Start with an empty array.
		var menuToggleList = _getAllMenusArray();

		// Exit out if there are no menu items to update.
		if ( ! $( menuToggleList ).length > 0 ) {
			return;
		}

		$.each(
			menuToggleList, function ( key, value ) {

				var newValue = value.replace( '.', '' ),
				startLink    = 'power-' + newValue,
				endLink      = 'power-mobile-' + newValue;

				if ( 'none' == _getDisplayValue( buttons ) ) {
					startLink = 'power-mobile-' + newValue;
					endLink   = 'power-' + newValue;
				}

				var $item = $( '.power-skip-link a[href="#' + startLink + '"]' );

				if ( menusToCombine !== null && value !== menusToCombine[0] ) {
					$item.toggleClass( 'skip-link-hidden' );
				}

				if ( $item.length > 0 ) {
					var link = $item.attr( 'href' );
					link     = link.replace( startLink, endLink );

					$item.attr( 'href', link );
				} else {
					return;
				}

			}
		);

	}

	/**
	 * Close all the menu toggles if buttons are hidden.
	 *
	 * @param buttons
	 */
	function _maybeClose( buttons ) {
		if ( 'none' !== _getDisplayValue( buttons ) ) {
			return true;
		}

		$( '.' + mainMenuButtonClass + ', .' + responsiveMenuClass + ' .sub-menu-toggle' )
			.removeClass( 'activated' )
			.attr( 'aria-expanded', false )
			.attr( 'aria-pressed', false );

		$( '.' + responsiveMenuClass + ', .' + responsiveMenuClass + ' .sub-menu' )
			.attr( 'style', '' );
	}

	/**
	 * Generic function to get the display value of an element.
	 *
	 * @param  {id} $id ID to check
	 * @return {string}     CSS value of display property
	 */
	function _getDisplayValue( $id ) {
		var element = document.getElementById( $id ),
			style   = window.getComputedStyle( element );
		return style.getPropertyValue( 'display' );
	}

	/**
	 * Toggle aria attributes.
	 *
	 * @param  {button} $this     passed through
	 * @param  {aria-xx} attribute aria attribute to toggle
	 * @return {bool}           from _ariaReturn
	 */
	function _toggleAria( $this, attribute ) {
		$this.attr(
			attribute, function( index, value ) {
				return 'false' === value;
			}
		);
	}

	/**
	 * Helper function to return a comma separated string of menu selectors.
	 *
	 * @param {itemArray} Array of menu items to loop through.
	 * @param {ignoreSecondary} boolean of whether to ignore the 'secondary' menu item.
	 * @return {string} Comma-separated string.
	 */
	function _getMenuSelectorString( itemArray ) {

		var itemString = $.map(
			itemArray, function( value, key ) {
				return value;
			}
		);

		return itemString.join( ',' );

	}

	/**
	 * Helper function to return a group array of all the menus in
	 * both the 'others' and 'combine' arrays.
	 *
	 * @return {array} Array of all menu items as class selectors.
	 */
	function _getAllMenusArray() {

		// Start with an empty array.
		var menuList = [];

		// If there are menus in the 'menusToCombine' array, add them to 'menuList'.
		if ( menusToCombine !== null ) {

			$.each(
				menusToCombine, function( key, value ) {
					menuList.push( value.valueOf() );
				}
			);

		}

		// Add menus in the 'others' array to 'menuList'.
		$.each(
			powerMenus.others, function( key, value ) {
				menuList.push( value.valueOf() );
			}
		);

		if ( menuList.length > 0 ) {
			return menuList;
		} else {
			return null;
		}

	}

	$( document ).ready(
		function () {

			if ( _getAllMenusArray() !== null ) {

				powerMenu.init();

			}

		}
	);

})( document, jQuery );
