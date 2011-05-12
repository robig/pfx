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
 * Title: lib_pfx
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
// ------------------------------------------------------------------
// set up pfx and let the REAL magic begin
function pfx($pfx_rel_path = FALSE, $pfx_style = FALSE, $pfx_page_display_name = FALSE, $pfx_page_type = FALSE, $pfx_page_id = FALSE, $pfx_staticpage = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE, $pfx_p = FALSE) {
	$pfx_request = $_SERVER['REQUEST_URI'];
	if ( (isset($pfx_style)) && ($pfx_style) ){
		$pfx_request = str_replace("?style={$pfx_style}", "", $pfx_request);
	}
	if (PREFS_CLEAN_URLS == 'yes') {
		/* If the request contains a ? then this person is accessing with a dirty URL and is handled accordingly  */
		if (strpos($pfx_request, '?s=') !== FALSE) {
			$pfx_rel_path = './';
		} else {
			$pfx_url        = explode('/', $pfx_request);
			$pfx_count      = count($pfx_url);
			$pfx_site_url_x = str_replace('http://', "", PREFS_SITE_URL);
			$pfx_temp       = explode('/', $pfx_site_url_x);
			$pfx_install    = count($pfx_temp);
			$pfx_dir_level  = $pfx_install - 2;
			if ($pfx_dir_level < 0) {
				$pfx_dir_level = 0;
			}
			if ( isset($pfx_url[$pfx_dir_level + 1]) ) {
				$pfx_s = strtolower($pfx_url[$pfx_dir_level + 1]);
			} else {
				$pfx_s = FALSE;
			}
			if (isset($pfx_url[$pfx_dir_level + 2])) {
				$pfx_m = strtolower($pfx_url[$pfx_dir_level + 2]);
			} else {
				$pfx_m = FALSE;
			}
			if (isset($pfx_url[$pfx_dir_level + 3])) {
				$pfx_x = strtolower($pfx_url[$pfx_dir_level + 3]);
			} else {
				$pfx_x = FALSE;
			}
			if (isset($pfx_url[$pfx_dir_level + 4])) {
				$pfx_p = strtolower($pfx_url[$pfx_dir_level + 4]);
			} else {
				$pfx_p = FALSE;
			}
			switch ($pfx_count) {
				case $pfx_dir_level + 3:
					$pfx_rel_path = '../';
					break;
				case $pfx_dir_level + 4:
					$pfx_rel_path = '../../';
					break;
				case $pfx_dir_level + 5:
					$pfx_rel_path = '../../../';
					break;
				case $pfx_dir_level + 6:
					$pfx_rel_path = '../../../../';
					break;
				default:
					$pfx_rel_path = './';
					break;
			}
		}
	} else {
		$pfx_rel_path = './';
	}
	if ( (isset($pfx_s)) && ($pfx_s) ) {
	} else {
		$pfx_default_page = PREFS_DEFAULT_PAGE;
		$pfx_last    = $pfx_default_page{strlen($pfx_default_page) - 1};
		$pfx_default = explode('/', $pfx_default_page);
		if (isset($pfx_default['0'])) {
			$pfx_s = sterilise_txt($pfx_default['0']);
		} else {
			$pfx_s = FALSE;
		}
		if (isset($pfx_default['1'])) {
			$pfx_m = sterilise_txt($pfx_default['1']);
		} else {
			$pfx_m = FALSE;
		}
		if (isset($pfx_default['2'])) {
			$pfx_x = sterilise_txt($pfx_default['2']);
		} else {
			$pfx_x = FALSE;
		}
		if (isset($pfx_default['3'])) {
			$pfx_p = sterilise_txt($pfx_default['3']);
		} else {
			$pfx_p = FALSE;
		}
	}
	if ( (isset($pfx_s)) && ($pfx_s !== 'bad') ) {
		$pfx_s = public_check_404($pfx_s);
	}
	if ( (isset($pfx_s)) && ($pfx_s == '404') ) {
		$pfx_m = FALSE;
		$pfx_x = FALSE;
		$pfx_p = FALSE;
	}
	if ($pfx_m == 'rss') {
		if ( isset($pfx_s) ) {
			$pfx_rss = public_check_rss($pfx_s);
		}
		if ( isset($pfx_rss) && ($pfx_rss) ) {
		} else {
			$pfx_s = '404';
			$pfx_m = FALSE;
			$pfx_x = FALSE;
			$pfx_p = FALSE;
		}
	} else {
		$pfx_rss = FALSE;
	}
	if ( (isset($pfx_s)) && ($pfx_s !== 'bad') ) {
		$pfx_page_type = check_type($pfx_s);
	}
	if ($pfx_page_type == 'dynamic') {
		$pfx_style = $pfx_page_type;
	} else if ($pfx_page_type == 'static') {
		$pfx_style = $pfx_s;
		$pfx_m     = FALSE;
		$pfx_x     = FALSE;
		$pfx_p     = FALSE;
	} else if ($pfx_s == '404') {
		$pfx_style = '404';
	} else {
		$pfx_style = $pfx_s;
	}
	function resolver($pfx_string) {
		$pfx_string = str_replace( 'BREAK2', '_', str_replace('BREAK1', '-', sterilise_url(str_replace('_', 'BREAK2', str_replace('-', 'BREAK1', $pfx_string)))) );
		return $pfx_string;
	}
	if ( (isset($pfx_s)) && ($pfx_s !== 'bad') ) {
		$pfx_s                 = resolver($pfx_s);
		$pfx_m                 = resolver($pfx_m);
		$pfx_x                 = resolver($pfx_x);
		$pfx_p                 = resolver($pfx_p);
		$pfx_page_id           = get_page_id($pfx_s);
		$pfx_page_hits         = safe_field('page_views', 'pfx_core', "page_name='{$pfx_s}'");
		$pfx_page_display_name = safe_field('page_display_name', 'pfx_core', "page_name='{$pfx_s}'");
		safe_update('pfx_core', "page_views  = {$pfx_page_hits} + 1", "page_name = '{$pfx_s}'");
		return array(
		's' => $pfx_s,
		'm' => $pfx_m,
		'x' => $pfx_x,
		'p' => $pfx_p,
		'page_id' => $pfx_page_id,
		'style' => $pfx_style,
		'rss' => $pfx_rss,
		'rel_path' => $pfx_rel_path,
		'page_display_name' => $pfx_page_display_name,
		'page_type' => $pfx_page_type,
		'staticpage' => $pfx_staticpage
		);
	} else if ($pfx_s == 'bad') {
		return array(
			's' => $pfx_s
			);
	}
}
// ------------------------------------------------------------------
// Build the navigation dynamically or build it from specified array
function build_navigation($pfx_lang, $pfx_nested_nav = FALSE, $pfx_s = FALSE) {
	$pfx_check_pages = safe_rows('*', 'pfx_core', "public = 'yes' and in_navigation = 'yes' and page_name not in ('404','rss') order by page_order asc");
	$pfx_num         = count($pfx_check_pages);
	$pfx_current_dir = current_dir();
	echo "<h3>{$pfx_lang['navigation']}</h3>\n\t\t\t\t<ul id=\"navigation_1\">\n";
	$pfx_i     = 0;
	$pfx_first = TRUE; // first link
	$pfx_last  = FALSE; // last link
	while ($pfx_i < $pfx_num) {
		$pfx_out               = $pfx_check_pages[$pfx_i];
		$pfx_page_display_name = $pfx_out['page_display_name'];
		$pfx_page_name         = $pfx_out['page_name'];
		$pfx_page_type         = $pfx_out['page_type'];
		if ($pfx_i == ($pfx_num - 1))
			$pfx_last = TRUE;
		if ($pfx_s == $pfx_page_name) {
			if ($pfx_page_type == 'dynamic') {
				$pfx_includestr = 'dynamic';
			} else {
				$pfx_includestr = $pfx_page_name;
			}
			if ( file_exists("admin/blocks/block_{$pfx_includestr}_nav.php") ) {
				echo "\t\t\t\t\t<li id=\"li_1_$pfx_page_name\"><a href=\"" . createURL($pfx_page_name) . "\" title=\"{$pfx_page_display_name}\" id=\"navigation_1_{$pfx_page_name}\" class=\"nav_current_1 replace\">{$pfx_page_display_name}<span></span></a></li>\n";
				include "admin/blocks/block_{$pfx_includestr}_nav.php";
			} else {
				echo "\t\t\t\t\t<li id=\"li_1_{$pfx_page_name}\" class=\"nav_current_li_1" . ($pfx_first ? ' first' : "") . ($pfx_last ? ' last' : "") . "\"><a href=\"" . createURL($pfx_page_name) . "\" title=\"{$pfx_page_display_name}\" id=\"navigation_1_{$pfx_page_name}\" class=\"nav_current_1 replace\">{$pfx_page_display_name}<span></span></a></li>\n";
				$pfx_first = FALSE;
			}
		} else {
			echo "\t\t\t\t\t<li id=\"li_1_{$pfx_page_name}\"" . ($pfx_first ? " class=\"first\"" : "") . ($pfx_last ? " class=\"last\"" : "") . "><a href=\"" . createURL($pfx_page_name) . "\" title=\"{$pfx_page_display_name}\" id=\"navigation_1_{$pfx_page_name}\" class=\"replace\">{$pfx_page_display_name}<span></span></a></li>\n";
			$pfx_first = FALSE;
		}
		$pfx_i++;
	}
	echo "\t\t\t\t</ul>\n";
}
// ------------------------------------------------------------------
// Build and include the blocks for this page
function build_blocks($pfx_lang, $pfx_page_blocks = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_core_page = safe_row('*', 'pfx_core', "page_name = '{$pfx_s}'");
	extract($pfx_core_page, EXTR_PREFIX_ALL, 'pfx');
	$pfx_core_page = NULL;
	$pfx_blocks = explode(" ", $pfx_page_blocks);
	for ($pfx_count = 0; $pfx_count < (count($pfx_blocks)); $pfx_count++) {
		$pfx_current = $pfx_blocks[$pfx_count];
		$pfx_current = str_replace(" ", "", $pfx_current);
		if (file_exists("admin/blocks/block_{$pfx_current}.php")) {
			include "admin/blocks/block_{$pfx_current}.php";
			echo "\n";
		}
	}
}
// ------------------------------------------------------------------
// Build a header bar for logged in users
function build_head($pfx_lang) {
	if (isset($_COOKIE['pfx_login'])) {
		list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
		$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
		if ( hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash ) {
			$GLOBALS['pfx_user_privs'] = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'");
			$GLOBALS['pfx_real_name'] = safe_field('realname', 'pfx_users', "user_name='{$pfx_username}'");
			$GLOBALS['rte_user'] = safe_field('rte_user', 'pfx_users', "user_name='{$pfx_username}'");
			$GLOBALS['pfx_user'] = $pfx_username;
			$pfx_user_count = @mysql_num_rows(safe_query('select * from ' . CONFIG_TABLE_PREFIX . 'pfx_log_users_online'));
			$pfx_user_count = $pfx_user_count - 1;
			echo '<div id="admin-bar"><div id="ab" class="rh">
			<div class="bc"><div id="admin_header"><h1><b class="blk">Hello</b> ';
			if (isset($GLOBALS['pfx_real_name'])) {
				echo firstword($GLOBALS['pfx_real_name']);
			}
			echo ",</h1>
		<div id=\"admin_header_text\"><p>" . safe_strftime( $pfx_lang, PREFS_DATE_FORMAT, time() ) . ".
		    " . PREFS_SITE_URL . " has {$pfx_user_count} visitor(s) online.</p>
		</div>
		<div id=\"admin_header_controls\">
		    <p>
			<a href=\"" . PREFS_SITE_URL . "admin/\" title=\"Return to the dashboard\">Dashboard</a>
			<a href=\"" . PREFS_SITE_URL . "admin/?s=logout&amp;tool=home\" title=\"Logout\">Logout</a>
		    </p>
		</div></div></div></div><b class=\"rb\"><b class=\"ab-4\"></b><b class=\"ab-3\"></b><b class=\"ab-2\"></b><b class=\"ab-1\"></b></b></div><div id=\"bar-spacer\"></div>"; /* Needs language */
		}
	}
}
// ------------------------------------------------------------------
// Build title for current page
function build_title($pfx_page_display_name = FALSE, $pfx_page_type = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE, $pfx_p = FALSE) {
	// will probably need support for child pages!
	if ( ($pfx_page_type == 'dynamic') && ($pfx_m == 'permalink') ) {
		$pfx_post_title = safe_field('title', 'pfx_dynamic_posts', "post_slug = '{$pfx_x}'");
		if ($pfx_post_title) {
			return "{$pfx_post_title} - {$pfx_page_display_name} - " . PREFS_SITE_NAME;
		} else {
			if ( (isset($pfx_page_display_name)) && ($pfx_page_display_name) ) {
				return "{$pfx_page_display_name} - " . PREFS_SITE_NAME;
			} else {
				return PREFS_SITE_NAME;
			}
		}
	} else if ($pfx_m == 'tag') {
		if ($pfx_p) {
			return "{$pfx_page_display_name} - " . simplify(squash_slug($pfx_x)) . " - {$pfx_p} - " . PREFS_SITE_NAME;
		} else {
			return "{$pfx_page_display_name} - " . simplify(squash_slug($pfx_x)) . ' - ' . PREFS_SITE_NAME;
		}
	} else if ($pfx_m == 'page') {
		return "{$pfx_page_display_name} - " . simplify($pfx_m) . ' ' . simplify($pfx_x) . ' - ' . PREFS_SITE_NAME;
	} else if ($pfx_m) {
		return "{$pfx_page_display_name} - " . simplify($pfx_m) . ' - ' . PREFS_SITE_NAME;
	} else {
		return "{$pfx_page_display_name} - " . PREFS_SITE_NAME;
	}
}