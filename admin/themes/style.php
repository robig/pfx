<?php
header('Content-type: text/css');
header('Vary: Accept-Encoding');
/* Declare the output of the file as CSS */
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
 * Title: Style Import
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
if ( $pfx_refering = parse_url(($_SERVER['HTTP_REFERER'])) ) {
} else {
	$pfx_refering = FALSE;
}
if ( (isset($_SERVER['HTTP_HOST'])) && ($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST']) && ($pfx_refering['host'] == $_SERVER['HTTP_HOST']) ) {
	if ( (defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG')) ) {
		require_once '../lib/lib_misc.php';
		exit( pfxExit() );
	}
	define('DIRECT_ACCESS', 1);
	require_once '../lib/lib_misc.php';
	/* Perform basic sanity checks */
	bombShelter();
	/* Check URL size */
	if (PFX_DEBUG == 'yes') {
		error_reporting(-1);
	} else {
		error_reporting(0);
	}
	if ( (isset($_SERVER['HTTP_HOST'])) && ($_SERVER['HTTP_HOST']) ) {
		define('PFX_THEME_NAME', trim(str_replace('//', "", $_REQUEST['theme'])));
		if ( (isset($_REQUEST['theme'])) && ($_REQUEST['theme']) ) {
			if (file_exists(PFX_THEME_NAME . '/core.css')) {
				echo '@import url(' . PFX_THEME_NAME . '/core.css);';
			}
			if (file_exists(PFX_THEME_NAME . '/layout.css')) {
				echo '@import url(' . PFX_THEME_NAME . '/layout.css);';
			}
			if (file_exists(PFX_THEME_NAME . '/navigation.css')) {
				echo '@import url(' . PFX_THEME_NAME . '/navigation.css);';
			}
			if ( (isset($_REQUEST['s'])) && ($_REQUEST['s']) ) {
				$pfx_file = PFX_THEME_NAME . "/{$_REQUEST['s']}.css";
				if (file_exists($pfx_file)) {
					echo '@import url(' . PFX_THEME_NAME . "/{$pfx_file});";
				}
			}
		}
	}
	/* The CSS should output here */
} else {
	exit( header('Location: ../../') );
}