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
 * Title: Links Module
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
/* The module is loaded into PFX in many different instances, the variable */
/* $pfx_do is used to run the module in different ways. */
switch ($pfx_do) {
	/* General information : */
	/* The general information is used to show information about the module within PFX. */
	/* Simply enter details of your module here : */
	case 'info' :
		/* The name of your module */
		$pfx_m_name          = ucfirst($pfx_m_n);
		/* A description of your module */
		$pfx_m_description   = "Store a collection of {$pfx_m_n} on your website and group them by tag.";
		/* Who is the module author? */
		$pfx_m_author        = 'Scott Evans';
		/* What is the URL of your homepage */
		$pfx_m_url           = 'http://www.toggle.uk.com';
		/* What version is this? */
		$pfx_m_version       = 1.3;
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
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,`link_title` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',`tags` varchar(200) collate " . PFX_DB_COLLATE . " NOT NULL default '',`url` varchar(255) collate " . PFX_DB_COLLATE . " NOT NULL default '',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`top_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`lower_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`open_{$pfx_m_n}_in_new_tabs` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'no',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
		$pfx_execute2 = "INSERT INTO `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id`) VALUES (1);";
		break;
	/* The administration of the module (add, edit, delete) */
	/* This is where PFX really saves you time, these few lines of code will create the entire admin interface */
	case 'admin' :
		/* The name of your module */
		$pfx_module_name    = ucfirst($pfx_m_n);
		/* The name of the table */
		$pfx_table_name     = "pfx_module_{$pfx_m_n}";
		/* The field to order by in table view */
		$pfx_order_by       = 'link_title';
		/* Ascending (asc) or decending (desc) */
		$pfx_asc_desc       = 'asc';
		/* Fields you want to exclude in your table view */
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id",
			'tags'
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id"
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 15;
		/* Does this module support tags (yes or no) */
		$pfx_tags           = 'yes';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/* The three sections below are all for the module output, a module is loaded at three different stages of a page build. */
	/* Pre */
	/* Any code to be run before HTML output, any redirects or header changes must occur here */
	case 'pre' :
		/* Lets have a look at $pfx_m to see what we are trying to get out of the page */
		switch ($pfx_m) {
			/* OK so the visitor has come along to www.mysite.com/links/tag/something lets show them all links tagged "something" */
			case 'tag':
				/* We need $pfx_x to be a valid variable so lets check it */
				$pfx_x  = squash_slug($pfx_x);
				$pfx_rz = safe_rows('*', "pfx_module_{$pfx_m_n}", "tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]'");
				if ($pfx_rz) {
					/* We have found a entry tagged "something" to lets change the page title to reflect that */
					/* First lets get the current sites title */
					$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
					/* $pfx_ptitle will overwrite the current page title */
					$pfx_ptitle     = "{$pfx_site_title} - " . ucfirst($pfx_m_n) . " - Tagged - {$pfx_x}";
				} else {
					/* No tags were found, lets redirect back to the defualt view again. */
					/* CreateURL is your friend... its one of the most useful functions in PFX */
					if (isset($pfx_s)) {
						$pfx_redirect = createURL($pfx_s);
						header("Location: {$pfx_redirect}");
					}
					exit();
				}
				break;
			default:
				/* By default this module is called the links module, PFX will work this out for us so I do not need */
				/* to set $pfx_ptitle here. PFX will always TRY and create a unique, accurate page title if one is not set. */
				break;
		}
		$pfx_open_links_in_new_tabs = fetch("open_{$pfx_m_n}_in_new_tabs", "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_l_top_descr = fetch('top_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_l_lower_descr = fetch('lower_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		break;
	/* Head */
	/* This will output code into the end of the head section of the HTML, this allows you to load in external CSS, etc */
	case 'head' :
		break;
	/* Show Module */
	/* This is where your module will output into the content div on the page */
	default :
		/* Switch $pfx_m (our second variable from the URL) and adjust ouput accordingly */
		switch ($pfx_m) {
			/* $pfx_m is set to tag the we want to filter our links page to only check this tag */
			case 'tag' :
				if ($pfx_x) {
					/* Turn $pfx_x back into a tag from a slug */
					$pfx_x = squash_slug($pfx_x);
					if (isset($pfx_s)) {
						$pfx_core_page_name = safe_row('*', 'pfx_core', "page_name = '{$pfx_s}'");
						extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
						$pfx_core_page_name = NULL;
					}
					/* Find all the links with a matching tag to $pfx_x */
					$pfx_rz = safe_rows('*', "pfx_module_{$pfx_m_n}", "tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]'");
					if ($pfx_rz) {
						echo '<div ';
						if (isset($pfx_s)) {
							echo "id=\"{$pfx_s}\"";
						}
						echo ">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n<div id=\"{$pfx_m_n}-top-desc\">{$pfx_l_top_descr}</div>";
						$pfx_num = count($pfx_rz);
						echo "\t\t\t\t\t<div id=\"{$pfx_x}\" class=\"link_list\">\n\t\t\t\t\t\t<h4>" . ucwords($pfx_x) . "</h4>\n\t\t\t\t\t\t<ul>\n";
						$pfx_i = 0;
						/* Now loop out the results */
						while ($pfx_i < $pfx_num) {
							$pfx_out        = $pfx_rz[$pfx_i];
							$pfx_url        = $pfx_out['url'];
							$pfx_link_title = $pfx_out['link_title'];
							if ($pfx_open_links_in_new_tabs == 'yes') {
								echo "\t\t\t\t\t\t\t<li><a target=\"_blank\" href=\"{$pfx_url}\" title=\"{$pfx_link_title}\">{$pfx_link_title}</a></li>\n";
							} else {
								echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_url}\" title=\"{$pfx_link_title}\">{$pfx_link_title}</a></li>\n";
							}
							$pfx_i++;
						}
						echo "<li style=\"display:none;\"></li>"; /* Prevent invalid markup if the list is empty */
						echo "\n\t\t\t\t\t\t</ul>\n\t\t\t\t\t</div>\n";
						echo "<br /><div id=\"{$pfx_m_n}-lower-desc\">{$pfx_l_lower_descr}</div>";
						echo "\t\t\t\t</div>\n";
					}
				}
				break;
			default:
				/* Get the page display name from the database */
				if (isset($pfx_s)) {
					$pfx_core_page_name = safe_row('*', 'pfx_core', "page_name = '{$pfx_s}'");
					extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
					$pfx_core_page_name = NULL;
				}
				/* Echo the display name into a h3 */
				echo '<div ';
				if (isset($pfx_s)) {
					echo "id=\"{$pfx_s}\"";
				}
				echo ">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n<div id=\"{$pfx_m_n}-top-desc\">{$pfx_l_top_descr}</div>";
				/* Get all the tags from the links page using the all_tags function within PFX */
				$pfx_tags_array = all_tags("pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '0'");
				/* Make sure we actually got something */
				if (count($pfx_tags_array) != 0) {
					/* Sort the tags in the array */
					sort($pfx_tags_array);
					$pfx_max = 0;
					/* Begin to loop the array of tags */
					for ($pfx_c = 1; $pfx_c < (count($pfx_tags_array)); $pfx_c++) {
						/* Get the current tag */
						$pfx_current = $pfx_tags_array[$pfx_c];
						/* Search for links tagged with the current tag */
						$pfx_rz      = safe_rows('*', "pfx_module_{$pfx_m_n}", "tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
						$pfx_num     = count($pfx_rz);
						/* If found then output all those links into an unordered list */
						if ($pfx_rz) {
							echo "\t\t\t\t\t<div id=\"{$pfx_current}\" class=\"link_list\">\n\t\t\t\t\t\t<h4>" . ucwords($pfx_current) . "</h4>\n\t\t\t\t\t\t<ul>\n";
							$pfx_i = 0;
							while ($pfx_i < $pfx_num) {
								$pfx_out        = $pfx_rz[$pfx_i];
								$pfx_url        = $pfx_out['url'];
								$pfx_link_title = $pfx_out['link_title'];
								if ($pfx_open_links_in_new_tabs == 'yes') {
									echo "\t\t\t\t\t\t\t<li><a target=\"_blank\" href=\"{$pfx_url}\" title=\"{$pfx_link_title}\">{$pfx_link_title}</a></li>\n";
								} else {
									echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_url}\" title=\"{$pfx_link_title}\">{$pfx_link_title}</a></li>\n";
								}
								$pfx_i++;
							}
							echo "<li style=\"display:none;\"></li>"; /* Prevent invalid markup if the list is empty */
							echo "\n\t\t\t\t\t\t</ul>\n\t\t\t\t\t</div>\n";
						}
					}
				}
				echo "<br /><div id=\"{$pfx_m_n}-lower-desc\">{$pfx_l_lower_descr}</div>";
				echo '</div>';
				break;
		}
		break;
}