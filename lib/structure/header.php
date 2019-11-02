<?php
/**
 * Power Framework.
 *
 * WARNING: This file is part of the core Power Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Power\Header
 * @author  Core Engine
 * @license GPL-2.0-or-later
 * @link    www.daniellane.eu
 */

add_action( 'power_doctype', 'power_do_doctype' );
/**
 * Echo the doctype and opening markup.
 *
 * If you are going to replace the doctype with a custom one, you must remember to include the opening <html> and
 * <head> elements too, along with the proper attributes.
 *
 * It would be beneficial to also include the <meta> tag for content type.
 *
 * The default doctype is XHTML v1.0 Transitional, unless HTML support os present in the child theme.
 *
 * @since 1.3.0
 * @since 3.0.0 Removed xhtml logic.
 */
function power_do_doctype() {

	power_html5_doctype();

}

/**
 * HTML5 doctype markup.
 *
 * @since 2.0.0
 */
function power_html5_doctype() {

	?>
<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>
<head <?php echo power_attr( 'head' ); ?>>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php // phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact -- To keep layout of existing HTML output.

}

add_filter( 'document_title_parts', 'power_document_title_parts' );
/**
 * Filter Document title parts based on context and SEO settings values.
 *
 * @since 2.6.0
 *
 * @param array $parts The document title parts.
 * @return array Return modified array of title parts.
 */
function power_document_title_parts( $parts ) {

	$power_document_title_parts = new Power_SEO_Document_Title_Parts();

	return $power_document_title_parts->get_parts( $parts );

}

add_filter( 'document_title_separator', 'power_document_title_separator' );
/**
 * Filter Document title parts separator based on SEO setting value.
 *
 * @since 2.6.0
 *
 * @param string $sep The title parts separator.
 * @return string Return modified title parts separator.
 */
function power_document_title_separator( $sep ) {

	$sep = power_get_seo_option( 'doctitle_sep' );

	return $sep ?: '-';

}

add_action( 'get_header', 'power_doc_head_control' );
/**
 * Remove unnecessary code that WordPress puts in the `head`.
 *
 * @since 1.3.0
 */
function power_doc_head_control() {

	remove_action( 'wp_head', 'wp_generator' );

	if ( ! power_get_seo_option( 'head_adjacent_posts_rel_link' ) ) {
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	}

	if ( ! power_get_seo_option( 'head_wlwmanifest_link' ) ) {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}

	if ( ! power_get_seo_option( 'head_shortlink' ) ) {
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	}

	if ( is_single() && ! power_get_option( 'comments_posts' ) ) {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

	if ( is_page() && ! power_get_option( 'comments_pages' ) ) {
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

}

add_action( 'power_meta', 'power_seo_meta_description' );
/**
 * Output the meta description based on contextual criteria.
 *
 * Output nothing if description isn't present.
 *
 * @since 1.2.0
 * @since 2.4.0 Logic moved to `power_get_seo_meta_description()`
 *
 * @see power_get_seo_meta_description()
 */
function power_seo_meta_description() {

	$description = power_get_seo_meta_description();

	// Add the description if one exists.
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
}

add_action( 'power_meta', 'power_seo_meta_keywords' );
/**
 * Output the meta keywords based on contextual criteria.
 *
 * Outputs nothing if keywords are not present.
 *
 * @since 1.2.0
 * @since 2.4.0 Logic moved to `power_get_seo_meta_keywords()`
 *
 * @see power_get_seo_meta_keywords()
 */
function power_seo_meta_keywords() {

	$keywords = power_get_seo_meta_keywords();

	// Add the keywords if they exist.
	if ( $keywords ) {
		echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '" />' . "\n";
	}
}

add_action( 'power_meta', 'power_robots_meta' );
/**
 * Output the robots meta code in the document `head`.
 *
 * @since 1.0.0
 * @since 2.4.0 Logic moved to `power_get_robots_meta_content()`
 *
 * @see power_get_robots_meta_content()
 *
 * @return void Return early if blog is not public.
 */
function power_robots_meta() {

	// If the blog is private, then following logic is unnecessary as WP will insert noindex and nofollow.
	if ( ! get_option( 'blog_public' ) ) {
		return;
	}

	$meta = power_get_robots_meta_content();

	// Add meta if any exist.
	if ( $meta ) {
		?>
		<meta name="robots" content="<?php echo esc_attr( $meta ); ?>" />
		<?php
	}

}

add_action( 'power_meta', 'power_responsive_viewport' );
/**
 * Outputs the responsive CSS viewport tag.
 *
 * Applies `power_viewport_value` filter on content attribute.
 *
 * @since 1.9.0
 * @since 2.7.0 Adds `minimum-scale=1` when AMP URL.
 * @since 3.0 Do not check if theme support `power-responsive-viewport`
 *
 * @return void Return early if child theme does not support `power-responsive-viewport`.
 */
function power_responsive_viewport() {
	/**
	 * Filter the viewport meta tag value.
	 *
	 * @since 2.3.0
	 *
	 * @param string $viewport_default Default value of the viewport meta tag.
	 */
	$viewport_value = apply_filters( 'power_viewport_value', 'width=device-width, initial-scale=1' );

	// If the web page is an AMP URL and `minimum-scale` is missing, add it.
	if ( power_is_amp() && strpos( $viewport_value, 'minimum-scale' ) === false ) {
		$viewport_value .= ',minimum-scale=1';
	}

	printf(
		'<meta name="viewport" content="%s" />' . "\n",
		esc_attr( $viewport_value )
	);

}

add_action( 'wp_head', 'power_load_favicon' );
/**
 * Echo favicon link.
 *
 * @since 1.0.0
 * @since 2.4.0 Logic moved to `power_get_favicon_url()`.
 *
 * @see power_get_favicon_url()
 *
 * @return void Return early if WP Site Icon is used.
 */
function power_load_favicon() {

	// Use WP site icon, if available.
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$favicon = power_get_favicon_url();

	if ( $favicon ) {
		echo '<link rel="icon" href="' . esc_url( $favicon ) . '" />' . "\n";
	}

}

add_action( 'wp_head', 'power_do_meta_pingback' );
/**
 * Adds the pingback meta tag to the head so that other sites can know how to send a pingback to our site.
 *
 * @since 1.3.0
 */
function power_do_meta_pingback() {

	if ( 'open' === get_option( 'default_ping_status' ) ) {
		echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '" />' . "\n";
	}

}

add_action( 'wp_head', 'power_paged_rel' );
/**
 * Output rel links in the head to indicate previous and next pages in paginated archives and posts.
 *
 * @link https://webmasters.googleblog.com/2011/09/pagination-with-relnext-and-relprev.html
 *
 * @since 2.2.0
 *
 * @return void Return early if doing a Customizer preview.
 */
function power_paged_rel() {

	global $wp_query;

	$next = '';
	$prev = $next;

	$paged = (int) get_query_var( 'paged' );
	$page  = (int) get_query_var( 'page' );

	if ( ! is_singular() ) {
		$prev = $paged > 1 ? get_previous_posts_page_link() : $prev;
		$next = $paged < $wp_query->max_num_pages ? get_next_posts_page_link( $wp_query->max_num_pages ) : $next;
	} else {
		// No need for this on previews.
		if ( is_preview() ) {
			return;
		}

		$numpages = substr_count( $wp_query->post->post_content, '<!--nextpage-->' ) + 1;

		if ( $numpages && ! $page ) {
			$page = 1;
		}

		if ( $page > 1 ) {
			$prev = power_paged_post_url( $page - 1 );
		}

		if ( $page < $numpages ) {
			$next = power_paged_post_url( $page + 1 );
		}
	}

	if ( $prev ) {
		printf( '<link rel="prev" href="%s" />' . "\n", esc_url( $prev ) );
	}

	if ( $next ) {
		printf( '<link rel="next" href="%s" />' . "\n", esc_url( $next ) );
	}

}

add_action( 'wp_head', 'power_meta_name' );
/**
 * Output meta tag for site name.
 *
 * @since 2.2.0
 *
 * @return void Return early if not HTML5 or not front page.
 */
function power_meta_name() {

	if ( ! is_front_page() ) {
		return;
	}

	printf( '<meta itemprop="name" content="%s" />' . "\n", esc_html( get_bloginfo( 'name' ) ) );

}

add_action( 'wp_head', 'power_meta_url' );
/**
 * Output meta tag for site URL.
 *
 * @since 2.2.0
 *
 * @return void Return early if not HTML5 or not front page.
 */
function power_meta_url() {

	if ( ! is_front_page() ) {
		return;
	}

	printf( '<meta itemprop="url" content="%s" />' . "\n", esc_url( trailingslashit( home_url() ) ) );

}

add_action( 'wp_head', 'power_canonical', 5 );
/**
 * Echo custom canonical link tag.
 *
 * Remove the default WordPress canonical tag, and use our custom
 * one. Gives us more flexibility and effectiveness.
 *
 * @since 1.0.0
 */
function power_canonical() {

	// Remove the WordPress canonical.
	remove_action( 'wp_head', 'rel_canonical' );

	$canonical = power_canonical_url();

	if ( $canonical ) {
		printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( apply_filters( 'power_canonical', $canonical ) ) );
	}

}

add_filter( 'power_header_scripts', 'do_shortcode' );
add_action( 'wp_head', 'power_header_scripts' );
/**
 * Echo header scripts in to wp_head().
 *
 * Allows shortcodes.
 *
 * Applies `power_header_scripts` filter on value stored in header_scripts setting.
 *
 * Also echoes scripts from the post's custom field.
 *
 * @since 1.0.0
 */
function power_header_scripts() {

	echo apply_filters( 'power_header_scripts', power_get_option( 'header_scripts' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Need to output scripts.

	// If singular, echo scripts from custom field.
	if ( is_singular() ) {
		power_custom_field( '_power_scripts' );
	}

}

add_action( 'power_before', 'power_page_specific_body_scripts' );
/**
 * Output page-specific body scripts if their position is set to 'top'.
 *
 * If the position is 'bottom' or null, output occurs in power_footer_scripts() instead.
 *
 * @since 2.5.0
 */
function power_page_specific_body_scripts() {

	if ( ! is_singular() ) {
		return;
	}

	if ( 'top' === power_get_custom_field( '_power_scripts_body_position' ) ) {
		power_custom_field( '_power_scripts_body' );
	}

}

add_action( 'after_setup_theme', 'power_custom_header' );
/**
 * Activate the custom header feature.
 *
 * It gets arguments passed through add_theme_support(), defines the constants, and calls `add_custom_image_header()`.
 *
 * Applies `power_custom_header_defaults` filter.
 *
 * @since 1.6.0
 *
 * @return void Return early if `custom-header` or `power-custom-header` are not supported in the theme.
 */
function power_custom_header() {

	$wp_custom_header = get_theme_support( 'custom-header' );

	// If WP custom header is active, no need to continue.
	if ( $wp_custom_header ) {
		return;
	}

	$power_custom_header = get_theme_support( 'power-custom-header' );

	// If Power custom is not active, do nothing.
	if ( ! $power_custom_header ) {
		return;
	}

	// Blog title option is obsolete when custom header is active.
	add_filter( 'power_pre_get_option_blog_title', '__return_empty_array' );

	// Cast, if necessary.
	$power_custom_header = isset( $power_custom_header[0] ) && is_array( $power_custom_header[0] ) ? $power_custom_header[0] : [];

	// Merge defaults with passed arguments.
	$args = wp_parse_args(
		$power_custom_header,
		apply_filters(
			'power_custom_header_defaults',
			[
				'width'                 => 960,
				'height'                => 80,
				'textcolor'             => '333333',
				'no_header_text'        => false,
				'header_image'          => '%s/images/header.png',
				'header_callback'       => '',
				'admin_header_callback' => '',
			]
		)
	);

	// Push $args into theme support array.
	add_theme_support(
		'custom-header',
		[
			'default-image'       => sprintf( $args['header_image'], get_stylesheet_directory_uri() ),
			'header-text'         => $args['no_header_text'] ? false : true,
			'default-text-color'  => $args['textcolor'],
			'width'               => $args['width'],
			'height'              => $args['height'],
			'random-default'      => false,
			'header-selector'     => '.site-header',
			'wp-head-callback'    => $args['header_callback'],
			'admin-head-callback' => $args['admin_header_callback'],
		]
	);

}

add_action( 'after_setup_theme', 'power_custom_logo' );
/**
 * Add support for the WordPress custom logo feature.
 *
 * Passes add_theme_support() arguments from `power-custom-logo` to `custom-logo`.
 *
 * Applies `power_custom_logo_defaults` filter.
 *
 * @since 3.1.0
 *
 * @return void Return early if `custom-logo` is supported or `power-custom-logo` is not supported in the theme.
 */
function power_custom_logo() {

	$wp_custom_logo = get_theme_support( 'custom-logo' );

	// If WP custom logo is active, no need to continue.
	if ( $wp_custom_logo ) {
		return;
	}

	$power_custom_logo = get_theme_support( 'power-custom-logo' );

	// If Power custom is not active, do nothing.
	if ( ! $power_custom_logo ) {
		return;
	}

	// Blog title option is obsolete when custom logo is active.
	add_filter( 'power_pre_get_option_blog_title', '__return_empty_array' );

	// Cast, if necessary.
	$power_custom_logo = isset( $power_custom_logo[0] ) && is_array( $power_custom_logo[0] ) ? $power_custom_logo[0] : [];

	// Merge defaults with passed arguments.
	$args = wp_parse_args(
		$power_custom_logo,
		apply_filters(
			'power_custom_logo_defaults',
			[
				'height'      => 100,
				'width'       => 400,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => '',
			]
		)
	);

	// Push $args into theme support array.
	add_theme_support(
		'custom-logo',
		[
			'header-text' => $args['header-text'],
			'height'      => $args['height'],
			'width'       => $args['width'],
			'flex-height' => $args['flex-height'],
			'flex-width'  => $args['flex-height'],
		]
	);

}

add_action( 'wp_head', 'power_custom_header_style' );
/**
 * Custom header callback.
 *
 * It outputs special CSS to the document head, modifying the look of the header based on user input.
 *
 * @since 1.6.0
 *
 * @return void Return early if `custom-header` not supported, user specified own callback, or no options set.
 */
function power_custom_header_style() {

	// Do nothing if custom header not supported.
	if ( ! current_theme_supports( 'custom-header' ) ) {
		return;
	}

	// Do nothing if user specifies their own callback.
	if ( get_theme_support( 'custom-header', 'wp-head-callback' ) ) {
		return;
	}

	$output = '';

	$header_image = get_header_image();
	$text_color   = get_header_textcolor();

	// If no options set, don't waste the output. Do nothing.
	if ( empty( $header_image ) && ! display_header_text() && get_theme_support( 'custom-header', 'default-text-color' ) === $text_color ) {
		return;
	}

	$header_selector = get_theme_support( 'custom-header', 'header-selector' );
	$title_selector  = '.custom-header .site-title';
	$desc_selector   = '.custom-header .site-description';

	// Header selector fallback.
	if ( ! $header_selector ) {
		$header_selector = '.custom-header .site-header';
	}

	// Header image CSS, if exists.
	if ( $header_image ) {
		$output .= sprintf( '%s { background: url(%s) no-repeat !important; }', $header_selector, esc_url( $header_image ) );
	}

	// Header text color CSS, if showing text.
	if ( display_header_text() && get_theme_support( 'custom-header', 'default-text-color' ) !== $text_color ) {
		$output .= sprintf( '%2$s a, %2$s a:hover, %3$s { color: #%1$s !important; }', esc_html( $text_color ), esc_html( $title_selector ), esc_html( $desc_selector ) );
	}

	if ( $output ) {
		printf( '<style type="text/css">%s</style>' . "\n", $output ); // phpcs:ignore  WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped.
	}

}

add_action( 'power_header', 'power_header_markup_open', 5 );
/**
 * Echo the opening structural markup for the header.
 *
 * @since 1.2.0
 */
function power_header_markup_open() {

	power_markup(
		[
			'open'    => '<header %s>',
			'context' => 'site-header',
		]
	);

	power_structural_wrap( 'header' );

}

add_action( 'power_header', 'power_header_markup_close', 15 );
/**
 * Echo the opening structural markup for the header.
 *
 * @since 1.2.0
 */
function power_header_markup_close() {

	power_structural_wrap( 'header', 'close' );
	power_markup(
		[
			'close'   => '</header>',
			'context' => 'site-header',
		]
	);

}

add_action( 'power_header', 'power_do_header' );
/**
 * Echo the default header, including the #title-area div, along with #title and #description, as well as the .widget-area.
 *
 * Does the `power_site_title`, `power_site_description` and `power_header_right` actions.
 *
 * @since 1.0.2
 *
 * @global $wp_registered_sidebars Holds all of the registered sidebars.
 */
function power_do_header() {

	global $wp_registered_sidebars;

	power_markup(
		[
			'open'    => '<div %s>',
			'context' => 'title-area',
		]
	);

	/**
	 * Fires inside the title area, before the site description hook.
	 *
	 * @since 2.6.0
	 */
	do_action( 'power_site_title' );

	/**
	 * Fires inside the title area, after the site title hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'power_site_description' );

	power_markup(
		[
			'close'   => '</div>',
			'context' => 'title-area',
		]
	);

	if ( has_action( 'power_header_right' ) || ( isset( $wp_registered_sidebars['header-right'] ) && is_active_sidebar( 'header-right' ) ) ) {

		power_markup(
			[
				'open'    => '<div %s>',
				'context' => 'header-widget-area',
			]
		);

			/**
			 * Fires inside the header widget area wrapping markup, before the Header Right widget area.
			 *
			 * @since 1.5.0
			 */
			do_action( 'power_header_right' );
			add_filter( 'wp_nav_menu_args', 'power_header_menu_args' );
			add_filter( 'wp_nav_menu', 'power_header_menu_wrap' );
			dynamic_sidebar( 'header-right' );
			remove_filter( 'wp_nav_menu_args', 'power_header_menu_args' );
			remove_filter( 'wp_nav_menu', 'power_header_menu_wrap' );

		power_markup(
			[
				'close'   => '</div>',
				'context' => 'header-widget-area',
			]
		);

	}

}

add_action( 'after_setup_theme', 'power_output_custom_logo', 11 );
/**
 * Adds the WordPress custom logo inside the title area, before the site title hook.
 *
 * @since 3.1.0
 */
function power_output_custom_logo() {

	if ( current_theme_supports( 'power-custom-logo' ) ) {
		add_action( 'power_site_title', 'the_custom_logo', 0 );
	}

}

add_action( 'power_site_title', 'power_seo_site_title' );
/**
 * Echo the site title into the header.
 *
 * Depending on the SEO option set by the user, this will either be wrapped in an `h1` or `p` element.
 * The Site Title will be wrapped in a link to the homepage, if a custom logo is not in use.
 *
 * Applies the `power_seo_title` filter before echoing.
 *
 * @since 1.1.0
 */
function power_seo_site_title() {

	// Set what goes inside the wrapping tags.
	$inside = current_theme_supports( 'power-custom-logo' ) && has_custom_logo() ? wp_kses_post( get_bloginfo( 'name' ) ) : wp_kses_post( sprintf( '<a href="%s">%s</a>', trailingslashit( home_url() ), get_bloginfo( 'name' ) ) );

	// Determine which wrapping tags to use.
	$wrap = power_is_root_page() && 'title' === power_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';

	// Fallback for static homepage if an SEO plugin is active.
	$wrap = power_is_root_page() && power_seo_disabled() ? 'p' : $wrap;

	// Fallback for latest posts if an SEO plugin is active.
	$wrap = is_front_page() && is_home() && power_seo_disabled() ? 'h1' : $wrap;

	// And finally, $wrap in h1 if HTML5 & semantic headings enabled.
	$wrap = power_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

	/**
	 * Site title wrapping element
	 *
	 * The wrapping element for the site title.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'power_site_title_wrap', $wrap );

	// Build the title.
	$title = power_markup(
		[
			'open'    => sprintf( "<{$wrap} %s>", power_attr( 'site-title' ) ),
			'close'   => "</{$wrap}>",
			'content' => $inside,
			'context' => 'site-title',
			'echo'    => false,
			'params'  => [
				'wrap' => $wrap,
			],
		]
	);

	/**
	 * The SEO title filter
	 *
	 * Allows the entire SEO title to be filtered.
	 *
	 * @since ???
	 *
	 * @param string $title  The SEO title.
	 * @param string $inside The inner portion of the SEO title.
	 * @param string $wrap   The html element to wrap the title in.
	 */
	$title = apply_filters( 'power_seo_title', $title, $inside, $wrap );

	echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitize done prior to filter application
}

add_action( 'power_site_description', 'power_seo_site_description' );
/**
 * Echo the site description into the header.
 *
 * Depending on the SEO option set by the user, this will either be wrapped in an `h1` or `p` element.
 *
 * Applies the `power_seo_description` filter before echoing.
 *
 * @since 1.1.0
 */
function power_seo_site_description() {

	// Set what goes inside the wrapping tags.
	$inside = esc_html( get_bloginfo( 'description' ) );

	// Determine which wrapping tags to use.
	$wrap = power_is_root_page() && 'description' === power_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';

	// And finally, $wrap in h2 if HTML5 & semantic headings enabled.
	$wrap = power_get_seo_option( 'semantic_headings' ) ? 'h2' : $wrap;

	/**
	 * Site description wrapping element
	 *
	 * The wrapping element for the site description.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'power_site_description_wrap', $wrap );

	// Build the description.
	$description = power_markup(
		[
			'open'    => sprintf( "<{$wrap} %s>", power_attr( 'site-description' ) ),
			'close'   => "</{$wrap}>",
			'content' => $inside,
			'context' => 'site-description',
			'echo'    => false,
			'params'  => [
				'wrap' => $wrap,
			],
		]
	);

	// Output (filtered).
	$output = $inside ? apply_filters( 'power_seo_description', $description, $inside, $wrap ) : '';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $output;

}

/**
 * Sets attributes for the custom menu widget if used in the Header Right widget area.
 *
 * @since 1.9.0
 *
 * @param array $args Navigation menu arguments.
 * @return array $args Arguments for custom menu widget used in Header Right widget area.
 */
function power_header_menu_args( $args ) {

	$args['container']   = '';
	$args['link_before'] = $args['link_before'] ?: sprintf( '<span %s>', power_attr( 'nav-link-wrap' ) );
	$args['link_after']  = $args['link_after'] ?: '</span>';
	$args['menu_class'] .= ' power-nav-menu';
	$args['menu_class'] .= power_superfish_enabled() ? ' js-superfish' : '';

	return $args;

}

/**
 * Wrap the header navigation menu in its own nav tags with markup API.
 *
 * @since 2.0.0
 *
 * @param string $menu Menu output.
 * @return string $menu Modified menu output, or original if not HTML5.
 */
function power_header_menu_wrap( $menu ) {

	return power_markup(
		[
			'open'    => sprintf( '<nav %s>', power_attr( 'nav-header' ) ),
			'close'   => '</nav>',
			'content' => $menu,
			'context' => 'header-nav',
			'echo'    => false,
		]
	);

}

add_action( 'power_before_header', 'power_skip_links', 5 );
/**
 * Add skip links for screen readers and keyboard navigation.
 *
 * @since 2.2.0
 *
 * @return void Return early if skip links are not supported.
 */
function power_skip_links() {

	if ( ! power_a11y( 'skip-links' ) ) {
		return;
	}

	// Call function to add IDs to the markup.
	power_skiplinks_markup();

	// Determine which skip links are needed.
	$links = [];

	if ( power_nav_menu_supported( 'primary' ) && has_nav_menu( 'primary' ) ) {
		$links['power-nav-primary'] = esc_html__( 'Skip to primary navigation', 'power' );
	}

	$links['power-content'] = esc_html__( 'Skip to main content', 'power' );

	if ( 'full-width-content' !== power_site_layout() ) {
		$links['power-sidebar-primary'] = esc_html__( 'Skip to primary sidebar', 'power' );
	}

	if ( in_array( power_site_layout(), [ 'sidebar-sidebar-content', 'sidebar-content-sidebar', 'content-sidebar-sidebar' ], true ) ) {
		$links['power-sidebar-secondary'] = esc_html__( 'Skip to secondary sidebar', 'power' );
	}

	if ( current_theme_supports( 'power-footer-widgets' ) ) {
		$footer_widgets = get_theme_support( 'power-footer-widgets' );
		if ( isset( $footer_widgets[0] ) && is_numeric( $footer_widgets[0] ) && is_active_sidebar( 'footer-1' ) ) {
			$links['power-footer-widgets'] = esc_html__( 'Skip to footer', 'power' );
		}
	}

	/**
	 * Filter the skip links.
	 *
	 * @since 2.2.0
	 *
	 * @param array $links {
	 *     Default skiplinks.
	 *
	 *     @type string HTML ID attribute value to link to.
	 *     @type string Anchor text.
	 * }
	 */
	$links = (array) apply_filters( 'power_skip_links_output', $links );

	// Write HTML, skiplinks in a list.
	$skiplinks = '<ul class="power-skip-link">';

	// Add markup for each skiplink.
	foreach ( $links as $key => $value ) {
		$skiplinks .= '<li><a href="' . esc_url( '#' . $key ) . '" class="screen-reader-shortcut"> ' . $value . '</a></li>';
	}

	$skiplinks .= '</ul>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $skiplinks;

}
