<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header( 'Location: ../../' ) );
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
 * Title: lib common
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
if ( (defined('CONFIG_COMMON')) && (CONFIG_COMMON == 'admin') ) {
	$pfx_inc_path = 'lib/';
} else if ( (defined('CONFIG_COMMON')) && (CONFIG_COMMON == 'index') ) {
	$pfx_inc_path = 'admin/lib/';
} else {
	exit();
}
require_once "{$pfx_inc_path}lib_defs.php";
require_once "{$pfx_inc_path}lib_misc.php";
/* perform basic sanity checks */
bombShelter();
/* check URL size */
if (PFX_DEBUG == 'yes') {
	error_reporting(-1);
} else {
	error_reporting(0);
}
/* set error reporting up if debug is enabled */
extract($_REQUEST, EXTR_PREFIX_ALL, 'pfx');
$_REQUEST = NULL;
/* access to form vars if register globals is off */
if (CONFIG_COMMON == 'admin') {
    require_once 'config.php';
} else if (CONFIG_COMMON == 'index') {
    require_once 'admin/config.php';
}
if (defined('CONFIG_TIME_ZONE')) {
	date_default_timezone_set(CONFIG_TIME_ZONE);
} else {
	date_default_timezone_set('GMT');
}
/* load configuration */
require_once "{$pfx_inc_path}lib_crypt.php";
/* Import crypt library */
include_once "{$pfx_inc_path}lib_db.php";
/* import the database function library */
get_prefs();
if ( (defined('PREFS_GZIP')) && (PREFS_GZIP == 'yes') && (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) && (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) && (@extension_loaded('zlib')) ) /* Start gzip compression */ {
    if (@ob_start('ob_gzhandler')) {
	header('Content-Encoding: gzip');
    }
}
header('Vary: Accept-Encoding');
if (defined('PREFS_TIMEZONE')) {
	date_default_timezone_set(PREFS_TIMEZONE);
}
if (defined('PFX_LICENSE')) {
} else {
    define('PFX_LICENSE', '<!--
	PFX Powered (heydojo.co.cc)
	Licence: GNU General Public License v3 http://www.gnu.org/licenses/gpl-3.0.html
	Pixie Copyright (C) 2008 - 2010, Scott Evans
	PFX Copyright (C) ' . date('Y') . ', Tony White

	Site Name :		' . PREFS_SITE_NAME . '
	Developed By :		' . PREFS_SITE_AUTHOR . '
	Copyright :		' . PREFS_SITE_COPYRIGHT . '
	Page Date Generated :	' . date( 'd/m/Y', time() ) . '
	Page Time Generated :	' . date( 'H:i:s', time() ) . '
	-->
	'
    );
}
/* Tell php what the server timezone is so that we can use php 5's rewritten time and date functions to set the correct time without error messages */
include_once "{$pfx_inc_path}lib_lang.php";
/* import the language file */
include_once "{$pfx_inc_path}lib_logs.php";
/* import the log library */
include_once "{$pfx_inc_path}lib_validate.php";
/* import the validate library */
include_once "{$pfx_inc_path}lib_date.php";
/* import the date library */
pagetime('init');
/* start the runtime clock */
if (CONFIG_COMMON == 'admin') {
    include_once 'lib/lib_auth.php';
    /* check user is logged in */
    require 'lib/htmlpurifier/library/HTMLPurifier.php';
/* Needs to be optional */
    require 'lib/htmlpurifier/library/standalone/HTMLPurifier/Filter/YouTube.php';
/* Needs to be optional */
    $pfx_purify_config = HTMLPurifier_Config::createDefault();
    $pfx_purify_config->set('Cache.SerializerPath', '../files/cache');
    $pfx_purify_config->set('HTML.SafeObject', true);
    $pfx_purify_config->set('HTML.SafeEmbed', true);
    $pfx_purify_config->set('Output.FlashCompat', true);
    $pfx_purify_config->set('HTML.Trusted', true);
/* Needs to be optional */
    $pfx_purify_config->set('Filter.YouTube', true);
/* Needs to be optional */
    $pfx_purifier = new HTMLPurifier($pfx_purify_config);
    /* import the HTMLPurifier library */
    include_once 'lib/lib_core.php';
    /* import the core library */
    include_once 'lib/lib_upload.php';
    /* import the upload library */
    include_once 'lib/lib_backup.php';
    /* import the backup library */
}
include_once "{$pfx_inc_path}lib_paginator.php";
/* import the paginator library */
if (CONFIG_COMMON == 'index') {
    include_once 'admin/lib/lib_pfx.php';
    if (PREFS_CAPTCHA == 'yes') {
	/* Import the pfx library */
	include_once 'admin/lib/lib_recaptcha.php';
    }
    /* Browser detection */
    if ( (file_exists('admin/themes/' . PREFS_SITE_THEME . '/settings.php')) ) {
	/* Load the current themes settings */
	include_once 'admin/themes/' . PREFS_SITE_THEME . '/settings.php';
    }
}
include_once "{$pfx_inc_path}lib_rss.php";
/* import the rss library */
include_once "{$pfx_inc_path}lib_tags.php";
/* import the tags library */
include_once "{$pfx_inc_path}bad-behavior-pfx.php";
/* no spam please */
include_once "{$pfx_inc_path}lib_browser_detect.php";
/* Browser detection */
define( 'B_NAME', browser_detection('browser') );
define( 'B_VERSION', browser_detection('number') );
define( 'B_TYPE', browser_detection('type') );
if ( (B_NAME == 'ie' ) && (B_VERSION <= 6) ) {
    define('IE6_USER', TRUE);
} else {
    define('IE6_USER', FALSE);
}
function ie6_check($pfx_ie_warning) {

	if (IE6_USER == TRUE) { echo "<p><label class=\"error\">{$pfx_ie_warning}</label></p>"; }

}
if (CONFIG_COMMON == 'index') {
    /* Let the REAL magic begin */
    $pfx_resolve = pfx($pfx_rel_path, $pfx_style, $pfx_page_display_name, $pfx_page_type, $pfx_page_id, $pfx_staticpage, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
    extract($pfx_resolve, EXTR_PREFIX_ALL, 'pfx');
    $pfx_resolve = NULL;
    if ( (isset($pfx_ptitle)) && ($pfx_ptitle) ) {
	$pfx_page_display_name = $pfx_ptitle;
    } else {
	    if ( (isset($pfx_page_display_name)) && ($pfx_page_display_name) ) {
	    } else {
		    $pfx_page_display_name = FALSE;
	    }
	    if ( (isset($pfx_page_type)) && ($pfx_page_type) ) {
	    } else {
		    $pfx_page_type = FALSE;
	    }
	    $pfx_ptitle = build_title($pfx_page_display_name, $pfx_page_type, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
    }
    if ( (isset($pfx_s)) && ($pfx_s == 404) ) {
	/* Send correct header for 404 pages */
	header('HTTP/1.0 404 Not Found');
    }
    if ( (defined('BAD_BOT')) && (BAD_BOT == 'bad') ) {
	if ( file_exists('admin/modules/deny.php') ) {
		$pfx_do = 'default';
		exit( include_once 'admin/modules/deny.php' );
	}
    }
    if ( file_exists('admin/modules/deny.php') ) {
	$pfx_do = 'pre';
	include_once 'admin/modules/deny.php';
    }
    /* Current site visitors */
    users_online();
    if ($pfx_m == 'rss') {
	if ( (isset($pfx_s)) && ($pfx_s) ) {
		/* RSS */
		exit( rss($pfx_s, $pfx_page_display_name, $pfx_page_id, $pfx_lang, $pfx_s) );
	}
    } else {
	/* Referral */
	referral($pfx_lang, $pfx_page_display_name, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
	if ( (isset($pfx_s)) && ($pfx_s) ) {
		/* Load this page's description */
		$pfx_page_description = safe_field('page_description', 'pfx_core', "page_name='{$pfx_s}'");
	}
	if ($pfx_page_type == 'module') {
		if (isset($pfx_s)) {
			if ( file_exists("admin/modules/functions/{$pfx_s}_functions.php") ) {
				include_once "admin/modules/functions/{$pfx_s}_functions.php";
			}
			/* Load the module in pre mode */
			if ( file_exists("admin/modules/{$pfx_s}.php") ) {
				$pfx_do = 'pre';
				include_once "admin/modules/{$pfx_s}.php";
			}
		}
	}
	/* Theme Override Super Feature */
	if ( file_exists('admin/themes/' . PREFS_SITE_THEME . '/theme.php') ) {
		/* New! Your custom theme file must be named theme.php instead of index.php */
		exit( include_once 'admin/themes/' . PREFS_SITE_THEME . '/theme.php' );
	}
		/* By default use the regular Pfx template */
    }
}

if (CONFIG_COMMON == 'admin') {
    $pfx_s = check_404($pfx_s);
    /* check section exists */
    if ( (isset($pfx_s)) && (isset($pfx_do)) && ($pfx_do == 'rss') && (isset($pfx_nonce)) ) {
	exit( adminrss($pfx_user, $pfx_nonce, $pfx_lang, $pfx_s) );
    }
}