# Power Framework Change Log

https://daniellane.eu/themes/power/

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Up until release 2.7.0, this project did _not_ follow semantic versioning. It followed the WordPress policy where updates of x and y in an x.y.z version number means a major release, and updates to z means a patch release.

## [3.1.2] - 2019-09-05

### Fixed
* Prevent the block editor and Power sidebar failing to load if a custom post type supports `power-layouts` but not `custom-fields`.
* Find '© 2019' in stored footer text and replace it with the `[footer_copyright]` shortcode during the Power update process. This is designed to ensure that the copyright year in footer text updates in future years.

## [3.1.1] - 2019-08-29

### Added
* Add a new power-breadcrumbs-visible body class if breadcrumbs are visible on a page.
* Add new power-singular-image-visible body class to allow styling of pages if a featured image appears on them.

### Changed
* Add the power-breadcrumbs-hidden body class if breadcrumbs are disabled for the current page type. (Previously this was only applied if breadcrumbs were active but hidden using “hide breadcrumbs”.)
* Hide Custom Classes panel in the Power sidebar if the current post type is not public.
* Do not emit power-singular-image-hidden body class if unsupported by current post type.
* Hide Breadcrumbs panel in the new Power editor sidebar if a theme opts to disable support for Power breadcrumbs.
* Hide Power sidebar if no panels will display.

### Fixed
* Fix an issue where heading levels would change for static homepages on sites using SEO plugins.
* Fix an issue where changes to Power sidebar settings would fail to save if the Easy Digital Downloads plugin was in use.
* Prevent the Power sidebar from appearing on posts that do not support custom-fields.

## [3.1.0] - 2019-08-21

Requires WordPress 5.0+ and PHP 5.6+.

### Added
* Theme support: Add `power-custom-logo` theme support for logo output. This enables logo upload in the Site Identity section, outputs a logo, and improves accessibility of the site title.
* Customizer: Add a footer text option under Theme Settings -> Footer.
* Customizer: Add new Singular Content section to hold settings specific to posts, pages and other post types.
* Editor: Add a new Power sidebar for sites that use the block editor.
* Editor: Add a “hide breadcrumbs” checkbox to the Power sidebar in the block editor.
* Editor: Add a “hide title” checkbox to the Power sidebar in the block editor.
* Editor: Add a “hide featured image” checkbox to the Power sidebar in the block editor.
* Editor: Add status info to the Breadcrumbs panel to show global status of breadcrumbs for the current post type (requires the `edit-theme-options` capability and `power-breadcrumbs-toggle` post type support).
* Editor: Add status info to the Images panel to show global status of featured images for the current post type (requires the `edit-theme-options` capability and `power-singular-images` post type support).
* Editor: Add link to the Breadcrumbs panel to toggle breadcrumbs on and off globally for the current post type (requires the `edit-theme-options` capability and `power-breadcrumbs-toggle` post type support).
* Editor: Add link to the Images panel to toggle featured images on and off globally for the current post type (requires the `edit-theme-options` capability and `power-singular-images` post type support).
* Schema: Suppress output of Power schema if Yoast SEO is outputting JSON-LD markup.
* Schema: developers can disable schema with `add_filter( 'power_disable_microdata', '__return_true' );`.
* Post type support: Add `power-singular-images` post type support to enable output of featured images, and a related output setting in the new Singular Content Customizer panel.
* Post type support: Add `power-breadcrumbs-toggle` post type support to control which post types support the “hide breadcrumbs” checkbox.
* Post type support: Add `power-title-toggle` post type support to control which post types support the “hide title” checkbox.
* CSS: Add 'power-title-hidden' body class if the “hide title” checkbox is enabled.
* Theme setup: add option to import widgets during one-click theme setup. See Power Sample's `onboarding.php` for an up-to-date usage example.
* Theme setup: Add Starter Packs feature. Theme developers can now offer users a choice of content and plugin packs during theme setup. See Power Sample's `onboarding.php` for an up-to-date usage example.
* Theme setup: Add Child Theme Setup menu item to the Power admin menu to make finding the Getting Started page again easier for active themes that support one-click theme setup.
* REST API: Power now exposes hide title and hide breadcrumbs state, page layout, and custom body and post class via the `meta` field in the `posts` endpoint: `/wp-json/wp/v2/posts/[id]`
* REST API: Power now exposes supported layouts for the active theme via a new `layouts` endpoint: `/wp-json/power/v1/layouts/site`.
* Tooling: use the `@wordpress/scripts` package to build ES2015+ and React code.

### Removed
* SEO: Remove `noodp` and `noydir` settings. The Yahoo! Directory (the `ydir` in `noydir`) closed in 2014. The Open Directory Project (the `odp` in `noodp`) closed in 2017. Related settings are also removed from the database during upgrade.
* Deprecation: The `power_footer_creds_text` filter is now deprecated. Developers can point users who want to edit their footer text to the new Power footer setting in the Customizer. If you want to set default footer text for your child theme during theme activation, you should set the `footer_text key` in your theme’s `child-theme-settings.php` file.
* Tooling: remove the `phpcs-fixed` command. Power now runs `phpcs` against all files instead of a subset of known-good files.

### Changed
* Code: Switch to PHP short array syntax and enforce this via `phpcs.xml.dist`.
* Translation: Remove HTML from translated strings where possible.
* Tooling: language files are now generated with WP-CLI. This ensures strings in JavaScript files are now captured.
* Documentation: Add link to the Power developer documentation site to the readme.
* Theme setup: create new menus during one-click theme setup instead of appending menu items to existing menus.
* SEO: The Power SEO “Primary Title H1” setting will now apply on static homepages.
* Accessibility: Change skip link text from “Skip to content” to “Skip to main content” to improve pronunciation of “content” by screen readers.
* Customizer: Update text in the Theme Settings -> Updates panel to clarify what site data is sent during update requests and link to the privacy policy.

### Fixed
* Standards: Remove an unneeded argument when calling `power_onboarding_import_content()`.
* Standards: Address all PHP_CodeSniffer coding standards violations.
* Standards: Fix a warning that could appear during update if Power Simple Sidebars was in use.
* Tooling: Correct an issue where PHP_CodeSniffer extensions for VS Code and Atom users would fail to run.
* Translation: Correct missing translators comments and correct numbered placeholders.
* Translation: Fix translators comments that differed for the same string.
* Breadcrumbs: Ensure “Breadcrumbs on Homepage” enables breadcrumbs on static homepages. Previously “Breadcrumbs on Pages” also had to be checked.
* General: `power_get_global_post_type_name()` will now return the correct post type if the main query has been filtered to show additional post types.

## [3.0.3] - 2019-08-05

### Added
* Ensure that default settings get inserted on theme activation

## [3.0.2] - 2019-07-03

### Fixed
* Fixed instances of late escaping that were too aggressive.
* Fixed an issue encountered by the AMP plugin where the use of `uniqid()` breaks the post-processor.

## [3.0.1] - 2019-06-20

### Added
* Restored `404.php`.
* Restored `page.php`.
* Restored `search.php`.
* Restored `single.php`.

### Removed
* Removed language files from core while we work on reliability standards.

### Fixed
* Fixed issue where certain child theme styles fail to load. `CHILD_THEME_NAME` is now used (if available) when returning a theme handle (used when enqueueing CSS).

## [3.0.0] - 2019-06-19

### Added
* Added AMP support if the WordPress AMP plugin is installed and active (https://wordpress.org/plugins/amp/). This includes an AMP-compatible, responsive menu that theme developers can add via `power_register_responsive_menus()`, in place of having to enqueue their own responsive menu scripts.
* Added `power_get_theme_handle()` function that returns a formatted theme handle, via the theme name in `style.css`, for use in places where a string of words (lowercase, separated by dashes) is needed.
* Added `power_get_theme_version()` function to return the version string from the stylesheet header, or the current Unix time if the `SCRIPT_DEBUG` constant is true. This is helpful as a cache-busting string when enqueueing assets, so that you no longer need to add a `CHILD_THEME_VERSION` constant to your `functions.php` that duplicates information in your stylesheet header.
* Added memoization to `power_get_theme_handle()` and `power_get_theme_version()` so multiple uses of these functions won't negatively affect performance.
* Added ability to use the `query_args` custom field to trigger a custom loop in pages (like the old `page_blog.php` used to do).
* Added db upgrade functions that provide for backward compatibility for sites using the `page_blog.php` and `page_archive.php` page templates.
* Added Power information to the WordPress Site Health admin page.

### Removed
* Removed Theme and SEO Settings page content. Customizer is now the canonical location for configuring these settings.
* Removed support for all non-html5 output.
* Removed `404.php` template.
* Removed `page.php` template.
* Removed `page_archive.php` template.
* Removed `page_blog.php` template.
* Removed `search.php` template.
* Removed `single.php` template.
* Removed Adsense Auto Ads integration, with limited backward compatibility.
* Removed integration with Google Plus.
* Removed all styles from `style.css`.
* Removed all functions deprecated prior to Power 2.0.0.
* Removed compatibility with old and unknown breadcrumb plugins/functions.
* Removed unused `$backtotop` variable and filter from footer output function.
* Deprecated the `power_nav_right()` callback function.

### Changed
* Output the responsive viewport meta tag by default.

### Fixed
* Fixed all known instances of content being output without any escaping or sanitization.
* Fixed bug where an empty paragraph was output in the archive intro text.
* Fixed bug in comments where certain markup was appearing out of order.
* Fixed issue where the Power Plugins link was showing for people who did not have permission to install plugins.

## [2.10.1] - 2019-05-07

### Added
* Added action links (via filter) to the end of the update completed screen.

### Removed
* Removed automatic redirect to Theme Settings after an update.
* Removed the function that output a "success" notice after database upgrade. Upgrades are now silent.

### Fixed
* Fixed issue on Power Plugins page that resulted in a fatal error on WP 5.x or older.
* Fixed issue with the database upgrade that would cause it not to run in certain circumstances.

## [2.10.0] - 2019-05-01

### Added
* Added `wp power core upgrade` WP-CLI Command.
* Added `wp power core version` WP-CLI Command.
* Added `wp power db upgrade` WP-CLI Command.
* Added `wp power db version` WP-CLI Command.
* Added `wp power setting get` WP-CLI Command.
* Added `wp power setting update` WP-CLI Command.
* Added child theme version to data sent to update server.
* Added Power Plugins page, to allow Power plugins to be discovered and installed easily.
* Added actions hooks before and after content import during Theme Setup.
* Added `wp_body_open()` to the header template, directly after the opening `<body>` tag.
* Added ability for Power to run a database upgrade after an update.

### Changed
* Pass all comment markup through the Markup API.
* Process shortcodes and embeds in archive intro text.
* Allow for the import of local images during Theme Setup.
* Made output of the Theme Setup screen conditional based on the content in the onboarding config file.
* Pass comment author name through Markup API.

### Fixed
* Fixed broken comment author link in xHTML.
* Fixed empty H1 on Theme Setup page.
* Fixed many WordPress code standards warnings and errors.
* Fixed multiple home links in breadcrumbs under certain circumstances.
* Fixed instances where users who can't install plugins are sent to onboarding screen.

## [2.9.1] - 2019-03-20

### Fixed
- Fixed issue where `get_comment_author_link()` was being escaped improperly.


## [2.9.0] - 2019-03-13
### Added
* Added menu support to onboarding process.
* Added featured image support to onboarding process.
* Added support for assigning static Posts page to onboarding process.
* Added post excerpt support to onboarding process.
* Added dynamic support for child theme constants.
* Added support for importing specified settings on theme activation via a config file.
* Added functions to get, set, and delete settings that eventually expire.

### Changed
* Improved the onboarding process for screen readers.
* Update Superfish to 1.7.10.
* Changed references of "Front Page" to "Homepage" where appropriate.
* Use the post type's label as linked text in the metabox on CPT archive settings.
* Changed the Power update storage to an expiring setting rather than a transient.

### Removed
* Removed several Grunt dev tools and replaced with node scripts.
* Removed sitemap from 404 template (performance and security).

## [2.8.1] - 2019-01-30
### Fixed
- Fixed `power_human_time_diff()` to display accurate relative dates.
- Fixed a problem with `aria-hidden` and `tabindex` attributes were being escaped, causing the quotes to be unintentionally encoded.

## [2.8.0] - 2019-01-16
### Added
- Add a `power_get_config()` function, to locate and load config files from Power and a child theme.
- Add a new "onboarding" feature that allows users to import homepage demo content in WordPress 5.0.
- Add a new function that allows you to get an author box by specified user.

### Changed
- Improved/clarified the labels on settings/customizer pages.
- Changed references of "- None -" to "None" in forms, for better accessibility.

## [2.7.3] - 2018-12-19
### Fixed
- Fixed an issue with the search form, where some elements were missing attributes, or had the wrong attributes.

## [2.7.2] - 2018-12-13
### Fixed
- Fixed issue with schema on the breadcrumbs wrapper by removing breadcrumb div schema.org attributes when not needed, use RDFa for Breadcrumb NavXT.
- Fixed issue with the search form not properly outputting a label when a11y is enabled.

## [2.7.1] - 2018-11-15
### Fixed
- Fixed issue with filtered content being passed to `wp_kses_post()`.
- Fixed issue with the `power_search_form()` function returning nothing if used directly.

## [2.7.0] - 2018-11-14
### Added
- Added soft PHP 5.3 requirement, with admin messaging.
- Added meta tag for breadcrumb position.
- Added ability to export or remove private data via the WordPress privacy tools.
- Added ability to autoload namespaced classes.
- Added `power_is_amp()` utility function for detecting when the request is an AMP URL.
- Added `minimum-scale` to the viewport meta tag when the request is an AMP URL.
- Added a `power_more_text` filter.
- Added a `/docs` folder for housing Power documentation.
- Added individual changelog files for each release.
- Added SEO support for SEOPress.
- Added Power version to the "At a Glance" dashboard widget.
- Added `rel="noopener noreferrer"` to new window links.
- Added `aria-current` to pagination for accessibility.

### Changed
- Use [Semantic Versioning](https://semver.org/) for all future releases.
- Use config file for breadcrumb arguments.
- Use Markup API to build breadcrumb links.
- Redirect to the "What's New" page on all upgrades, not just "major" ones.
- Change the license line in all file headers to "GPL-2.0-or-later".
- Use `wp_strip_all_tags()` instead of `strip_tags()`.
- Replace all references to "Copyblogger" with "Core Engine".
- Replace all references to "StudioPress" with "Core Engine".
- Replace all references to "Studiopress" with "Core Engine".
- Refresh `.editorconfig`.
- Use Markup API for opening and closing `entry-content` tags.
- Clear cache at the end of an upgrade.

### Fixed
- Fixed various code standards violations.
- Fixed various missing or incorrect inline documentation.
- Fixed issue where avatars were fetched even when the size to fetch is `0`.
- Fixed issue where `power_update_action_links()` was not returning an array.
- Fixed potential null pointer exceptions.
- Fixed misuses of `mb_strlen()`.
- Fixed Tiago Hillebrandt's Twitter link.

### Removed
- Deprecated `power_is_major_version()`.
- Removed direct file access block from `comments.php`.
- Removed an unused variable assignment in the entry content output function.
- Removed a duplicate `description` from `composer.json`.
- Removed tab stop on `aria-hidden` featured images.
- Remove all references to "Scribe".

## [2.6.1] - 2018-03-14
### Fixed
- Fix compatibility issue with breadcrumbs in Yoast SEO.
- Fix issue with extra slashes in settings when using Customizer.
- Fix PHP 7 issue with non-static methods being used statically.
- Fix empty string warning in `skip-links.js`.

## [2.6.0] - 2018-03-05
### Added
- Add option to sort Featured Posts by date modified.
- Add contextual filter for `content` passed through the Markup API.
- Add `Power_Customizer` class.
- Add `Power_SEO_Document_Title_Parts` class.
- Add `title-tag` theme support by default.
- Add class autoloader.
- Add support for AdSense Auto Ads.
- Add `aria-label` attribute to secondary `nav` element.
- Add allowance for extra attributes on script tags for registered scripts.

### Changed
- Change URLs to `https` wherever possible.
- Update normalize.css to `7.0.0`.
- Duplicate all theme and SEO settings in the Customizer.
- Move all classes to their own files in `lib/classes`.
- Use Markup API for `entry-title-link`.
- Use Markup API for 404 page title.
- Change description for headings on archive pages to account for accessibility.
- Improve color scheme retrieval function.

### Fixed
- More compliance with WordPress coding standards.
- Set ID of `entry-pings` to `comments` if only pings exist.
- Ensure default settings get saved to database in new installs.
- Change `h3` to `h2` for titles in admin metaboxes.
- Ensure theme support for Power import / export menu before outputting.
- Check for post parents before outputting parent in breadcrumbs.
- Ensure `[post_tags]` and `[post_categories]` are valid for post type before outputting.
- Update `aria-label` attributes for `nav` elements to remove redundant "navigation" word.

### Removed
- Remove duplicate `power_load_favicon` from being hooked to `wp_head`.
- Remove screen reader `h2` from inside Header Right widget area.
- Remove screen reader `h2` from inside primary `nav` element.
- Remove feed settings if Power 2.6 is your first version.

## [2.5.3] - 2017-09-27
### Fixed
- Prevent global scripts being slashed if they are unchanged.

## [2.5.2] - 2017-06-09
### Fixed
- Alternate method for preventing attribute filter on closing tags.

## [2.5.1] - 2017-06-08
### Added
- Add logic to detect post-upgrade redirect type.

### Changed
- Updated docblock for `power_post_meta()`.

### Fixed
- Fix issue with script loading logic.
- Fix issue with Layout API fallback logic.
- Fix issue with Layout API type priority determination.
- Fix issue with posts not being excluded in Featured Posts widget.
- Fix issue with `entry` attribute filter being applied to closing tag.
- Fix issue with use of `require` by switching back to `require_once`.

## [2.5.0] - 2017-04-20
_Requires WordPress 4.7.0.__
### Added
- Add instances of markup API use in several locations where it was previously not used.
- Add any missed XHTML markup to the XHTML markup filter.
- Add `Power_Contributors` and `Power_Contributor` classes.
- Add `views` directory and extracted output to organized view files.
- Add full support for WordPress's new title tag.
- Add slashing for user script input fields.
- Add primary category support when Yoast SEO is on, but breadcrumb feature is off.
- Add support for multiple layout types depending on context.
- Add script loader class.
- Add ability to specify location of entry scripts via a second option.
- Add filter for capability required to use CPT archive settings.
- Add filter to disable layout settings on CPT archive settings page.
- Add sanitizer for layout settings on CPT archive settings page.
- Add a posts page check to `power_do_blog_template_heading()`.
- Add filter for entry content display options in the customizer.
- Add terms back to terms array in our terms filter.
- Add `power_strip_p_tags()` function.
- Add center alignment option to featured image alignment setting.
- Add more filters to breadcrumb class.

### Changed
- Split featured post and page widget entry header markup, gave markup API context for each.
- Restored adding `tabindex` via JavaScript when `power-accessibility` is supported.
- Prevent smushed offscreen accessible text.
- Reorganized `init.php`.
- Strip paragraph tags from filtered credits text to avoid paragraph nesting.
- Standardize the context naming in widget markup.
- Flag entry markup as `is_widget` via the params array so it can be modified without affecting other entries.
- Restored new line between admin screen buttons.
- Improvements to composer, PHPCS, and unit tests.
- Switch all schema.org URLs to `https`.
- Formally deprecate `power_get_additional_image_sizes()`.
- Formally deprecate `power_contributors()`.
- Formally deprecate `power_register_scripts()`.
- Formally deprecate `power_load_scripts()`.
- Formally deprecate `power_load_admin_scripts()`.
- Formally deprecate `power_load_admin_js()`.
- CSS improvements.
- Code optimization and documentation improvements.
- Ensure skip links filter returns an array.
- Improve randomness of search form ID.
- Fix potential issue with footer scripts filter.
- Move `aria-label` to the anchor element so screen readers will announce it.
- Add capability check to CPT archive settings link in the toolbar.
- Refactor and improve archive headings.
- Fix typo in comments feed setting.

### Removed
- Remove semantic headings SEO option, with fallback for backward compatibility.
- Disable `backtotop` output if HTML5 is on.
- Remove output buffering on search form.
- Remove unnecessary heading on skip links.

## [2.4.2] - 2016-10-04
### Fixed
- Fix issue with featured post/page widget outputting `entry-content` div when XHTML is active.

## [2.4.1] - 2016-09-30
### Fixed
- Fix issue with filters on featured post and page widget content output.
- Fix some typos in the What's New page, as well as the `CHANGELOG.md` file.

## [2.4.0] - 2016-09-28
_Requires WordPress 4.4.0.__
### Added
- Add `unfiltered_or_safe_html` sanitizer.
- Add or correct lots of inline documentation.
- Add `phpcs.xml` file for code standards testing.
- Add identifying classes to featured posts' "More Posts" section title and list.
- Add `$wrap` and `$title` to the passed arguments of the `power_post_title_output` filter.
- Add new features to the Markup API, allowing for open and close arguments, passing content, and new filters.
- Add `js-superfish` class to all menus that support it.
- Add missing "to" in `power_prev_next_post_nav()`'s comment header.
- Add new functions that handle the logic for meta and favicon markup, and amended existing output functions to use them.
- Add release notes going back to 1.6.0 to `CHANGELOG.md`.

### Changed
- Extract XHTML from Power output, and added it back in with new Markup API filters if HTML5 is not supported.
- Move `power_create_initial_layouts()` to the `power_setup` hook. Possible breaking change, in order to ensure compatibility with WordPress 4.7+.
- Move `h1` elements outside the form on admin settings pages.
- Move SEO tooltips to Help tab on post editor screen.
- Change URLs for gravatars on the "What's New" page to use HTTPS.
- Change Featured Post widget to use placeholder instead of default value for number of posts to show.
- Change CPT archive intro setting to use `unfiltered_or_safe_html` sanitizer.
- Change some code and most documentation to better match WordPress coding standards.
- Change to use of time constants in update check transients.
- Change sitemap to hide Posts-related sections if the site has no Posts.
- Change `power_user_meta_default_on()` and `Power_Admin::create()` to do return checks earlier.

### Removed
- Remove colons from labels on settings screens.
- Remove errant `$` in the URL used in the "parent theme active" admin notice.
- Remove unused global for Admin Readme class.
- Remove dead code in two post shortcode callback functions.
- Remove unused parameters in `power_nav_menu_link_attributes()`.

### Fixed
- Fix heading on the import/export admin page to be `<h1>`.
- Fix Featured Post entry header to display `<header>` wrapper even when only byline is showing.
- Fix typo on SEO settings screen.

## [2.3.1] - 2016-08-02
### Changed
- Optimize `power_truncate_phrase()` by returning early if `$max_characters` is falsy.

### Removed
- Remove type hinting in `Power_Admin_CPT_Archive_Settings` constructor to prevent fatal error in WordPress 4.6.

## [2.3.0] - 2016-06-15
### Added
- Apply identifying class to entry image link.
- Add a toolbar link to edit custom post type archive settings.
- Add filter for the viewport meta tag value.
- Add shortcodes for site title and home link.
- Add filters for Power default theme support items.
- Add ability to specify post ID when using `power_custom_field()`.
- Add admin notice when Power is activated directly.
- Add a11y to the paginaged post navigation.
- Add relative_depth parameter to date shortcodes.

### Changed
- Allow custom post classes on Ajax requests to account for endless scroll.
- Change "Save Settings" to "Save Changes", as WordPress core does.
- Use version constant rather than database setting for reporting theme version in Settings.
- Use sfHover for superfish hover state.
- Prevent empty footer widgets markup.
- Prevent empty spaces in entry footer of custom post types.
- Trim filtered value of entry meta.
- Update and simplify favicon markup for the modern web.
- Prevent author shortcode from outputting empty markup when no author is assigned.
- Disable author box on entries where post type doesn't support author.
- Change the label on the update setting to reflect what it actually does, check for updates.
- Update theme tags.
- Enable after entry widget area for all post types via post type support.
- Hide layout selector when only one layout is supported.
- Disable author shortcode output if author is not supported by post type.
- Improve image size retrieval function and usage.
- Update to `normalize.css` 4.1.1.
- Use TinyMCE for archive intro text input.
- Allow foreign language characters in content limit functions.
- Pass entry image link through markup API.
- Allow adjacent single entry navigation via post type support.
- Exclude posts page from page selection dropdown in Featured Page widget.

### Removed
- Remove the top buttons (save and reset) from Power admin classes.
- Remove right float on admin buttons.
- Remove unnecessary warning from theme description in `style.css`.

### Fixed
- Fix issue with no sitemap when running html5 and no a11y support for 404 page.

## [2.2.7] - 2016-03-04
### Changed
- Limit entry class filters to the front end.

### Removed
- Remove Scribe nag.

### Fixed
- Fix issue with multisite installs where Power could technically upgrade before WordPress.
- Fix issue with Power using old style term meta method in some places.

## [2.2.6] - 2016-01-05
### Added
- Include and use local html5shiv file, rather than the one hosted at Google Code.

### Changed
- Use CreativeWork as default content type.
- Update Term Meta for WordPress 4.4.

## [2.2.5] - 2015-12-17
_Requires WordPress 4.3.0._
### Fixed
- Fix issue with entries not honoring selected layout.

## [2.2.4] - 2015-12-16
### Changed
- Use form-table style on all Power admin areas.
- Make posts page (when static homepage selected) honor selected page layout.
- Make a11y features available only to HTML5 themes.
- Limit markup API filter for nav links to HTML5.
- Allow archive layout selector to be disabled by removing theme support.
- Relative timestamp enhancement.
- Later priority for Power entry redirect.

### Removed
- Remove unintended rel="next" code output on archive pages.

### Fixed
- Fix Power settings screen styling for WordPress 4.4.

## [2.2.3] - 2015-10-12
### Added
- Add screen reader text to read more link in Featured Page Widget.

### Changed
- Prevent automatic support of all a11y features if no argument is supplied.
- Require explicit 404-page a11y feature.
- Turn on screen-reader-text a11y support if any a11y support is enabled.

### Fixed
- Fix uneven spacing in numeric pagination.
- Fix Featured Post Widget double outputs screen reader text on read more link.
- Fix Read More link not outputting screen reader text when "more" tag is used.
- Fix potential for 2 H1 titles on author archives.
- Fix a11y heading output for primary nav, even if no menu is assigned.
- Fix potential for multiple H1 titles on homepage.
- Fix small bug with screen-reader-text and RTL support.
- Fix double separator character in feed title.

## [2.2.2] - 2015-09-08
### Fixed
- Released to correct corrupted zip from 2.2.1 release.

## [2.2.1] - 2015-09-08
### Added
- Add boolean attribute option to markup API.
- Add H1 to posts page when using static front page and theme supports a11y.
- Add helper function to filter markup to add .screen-reader-text class to markup.

### Changed
- Better logic for generating H1 on front page.
- Prevent duplicate H1 elements on author archives.
- Only output http://schema.org/WebSite on front page.
- Disable http://schema.org/WebSite if SEO plugin is active, to prevent conflicts.
- Pass archive title / description wrappers through markup API.

### Removed
- Remove incorrect usage of mainContentOfPage.
- Remove a11y checks for titles that were previously output by default.

### Fixed
- Fix issue with Schema.org microdata when using Blog template.
- Fix breadcrumb Schema.org microdata for breadcrumb items.

## [2.2.0] - 2015-09-01
### Changed
- Allow child themes to enable accessibility features for web users with disabilities.
- Improvements to the Schema.org microdata Power outputs.
- Compatibility with WordPress's generated Title Tag output.
- Compatibility with WordPress's new Site Icon feature.
- Allow entry meta to be turned off on a per post type level.
- Many other improvements and bug fixes.

## [2.1.3] - 2015-08-12
_Requires WordPress 3.8.0._
### Changed
- Prepare taxonomy term meta for mandatory split in WordPress 4.3.

## [2.1.2] - 2014-07-15
### Changed
- Updated the `.pot` file with the new strings.

### Fixed
- Fix untranslatable strings in the Customizer.
- Fix comment author link bug.

## [2.1.1] - 2014-07-01
### Fixed
- Fix secondary navigation ID on XHTML child themes.
- Fix After Entry widget area not checking for theme support.
- Fix Archive Settings menu item not showing for custom post types.
- Fix `sprintf()` warnings in post info and post meta.

## [2.1.0] - 2014-06-30
### Added
- Add Customizer settings.
- Add content archives image alignment option.
- Add centre alignment option to featured widgets.
- Add gallery and caption styles.
- Add Google Web Font Lato weight 400.
- Add admin RTL style sheet.
- Add `power_before_while` action hook.
- Add `power_user_meta_defaults` filter hook.
- Add $args argument to `power_get_image_default_args` filter hook.
- Add `power_register_widget_area_defaults` filter hook.
- Add context to post info and post meta areas to allow filtering.
- Add `power_get_nav_menu and power_nav_menu()` functions.
- Add `post_modified_date` and `post_modified_time shortcodes`.
- Add echo methods to admin class for field name, id and value.
- Add power-form class to main wrap on `Power_Admin_Form` pages.
- Add gallery and caption HTML5 support.
- Add support for `DISALLOW_FILE_MODS` when displaying update notifications.
- Add `power_regster_widget_area()` function.
- Add new widget area with power-after-entry-widget-area theme support.
- Add Feedblitz support.
- Add compatibility for WordPress SEO 1.5+ breadcrumb changes.
- Add email address sanitization filter.
- Add more of comment markup through Markup API.
- Add check for `HTTP_USER_AGENT` for feed redirection.
- Add `power_is_blog_template()` function.
- Add fresh install detection.
- Add grunt tasks.
- Add some unit tests.
- Add some new hooks documentation.

### Changed
- Improve SEO section title on user settings page.
- Improve term meta fields to only show for public taxonomy.
- Improve header widget area description to list appropriate widgets.
- Improve layout names.
- Improve appearance of inputs on settings pages.
- Improve style header tag fixed-width to responsive-layout.
- Improve (updated) `normalize.css` from 2.1.2 to 3.0.1.
- Improve design for wider screens, largest breakpoint now 1139px to 1200px.
- Improve favicon.
- Improve general design.
- Improve optimisation of images.
- Improve screenshot.
- Improve when `power_pre_get_option_-` filter hook fires.
- Improve SEO disabling by amending hooks.
- Improve hook names to use interpolation not concatenation.
- Improve author box to obey semantic headings setting.
- Improve how admin classes autoload scripts, styles and help content.
- Improve `power_get_image()` to accept `$post_id`.
- Improve `power_save_custom_field()` to formally deprecate `$post_id` argument.
- Improve `_power_update_settings()` to make it a public function.
- Improve nav menu registration.
- Improve term-meta callbacks to move them into a more suitable file.
- Improve variables in `power_custom_header()`.
- Improve style sheet documentation to use Markdown.
- Improve documentation for globals.

### Removed
- Remove filter for layout columns.
- Remove Primary Nav Extras (for fresh installs).
- Remove unnecessary title attributes.
- Remove Roboto Google Web Font.
- Remove styles for Gravity Forms.
- Remove styles for Power Latest Tweets.
- Remove rem units.
- Remove references to admin screen icons.
- Remove (deprecated) `power_doctitle_wrap()`.
- Remove `power_add_user_profile_fields()` function.
- Remove all uses of `extract()` function.
- Remove global $post in favour of functions where possible.
- Remove last var keyword.
- Remove dead code.

### Fixed
- Fix layout not selectable with IE11.
- Fix empty post titles in featured widgets.
- Fix location of Semantic Headings description.
- Fix SEO user option showing when SEO is disabled.
- Fix default layout for RTL.
- Fix formatting of CSS.
- Fix JavaScript code practices.
- Fix duplicate `.pot` file headers.
- Fix Language Team `.pot` value.
- Fix POEdit keyword list.
- Fix missing text domains.
- Fix `power_structural_wrap` filter hook.
- Fix title tags being added to all instances of `wp_title()`.
- Fix more tag on home page loop with Featured Page.
- Fix array to string conversion error from taxonomy meta data.
- Fix multiple calls to update API server.

## [2.0.2] - 2014-01-09
### Added
- Add Lauren Mancke to Contributor List.
- Add Google+ Publisher URL field.

### Changed
- Improve import button user interface consistency.
- Improve copyright shortcode by using non-breaking space between symbol and year.
- Improve pagination setting by using numeric as default.
- Improve search field to use value instead of placeholder when query is present.
- Improve SEO Settings user interface.
- Improve `rel=author` output to only target posts.
- Improve screenshot.
- Improve menu icon.

### Removed
- Remove Homepage Author field.

### Fixed
- Fix incorrect Power and child themes updates from WordPress.org.
- Fix radio button appearance in WordPress 3.8 admin.
- Fix metabox textarea widths.
- Fix hidden text box handles.
- Fix admin style references to MP6 plugin.
- Fix `power_human_time_diff()`.
- Fix assign by reference Strict Standards warning.
- Fix order of Contributors.

## [2.0.1] - 2013-08-21
_Requires WordPress 3.5.0._
### Changed
- Improve `power_get_cpt_archive_types_names()` to always return an array.
- Improve external resources by using relative protocol.
- Improve term meta field names.
- Improve files to consistently use Unix line-endings.

### Removed
- Remove type hint from sanitization filter.

### Fixed
- Fix `post_author_link` shortcode for XHTML themes.
- Fix empty document title for custom post type archive settings usage.
- Fix more tag on home page loop with Featured Post.
- Fix Leave a Comment link when no comments are present.

## 2.0.0 - 2013-08-07
### Added
- Add semantic HTML5 elements across all output.
- Add attributes markup functions `power_attr()` and `power_parse_attr()`, allowing key elements to have their attributes filtered in.
- Add default microdata that covers itemtypes of WebPage, Blog, SearchResultsPage, WPHeader, WPSideBar, WPFooter, SiteNavigationElement, CreativeWork, BlogPosting, UserComments, and Person, and their corresponding properties.
- Add role attributes to assist with accessibility.
- Add more classes for pagination elements.
- Add HTML5-specific hooks that better match the new semantic structure and be post type agnostic.
- Add HTML5 shiv for Internet Explorer 8 and below.
- Add archive settings for custom post types that are (filterable conditions) public, show a UI, show a menu, have an archive, and support `power-cpt-archives-settings`.
- Add contextual help to settings pages, allowing better explanation of settings, and potentially reducing some visual distractions amongst the settings.
- Add distinct admin menu icon, instead of using default favicon.
- Add an unsaved settings alert, when the user is about to navigate away from a settings page after changing a value but not yet saved.
- Add semantic headings setting for using multiple h1 elements on a page.
- Add permalink on posts with no title.
- Add recognition of SEO Ultimate plugin, to enable Power SEO to automatically disable.
- Add iframe to CSS to cover responsive video.
- Add new clearfix method for block elements.
- Add `rtl.css` file to automatically display sites set-up as right-to-left language better, and gives theme authors a good starting point.
- Add updated screenshot.
- Add JSLint Closure Compiler instructions to Superfish args non-minified file.
- Add minified JavaScript (`-.min.js`) files that are used by default, unless `SCRIPT_DEBUG` is true.
- Add minified admin style sheet (`-.min.css`) files that are used by default, unless `SCRIPT_DEBUG` is true.
- Add early registration of Superfish files.
- Add header logo files.
- Add `absint` and `safe_html` new settings sanitization types.
- Add sanitization for custom body and post classes.
- Add filter to disable loading of deprecated functions file.
- Add filter to Superfish args URL.
- Add filter to initial layouts.
- Add filters to structural wraps – attributes and output.
- Add ability to wrap markup around output of `power_custom_field()`.
- Add two new breadcrumb-related filters, `power_build_crumbs` and `power_breadcrumb_link`.
- Add `$args` to sidebar defaults filter.
- Add `$footer_widgets` to `power_footer_widget_areas` filter.
- Add context arg in `power_get_image()` to allow for more control when filtering output.
- Add fallback arg in `power_get_image()` to decide what thumbnail to show if a featured image is not set.
- Add array type hints where possible. Methods with the same name in classes extended from WP can't have them, not can methods which accept array or strings arguments.
- Add global displayed IDs variable to track which posts are being shown across any loop.
- Add setting to Featured Post widget to exclude already displayed posts.
- Add third parameter to `shortcode_atts()` to utilize new WordPress 3.6 filter.
- Add network-wide update, to eliminate the need to visit each site to trigger database changes.
- Add blank line at the end of each file for cleaner files and diffs.
- Add some preparatory functions for Theme Customizer (full support not until at least Power 2.1)
- Add archive description box markup around search result page heading for consistency.
- Add common class for all archive description boxes.
- Add common class for both Featured widgets.
- Add `widget-title` class next to `widgettitle`.
- Add `lib/functions/breadcrumb.php` for breadcrumb-related functions.

### Changed
- Improve in-post scripts box by moving it to its own box, that won't be hidden when an SEO plugin is active.
- Improve feedback for navigation settings.
- Improve What's New page with new content, and random order of contributors.
- Improve admin styles to work better with MP6 plugin.
- Improve wording for email notification setting.
- Improve labels containing URI to use URL instead.
- Improve widget areas by only showing default content to those who can edit widgets.
- Improve organization of style sheet into a more logical grouping.
- Improve reset styles by switching to `normalize.css`.
- Improve selectors by removing all use of ID selectors in `style.css`, down from 107 in Power Framework 1.9.2.
- Improve development speed, by switching to 62.5% (10px) default font-size.
- Improve Google Web Fonts usage by using a protocol-less URL.
- Improve Featured Page and Featured Post widgets to utilize the global `$wp_query` so that `is_main_query()` works correctly against it.
- Improve code that toggles display of extra settings, to allow extra settings to be shown when checkbox is not checked.
- Improve inline settings for Closure Compiler so it uses the latest jQuery externs file (1.8).
- Improve Superfish by updating to the latest version (1.7.4) that supports the version of jQuery that ships with WP 3.6, and has touch event support. Includes back-compat file for arrows support.
- Improve support for languages with multibyte characters by replacing both instances of `substr()` with `mb_substr()`.
- Improve widgets by calling `parent::__construct()` directly when registering widgets.
- Improve output from `get_terms()` by making Power term metadata available.
- Improve `power_do_noposts()` to be post type agnostic.
- Improve `power_do_noposts()` to use consistent entry markup.
- Improve admin metabox abstraction so that it hooks in the previously hard-coded metabox container markup.
- Improve import feature to only import Power-related settings.
- Improve multi-page navigation code, by moving it out of post content function into its own hooked in function.
- Improve menus by not showing empty markup if there are no menu items.
- Improve unpaged comment navigation by not showing empty markup.
- Improve filtering of terms, by doing nothing if term variable is not an object.
- Improve `power_get_custom_field()` by allowing custom fields to return as arrays.
- Improve checkbox inputs to utilize WP admin styling, by wrapping label element around them.
- Improve the organization of the `lib/structure/header.php` file.
- Improve JavaScript classes, by adding `js-` prefix to them.
- Improve breadcrumbs class to refactor large methods into several smaller ones.
- Improve default sidebar contents by refactoring it into a single re-usable function.
- Improve `power_search_form()` escaping and logic.
- Improve check for presence of Header Right sidebar before displaying markup.
- Improve internationalization so that menu location names are translatable, by moving loading of text domain earlier.
- Improve internationalization by simplifying strings.
- Improve README file by changing it from a `.txt` to `.md` file.
- Improve single line comment format to be consistent, allowing easier block-commenting around and from the single line comment.
- Improve overall code by using identity comparisons wherever suitable.
- Improve inline documentation throughout.

### Removed
- Remove display of `entry-footer` for everything except posts.
- Remove loading of Superfish script by default. Can be added back by filtering `power_superfish_enabled` to be true, or use Power Fancy Dropdowns.
- Remove Microformat classes such as hentry.
- Remove global `$loop_counter` since `$wp_query->current_post` does the same job.
- Remove back to top text.
- Remove custom comment form arguments, resulting in default "Leave your Reply" and "You may use these HTML tags and attributes…" showing.
- Remove Fancy Dropdown settings for each menu in favour of more explicit Load Superfish Script setting.
- Remove the now empty Secondary Navigation settings, and which just leaves Primary Navigation Extras.
- Remove Theme Information setting, since parent and child theme information is publicly available in the style sheets.
- Remove child theme README admin menu item.
- Remove RSS and Twitter images.
- Remove device-specific subheadings.
- Remove support for five-column layout.
- Remove previously deprecated eNews widget. Use Power eNews Extended plugin as an enhanced replacement.
- Remove previously deprecated Latest Tweets widget. Use Power Latest Tweets plugin, or official Twitter widget.
- Remove ternary part of `power.confirm()` JavaScript function.
- Remove (deprecated) `power_show_theme_info_in_head()`.
- Remove (deprecated) `power_theme_files_to_edit()`.
- Remove (deprecated) `g_ent()`.
- Remove (deprecated) `power_tweet_linkify()`.
- Remove (deprecated) `power_custom_header_admin_style()`.
- Remove (deprecated) `power_older_newer_posts_nav()`.
- Remove `POWER_LANGUAGES_URL` constant.
- Remove redundant calls and globals from various functions.
- Remove redundant escaping on in-post meta boxes save.
- Remove post templates functionality. Use Single Post Template plugin as a replacement.
- Remove all remaining style attributes.
- Remove all but two of the remaining inline event handlers (on- attributes). Only `onfocus` and `onblur` remain on the XHTML search form in lieu of no placeholder attribute support.
- Remove closing element HTML comments.
- Remove empty files and a directory.
- Remove the Older Posts / Newer Posts archive pagination format in favour of existing Next Page / Previous Page.

### Fixed
- Fix mis-alignment of settings page boxes.
- Fix inconsistent term meta user interface.
- Fix Closure Compiler output file name for `admin.js`.
- Fix `wp_footer()` so it fires right before `</body>`, now after `power_after` hook.
- Fix duplicate IDs on top and bottom submit and reset admin buttons.
- Fix invalid HTML output in user profile widget.
- Fix duplicate calls to `power_no_comments_text` filter.
- Fix structural wrap function so support for them can be removed completely.
- Fix incorrectly linked label on noarchive post setting.
- Fix out-of-date Theme and SEO Settings defaults and sanitising.
- Fix redundant parameter in `power_save_custom_fields()`.
- Fix breadcrumb issue for date archives.

## 1.9.2 - 2013-04-10
### Fixed
- Fix potential notice when saving post custom fields.
- Fix potential security issue in the search form (props Sucuri Security team and Alun Jones).
- Fix duplicate ID attributes on admin save and reset buttons.
- Fix notice when trying to filter a term that is not an object.
- Fix missing class on layout selector default radio input.
- Fix distorted images in IE8.

## 1.9.1 - 2013-01-08
### Fixed
- Fix loading of child theme main style sheet, so it is referenced before any other extra child theme style sheets.

## 1.9.0 - 2013-01-07
### Added
- Add `.entry` class to all content, in preparation for the potential absence of `.hentry` in a HTML5-flavoured Power that prefers Microdata over Microformats.
- Add filter for term meta defaults.
- Add comment header wrapping div.
- Add ability to disable the loading of all breadcrumb features.
- Add `archive-title` class to archive titles.
- Add fallback parameter to `power_get_image()`.
- Add a What's New page.
- Add front page and posts page breadcrumb settings.
- Add search result page title.
- Add menu highlight class.
- Add link to download Power for Beginners to readme.
- Add support for `rel="author"` link tag, allowing author highlighting on Google result pages.

### Changed
- Improve `power_site_layout()` by allowing cache to be bypassed.
- Improve custom field saving function.
- Improve how Power / child theme style sheet is referenced, by enqueueing it.
- Improve post title output, adding a filter to decide if it should be linked to the single post on archive pages (default is true, as currently).
- Improve user meta fields integration by limiting to admin back-end only.
- Improve method to check to see if Scribe is installed.
- Improve breadcrumb class for PHP 5.
- Improve comment template by only loading it when needed.
- Improve wording on SEO Settings page, including Scribe marketing notice.
- Improve theme settings page by hiding update options when automatic updates are programatically disabled.
- Improve organization of CSS.
- Improve overall base design:
    - Increased maximum width, 1152px.
    - Different font.
    - Default styles for HTML5 elements.
    - Fluid-width columns.
    - Use of rem units with pixel fallback.
- Improve usage of proper defaults in eNews widget.
- Improve License description by changing from "GPL v2.0 (or later)" to "GPL-2.0+" as per SPDX open source license registry.
- Improve default document title separator from being a hyphen-minus character to an em-dash.
- Improve `.pot` file.

### Removed
- Remove `i18n.php` and moved textdomain load to `init.php`.
- Remove legacy customer header code.
- Remove on / off setting for primary and secondary menus in favour of theme nav menu locations to determine visibility.
- Remove settings for eNews widget (consider it deprecated).
- Remove settings for Latest Tweets widget (consider it deprecated).

### Fixed
- Fix call to `power_site_layout()` resetting the query.
- Fix the custom header body class conditional for WP 3.4.
- Fix warnings when saving posts.
- Fix footer scripts setting having incorrect ID.
- Fix extra quote in Author Box setting markup.
- Fix empty post image link, when there is no post image.
- Fix empty featured post / page widget image link, when there is no image to display.
- Fix use of path constants in post-templates to use functions instead.
- Fix comments template loading on custom post type single posts, if it supports comments.
- Fix post class field not saving.
- Fix inconsistency with comments and trackback edit links.
- Fix robots meta tag help links to point to articles by Yoast.
- Fix dropdown size issue in widget forms.
- Fix trackback URL output showing when post type does not support trackbacks.
- Fix post meta section showing for pages in search results page.
- Fix grid loop problems.
- Fix spacing between bottom buttons on settings pages.

### Security
- Improve sanitization on some settings inputs.
- Improve search form security by escaping input and button text outside of filter – you should remove any `esc_attr()` calls in functions that filter these strings and just return plain text.
- Add a new sanitization filter, `url`.
- Add escaping to names and dimensions of image sizes used in image size dropdowns.

## 1.8.2 - 2012-06-20
_Requires WordPress 3.3.0._
### Changed
- Improve user interface by removing Header setting box if WP native custom-header has theme support.

### Fixed
- Fix term meta data from being deleted when quick editing a term.
- Fix warning when showing theme info in the head.
- Fix warnings in theme editor by no longer hiding Power Framework files.
- Fix warnings related to custom header by supporting native functionality if WordPress ≥ 3.4.

## 1.8.1 - 2012-04-30
### Security
- This was a security release. Details of what was actually fixed will be revealed when users have had chance to update their Power installs (recommended immediately).

## 1.8.0 - 2012-01-20
### Added
- Add new color scheme / style metabox on Theme Settings page which child themes can use instead of building their own.
- Add setting to enable / disable breadcrumbs on attachment pages.
- Add Power features to post and page editors via post type support, instead of hard-coding – you can now disable the inpost metaboxes by removing post type support with a single line of code.
- Add separate custom title and description on term archives (displayed content defaults to existing title and description if not customized further).
- Add vendor-prefixed border-radius properties.
- Add posts-link class to user profile widget to accompany the now deprecated `posts_link` class.
- Add extended page link text setting for the user profile widget. No longer hard-coded as `[Read more…]`.
- Add warning to Power Page and Category Menu widget descriptions, to gently deprecate them (use WP Custom Menu widget instead).
- Add `Power_Admin` classes – a set of 1+3 abstract classes from which all Power admin pages now extend from.
- Add `power_is_menu_page()` helper function to check we're targeting a specific admin page.
- Add new `power_widget_area()` helper function for use in child themes.
- Add `author` value to `rel` attribute for author link shortcode functions.
- Add argument to `power_get_option()` and others to not use the Power cache.
- Add ability to make nav menu support conditional.
- Add search form label filter, so themes can add a visual label in if they wish.
- Add filter to disable edit post / page link.
- Add filter to Content Archives display types.
- Add filter to the options sent to `wp_remote_post()` when doing an update check.
- Add filter on custom header defaults.
- Add filters for term meta.
- Add filters for previous and next links text.
- Add `power_formatting_kses()` to be used as a filter function.
- Add crop parameter to return value of `power_get_image_sizes()`.
- Add a complete overhaul of DocBlock documentation at the page-, class-, method- and function-level. See an example of the generated documentation for Power 1.8.0. Comment lines now make up over 40% of all lines of code in Power 1.8.0, up from 30% in Power 1.6, with a significant amount of non-comment code having been added in the meantime as well.

### Changed
- Improve admin labels by reducing conspicuousness (basically, removing "Power" from several headings also displayed on wordpress.com installs).
- Improve image dimensions dropdown to use correct multiplication character, not the letter x.
- Improve label relationships with the `for` attribute to make them explicitly linked as per accessibility best practices.
- Improve top buttons to work better with non-English languages.
- Improve metabox order on Theme Settings page.
- Improve specific case CSS for input buttons with more generic selectors.
- Improve styles for new default Power appearance, including responsive design.
- Improve classes used for menus to be more consistent with WP, and allow simpler selectors.
- Improve eNews widget to now pass WP locale to Feedburner, instead of hard-coded `en_US`.
- Improve "Header Right" widget area to display as "Header Left" if right-to-left language is used.
- Improve the image alignment option "None" by giving it a value of alignnone in featured post and page widgets.
- Improve user profile author dropdown to only show actual authors, not all users.
- Improve `admin.js` with a complete rewrite to separate functions from events, make functions re-usable under power namespace, switch to using `on()` method for jQuery 1.7.1 and ensure all event bindings are namespaced.
- Improve ability to amend togglable settings by moving the config to PHP where they can be more easily filtered, before sending to JavaScript.
- Improve admin scripts to only appear on the appropriate admin pages.
- Improve submit button markup by using `submit_button()` instead of hard-coding it.
- Improve structural wrap usage.
- Improve `power_layout_selector()` by allowing layout options to be shown by type.
- Improve code quality by refactoring widget defaults into the constructor to avoid duplication.
- Improve some functions to return earlier if conditions aren't correct.
- Improve `power_strip_attr()` to accept a string for the elements arguments.
- Improve featured post widget performance by sanitizing byline with KSES on save, not output.
- Improve taxonomy term performance by sanitizing description on save, not output.
- Improve `comment_form()` by passing filterable comment form args.
- Improve `power_admin_redirect()` by eliminating multiple calls to `add_query_arg()`.
- Improve order of the notice checks to avoid the reset notice still showing after saving settings.
- Improve `power_custom_loop()` by refactoring it to use `power_standard_loop()`.
- Improve updates procedure by ensuring a fresh request for database options at each incremental stage.
- Improve notice to actually check if settings save was actually sucessfull or not.
- Improve custom post type (custom post type) archive breadcrumb by only linking if custom post type has an archive.
- Improve post date title attribute for hEntry by using HTML5-compatible format.
- Improve `_power_update_settings()` by moving it to the correct file.
- Improve code organization by moving general sanitization functions to the sanitization file from theme settings file.
- Improve code organization by moving per-page sanitization code to the related admin page class.
- Improve theme screenshot.
- Improve favicon.
- Improve default footer wording credits.
- Improve readme content with Header Right info.
- Improve `.pot` file with additional and corrected headers and updated to 381 strings in total.
- Improve documentation by moving warning message in top-level files to outside of docblocks so they don't count as short descriptions.
- Improve code so it is now written to WordPress Code Standards, programatically testable via WordPress Code Sniffs.
- Improve translation of strings by extracting `<code>` bits to simplify them and reduce the number of unique strings to translate.

### Removed
- Remove settings form from Power Page and Category Menu widgets, to further deprecate them.
- Remove now-deprecated functions from `lib/functions/admin.php` and deprecated file.
- Remove duplicated custom post class handling code.
- Remove (deprecated) `power_filter_attachment_image_attributes()` function as WP has since improved.
- Remove `power_load_styles()` as it was an empty function that was never used.
- Remove remaining PHP4-compatible class constructor names in favour of `__construct()`.
- Remove unnecessary check for WordPress SEO plugin to re-enable title and description output on term archive pages when WordPress SEO is active.
- Remove SEO options that remove some of the relationship link tags from the head. See [\[18680\]](http://core.trac.wordpress.org/changeset/18680) for more info.

### Fixed
- Fix appearance of layout selector for IE8 users.
- Fix issue with incorrect CSS being output for custom header text color.
- Fix issue with new WP install default widgets appearing in Header Right widget area when switching themes.
- Fix escaping of some values in theme settings.
- Fix rare `add_query_arg()` bug by not passing it an encoded URL.
- Fix issue with duplicate canonical tags in the head when an SEO plugin is active.
- Fix missing second and third parameters when applying the `widget_title filter`.
- Fix empty anchor in `post_author_posts_link` shortcode function.
- Fix clash with grid loop features and features taxonomy (as in AgentPress Listings plugin).
- Fix variable name under which JavaScript strings are localized, from `power` to `powerL10n` to be consistent with WordPress practices.
- Fix license compatibility for child themes by changing license from "GPLv2" to "GPLv2 (or later)".
- Fix missing text-domain for footer widget area description, post author link shortcode, and user profile widget.
- Fix the Scribe notice to be translatable.

## 1.7.1 - 2011-07-18
_Requires WordPress 3.2.0._
### Added
- Add new conditionals to feed filter to ensure compatibility with other code that amend the feed link.

### Changed
- Improve CSS for new default look.

### Fixed
- Fix bug with `__power_return_content_sidebar` returning the wrong value.
- Fix tweet text escaping not working as intended, so reverted.

## 1.7.0 - 2011-07-06
### Added
- Add `power_human_time_diff()` to use on relative post dates, as a replacement for poor WP function.
- Add `power_canonical` filter.
- Add version number to `admin.js` to bust cache when updating Power.
- Add database version string to theme info stored in the database.
- Add private function to update database settings more easily.
- Add ability to return array values from database via `power_get_option()`.
- Add structural wrap fallback for child themes that do not load `init.php`.
- Add structural wrap support for sidebars.
- Add new layout images and visual selector feature.
- Add link to support forums on Theme Settings page.
- Add `.gallery-caption` and `.bypostauthor` classes (empty) to meet Theme Review guidelines.
- Add updated `.pot` file, now with 385 strings in total.
- Add class and method-level documentation for widget classes.

### Changed
- Improve settings page user interface to match new user interface for WordPress 3.2.
- Improve settings pages to be a single column.
- Improve organization of settings by combining some settings into other meta boxes, removing other meta boxes and conditionally hiding some depending on theme support for features.
- Improve user interface on User Profile page by amending widths of input and textarea fields.
- Improve wording on all admin pages to be clearer.
- Improve wording in notices, and to use WordPress wording where possible.
- Improve naming of layout choices.
- Improve capability check for Power pages by changing from `manage_options` to `edit_theme_options`.
- Improve old hook functions by formally deprecating them.
- Improve init to use WordPress function `require_if_theme_supports()` instead of using Power conditional.
- Improve widget organization and registration.
- Improve breadcrumbs to remove entry crumbs – allows Home crumb and separator to be remove, for instance.
- Improve README to be formatted for viewing inside WP Dashboard.
- Improve code standards by correcting whitespace and formatting issues in CSS.
- Improve code standards by correcting some whitespace issues in PHP.
- Improve styles for:
    - defaults
    - body
    - header
    - title
    - description
    - menus (including superfish)
    - breadcrumbs
    - headings (all levels)
    - blockquotes
    - inputs
    - ordered lists
    - list items
    - captions
    - taxonomy descriptions
    - images
    - post icons
    - featured images
    - sticky
    - avatars
    - post navigation
    - comments
    - subscribe-to-comments
    - sidebars
    - widgets

### Removed
- Remove "NOTE:" prefix for settings descriptions.
- Remove Header Right theme setting – sidebar now always registered but only shown if it contains a widget.
- Remove `strip_tags()` call on page title in breadcrumbs.
- Remove existing meta box order settings from the database.
- Remove `lib/functions/hooks.php` file as all contents have been moved to `lib/functions/deprecated.php`.

### Fixed
- Fix issue with menu separator having a class.
- Fix issues with post info and post meta not showing up on custom pages.
- Fix issue with feed redirection being too inclusive and breaking other plugins.
- Fix breadcrumb issue which stopped breadcrumbs from being turned off on blog pages for sites with a static front page.
- Fix Power to use `power_formatting_allowedtags()` instead of the global `$_power_formatting_allowedtags`.
- Fix load superfish script if custom menu widget is active.
- Fix Nav Extra posts feed to use RSS2 instead of RSS.
- Fix issue with toggle checkboxes in page / category widget checklist.
- Fix wording in latest tweets, categories menu, pages menu and user profile widgets to be translatable.
- Fix "Theme URL" to be "Theme URI".

### Security
- Security Audit by Mark Jaquith.
- Fix wrong escaping on comment permalink.
- Improve performance and security by sanitizing widget option values on save, instead of on display.
- Add a capability check before displaying Header and Footer scripts meta box.
- Add complete new settings sanitization class and API, aimed at core, extendable to child themes.

## 1.6.1 - 2011-05-02
_Requires WordPress 3.1.0._
### Fixed
- Fix robots meta not outputting unless all meta tags were sent.
- Fix minor CSS issues.

## 1.6.0 - 2011-04-26
### Added
- Add select / deselect all checkbox switch to category menu widget.
- Add plugin detection function.
- Add an edit link to breadcrumbs of all term archive pages.
- Add filter for text shown when comment is awaiting moderation.
- Add filter to sidebar registration defaults.
- Add filters to `power_do_nav()` and `power_do_subnav()`.
- Add filters for post navigation text.
- Add custom header functionality. Can now be enabled via a single line of code in a child theme.
- Add footer widgets functionality. Can now be enabled via a single line of code in a child theme.
- Add trailing slash to breadcrumb home link.
- Add content width filter for variable layouts.
- Add option to show features on page 2+ with the grid loop.
- Add relative time option to the post date shortcode options – `[post_date format="relative"]`.
- Add inline documentation in multiple files to some locations where it was missing (ongoing – remaining to be done post-1.6 release).
- Add conditional structural wrap system.
- Add `sidebar` class to primary and secondary sidebar divs.
- Add `widget-area` class to widget areas in footer widgets.

### Changed
- Improve Export to use checkboxes instead of dropdown for export options – now filterable to allow themes and plugins to hook in.
- Improve Theme Settings user interface by decluttering and toggling secondary options via JavaScript.
- Improve breadcrumbs settings – now off by default.
- Improve admin pages document title to ensure default is shown.
- Improve headline and intro text fields (taxonomy and user) by moving to their own function so they do not get unhooked when an SEO plugin is active.
- Improve image size dropdown in Theme Settings by making it use `power_get_image_sizes()`.
- Improve footer credit wording.
- Improve code to use available WP functions – `is_child_theme()`, `menu_page_url()` and more.
- Improve `init.php` content by putting into hooked functions.
- Improve theme speed by loading admin files on admin pages only.
- Improve the post format image function to harden it.
- Improve `power_get_custom_field()` to use $id if available.
- Improve data sent when doing an update check.
- Improve check for third party SEO plugins by using plugin detection function.
- Improve admin styles by moving most inline styles from widgets and admin pages to `admin.css`.
- Improve Power `style.css` to new header standard for giving an explicit license.

### Removed
- Remove Power Menu options. Existing Power menus still supported, but amendments will need to be done by creating and using a WordPress Custom Menu.
- Remove XML demo file from Power – kept in with Sample Child Theme.
- Remove (deprecated) `power_ie8_js()`.
- Remove (to be formally deprecated next version) the hook functions, in favour of direct `do_action()` calls.
- Remove rogue `li` tag from category menu widget.
- Remove WordPress 3.0 compatibility checks in breadcrumb class.
- Remove redundant use of sidebar IDs in `style.css`.
- Remove admin CSS related to purchase themes menu.

### Fixed
- Fix typo on Import / Export page.
- Fix two bugs in `power_admin_redirect()`.
- Fix SEO Settings reset action.
- Fix bug with new installs not pushing all the default SEO settings.
- Fix empty site description outputting redudant markup.
- Fix issue with SEO plugin compatibility.
- Fix notice on categories menu widget.
- Fix footer markup typo.
- Fix bug in title output of featured post / page widgets.
- Fix issue with filter in `power_custom_header()` not returning an appropriate value, causing conflicts.
- Fix inline documentation in multiple files – moved docblocks directly above functions so they are correctly associated.
- Fix a lot of code that was inconsistent with coding standards, including whitespace (ongoing).
- Fix list styles on archive pages.
- Fix `sub-sub-menu` issue on non-superfish dropdowns.
- Fix CSS conflict with admin bar.



First public release.
