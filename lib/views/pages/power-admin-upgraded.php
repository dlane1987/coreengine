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

$power_allowed_code = [
	'code' => [],
	'a'    => [
		'href'   => [],
		'target' => [],
		'rel'    => [],
	],
];
?>
<div class="wrap about-wrap">

<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

<p class="about-text">
	<?php
	printf(
		wp_kses(
			/* translators: 1: Power version, 2: Changelog URI. */
			__( 'Congratulations for successfully updating to Power %1$s. We keep a <a href="%2$s" target="_blank" rel="noopener noreferrer">detailed changelog</a> for each release. Feel free to take a look!', 'power' ),
			$power_allowed_code
		),
		esc_html( PARENT_THEME_VERSION ),
		'https://powerchangelog.com/'
	);
	?>
</p>

<div class="return-to-dashboard">
	<p><a href="<?php echo esc_url( menu_page_url( 'power', 0 ) ); ?>"><?php esc_html_e( 'Go to Theme Settings &rarr;', 'power' ); ?></a></p>
	<?php if ( ! power_seo_disabled() ) : ?>
	<p><a href="<?php echo esc_url( menu_page_url( 'seo-settings', 0 ) ); ?>"><?php esc_html_e( 'Go to SEO Settings &rarr;', 'power' ); ?></a></p>
	<?php endif; ?>

</div>

</div>