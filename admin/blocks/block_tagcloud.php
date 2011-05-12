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
 * Title: Tag Cloud block
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
if (isset($pfx_s)) {
	$pfx_id   = get_page_id($pfx_s);
	$pfx_type = check_type($pfx_s);
	if ($pfx_type == 'dynamic') {
		$pfx_table = 'pfx_dynamic_posts';
	} else if ($pfx_type == 'module') {
		$pfx_table = "pfx_module_{$pfx_s}";
	}
	echo "<div id=\"block_tagcloud\" class=\"block\">\n\t\t\t\t\t\t<div class=\"block_header\">\n\t\t\t\t\t\t\t<h4>{$pfx_lang['tags']}</h4>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t\t<div class=\"block_body\">\n";
	if ($pfx_type == 'dynamic') {
		public_tag_cloud($pfx_table, "page_id = {$pfx_id} and public = 'yes'", $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
	} else {
		$pfx_condition = "{$pfx_s}_id >= '0'";
		if (isset($pfx_table)) {
			public_tag_cloud($pfx_table, $pfx_condition, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
		}
	}
	echo '</div><div class="block_footer"></div></div>';
}