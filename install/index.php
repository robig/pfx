<?php
session_name('installer');
session_start();
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
 * Title: Installer
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
 * @release Code Red 
 *
 */
if (!defined('DIRECT_ACCESS')) {
	define('DIRECT_ACCESS', 1);/* Important to set this first */
}
require_once '../admin/lib/lib_misc.php';
bombShelter();
/* Set error reporting up if debug is enabled */
if (PFX_DEBUG == 'yes') {
	error_reporting(-1);
} else {
	error_reporting(0);
}
include_once '../admin/lib/lib_lang.php';
include_once '../admin/lib/lib_tz.php';
$pfx_version = '1.05';
$pfx_charset = 'UTF-8';
$pfx_db_charset = str_replace('-', '', strtolower($pfx_charset));
/* $pfx_version and $pfx_charset should always be the same charset and should only change if the installer language is changed
See admin/admin/modules/mod_pfx.php - $pfx_charset_list for a list of charsets we can _try_ to use.
*/
$pfx_db_collate = 'utf8_unicode_ci'; /* Don't use utf8_general_ci -- We want to be able to read the database. */ /* http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html */
/*
I think we can do it like this : If we use the language selection to use a the basis for the charset selection, we
can then use mysql> SHOW CHARACTER SET; once a database connection has been established, to reveal the default
collation. Then we can use that default collation as the db_charset but we always use unicode for English.
The problem this creates is that to be readable in whatever language the database is collated in, it probbably needs
to be converted to utf8 after it is exported but then back into the orginal charset before importing. That wouldn't
apply to databases already collated in utf8.

http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html
http://dev.mysql.com/doc/refman/5.0/en/charset-unicode-utf8.html
*/
$pfx_url             = str_replace('install/', "", "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}");
$pfx_user            = 'PFX Installer';
extract($_POST, EXTR_PREFIX_ALL, 'pfx');
$_REQUEST = NULL;
if ( @filesize('autoinstall.php') > 60 ) {
	include_once 'autoinstall.php';
	if ( (isset($_SERVER['HTTP_MOD_REWRITE'])) && ($_SERVER['HTTP_MOD_REWRITE'] == 'On') ) {
		$pfx_clean_urls_check = 'yes';
	} else {
		$pfx_clean_urls_check = 'no';
	}
	$pfx_db_charset = str_replace('-', '', strtolower($pfx_charset));
	include_once 'si.php';
	include_once 'di.php';
	include_once 'sui.php';
	include_once 'process.php';
	exit( header("Location: {$pfx_url}") );
} else {
	if ( (isset($pfx_step)) && ($pfx_step) ) {

	    switch ($pfx_step) {
		case 1 :
		    include_once 'si.php';
		break;
		case 2 :
	    include_once 'di.php';
		break;
		case 3 :
		    include_once 'sui.php';
		break;
		case 4 :
		    include_once 'process.php';
		break;
	    }

	} else {
		if (strnatcmp(phpversion(), '5.2.14') <= 0) {
			/* Warn about unsupported php version here */
			$pfx_error = "{$pfx_lang['installer_php_version_warn1']} " . phpversion() . " {$pfx_lang['installer_php_version_warn2']}";
		}
		date_default_timezone_set('GMT');
		include_once 'ui.php';
	}
}