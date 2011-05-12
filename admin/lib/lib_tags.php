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
 * Title: lib_tags
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
// ------------------------------------------------------------------
// get all current tags from a table
function all_tags($pfx_table, $pfx_condition) {
	$pfx_rs         = safe_rows('*', $pfx_table, $pfx_condition);
	$pfx_num        = count($pfx_rs);
	$pfx_tags_array = array();
	$pfx_first      = NULL;
	$pfx_last       = NULL;
	if (($pfx_rs)) {
		$pfx_i = 0;
		while ($pfx_i < $pfx_num) {
			$pfx_out = $pfx_rs[$pfx_i];
			if (isset($pfx_out['tags'])) {
			} else {
				$pfx_out['tags'] = NULL;
			}
			$pfx_all_tags = $pfx_out['tags'];
			$pfx_all_tags = strip_tags($pfx_all_tags);
			$pfx_all_tags = str_replace('&quot;', "", $pfx_all_tags);
			if (($pfx_all_tags != 0)) {
				$pfx_last = $pfx_all_tags{strlen($pfx_all_tags) - 1};
			}
			if (($pfx_all_tags != 0)) {
				$pfx_first = $pfx_all_tags{strlen($pfx_all_tags) - strlen($pfx_all_tags)};
			}
			if ($pfx_last != " ") {
				$pfx_all_tags = "{$pfx_all_tags} ";
			}
			if ($pfx_first != " ") {
				$pfx_all_tags = " {$pfx_all_tags}";
			}
			$pfx_tags_array_temp = explode(" ", $pfx_all_tags);
			for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
				$pfx_current = $pfx_tags_array_temp[$pfx_count];
				if ($pfx_current != 0) {
					$pfx_first = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
				}
				if ($pfx_current != 0) {
					$pfx_last = $pfx_current{strlen($pfx_current) - 1};
				}
				if ($pfx_first == " ") {
					$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
				}
				if (!in_array($pfx_current, $pfx_tags_array)) {
					$pfx_tags_array[] = $pfx_current;
				}
			}
			$pfx_i++;
		}
		return $pfx_tags_array;
	}
}
// ------------------------------------------------------------------
// creates a public tag cloud
function public_tag_cloud($pfx_table, $pfx_condition, $pfx_lang, $pfx_s = '', $pfx_m = '', $pfx_x = '') {
	$pfx_tags_array = all_tags($pfx_table, $pfx_condition);
	if (count($pfx_tags_array) != 0) {
		$pfx_max = 0;
		$pfx_min = 1;
		for ($pfx_findmax = 1; $pfx_findmax < (count($pfx_tags_array)); $pfx_findmax++) {
			$pfx_current = $pfx_tags_array[$pfx_findmax];
			$pfx_rz      = safe_rows('*', $pfx_table, "{$pfx_condition} AND tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
			$pfx_total   = count($pfx_rz);
			if ($pfx_total > $pfx_max) {
				$pfx_max = $pfx_total;
			}
			if ($pfx_total < $pfx_max) {
				$pfx_min = $pfx_total;
			}
		}
		$pfx_cloud = FALSE;
		sort($pfx_tags_array);
		for ($pfx_final = 1; $pfx_final < (count($pfx_tags_array)); $pfx_final++) {
			$pfx_current = $pfx_tags_array[$pfx_final];
			$pfx_rz      = safe_rows('*', $pfx_table, "{$pfx_condition} AND tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
			$pfx_total   = count($pfx_rz);
			if ($pfx_total == 0) {
				$pfx_total = 1;
			}
			if ($pfx_total >= $pfx_max) {
				$pfx_tag_class = 'tag_max';
			} else if ($pfx_total == $pfx_min) {
				$pfx_tag_class = 'tag_min';
			} else {
				$pfx_inc       = floor(($pfx_total * 10) / $pfx_max);
				$pfx_tag_class = 'tag_' . $pfx_inc;
			}
			$pfx_link = str_replace(" ", '-', $pfx_current);
			if ((isset($pfx_s)) && (isset($pfx_current))) {
				$pfx_cloud .= "\t\t\t\t\t\t\t<a href=\"" . createURL($pfx_s, 'tag', $pfx_link) . "\" title=\"{$pfx_lang['view']} {$pfx_lang['all_posts_tagged']}: {$pfx_current}\" class=\"{$pfx_tag_class}\" rel=\"tag\">{$pfx_current}</a>,\n";
			}
		}
		$pfx_cloud = substr($pfx_cloud, 0, (strlen($pfx_cloud) - 2)) . "";
		echo "$pfx_cloud\n";
	} else {
		return FALSE;
	}
}
// ------------------------------------------------------------------
// creates a tag cloud in block
function admin_block_tag_cloud($pfx_table, $pfx_condition, $pfx_type = '', $pfx_lang = '', $pfx_s = '', $pfx_m = '', $pfx_x = '') {
	$pfx_tags_array = all_tags($pfx_table, $pfx_condition);
	if (count($pfx_tags_array) != 0) {
		echo "\n\t\t\t\t\t<div id=\"admin_block_tags\" class=\"admin_block\">
			\t\t\t<h3 class=\"{$pfx_type} tag-title\">{$pfx_lang['tags']}:</h3>\n";
		$pfx_max = 0;
		$pfx_min = 1;
		for ($pfx_findmax = 1; $pfx_findmax < (count($pfx_tags_array)); $pfx_findmax++) {
			$pfx_current = $pfx_tags_array[$pfx_findmax];
			$pfx_rz      = safe_rows('*', $pfx_table, "{$pfx_condition} AND tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
			$pfx_total   = count($pfx_rz);
			if ($pfx_total > $pfx_max) {
				$pfx_max = $pfx_total;
			}
			if ($pfx_total < $pfx_max) {
				$pfx_min = $pfx_total;
			}
		}
		sort($pfx_tags_array);
		if ((isset($pfx_cloud))) {
		} else {
			$pfx_cloud = FALSE;
		}
		for ($pfx_final = 1; $pfx_final < (count($pfx_tags_array)); $pfx_final++) {
			$pfx_current = $pfx_tags_array[$pfx_final];
			$pfx_rz      = safe_rows('*', $pfx_table, "{$pfx_condition} AND tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
			$pfx_total   = count($pfx_rz);
			if ($pfx_total == 0) {
				$pfx_total = 1;
			}
			if ($pfx_total >= $pfx_max) {
				$pfx_tag_class = 'tag_max';
			} else if ($pfx_total == $pfx_min) {
				$pfx_tag_class = 'tag_min';
			} else {
				$pfx_inc       = floor(($pfx_total * 10) / $pfx_max);
				$pfx_tag_class = "tag_{$pfx_inc}";
			}
			if ((isset($pfx_s)) && (isset($pfx_current))) {
				$pfx_cloud .= "\t\t\t\t\t\t<a href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;tag=" . make_slug($pfx_current) . "\" title=\"{$pfx_lang['view']} {$pfx_lang['all_posts_tagged']}: {$pfx_current}\" class=\"{$pfx_tag_class}\" rel=\"tag\">{$pfx_current}({$pfx_total})</a>\n";
			}
		}
		$pfx_cloud = substr($pfx_cloud, 0, (strlen($pfx_cloud) - 1)) . "";
		echo "{$pfx_cloud}</div>\n";
	}
}
// ------------------------------------------------------------------
// creates a form tag adder
function form_tag($pfx_table, $pfx_condition, $pfx_lang, $pfx_s, $pfx_m, $pfx_x) {
	$pfx_tags_array = all_tags($pfx_table, $pfx_condition);
	if (count($pfx_tags_array) != 0) {
		$pfx_max = 0;
		for ($pfx_findmax = 1; $pfx_findmax < (count($pfx_tags_array)); $pfx_findmax++) {
			$pfx_current = $pfx_tags_array[$pfx_findmax];
			$pfx_rz      = safe_rows('*', $pfx_table, "{$pfx_condition} AND tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
			$pfx_total   = count($pfx_rz);
			if ($pfx_total > $pfx_max) {
				$pfx_max = $pfx_total;
			}
			$pfx_max = $pfx_max - 1;
			$pfx_min = 1;
		}
		sort($pfx_tags_array);
		for ($pfx_final = 1; $pfx_final < (count($pfx_tags_array)); $pfx_final++) {
			$pfx_current = $pfx_tags_array[$pfx_final];
			$pfx_rz      = safe_rows('*', $pfx_table, "{$pfx_condition} AND tags REGEXP '[[:<:]]{$pfx_current}[[:>:]]'");
			$pfx_total   = count($pfx_rz);
			if ( (isset($pfx_cloud)) && ($pfx_cloud) ) {
			} else {
				$pfx_cloud = FALSE;
			}
			$pfx_cloud .= "\t\t\t\t\t\t\t\t\t<a href=\"#\" rel=\"tag\" onclick=\"return false;\" title=\"Add tag {$pfx_current}\">{$pfx_current}</a>\n";
		}
		if ( (isset($pfx_cloud)) && ($pfx_cloud) ) {
			$pfx_cloud = substr($pfx_cloud, 0, (strlen($pfx_cloud) - 1)) . "";
		}
		if ( (isset($pfx_rz)) && ($pfx_rz) ) {
			echo "\t\t\t\t\t\t\t\t<div class=\"form_tags_suggestions\" id=\"form_tags_list\">";
			echo "<span class=\"form_tags_suggestions_text\">{$pfx_lang['form_help_current_tags']}</span>\n {$pfx_cloud}\n";
			echo "\t\t\t\t\t\t\t\t</div>\n";
		}
	}
}