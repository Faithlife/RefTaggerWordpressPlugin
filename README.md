=== Logos Reftagger ===
Contributors: Logos Bible Software
Donate link: 
Tags: reftagger, reftagging, bible, verse, verses, reference, references, scripture, tagging, tagger, libronix, logos, faithlife, lbs, ldls
Requires at least: 2.3
Tested up to: 6.4
Stable tag: 2.4.6

Logos Reftagger turns Bible references into links to the verse on Biblia.com and adds tooltips with the text of the verse.

== Description ==

Logos Reftagger is a service which automatically converts Bible references on your site into links so your site’s visitors can see Scripture just by hovering over the link. Reftagger modifies your site to run the tagging script each time a page loads, identifying Bible verse references and turning them into links to the verse on Biblia.com.

Hovering over a link displays a tooltip with the text of the reference, so users don’t need to leave the page to see the verse. You can also insert a small icon next to the reference to open the verse in Logos Bible Software.

The plugin provides a simple options page where you can customize settings. Preferences include options to:

* Specify a Bible version.
* Insert a Logos link after each reference.
* Insert a Logos icon after existing Logos links on your site.
* Choose which icon to use if Logos links are enabled.
* Enable or disable hover tooltips.
* Work on existing Biblia.com and Ref.ly links.
* Work on Bible references with improper casing (such as jn 3:16 or JOHN 3:16).
* Tag chapter references (such as Gen. 1).
* Prevent searching user comments for references.
* Prevent searching specific HTML tags (such as bold, h1, ordered list).

This plugin relies on the Logos Reftagger API service (api.reftagger.com) to make this tagging possible. By using this plugin and corresponding service, you are accept Logos Reftagger API's Privacy Policy (https://www.logos.com/privacy) and Terms of Use (https://www.logos.com/terms).

For more information, visit https://www.logos.com/reftagger.

== Installation ==

Easy Install

1. In your WordPress admin, go to Plugins > Add New.
1. Search for Reftagger.
1. Click Install, then Install Now, and then Activate Plugin.

Manual Install

1. Download the plugin.
1. Unzip the plugin to your WordPress plugins directory `(/wp-content/plugins/)`.
1. Activate Reftagger through the Plugins page in WordPress.

== Usage ==

The plugin works immediately when you activate it on the Plugins page. Customize preferences from Settings > Logos Reftagger. Any changes take effect immediately after clicking Save Changes.

== Frequently Asked Questions ==

= How do I know if it’s working? =

Any Bible references on your site will be displayed as links.

= The plugin is not working =

1. Check to make sure you have activated the plugin from the Plugins page.
1. Check to make sure you have JavaScript enabled in your web browser. 
1. If you use a different font for your Bible references, such as italic text, go to the settings page at Settings > Logos Reftagger and make sure the script is allowed to search that kind of text.

= See https://www.logos.com/faq#reftagger for more FAQs =

== changelog ==
2.5.6
* Upgrade to WordPress 6.4.2
* Update URLs in documentation

2.4.5:
 * Update settings text
 * Rename plugin to Logos Reftagger

2.4.4:
 * Upgrade to WordPress 5.8

2.4.3:
 * Upgrade to WordPress 5.6

2.4.2:
 * Replace protocol-relative url with explicit https.

2.4.1:
 * Misc. security improvements.
 * Moved externally hosted images to the plugin.
 * Update readme with Reftagger API service documentation.

2.4.0:
 * Upgrade to WordPress 5.5
 * Use HTTPS for links.

2.3.0:
 * Upgrade to WordPress 5.3
 * Fix undefined index errors when running in debug mode.
 * Fix Logos Bible Software link icon being enabled by default.

2.2.1:
 * Fix stable tag

2.2.0:
 * Upgrade to WordPress 5.2
 * Fix bug in saving 'Links open in' setting persisting on 'New window'

== screenshots ==

Please see https://www.logos.com/reftagger for screenshots.
