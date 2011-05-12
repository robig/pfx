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
 * Title: Installer database information form processing
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
$_SESSION['host'] = sterilise_txt($pfx_host);
$_SESSION['db_username'] = sterilise_txt($pfx_db_username);
$_SESSION['db_usr_password'] = addslashes( sterilise_txt($pfx_db_usr_password) );
$_SESSION['db_database'] = sterilise_txt($pfx_database);
if ( (isset($pfx_create_db)) or ($pfx_create_db) ) {
	$_SESSION['create_db'] = sterilise_txt($pfx_create_db);
} else {
	$_SESSION['create_db'] = 'no';
}
if ( (isset($_SESSION['db_created'])) && ($_SESSION['db_created']) ) {
	$_SESSION['db_created'] = sterilise_txt($_SESSION['db_created']);
} else {
	$_SESSION['db_created'] = 'no';
}
$_SESSION['prefix'] = sterilise_txt($pfx_prefix);
if ( (isset($_SESSION['prefix'])) && ($_SESSION['prefix'] == 'pfx_') ) {
	$_SESSION['prefix'] = date('Hisd') . '_';
}
date_default_timezone_set( sterilise_txt($_SESSION['server_timezone']) );
$pfx_link = mysql_connect($_SESSION['host'], $_SESSION['db_username'], $_SESSION['db_usr_password']);
if ($pfx_link) {
	if ($_SESSION['db_created'] == 'yes') {
		$pfx_db_selected = mysql_select_db($_SESSION['db_database'], $pfx_link);
		if ($pfx_db_selected) {
		} else {
			if ( mysql_query("CREATE DATABASE {$_SESSION['db_database']} CHARACTER SET utf8 COLLATE {$pfx_db_collate}") ) {
				$_SESSION['db_created'] = 'yes';
			} else {
				$pfx_error = $pfx_lang['installer_db_create_failed'];
				$_SESSION['db_created'] = 'no';
			}
		}
	}
	if ( (isset($_SESSION['create_db'])) && ($_SESSION['create_db'] == 'yes') && ($_SESSION['db_created'] === 'no') ) {
		if ( mysql_query("CREATE DATABASE {$_SESSION['db_database']} CHARACTER SET utf8 COLLATE {$pfx_db_collate}") ) {
			$_SESSION['db_created'] = 'yes';
		} else {
			$pfx_error = $pfx_lang['installer_db_create_failed'];
			$_SESSION['db_created'] = 'no';
		}
	}
	if ( (isset($pfx_error)) && ($pfx_error) ) {
		echo "<?xml version=\"1.0\"?><root><message>{$pfx_error} " . htmlspecialchars( mysql_error(), ENT_QUOTES,'UTF-8' ) . "</message></root>";
	} else {
		echo '<?xml version="1.0"?><root><message>Step 2</message></root>';
	}
} else {
	echo "<?xml version=\"1.0\"?><root><message>{$pfx_lang['installer_db_connect_failed']} " . htmlspecialchars( mysql_error(), ENT_QUOTES,'UTF-8' ) . "</message></root>";
}