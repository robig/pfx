<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../../../') );
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
 * Title: Settings
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
$pfx_x = sterilise_url($pfx_x);
if (isset($pfx_username)) {
} else {
	$pfx_username = FALSE;
}
if (isset($_COOKIE['pfx_login'])) {
	list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
	$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
	if ( hash('sha256', $pfx_username . $pfx_nonce) == $pfx_cookie_hash ) {
		$pfx_privs = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'");
		if ($pfx_privs >= 2) {
			if (file_exists("admin/modules/mod_{$pfx_x}.php")) {
				include_once("admin/modules/mod_{$pfx_x}.php");
			} else {
				$pfx_message = "Admin module {$pfx_x} has been removed from the admin modules folder.";
			}
		}
	}
}