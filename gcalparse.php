<?php
/*
Plugin Name: Google Calendar Feed Parser
Plugin URI: http://jmbennett.org/2008/06/21/google-calendar-feed-parser/
Description: Parses a Google Calendar XML feed for display in the sidebar of your blog.
Version: 0.3
Author: Justin Bennett
Author URI: http://jmbennett.org
*/

/*  Copyright 2008  Justin M. Bennett  (email : bennettj1087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Hook for adding admin menu
add_action("admin_menu", "gcal_add_menus");

function gcal_add_menus() {
  //add_submenu_page("settings.php", "Google Calendar Feed Parser", "Google Calendar", 10, 
  //"gcal_parser", "gcal_display_menu");

  add_options_page("Google Calendar Feed Parser", "Google Calendar", 10, "gcal_admin", "gcal_display_menu");
}

/**
 * gcal_display_menu - Displays the admin menu
 */
function gcal_display_menu() {
$option = get_option('gcal_static_url_option');
?>

<form action="options.php" method="post">
<div class="wrap">
	<h2>Google Calendar Feed Parser Settings</h2>
	<?php wp_nonce_field('update-options'); ?>
	<p class="submit"><input type="submit" name="Submit" value="Save Changes" /></p>
	<table class="form-table">
		<tr>
			<th scope="row" valign="top">Feed URL:</th>
			<td>
			<input type="text" name="gcal_feed_url" value="<?php echo get_option('gcal_feed_url'); ?>" style="width: 600px" />
			<br />The URL for the Google Calendar Feed. Don't specify a "max-results" parameter in your query string,
			instead, set it below.</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Static URL?:</th>
			<td>
			<select name="gcal_static_url_option">
				<option value="0" <?php echo ($option == 0) ? 'selected="selected"' : ''; ?>>No</option>
				<option value="1" <?php echo ($option == 1) ? 'selected="selected"' : ''; ?>>Yes</option>
			</select>
			<input type="text" name="gcal_static_url" value="<?php echo get_option('gcal_static_url'); ?>" style="width: 300px" />
			<br />If set to "Yes", the plugin will link to the static url you provide for each calendar event.  
				  If set to "No", the plugin will link to the event's URL from the feed.</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Max Results:</th>
			<td>
			<input type="text" name="gcal_max_results" value="<?php echo get_option('gcal_max_results'); ?>" style="width: 20px" />
			<br />The maximum number of events to retrieve and display.  If left blank, the default is 4.</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Timezone offset:</th>
			<td>
			<input type="text" name="gcal_timezone_offset" value="<?php echo get_option('gcal_timezone_offset'); ?>" style="width: 70px" /> seconds
			<br />Offset to apply to start and end times from XML feed (default: 7200 seconds).  Only change if you're having problems with times not displaying correctly.</td>
		</tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="gcal_feed_url,gcal_static_url_option,gcal_static_url,gcal_max_results,gcal_timezone_offset" />
	<p class="submit">
	<input type="submit" name="Submit" class="button" value="Save Changes" />
	</p>
</div>
</form>

<?php
}


/**
 * gcal_parse_feed - Main function parses and displays the calendar feed
 */
function gcal_parse_feed() {
	$feed_url = get_option('gcal_feed_url');
	$feed_url .= '&max-results=' . (( get_option('gcal_max_results') == '' ) ? '4' : get_option('gcal_max_results'));
	
	$xmlstr = wp_remote_fopen($feed_url);
	$static_url = get_option('gcal_static_url_option');

	$xml = new SimpleXMLElement($xmlstr);

	echo '<div id="events">';
	foreach($xml->entry as $entry) {
		echo '<div class="event">';
		
		$gd = $entry->children('http://schemas.google.com/g/2005');

		$event_link = $entry->link->attributes()->href;

		if ( $static_url ) {
			echo '<h3><a href="' . get_option('gcal_static_url') . '">' . $entry->title . "</a></h3>\n";
		}
		else {
			echo '<h3><a href="' . $event_link  . '">' . $entry->title . "</a></h3>\n";
		}

		if (($offset = get_option('gcal_timezone_offset')) == '')
		   $offset = 7200;

		$start = date("l, F j \\f\\r\o\m g:ia", strtotime($gd->when->attributes()->startTime) + $offset);
		$end = date("g:ia", strtotime($gd->when->attributes()->endTime) + $offset);

		echo "<p class='event_time'>$start to $end</p></div>";
	}
	echo '</div>';
}
?>
