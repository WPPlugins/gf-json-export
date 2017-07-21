=== GF JSON Export Add-On ===
Contributors: fissionstrategy
Tags: forms, export, json
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin for exporting Gravity Forms entries in JSON format.

== Description ==
This is a WordPress plugin that adds the capability to export Gravity Forms entries in JSON format.

= Usage =
Go to Forms > JSON Export in the WordPress admin to view the page to initiate an export. You have the options of choosing which form to export, start date, end date, and output method.

= Translations =
Please email [dev@fissionstrategy.com](mailto:dev@fissionstrategy.com) if you wish to volunteer translating strings in the plugin.

== Installation ==

You can either install this plugin automatically from the WordPress admin, or do it manually:

1. Unzip the archive and put the gravityforms-json-export folder into your plugins folder (/wp-content/plugins/).
2. Activate the plugin from the Plugins menu.

== Frequently Asked Questions ==
= Why does exporting entries result in an error? =
Sometimes exporting a large amount of entries can result in an error. This is usually caused by a PHP memory limit or timeout. If possible, try increasing the memory_limit and max_execution_time settings in your PHP configuration. An alternative is to export entries in batches using smaller date ranges.

== Changelog ==

= 1.0 =
* Stable release.