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
 * Title: Sitemap Generator
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
if ((defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG'))) {
	require_once 'admin/lib/lib_misc.php';
	exit(pfxExit());
}
define('DIRECT_ACCESS', 1);
require_once 'admin/lib/lib_misc.php';
bombShelter(); /* perform basic sanity checks */
/* Set error reporting up if debug is enabled */
if (PFX_DEBUG == 'yes') {
	error_reporting(-1);
} else {
	error_reporting(0);
}
require_once 'admin/config.php';
header('Content-type: application/xml; charset="' . CONFIG_CHARSET . '"', TRUE);
header('Pragma: no-cache');
include_once 'admin/lib/lib_crypt.php';
$crypt = new encryption_class;
include_once 'admin/lib/lib_lang.php';
include_once 'admin/lib/lib_db.php';
include_once 'admin/lib/lib_date.php';
include_once 'admin/lib/lib_tags.php';
get_prefs();
require_once('admin/lib/lib_sitemap.php');
$pfx_rs  = safe_rows('*', 'pfx_core', "public='yes' and page_name!='404' and page_type!='plugin' order by page_views desc");
$pfx_num = count($pfx_rs);
$pfx_i   = 0;
while ($pfx_i < $pfx_num) {
	$pfx_out          = $pfx_rs[$pfx_i];
	$pfx_pageid       = $pfx_out['page_id'];
	$pfx_pagename     = $pfx_out['page_name'];
	$pfx_type         = $pfx_out['page_type'];
	$pfx_lastmodified = $pfx_out['last_modified'];
	$pfx_url          = createURL($pfx_pagename);
	if ($pfx_type == 'dynamic') {
		$pfx_change = 'weekly';
		$pfx_rz     = safe_rows('*', 'pfx_dynamic_posts', "page_id='{$pfx_pageid}' and public = 'yes' order by post_views desc");
		$pfx_num1   = count($pfx_rz);
		$pfx_j      = 0;
		while ($pfx_j < $pfx_num1) {
			$pfx_dynpg             = $pfx_rz[$pfx_j];
			$pfx_dynpgslug         = $pfx_dynpg['post_slug'];
			$pfx_dynpglastmodified = $pfx_dynpg['last_modified'];
			$pfx_dynpgurl          = createURL($pfx_pagename, 'permalink', $pfx_dynpgslug);
			$pfx_log               = returnUnixtimestamp($pfx_dynpglastmodified);
			$pfx_dynpglm           = safe_strftime($pfx_lang, '%Y-%m-%d', $pfx_log);
			$pfx_cats[]            = array(
				'loc' => $pfx_dynpgurl,
				'changefreq' => 'yearly',
				'lastmod' => $pfx_dynpglm
			);
			$pfx_j++;
		}
		// pagination
		$pfx_total      = count(safe_rows('*', 'pfx_dynamic_posts', "page_id='{$pfx_pageid}' and public = 'yes' order by post_views desc"));
		$pfx_show       = safe_field('posts_per_page', 'pfx_dynamic_settings', "page_id='{$pfx_pageid}'");
		$pfx_roundup    = ceil($pfx_total / $pfx_show);
		$pfx_latestdate = safe_field('posted', 'pfx_dynamic_posts', "page_id='{$pfx_pageid}' and public = 'yes' order by posted desc limit 1");
		$pfx_log        = returnUnixtimestamp($pfx_latestdate);
		$pfx_recent     = safe_strftime($pfx_lang, '%Y-%m-%d', $pfx_log);
		$pfx_k          = 2;
		while ($pfx_k <= $pfx_roundup) {
			$pfx_dynpgurl = createURL($pfx_pagename, 'page', $pfx_k);
			$pfx_cats[]   = array(
				'loc' => $pfx_dynpgurl,
				'changefreq' => 'monthly',
				'lastmod' => $pfx_recent
			);
			$pfx_k++;
		}
		// tag list /dynamic/tags/
		$pfx_dynpgurl   = createURL($pfx_pagename, 'tags');
		$pfx_cats[]     = array(
			'loc' => $pfx_dynpgurl,
			'changefreq' => 'monthly',
			'lastmod' => $pfx_recent
		);
		// popular /dynamic/popular/
		$pfx_dynpgurl   = createURL($pfx_pagename, 'popular');
		$pfx_cats[]     = array(
			'loc' => $pfx_dynpgurl,
			'changefreq' => 'monthly',
			'lastmod' => $pfx_recent
		);
		// archives /dynamic/arhcives/
		$pfx_dynpgurl   = createURL($pfx_pagename, 'archives');
		$pfx_cats[]     = array(
			'loc' => $pfx_dynpgurl,
			'changefreq' => 'monthly',
			'lastmod' => $pfx_recent
		);
		// every tag /dynamic/tag/$pfx_tagname
		$pfx_tags_array = all_tags('pfx_dynamic_posts', "public = 'yes' and page_id = '{$pfx_pageid}'");
		if (count($pfx_tags_array) != 0) {
			sort($pfx_tags_array);
			for ($pfx_final = 1; $pfx_final < (count($pfx_tags_array)); $pfx_final++) {
				$pfx_current = $pfx_tags_array[$pfx_final];
				$pfx_link    = str_replace(" ", '-', $pfx_current);
				$pfx_url1    = createURL($pfx_pagename, 'tag', $pfx_link);
				//echo $pfx_final." ".$pfx_current."\n";
				$pfx_cats[]  = array(
					'loc' => $pfx_url1,
					'changefreq' => 'monthly',
					'lastmod' => $pfx_recent
				);
			}
		}
		// also need a dynamic prority calculator 0 -> 1  
	} else if ($pfx_type == 'module') {
		$pfx_change = 'monthly';
	} else {
		$pfx_change = 'monthly';
	}
	$pfx_logunix = returnUnixtimestamp($pfx_lastmodified);
	$pfx_lm      = safe_strftime($pfx_lang, '%Y-%m-%d', $pfx_logunix);
	$pfx_cats[]  = array(
		'loc' => $pfx_url,
		'changefreq' => $pfx_change,
		'lastmod' => $pfx_lm
	);
	$pfx_i++;
}
$pfx_site_map_container = new google_sitemap();
for ($pfx_i = 0; $pfx_i < count($pfx_cats); $pfx_i++) {
	$pfx_value         = $pfx_cats[$pfx_i];
	$pfx_site_map_item = new google_sitemap_item($pfx_value['loc'], $pfx_value['lastmod'], $pfx_value['changefreq'], '0.7');
	$pfx_site_map_container->add_item($pfx_site_map_item);
}
echo $pfx_site_map_container->build();