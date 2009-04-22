===Google Calendar Feed Parser===
Contributors: bennettj1087
Donate link: http://jmbennett.org/2008/06/21/google-calendar-feed-parser
Tags: google, calendar, xml, parser, feed
Requires at least: 2.5.1
Tested up to: 2.7.1
Stable tag: 0.3

This plugin parses and displays upcoming events from a Google Calendar XML feed.

== Description ==

This plugin will parse a Google Calendar XML Feed and display it on your blog.  Installation is simple
and configuring and using the plugin is easy as well.  Please visit [the plugin's webpage](http://jmbennett.org/2008/06/21/google-calendar-feed-parser) at the [author's webpage](http://jmbennett.org) for more information.

== Installation ==

1. Upload `gcalparse.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php gcal_parse_feed(); ?>` in your template wherever you want your calendar feed to be displayed.
1. Use the Google Calendar page of the WordPress Settings menu to configure the plugin.  See the plugin's webpage
for complete descriptions of each option.

== Screenshots ==

1. This is a shot of the plugin in action in the sidebar of a blog.

== Changelog ==

0.3: Switched from using file\_get\_contents() to retrieve URL (which isn't allowed by many hosting providers) to using the WordPress function wp\_remote\_fopen().  This function firsts attempts to use fopen() to get the file and, if that fails, proceeds to use cURL.

0.2: Add ability to configure custom time offsets.  This feature will be replaced with more comprehensive timezone controls in a future release.

0.1: Initial plugin release.
