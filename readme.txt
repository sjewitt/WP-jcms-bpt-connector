=== WP BPT ===

Contributors: smeghammer
Donate link: n/a
Tags: content embed, content connector,connector,http embed
Requires at least: 4.7
Tested up to: 4.8.3
Requires PHP: 7.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin to embed sanitised CMS content via HTTP using configured CMS server and a shortcode to generate the content URI.

== Description ==

A shortcode-based plugin that offers embedding of remote text content via HTTP. The intended use-case is for re-purposing of previously authored content from an existing but retired CMS system into a WordPress site.

The plugin allows configuration of a remote CMS server HTTP address and a content retrieval endpoint. Shortcodes can then specify content IDs. The server configuration plus shortcode allows the generation of URLs for content retrieval. The content identified by the generated URL is embedded in the template at the shortcode position.

This 'lite' version allows configuration of a single server connection, with the content expected to be provided by a parameterised URL (`cms-server/contentscript?contentid=1234`). The full version, available via a consultancy engagement, offers three configurable connections as well as path-based content retrieval. The full version also offers configurable content caching, based on retrieval URL, for performance.

Please note that a pre-requisite is that your CMS server must be configured to provide the plain text content.

== Installation ==

1. Unzip the plugin archive into /wp-content/plugins/ directory of your WordPress installation, or install the plugin through the WordPress plugins screen directly.
2. Enable in the plugins section of the wp-admin dashboard. There are no configuration options needed.


== Usage == 
 * Configure a server connection via the settings/JCMS Commector. Specify a connection name, your remote server, the content provision endpoint and required content identifying URL parameter. 
 * Add shortcode for rendering content by ID: Add shortcode `[jcms_connector_content_id id=nnn service=sss]` in a suitable location. The specified content from the remote CMS will be rendered.


== Frequently Asked Questions ==

= Do you offer retrieval of content from URLs of the form `cms-server/path/to/content1234`? =

Yes, using the full version of this plugin, available upon consultancy engagements.

= Does this plugin offer retrieval of content from a database source? =

No. This is the remit of an upcoming plugin, still being developed.

= Are there any limitations? =

 * Your legacy CMS must be able to expose content via a URL
 * Your WordPress deployment server must be able to reach the legacy CMS server via HTTP.
 * Remote resource URLs (images etc.) are not currently supported. The option to allow a configurable list of MIME types of remote resource URLs to be automatically generated may be added for a future version.




== Screenshots ==

1. View of admin settings for server root configuration.
2. View of shortcode in template with content ID specified.
3. View of source CMS with content in context.
4. View of source CMS with content exposed as raw text via a handler script. 
5. View of remote content embedded in WordPress template context.

== Changelog ==

= 1.0 =
* First version.

== Upgrade Notice ==

= 1.0 =
First Version.
