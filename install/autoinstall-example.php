<?php
if (!defined('DIRECT_ACCESS')) { exit( header( 'Location: ../' ) ); }
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
 * Title: Installer automatic installation config file
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

/* This file is designed as a config file to be used by a wrapper script of your own creation to automate installing
PFX. A wrapper is not required. It can be used without; however the regular install method is recommended instead
in most cases. To use this method; edit this file to reflect the correct information, rename this file to autoinstall.php
then make writeable the files .htaccess robots.txt admin/config.php and the folder /files/ (Recursively.)
Start the autoinstall by visiting the site's front page url. */

/* Site Information */
$pfx_langu = 'en-gb'; /* Language */
$pfx_server_timezone = 'Europe/London';		/* Timezone http://php.net/manual/en/timezones.php */
$pfx_url = 'http://somesite.org/pfx/';		/* The online path to your PFX installation */
$pfx_sitename = 'My PFX Site';			/* The name of your site */
$pfx_collate = 'UTF-8';				/* The database collation to use site wide. UTF-8 is mostly correct here. See admin/admin/modules/mod_pfx.php - $pfx_charset_list for a list of charsets we can _try_ to use. */
$pfx_db_charset = 'utf8_unicode_ci';		/* The charset to use in the database. utf8_unicode_ci is mostly correct here */
/* Database Information */
$pfx_host = 'localhost';			/* The name of the database host, usually localhost */
$pfx_db_username = 'root';			/* The name of the database user account you want to use */
$pfx_db_usr_password = 'somedatabasepassword';	/* The password of the database user account you want to use */
$pfx_database = 'pfxdb';			/* The name of the database you want to use */
$pfx_create_db = 'no';				/* Creates a database of the same name as the name above if set to yes */
$pfx_prefix = '';				/* A database table prefix like data_ (Optional - Leave empty for no) */
/* Super User Information */
$pfx_name = 'Admin';				/* The PFX super user account's full real name */
$pfx_login_username = 'admin';			/* The PFX super user account's login name */
$pfx_email = 'dev@dev.null';			/* The PFX super user account's email address */
$pfx_login_password = 'someloginpassword';	/* The PFX super user account's password */