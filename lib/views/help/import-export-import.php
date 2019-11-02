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
<h3><?php esc_html_e( 'Import', 'power' ); ?></h3>
<p>
	<?php
	/* translators: %s: File name prefix. */
	printf( esc_html__( 'You can import a file you\'ve previously exported. The file name will start with %s followed by one or more strings indicating which settings it contains, finally followed by the date and time it was exported.', 'power' ), power_code( 'power-' ) );
	?>
</p>
<p>
	<?php esc_html_e( 'Once you upload an import file, it will automatically overwrite your existing settings.', 'power' ); ?>
	<strong><?php esc_html_e( 'This cannot be undone', 'power' ); ?></strong>
</p>