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
 * Title: Gallery Module
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
switch ($pfx_do) {
	/*	General information.
		The general information is used to show information about the module within PFX. 
		Simply enter details of your module here :
	*/
	case 'info' :
		$pfx_m_name        = ucfirst($pfx_m_n); /* The name of your module */
		$pfx_m_description = 'Create and display a collection of images on your website.'; /* A description of your module */
		$pfx_m_author      = 'Tony White'; /* Who is the module author? (Don't forget to add your name to the author tag at the top of this file too) */
		$pfx_m_url         = 'http://heydojo.co.cc'; /* What is the URL of your homepage */
		$pfx_m_version     = '1.0'; /* What version is this? */
		$pfx_m_type        = 'module'; /* Can be set to module or plugin. */
		$pfx_m_publish     = 'yes'; /* Is this a module that needs publishing to? */
		$pfx_m_in_navigation     = 'yes';
		break;
	/*	Install.
		This section contains the SQL needed to create your modules tables
	*/
	case 'install' :
	/*	Create any required database tables	*/
		$pfx_execute = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,`file` varchar(300) collate " . PFX_DB_COLLATE . " default '',`title` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',`post_slug` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',`description` LONGTEXT collate " . PFX_DB_COLLATE . " NOT NULL default '',`tags` varchar(200) collate " . PFX_DB_COLLATE . " default '',`image` varchar(300) collate " . PFX_DB_COLLATE . " NOT NULL default '',`published` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`lightbox` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`use_{$pfx_m_n}_css_file` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`top_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`lower_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`total_images_per_page` varchar(3) collate " . PFX_DB_COLLATE . " NOT NULL default '10',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute2 = "INSERT INTO `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id`) VALUES (1);";
		break;
	/*	The administration of the module (add, edit, delete)
		This is where PFX really saves you time, these few lines of code will create the entire admin interface
	*/
	case 'admin' :
		$pfx_module_name    = ucfirst($pfx_m_n); /* The name of your module */
		$pfx_table_name     = "pfx_module_{$pfx_m_n}"; /* The name of the table */
		$pfx_order_by       = 'title'; /* The field to order by in table view */
		$pfx_asc_desc       = 'asc'; /* Ascending (asc) or decending (desc) */
		/* Fields you want to exclude in your table view */
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id",
			'post_slug',
			'tags',
			'image',
			'file'
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id",
			'post_slug',
			'file'
		);
		$pfx_items_per_page = 10; /* The number of items per page in the table view */
		$pfx_tags           = 'yes'; /* Does this module support tags (yes or no) */
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;

	/*	The three sections below are all for the module output, a module is loaded at three different stages of a page build.
		If you need to declare functions you must do so in a seperate file. For this example module I would create a
		file called gallery_functions.php and place it in the modules sub folder named functions. PFX will include this once before running the Pre
		section below.
	*/
	/*	Pre.
		Any code to be run before HTML output, any redirects or header changes must occur here
	*/
	case 'pre' :
		$pfx_module_jscript = fetch('lightbox', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		if ($pfx_module_jscript === 'yes') {
			if (PREFS_JQUERY === 'no') {
				/* TODO : send an alert to the debug log */
			}
			if (PREFS_LIGHTBOX === 'no') {
				/* TODO : send an alert to the debug log */
			}
		}
		gallery_pre($pfx_m_n);
		break;
	/*	Head.
		This will output code into the end of the head section of the HTML, this allows you to load in external CSS
	*/
	case 'head' :
		$pfx_gallery_css = fetch("use_{$pfx_m_n}_css_file", "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		if ($pfx_gallery_css === 'yes') {
		/*	Add the stylesheet to the head	*/
		    echo "<link rel=\"stylesheet\" href=\"{$pfx_rel_path}admin/modules/css/{$pfx_m_n}.css\" type=\"text/css\" media=\"screen\" />";
		}
		break;
	/*	Show Module.	*/

	/*	This is where your module will output into the content div on the page */

	default :
	/*	Get the page display name from the database	*/
		$pfx_table_name     = "pfx_module_{$pfx_m_n}"; /* The name of the table */
		$pfx_order_by       = 'title'; /* The field to order by in table view */
		$pfx_asc_desc       = 'asc'; /* Ascending (asc) or decending (desc) */
		$pfx_exclude = array(
				"{$pfx_m_n}_id",
				'post_slug',
				'tags',
				'image',
				'published'
				);
		$pfx_view_number = fetch('total_images_per_page', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1); /* Total records to show per page */
		if ( (isset($pfx_m)) && (is_numeric($pfx_m)) ) {
			$pfx_p = $pfx_m;
			$pfx_m == FALSE;
		}
		if ( (isset($pfx_p)) && (is_numeric($pfx_p)) && ($pfx_p != 1) ) {
			$pfx_lo = ($pfx_p * $pfx_view_number - $pfx_view_number);
		} else {
			$pfx_p = 1;
			$pfx_lo = 0;
		}
		$pfx_h3_link = createURL($pfx_m_n);
		$pfx_is_tag = 'no';
		$pfx_g_top_descr_result = fetch('top_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_g_lower_descr_result = fetch('lower_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_g_top_content = "<div id=\"{$pfx_m_n}-top-descr\">{$pfx_g_top_descr_result}</div>\n";
		$pfx_g_lower_content = "<div id=\"{$pfx_m_n}-lower-descr\">{$pfx_g_lower_descr_result}</div></div>\n\t\t\t\t\t</div>\n";
		$pfx_list_top = "\t\t\t\t\t<div id=\"{$pfx_m_n}-show\" class=\"{$pfx_m_n}-list\">";

		switch ($pfx_m) {

			case 'tag' :
				if ( (isset($pfx_m)) && ($pfx_m == 'tag') && (isset($pfx_x)) && ($pfx_x) ) {
					echo "{$pfx_list_top}<div id=\"{$pfx_s}\">\n\t\t\t\t\t<h3>{$pfx_page_display_name} (Tag: {$pfx_x})</h3>\n{$pfx_g_top_content}";
					$pfx_is_tag = 'yes';
					$pfx_condition = "WHERE published = 'yes' and tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]'";
					gallery_create($pfx_m_n, $pfx_lang, $pfx_condition, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_exclude, $pfx_view_number, $pfx_lo, $pfx_module_jscript, $pfx_is_tag, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
					echo "</div><p><a class=\"ajax\" href=\"{$pfx_h3_link}\">Return to {$pfx_page_display_name}</a></p>{$pfx_g_lower_content}";
				}
			break;

			case 'page' :
				if ( (isset($pfx_m)) && ($pfx_m == 'page') && (isset($pfx_x)) && ($pfx_x) ) {
					echo "\t\t\t\t\t<div id=\"{$pfx_m_n}-show\" class=\"{$pfx_m_n}-page\">";
					$pfx_title = getThing($pfx_query = "SELECT title FROM " . CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_m_n} WHERE post_slug='{$pfx_x}'");
					$pfx_description = getThing($pfx_query = "SELECT description FROM " . CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_m_n} WHERE post_slug='{$pfx_x}'");
					$pfx_file = getThing($pfx_query = "SELECT file FROM " . CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_m_n} WHERE post_slug='{$pfx_x}'");
					/*	Print the display name into a h3	*/
					echo "<div id=\"{$pfx_s}\">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n<h4>{$pfx_title}</h4>";
					echo "<div id=\"image-viewer\"><a class=\"lightbox\" href=\"" . PREFS_SITE_URL . "files/images/{$pfx_file}\" rel=\"lightbox[group1]\"  title=\"{$pfx_title}\"><img id=\"image-view\" src=\"" . PREFS_SITE_URL . "files/images/{$pfx_file}\" alt=\"{$pfx_file}\" /></a></div><div id=\"image-desc\"><p>{$pfx_description}</p></div>";
					echo "</div><a class=\"ajax\" href=\"{$pfx_h3_link}\">Return to {$pfx_page_display_name}</a></div>";
				}
			break;

			default :
				echo "{$pfx_list_top}<div id=\"{$pfx_s}\">\n\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n{$pfx_g_top_content}";
				$pfx_condition = "WHERE published = 'yes'";
				gallery_create($pfx_m_n, $pfx_lang, $pfx_condition, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_exclude, $pfx_view_number, $pfx_lo, $pfx_module_jscript, $pfx_is_tag, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
				echo '</div>';
				$pfx_module_cloud = public_tag_cloud("pfx_module_{$pfx_m_n}", "published = 'yes'", $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
				if ($pfx_module_cloud == TRUE) {
					echo "<div class=\"tag-cloud\"><h4 id=\"h4-tags\">Tags:</h4><div class=\"tcloud\">{$pfx_module_cloud}</div></div>";
				}
				echo $pfx_g_lower_content;

		}
	break;
}