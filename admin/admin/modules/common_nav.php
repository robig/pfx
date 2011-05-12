<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( ('Location: ../../../') );
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
 * Title: common_nav - Common navigation items
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
	if ($GLOBALS['pfx_user_privs'] >= 2) { ?>
<li><a href="?s=settings" title="<?php
				echo $pfx_lang['nav1_settings'];
?>"<?php
				if ( ($pfx_s == 'settings') && ($pfx_x !== 'site') && ($pfx_x !== 'pfx') && ($pfx_x !== 'theme') && ($pfx_x !== 'users') && ($pfx_x !== 'dbtools') ) {
					echo " class=\"nav_current_1\"";
				}
?>><?php
				echo $pfx_lang['nav1_settings'];
?></a></li>

<?php }?>

<?php
if ( (isset($pfx_default_p)) && ($pfx_s == 'settings') && ($pfx_x == 'site') ) {
$pfx_default_nav_page = substr( sterilise_url($pfx_default_p), 0, -1 );
} else {
$pfx_default_nav_page = substr( fetch('default_page', 'pfx_settings', 'settings_id', 1), 0, -1 );
}
if ($pfx_default_nav_page == 'contact') {
	$pfx_default_nav_page = 'blog';
}
			    $pfx_default_type = fetch('page_type', 'pfx_core', 'page_name', $pfx_default_nav_page);
?><li><a href="?s=publish<?php echo "&amp;m={$pfx_default_type}&amp;x={$pfx_default_nav_page}"; ?>" title="<?php
				echo $pfx_lang['nav1_publish'];
?>"<?php
				if ( ($pfx_s == 'publish') && ($pfx_x !== 'filemanager') ) {
					echo " class=\"nav_current_1\"";
				}
?>><?php
				echo $pfx_lang['nav1_publish'];
?></a></li>

<?php }?>

<li><a href="?s=myaccount" title="<?php
				echo $pfx_lang['nav1_home'];
?>"<?php
				if ( ($pfx_s == 'myaccount') && ($pfx_x !== 'myprofile') ) {
					echo " class=\"nav_current_1\"";
				}
?>><?php
				echo $pfx_lang['nav1_home'];
?></a></li>