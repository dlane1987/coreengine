<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Power
 * @author  Core Engine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

$power_active_theme         = wp_get_theme();
$power_onboarding_plugins   = power_onboarding_plugins();
$power_onboarding_content   = power_onboarding_content();
$power_onboarding_nav_menus = power_onboarding_navigation_menus();
?>
<div class="wrap">
	<div class="power-onboarding-page-wrap">
		<main class="power-onboarding-main">
			<h1 class="power-onboarding-intro-title">
				<?php
				/* translators: %s: Theme name */
				echo esc_html( sprintf( __( 'Get started with %s.', 'power' ), $power_active_theme['Name'] ) );
				?>
			</h1>
			<p class="power-onboarding-intro-text">
				<?php
				/* translators: %s: Theme name */
				echo esc_html( sprintf( __( '%s supports automatic set up and import of demo content and/or recommended plugins.', 'power' ), esc_html( $power_active_theme['Name'] ) ) );
				?>
			</p>
			<p class="power-onboarding-intro-text">
				<?php
				esc_html_e( 'Use the "Set Up Your Website" button to get started. None of your existing content will be lost.', 'power' );
				?>
			</p>
			<div class="power-onboarding-progress-bar-wrapper">
				<span id="power-onboarding-progress-bar"></span>
			</div>

			<button id="power-onboarding-start" class="power-onboarding-button power-onboarding-button-blue" data-task="dependencies" data-step="0"><?php esc_html_e( 'Set up your website', 'power' ); ?></button>
			<a id="power-onboarding-settings-link" class="power-onboarding-button power-onboarding-button-alt" href="<?php echo esc_url( admin_url( 'customize.php?return=' . admin_url( 'admin.php?page=power-getting-started' ) ) ); ?>"><?php esc_html_e( 'Or go to Theme Settings', 'power' ); ?></a>

			<ul class="power-onboarding-list">
				<?php if ( $power_onboarding_plugins ) : ?>
				<li class="power-onboarding-task-dependencies">
					<div class="power-onboarding-task-steps">
						<div class="power-onboarding-step-one"></div>
						<div class="power-onboarding-step-two">
							<svg class="power-onboarding-list-spinner" viewBox="0 0 50 50" aria-hidden="true">
								<circle class="path" cx="25" cy="25" r="23" fill="none" stroke-width="4"></circle>
							</svg>
						</div>
						<div class="power-onboarding-step-three">
							<svg style="width:40px; height:40px;" viewBox="0 0 10 10" aria-hidden="true">
								<circle cx="5" cy="5" r="4.5" style="stroke:#6c8196; stroke-width:0.7; fill:none;"></circle>
								<polyline points="2.7,5 4.2,6.7 7.5,3.6" style="stroke:#6c8196; stroke-width:0.7; stroke-linejoin:round; stroke-linecap:round; fill:none;"></polyline>
							</svg>
						</div>
					</div>

					<h3><?php esc_html_e( 'Recommended plugins', 'power' ); ?></h3>
					<p><?php esc_html_e( 'The following plugins will be automatically installed and activated with this theme (links open in new window):', 'power' ); ?></p>
					<?php echo wp_kses_post( power_onboarding_plugins_list() ); ?>
				</li>
				<?php endif; ?>

				<?php if ( $power_onboarding_content || $power_onboarding_nav_menus ) : ?>
				<li class="power-onboarding-task-content">
					<div class="power-onboarding-task-steps">
						<div class="power-onboarding-step-one"></div>
						<div class="power-onboarding-step-two">
							<svg class="power-onboarding-list-spinner" viewBox="0 0 50 50" aria-hidden="true">
								<circle class="path" cx="25" cy="25" r="23" fill="none" stroke-width="4"></circle>
							</svg>
						</div>
						<div class="power-onboarding-step-three">
							<svg style="width:40px; height:40px;" viewBox="0 0 10 10" aria-hidden="true">
								<circle cx="5" cy="5" r="4.5" style="stroke:#6c8196; stroke-width:0.7; fill:none;"></circle>
								<polyline points="2.7,5 4.2,6.7 7.5,3.6" style="stroke:#6c8196; stroke-width:0.7; stroke-linejoin:round; stroke-linecap:round; fill:none;"></polyline>
							</svg>
						</div>
					</div>

					<h3><?php esc_html_e( 'Demo content', 'power' ); ?></h3>
					<p>
						<?php
						esc_html_e( 'Sample content for the theme will be added to make your theme look like the demo.', 'power' );
						if ( isset( $power_onboarding_content['homepage'] ) ) {
							echo ' ';
							esc_html_e( 'This will change your default homepage.', 'power' );
						}
						?>
					</p>
				</li>
				<?php endif; ?>

				<li class="power-onboarding-task-final">
					<div class="power-onboarding-task-steps">
						<div class="power-onboarding-step-one"></div>
						<div class="power-onboarding-step-two">
							<svg class="power-onboarding-list-spinner" viewBox="0 0 50 50" aria-hidden="true">
								<circle class="path" cx="25" cy="25" r="23" fill="none" stroke-width="4"></circle>
							</svg>
						</div>
						<div class="power-onboarding-step-three">
							<svg style="width:40px; height:40px;" viewBox="0 0 10 10" aria-hidden="true">
								<circle cx="5" cy="5" r="4.5" style="stroke:#6c8196; stroke-width:0.7; fill:none;"></circle>
								<polyline points="2.7,5 4.2,6.7 7.5,3.6" style="stroke:#6c8196; stroke-width:0.7; stroke-linejoin:round; stroke-linecap:round; fill:none;"></polyline>
							</svg>
						</div>
					</div>

					<h3><?php esc_html_e( 'All done!', 'power' ); ?></h3>
					<p><?php esc_html_e( 'Your website setup is complete! View or edit your homepage using the buttons below.', 'power' ); ?></p>

					<a id="power-onboarding-view-homepage" class="power-onboarding-button power-onboarding-button-blue" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'View your homepage', 'power' ); ?></a>
					<a id="power-onboarding-edit-homepage" class="power-onboarding-button power-onboarding-button-blue" href="#"><?php esc_html_e( 'Edit your homepage', 'power' ); ?></a>
				</li>
			</ul>
		</main>

		<aside class="power-onboarding-sidebar">
			<section>
				<h3><?php esc_html_e( 'Helpful Links', 'power' ); ?></h3>
				<p><?php esc_html_e( 'Learn about the new WordPress editor (Gutenberg) and building with content blocks by using these resources below.', 'power' ); ?></p>
				<ul>
					<li><a href="https://wordpress.org/gutenberg/"><?php esc_html_e( 'Gutenberg Intro', 'power' ); ?></a></li>
					<li><a href="https://www.danielane.eu"><?php esc_html_e( 'CoreEngine Blog', 'power' ); ?></a></li>
					<li><a href="https://gutenberg.news"><?php esc_html_e( 'Gutenberg News', 'power' ); ?></a></li>
					<li><a href="https://atomicblocks.com"><?php esc_html_e( 'Atomic Blocks', 'power' ); ?></a></li>
				</ul>
			</section>
		</aside>
	</div><!-- .power-onboarding-page-wrap -->
</div>
