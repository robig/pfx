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
 * Title: Module Template
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
		$pfx_m_description = 'Store a collection of examples on your website and group them by tag.'; /* A description of your module */
		$pfx_m_author      = 'Your name'; /* Who is the module author? (Don't forget to add your name to the author tag at the top of this file too) */
		$pfx_m_url         = 'http://yourwebsite.com'; /* What is the URL of your homepage */
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
		$pfx_execute = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (
				`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,
				`{$pfx_m_n}_title` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',
				`tags` varchar(200) collate " . PFX_DB_COLLATE . " NOT NULL default '',
				`url` varchar(300) collate " . PFX_DB_COLLATE . " NOT NULL default '',
				PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;
				";
		break;
	/*	The administration of the module (add, edit, delete)
		This is where PFX really saves you time, these few lines of code will create the entire admin interface
	*/
	case 'admin' :
		$pfx_module_name    = ucfirst($pfx_m_n); /* The name of your module */
		$pfx_table_name     = "pfx_module_{$pfx_m_n}"; /* The name of the table */
		$pfx_order_by       = "{$pfx_m_n}_title"; /* The field to order by in table view */
		$pfx_asc_desc       = 'asc'; /* Ascending (asc) or decending (desc) */
		/* Fields you want to exclude in your table view */
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id",
			'tags'
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id"
		);
		$pfx_items_per_page = 15; /* The number of items per page in the table view */
		$pfx_tags           = 'yes'; /* Does this module support tags (yes or no) */
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/*	The three sections below are all for the module output, a module is loaded at three different stages of a page build.
		If you need to declare functions you must do so in a seperate file. For this example module I would create a
		file called module_functions.php and place it in the modules sub folder named functions. PFX will include this once before running the Pre
		section below.
	*/
	/*	Pre.
		Any code to be run before HTML output, any redirects or header changes must occur here
	*/
	case 'pre' :
		$pfx_ptitle = 'Welcome to my ' . ucfirst($pfx_s); /* $pfx_ptitle - To overwrite the current page title */
		$pfx_pinfo  = "This is my {$pfx_s} page and it's really great."; /* $pfx_pinfo - To overwrite the strapline and meta description. */
		break;
	/*	Head.
		This will output code into the end of the head section of the HTML, this allows you to load in external CSS, JavaScript etc
	*/
	case 'head' :
		break;
	default :
		$pfx_page_title = "<h3>{$pfx_page_display_name}</h3>";
		if ( (isset($pfx_m)) or (isset($pfx_x)) or (isset($pfx_p)) ) {
			switch ($pfx_m) {
			    case 'tag':
			    $pfx_page_title = "<h3>{$pfx_page_display_name} (Tag: {$pfx_x})</h3>";
			    break;
			    case 'catagories':
			    $pfx_page_title = "<h3>{$pfx_page_display_name} (Catagories)</h3>";
			    break;
			}
		    if ( (isset($pfx_x)) && ($pfx_x) && ($pfx_m !== 'tag') ) {
			switch ($pfx_x) {
			    case "{$pfx_x}":
			    $pfx_page_title = "<h3>{$pfx_page_display_name} (Pages: {$pfx_x})</h3>";
			    break;
			}
		    }
		    if ( (isset($pfx_p)) && ($pfx_p) ) {
			switch ($pfx_p) {
			    case "{$pfx_p}":
			    $pfx_page_title = "<h3>{$pfx_page_display_name} (Page: {$pfx_p})</h3>";
			    break;
			}
		    }
		}
	/*	Show Module.	*/
	/*	This is where your module will output into the content div on the page */
		echo $pfx_page_title;
		echo '<p>Hello, World!</p>';
		if ( ($pfx_m !== 'catagories') && ($pfx_m !== 'tag') ) {
		    echo '<p>Here\'s how the url structure works :</p>';
		}

		$pfx_tag = 'Stuff'; /* You should pull this from the database */

		    if ( ($pfx_m !== 'catagories') && ($pfx_m !== 'tag') ) {
			$pfx_tag_link = createURL($pfx_s, 'tag', $pfx_tag);
			echo "<p>Here is an example tag link : <a href=\"{$pfx_tag_link}\" title=\"Add a title here\">{$pfx_tag}</a></p>";
		    }
		    if ( (isset($pfx_s)) && ($pfx_s) && ($pfx_m !== 'tag') && ($pfx_m !== 'catagories') ) {
			$pfx_catagories_link = createURL($pfx_s, 'catagories');
			echo "<p>Here is an example of how to create a link to some <a href=\"{$pfx_catagories_link}\" title=\"Add a title here\">catagories</a></p>";
		    }

		$pfx_sub_catagory = 'Foo'; /* You should pull this from the database */

		if ( (isset($pfx_m)) && ($pfx_m) && ($pfx_m !== 'tag') && ($pfx_x !== strtolower("{$pfx_sub_catagory}")) ) {
		    $pfx_sub_catagory_link = createURL($pfx_s, 'catagories', $pfx_sub_catagory);
		    echo "<p>Here is an example link to another sub catagory : <a href=\"{$pfx_sub_catagory_link}\" title=\"Add a title here\">The {$pfx_sub_catagory} sub-catagory pages.</a></p>";
		}

		$pfx_page = 'Bar'; /* You should pull this from the database */

		if ( (isset($pfx_x)) && ($pfx_x) && ($pfx_m !== 'tag') && ($pfx_p !== strtolower("{$pfx_page}")) ) {
		    $pfx_page_link = createURL($pfx_s, 'catagories', $pfx_sub_catagory, $pfx_page);
		    echo "<p>Here is an example link to a page : <a href=\"{$pfx_page_link}\" title=\"Add a title here\">The {$pfx_page} page</a></p>";
		}

		echo '<div id="module-footer" class="pfx-module"><div id="breadcrumbs" class="pfx-module">';
		if ( (isset($pfx_m)) && ($pfx_m) && ($pfx_m !== 'tag') ) {
		    $pfx_module_link = createURL($pfx_s);
		    echo "<a href=\"{$pfx_module_link}\" title=\"Add a title here\">" . ucfirst($pfx_s) . "</a>";
		    $pfx_module_link = createURL($pfx_s, $pfx_m);
		    echo " &raquo; <a href=\"{$pfx_module_link}\" title=\"Add a title here\">" . ucfirst($pfx_m) . "</a>";
		}
		if ( (isset($pfx_x)) && ($pfx_x) && ($pfx_m !== 'tag') ) {
		$pfx_module_link = createURL($pfx_s, $pfx_m, $pfx_x);
		echo " &raquo; <a href=\"{$pfx_module_link}\" title=\"Add a title here\">" . ucfirst($pfx_x) . "</a>";
		}
		if ( (isset($pfx_p)) && ($pfx_p) && ($pfx_m !== 'tag') ) {
		$pfx_module_link = createURL($pfx_s, $pfx_m, $pfx_x, $pfx_p);
		echo " &raquo; <a href=\"{$pfx_module_link}\" title=\"Add a title here\">" . ucfirst($pfx_p) . "</a>";
		}
		echo '</div></div>';

		break;

}