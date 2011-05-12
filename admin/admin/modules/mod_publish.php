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
 * Title: Publish
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
if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 1) {
	if ( (isset($pfx_m)) && ($pfx_m) ) {
		if ($pfx_x) {
			if ($pfx_m == 'module') {
				if (file_exists("../admin/modules/{$pfx_x}.php")) {
					$pfx_do = 'admin';
					include("../admin/modules/{$pfx_x}.php");
				} else {
					$pfx_message = "Page {$pfx_x} has been removed (check if module has been removed from modules folder).";
				}
			} else {
				if (file_exists("../admin/modules/{$pfx_m}.php")) {
					$pfx_do = 'admin';
					include("../admin/modules/{$pfx_m}.php");
				} else {
					$pfx_message = "Page {$pfx_x} has been removed (check if module has been removed from modules folder).";
				}
			}
		} else {
			if ( (isset($pfx_x)) && ($pfx_x) ) {
				$pfx_core_type_m = safe_row('*', 'pfx_core', "page_type = '{$pfx_m}' and publish = 'yes' and privs <= '{$GLOBALS['pfx_user_privs']}' order by page_views desc limit 0,1");
				extract($pfx_core_type_m, EXTR_PREFIX_ALL, 'pfx');
				$pfx_core_type_m = NULL;
				$pfx_x = $pfx_page_name;
				if ($pfx_m == 'module') {
					if (file_exists("../admin/modules/{$pfx_x}.php")) {
						$pfx_do = 'admin';
						include("../admin/modules/{$pfx_x}.php");
					} else {
						$pfx_message = "Page {$pfx_m} has been removed (check if module has been removed from modules folder).";
					}
				} else {
					if (file_exists("../admin/modules/{$pfx_m}.php")) {
						$pfx_do = 'admin';
						include("../admin/modules/{$pfx_m}.php");
					} else {
						$pfx_message = "Page {$pfx_m} has been removed (check if module has been removed from modules folder).";
					}
				}
			}
		}
	} else {
		if (file_exists("admin/modules/mod_{$pfx_x}.php")) {
			$pfx_do = 'admin';
			include("admin/modules/mod_{$pfx_x}.php");
		} else {
			$pfx_message = "Admin module {$pfx_x} has been removed from the admin modules folder.";
		}
	}
}