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

wp_nonce_field( 'power_inpost_scripts_save', 'power_inpost_scripts_nonce' );
?>
<table class="form-table">
	<tbody>

	<tr>
		<th scope="row">
			<label for="power_scripts"><strong><?php esc_html_e( 'Header Scripts', 'power' ); ?></strong></label>
		</th>
		<td>
			<p><textarea class="widefat" rows="4" name="power_seo[_power_scripts]"
						id="power_scripts"><?php echo esc_textarea( power_get_custom_field( '_power_scripts' ) ); ?></textarea>
			</p>
			<p>
				<?php
				/* translators: %s: Name of head tag. */
				printf( esc_html__( 'Output before the closing %s tag, after sitewide header scripts.', 'power' ), power_code( 'head' ) );
				?>
			</p>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="power_scripts_body"><strong><?php esc_html_e( 'Body Scripts', 'power' ); ?></strong></label>
		</th>
		<td>
			<p><textarea class="widefat" rows="4" name="power_seo[_power_scripts_body]"
						id="power_scripts_body"><?php echo esc_textarea( power_get_custom_field( '_power_scripts_body' ) ); ?></textarea>
			</p>
		</td>
	</tr>

	<tr>
		<th scope="row">
			<label for="power_scripts_body_position"><?php esc_html_e( 'Body Scripts Position', 'power' ); ?></label>
		</th>
		<td>
			<select name="power_seo[_power_scripts_body_position]" id="power_scripts_body_position">
				<option value="bottom"<?php selected( power_get_custom_field( '_power_scripts_body_position' ), 'bottom' ); ?>><?php esc_html_e( 'Bottom: before closing body tag', 'power' ); ?></option>
				<option value="top"<?php selected( power_get_custom_field( '_power_scripts_body_position' ), 'top' ); ?>><?php esc_html_e( 'Top: after opening body tag', 'power' ); ?></option>
			</select>
		</td>
	</tr>

	</tbody>
</table>
