<?php
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
 * Title: Ajax page order system
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
$pfx_refering = NULL;
$pfx_refering = parse_url(($_SERVER['HTTP_REFERER']));
if ( ($pfx_refering['host'] == $_SERVER['HTTP_HOST']) ) {
	if ( (defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG')) ) {
		require_once '../../lib/lib_misc.php';
		exit(pfxExit());
	}
	define('DIRECT_ACCESS', 1);
	require_once '../../lib/lib_misc.php';
	/* perform basic sanity checks */
	bombShelter();
	/* check URL size */
	if (PFX_DEBUG == 'yes') {
		error_reporting(-1);
	} else {
		error_reporting(0);
	}
	if ($_POST['pages']) {
		require_once '../../config.php';
		require_once '../../lib/lib_crypt.php';
		/* Import crypt library */
		$crypt = new encryption_class;
		include_once '../../lib/lib_db.php';
		include_once '../../lib/lib_validate.php';
		include_once '../../lib/lib_auth.php';
		$pfx_count = count($_POST['pages']);
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 2) {
			$pfx_i = 0;
			while ($pfx_i < $pfx_count) {
				$pfx_page_name = $_POST['pages'][$pfx_i];
				safe_update('pfx_core', "page_order  = {$pfx_i} + 1", "page_name = '{$pfx_page_name}'");
				$pfx_i++;
			}
		}
	}
	/* This file should be merged as an include or merged directly into another file instead of it being directly accessed like this. */
} else {
	exit( header('Location: ../../../') );
}