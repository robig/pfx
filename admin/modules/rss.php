<?php
if (!defined('DIRECT_ACCESS')) {
	exit( header('Location: ../../') );
}
/**
 * PFX: Pixie Fork Xtreme.
 * Copyright (C) 2010, Tony White
 *
 * Largely based on code derived from :
 *
 * Pixie: The Small, Simple, Site Maker.
 * 
 * Licence: GNU General Public License v3
 * Copyright (C) 2010, Scott Evans
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/
 *
 * Title: RSS Plugin
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
switch ($pfx_do) {
	/* General information */
	case 'info' :
		$pfx_m_name        = strtoupper($pfx_m_n) . ' Plugin';
		$pfx_m_description = 'Available ' . strtoupper($pfx_m_n) . ' feeds.';
		$pfx_m_author      = 'Scott Evans';
		$pfx_m_url         = 'http://www.toggle.uk.com/';
		$pfx_m_version     = 1.1;
		$pfx_m_type        = 'plugin';
		$pfx_m_publish     = 'yes';
		$pfx_m_in_navigation     = 'no';
		break;
	/* Install */
	case 'install' :
		if ( (defined('PREFS_CLEAN_URLS')) && (PREFS_CLEAN_URLS == 'yes') ) {
			$pfx_rss_plugin_url = PREFS_SITE_URL . '/blog/rss';
		} else {
			$pfx_rss_plugin_url = PREFS_SITE_URL . '?s=blog&amp;x=rss';
		}
		// create any required tables
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` tinyint(2) NOT NULL auto_increment,`feed_display_name` varchar(80) collate " . PFX_DB_COLLATE . " NOT NULL default '',`url` varchar(80) collate " . PFX_DB_COLLATE . " NOT NULL default '',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
		$pfx_execute1 = "INSERT INTO `pfx_module_rss` (`rss_id`, `feed_display_name`, `url`) VALUES (1, '" . PREFS_SITE_NAME . "', '{$pfx_rss_plugin_url}');";
		break;
	/* Pre (To be run before page load) */
	case 'pre' :
		break;
	/* Head (To be run in the head) */
	case 'head' :
		break;
	/* Admin of module */
	case 'admin' :
		$pfx_module_name  = "{$pfx_m_n}";
		$pfx_table_name   = "pfx_module_{$pfx_m_n}";
		$pfx_order_by     = 'feed_display_name';
		$pfx_asc_desc     = 'asc';
		$pfx_view_exclude = array(
			"{$pfx_m_n}_id"
		);
		$pfx_edit_exclude = array(
			"{$pfx_m_n}_id"
		);
		$pfx_tags         = 'no';
		/* The number of items per page in the table view */
		$pfx_items_per_page = 15;
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/* Show module */
	default :
		if (isset($pfx_s)) {
			$pfx_core_page_name = safe_row('*', 'pfx_core', "page_name = '{$pfx_s}'");
			extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
			$pfx_core_page_name = NULL;
		}
		echo '<div ';
		if (isset($pfx_s)) {
			echo "id=\"{$pfx_s}\"";
		}
		echo ">\t\t\t<h3>{$pfx_page_display_name}</h3>
	  				<h4 id =\"explain-{$pfx_m_n}\">Whats all this then?</h4>
	  				<p>" . strtoupper($pfx_m_n) . " or Really Simple Syndication, is a way of reading new content from websites. It allows you to keep informed of the latest developments
	  				without the need to constantly revisit a site. Most sites now offer this feature, to find out more have a read of the 
	  				<a href=\"http://en.wikipedia.org/wiki/RSS_(protocol)\" title=\"RSS @ Wikipedia\">Wikipedia entry</a> on RSS.</p>
	  				<h4>RSS Tools</h4>
	  				<p>Clicking on a feed should open it in your default feed reader. If you do not have a reader I recommend using <a href=\"http://www.google.com/reader/\" title=\"Google Reader\">
	  				Google's online</a> reader to get started. Once you get used to the idea try a software based solution. 
	  				For Apple users I recommend using <a href=\"http://newsfirerss.com/\" title=\"Newsfire RSS reader\">Newsfire</a>, or the built in RSS reader in <a href=\"http://www.apple.com/macosx/features/safari/\" title=\"Safari RSS reader\">Safari</a>. 
	  				Windows users try <a href=\"http://www.blogbridge.com\" title=\"BlogBridge RSS reader\">BlogBridge</a>, or <a href=\"http://www.rssowl.org/\" title=\"RSSOwl RSS reader\">RSSOwl</a>.</p>\n";
		$pfx_rs  = safe_rows('*', 'pfx_dynamic_settings', "{$pfx_m_n} = 'yes'");
		$pfx_num = count($pfx_rs);
		if ($pfx_rs) {
			include_once 'admin/lib/lib_simplepie.php';
			$pfx_number_of_items = 10;
			/* Set the maximum number of items here */
			/* Enter the URL of your RSS feed here */
			$pfx_show_errors     = 'no';
			$pfx_new_tab   = 'no'; /* Open the links in a new window or tab? */
			$pfx_cache_admin = 'no'; /* Cache path is relative showRss needs to know this */
			$pfx_i = 0;
			echo "\t\t\t\t\t<h4>Local Feeds</h4>
						<ul id=\"local_feeds\">\n";
			if (public_page_exists($pfx_m_n)) {
				$pfx_rs  = safe_rows_start('*', "pfx_module_{$pfx_m_n}", '1 order by feed_display_name desc');
				$pfx_num = count($pfx_rs);
				if ($pfx_rs) {
					while ($pfx_a = nextRow($pfx_rs)) {
						extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
						$pfx_a = NULL;
						echo "\t\t\t\t\t\t<h3><br /><a class=\"{$pfx_m_n}-subscribe\" href=\"{$pfx_url}\" title=\"{$pfx_url}\">{$pfx_feed_display_name}</a></h3>\n";
						showRss($pfx_number_of_items, $pfx_url, $pfx_show_errors, $pfx_new_tab, $pfx_cache_admin);
						$pfx_i++;
					}
				}
			} else {
				while ($pfx_i < $pfx_num) {
					$pfx_out     = $pfx_rs[$pfx_i];
					$pfx_page_id = $pfx_out['page_id'];
					$pfx_rs1     = safe_row('*', 'pfx_core', "page_id = '{$pfx_page_id}' limit 0,1");
					extract($pfx_rs1, EXTR_PREFIX_ALL, 'pfx');
					$pfx_rs1 = NULL;
					$pfx_rss_url = createURL($pfx_page_name, "{$pfx_m_n}");
					echo "\t\t\t\t\t\t<h3><br /><a class=\"{$pfx_m_n}-subscribe\" href=\"{$pfx_rss_url}\" title=\"" . PREFS_SITE_NAME . " - {$pfx_page_display_name}\">{$pfx_page_display_name}</a></h3>\n";
					showRss($pfx_number_of_items, $pfx_url, $pfx_show_errors, $pfx_new_tab, $pfx_cache_admin);
					$pfx_i++;
				}
			}
			echo '<li style="display:none;"></li><br /></ul>'; /* Prevent invalid markup if the list is empty */
		}
		echo '</div>';
		break;
}