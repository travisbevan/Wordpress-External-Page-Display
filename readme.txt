=== External Page Display ===
Contributors: Your Name
Tags: embed, broadcast, page display, content sharing
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display WordPress page content on external websites with a simple embed code.

== Description ==

External Page Display allows you to broadcast a WordPress page content to external websites using a simple JavaScript embed code. Perfect for sharing articles, reports, or any page content across multiple sites.

**Features:**

* Simple checkbox in the page editor to enable broadcasting
* Automatically generated embed code for each broadcast page
* Responsive design that works on any device
* Includes featured images automatically
* No iframe limitations - content displays directly on the page
* Clean, professional styling included
* REST API endpoint for secure content delivery
* No scrolling or sizing issues

**How to Use:**

1. Edit any WordPress page
2. In the sidebar, check "Yes, broadcast this page"
3. Save/Update the page
4. Copy the generated embed code from the sidebar
5. Paste the code into any webpage where you want the content to appear

The embed code fetches the page content via REST API and displays it with proper formatting and styling.

== Installation ==

1. Upload the `external-page-display` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to any page editor and look for the "Broadcast Page" meta box in the sidebar

== Frequently Asked Questions ==

= Does this work with any WordPress theme? =

Yes! The plugin works independently of your theme and includes its own styling.

= Can I customize the appearance? =

Yes, you can add custom CSS to override the default styles. All content is wrapped in `#external-broadcast-content` for easy targeting.

= Will the content update automatically? =

Yes! Whenever you update the WordPress page, the embedded content will automatically reflect those changes.

= Does this work on non-WordPress sites? =

Absolutely! The embed code is pure HTML and JavaScript, so it works on any website.

= What happens if I uncheck the broadcast option? =

The embed will stop displaying content and show an error message instead.

== Changelog ==

= 1.4 =
* Fixed CSS selector mismatches
* Added security nonces for meta box saving
* Improved error handling
* Made API URL dynamic instead of hardcoded
* Added CSS directly to embed code for better portability
* Improved permission checks
* Added validation for REST API parameters
* Better admin notices and user feedback
* Fixed readme.txt format

= 1.3 =
* Initial release

== Upgrade Notice ==

= 1.4 =
This version includes important security improvements and bug fixes. Upgrade recommended.
