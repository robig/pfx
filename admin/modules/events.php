<?php
if ( !defined('DIRECT_ACCESS') ) {
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
 * Title: Events Module
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
	/* General information: */
	/* The general information is used to show infromation about the module within PFX. */
	case 'info' :
		/* The name of your module */
		$pfx_m_name          = ucfirst($pfx_m_n);
		/* A description of your module */
		$pfx_m_description   = ucfirst($pfx_m_n) . ' module with support for hCalendar microformat, archives and Google calendar links.';
		/* Who is the module author? */
		$pfx_m_author        = 'Scott Evans';
		/* What is the URL of your homepage */
		$pfx_m_url           = 'http://www.toggle.uk.com';
		/* What version is this? */
		$pfx_m_version       = '1.2';
		/* Can be set to module or plugin. */
		$pfx_m_type          = 'module';
		/* Is this a module that needs publishing to? */
		$pfx_m_publish       = 'yes';
		/* Put this module in the navigation by default? */
		$pfx_m_in_navigation = 'yes';
		break;
	/* Install */
	/* This section contains the SQL needed to create your modules tables */
	case 'install' :
		/* Create any required tables */
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(5) NOT NULL auto_increment,`date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,`title` varchar(100) collate " . PFX_DB_COLLATE . " NOT NULL default '',`description` longtext collate " . PFX_DB_COLLATE . ",`location` varchar(120) collate " . PFX_DB_COLLATE . " default NULL,`url` varchar(140) collate " . PFX_DB_COLLATE . " default NULL,`public` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',PRIMARY KEY  (`{$pfx_m_n}_id`),UNIQUE KEY `id` (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`google_calendar_links` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default '',`number_of_{$pfx_m_n}` varchar(3) collate " . PFX_DB_COLLATE . " NOT NULL default '10',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute2 = "INSERT INTO `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id`, `google_calendar_links`, `number_of_{$pfx_m_n}`) VALUES (1, 'yes', '10');";
		/* You can execute upto 5 sql queries ($pfx_execute - $pfx_execute4)  */
		break;
	/* The administration of the module (add, edit, delete) */
	/* This is where PFX really saves you time, these few lines of code will create the entire admin interface */
	case 'admin' :
		/* The name of your module */
		$pfx_module_name    = ucfirst($pfx_m_n);
		/* The name of the table */
		$pfx_table_name     = "pfx_module_{$pfx_m_n}";
		/* The field to order by in table view */
		$pfx_order_by       = 'date';
		/* Ascending (asc) or decending (desc) */
		$pfx_asc_desc       = 'desc';
		/* Fields you want to exclude in your table view */
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id",
			'description',
			'cost',
			'location',
			'public',
			'url'
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id"
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 15;
		/* Does this module support tags (yes or no) */
		$pfx_tags           = 'no';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/* Pre */
	/* Any code to be run before HTML output, any redirects or header changes must occur here */
	case 'pre' :
		/* Get the details of this page from pfx_core */
		if (isset($pfx_s)) {
			$pfx_core_page_name  = safe_row('*', 'pfx_core', "page_name='{$pfx_s}'");
			extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
			$pfx_core_page_name = NULL;
		}
		/* Get the settings of the page from its settings table */
		$pfx_mod_events_sets  = safe_row('*', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id='1'");
		extract($pfx_mod_events_sets, EXTR_PREFIX_ALL, 'pfx');
		$pfx_mod_events_sets = NULL;
		switch ($pfx_m) {
			case 'archives' :
				$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
				$pfx_ptitle     = "{$pfx_site_title} - {$pfx_page_display_name} - Archives";
				$pfx_rs         = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '0'  and date < now() and public = 'yes' order by date desc");
				break;
			default :
				$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
				$pfx_ptitle     = "{$pfx_site_title} - {$pfx_page_display_name}";
				$pfx_rs         = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '0'  and date > now() and public = 'yes' order by date asc limit {$pfx_number_of_events}");
				break;
		}
		break;
	/* Head */
	/* This will output code into the end of the head section of the HTML, this allows you to load in external CSS, JavaScript etc */
	case 'head' :
		/* You could place some css for layout here... Alternatively place a file called events.css in your theme folder and PFX will load it with this page automatically. */
		break;
	/* Show Module */
	/* This is where your module will output into the content div on the page */
	default :
		if (isset($pfx_s)) {
			echo "<div id=\"{$pfx_s}\">\n\t\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n";
			if ($pfx_rs) {
				while ($pfx_a = nextRow($pfx_rs)) {
					extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
					$pfx_a = NULL;
					$pfx_logunix     = returnUnixtimestamp($pfx_date);
					$pfx_dateis      = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
					$pfx_microformat = safe_strftime($pfx_lang, '%Y-%m-%dT%T%z', $pfx_logunix);
					$pfx_googtime    = safe_strftime($pfx_lang, '%Y%m%dT%H%M%SZ', $pfx_logunix);
					echo "<div class=\"vevent\"><h4 class=\"summary\" title=\"";
					if (isset($pfx_title)) {
						echo $pfx_title;
					}
					echo "\">";
					if (isset($pfx_title)) {
						echo $pfx_title;
					}
					echo "</h4><ul class=\"vdetails\"><p><li class=\"vtime\">Date: <abbr class=\"dtstart\" title=\"{$pfx_microformat}\"><b>{$pfx_dateis}</b></abbr></li>\n";
					if ($pfx_location) {
						echo "\t\t\t\t\t\t\t\t<li class=\"vlocation\">Venue: <span class=\"location\">{$pfx_location}</span></li>\n";
					}
					if ($pfx_url) {
						echo "\t\t\t\t\t\t\t\t<li class=\"vlink\">Link: <a class=\"url\" href=\"{$pfx_url}\">{$pfx_url}</a></li>\n";
					}
					if ($pfx_google_calendar_links == 'yes') {
						$pfx_url_desc = substr($pfx_description, 0, 24);
						/* Limit the description to 24 characters in length, so that a huge url is not sent. */
						echo "\t\t\t\t\t\t\t\t<li class=\"vgoogle\"><a href=\"http://www.google.com/calendar/event?action=TEMPLATE&amp;text=";
						if (isset($pfx_title)) {
							echo (urlencode($pfx_title));
						}
						echo "&amp;dates={$pfx_googtime}/{$pfx_googtime}&amp;details=" . urlencode(strip_tags($pfx_url_desc)) . "&amp;location=" . urlencode($pfx_location) . '&amp;trp=false&amp;sprop=' . PREFS_SITE_URL . "&amp;sprop=name:{$pfx_site_title}\"  target=\"_blank\">Add to Google calendar</a></li>\n";
					}
					echo "</p></ul><div class=\"event_body\"><p>";
					echo "\t\t\t\t\t\t\t\t" . str_replace('<p>', "<p class=\"description\">", $pfx_description);
					echo "</p></div></div>\n";
				}
				if (!isset($pfx_title)) {
					echo "<p class=\"error\">No {$pfx_m_n} found</p>";
				}
				if ($pfx_m == 'archives') {
					if (isset($pfx_s)) {
						echo "\t\t\t\t\t\t<a class=\"view_more_link\" href=\"" . createURL($pfx_s) . "\">View upcoming {$pfx_m_n}...</a>\n";
						/* Needs lannguage */
					}
				} else {
					if (isset($pfx_s)) {
						echo "\t\t\t\t\t\t<a class=\"view_more_link\" href=\"" . createURL($pfx_s, 'archives') . "\">View the archives...</a>\n";
						/* Needs lannguage */
					}
				}
			}
			echo '</div>';
		}
		break;
}