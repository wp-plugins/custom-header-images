=== Plugin Name ===
Contributors: blackbam
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DX9GDC5T9J9AQ
Tags: header, images, simple
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A very simple and lightweight Plugin for managing custom header images for pages, posts, archive-pages, and all other possible.

== Description ==

This plugin provides an easy, lightweight possibility to add header images to your blog. It is simple and clean. 
It is easy to understand, maintain and extend. It does not make use of extra tables or buggy built-in uploads.

Features:

* Simple and easy management of header images
* Each possible state is covered, by using all elements of the <a href="http://upload.wikimedia.org/wikipedia/commons/3/3d/Wordpress_Template_Hierarchy.png">WordPress template hierarchy</a>
* The Media Library is used for image management, images are saved by URL copy/paste (so external images can be used, too)
* Requires no extra tables
* Clean install/uninstall

Plugin URL: <a href="http://www.blackbam.at/blackbams-blog/2012/06/25/custom-header-images-plugin-for-wordpress">http://www.blackbam.at/blackbams-blog/2012/06/25/custom-header-images-plugin-for-wordpress</a>

== Installation ==

1. Upload the Plugin to your wp-content/plugins/ folder
2. Activate the Plugin
3. Go to Settings -&gt; Header Images and insert the image URLs (get the URLs from the media library)
4. Paste the following code into your theme: <code><?php if(function_exists('chi_display_header')) { chi_display_header(); } ?></code>

Where to set the image data:

* Go to Settings -> Header Images for general settings
* Meta Boxes at the bottom of post / page edit screen
* Category add/edit screen

== Frequently Asked Questions ==

Q: Is there support for custom post types and custom taxonomies?
A: This feature is currently under development, and will probably be available soon.

== Changelog ==

= 1.0.0 =
* Initial release.
