=== Protect WordPress Uploads ===
Contributors: csorbamedia, mauwen
Tags: secure downloads, protection, uploads, uploads folder
Requires at least: 4.8
Tested up to: 5.6.2
Requires PHP: 5.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Stable tag: trunk

This plugin can be used to prevent direct access to WordPress uploads.

== Description ==

Protect WordPress Uploads is a simple and easy way to protect WordPress uploads.

Seamlessly integrated, you can easily protect your WordPress uploads by just one single click. Once protected, they cannot be accessed directly through their original, unprotected links (URLs). Unwanted users will be redirected.

[youtube https://www.youtube.com/watch?v=9m9hQKdtn-c]

== Features ==

- Unlimited protected WordPess Uploads.
- Files are not indexed in Google or any other search engine.
- Filter by private uploaded files in the Media Library.
- Works with Apache and NGINX.
- Easy upload, protect and unprotect your WordPress Uploads.
- ACF-filter available.
- Available in 7 languages and counting.

== Available languages ==

* English
* Spanish (thanks to @yordansoares)
* Russian
* Japanese (thanks to @nao)
* Dutch

== Installation ==

=== From within WordPress ===

1. Visit 'Plugins > Add New'
2. Search for 'Protect WordPress Uploads'
3. Activate 'Protect WordPress Uploads' from your Plugins page.
4. Go to "after activation" below.

=== Manually ===

1. Upload the 'wp-private-media' folder to the '/wp-content/plugins/' directory
2. Activate the 'Protect WordPress Uploads' plugin through the 'Plugins' menu in WordPress
3. Go to "after activation" below.

=== After activation ===

1. You should see the menu item 'Protect WP Files' in the admin menu.
2. On the wp-admin/upload.php page, you should see an extra admin column called URL.

==== IMPORTANT STEP IF YOU USE NGINX ====

3. If you use NGINX as WEBSERVER, add a rewrite to your NGINX config file. Look into the folder _rewrites for an example.

== Frequently Asked Questions ==

If there are any questions please don't hesitate to send them to support@mauwen.com.

= How can I control which role can upload protected files? =

We added a capability 'manage_pwpf_files'. You can assign this capability to any role in WordPress by using a roles & capabilities plugin.

= If the file is protected is it possible to unprotect? =

Yes, there is an option to unprotect files. The file will be moved to the public wp-uploads/ folder.

= Can everyone download protected files? =

No, only logged in users can download files.

= Is there a role restriction who can and who cannot download? =

Currently, all users with any role could download protected files.

= Is the file encrypted? =

No, not yet but we are working on that.

== Screenshots ==

1. Protect WordPress Uploads introduction.
2. Upload a protected upload.
3. Filter by protected uploads.
4. Download restriction screen.
5. Attachment copy protected link.

== Filters ==

For more information on how to use the filters please go to [the plugin explanation page](https://www.mauwen.com/docs/protect-wordpress-uploads/hooks-filters/)

= Plugin / Theme Support =

* Plugin: ["Advanced Custom Fields" (free, by Elliot Condon)](https://wordpress.org/plugins/advanced-custom-fields/)

== Changelog ==

= 1.2.2.8 =
- Removed settings, introduction page for editors role.
- Removed renaming function for admin menu.
- Removed nginx message for non-administrators.

= 1.2.2.7 =
- Added capability for editor role
- Small bug fix in admin menu for other roles than administrator

= 1.2.2.6 =
- Added check and error message for WP sites with plain permalink structure
- Small text-domain changes
- Small picture / design changes

= 1.2.2.5 =
- Tested with WP 5.6.1
- Small fix, issue with WP Forms.

= 1.2.2.4 =
- Added documentation link.
- Updated some links to new website mauwen.com

= 1.2.2.3 =
- Update, settings page, linkback url added for protection message.
- Small warning fix in PWPF_url_by_slug() function, thanks to @mreisphotography.

= 1.2.2.2 =
- Fix for slug for backend grid view thumbs.

= 1.2.2.1 =
- Fix for slug for backend thumbs.

= 1.2.2 =
- Grid and List view thumbnails added.
- Settings page added to change the protection message.
- Changed the wp-admin upload icon to SVG.
- Fix for downloading large files PWPF_handle_private_download().
- Fix remove image sizes in the private folder after unprotect the file.

= 1.2.1 =
- Small fix on a translation, wrong text domain

= 1.2 =
- Changed site_url() to get_bloginfo('url').
- Small code changes in pwpf-messages.php.
- Added media library filter to filter by protected uploads.
- Added support page.
- Some changes on the wizard page.
- Moved wizard template from function to admin-templates folder.

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
Small bug fix for filter - private_media_url_by_array.
It always returned the file url when using the filter without checking if the file is protected or not.

= 1.0.1 =
Small bug fix for remove _nginx message in admin.

= 1.0.0 =
First realease on May 17th, 2019
