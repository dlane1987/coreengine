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

?>
<h3><?php esc_html_e( 'Robots Meta Settings', 'power' ); ?></h3>
<p>
	<?php esc_html_e( 'Noarchive and noindex are explained in the home settings. Here you can select what other parts of the site to apply these options to.', 'power' ); ?>
</p>
<p>
	<?php esc_html_e( 'At least one archive should be indexed, but indexing multiple archives will typically result in a duplicate content penalization (multiple pages with identical content look manipulative to search engines).', 'power' ); ?>
</p>
<p>
	<?php esc_html_e( 'For most sites either the home page or blog page (using the blog template) will serve as this index which is why the default is not to index categories, tags, authors, dates, or searches.', 'power' ); ?>
</p>