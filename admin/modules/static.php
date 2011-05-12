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
 * Title: Static Page Module
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
switch ($pfx_do) {
	// Module Admin
	case 'admin':
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 1) {
			$pfx_type       = 'static';
			$pfx_table_name = 'pfx_core';
			$pfx_edit_id    = 'page_id';
			if ( (isset($pfx_edit)) or ($pfx_edit) ) {
			} else {
				$pfx_edit = safe_field('page_id', 'pfx_core', "page_name='{$pfx_x}'");
			}
			$pfx_scroll = admin_carousel($pfx_lang, $pfx_x, $pfx_scroll, $pfx_s);
			admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
			$pfx_message = admin_edit($pfx_table_name, $pfx_edit_id, $pfx_edit, $pfx_edit_exclude = array(
				'page_id',
				'page_type',
				'page_name',
				'page_description',
				'page_display_name',
				'page_blocks',
				'admin',
				'page_views',
				'public',
				'publish',
				'hidden',
				'searchable',
				'page_order',
				'last_modified',
				'page_parent',
				'in_navigation',
				'privs'
			), $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
		}
		break;
	// Show Module
	default:
		if ((!isset($pfx_s)) && (!$pfx_s)) {
			$pfx_s = 404;
		}
		if ((isset($pfx_s)) && ($pfx_s)) {
			$pfx_core_page_name = safe_row('*', 'pfx_core', "page_name='{$pfx_s}'");
			extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
			$pfx_core_page_name = NULL;
			echo "<div id=\"{$pfx_s}\">\n\t\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n";
			if (isset($_COOKIE['pfx_login'])) {
				list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
				$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
				if (hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash) {
					$pfx_privs = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'");
					if ($pfx_privs >= 1) {
						echo "\t\t\t\t\t\t<ul class=\"page_edit\">\n\t\t\t\t\t\t\t<li class=\"post_edit\"><a class=\"quick-edit\" href=\"" . PREFS_SITE_URL . "admin/?s=publish&amp;m=static&amp;x={$pfx_s}&amp;edit={$pfx_page_id}\" title=\"{$pfx_lang['edit_page']}\">{$pfx_lang['edit_page']}</a></li>\n\t\t\t\t\t\t</ul>\n";
					}
				}
			}
			eval('?>' . $pfx_page_content . '<?php ');
			echo "\n\t\t\t\t\t</div>\n";
		}
}