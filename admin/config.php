<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../') );
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
 * Title: Configuration settings
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
if ( (!defined('CONFIG_RANDOM')) OR (!defined('CONFIG_DB')) OR (!defined('CONFIG_USER')) OR (!defined('CONFIG_PASS')) OR (!defined('CONFIG_HOST')) OR (!defined('CONFIG_TABLE_PREFIX')) OR (!defined('CONFIG_TIME_ZONE')) OR (!defined('CONFIG_CHARSET')) ) {
	define('CONFIG_RANDOM', '106dc88c207d2816df0ae6d35a922b6b99fe56ac8e0aedbeb865d419af44de28');
	define('CONFIG_DB', 'robig_pfx_test');
	define('CONFIG_USER', 'pfx');
	define('CONFIG_PASS', 'LeLr<');
	define('CONFIG_HOST', 'localhost');
	define('CONFIG_TABLE_PREFIX', '');
	define('CONFIG_TIME_ZONE', 'Europe/London');
	define('CONFIG_CHARSET', 'UTF-8');
}