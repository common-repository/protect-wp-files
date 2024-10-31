### Protect WordPress Files

Contributors: Csorba Media<br>
Donate link: https://www.csorbamedia.com<br>
License: GPLv3<br>
License URI: http://www.gnu.org/licenses/gpl.html<br>
Tags: protect files, files protection, member area protection, prevent direct access, block direct access, downloads restriction<br>
Requires at least: 4.8<br>
Tested up to: 5.6<br>
Tested on webservers: Apache 2+ and NGINX<br>
Requires PHP: 5.6+<br>

This plugin can be used to prevent direct access to files for example in your member area on your WordPress website.

== Description ==

### Protect WordPress Files : Prevent direct access to uploads

Protect WordPress Files by CSORBA is a simple and easy way to protect WordPress files.

Seamlessly integrated, you can easily protect your WordPress Files by just one single click. Once protected, they cannot be accessed directly through their original, unprotected links (URLs). Unwanted users will be redirected.

#### Taking care of your Private WordPress uploads

* You can protect any file with the plugin.
* The plugin comes with a filter to change the URL in your custom content. For example if you use ACF Fields in your website.
* The plugin automatically change private links to the right URL with the the_content hook.
* **[Premium]** OpenSSL AES files encryption (requires PHP 5.6+)
* **[Premium]** Libsodium files encryption (requires PHP 7.2+)

> Note: some features are Premium. Which means you need CSORBA Premium to unlock those features. You can [get CSORBA Premium here](https://www.csorbamedia.com/wordpress-plugins/)!

### Premium support

The CSORBA team does not always provide active support for the plugin on the WordPress.org forums, as we prioritize our email support. One-on-one email support is available to people who [bought CSORBA Premium](https://www.csorbamedia.com/wordpress-plugins/) only.

### Bug reports

Bug reports for our plugins can be send to support@csorbamedia.com.

== Installation ==

=== From within WordPress ===

1. Visit 'Plugins > Add New'
2. Search for 'Protect WordPress Files'
3. Activate 'Protect WordPress Files' from your Plugins page.
4. Go to "after activation" below.

=== Manually ===

1. Upload the `wp-private-media` folder to the `/wp-content/plugins/` directory
2. Activate the 'Protect WordPress Files' plugin through the 'Plugins' menu in WordPress
3. Go to "after activation" below.

=== After activation ===

1. You should see the menu item 'Protect WP Files' in the admin menu.
2. On the wp-admin/upload.php page you should see an extra admin column called URL.

==== IMPORTANT STEP ====

3. If you use NGINX as WEBSERVER, add a rewrite to your NGINX config file. Look into the folder _rewrites for an example.

== Frequently Asked Questions ==

If there are any questions please don't hesitate to send them to support@csorbamedia.com.

== Screenshots ==

![ScreenShot](https://raw.github.com/csorbamedia/wp-private-media/master/assets/screenshots/screenshot1.png)

== Filters ==

For more information how to use the filters please go to [the plugin explanation page](https://www.csorbamedia.com/website-beveiliging/nieuwe-plugin-protect-wordpress-files/)

= Plugin / Theme Support =

* Plugin: ["Advanced Custom Fields" (free, by Elliot Condon)](https://wordpress.org/plugins/advanced-custom-fields/)

== Changelog ==

= 1.1 =
Added unprotect function.

= 1.0.9 =
Capability "manage_pwpf_files" moved to init.

= 1.0.8 =
Capability "manage_pwpf_files" added.

= 1.0.7 =
Small fix for the introduction page.

= 1.0.6 =
Updated the upload UX/UI design with upload progressbar.
Added upload-check for supported WordPress mimetypes.
Added introduction page.
Moved js/css to assets folders.

= 1.0.5 =
Added Dutch Translation.

= 1.0.4 =
Fixed small bug in ACF support filter.

= 1.0.3 =
ACF support with upload filter.

= 1.0.2 =
Small bug fix for filter - private_media_url_by_array

= 1.0.1 =
Small bug fix for remove _nginx message in admin.

= 1.0.0 =
Release Date: May 13th, 2019
