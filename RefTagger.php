<?php
/*
Plugin Name: RefTagger
Plugin URI: http://www.logos.com/reftagger
Description: Transform Bible references into links to the full text of the verse.
Author: Logos Bible Software
Version: 2.0.3
Author URI: http://www.logos.com/
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

function lbsFooter($unused)
{
	$bible_version = get_option('lbs_bible_version');
	$libronix = get_option('lbs_libronix');
	$existing_libronix = get_option('lbs_existing_libronix');
	$link_color = get_option('lbs_libronix_color');
	$tooltips = get_option('lbs_tooltips');
	$search_comments = get_option('lbs_search_comments');
	$nosearch = get_option('lbs_nosearch');
	$new_window = get_option('lbs_new_window');
	$libronix_bible_version = get_option('lbs_libronix_bible_version');
	$convert_hyperlinks = get_option('lbs_convert_hyperlinks');
	$case_insensitive = get_option('lbs_case_insensitive');
	$tag_chapters = get_option('lbs_tag_chapters');
	$is_spanish = in_array(get_option('lbs_bible_version'), array('RVA', 'LBLA95', 'NBLH', 'RVR60', 'NVI'));
	$first = true;

	// Generate the script code to be printed on the page
	?>
<script>
	var refTagger = {
		settings: {
			bibleVersion: "<?php echo $bible_version;?>",
			libronixBibleVersion: "<?php echo $libronix_bible_version;?>",
			addLogosLink: <?php echo ($libronix ? 'true' : 'false');?>,
			appendIconToLibLinks: <?php echo ($existing_libronix ? 'true' : 'false');?>,
			libronixLinkIcon: "<?php echo $link_color;?>",
			noSearchClassNames: <?php echo ($search_comments ? '[]' : '[ "commentList" ]');?>,
			useTooltip: <?php echo ($tooltips ? 'true' : 'false');?>,
			noSearchTagNames: [<?php
				$first = true;
				if (is_array($nosearch))
				{
					foreach($nosearch as $tagname => $value)
					{
						if($value == '1')
						{
							if($first)
								$first = false;
							else
								echo ', ';

							echo '"'.$tagname.'"';
						}
					}
				}?>],
			linksOpenNewWindow: <?php echo ($new_window ? 'true' : 'false');?>,
			convertHyperlinks: <?php echo ($convert_hyperlinks ? 'true' : 'false');?>,
			caseInsensitive: <?php echo ($case_insensitive ? 'true' : 'false');?>,
			tagChapters: <?php echo ($tag_chapters ? 'true' : 'false');?>
		}
	};

	(function(d, t) {
		var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
		g.src = '//api.reftagger.com/v2/reftagger<?php echo $is_spanish ? '.es' : ''?>.js';
		s.parentNode.insertBefore(g, s);
	}(document, 'script'));
</script>
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

register_activation_hook(__FILE__, 'lbs_set_options');
register_deactivation_hook(__FILE__, 'lbs_unset_options');

// Run when the footer is generated
add_action('wp_footer', 'lbsFooter');

?>