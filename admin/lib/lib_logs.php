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
 * Title: lib_logs
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
//------------------------------------------------------------------
/* Two functions to calculate page render times */
function getmicrotime() {
	list($pfx_usec, $pfx_sec) = explode(" ", microtime());
	return ((float) $pfx_usec + (float) $pfx_sec);
}
function pagetime($pfx_type) {
	static $pfx_orig_time;
	if ($pfx_type == 'init') {
		$pfx_orig_time = getmicrotime();
	}
	if ($pfx_type == 'print') {
		printf('%2.4f', getmicrotime() - $pfx_orig_time);
	}
}
//------------------------------------------------------------------
/* Referral function for tracking site referrals */
function referral($pfx_lang, $pfx_page_display_name = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE, $pfx_p = FALSE) {

	if ( (isset($_SERVER['HTTP_REFERER'])) && ($_SERVER['HTTP_REFERER']) ) {
		$pfx_ref = sterilise_url($_SERVER['HTTP_REFERER']);
		$pfx_reflink = "<a rel=\"nofollow\" href=\"{$pfx_ref}\" title=\"{$pfx_lang['referral_link']}\" target=\"_blank\">{$pfx_ref}</a>";
	} else {
		$pfx_reflink = $pfx_lang['unknown_referrer'];
		$pfx_ref = '!';
	}
	if ($pfx_s == 404) {
		$pfx_requested = sterilise_url($_SERVER['REQUEST_URI']);
	}
	if ( (isset($_SERVER['HTTP_REFERER'])) && ($_SERVER['HTTP_REFERER']) or ($pfx_s == 404) ) {
		if (B_NAME == 'op') {
			$pfx_long_b_name = 'Browser : Opera';
		} else if (B_NAME == 'moz') {
			$pfx_long_b_name = 'Browser : Mozilla';
		} else if (B_NAME == 'saf') {
			$pfx_long_b_name = 'Browser : Safari';
		} else if (B_NAME == 'konq') {
			$pfx_long_b_name = 'Browser : Konqueror';
		} else if (B_NAME == 'ns4') {
			$pfx_long_b_name = 'Browser : Netscape';
		} else if ( strpos(B_NAME, 'ie') == TRUE ) {
			$pfx_long_b_name = 'Browser : Internet Explorer';
		} else {
			if ( B_TYPE == 'bot' ) {
				$pfx_long_b_name = 'Web Bot';
			} else if ( B_TYPE == 'mobile' ) {
				$pfx_long_b_name = 'Mobile browser : Version Unknown';
			} else {
				$pfx_long_b_name = 'Browser : Version Unknown';
			}
		}
		if ($pfx_s == 404) {
			$pfx_referral = "{$pfx_long_b_name} " . B_VERSION . " - {$pfx_reflink} - <a rel=\"nofollow\" href=\"{$pfx_requested}\" target=\"_blank\">{$pfx_lang['page_miss']}</a> : {$pfx_page_display_name}";
		} else {
			$pfx_referral = "<a rel=\"nofollow\" href=\"" . createURL($pfx_s, $pfx_m, $pfx_x, $pfx_p) . "\" title=\"{$pfx_page_display_name}\" target=\"_blank\">{$pfx_page_display_name}</a> - {$pfx_long_b_name} " . B_VERSION . " - {$pfx_reflink}";
		}
		$pfx_domain = trim(str_replace('www.', "", PREFS_SITE_URL));
		if ( (isset($pfx_referral)) && ($pfx_referral) && (!strstr($pfx_ref, $pfx_domain)) ) {
			if ($pfx_referral == '') {
			} else {
				if ( (isset($GLOBALS['pfx_user'])) && ($GLOBALS['pfx_user']) ) {
					$pfx_uname = $GLOBALS['pfx_user'];
				} else {
					$pfx_uname = 'Visitor';
				}
				$pfx_ip = $_SERVER['REMOTE_ADDR'];
				$pfx_uname = sterilise_txt($pfx_uname, TRUE);
				if (preg_match('/^[0-9\.]+$/', $pfx_ip)) {
				} else {
					$pfx_ip = sterilise($pfx_ip, TRUE);
				}
				safe_insert('pfx_log', "user_id = '{$pfx_uname}',  
							user_ip = '{$pfx_ip}', 
							log_time = utc_timestamp(),
							log_type = 'referral',
							log_icon = 'referral',
							log_message = '{$pfx_referral}'");
			}
		}
	}
}
//------------------------------------------------------------------
/* Log function for writing information to log database */
function logme($pfx_message, $pfx_imp, $pfx_icon) {
	$pfx_ip = $_SERVER['REMOTE_ADDR'];
	if (isset($GLOBALS['pfx_user'])) {
		$pfx_uname = $GLOBALS['pfx_user'];
	} else {
		$pfx_uname = 'Visitor';
	}
	if (!$pfx_icon) {
		$pfx_icon = 'site';
	}
	safe_insert('pfx_log', "user_id = '{$pfx_uname}',  
								 user_ip = '{$pfx_ip}', 
							 	 log_time = utc_timestamp(),
							 	 log_type = 'system',
							 	 log_message = '{$pfx_message}',
							 	 log_icon = '{$pfx_icon}',
							 	 log_important = '{$pfx_imp}'");
}
//------------------------------------------------------------------
/* Log function for keeping track of who is online */
function users_online() {

	$pfx_sessiontime = 3; /* Minutes */
	safe_delete('pfx_log_users_online', "unix_timestamp() - last_visit >= {$pfx_sessiontime} * 60");
	if ( (PREFS_LOG_BOTS == 'no') && (B_TYPE == 'bot') ) {
	} else {
		$pfx_ip     = sterilise($_SERVER['REMOTE_ADDR'], TRUE);
		$pfx_query  = 'SELECT last_visit FROM ' . CONFIG_TABLE_PREFIX . "pfx_log_users_online WHERE visitor = '{$pfx_ip}'";
		$pfx_online = safe_query($pfx_query);
		if (mysql_num_rows($pfx_online) == 0) {
		$pfx_sql = "visitor = '{$pfx_ip}', last_visit = unix_timestamp()";
			safe_insert('pfx_log_users_online', $pfx_sql);
		} else {
			safe_update('pfx_log_users_online', 'last_visit = unix_timestamp()', "visitor = '{$pfx_ip}'");
		}
	}
}