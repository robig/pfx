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
 * Title: Installer configuration generation
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
$pfx_conf_data = "<?php
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
	define('CONFIG_RANDOM', '{$pfx_key}');
	define('CONFIG_DB', '{$_SESSION['db_database']}');
	define('CONFIG_USER', '{$_SESSION['db_username']}');
	define('CONFIG_PASS', '{$pfx_dup}');
	define('CONFIG_HOST', '{$_SESSION['host']}');
	define('CONFIG_TABLE_PREFIX', '{$_SESSION['prefix']}');
	define('CONFIG_TIME_ZONE', '{$_SESSION['server_timezone']}');
	define('CONFIG_CHARSET', '{$pfx_charset}');
}";