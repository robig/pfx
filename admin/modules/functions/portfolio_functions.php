<?php
if ( !defined('DIRECT_ACCESS') ) { exit( header( 'Location: ../../../' ) ); }
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
 * Title: Portfolio module functions
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
/*	Functions	*/
function portfolio_show_all($pfx_rs, $pfx_m_n, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	if ($pfx_rs) {
		$pfx_i = 0;
		while ($pfx_a = nextRow($pfx_rs)) {
			extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
			$pfx_a = NULL;
			$pfx_image = safe_field('file_name', 'pfx_files', "file_id = '{$pfx_image_1}'");
			if ($pfx_url) {
				$pfx_projectlink = "<li class=\"{$pfx_m_n}_link\"><strong>View:</strong> <a href=\"{$pfx_url}\" title=\"View work: {$pfx_title} &raquo;\">{$pfx_title} &raquo;</a></li>";
			} else {
				$pfx_projectlink = "";
			}
			if ($pfx_client_url) {
				$pfx_client = "<a href=\"{$pfx_client_url}\" title=\"{$pfx_client_name}\">{$pfx_client_name}</a>";
			} else {
				$pfx_client = $pfx_client_name;
			}
			if ($pfx_tags) {
				$pfx_all_tags        = strip_tags($pfx_tags);
				$pfx_all_tags        = str_replace('&quot;', '', $pfx_tags);
				$pfx_tags_array_temp = explode(' ', $pfx_all_tags);
				for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
					$pfx_current = $pfx_tags_array_temp[$pfx_count];
					$pfx_first   = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
					if ($pfx_first == " ") {
						$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
					}
					$pfx_ncurrent = make_slug($pfx_current);
					$pfx_tag_list .= "<a href=\"" . PREFS_SITE_URL . "{$pfx_s}/tag/{$pfx_ncurrent}/\" title=\"View {$pfx_m_n} items tagged {$pfx_current}\" rel=\"tag\">{$pfx_current}</a>, ";
				}
				$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
			}
			echo "<div class=\"{$pfx_m_n}_item clear\" id=\"{$pfx_m_n}_{$pfx_portfolio_id}\">
						<h4>{$pfx_title}
						</h4>
						<a href=\"" . createURL($pfx_s, 'permalink', $pfx_portfolio_id) . "\" title=\"{$pfx_title}\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /></a>
						<div class=\"col3\">
							<ul class=\"{$pfx_m_n}_credits\">
								<li class=\"{$pfx_m_n}_client\"><strong>Client:</strong> <span>{$pfx_client}</span></li>
								<li class=\"{$pfx_m_n}_tags\"><strong>Tags:</strong> <span class=\"tags\">{$pfx_tag_list}</span></li>
								{$pfx_projectlink}
							</ul>
							<div class=\"{$pfx_m_n}_info\">
								{$pfx_description}
							</div>
						</div>
					</div>";
			$pfx_tag_list = "";
			$pfx_i++;
		}
		if ( (isset($pfx_client)) && ($pfx_client) ) {
			echo "\t\t<span id=\"browse_archives\" class=\"clear\">Want to see more? <a href=\"" . createURL($pfx_s, 'archives') . "\" title=\"Browse the archives\">Browse the archives &raquo;</a></span>";
			/*	if this is set we have had a sucessful loop	*/
		} else {
			/*	Else something is wrong	*/
			echo '<h2>' . ucfirst($pfx_m_n) . '</h2><p class="error">No ' . ucfirst($pfx_m_n) . ' items were found</p>';
		}
	} else {
		echo '<h2>' . ucfirst($pfx_m_n) . '</h2><p class="error">No ' . ucfirst($pfx_m_n) . ' items were found</p>';
	}
}

function portfolio_show_single($pfx_rs, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	if ($pfx_rs) {
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = NULL;
		$pfx_image = safe_field('file_name', 'pfx_files', "file_id = '{$pfx_image_1}'");
		if ($pfx_url) {
			$pfx_projectlink = "<li class=\"{$pfx_m_n}_link\"><strong>View:</strong> <a href=\"{$pfx_url}\" title=\"View work: {$pfx_title} &raquo;\">{$pfx_title} &raquo;</a></li>";
		} else {
			$pfx_projectlink = "";
		}
		if ($pfx_client_url) {
			$pfx_client = "<a href=\"{$pfx_client_url}\" title=\"{$pfx_client_name}\">{$pfx_client_name}</a>";
		} else {
			$pfx_client = "{$pfx_client_name}";
		}
		if ($pfx_tags) {
			$pfx_all_tags        = strip_tags($pfx_tags);
			$pfx_all_tags        = str_replace('&quot;', "", $pfx_tags);
			$pfx_tags_array_temp = explode(" ", $pfx_all_tags);
			for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
				$pfx_current = $pfx_tags_array_temp[$pfx_count];
				$pfx_first   = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
				if ($pfx_first == " ") {
					$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
				}
				$pfx_ncurrent = make_slug($pfx_current);
				$pfx_tag_list .= "<a href=\"" . PREFS_SITE_URL . "{$pfx_s}/tag/{$pfx_ncurrent}/\" title=\"View {$pfx_m_n} items tagged {$pfx_current}\" rel=\"tag\">{$pfx_current}</a>, ";
			}
			$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
		}
		echo "	<div class=\"{$pfx_m_n}_item clear {$pfx_m_n}_single\" id=\"{$pfx_m_n}_{$pfx_portfolio_id}\">
				<h4>{$pfx_title}
				</h4>
				<img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" />
				<div class=\"col3\">
					<ul class=\"{$pfx_m_n}_credits\">
						<li class=\"{$pfx_m_n}_client\"><strong>Client:</strong> <span>{$pfx_client}</span></li>
							<li class=\"{$pfx_m_n}_tags\"><strong>Tags:</strong> <span class=\"tags\">{$pfx_tag_list}</span>
							</li>
							{$pfx_projectlink}
					</ul>
					<div class=\"{$pfx_m_n}_info\">
						{$pfx_description}
					</div>
				</div>
			</div>";
		echo "<br /><br /><span id=\"browse_archives\" class=\"clear\">Want to see more? 
		      <a href=\"" . createURL($pfx_s, 'archives') . "\" title=\"Browse the archives\">Browse the archives &raquo;
		      </a>or <a href=\"" . createURL($pfx_s) . "\" title=\"View most recent\">view most recent work &raquo;</a></span>";
			$pfx_tag_list = "";
	} else {
		echo '<h2>' . ucfirst($pfx_m_n) . '</h2><p class="error">No ' . ucfirst($pfx_m_n) . ' items were found</p>';
	}
}

function portfolio_archives($pfx_rs, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	if ($pfx_rs) {
		while ($pfx_a = nextRow($pfx_rs)) {
			extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
			$pfx_a = NULL;
			$pfx_image = safe_field('file_name', 'pfx_files', "file_id = '{$pfx_image_thumb}'");
			echo "<div class=\"{$pfx_m_n}_archive\">
						<a href=\"" . createURL($pfx_s, 'permalink', $pfx_portfolio_id) . "\" title=\"{$pfx_title}\"><img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" /></a>
					</div>";
		}
	} else {
		echo '<h2>' . ucfirst($pfx_m_n) . '</h2><p class="error">No ' . ucfirst($pfx_m_n) . ' items were found</p>';
	}
}