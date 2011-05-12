<?php
if (!defined('DIRECT_ACCESS')) { exit( header( 'Location: ../' ) ); }
header('Content-type: text/xml; charset=UTF-8');
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
 * Title: Installer - The install actions
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

/*
				if ((isset($pfx_do_the_drop)) && ($pfx_do_the_drop === 'yes')) {
					$pfx_sql = "SHOW TABLES FROM $pfx_database";
					if ($pfx_result = mysql_query($pfx_sql)) {
						while ($pfx_row = mysql_fetch_row($pfx_result)) {
							$pfx_found_tables[] = $pfx_row[0];
						}
					} else {
						$pfx_error      = 'Error, The database tables could not be listed. MySQL Error: ' . mysql_error();
						$pfx_step = 1;
						break;
					}
					foreach ($pfx_found_tables as $pfx_table_name_delete) {
						$pfx_sql = "DROP TABLE {$pfx_database}{$pfx_table_name_delete}";
						if ($pfx_result = mysql_query($pfx_sql)) {
						} else {
							$pfx_error      = "Error deleting $pfx_table_name_delete . MySQL Error : " . mysql_error() . "";
							$pfx_step = 1;
							break;
						}
					}
				}
*/

if ( (isset($_SESSION['prefix']) ) && ($_SESSION['prefix']) ) {
} else {
	$_SESSION['prefix'] = FALSE;
}
date_default_timezone_set( sterilise($_SESSION['server_timezone']) );
if ( filesize('../robots.txt') < 60 ) {
	$pfx_robo = "
User-agent: *
Disallow: /files/
Disallow: /admin/
Disallow: /install/
Disallow: /src/
Sitemap: {$_SESSION['url']}sitemap.xml.php

	";
	$pfx_fr = fopen('../robots.txt', 'w');
	fwrite($pfx_fr, $pfx_robo);
	fclose($pfx_fr);
	@chmod('../robots.txt', 0644);
}
include_once '../admin/lib/lib_crypt.php';
$pfx_crypt = new encryption_class;
/* Create and load the config file */
if ( filesize('../admin/config.php') < 10 ) {
	if ( $pfx_fc = fopen('../admin/config.php', 'a') ) {
		$pfx_key = hash('sha256', uniqid(rand(), TRUE));
		$pfx_dup = $pfx_crypt->encrypt($pfx_key, $_SESSION['db_usr_password']);
		include_once 'conflib.php';
			if ( fwrite($pfx_fc, $pfx_conf_data) ) {
				fclose($pfx_fc);
				@chmod('../admin/config.php', 0640);
			}
	}
}
/* Load the config file, connect to the database and add the tables */
if ( (isset($pfx_error)) && ($pfx_error) ) {

} else {
	include_once '../admin/config.php';
	include_once '../admin/lib/lib_db.php';
	$pfx_nonce = hash('sha256', uniqid( rand(), TRUE) );
	if ( $_SESSION['clean_urls_check'] == 'yes' ) {
		$pfx_rss_plugin_url = "{$_SESSION['url']}blog/rss";
	} else {
		$pfx_rss_plugin_url = "{$_SESSION['url']}?s=blog&amp;x=rss";
	}
	include_once '../admin/lib/lib_validate.php';
	include_once 'libtables.php';
	/* Install the base layer sql */
	$pfx_ok = safe_query($pfx_sql0);
	$pfx_ok = safe_query($pfx_sql1);
	$pfx_ok = safe_query($pfx_sql2);
	$pfx_ok = safe_query($pfx_sql3);
	$pfx_ok = safe_query($pfx_sql4);
	$pfx_ok = safe_query($pfx_sql5);
	$pfx_ok = safe_query($pfx_sql6);
	$pfx_ok = safe_query($pfx_sql7);
	$pfx_ok = safe_query($pfx_sql8);
	$pfx_ok = safe_query($pfx_sql9);
	$pfx_ok = safe_query($pfx_sql10);
	$pfx_ok = safe_query($pfx_sql11);
	$pfx_ok = safe_query($pfx_sql12);
	$pfx_ok = safe_update('pfx_settings', $pfx_sql13, "settings_id ='1'");
	$pfx_ok = safe_query($pfx_sql14);
	$pfx_ok = safe_query($pfx_sql15);
	$pfx_ok = safe_insert('pfx_users', $pfx_sql16);
	$pfx_ok = safe_update('pfx_settings', "site_author = '{$_SESSION['name']}', site_copyright = '{$_SESSION['name']}'", "settings_id ='1'");
	$pfx_ok = safe_update('pfx_users', $pfx_sql17, "user_id ='1'");
	$pfx_ok = safe_update('pfx_users', $pfx_sql18, "user_id ='1'");
	$pfx_ok = safe_update('pfx_users', $pfx_sql19, "user_id ='1'");
	$pfx_ok = safe_query($pfx_sql20);
	$pfx_ok = safe_query($pfx_sql21);
	$pfx_ok = safe_query($pfx_sql22);
	$pfx_ok = safe_query($pfx_sql23);
	$pfx_ok = safe_query($pfx_sql24);
	$pfx_ok = safe_query($pfx_sql25);
	$pfx_ok = safe_query($pfx_sql26);
	$pfx_ok = safe_query($pfx_sql27);
	$pfx_ok = safe_query($pfx_sql28);
	$pfx_ok = safe_query($pfx_sql29);
	$pfx_ok = safe_query($pfx_sql30);
	$pfx_ok = safe_query($pfx_sql31);
	$pfx_ok = safe_query($pfx_sql32);
	$pfx_ok = safe_query($pfx_sql33);
	$pfx_ok = safe_query($pfx_sql34);
	$pfx_ok = safe_query($pfx_sql35);
	$pfx_ok = safe_query($pfx_sql36);
	$pfx_ok = safe_query($pfx_sql37);
	$pfx_ok = safe_query($pfx_sql38);
	$pfx_ok = safe_query($pfx_sql39);
	$pfx_ok = safe_query($pfx_sql40);
	$pfx_ok = safe_query($pfx_sql41);
	$pfx_ok = safe_query($pfx_sql42);
	if ($pfx_ok !== TRUE) {
		$pfx_error = $pfx_lang['installer_db_add_tables_failed'];
	}
}

if ( (isset($pfx_error)) && ($pfx_error) ) {
echo "<?xml version=\"1.0\"?><root><message>{$pfx_error}</message></root>";
} else {
	include_once 'librules.php';
	if ( filesize('../.htaccess') < 1300 ) {
		$pfx_fh = fopen('../.htaccess', 'w');
		fwrite($pfx_fh, $pfx_hta);
		fclose($pfx_fh);
		@chmod('../.htaccess', 0644);
	}
	/* Log the install */
	include '../admin/lib/lib_logs.php';
	logme("{$pfx_lang['installer_log_warn1']}", 'yes', 'error');
	if (is_writable('../.htaccess')) {
		logme("{$pfx_lang['installer_log_warn2']}", 'yes', 'error');
	}
	if (is_writable('../admin/config.php')) {
		logme("{$pfx_lang['installer_log_warn3']}", 'yes', 'error');
	}
	if (is_writable('../robots.txt')) {
		logme("{$pfx_lang['installer_log_warn5']}", 'yes', 'error');
	}
	logme("{$pfx_lang['installer_log_welcome1']} {$pfx_version} {$pfx_lang['installer_log_welcome2']} " . phpversion() . " {$pfx_lang['installer_log_welcome3']} <a href=\"http://heydojo.co.cc\" target=\"_blank\">http://heydojo.co.cc</a> {$pfx_lang['installer_log_welcome4']}", 'no', 'site');
	$pfx_emessage = "
{$pfx_lang['installer_hello']} {$_SESSION['name']},
{$pfx_lang['installer_congrats']} {$pfx_lang['installer_good']} {$pfx_lang['installer_details']}

{$pfx_lang['form_username']} : {$_SESSION['login_username']}

{$pfx_lang['installer_visit']} : {$_SESSION['url']} {$pfx_lang['installer_view']} {$_SESSION['url']}admin {$pfx_lang['installer_to_login']}

{$pfx_lang['installer_congrats']}
{$pfx_lang['installer_enjoy']}

heydojo.co.cc
			      ";
/* We dont email the password for the super user account because it would be dumb.
It could be done like this :
{$pfx_lang['form_password']} : {$_SESSION['login_password']}
but that's a bad idea if you put an incorrect email address. */
	$pfx_subject  = "{$pfx_lang['installer_hello']} {$_SESSION['name']}, {$pfx_lang['installer_good']}";
	$pfx_headers  = 'MIME-Version: 1.0' . "\r\n";
	$pfx_headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
	$pfx_headers .= 'Content-transfer-encoding: 8bit' . "\r\n";
	$pfx_headers  .= "From: postmaster@{$_SERVER['HTTP_HOST']}" . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n";
	mail($_SESSION['email'], $pfx_subject, $pfx_emessage, $pfx_headers);
	/* Install complete! */
	session_destroy();
	echo "<?xml version=\"1.0\"?><root><message><p><b>{$pfx_lang['installer_congrats']}</b></p></message></root>";
}