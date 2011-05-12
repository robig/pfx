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
 * Title: Portfolio Module
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
	/*	General Info	*/
	case 'info' :
		$pfx_m_name        = ucfirst($pfx_m_n);
		$pfx_m_description = "Display a design {$pfx_m_n} of your work.";
		$pfx_m_author      = 'Scott Evans';
		$pfx_m_url         = 'http://www.toggle.uk.com';
		$pfx_m_version     = '1.2';
		$pfx_m_type        = 'module';
		$pfx_m_publish     = 'yes';
		$pfx_m_in_navigation     = 'yes';
		break;
	/*	Install	*/
	case 'install' :
		$pfx_execute = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` smallint(5) NOT NULL auto_increment,`date` timestamp NOT NULL default CURRENT_TIMESTAMP,`title` varchar(80) collate " . PFX_DB_COLLATE . " NOT NULL default '',`description` longtext collate " . PFX_DB_COLLATE . " NOT NULL,`client_name` varchar(80) collate " . PFX_DB_COLLATE . " NOT NULL default '',`client_url` varchar(80) collate " . PFX_DB_COLLATE . " default NULL,`image_1` varchar(5) collate " . PFX_DB_COLLATE . " NOT NULL default '',`image_thumb` varchar(5) collate " . PFX_DB_COLLATE . " NOT NULL default '',`url` varchar(80) collate " . PFX_DB_COLLATE . " default NULL,`tags` varchar(80) collate " . PFX_DB_COLLATE . " NOT NULL default '',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		break;
	/*	The admin of the module	*/
	case 'admin' :
		$pfx_module_name  = "{$pfx_m_n}";
		$pfx_table_name   = "pfx_module_{$pfx_m_n}";
		$pfx_order_by     = 'date';
		$pfx_asc_desc     = 'desc';
		$pfx_view_exclude = array(
			"{$pfx_m_n}_id",
			'description',
			'client_url',
			'image_thumb',
			'image_1',
			'tags',
			'url',
			'client_name'
		);
		$pfx_edit_exclude = array(
			"{$pfx_m_n}_id"
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 15;
		$pfx_tags         = 'yes';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/*	Pre (To be run before output to the browser.)	*/
	case 'pre' :
		switch ($pfx_m) {
			case 'permalink':
				if ($pfx_x) {
					$pfx_project_title = safe_field('title', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id = '{$pfx_x}'");
					$pfx_ptitle       = PREFS_SITE_NAME . ' - ' . ucfirst($pfx_m_n) . " - {$pfx_project_title}";
				}
				break;
		}
		break;
	/*	Head (Output added to the head)	*/
	case 'head' :
	/*	None	*/
		break;
	/*	Show the module	*/
	default:
		switch ($pfx_m) {
			case 'tags':
				echo "<h3>{$pfx_page_display_name} (Tags)</h3>\n\t\t\t\t<div class=\"tag_section\">\n";
				public_tag_cloud("pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '1'", $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
				echo "\t\t\t\t</div>\n";
				break;
			case 'tag':
				if ($pfx_x) {
					$pfx_tag = squash_slug($pfx_x);
					echo "<h3>{$pfx_page_display_name} (Tag: {$pfx_tag})</h3>";
					$pfx_rs  = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "tags REGEXP '[[:<:]]{$pfx_tag}[[:>:]]' order by date desc");
					portfolio_show_all($pfx_rs, $pfx_m_n, $pfx_s, $pfx_m, $pfx_x);
				} else {
					echo "<h3>{$pfx_page_display_name}</h3>";
					$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '1' order by date desc limit 0, 6");
					portfolio_show_all($pfx_rs, $pfx_m_n, $pfx_s, $pfx_m, $pfx_x);
				}
				break;
			case 'permalink':
				echo "<h3>{$pfx_page_display_name}</h3>";
				if ($pfx_x) {
					$pfx_rs = safe_row('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id = '{$pfx_x}' limit 0,1");
					portfolio_show_single($pfx_rs, $pfx_s, $pfx_m, $pfx_x);
				} else {
					$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '1' order by date desc limit 0, 6");
					portfolio_show_all($pfx_rs, $pfx_m_n, $pfx_s, $pfx_m, $pfx_x);
				}
				break;
			case 'archives':
				echo "<h3>{$pfx_page_display_name} (Archives)</h3>";
				$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '1' order by date desc");
				portfolio_archives($pfx_rs, $pfx_s, $pfx_m, $pfx_x);
				break;
			default:
				echo "<h3>{$pfx_page_display_name}</h3>";
				$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= '1' order by date desc limit 0, 6");
				portfolio_show_all($pfx_rs, $pfx_m_n, $pfx_s, $pfx_m, $pfx_x);
				break;
		}
		break;
}