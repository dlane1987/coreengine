/**
 * This file controls the behaviours within the Power Framework.
 *
 * Note that while this version of the file include 'use strict'; at the function level,
 * the Closure Compiler version strips that away. This is fine, as the compiler may
 * well be doing things that are not use strict compatible.
 *
 * @package Power\JS
 * @author  CoreEngine
 * @license GPL-2.0-or-later
 */

/* global power, powerL10n, power_toggles, confirm */

/**
 * Holds Power values in an object to avoid polluting global namespace.
 *
 * @since 1.8.0
 *
 * @constructor
 */
window[ 'power' ] = {

	settingsChanged: false,

	onboardingTasks: power_onboarding.tasks,

	/**
	 * Inserts a category checklist toggle button and binds the behaviour.
	 *
	 * @since 1.8.0
	 *
	 * @function
	 */
	categoryChecklistToggleInit: function() {
		'use strict';

		// Insert toggle button into DOM wherever there is a category checklist.
		jQuery( '<p><span id="power-category-checklist-toggle" class="button">' + powerL10n.categoryChecklistToggle + '</span></p>' )
			.insertBefore( 'ul.categorychecklist' );

		// Bind the behaviour to click.
		jQuery( document ).on( 'click.power.power_category_checklist_toggle', '#power-category-checklist-toggle', power.categoryChecklistToggle );
	},

	/**
	 * Provides the behaviour for the category checklist toggle button.
	 *
	 * On the first click, it checks all checkboxes, and on subsequent clicks it
	 * toggles the checked status of the checkboxes.
	 *
	 * @since 1.8.0
	 *
	 * @function
	 *
	 * @param {jQuery.event} event
	 */
	categoryChecklistToggle: function( event ) {
		'use strict';

		// Cache the selectors.
		var $this = jQuery( event.target ),
			checkboxes = $this.parent().next().find( ':checkbox' );

		// If the button has already been clicked once, clear the checkboxes and remove the flag.
		if ( $this.data( 'clicked' ) ) {
			checkboxes.removeAttr( 'checked' );
			$this.data( 'clicked', false );
		} else { // Mark the checkboxes and add a flag.
			checkboxes.attr( 'checked', 'checked' );
			$this.data( 'clicked', true );
		}
	},

	/**
	 * Grabs the array of toggle settings and loops through them to hook in
	 * the behaviour.
	 *
	 * The power_toggles array is filterable in load-scripts.php before being
	 * passed over to JS via wp_localize_script().
	 *
	 * @since 1.8.0
	 *
	 * @function
	 */
	toggleSettingsInit: function() {
		'use strict';

		jQuery.each( power_toggles, function( k, v ) {

			// Prepare data.
			var data = { selector: v[ 0 ], showSelector: v[ 1 ], checkValue: v[ 2 ] };

			// Setup toggle binding.
			jQuery( 'div.power-metaboxes' )
				.on( 'change.power.power_toggle', v[ 0 ], data, power.toggleSettings );

			// Trigger the check when page loads too.
			// Can't use triggerHandler here, as that doesn't bubble the event up to div.power-metaboxes.
			// We namespace it, so that it doesn't conflict with any other change event attached that
			// we don't want triggered on document ready.
			jQuery( v[ 0 ]).trigger( 'change.power_toggle', data );
		});

	},

	/**
	 * Provides the behaviour for the change event for certain settings.
	 *
	 * Three bits of event data is passed - the jQuery selector which has the
	 * behaviour attached, the jQuery selector which to toggle, and the value to
	 * check against.
	 *
	 * The checkValue can be a single string or an array (for checking against
	 * multiple values in a dropdown) or a null value (when checking if a checkbox
	 * has been marked).
	 *
	 * @since 1.8.0
	 *
	 * @function
	 *
	 * @param {jQuery.event} event
	 */
	toggleSettings: function( event ) {
		'use strict';

		// Cache selectors.
		var $selector = jQuery( event.data.selector ),
		    $showSelector = jQuery( event.data.showSelector ),
		    checkValue = event.data.checkValue;

		// Compare if a checkValue is an array, and one of them matches the value of the selected option
		// OR the checkValue is _unchecked, but the checkbox is not marked
		// OR the checkValue is _checked, but the checkbox is marked
		// OR it's a string, and that matches the value of the selected option.
		if (
			( jQuery.isArray( checkValue ) && jQuery.inArray( $selector.val(), checkValue ) > -1) ||
				( '_unchecked' === checkValue && $selector.is( ':not(:checked)' ) ) ||
				( '_checked' === checkValue && $selector.is( ':checked' ) ) ||
				( '_unchecked' !== checkValue && '_checked' !== checkValue && $selector.val() === checkValue )
		) {
			jQuery( $showSelector ).slideDown( 'fast' );
		} else {
			jQuery( $showSelector ).slideUp( 'fast' );
		}

	},

	/**
	 * When a input or textarea field field is updated, update the character counter.
	 *
	 * For now, we can assume that the counter has the same ID as the field, with a _chars
	 * suffix. In the future, when the counter is added to the DOM with JS, we can add
	 * a data( 'counter', 'counter_id_here' ) property to the field element at the same time.
	 *
	 * @since 1.8.0
	 *
	 * @function
	 *
	 * @param {jQuery.event} event
	 */
	updateCharacterCount: function( event ) {
		'use strict';
		jQuery( '#' + event.target.id + '_chars' ).html( jQuery( event.target ).val().length.toString() );
	},

	/**
	 * Provides the behaviour for the layout selector.
	 *
	 * When a layout is selected, the all layout labels get the selected class
	 * removed, and then it is added to the label that was selected.
	 *
	 * @since 1.8.0
	 *
	 * @function
	 *
	 * @param {jQuery.event} event
	 */
	layoutHighlighter: function( event ) {
		'use strict';

		// Cache class name.
		var selectedClass = 'selected';

		// Remove class from all labels.
		jQuery('input[name="' + jQuery(event.target).attr('name') + '"]').parent('label').removeClass(selectedClass);

		// Add class to selected layout.
		jQuery(event.currentTarget).addClass(selectedClass);

	},

	/**
	 * Helper function for confirming a user action.
	 *
	 * @since 1.8.0
	 *
	 * @function
	 *
	 * @param {String} text The text to display.
	 * @returns {Boolean}
	 */
	confirm: function( text ) {
		'use strict';

		return confirm( text );

	},

	/**
	 * Have all form fields in Power meta boxes set a dirty flag when changed.
	 *
	 * @since 2.0.0
	 *
	 * @function
	 */
	attachUnsavedChangesListener: function() {
		'use strict';

		jQuery( 'div.power-metaboxes :input' ).change( function() {
			power.registerChange();
		});
		window.onbeforeunload = function(){
			if ( power.settingsChanged ) {
				return powerL10n.saveAlert;
			}
		};
		jQuery( 'div.power-metaboxes input[type="submit"]' ).click( function() {
			window.onbeforeunload = null;
		});
	},

	/**
	 * Set a flag, to indicate form fields have changed.
	 *
	 * @since 2.0.0
	 *
	 * @function
	 */
	registerChange: function() {
		'use strict';

		power.settingsChanged = true;
	},

	/**
	 * Ask user to confirm that a new version of Power should now be installed.
	 *
	 * @since 2.0.0
	 *
	 * @function
	 *
	 * @return {Boolean} True if upgrade should occur, false if not.
	 */
	confirmUpgrade: function() {
		'use strict';

		return confirm( powerL10n.confirmUpgrade );
	},

	/**
	 * Ask user to confirm that settings should now be reset.
	 *
	 * @since 2.0.0
	 *
	 * @function
	 *
	 * @return {Boolean} True if reset should occur, false if not.
	 */
	confirmReset: function() {
		'use strict';

		return confirm( powerL10n.confirmReset );
	},

	/**
	 * Processes onboarding tasks.
	 *
	 * @since 2.8.0
	 *
	 * @function
	 *
	 * @param {String} task The task to process.
	 * @param {Number} step The step to process. Must be an integer.
	 */
	doOnboardingTask: function( task, step ) {

		task = task || 'dependencies';
		step = step || 0;

		if ( -1 === jQuery.inArray( task, power.onboardingTasks ) ) {
			power.completeOnboarding();
			return;
		}

		power.toggleOnboardingTaskStatus( task, 'processing' );

		jQuery.ajax( {
			data: {
				action: 'power_do_onboarding_process',
				task: task,
				step: step,
				nonce: power_onboarding.nonce,
			},
			type: 'post',
			dataType: 'json',
			url: ajaxurl,
			success: function( response ) {

				if ( response.data.homepage_edit_link ) {
					jQuery( '#power-onboarding-edit-homepage' ).attr( 'href', response.data.homepage_edit_link );
				}

				if ( true === response.data.complete ) {
					power.toggleOnboardingTaskStatus( task, 'done' );
					power.onboardingTasks.shift();

					if ( ! power.onboardingTasks.length ) {
						power.completeOnboarding();
						return;
					}

					power.toggleOnboardingTaskStatus( power.onboardingTasks[0], 'processing' );

					window.setTimeout( function() {
						power.doOnboardingTask( power.onboardingTasks[0], 0 );
					}, 2000 );

					return;
				}

				power.toggleOnboardingTaskStatus( power.onboardingTasks[0], 'processing' );

				window.setTimeout( function() {
					power.doOnboardingTask( response.data.task, response.data.next_step );
				}, 2000 );
			}
		} );
	},

	/**
	 * Toggles the status of the specified task.
	 *
	 * @param {String} task The task whose status will be updated.
	 * @param {String} status The status to set on the task.
	 */
	toggleOnboardingTaskStatus: function( task, status ) {

		if ( -1 === jQuery.inArray( task, power.onboardingTasks ) ) {
			return;
		}

		var current_task = jQuery( '.power-onboarding-task-' + task );

		switch( status ) {

			case 'processing':
				current_task.addClass( 'power-onboarding-list-processing' );
				wp.a11y.speak( power_onboarding.l10n.a11y.step_started, 'polite' );
				break;

			case 'done':
				current_task.addClass( 'power-onboarding-list-done' ).removeClass( 'power-onboarding-list-processing' );
				wp.a11y.speak( power_onboarding.l10n.a11y.step_completed, 'polite' );
				break;

		}
	},

	/**
	 * Runs the onboarding completion tasks.
	 */
	completeOnboarding: function() {
		window.setTimeout( function() {
			jQuery( '.power-onboarding-task-final' ).addClass( 'power-onboarding-list-done' );
		}, 300 );
		wp.a11y.speak( power_onboarding.l10n.a11y.onboarding_complete, 'polite' );
	},

	/**
	 * Stores the saved Starter Pack choice and triggers the first onboarding step.
	 *
	 * @since 3.1.0
	 *
	 * @function
	 *
	 * @param {String} pack The chosen starter pack.
	 */
	onboardingInstallPack: function( pack ) {

		if ( ! pack ) {
			return;
		}

		jQuery.ajax( {
			data: {
				action: 'power_do_onboarding_pack_selection',
				pack: pack,
				nonce: power_onboarding.nonce,
			},
			type: 'post',
			dataType: 'json',
			url: ajaxurl,
			success: function( response ) {

				// Update tasks to run based on what the chosen pack supports.
				if ( response.data.tasks ) {
					power_onboarding.tasks = response.data.tasks;
					power.onboardingTasks = power_onboarding.tasks;
				}

				// Hide the pack info modal if visible.
				$overlayCloseButton = jQuery( '#js-modal-close' );
				if ( $overlayCloseButton.length ) {
					$overlayCloseButton.click();
				}

				// Hide the pack selection area.
				jQuery( '.power-onboarding-starter-packs' ).addClass( 'power-onboarding-area-hidden' );

				// Show dependencies progress area.
				if ( response.data.tasks.includes( 'dependencies' ) ) {
					jQuery( '.power-onboarding-task-dependencies' )
						.addClass( 'power-onboarding-area-visible' )
						.removeClass( 'power-onboarding-area-hidden' );
				}

				// Show content progress area.
				if ( response.data.tasks.includes( 'content' ) || response.data.hasMenus ) {
					jQuery( '.power-onboarding-task-content' )
						.addClass( 'power-onboarding-area-visible' )
						.removeClass( 'power-onboarding-area-hidden' );
				}

				// Start the onboarding process.
				window.setTimeout( function() {
					power.doOnboardingTask( power.onboardingTasks[0], 0 );
				}, 500 );
			}
		});
	},

	/**
	 * Initialises all aspects of the scripts.
	 *
	 * Generally ordered with stuff that inserts new elements into the DOM first,
	 * then stuff that triggers an event on existing DOM elements when ready,
	 * followed by stuff that triggers an event only on user interaction. This
	 * keeps any screen jumping from occurring later on.
	 *
	 * @since 1.8.0
	 *
	 * @function
	 */
	ready: function() {
		'use strict';

		// Initialise category checklist toggle button.
		power.categoryChecklistToggleInit();

		// Initialise settings that can toggle the display of other settings.
		power.toggleSettingsInit();

		// Initialise form field changing flag.
		power.attachUnsavedChangesListener();

		// Bind character counters.
		jQuery( '#power_title, #power_description' ).on( 'keyup.power.power_character_count', power.updateCharacterCount );

		// Bind layout highlighter behaviour.
		jQuery('.power-layout-selector').on('change.power.power_layout_selector', 'label', power.layoutHighlighter);

		// Bind upgrade confirmation.
		jQuery( '.power-js-confirm-upgrade' ).on( 'click.power.power_confirm_upgrade', power.confirmUpgrade );

		// Bind reset confirmation.
		jQuery( '.power-js-confirm-reset' ).on( 'click.power.power_confirm_reset', power.confirmReset );

		// Bind onboarding start button.
		jQuery( '#power-onboarding-start' ).on( 'click', function ( event ) {
			jQuery( this ).prop( 'disabled', true ).addClass( 'power-onboarding-button-disabled' );
			jQuery( '#power-onboarding-settings-link' ).prop( 'disabled', true ).prop( 'tabindex', '-1' );
			wp.a11y.speak( power_onboarding.l10n.a11y.onboarding_started, 'polite' );
			power.doOnboardingTask( event.target.dataset.task, event.target.dataset.step );
		} );

		// Make “install pack” buttons trigger the installation process.
		jQuery( 'body' ).on( 'click', '.power-install-pack', function( event ) {

			// Hide all “install pack” buttons to prevent double runs.
			jQuery( '.power-install-pack' ).prop( 'disabled', true );

			power.onboardingInstallPack( event.target.dataset.pack );

		});

		// Make the enter key open the “Pack Details” view.
		jQuery( '.power-onboarding-page-starter-packs .pack-image' ).keypress( function( event ) {
			var ENTER_KEY = 13;
			if ( event.which === ENTER_KEY ) {
				jQuery( this ).click();
				event.preventDefault();
			}
		});

	}

};

jQuery( power.ready );

/**
 * Helper function for confirming a user action.
 *
 * This function is deprecated in favour of power.confirm( text ) which provides
 * the same functionality.
 *
 * @since 1.0.0
 * @deprecated 1.8.0
 */
function power_confirm( text ) {
	'use strict';
	return power.confirm( text );
}
