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
 * Title: Downloads Module - With Hit Counter
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
		$pfx_m_description   = "Store a collection of files on your website and present them for download.";
		/* Who is the module author? */
		$pfx_m_author        = 'Tony White';
		/* What is the URL of your homepage */
		$pfx_m_url           = 'http://heydojo.co.cc';
		/* What version is this? */
		$pfx_m_version       = 1.0;
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
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,`title` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',`file` varchar(300) collate " . PFX_DB_COLLATE . " NOT NULL default '',`download_ref` varchar(300) collate " . PFX_DB_COLLATE . " default '',`image` varchar(300) collate " . PFX_DB_COLLATE . " default '',`description` LONGTEXT collate " . PFX_DB_COLLATE . " NOT NULL default '',`tags` varchar(200) collate " . PFX_DB_COLLATE . " default '',`license_name` varchar(150) collate " . PFX_DB_COLLATE . " default '',`license_url` varchar(255) collate " . PFX_DB_COLLATE . " default '',`published` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`checksum` varchar(255) collate " . PFX_DB_COLLATE . " default '',`hits` varchar(255) collate " . PFX_DB_COLLATE . " default '0',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`top_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`lower_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`show_tags` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'no',`show_checksum` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`use_{$pfx_m_n}_css_file` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`last_ip` varchar(15) collate " . PFX_DB_COLLATE . " NOT NULL default '0.0.0.0',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
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
		$pfx_order_by       = 'title';
		/* Ascending (asc) or decending (desc) */
		$pfx_asc_desc       = 'asc';
		/* Fields you want to exclude in your table view */
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id",
			'tags',
			'file',
			'download_ref',
			'license_url',
			'license_name',
			'description',
			'image',
			'checksum'
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id",
			'download_ref',
			'hits',
			'checksum'
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 25;
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
		$pfx_downloads_lang = array(
				  'download_hint' => 'Download',
				  'license_hint' => 'is distributed under the terms of the',
				  'download_checksum' => 'The sha256 checksum for this file is : ',
				  'no_downloads' => 'Nothing available to download.'
				);
		/* Lets have a look at $pfx_m to see what we are trying to get out of the page */
		switch ($pfx_m) {
			case 'file' :
				if ($pfx_x) {
					$pfx_x = sterilise($pfx_x);
					$pfx_last_ip = fetch('last_ip', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
					if ($pfx_last_ip !== $_SERVER['REMOTE_ADDR']) {
						safe_update("pfx_module_{$pfx_m_n}", "hits = hits+1", "{$pfx_m_n}_id='{$pfx_x}'");
						safe_update("pfx_module_{$pfx_m_n}_settings", "last_ip='{$_SERVER['REMOTE_ADDR']}'", "{$pfx_m_n}_id='1'");
					}
					$pfx_file = fetch('file', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id", $pfx_x);
					$pfx_location = getThing($pfx_query = "SELECT file_type FROM " . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_file}'");
					$pfx_file = getThing($pfx_query = "SELECT file_name FROM " . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_file}'");
					$pfx_file = downloads_location($pfx_location, $pfx_file);
					exit( header("Location: {$pfx_file}") );
				}
				break;
			/* OK so the visitor has come along to www.mysite.com/downloads/tag/something lets show them all downloads tagged "something" */
			case 'tag' :
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
			default :
				/* By default this module is called the downloads module, PFX will work this out for us so I do not need */
				/* to set $pfx_ptitle here. PFX will always TRY and create a unique, accurate page title if one is not set. */
				break;
		}
		$pfx_d_top_descr = fetch('top_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_d_lower_descr = fetch('lower_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		break;
	/* Head */
	/* This will output code into the end of the head section of the HTML, this allows you to load in external CSS, JavaScript etc */
	case 'head' :
		    $pfx_use_downloads_css_file = fetch("use_{$pfx_m_n}_css_file", "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		    if ($pfx_use_downloads_css_file === 'yes') {
			    echo "<link rel=\"stylesheet\" href=\"{$pfx_rel_path}admin/modules/css/{$pfx_m_n}.css\" type=\"text/css\" media=\"screen\" />";
		    }
		break;
	/* Show Module */
	/* This is where your module will output into the content div on the page */
	default :
		/* Switch $pfx_m (our second variable from the URL) and adjust ouput accordingly */
		switch ($pfx_m) {
			/* $pfx_m is set to tag the we want to filter our downloads page to only check this tag */
			case 'tag' :
				if ($pfx_x) {
					/* Turn $pfx_x back into a tag from a slug */
					$pfx_x = squash_slug($pfx_x);
					if (isset($pfx_s)) {
						$pfx_core_page_name = safe_row('*', 'pfx_core', "page_name = '{$pfx_s}'");
						extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
						$pfx_core_page_name = NULL;
					}
					/* Find all the downloads with a matching tag to $pfx_x */
					$pfx_rz = safe_rows('*', "pfx_module_{$pfx_m_n}", "tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]' AND published = 'yes'");
					if ($pfx_rz) {
						echo '<div ';
						if (isset($pfx_s)) {
							echo "id=\"{$pfx_s}\"";
						}
						echo ">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n<div id=\"{$pfx_m_n}-top-desc\">{$pfx_d_top_descr}</div>";
						$pfx_num = count($pfx_rz);
						echo "\t\t\t\t\t<div id=\"{$pfx_x}\" class=\"download_list\">";
						$pfx_i = 0;
						/* Now loop out the results */
						while ($pfx_i < $pfx_num) {
							downloads_output($pfx_rz, $pfx_i, $pfx_downloads_lang, $pfx_m_n);
							$pfx_i++;
						}
						echo "\n\t\t\t\t\t\t</div>\n<br /><div id=\"{$pfx_m_n}-lower-desc\">{$pfx_d_lower_descr}</div>\t\t\t\t</div>\n";
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
				echo ">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n<div id=\"{$pfx_m_n}-top-desc\">{$pfx_d_top_descr}</div><div class=\"download_list\">";
				/* Search for downloads tagged with the current tag */
				$pfx_rz      = safe_rows('*', "pfx_module_{$pfx_m_n}", "published = 'yes'");
				$pfx_num     = count($pfx_rz);
				/* If found then output all the files */
				if ($pfx_rz) {
					$pfx_i = 0;
					while ($pfx_i < $pfx_num) {
						downloads_output($pfx_rz, $pfx_i, $pfx_downloads_lang, $pfx_m_n);
						$pfx_i++;
					}
				} else {
					echo "<span class=\"error\">{$pfx_downloads_lang['no_downloads']}</span>";
				}
				echo '</div>';
				$pfx_show_tags = fetch('show_tags', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
				if ($pfx_show_tags == 'yes') {
					echo '<div class="tag-cloud"><h4 id="h4-tags">Tags:</h4><div class="tcloud">';
					public_tag_cloud("pfx_module_{$pfx_m_n}", "published = 'yes'", $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
					echo '</div></div>';
				}
				echo "<div id=\"{$pfx_m_n}-lower-desc\">{$pfx_d_lower_descr}</div>\t\t\t\t\t</div>";
				break;
		}
		break;
}