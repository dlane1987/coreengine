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
<h2><?php esc_html_e( 'Author Archive SEO Settings', 'power' ); ?></h2>
<p><span class="description"><?php esc_html_e( 'These settings apply to this author\'s archive pages.', 'power' ); ?></span></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="power-meta[doctitle]"><?php esc_html_e( 'Custom Document Title', 'power' ); ?></label></th>
			<td>
				<input name="power-meta[doctitle]" id="power-meta[doctitle]" type="text" value="<?php echo esc_attr( get_the_author_meta( 'doctitle', $object->ID ) ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'The Custom Document Title sets the page title as seen in browsers and search engines. ', 'power' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="power-meta[meta-description]"><?php esc_html_e( 'Meta Description', 'power' ); ?></label></th>
			<td>
				<textarea name="power-meta[meta_description]" id="power-meta[meta-description]" rows="5" cols="30"><?php echo esc_textarea( get_the_author_meta( 'meta_description', $object->ID ) ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Text entered in the Meta Description field is used as the short page description under the title on search engine results pages.', 'power' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="power-meta[meta-keywords]"><?php esc_html_e( 'Meta Keywords', 'power' ); ?></label></th>
			<td>
				<input name="power-meta[meta_keywords]" id="power-meta[meta-keywords]" type="text" value="<?php echo esc_attr( get_the_author_meta( 'meta_keywords', $object->ID ) ); ?>" class="regular-text" /><br />
				<p class="description"><?php esc_html_e( 'A comma-separated list of keywords relevant to the page can be entered in the Meta Keywords field. Keywords are generally ignored by Search Engines.', 'power' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php esc_html_e( 'Robots Meta', 'power' ); ?>
				<a href="https://yoast.com/robots-meta-tags/" target="_blank" rel="noopener noreferrer">[?]</a>
			</th>
			<td>
				<label for="power-meta[noindex]"><input id="power-meta[noindex]" name="power-meta[noindex]" type="checkbox" value="1" <?php checked( get_the_author_meta( 'noindex', $object->ID ) ); ?> />
				<?php
				/* translators: %s: robots meta content attribute value, such as 'noindex', 'nofollow' or 'noarchive'. */
				printf( esc_html__( 'Apply %s to this archive?', 'power' ), power_code( 'noindex' ) );
				?>
				</label>
				<br />

				<label for="power-meta[nofollow]"><input id="power-meta[nofollow]" name="power-meta[nofollow]" type="checkbox" value="1" <?php checked( get_the_author_meta( 'nofollow', $object->ID ) ); ?> />
				<?php
				/* translators: %s: robots meta content attribute value, such as 'noindex', 'nofollow' or 'noarchive'. */
				printf( esc_html__( 'Apply %s to this archive?', 'power' ), power_code( 'nofollow' ) );
				?>
				</label><br />

				<label for="power-meta[noarchive]"><input id="power-meta[noarchive]" name="power-meta[noarchive]" type="checkbox" value="1" <?php checked( get_the_author_meta( 'noarchive', $object->ID ) ); ?> />
				<?php
				/* translators: %s: robots meta content attribute value, such as 'noindex', 'nofollow' or 'noarchive'. */
				printf( esc_html__( 'Apply %s to this archive?', 'power' ), power_code( 'noarchive' ) );
				?>
				</label>
			</td>
		</tr>
	</tbody>
</table>
