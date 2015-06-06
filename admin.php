<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add RefTagger to the Settings menu.
 *
 * @since 1.0.0
 *
 * @access public
 *
 * @uses add_options_page()
 *
 * @return void
 */
function lbs_add_menu() {
	add_options_page( 'RefTagger', 'RefTagger', 'manage_options', 'reftagger', 'lbs_admin_options' );
}

/**
 * Set the framework for the RefTagger options page.
 *
 * @since 1.0.0
 *
 * @access public
 *
 * @uses esc_html_e()
 * @uses settings_fields()
 * @uses do_settings_sections()
 * @uses submit_button()
 *
 * @return void
 */
function lbs_admin_options() {
?>

	<div class="wrap">
		<h2><?php esc_html_e( 'RefTagger Settings', 'reftagger' ); ?></h2>

		<form action="options.php" method="post">

			<?php settings_fields( 'reftagger' ); ?>

			<?php do_settings_sections( 'reftagger' ); ?>

			<?php submit_button(); ?>
		</form>
	</div>

<?php
}

/**
 * Register the settings for the options page.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses add_settings_section()
 * @uses register_setting()
 *
 * @return void
 */
function lbs_register_settings() {

	// Add Basic customization settings section
	add_settings_section( 'lbs_basic_customization_settings', __( 'Basic Customizations', 'reftagger' ), '', 'reftagger' );

	// Add 'Heading style'
	add_settings_field(
		'lbs_heading_style',
		__( 'Heading style', 'reftagger' ),
		'lbs_settings_callback_heading_style',
		'reftagger',
		'lbs_basic_customization_settings'
	);
	register_setting( 'reftagger', 'lbs_heading_style', 'lbs_sanitize_style_options' );

	// Add 'Bible translation'
	add_settings_field(
		'lbs_body_style',
		__( 'Body style', 'reftagger' ),
		'lbs_settings_callback_body_style',
		'reftagger',
		'lbs_basic_customization_settings'
	);
	register_setting( 'reftagger', 'lbs_body_style', 'lbs_sanitize_style_options' );

	// Add 'Bible translation'
	add_settings_field(
		'lbs_bible_version',
		__( 'Bible translation', 'reftagger' ),
		'lbs_settings_callback_bible_version',
		'reftagger',
		'lbs_basic_customization_settings'
	);
	register_setting( 'reftagger', 'lbs_bible_version', 'lbs_sanitize_bible_version' );

	// Add 'Additional styling'
	add_settings_field(
		'lbs_additional_styling',
		__( 'Additional styling', 'reftagger' ),
		'lbs_settings_callback_additional_styling',
		'reftagger',
		'lbs_basic_customization_settings'
	);
	register_setting( 'reftagger', 'lbs_drop_shadow', 'intval' );
	register_setting( 'reftagger', 'lbs_rounded_corners', 'intval' );

	// Add 'Social sharing'
	add_settings_field(
		'lbs_social_sharing',
		__( 'Social sharing', 'reftagger' ),
		'lbs_settings_callback_social_sharing',
		'reftagger',
		'lbs_basic_customization_settings'
	);
	register_setting( 'reftagger', 'lbs_social_sharing', 'lbs_sanitize_social_sharing' );

	// Add 'Online Bible reader'
	add_settings_field(
		'lbs_bible_reader',
		__( 'Online Bible reader', 'reftagger' ),
		'lbs_settings_callback_online_bible_reader',
		'reftagger',
		'lbs_basic_customization_settings'
	);
	register_setting( 'reftagger', 'lbs_bible_reader', 'lbs_sanitize_bible_reader' );

	// Add advanced settings section
	add_settings_section( 'lbs_advanced_settings', __( 'Advanced settings', 'reftagger' ), '', 'reftagger' );

	// Add 'Exclude content'
	add_settings_field(
		'lbs_exclude_content',
		__( 'Exclude content', 'reftagger' ),
		'lbs_settings_callback_exclude_content',
		'reftagger',
		'lbs_advanced_settings'
	);
	register_setting( 'reftagger', 'lbs_exclude_tags', 'lbs_sanitize_exclude_content' );
	register_setting( 'reftagger', 'lbs_exclude_classes', 'lbs_sanitize_exclude_content' );
	register_setting( 'reftagger', 'lbs_search_comments', 'intval' );

	// Add 'Logos integration'
	add_settings_field(
		'lbs_logos_integration',
		__( 'Logos integration', 'reftagger' ),
		'lbs_settings_callback_logos_integration',
		'reftagger',
		'lbs_advanced_settings'
	);
	register_setting( 'reftagger', 'lbs_libronix', 'intval' );
	register_setting( 'reftagger', 'lbs_libronix_color', 'lbs_sanitize_libronix_color' );

	// Add 'Advanced options'
	add_settings_field(
		'lbs_advanced_options',
		__( 'Advanced options', 'reftagger' ),
		'lbs_settings_callback_advanced_options',
		'reftagger',
		'lbs_advanced_settings'
	);
	register_setting( 'reftagger', 'lbs_tooltips', 'intval' );
	register_setting( 'reftagger', 'lbs_new_window', 'intval' );
	register_setting( 'reftagger', 'lbs_case_insensitive', 'intval' );
	register_setting( 'reftagger', 'lbs_convert_hyperlinks', 'intval' );
	register_setting( 'reftagger', 'lbs_tag_chapters', 'intval' );
}

/* CALLBACKS ******************************************************************/

/**
 * Callback function for the heading style setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses lbs_settings_callback_style_output()
 *
 * @return void
 */
function lbs_settings_callback_heading_style() {
	lbs_settings_callback_style_output( 'heading' );
}

/**
 * Callback function for the body style setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses lbs_settings_callback_style_output()
 *
 * @return void
 */
function lbs_settings_callback_body_style() {
	lbs_settings_callback_style_output( 'body' );
}

/**
 * Output HTML for lbs_settings_callback_heading_style() and
 * lbs_settings_callback_body_style().
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $style
 * @uses get_option()
 * @uses lbs_sanitize_style_options()
 * @uses esc_attr()
 * @uses lbs_default_font_families()
 * @uses esc_html_e()
 * @uses selected()
 *
 * @return void
 */
function lbs_settings_callback_style_output( $style = '' ) {

	if ( empty( $style ) ) {
		return;
	}
	$style = 'lbs_' . esc_attr( $style ) . '_style';

	$args     = lbs_sanitize_style_options( get_option( $style ), false );
	$font     = $args['font_family']['font'];
?>

	<label for="<?php echo $style; ?>[font_color]"><?php esc_html_e( 'Font color:', 'reftagger' ); ?></label>
	<input name="<?php echo $style; ?>[font_color]" value="<?php echo esc_attr( '#' . $args['font_color'] ); ?>" data-default-color="" id="<?php echo $style; ?>_font_color" class="lbs-color-picker" type="text" maxlength="7" />
	<br />
	<label for="<?php echo $style; ?>[font_family]"><?php esc_html_e( 'Font family:', 'reftagger' ); ?></label>
	<select name="<?php echo $style; ?>[font_family]" id="<?php echo $style; ?>_font_family">
		<option value="" <?php selected( $font, '' ); ?>><?php esc_html_e( '(Default)', 'reftagger' ); ?></option>
		<option value="arial" <?php selected( $font, 'arial' ); ?> class="lbs-font-family-arial">
			<?php esc_html_e( 'Arial', 'reftagger' ); ?>
		</option>
		<option value="courier" <?php selected( $font, 'courier' ); ?> class="lbs-font-family-courier">
			<?php esc_html_e( 'Courier New', 'reftagger' ); ?>
		</option>
		<option value="georgia" <?php selected( $font, 'georgia' ); ?> class="lbs-font-family-georgia">
			<?php esc_html_e( 'Georgia', 'reftagger' ); ?>
		</option>
		<option value="palantino" <?php selected( $font, 'palantino' ); ?> class="lbs-font-family-palantino">
			<?php esc_html_e( 'Palantino', 'reftagger' ); ?>
		</option>
		<option value="tahoma" <?php selected( $font, 'tahoma' ); ?> class="lbs-font-family-tahoma">
			<?php esc_html_e( 'Tahoma', 'reftagger' ); ?>
		</option>
		<option value="times" <?php selected( $font, 'times' ); ?> class="lbs-font-family-times">
			<?php esc_html_e( 'Times New Roman', 'reftagger' ); ?>
		</option>
		<option value="verdana" <?php selected( $font, 'verdana' ); ?> class="lbs-font-family-verdana">
			<?php esc_html_e( 'Verdana', 'reftagger' ); ?>
		</option>
	</select>
	<br />
	<label for="<?php echo $style; ?>[font_size]"><?php esc_html_e( 'Font size:', 'reftagger' ); ?></label>
	<select name="<?php echo $style; ?>[font_size]" id="<?php echo $style; ?>_font_size">
		<option value="" <?php selected( $args['font_size'], false ); ?>><?php esc_html_e( '(Default)', 'reftagger' ); ?></option>
		<option value="12" <?php selected( $args['font_size'], 12 ); ?>><?php esc_html_e( '12px', 'reftagger' ); ?></option>
		<option value="14" <?php selected( $args['font_size'], 14 ); ?>><?php esc_html_e( '14px', 'reftagger' ); ?></option>
		<option value="16" <?php selected( $args['font_size'], 16 ); ?>><?php esc_html_e( '16px', 'reftagger' ); ?></option>
		<option value="18" <?php selected( $args['font_size'], 18 ); ?>><?php esc_html_e( '18px', 'reftagger' ); ?></option>
	</select>
	<br />
	<label for="<?php echo $style; ?>[background_color]"><?php esc_html_e( 'Background color:', 'reftagger' ); ?></label>
	<input name="<?php echo $style; ?>[background_color]" value="<?php echo esc_attr( '#' . $args['background_color'] ); ?>" data-default-color="" id="<?php echo $style; ?>_background_color" class="lbs-color-picker" type="text" maxlength="7" />

<?php
}

/**
 * Callback function for the bible version setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses lbs_sanitize_bible_version()
 * @uses esc_attr_e()
 * @uses selected()
 * @uses esc_html_e()
 *
 * @return void
 */
function lbs_settings_callback_bible_version() {

	$bible  = lbs_sanitize_bible_version( get_option( 'lbs_bible_version' ) );
?>

	<select name="lbs_bible_version" id="lbs_bible_version">
		<optgroup label="<?php esc_attr_e( 'English Translations', 'reftagger' ); ?>">
			<option value="AB" <?php selected( $bible, 'AB' ); ?>>
				<?php esc_html_e( 'Amplified Bible (AMP)', 'reftagger' ); ?>
			</option>
			<option value="ASV" <?php selected( $bible, 'ASV' ); ?>>
				<?php esc_html_e( 'American Standard Version (ASV)', 'reftagger' ); ?>
			</option>
			<option value="DAR" <?php selected( $bible, 'DAR' ); ?>>
				<?php esc_html_e( 'Darby', 'reftagger' ); ?>
			</option>
			<option value="DOUAYRHEIMS" <?php selected( $bible, 'DOUAYRHEIMS' ); ?>>
				<?php esc_html_e( 'Douay-Rheims', 'reftagger' ); ?>
			</option>
			<option value="ESV" <?php selected( $bible, 'ESV' ); ?>>
				<?php esc_html_e( 'English Standard Version (ESV)', 'reftagger' ); ?>
			</option>
			<option value="GW" <?php selected( $bible, 'GW' ); ?>>
				<?php esc_html_e( "God's Word (GW)", 'reftagger' ); ?>
			</option>
			<option value="HCSB" <?php selected( $bible, 'HCSB' ); ?>>
				<?php esc_html_e( 'Holman Christian Standard Bible (HCSB)', 'reftagger' ); ?>
			</option>
			<option value="KJV" <?php selected( $bible, 'KJV' ); ?>>
				<?php esc_html_e( 'King James Version (KJV)', 'reftagger' ); ?>
			</option>
			<option value="LEB" <?php selected( $bible, 'LEB' ); ?>>
				<?php esc_html_e( 'Lexham English Bible (LEB)', 'reftagger' ); ?>
			</option>
			<option value="MESSAGE" <?php selected( $bible, 'MESSAGE' ); ?>>
				<?php esc_html_e( 'Message Bible', 'reftagger' ); ?>
			</option>
			<option value="NASB" <?php selected( $bible, 'NASB' ); ?>>
				<?php esc_html_e( 'New American Standard Bible (NASB)', 'reftagger' ); ?>
			</option>
			<option value="NIRV" <?php selected( $bible, 'NIRV' ); ?>>
				<?php esc_html_e( "New International Reader's Version", 'reftagger' ); ?>
			</option>
			<option value="NIV" <?php selected( $bible, 'NIV' ); ?>>
				<?php esc_html_e( 'New International Version (NIV)', 'reftagger' ); ?>
			</option>
			<option value="NKJV" <?php selected( $bible, 'NKJV' ); ?>>
				<?php esc_html_e( 'New King James Version (NKJV)', 'reftagger' ); ?>
			</option>
			<option value="NLT" <?php selected( $bible, 'NLT' ); ?>>
				<?php esc_html_e( 'New Living Translation (NLT)', 'reftagger' ); ?>
			</option>
			<option value="RSVCE" <?php selected( $bible, 'RSVCE' ); ?>>
				<?php esc_html_e( 'The Revised Standard Version, Catholic Edition', 'reftagger' ); ?>
			</option>
			<option value="YLT" <?php selected( $bible, 'YLT' ); ?>>
				<?php esc_html_e( "Young's Literal Translation (YLT)", 'reftagger' ); ?>
			</option>
		</optgroup>
		<optgroup label="<?php esc_attr_e( 'Spanish Translations', 'reftagger' ); ?>">
			<option value="LBLA95" <?php selected( $bible, 'LBLA95' ); ?>>
				<?php esc_html_e( 'La Biblia de las Américas (LBLA)', 'reftagger' ); ?>
			</option>
			<option value="NBLH" <?php selected( $bible, 'NBLH' ); ?>>
				<?php esc_html_e( 'Nueva Biblia Latinoamericana de Hoy (NBLH)', 'reftagger' ); ?>
			</option>
			<option value="NVI" <?php selected( $bible, 'NVI' ); ?>>
				<?php esc_html_e( 'Nueva Versión Internacional', 'reftagger' ); ?>
			</option>
			<option value="RVA" <?php selected( $bible, 'RVA' ); ?>>
				<?php esc_html_e( 'Reina-Valera Actualizada', 'reftagger' ); ?>
			</option>
			<option value="RVR60" <?php selected( $bible, 'RVR60' ); ?>>
				<?php esc_html_e( 'Reina Valera Revisada (1960)', 'reftagger' ); ?>
			</option>
		</optgroup>
	</select>

<?php
}

/**
 * Callback function for the additional styling setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses checked()
 * @uses esc_html_e()
 *
 * @return void
 */
function lbs_settings_callback_additional_styling() {
	$drop_shadow     = (bool) get_option( 'lbs_drop_shadow', 1 );
	$rounded_corners = (bool) get_option( 'lbs_rounded_corners', 0 );
?>

	<input name="lbs_drop_shadow" value="1" id="lbs_drop_shadow" type="checkbox" <?php checked( $drop_shadow ); ?> />
	<label for="lbs_drop_shadow"><?php esc_html_e( 'Drop shadow', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_rounded_corners" value="1" id="lbs_rounded_corners" type="checkbox" <?php checked( $rounded_corners ); ?> />
	<label for="lbs_rounded_corners"><?php esc_html_e( 'Rounded corners', 'reftagger' ); ?></label>

<?php

}

/**
 * Callback function for the social sharing setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses lbs_default_sharing_args()
 * @uses get_option()
 * @uses lbs_sanitize_social_sharing()
 * @uses checked()
 * @uses esc_html_e()
 *
 * @return void
 */
function lbs_settings_callback_social_sharing() {
	$args = get_option( 'lbs_social_sharing', lbs_default_sharing_args() );
	$args = lbs_sanitize_social_sharing( $args );
?>

	<input name="lbs_social_sharing[]" value="faithlife" id="lbs_social_sharing_faithlife" type="checkbox" <?php checked( in_array( 'faithlife', $args ) ); ?> />
	<label for="lbs_social_sharing_faithlife"><?php esc_html_e( 'Faithlife', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_social_sharing[]" value="facebook" id="lbs_social_sharing_facebook" type="checkbox" <?php checked( in_array( 'facebook', $args ) ); ?> />
	<label for="lbs_social_sharing_facebook"><?php esc_html_e( 'Facebook', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_social_sharing[]" value="google" id="lbs_social_sharing_google" type="checkbox" <?php checked( in_array( 'google', $args ) ); ?> />
	<label for="lbs_social_sharing_google"><?php echo esc_html_e( 'Google+', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_social_sharing[]" value="twitter" id="lbs_social_sharing_twitter" type="checkbox" <?php checked( in_array( 'twitter', $args ) ); ?> />
	<label for="lbs_social_sharing_twitter"><?php echo esc_html_e( 'Twitter', 'reftagger' ); ?></label>

<?php

}

/**
 * Callback function for the online bible reader setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses lbs_sanitize_bible_reader()
 * @uses checked()
 * @uses esc_html_e()
 *
 * @return void
 */
function lbs_settings_callback_online_bible_reader() {
	$bible_reader = get_option( 'lbs_bible_reader' );
	$bible_reader = lbs_sanitize_bible_reader( $bible_reader );
?>

	<input name="lbs_online_bible_reader" value="biblia" id="lbs_online_bible_reader_biblia" type="radio" <?php checked( $bible_reader, 'biblia' ); ?> />
	<label for="lbs_online_bible_reader_biblia"><?php esc_html_e( 'Biblia', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_online_bible_reader" value="bible.faithlife" id="lbs_online_bible_reader_faithlife" type="radio" <?php checked( $bible_reader, 'bible.faithlife' ); ?> />
	<label for="lbs_online_bible_reader_faithlife"><?php esc_html_e( 'Faithlife Bible Study', 'reftagger' ); ?></label>

<?php
}

/**
 * Callback function for the exclude content setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses lbs_sanitize_exclude_content()
 * @uses esc_html_e()
 * @uses esc_attr()
 * @uses checked()
 *
 * @return void
 */
function lbs_settings_callback_exclude_content() {
	// Prepare our values
	$tags     = (array) get_option( 'lbs_exclude_tags', array( 'h1', 'h2', 'h3' ) );
	$tags     = implode( ', ', lbs_sanitize_exclude_content( $tags ) );
	$classes  = (array) get_option( 'lbs_exclude_classes' );
	$classes  = implode( ', ', lbs_sanitize_exclude_content( $classes ) );
	$comments = (bool) get_option( 'lbs_search_comments', true );
?>

	<label for="lbs_exclude_tags"><?php esc_html_e( 'Tags to exclude:', 'reftagger' ); ?></label>
	<input name="lbs_exclude_tags" value="<?php echo esc_attr( $tags ); ?>" id="lbs_exclude_tags" class="regular-text code" type="text" />
	<br />
	<label for="lbs_exclude_classes"><?php esc_html_e( 'Classes to exclude:', 'reftagger' ); ?></label>
	<input name="lbs_exclude_classes" value="<?php echo esc_attr( $classes ); ?>" id="lbs_exclude_classes" class="regular-text code" type="text" />
	<br />
	<input name="lbs_search_comments" value="1" id="lbs_search_comments" type="checkbox" <?php checked( $comments ); ?> />
	<label for="lbs_search_comments"><?php esc_html_e( 'Search comments', 'reftagger' ); ?></label>


<?php
}

/**
 * Callback function for the Logos integration setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses lbs_sanitize_libronix_color()
 * @uses checked()
 * @uses esc_html_e()
 *
 * @return void
 */
function lbs_settings_callback_logos_integration() {
	// Prepare our values
	$link  = (bool) get_option( 'lbs_libronix', false );
	$color = lbs_sanitize_libronix_color( get_option( 'lbs_libronix_color', 'light' ) );
?>

	<input name="lbs_libronix" value="1" id="lbs_libronix" type="checkbox" <?php checked( $link ); ?> />
	<label for="lbs_libronix"><?php esc_html_e( 'Add Logos buttons to tooltip', 'reftagger' ); ?></label>
	<br /><br />
	<input name="lbs_libronix_color" value="light" id="lbs_libronix_color1" type="radio" <?php checked( $color, 'light' ); ?> />
	<label for="lbs_libronix_color1"><?php esc_html_e( 'Light', 'reftagger' ); ?> <span style="vertical-align: middle;"><img src="//www.logos.com/images/Corporate/LibronixLink_light.png"/></span></label>
	&nbsp;&nbsp;&nbsp;
	<input name="lbs_libronix_color" id="lbs_libronix_color0" value="dark" type="radio" <?php checked( $color, 'dark' ); ?> />
	<label for="lbs_libronix_color0"><?php esc_html_e( 'Dark', 'reftagger' ); ?> <span style="vertical-align: middle;"><img src="//www.logos.com/images/Corporate/LibronixLink_dark.png"/></span></label>

<?php
}

/**
 * Callback function for the Logos integration setting to output the HTML.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses checked()
 * @uses esc_html_e()
 *
 * @return void
 */
function lbs_settings_callback_advanced_options() {
	// Prepare our values
	$tooltips           = (bool) get_option( 'lbs_tooltips', true );
	$new_window         = (bool) get_option( 'lbs_new_window', true );
	$case_insensitive   = (bool) get_option( 'lbs_case_insensitive', true );
	$convert_hyperlinks = (bool) get_option( 'lbs_convert_hyperlinks', false );
	$tag_chapters       = (bool) get_option( 'lbs_tag_chapters',false );
?>

	<input name="lbs_tooltips" value="1" id="lbs_tooltips" type="checkbox" <?php checked( $tooltips ); ?> />
	<label for="lbs_tooltips"><?php esc_html_e( 'Show tooltip on hover', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_new_window" value="1" id="lbs_new_window" type="checkbox" <?php checked( $new_window ); ?> />
	<label for="lbs_new_window"><?php esc_html_e( 'Open Bible in new window', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_case_insensitive" value="1" id="lbs_case_insensitive" type="checkbox" <?php checked( $case_insensitive ); ?> />
	<label for="lbs_case_insensitive"><?php esc_html_e( 'Case sensitivity', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_convert_hyperlinks" value="1" id="lbs_convert_hyperlinks" type="checkbox" <?php checked( $convert_hyperlinks ); ?> />
	<label for="lbs_convert_hyperlinks"><?php esc_html_e( 'Enable Reftagger on existing Biblia links', 'reftagger' ); ?></label>
	<br />
	<input name="lbs_tag_chapters" value="1" id="lbs_tag_chapters" type="checkbox" <?php checked( $tag_chapters ); ?> />
	<label for="lbs_tag_chapters"><?php esc_html_e( 'Chapter-level tagging', 'reftagger' ); ?></label>

<?php
}

/* SCRIPTS/STYLES *************************************************************/

/**
 * Call our styles and scripts when we're on the RefTagger settings page.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @param string $hook_prefix The current admin page hook prefix.
 * @uses wp_enqueue_script()
 * @uses wp_enqueue_script()
 * @uses add_action()
 *
 * @return void
 */
function lbs_enqueue_scripts_and_styles( $hook_prefix = '' ) {

	// Only run if we're on the RefTagger settings page
	if ( 'settings_page_reftagger' !== $hook_prefix ) {
		return;
	}

	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );

	add_action( 'admin_head', 'lbs_add_styles' );

	add_action( 'admin_footer', 'lbs_add_color_picker' );
}

/**
 * Output the admin CSS styles.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @return void
 */
function lbs_add_styles() {
?>

	<style>
		.lbs-font-family-arial {
			font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
		}
		.lbs-font-family-courier {
			font-family: 'Courier New', Courier, 'Lucida Sans Typewriter', 'Lucida Typewriter', monospace;
		}
		.lbs-font-family-georgia {
			font-family: Georgia, Times, 'Times New Roman', serif;
		}
		.lbs-font-family-palantino {
			font-family: Palatino, 'Palatino Linotype', 'Palatino LT STD', 'Book Antiqua', Georgia, serif;
		}
		.lbs-font-family-tahoma {
			font-family: Tahoma, Verdana, Segoe, sans-serif;
		}
		.lbs-font-family-times {
			font-family: TimesNewRoman, 'Times New Roman', Times, Baskerville, Georgia, serif;
		}
		.lbs-font-family-verdana {
			font-family: Verdana, Geneva, sans-serif;
		}
		.wp-picker-container {
			vertical-align: middle;
		}
	</style>

<?php
}

/**
 * Initiate the WP Color Picker.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @return void
 */
function lbs_add_color_picker() {
?>

	<script>
		jQuery(document).ready(function($){
			$('.lbs-color-picker').wpColorPicker();
		});
	</script>

<?php
}

/* UPDATE *********************************************************************/

/**
 * Upgrade routine.
 *
 * Upgrade any options that have changed from previous versions, and bump the
 * plugin version number.
 *
 * @since 2.1.0
 *
 * @access public
 *
 * @uses get_option()
 * @uses delete_option()
 * @uses lbs_sanitize_exclude_content()
 * @uses update_option()
 *
 * @return void
 */
function lbs_upgrade_options() {
	$current_db_version = (int) get_option( 'lbs_plugin_version' );
	$db_version         = lbs_get_db_version();

	// Everything is up-to-date, so move along
	if ( $current_db_version === $db_version ) {
		return;
	}

	// < 2.1.0
	if ( 11 > $db_version ) {

		// Make sure to add any new/missing options
		lbs_set_options();

		// Convert the old HTML tag exclusions to the new option
		$exclude_tags = (array) get_option( 'lbs_nosearch', false );
		if ( ! empty( $exclude_tags ) ) {
			$exclude_tags = array_keys( $exclude_tags, '1' );
			$exclude_tags = lbs_sanitize_exclude_content( $exclude_tags );
			if ( ! empty( $exclude_tags ) ) {
				update_option( 'lbs_exclude_tags', $exclude_tags );
			}
		}
	}

	update_option( 'lbs_db_version', $db_version );
}

/* ADMIN ACTIONS **************************************************************/

// Register settings with Settings API
add_action( 'admin_init', 'lbs_register_settings' );

// Run the updater
add_action( 'admin_init', 'lbs_upgrade_options' );

// Add settings page link to Dashboard
add_action( 'admin_menu', 'lbs_add_menu' );

// Add WP Color Picker
add_action( 'admin_enqueue_scripts', 'lbs_enqueue_scripts_and_styles' );
