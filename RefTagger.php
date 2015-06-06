<?php

/**
 * Plugin Name: RefTagger
 * Plugin URI:  http://reftagger.com
 * Description: Transform Bible references into links to the full text of the verse.
 * Author:      Logos Bible Software, Logos, Brandon Allen
 * Author URI:  http://www.logos.com/
 * Version:     2.1.0
 * Text Domain: reftagger
 * Domain Path: /languages
 * License:     The MIT License (LICENSE)
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Output the current plugin version.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @return int Current database version
 */
function lbs_get_db_version() {
	return 11;
}

/**
 * Output RefTagger JS in wp_footer.
 *
 * Here we pull all of our relevant options, then sanitize the output. Then we
 * use json_encode to give one last sanitization for use inside the script tag.
 *
 * @since 1.0.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses json_encode()
 * @uses lbs_sanitize_bible_reader()
 * @uses lbs_sanitize_bible_version()
 * @uses lbs_sanitize_style_options()
 * @uses lbs_sanitize_libronix_color()
 * @uses wp_parse_args()
 * @uses lbs_sanitize_exclude_content()
 * @uses lbs_default_sharing_args()
 * @uses lbs_sanitize_social_sharing()
 * @uses lbs_sanitize_exclude_content()
 * @uses lbs_sanitize_exclude_content()
 *
 * @return void
 */
function lbsFooter() {

	// Add Logos link
	$add_logos_link = json_encode( (bool) get_option( 'lbs_libronix', false ) );

	// Append icon to lib links
	$append_icon = json_encode( (bool) get_option( 'lbs_existing_libronix', false ) );

	// Online Bible reader
	$bible_reader = get_option( 'lbs_bible_reader', 'biblia' );
	$bible_reader = lbs_sanitize_bible_reader( $bible_reader, 'js' );

	// Bible version
	$bible_version_raw = get_option( 'lbs_bible_version' );
	$bible_version     = lbs_sanitize_bible_version( $bible_version_raw, 'js' );

	// Case insensitive
	$case_insensitive = (bool) get_option( 'lbs_case_insensitive', false );
	$case_insensitive = json_encode( $case_insensitive );

	// Convert hyperlinks
	$convert_hyperlinks = (bool) get_option( 'lbs_convert_hyperlinks', false );
	$convert_hyperlinks = json_encode( $convert_hyperlinks );

	// Custom style (body)
	$cs_body = get_option( 'lbs_body_style' );
	$cs_body = lbs_sanitize_style_options( $cs_body, 'js' );

	// Custom style (heading)
	$cs_heading = get_option( 'lbs_heading_style' );
	$cs_heading = lbs_sanitize_style_options( $cs_heading, 'js' );

	// Drop shadow
	$drop_shadow = json_encode( (bool) get_option( 'lbs_drop_shadow', true ) );

	// Links open new window
	$new_window = json_encode( (bool) get_option( 'lbs_new_window', false ) );

	// Logos link icon
	$link_color = false;
	if ( (bool) get_option( 'lbs_libronix', false ) ) {
		$link_color = lbs_sanitize_libronix_color( get_option( 'lbs_libronix_color' ) );
	}
	$link_color = json_encode( $link_color );

	// Build comment search list to append to the exclude classes array
	$search_comments = (bool) get_option( 'lbs_search_comments', true );
	$comment_classes = array();
	if ( ! $search_comments ) {
		$comment_classes = array( 'commentList', 'commentlist', 'comment-list' );
	}

	// Add 'no-reftagger' shortcode class
	$shortcode_classes = array( 'no-reftagger' );

	// Merge hidden classes
	$hidden_classes = wp_parse_args( $exclude_classes, $shortcode_classes );

	// Exclude CSS classes
	$exclude_classes = (array) get_option( 'lbs_exclude_classes' );
	$exclude_classes = wp_parse_args( $exclude_classes, $hidden_classes );
	$exclude_classes = lbs_sanitize_exclude_content( $exclude_classes, 'js' );

	// Exclude HTML tags
	$exclude_tags = (array) get_option( 'lbs_exclude_tags', array( 'h1', 'h2', 'h3' ) );
	$exclude_tags = lbs_sanitize_exclude_content( $exclude_tags, 'js' );

	// Rounded corners
	$rounded_corners = json_encode( (bool) get_option( 'lbs_rounded_corners', false ) );

	// Social sharing
	$social_sharing = get_option( 'lbs_social_sharing', lbs_default_sharing_args() );
	$social_sharing = lbs_sanitize_social_sharing( $social_sharing, 'js' );

	// Tag chapters
	$tag_chapters = json_encode( (bool) get_option( 'lbs_tag_chapters', false ) );

	// Use tooltip
	$use_tooltip = json_encode( (bool) get_option( 'lbs_tooltips', true ) );

	// Search for Spanish language references
	$_bible_version = lbs_sanitize_bible_version( $bible_version_raw );
	$is_spanish = in_array( $_bible_version, array( 'LBLA95', 'NBLH', 'NVI', 'RVA', 'RVR60', ) );
	$spanish = $is_spanish ? '.es' : '';
?>

	<!-- Begin RefTagger -->
	<script>
		var refTagger = {
			settings: {
				addLogosLink: <?php echo $add_logos_link; ?>,
				appendIconToLibLinks: <?php echo $append_icon; ?>,
				bibleReader: <?php echo $bible_reader; ?>,
				bibleVersion: <?php echo $bible_version; ?>,
				caseInsensitive: <?php echo $case_insensitive; ?>,
				convertHyperlinks: <?php echo $convert_hyperlinks; ?>,
				customStyle: {
					body: {
						backgroundColor: <?php echo $cs_body['background_color']; ?>,
						color: <?php echo $cs_body['font_color']; ?>,
						fontFamily: <?php echo $cs_body['font_family']['family']; ?>,
						fontSize: <?php echo $cs_body['font_size']; ?>
					},
					heading: {
						backgroundColor: <?php echo $cs_heading['background_color']; ?>,
						color: <?php echo $cs_heading['font_color']; ?>,
						fontFamily: <?php echo $cs_heading['font_family']['family']; ?>,
						fontSize: <?php echo $cs_heading['font_size']; ?>
					}
				},
				dropShadow: <?php echo $drop_shadow; ?>,
				linksOpenNewWindow: <?php echo $new_window; ?>,
				logosLinkIcon: <?php echo $link_color; ?>,
				noSearchClassNames: <?php echo $exclude_classes; ?>,
				noSearchTagNames: <?php echo $exclude_tags; ?>,
				roundedCorners: <?php echo $rounded_corners; ?>,
				socialSharing: <?php echo $social_sharing; ?>,
				tagChapters: <?php echo $tag_chapters; ?>,
				useTooltip: <?php echo $use_tooltip; ?>
			}
		};

		(function(d, t) {
			var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
			g.src = '//api.reftagger.com/v2/RefTagger<?php echo $spanish; ?>.js';
			s.parentNode.insertBefore(g, s);
		}(document, 'script'));
	</script>
	<!-- End RefTagger -->

<?php
}

/* OPTIONS *******************************************************************/

/**
 * Add our default options on activation.
 *
 * @since 1.0.0
 *
 * @access public
 *
 * @uses add_option()
 *
 * @return void
 */
function lbs_set_options() {
	add_option( 'lbs_bible_reader', 'biblia' );
	add_option( 'lbs_bible_version', 'ESV' );
	add_option( 'lbs_body_style', array() );
	add_option( 'lbs_case_insensitive', '1' );
	add_option( 'lbs_convert_hyperlinks', '0' );
	add_option( 'lbs_drop_shadow', '1' );
	add_option( 'lbs_exclude_classes', array() );
	add_option( 'lbs_exclude_tags', array( 'h1', 'h2', 'h3' ) );
	add_option( 'lbs_heading_style', array() );
	add_option( 'lbs_libronix', '0' );
	add_option( 'lbs_libronix_color', 'light' );
	add_option( 'lbs_new_window', '1' );
	add_option( 'lbs_rounded_corners', '0' );
	add_option( 'lbs_search_comments', '1' );
	add_option( 'lbs_social_sharing', lbs_default_sharing_args() );
	add_option( 'lbs_tag_chapters', '0' );
	add_option( 'lbs_tooltips', '1' );
}

/**
 * Remove our default options on uninstallation.
 *
 * @since 1.0.0
 *
 * @access public
 *
 * @uses delete_option()
 *
 * @return void
 */
function lbs_unset_options() {
	delete_option( 'lbs_bible_reader' );
	delete_option( 'lbs_bible_version' );
	delete_option( 'lbs_body_style' );
	delete_option( 'lbs_case_insensitive' );
	delete_option( 'lbs_convert_hyperlinks' );
	delete_option( 'lbs_drop_shadow' );
	delete_option( 'lbs_exclude_classes' );
	delete_option( 'lbs_exclude_tags' );
	delete_option( 'lbs_existing_libronix' );
	delete_option( 'lbs_heading_style' );
	delete_option( 'lbs_libronix' );
	delete_option( 'lbs_libronix_bible_version' );
	delete_option( 'lbs_libronix_color' );
	delete_option( 'lbs_new_window' );
	delete_option( 'lbs_nosearch' );
	delete_option( 'lbs_plugin_version' );
	delete_option( 'lbs_rounded_corners' );
	delete_option( 'lbs_search_comments' );
	delete_option( 'lbs_social_sharing' );
	delete_option( 'lbs_tag_chapters' );
	delete_option( 'lbs_tooltips' );
}

/* SANITIZATION **************************************************************/

/**
 * Sanitize the bible reader.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $bible (default: 'biblia')
 * @param string $context (default: '')
 *
 * @return string
 */
function lbs_sanitize_bible_reader( $bible = 'biblia', $context = '' ) {

	// We only have two options. Default to 'biblia'
	if ( ! in_array( $bible, array( 'biblia', 'bible.faithlife' ) ) ) {
		$bible = 'biblia';
	}

	if ( 'js' === $context ) {
		$bible = json_encode( $bible );
	}

	return $bible;
}

/**
 * Sanitize the bible version.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $bible
 * @param string $context
 * @uses lbs_default_bibles()
 *
 * @return string $bible
 */
function lbs_sanitize_bible_version( $bible = 'ESV', $context = '' ) {

	$bibles = lbs_default_bibles();

	if ( 'TNIV' === $bible ) {
		$bible = 'NIV';
	}

	if ( ! in_array( $bible, $bibles ) ) {
		$bible = 'ESV';
	}

	if ( 'js' === $context ) {
		$bible = json_encode( $bible );
	}

	return $bible;
}

/**
 * Sanitize body/heading style options.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param array $args (default: array())
 * @param string $context (default: 'db')
 * @uses wp_parse_args()
 * @uses lbs_sanitize_css_color()
 * @uses lbs_sanitize_font_family()
 * @uses lbs_sanitize_font_size()
 *
 * @return array
 */
function lbs_sanitize_style_options( $args = array(), $context = 'db' ) {

	$defaults = array(
		'background_color' => false,
		'font_color'       => false,
		'font_family'      => false,
		'font_size'        => false,
	);

	$r = wp_parse_args( $args, $defaults );

	foreach ( $r as $key => $value ) {
		// Sanitize CSS colors
		if ( in_array( $key, array( 'background_color', 'font_color' ) ) ) {
			$r[ $key ] = lbs_sanitize_css_color( $value, $context );
		}

		// Sanitize the font-family
		if ( 'font_family' === $key ) {
			$r['font_family'] = lbs_sanitize_font_family( $r['font_family'], $context );
		}

		// Sanitize the font-size
		if ( 'font_size' === $key ) {
			$r['font_size'] = lbs_sanitize_font_size( $r['font_size'], $context );
		}
	}

	return $r;
}

/**
 * Sanitize CSS colors.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $color (default: '')
 * @param string $context (default: '')
 *
 * @return string $color
 */
function lbs_sanitize_css_color( $color = '', $context = '' ) {

	// Lowercase and strip all unnecessary characters
	$color = preg_replace( '/[^0-9a-f]/', '', strtolower( $color ) );

	if ( 6 !== strlen( $color ) && 3 !== strlen( $color ) ) {
		$color = false;
	}

	// Prepare for output
	if ( in_array( $context, array( 'display', 'js' ) ) ) {
		if ( ! empty( $color ) ) {
			$color = '#' . $color;
		}

		if ( 'js' === $context ) {
			$color = json_encode( $color );
		}
	}

	return $color;
}

/**
 * Sanitize the font family.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $font (default: '')
 * @param string $context (default: '')
 * @uses lbs_default_font_families()
 *
 * @return string $color
 */
function lbs_sanitize_font_family( $font = '', $context = '' ) {

	// Make sure we were sent a valid font
	if ( ! in_array( $font, array( 'arial', 'courier', 'georgia', 'palantino', 'tahoma', 'times', 'verdana' ) ) ) {
		$font = false;
	}

	// Return early if we're saving to the db
	if ( 'db' === $context ) {
		return $font;
	}

	$font_family = lbs_default_font_families( $font );
	if ( empty( $font ) ) {
		$font_family = false;
	}

	if ( 'js' === $context ) {
		$font_family = json_encode( $font_family );
	}

	return array( 'font' => $font, 'family' => $font_family );
}

/**
 * Sanitize the font size.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $font_size (default: '')
 * @param string $context (default: '')
 * @uses absint()
 *
 * @return bool|string
 */
function lbs_sanitize_font_size( $font_size = '', $context = '' ) {

	$font_size = absint( $font_size );
	if ( ! in_array( $font_size, array( 12, 14, 16, 18 ) ) ) {
		$font_size = false;
	}

	if ( in_array( $context, array( 'display', 'js' ) ) ) {
		if ( ! empty( $font_size ) ) {
			$font_size = $font_size . 'px';
		}

		if ( 'js' === $context ) {
			$font_size = json_encode( $font_size );
		}
	}

	return $font_size;
}

/**
 * Sanitize the exclude classes/tags.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param array $content (default: false)
 * @param string $context (default: '')
 *
 * @return string $content
 */
function lbs_sanitize_exclude_content( $content = array(), $context = '' ) {
	if ( ! is_array( $content ) ) {
		$content = explode( ',', $content );
	}

	if ( ! empty( $content ) ) {
		$content = array_map( 'trim', $content );
		$content = array_map( 'sanitize_html_class', $content );
		$content = array_unique( array_filter( $content ) );
	}

	if ( 'js' === $context ) {
		$content = json_encode( $content );
	}

	return $content;
}

/**
 * Sanitize the Logos link color.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $color (default: 'light')
 * @param string $context (default: '')
 *
 * @return string $color
 */
function lbs_sanitize_libronix_color( $color = 'light', $context = '' ) {

	if ( ! in_array( $color, array( 'light', 'dark' ) ) ) {
		$color = 'light';
	}

	if ( 'js' === $context ) {
		$color = json_encode( $color );
	}

	return $color;
}

/**
 * Sanitize the sharing options.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param array $sharing (default: array())
 * @param string $context (default: '')
 * @uses lbs_default_sharing_args()
 *
 * @return array|string $sharing
 */
function lbs_sanitize_social_sharing( $sharing = array(), $context = '' ) {

	$sharing  = (array) $sharing;
	$defaults = lbs_default_sharing_args();

	foreach ( $sharing as $key => $value ) {
		if ( ! in_array( $value, $defaults ) ) {
			unset( $sharing[ $key ] );
		}
	}

	if ( 'js' === $context ) {
		$sharing = json_encode( $sharing );
	}

	return $sharing;
}

/* DEFAULTS ******************************************************************/

/**
 * Return an array of default Bible versions.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @return array Bible versions
 */
function lbs_default_bibles() {
	return array(
		// English
		'AB', 'ASV', 'DAR', 'DOUAYRHEIMS', 'ESV', 'GW', 'HCSB', 'KJV', 'LEB',
		'MESSAGE', 'NASB', 'NCV', 'NIRV', 'NIV', 'NKJV', 'NLT', 'RSVCE', 'YLT',
		// Spanish
		'LBLA95', 'NBLH', 'NVI', 'RVA', 'RVR60',
	);
}

/**
 * Return our default font families.
 *
 * If a font is passed, this function attempts to return a corresponding font
 * family. If no match is found, the full font family array is returned.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $font (default: '')
 *
 * @return string|array
 */
function lbs_default_font_families( $font = '' ) {

	$families = array(
		'arial'     => "Arial, 'Helvetica Neue', Helvetica, sans-serif",
		'courier'   => "'Courier New', Courier, 'Lucida Sans Typewriter', 'Lucida Typewriter', monospace",
		'georgia'   => "Georgia, Times, 'Times New Roman', serif",
		'palantino' => "Palatino, 'Palatino Linotype', 'Palatino LT STD', 'Book Antiqua', Georgia, serif",
		'tahoma'    => 'Tahoma, Verdana, Segoe, sans-serif',
		'times'     => "TimesNewRoman, 'Times New Roman', Times, Baskerville, Georgia, serif",
		'verdana'   => 'Verdana, Geneva, sans-serif',
	);

	if ( ! empty( $font ) && ! empty( $families[ $font ] ) ) {
		return $families[ $font ];
	} else {
		return $families;
	}
}

/**
 * Return an array of default sharing options.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @return array Sharing options
 */
function lbs_default_sharing_args() {
	return array( 'faithlife', 'facebook', 'google', 'twitter' );
}

/* SHORTCODES *****************************************************************/

/**
 * Register our shortcodes.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @return void
 */
function lbs_register_shortcodes() {
	add_shortcode( 'noreftagger', 'lbs_do_noreftagger_shortcode' );
}

/**
 * Wraps a verse reference in a <span> tag with the 'no-reftagger' class, to
 * allow users to turn off tagging for specific references from within the
 * Posts/Pages editor.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param mixed $attributes
 * @param mixed $content (default: false)
 *
 * @return void
 */
function lbs_do_noreftagger_shortcode( $attributes, $content = false ) {
	if ( empty( $content ) ) {
		return;
	}

	return '<span class="no-reftagger">' . $content . '</span>';
}

/* LOCALIZATION ***************************************************************/

/**
 * Load the translation file for current language. Checks the languages
 * folder inside the RefTagger plugin first, and then the default WordPress
 * languages folder.
 *
 * Note that custom translation files inside the RefTagger plugin folder
 * will be removed on RefTagger updates. If you're creating custom
 * translation files, please use the global language folder.
 *
 * Paths checked (in order attempted):
 * /wp-content/languages/reftagger
 * /wp-content/reftagger/languages/
 * /wp-content/languages/plugins/
 *
 * Translations should be prefixed with 'reftagger' followed by the locale string
 * i.e. - reftagger-en_US.po/reftagger-en_US.mo
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_locale()
 * @uses apply_filters()
 * @uses load_textdomain()
 * @uses plugin_basename()
 * @uses load_plugin_textdomain()
 *
 * @return void
 */
function lbs_load_textdomain() {

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale', get_locale(), 'reftagger' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'reftagger', $locale );

	// Setup paths to current locale file
	$mofile_global = WP_LANG_DIR . '/reftagger/' . $mofile;

	// Look in global /wp-content/languages/reftagger folder
	load_textdomain( 'reftagger', $mofile_global );

	// Look in /wp-content/reftagger/languages/
	// else in global /wp-content/languages/plugins/
	load_plugin_textdomain( 'reftagger', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/* INCLUDES *******************************************************************/

// Only include admin functions when we're on an admin page
if ( is_admin() ) {
	include dirname( __FILE__ ) . '/admin.php';
}

/* SUB-ACTIONS ****************************************************************/

/**
 * The below functions exist solely to create plugin dependency hooks, and exist
 * mostly to allow a reliable way for plugins to hook into the RefTagger plugin.
 *
 * When using the appropriate hook, plugin authors using these hooks can be
 * confident that any relevant RefTagger code has been loaded, and that they're
 * code will only be executed when the RefTagger plugin is active.
 */

/**
 * Initialize any code after everything has been loaded.
 *
 * Runs on the WP 'init' hook, with priority '0'.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses do_action()
 *
 * @return void
 */
function lbs_reftagger_init() {
	do_action( 'lbs_reftagger_init' );
}

/**
 * RefTagger has initialized all it's code.
 *
 * Runs on the 'lbs_reftagger_ready' hook, with priority '999'.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses do_action()
 *
 * @return void
 */
function lbs_reftagger_ready() {
	do_action( 'lbs_reftagger_ready' );
}

/**
 * Runs on the WP 'plugins_loaded' hook, with priority '10'.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses do_action()
 *
 * @return void
 */
function lbs_reftagger_loaded() {
	do_action( 'lbs_reftagger_loaded' );
}

/**
 * Register our shortcodes.
 *
 * Runs on the 'lbs_reftagger_init' hook, with priority '10'.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses do_action()
 *
 * @return void
 */
function lbs_reftagger_register_shortcodes() {
	do_action( 'lbs_reftagger_register_shortcodes' );
}

/* ACTIONS ********************************************************************/

// Register activation/uninstall hooks. Adds/removes options used by the plugin.
register_activation_hook( __FILE__, 'lbs_set_options' );
register_uninstall_hook( __FILE__, 'lbs_unset_options' );

// RefTagger self-hooks
add_action( 'init', 'lbs_reftagger_init', 0 );
add_action( 'lbs_reftagger_init', 'lbs_reftagger_ready', 999 );
add_action( 'plugins_loaded', 'lbs_reftagger_loaded' );
add_action( 'lbs_reftagger_init', 'lbs_reftagger_register_shortcodes' );

// Load translation files
add_action( 'lbs_reftagger_init', 'lbs_load_textdomain', 0 );

// Register RefTagger shortcodes
add_action( 'lbs_reftagger_register_shortcodes', 'lbs_register_shortcodes', 0 );

// Add the RefTagger script with the user specified options
add_action( 'wp_footer', 'lbsFooter' );
