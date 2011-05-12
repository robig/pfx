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
 * Title: A welcome module which can be used as a home page adapted from : http://tutorialzine.com/2009/11/beautiful-apple-gallery-slideshow/
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
		$pfx_m_description   = "{$pfx_m_name} visitors to your site with this landing page.";
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
		$pfx_m_in_navigation = 'no';
		break;
	/* Install */
	/* This section contains the SQL needed to create your modules tables */
	case 'install' :
		/* Create any required tables */
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,`title` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',`image` varchar(300) collate " . PFX_DB_COLLATE . " NOT NULL default '',`description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`url` varchar(255) collate " . PFX_DB_COLLATE . " default '',`published` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`top_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`lower_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`use_{$pfx_m_n}_css_file` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`use_{$pfx_m_n}_js_file` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
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
			'image',
			'url'
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id"
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 5;
		/* Does this module support tags (yes or no) */
		$pfx_tags           = 'no';
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
			default :
				/* By default this module is called the welcome module, PFX will work this out for us so I do not need */
				/* to set $pfx_ptitle here. PFX will always TRY and create a unique, accurate page title if one is not set. */
				break;
		}
		$pfx_w_top_descr = fetch('top_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_w_lower_descr = fetch('lower_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		break;
	/* Head */
	/* This will output code into the end of the head section of the HTML, this allows you to load in external CSS, etc */
	case 'head' :
		$pfx_welcome_css = fetch("use_{$pfx_m_n}_css_file", "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		if ($pfx_welcome_css === 'yes') {
		/*	Add the stylesheet to the head	*/
		    echo "<link rel=\"stylesheet\" href=\"{$pfx_rel_path}admin/modules/css/{$pfx_m_n}.css\" type=\"text/css\" media=\"screen\" />";
		}
		if (PREFS_JQUERY === 'no') {
			/* TODO : send an alert to the debug log */
		} else {
			$pfx_welcome_js = fetch("use_{$pfx_m_n}_js_file", "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
			if ($pfx_welcome_js === 'yes') {
				    define('THEME_JS', "<script type=\"text/javascript\" src=\"{$pfx_rel_path}admin/modules/js/{$pfx_m_n}.js\" charset=\"UTF-8\"></script>");
			}
		}
		break;
	/* Show Module */
	/* This is where your module will output into the content div on the page */
	default :
		/* Switch $pfx_m (our second variable from the URL) and adjust ouput accordingly */
		switch ($pfx_m) {
			default :
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
				echo ">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n<div id=\"{$pfx_m_n}-top-desc\">{$pfx_w_top_descr}</div>";
				$pfx_rz = safe_rows('*', "pfx_module_{$pfx_m_n}", "published = 'yes' order by {$pfx_m_n}_id desc limit 5");
				$pfx_num = count($pfx_rz);
				/* If found then output the markup */
				if ($pfx_rz) {
					echo '<div id="main"><div class="welcome-slider"><div class="slides">';
					$pfx_i = 0;
					while ($pfx_i < $pfx_num) {
						$pfx_out = $pfx_rz[$pfx_i];
						$pfx_title = $pfx_out['title'];
						$pfx_image = $pfx_out['image'];
						$pfx_image = getThing($pfx_query = "SELECT file_name FROM " . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_image}'");
						$pfx_description = $pfx_out['description'];
						$pfx_url = $pfx_out['url'];
						if ($pfx_url == '') {
						if ($pfx_description == '') {
							echo "<div class=\"slide\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /></div>";
						} else {
							echo "<div class=\"slide\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /><div class=\"wel-desc\">{$pfx_description}</div></div>";
}
						} else {
						if ($pfx_description == '') {
							echo "<div class=\"slide\"><a href=\"{$pfx_url}\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /></a></div>";
						} else {
							echo "<div class=\"slide\"><a href=\"{$pfx_url}\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /></a><div class=\"wel-desc\">{$pfx_description}</div></div>";
}
						}
						$pfx_i++;
					}
					echo '</div><div class="thumbs"><ul><li class="fbar">&nbsp;</li>';
					$pfx_i = 0;
					while ($pfx_i < $pfx_num) {
						$pfx_out = $pfx_rz[$pfx_i];
						$pfx_title = $pfx_out['title'];
						$pfx_image = $pfx_out['image'];
						$pfx_image = getThing($pfx_query = "SELECT file_name FROM " . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_image}'");
						$pfx_description = $pfx_out['description'];
						$pfx_url = $pfx_out['url'];
						echo "<li class=\"thumb-item\"><a href=\"#\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /></a></li>";
						$pfx_i++;
					}
					echo '</ul></div></div></div>';
				}
				echo "<br /><div id=\"{$pfx_m_n}-lower-desc\">{$pfx_w_lower_descr}</div></div>";
				break;
		}
		break;
}