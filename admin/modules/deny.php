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
 * Title: Deny by ip address Plugin
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
function pfx_count($pfx_table) {
	$pfx_table = adjust_prefix($pfx_table);
	return mysql_num_rows(safe_query("select ip from {$pfx_table} where `ip` = '{$_SERVER['REMOTE_ADDR']}' limit 1"));
}
switch ($pfx_do) {
	/* General info */
	case 'info' :
		$pfx_m_name        = ucfirst($pfx_m_n);
		$pfx_m_description = 'Block bad visitors and bots by IP address.';
		$pfx_m_author      = 'Tony White';
		$pfx_m_url         = 'http://heydojo.co.cc';
		$pfx_m_version     = 1.0;
		$pfx_m_type        = 'plugin';
		$pfx_m_publish     = 'yes';
		$pfx_m_in_navigation     = 'no';
	break;
	/* Install */
	case 'install' :
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,`ip` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL default '',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
	break;
	/* pre (To be run before page load) */
	case 'pre' :
		if ( table_exists("pfx_module_{$pfx_m_n}") ) {
			if (pfx_count("pfx_module_{$pfx_m_n}") > 0) {
				die( header('HTTP/1.0 403 Forbidden') );
			}
		}
	break;
	/* Admin of plugin */
	case 'admin' :
		$pfx_module_name  = $pfx_m_n;
		$pfx_table_name   = "pfx_module_{$pfx_m_n}";
		$pfx_order_by     = "{$pfx_m_n}_id";
		$pfx_asc_desc     = 'desc';
		$pfx_view_exclude = array(
			"{$pfx_m_n}_id"
		);
		$pfx_edit_exclude = array(
			"{$pfx_m_n}_id"
		);
		$pfx_tags         = 'no';
		$pfx_items_per_page = 25; /* The number of items per page in the table view */
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
	break;
	/* Ban bad bots */
	default :
		if ( (table_exists("pfx_module_{$pfx_m_n}")) && (B_TYPE == 'bot') ) {
			if (pfx_count("pfx_module_{$pfx_m_n}") > 0) {
				die( header('HTTP/1.0 403 Forbidden') );
			} else {
				safe_insert("pfx_module_{$pfx_m_n}", "`ip` = '{$_SERVER['REMOTE_ADDR']}'");
				logme("<a rel=\"nofollow\" href=\"http://network-tools.com/default.asp?prog=lookup&amp;host={$_SERVER['REMOTE_ADDR']}\" title=\"Lookup IP: {$_SERVER['REMOTE_ADDR']}\" target=\"_blank\">{$_SERVER['REMOTE_ADDR']}</a> {$pfx_lang['bad_bot_block']}", 'yes', 'error');
				die( header('HTTP/1.0 403 Forbidden') );
			}
		} else {
			die( header('HTTP/1.0 403 Forbidden') );
		}
	break;
}