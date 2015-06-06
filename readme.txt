=== RefTagger ===
Contributors: Logos, thebrandonallen
Tags: reftagger, reftagging, bible, verse, verses, reference, references, scripture, tagging, tagger, libronix, logos, lbs, ldls
Requires at least: 3.5.2
Tested up to: 4.2.2
Stable tag: trunk

RefTagger turns Bible references into links to the verse on Biblia.com and adds tooltips with the text of the verse.

== Description ==

This plugin provides an easy way to use the RefTagger Bible reference tagging script from Logos Bible Software. It automatically modifies your site to run the script each time a page loads. The script identifies references to Bible verses and turns the references into links to the verse on Biblia.com. Hovering over a link will display a small tooltip containing the text of the reference, so users don't need to leave the page to view the verse. If desired, a small icon can also be inserted next to the reference. The plugin provides a simple options page where you can customize the settings.

Customizable preferences include:

* Which Bible version to link to.
* Option to insert a Logos link after each reference.
* Option to insert a Logos icon after any Logos links you already have on your site.
* Choice of which icon to use if Logos links are enabled.
* Option to enable or disable hover tooltips.
* Option to work on existing Biblia.com and Ref.ly links.
* Option to work on Bible references that use improper casing (e.g., jn 3:16 or JOHN 3:16).
* Option to tag chapter references (e.g. Gen 1).
* Option to prevent searching user comments for references.
* Option to prevent searching specific HTML tags, such as bold, h1, ordered list, etc.

For more information, visit http://reftagger.com.

== Installation ==

= Easy Install =

1. In your WordPress admin, go to 'Plugins' > 'Add New'.
1. Search for 'RefTagger'.
1. Click 'Install', then 'Install Now', and then 'Activate Plugin'.

= Manual Install =

1. Download the plugin.
1. Unzip the plugin to your WordPress plugins directory `(/wp-content/plugins/)`.
1. Activate 'RefTagger' through the 'Plugins' page in WordPress.

== Changelog ==

= 2.1.0 =
* Options now mirror those found at http://reftagger.com, with a few extra WordPress specific options added in.
* Utilize the WordPress Settings API. In short, the settings page looks, and works, better.
* Improve security throughout the plugin.
* The plugin no longer removes your carefully crafted settings upon plugin deactivation. Instead, your settings are only removed when you uninstall.
* By our estimation, you now have 300% more awesome. Feel free to check our math :)

== Upgrade Notice ==

= 2.1.0 =
This update is highly recommended for greater enjoyment and security.

== Usage ==

The plugin will begin working immediately when you activate it on the 'Plugins' page. If you wish to customize the preferences you can do so from 'Settings' > 'RefTagger'. Any changes will take effect immediately after clicking 'Save Changes'.

== Frequently Asked Questions ==

= How do I know if it's working? =

Any Bible references on your site will be displayed as links.

= The plugin is not working =

1. Check to make sure you have activated the plugin from the 'Plugins' page.
1. Check to make sure you have JavaScript enabled in your web browser.
1. If you use a different font for your Bible references, such as italic text, go to the options page at 'Settings' > 'RefTagger' and make sure the script is allowed to search that kind of text.

= See http://reftagger.com/documentation/#faq for more FAQs =

== Screenshots ==

Please see http://reftagger.com for screenshots.