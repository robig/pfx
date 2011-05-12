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
 * Title: Installer super user information form processing
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
$_SESSION['name'] = sterilise_txt($pfx_name);
$_SESSION['login_username'] = str_replace( " ", "", preg_replace('/\s\s+/', ' ', trim( sterilise_txt($pfx_login_username) )) );
$_SESSION['email'] = sterilise_txt($pfx_email);
$_SESSION['login_password'] = sterilise_txt( addslashes($pfx_login_password) );
date_default_timezone_set( sterilise_txt($_SESSION['server_timezone']) );
/* Here we just validate that the email is actually an email */
include_once '../admin/lib/lib_validate.php';
$pfx_check = new Validator();
$pfx_check->validateEmail($_SESSION['email'], "{$pfx_lang['user_email_error']} ");
if ( $pfx_check->foundErrors() ) {
	$pfx_error .= $pfx_check->listErrors('x');
	$pfx_err   = explode('|', $pfx_error);
	$pfx_error = $pfx_err[0];
echo "<?xml version=\"1.0\"?><root><message>{$pfx_error}</message></root>";
} else {
echo '<?xml version="1.0"?><root><message>Step 3</message></root>';
}