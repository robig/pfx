<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../../') );
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
 * Title: Digg RSS Feed Block
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
include_once 'admin/lib/lib_simplepie.php'; /* Include simplepie */
$pfx_number_of_items = 24; /* Set the maximum number of items here */
$pfx_digg_username   = 'kevinrose'; /* Add your digg user name here */
$pfx_rss_url         = "http://digg.com/users/{$pfx_digg_username}/history.rss"; /* Digg's feed url */
$pfx_show_errors     = 'no'; /* Block not showing any content? Set this to yes to find out why */ /* If you get a curl error and curl is installed, it's a simplepie bug because unfortunately the simplepie developers insist on using curl unfortunately */
$pfx_new_tab   = 'no'; /* Open the links in a new window or tab? */
$pfx_cache_admin = 'no'; /* Cache path is relative showRss needs to know this */
?>
    <div id="block_digg" class="block">
	<div class="block_header">
	    <h4>I <a href="http://digg.com/">Digg</a> :</h4>
	</div>
	    <div class="block_body">
		<ul>
		    <?php
		    showRss($pfx_number_of_items, $pfx_rss_url, $pfx_show_errors, $pfx_new_tab, $pfx_cache_admin);
		    ?>
		</ul>
	    </div>
	<div class="block_footer"></div>
    </div>