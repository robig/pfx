<?php
if ( !defined('DIRECT_ACCESS' )) {
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
 * Title: lib_auth
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
// -------------------------------------------------------------
function auth_login($pfx_username, $pfx_password, $pfx_lang, $pfx_remember) {
	$pfx_username = sterilise_txt($pfx_username, TRUE);
	$pfx_password = addslashes($pfx_password);
	$pfx_password = sterilise_txt($pfx_password, TRUE);
	$pfx_remember = sterilise_txt($pfx_remember, TRUE);
	$pfx_howmany  = count(safe_rows('*', 'pfx_log', "log_message = '{$pfx_lang['failed_login']}' and user_ip = '{$_SERVER["REMOTE_ADDR"]}' and log_time < utc_timestamp() and log_time > DATE_ADD(utc_timestamp(), INTERVAL -1 DAY)"));
	sleep(1); /*	Should halt dictionary attacks	*/
	/*	No more than 5 failed logins in 24 hours	*/
	if ($pfx_howmany > 5) {
		$pfx_message = $pfx_lang['login_exceeded'];
		logme($pfx_lang['logins_exceeded'], 'yes', 'error');
		return $pfx_message;
	} else {
		if ( (isset($pfx_username)) && ($pfx_username) && (isset($pfx_password)) && ($pfx_password) ) {
			$pfx_r = safe_field('user_name', 'pfx_users', "user_name = '{$pfx_username}'and 
			pass = '" . doPass($pfx_password) . "' and privs >= 0");
			if ( (isset($pfx_r)) && ($pfx_r) ) {
				$pfx_user_hits = safe_field('user_hits', 'pfx_users', "user_name='{$pfx_username}'");
				safe_update('pfx_users', "last_access = utc_timestamp()", "user_name = '{$pfx_username}'");
				safe_update('pfx_users', "user_hits  = {$pfx_user_hits} + 1", "user_name = '{$pfx_username}'");
				$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
				if ( (isset($pfx_remember)) && ($pfx_remember) ) { // persistent cookie required
					setcookie('pfx_login', $pfx_username . ',' . hash('sha256', "{$pfx_username}{$pfx_nonce}"), time() + 3600 * 24 * 365, '/');
				} else { // session-only cookie required
					setcookie('pfx_login', $pfx_username . ',' . hash('sha256', "{$pfx_username}{$pfx_nonce}"), 0, '/');
				}
				$pfx_privs    = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'"); // login is good, create user
				$pfx_realname = safe_field('realname', 'pfx_users', "user_name='{$pfx_username}'");
				$pfx_nonce    = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
				$pfx_rte_user = safe_field('rte_user', 'pfx_users', "user_name='{$pfx_username}'");
				if ( (isset($pfx_realname)) && ($pfx_realname) ) {
					$GLOBALS['pfx_real_name'] = $pfx_realname;
				}
				if ( (isset($pfx_privs)) && ($pfx_privs) ) {
					$GLOBALS['pfx_user_privs'] = $pfx_privs;
				}
				$GLOBALS['pfx_user'] = $pfx_username;
				$GLOBALS['nonce'] = $pfx_nonce;
				$GLOBALS['rte_user'] = $pfx_rte_user;
				return '';
			} else { // login failed
				$GLOBALS['pfx_user'] = '';
				$pfx_message = $pfx_lang['login_incorrect'];
				return $pfx_message;
			}
		} else {
			$GLOBALS['pfx_user'] = '';
			$pfx_message = $pfx_lang['login_missing'];
			return $pfx_message;
		}
	}
}
// -------------------------------------------------------------
function auth_check($pfx_lang) {
	if (isset($_COOKIE['pfx_login'])) {
		list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
		$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
		if ( hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash ) { // check nonce
			$pfx_privs    = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'"); // login is good, create user
			$pfx_realname = safe_field('realname', 'pfx_users', "user_name='{$pfx_username}'");
			$pfx_rte_user = safe_field('rte_user', 'pfx_users', "user_name='{$pfx_username}'");
			if ( (isset($pfx_realname)) && ($pfx_realname) ) {
				$GLOBALS['pfx_real_name'] = $pfx_realname;
			}
			if ( (isset($pfx_privs)) && ($pfx_privs) ) {
				$GLOBALS['pfx_user_privs'] = $pfx_privs;
			}
			$GLOBALS['pfx_user'] = $pfx_username;
			$GLOBALS['rte_user'] = $pfx_rte_user;
			return '';
		} else { // something's wrong
			$GLOBALS['pfx_user'] = '';
			setcookie('pfx_login', '', time() - 3600);
			$pfx_message = $pfx_lang['bad_cookie'];
			return $pfx_message;
		}
	} else {
		$GLOBALS['pfx_user'] = '';
		setcookie('pfx_login', '', time() - 3600);
	}
}
if ( (isset($pfx_login_submit)) && ($pfx_login_submit) ) {
	if ( (isset($pfx_username)) && ($pfx_username) ) {
	} else {
		$pfx_username = FALSE;
	}
	if ( (isset($pfx_password)) && ($pfx_password)) {
	} else {
		$pfx_password = FALSE;
	}
	if (isset($pfx_remember) && ($pfx_remember)) {
	} else {
		$pfx_remember = FALSE;
	}
	if ($pfx_log_in = auth_login($pfx_username, $pfx_password, $pfx_lang, $pfx_remember)) {
		$pfx_s       = 'login';
		$pfx_message = $pfx_log_in;
		logme($pfx_lang['failed_login'], 'yes', 'error');
	} else {
		$pfx_s = 'myaccount';
		logme("{$pfx_lang['user']} {$GLOBALS['pfx_user']} {$pfx_lang['ok_login']}", 'no', 'user');
	}
} else if ( (isset($pfx_s)) && ($pfx_s == 'logout') ) {
	setcookie('pfx_login', ' ', time() - 3600, '/');
	$pfx_s = 'login';
	auth_check($pfx_lang);
	if (isset($GLOBALS['pfx_user'])) {
		logme("{$pfx_lang['user']} {$GLOBALS['pfx_user']} {$pfx_lang['ok_logout']}", 'no', 'user');
	}
	if ( (isset($pfx_tool)) && ($pfx_tool == 'home') ) {
		exit( header('Location: ../') );
	} else {
		exit( header('Location: ' . PREFS_SITE_URL . 'admin/') );
	}
} else {
	$pfx_log_in = auth_check($pfx_lang);
	if (isset($GLOBALS['pfx_user'])) {
		if ($GLOBALS['pfx_user']) {
			if ( (isset($pfx_s)) && ($pfx_s) ) {
				/* Then use $pfx_s */
			} else {
				$pfx_s = 'myaccount';
			}
		} else {
			$pfx_s       = 'login';
			$pfx_message = $pfx_log_in;
		}
	}
}