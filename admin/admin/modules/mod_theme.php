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
 * Title: Theme Settings
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
if ($GLOBALS['pfx_user'] && $GLOBALS['pfx_user_privs'] >= 2) {
	if ((isset($pfx_do)) && ($pfx_do)) {
		$pfx_do = sterilise($pfx_do);
		if (file_exists("themes/{$pfx_do}")) {
			$pfx_ok = safe_update("pfx_settings", "site_theme = '{$pfx_do}'", "settings_id ='1'");
		}
		if ($pfx_ok) {
			$pfx_messageok = $pfx_lang['theme_ok'];
		} else {
			$pfx_message = $pfx_lang['theme_error'];
		}
	}
	define('MOD_THEME', fetch('site_theme', "pfx_settings", "settings_id", 1));
	echo "<h2>{$pfx_lang['nav2_theme']}</h2>\n\t\t\t\t<p>{$pfx_lang['theme_info']}</p><div id=\"themes\"><h3>{$pfx_lang['theme_pick']}</h3>";
	$pfx_dir = 'themes/';
	if (is_dir($pfx_dir)) {
		$pfx_fd = @opendir($pfx_dir);
		if ($pfx_fd) {
			while (($pfx_part = @readdir($pfx_fd)) == true) {
				if ($pfx_part != '.' && $pfx_part != '..') {
					$pfx_newdir = $pfx_dir.$pfx_part;   
					if (is_dir($pfx_newdir) && preg_match('/^[A-Za-z].*[A-Za-z]$/', $pfx_part)) {
						include "themes/{$pfx_part}/settings.php";
						if ($pfx_part == MOD_THEME) {
							echo "<div class=\"atheme currenttheme\"><h3 class=\"tname\">{$pfx_theme_name}</h3><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;do={$pfx_part}\" title=\"{$pfx_lang['theme_apply']}\"><img src=\"themes/{$pfx_part}/thumb.png\" alt=\"{$pfx_lang['nav2_theme']}: {$pfx_theme_name}\" class=\"ticon\"/></a><span class=\"tcreator\">{$pfx_lang['by']} <a href=\"{$pfx_theme_link}\" target=\"_blank\">{$pfx_theme_creator}</a></span><span class=\"tselect\">Current theme</span></div>\n"; 
						} else { 
							echo "<div class=\"atheme\"><h3 class=\"tname\">{$pfx_theme_name}</h3><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;do={$pfx_part}\" title=\"{$pfx_lang['theme_apply']}\"><img src=\"themes/{$pfx_part}/thumb.png\" alt=\"{$pfx_lang['nav2_theme']}: {$pfx_theme_name}\" class=\"ticon\"/></a><span class=\"tcreator\">{$pfx_lang['by']} <a href=\"{$pfx_theme_link}\" target=\"_blank\">{$pfx_theme_creator}</a></span><span class=\"tselect\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;do={$pfx_part}\" title=\"{$pfx_lang['theme_apply']}\">{$pfx_lang['theme_apply']}</a></span></div>\n"; 
						}
					}
				}
			}
		}
	}
	echo '</div>';
}