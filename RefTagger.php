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

// Register the user preferences when the plugin is enabled
function lbs_set_options()
{
	add_option('lbs_bible_version', 'ESV', 'Which Bible version to use');
	add_option('lbs_libronix', 'false', 'Insert Logos Bible Software links');
	add_option('lbs_existing_libronix', 'false', 'Insert Logos icon after existing Logos Bible Software links');
	add_option('lbs_libronix_color', 'dark', 'Color of Logos link icons');
	add_option('lbs_tooltips', '1', 'Show a tooltip containing the verse text when the mouse hovers over a reference');
	add_option('lbs_search_comments', '1', 'Whether or not to search user comments');
	$default_nosearch = array('h1' => "1",
							  'h2' => "1",
							  'h3' => "1");
	add_option('lbs_nosearch', $default_nosearch, 'List of HTML tags that will not be searched');
	add_option('lbs_new_window', '0', 'Whether or not to open links in a new window');
	add_option('lbs_libronix_bible_version', 'ESV', 'Which Bible version to use with Logos Bible Software links');
	add_option('lbs_convert_hyperlinks', '0', 'Whether or not to add tooltips to existing Biblia.com and Ref.ly links');
	add_option('lbs_case_insensitive', '0', 'Whether or not to link references with improper casing');
	add_option('lbs_tag_chapters', '0', 'Whether or not to tag chapter references (e.g. Genesis 1)');
}

// Remove the user preferences when the plugin is disabled
function lbs_unset_options()
{
	delete_option('lbs_bible_version');
	delete_option('lbs_libronix');
	delete_option('lbs_existing_libronix');
	delete_option('lbs_libronix_color');
	delete_option('lbs_tooltips');
	delete_option('lbs_search_comments');
	delete_option('lbs_nosearch');
	delete_option('lbs_new_window');
	delete_option('lbs_libronix_bible_version');
	delete_option('lbs_convert_hyperlinks');
	delete_option('lbs_case_insensitive');
	delete_option('lbs_tag_chapters');
}

// The options page
function lbs_admin_options()
{
	?>

<div class="wrap">
  <h2>RefTagger Settings</h2>
  <?php

	// If the user clicked submit, update the preferences
	if($_REQUEST['submit'])
	{
		lbs_update_options();
	}

	// Print the options page
	lbs_options_page();

	?>
</div>
<?php
}

// Update any preferences the user has changed
function lbs_update_options()
{
	$changed = false;
	$old_libronix = get_option('lbs_libronix');
	$existing_libronix = get_option('lbs_existing_libronix');
	$old_comments = get_option('lbs_search_comments');
	$nosearch = get_option('lbs_nosearch');
	$window = get_option('lbs_new_window');
	$old_tooltips = get_option('lbs_tooltips');
	$old_convert = get_option('lbs_convert_hyperlinks');
	$old_case = get_option('lbs_case_insensitive');
	$old_tag_chapters = get_option('lbs_tag_chapters');

	if($_REQUEST['lbs_bible_version'])
	{
		update_option('lbs_bible_version', $_REQUEST['lbs_bible_version']);
		$changed = true;
	}

	if($_REQUEST['lbs_libronix_bible_version'])
	{
		update_option('lbs_libronix_bible_version', $_REQUEST['lbs_libronix_bible_version']);
		$changed = true;
	}

	if($_REQUEST['lbs_libronix'] != $old_libronix)
	{
		update_option('lbs_libronix', $_REQUEST['lbs_libronix']);
		$changed = true;
	}

	if($_REQUEST['lbs_convert_hyperlinks'] != $old_convert)
	{
		update_option('lbs_convert_hyperlinks', $_REQUEST['lbs_convert_hyperlinks']);
		$changed = true;
	}

	if($_REQUEST['lbs_case_insensitive'] != $old_case)
	{
		update_option('lbs_case_insensitive', $_REQUEST['lbs_case_insensitive']);
		$changed = true;
	}

	if($_REQUEST['lbs_existing_libronix'] != $existing_libronix)
	{
		update_option('lbs_existing_libronix', $_REQUEST['lbs_existing_libronix']);
		$changed = true;
	}

	if($_REQUEST['lbs_tag_chapters'] != $old_tag_chapters)
	{
		update_option('lbs_tag_chapters', $_REQUEST['lbs_tag_chapters']);
		$changed = true;
	}

	if($_REQUEST['lbs_libronix_color'])
	{
		update_option('lbs_libronix_color', $_REQUEST['lbs_libronix_color']);
		$changed = true;
	}

	if($_REQUEST['lbs_tooltips'] != $old_tooltips)
	{
		update_option('lbs_tooltips', $_REQUEST['lbs_tooltips']);
		$changed = true;
	}

	if($_REQUEST['lbs_search_comments'] != $old_comments)
	{
		update_option('lbs_search_comments', $_REQUEST['lbs_search_comments']);
		$changed = true;
	}

	if($_REQUEST['lbs_new_window'])
	{
		update_option('lbs_new_window', $_REQUEST['lbs_new_window']);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_h1'] != $nosearch['h1'])
	{
		$nosearch['h1'] = $_REQUEST['lbs_nosearch_h1'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_h2'] != $nosearch['h2'])
	{
		$nosearch['h2'] = $_REQUEST['lbs_nosearch_h2'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_h3'] != $nosearch['h3'])
	{
		$nosearch['h3'] = $_REQUEST['lbs_nosearch_h3'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_h4'] != $nosearch['h4'])
	{
		$nosearch['h4'] = $_REQUEST['lbs_nosearch_h4'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_h5'] != $nosearch['h5'])
	{
		$nosearch['h5'] = $_REQUEST['lbs_nosearch_h5'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_h6'] != $nosearch['h6'])
	{
		$nosearch['h6'] = $_REQUEST['lbs_nosearch_h6'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_b'] != $nosearch['b'])
	{
		$nosearch['b'] = $_REQUEST['lbs_nosearch_b'];
		$nosearch['strong'] = $_REQUEST['lbs_nosearch_b'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_i'] != $nosearch['i'])
	{
		$nosearch['i'] = $_REQUEST['lbs_nosearch_i'];
		$nosearch['em'] = $_REQUEST['lbs_nosearch_i'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_u'] != $nosearch['u'])
	{
		$nosearch['u'] = $_REQUEST['lbs_nosearch_u'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_ol'] != $nosearch['ol'])
	{
		$nosearch['ol'] = $_REQUEST['lbs_nosearch_ol'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($_REQUEST['lbs_nosearch_ul'] != $nosearch['ul'])
	{
		$nosearch['ul'] = $_REQUEST['lbs_nosearch_ul'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}

	if($_REQUEST['lbs_nosearch_span'] != $nosearch['span'])
	{
		$nosearch['span'] = $_REQUEST['lbs_nosearch_span'];
		update_option('lbs_nosearch', $nosearch);
		$changed = true;
	}
	if($changed)
	{
		?>
<div id="message" class="updated fade">
  <p>Settings Saved.</p>
</div>
<?php
	}
}

// Print the options page
function lbs_options_page()
{
	$selected_version = get_option('lbs_bible_version');
	$selected_libronix = get_option('lbs_libronix');
	$selected_existing_libronix = get_option('lbs_existing_libronix');
	$selected_color = get_option('lbs_libronix_color');
	$selected_tooltips = get_option('lbs_tooltips');
	$selected_nosearch = get_option('lbs_nosearch');
	$selected_comments = get_option('lbs_search_comments');
	$selected_window = get_option('lbs_new_window');
	$selected_lib_version = get_option('lbs_libronix_bible_version');
	$selected_convert_hyperlinks = get_option('lbs_convert_hyperlinks');
	$selected_case_insensitive = get_option('lbs_case_insensitive');
	$selected_tag_chapters = get_option('lbs_tag_chapters');
	?>
<form method="post">
  <table class="form-table">
    <tr style="vertical-align:top">
      <th scope="row">Bible version:</th>
      <td><select name="lbs_bible_version">
          <option value="NIV" <?php if ($selected_version == 'NIV') { print 'selected="SELECTED"'; } ?>>NIV</option>
          <option value="NASB" <?php if ($selected_version == 'NASB') { print 'selected="SELECTED"'; } ?>>NASB</option>
          <option value="KJV" <?php if ($selected_version == 'KJV') { print 'selected="SELECTED"'; } ?>>KJV</option>
		  <option value="NKJV" <?php if ($selected_version == 'NKJV') { print 'selected="SELECTED"'; } ?>>NKJV</option>
          <option value="ESV" <?php if ($selected_version == 'ESV') { print 'selected="SELECTED"'; } ?>>ESV</option>
          <option value="ASV" <?php if ($selected_version == 'ASV') { print 'selected="SELECTED"'; } ?>>ASV</option>
          <option value="NLT" <?php if ($selected_version == 'NLT') { print 'selected="SELECTED"'; } ?>>NLT</option>
          <option value="YLT" <?php if ($selected_version == 'YLT') { print 'selected="SELECTED"'; } ?>>YLT</option>
          <option value="DAR" <?php if ($selected_version == 'DAR') { print 'selected="SELECTED"'; } ?>>DARBY</option>
          <option value="NIRV" <?php if ($selected_version == 'NIRV') { print 'selected="SELECTED"'; } ?>>NIRV</option>
          <option value="TNIV" <?php if ($selected_version == 'TNIV') { print 'selected="SELECTED"'; } ?>>TNIV</option>
		  <option value="GW" <?php if ($selected_version == 'GW') { print 'selected="SELECTED"'; } ?>>GW</option>
		  <option value="MESSAGE" <?php if ($selected_version == 'MESSAGE') { print 'selected="SELECTED"'; } ?>>The Message</option>
		  <option value="RSVCE" <?php if ($selected_version == 'RSVCE') { print 'selected="SELECTED"'; } ?>>RSVCE</option>
		  <option value="DOUAYRHEIMS" <?php if ($selected_version == 'DOUAYRHEIMS') { print 'selected="SELECTED"'; } ?>>D-R</option>
		  <option value="RVR60" <?php if ($selected_version == 'RVR60') { print 'selected="SELECTED"'; } ?>>RVR60</option>
		  <option value="NVI" <?php if ($selected_version == 'NVI') { print 'selected="SELECTED"'; } ?>>NVI</option>
		  <option value="LBLA95" <?php if ($selected_version == 'LBLA95') { print 'selected="SELECTED"'; } ?>>LBLA95</option>
		  <option value="NBLH" <?php if ($selected_version == 'NBLH') { print 'selected="SELECTED"'; } ?>>NBLH</option>
		  <option value="RVA" <?php if ($selected_version == 'RVA') { print 'selected="SELECTED"'; } ?>>RVA</option>
        </select>
      </td>
    </tr>
    <tr style="vertical-align:middle">
      <th scope="row">Links open in:</th>
      <td><input name="lbs_new_window" value="0" id="lbs_new_window0" style="vertical-align: middle" type="radio" <?php if ($selected_window == '0') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_new_window0">Existing window</label>
        <br/>
        <input name="lbs_new_window" value="1" id="lbs_new_window1" style="vertical-align: middle" type="radio" <?php if ($selected_window == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_new_window1">New window</label>
      </td>
    </tr>
    <tr style="vertical-align:middle">
      <th scope="row">Insert Logos Bible Software links:</th>
      <td><input name="lbs_libronix" value="1" id="lbs_libronix" type="checkbox" <?php if ($selected_libronix == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_libronix">Insert a small icon linking to the verse in <a href="http://www.logos.com/demo?wprtplugin" target="_blank">Logos Bible Software</a>.</label>
        <br/>
        <input name="lbs_existing_libronix" value="1" id="lbs_existing_libronix" type="checkbox" <?php if ($selected_existing_libronix == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_existing_libronix">Add a Logos icon to existing Logos Bible Software links.</label>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Logos Bible Software Bible version:</th>
      <td><select name="lbs_libronix_bible_version">
          <option value="DEFAULT" <?php if ($selected_lib_version == 'DEFAULT') { print 'selected="SELECTED"'; } ?>>User’s Default</option>
          <option value="NIV" <?php if ($selected_lib_version == 'NIV') { print 'selected="SELECTED"'; } ?>>NIV</option>
          <option value="NASB95" <?php if ($selected_lib_version == 'NASB95') { print 'selected="SELECTED"'; } ?>>NASB95</option>
          <option value="NASB" <?php if ($selected_lib_version == 'NASB') { print 'selected="SELECTED"'; } ?>>NASB77</option>
          <option value="KJV" <?php if ($selected_lib_version == 'KJV') { print 'selected="SELECTED"'; } ?>>KJV</option>
          <option value="ESV" <?php if ($selected_lib_version == 'ESV') { print 'selected="SELECTED"'; } ?>>ESV</option>
          <option value="ASV" <?php if ($selected_lib_version == 'ASV') { print 'selected="SELECTED"'; } ?>>ASV</option>
          <option value="MESSAGE" <?php if ($selected_lib_version == 'MESSAGE') { print 'selected="SELECTED"'; } ?>>MSG</option>
          <option value="NRSV" <?php if ($selected_lib_version == 'NRSV') { print 'selected="SELECTED"'; } ?>>NRSV</option>
          <option value="AMP" <?php if ($selected_lib_version == 'AMP') { print 'selected="SELECTED"'; } ?>>AMP</option>
          <option value="NLT" <?php if ($selected_lib_version == 'NLT') { print 'selected="SELECTED"'; } ?>>NLT</option>
          <option value="CEV" <?php if ($selected_lib_version == 'CEV') { print 'selected="SELECTED"'; } ?>>CEV</option>
          <option value="NKJV" <?php if ($selected_lib_version == 'NKJV') { print 'selected="SELECTED"'; } ?>>NKJV</option>
          <option value="NCV" <?php if ($selected_lib_version == 'NCV') { print 'selected="SELECTED"'; } ?>>NCV</option>
          <option value="KJ21" <?php if ($selected_lib_version == 'KJ21') { print 'selected="SELECTED"'; } ?>>KJ21</option>
          <option value="YLT" <?php if ($selected_lib_version == 'YLT') { print 'selected="SELECTED"'; } ?>>YLT</option>
          <option value="DARBY" <?php if ($selected_lib_version == 'DARBY') { print 'selected="SELECTED"'; } ?>>DARBY</option>
          <option value="ANIV" <?php if ($selected_lib_version == 'ANIV') { print 'selected="SELECTED"'; } ?>>ANIV</option>
          <option value="HCSB" <?php if ($selected_lib_version == 'HCSB') { print 'selected="SELECTED"'; } ?>>HCSB</option>
          <option value="NIRV" <?php if ($selected_lib_version == 'NIRV') { print 'selected="SELECTED"'; } ?>>NIRV</option>
          <option value="TNIV" <?php if ($selected_lib_version == 'TNIV') { print 'selected="SELECTED"'; } ?>>TNIV</option>
        </select>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Logos link icon:</th>
      <td><input name="lbs_libronix_color" id="lbs_libronix_color0" value="dark" style="vertical-align: middle" type="radio" <?php if ($selected_color == 'dark') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_libronix_color0"><img src="http://www.logos.com/images/Corporate/LibronixLink_dark.png"/> Dark (for sites with light backgrounds)</label>
        <br/>
        <input name="lbs_libronix_color" value="light" id="lbs_libronix_color1" style="vertical-align: middle" type="radio" <?php if ($selected_color == 'light') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_libronix_color1"><img src="http://www.logos.com/images/Corporate/LibronixLink_light.png"/> Light (for sites with dark backgrounds)</label>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Show tooltips:</th>
      <td><input name="lbs_tooltips" value="1" id="lbs_tooltips" type="checkbox" <?php if ($selected_tooltips == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_tooltips">Show a tooltip containing the verse text when the mouse hovers over a reference.</label>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Add tooltips to links:</th>
      <td><input name="lbs_convert_hyperlinks" value="1" id="lbs_convert_hyperlinks" type="checkbox" <?php if ($selected_convert_hyperlinks == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_convert_hyperlinks">Add tooltips to existing <a href="http://biblia.com/" target="_blank">Biblia.com</a> and <a href="http://ref.ly/" target="_blank">Ref.ly</a> links.</label>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Case sensitivity:</th>
      <td><input name="lbs_case_insensitive" value="1" id="lbs_case_insensitive" type="checkbox" <?php if ($selected_case_insensitive == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_case_insensitive">Tag Bible references with improper casing (e.g., jn 3:16 or JOHN 3:16).</label>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Tag chapters:</th>
      <td><input name="lbs_tag_chapters" value="1" id="lbs_tag_chapters" type="checkbox" <?php if ($selected_tag_chapters == '1') { print 'checked="CHECKED"'; } ?>>
        <label for="lbs_tag_chapters">Tag chapter references (e.g. Gen 1).</label>
      </td>
    </tr>
    <tr style="vertical-align:top">
      <th scope="row">Search options:</th>
      <td>
      <input name="lbs_search_comments" value="1" id="lbs_search_comments" type="checkbox" <?php if ($selected_comments == '1') { print 'checked="CHECKED"'; } ?>>
      <label for="lbs_search_comments">Search for Bible references in user comments.</label>
      <br/>
      <br/>
      <table>
        <tr>Do not search the following HTML tags:</tr>
        <tr>
          <td><input name="lbs_nosearch_b" value="1" id="lbs_nosearch_b" type="checkbox" <?php if ($selected_nosearch['b'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_b">Bold</label>
            <br/>
            <input name="lbs_nosearch_i" value="1" id="lbs_nosearch_i" type="checkbox" <?php if ($selected_nosearch['i'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_i">Italic</label>
            <br/>
            <input name="lbs_nosearch_u" value="1" id="lbs_nosearch_u" type="checkbox" <?php if ($selected_nosearch['u'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_u">Underline</label>
            <br/>
            <input name="lbs_nosearch_ol" value="1" id="lbs_nosearch_ol" type="checkbox" <?php if ($selected_nosearch['ol'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_ol">Ordered list</label>
            <br/>
            <input name="lbs_nosearch_ul" value="1" id="lbs_nosearch_ul" type="checkbox" <?php if ($selected_nosearch['ul'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_ul">Unordered list</label>
            <br/>
            <input name="lbs_nosearch_span" value="1" id="lbs_nosearch_span" type="checkbox" <?php if ($selected_nosearch['span'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_span">Span</label>
          </td>
          <td><input name="lbs_nosearch_h1" value="1" id="lbs_nosearch_h1" type="checkbox" <?php if ($selected_nosearch['h1'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_h1">Header 1</label>
            <br/>
            <input name="lbs_nosearch_h2" value="1" id="lbs_nosearch_h2" type="checkbox" <?php if ($selected_nosearch['h2'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_h2">Header 2</label>
            <br/>
            <input name="lbs_nosearch_h3" value="1" id="lbs_nosearch_h3" type="checkbox" <?php if ($selected_nosearch['h3'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_h3">Header 3</label>
            <br/>
            <input name="lbs_nosearch_h4" value="1" id="lbs_nosearch_h4" type="checkbox" <?php if ($selected_nosearch['h4'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_h4">Header 4</label>
            <br/>
            <input name="lbs_nosearch_h5" value="1" id="lbs_nosearch_h5" type="checkbox" <?php if ($selected_nosearch['h5'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_h5">Header 5</label>
            <br/>
            <input name="lbs_nosearch_h6" value="1" id="lbs_nosearch_h6" type="checkbox" <?php if ($selected_nosearch['h6'] == '1') { print 'checked="CHECKED"'; } ?>>
            <label for="lbs_nosearch_h6">Header 6</label>
          </td>
        </tr>
        </td>

      </table>
  </table>
  <p class="submit">
    <input type="submit" name="submit" value="Save Changes" />
  </p>
</form>
<?php
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

// Add the options page to the menu
function lbs_add_menu()
{
	add_options_page('RefTagger', 'RefTagger', 'manage_options', __FILE__, 'lbs_admin_options');
}

add_action('admin_menu', 'lbs_add_menu');

register_activation_hook(__FILE__, 'lbs_set_options');
register_deactivation_hook(__FILE__, 'lbs_unset_options');

// Run when the footer is generated
add_action('wp_footer', 'lbsFooter');

?>