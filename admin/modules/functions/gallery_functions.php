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
 * Title: gallery_functions - Displays contents of a db table
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

function gallery_pre($pfx_m_n) {
	/*	Perform an SQL query to update gallery's file records	*/
	$pfx_g_result_image_file_number = safe_query("SELECT image FROM pfx_module_{$pfx_m_n}");
	while ($pfx_g_array_image_file_number = mysql_fetch_array($pfx_g_result_image_file_number, MYSQL_ASSOC)) {
		foreach ($pfx_g_array_image_file_number as $pfx_g_single_image_file_number) {
			$pfx_g_result_image_file_name = safe_query("SELECT file_name FROM pfx_files WHERE file_id='{$pfx_g_single_image_file_number}'");
			/*	Update the results to the database	*/
			while ($pfx_g_array_image_file_name = mysql_fetch_array($pfx_g_result_image_file_name, MYSQL_ASSOC)) {
				foreach ($pfx_g_array_image_file_name as $pfx_list_item) {
					safe_update("pfx_module_{$pfx_m_n}", "file='{$pfx_list_item}'", "image='{$pfx_g_single_image_file_number}'");
				}
			}
		}
	}
}


function gallery_update($pfx_m_n) {
	/*	Update the results to the database	*/
	$pfx_g_result_name = safe_query("SELECT file FROM pfx_module_{$pfx_m_n}");
	while ($pfx_g_array_name = mysql_fetch_array($pfx_g_result_name, MYSQL_ASSOC)) {
		foreach ($pfx_g_array_name as $pfx_g_filename) {
			$pfx_checkfilename = PREFS_SITE_URL . "files/images/{$pfx_g_filename}";
			$pfx_g_result_id_number = fetch("{$pfx_m_n}_id", "pfx_module_{$pfx_m_n}", 'file', "{$pfx_g_filename}");
			$pfx_g_result_title = fetch('title', "pfx_module_{$pfx_m_n}", 'file', "{$pfx_g_filename}");
			$pfx_g_result_desc = fetch('description', "pfx_module_{$pfx_m_n}", 'file', "{$pfx_g_filename}");
			if (url_exist($pfx_checkfilename)) {
			} else {
				/*	Remove the invalid entry for the file name because it's not there	*/
				safe_delete("pfx_module_{$pfx_m_n}", "file='{$pfx_g_filename}'");
			}
		}
	}
}

/* A gallery class for displaying contents of a db table */
class galleryTable {
	var $pfx_Res;
	var $pfx_exclude = array();
	var $pfx_table_name;
	var $pfx_view_number;
	var $pfx_lo;
	var $pfx_finalmax;
	var $pfx_whereami;
	var $pfx_a_array = array();
	var $pfx_edit;
	function galleryTable($pfx_Res, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_whereami, $pfx_s) {

		$this->Res       = $pfx_Res;
		$this->exclude   = $pfx_exclude;
		$this->table     = $pfx_table_name;
		$this->limit     = $pfx_view_number;
		$this->num       = $pfx_lo;
		$this->finalmax  = $pfx_finalmax;
		$this->whereami  = $pfx_whereami;
		$this->s         = $pfx_s;
	}
	function galleryBody($pfx_m_n, $pfx_wheream_s) {

		echo "\t<div class=\"table " . $this->table . " lightbox\"><div class=\"thead\"></div>";
		if ($this->finalmax)
			$this->limit = $this->finalmax;
		echo '<div class="tbody">';
		$pfx_counter = NULL;
		while ($pfx_counter < $this->limit) {
			$pfx_F = mysql_fetch_array($this->Res);
			if (is_even($pfx_counter))
				$pfx_trclass = 'tr odd';
			else
				$pfx_trclass = 'tr even';
			echo "<div class=\"{$pfx_trclass}\">\n";
			for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
				if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude)) {
					if (mysql_field_name($this->Res, $pfx_j) == 'file') {
						$pfx_img = PREFS_SITE_URL . "files/images/{$pfx_F[$pfx_j]}";
						echo "<div class=\"td\"><a href=\"{$pfx_img}\" rel=\"lightbox[group1]\"><img class=\"image-thumb\" src=\"{$pfx_img}\" alt=\"{$pfx_F[$pfx_j]}\" /></a></div>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'title') {
						$pfx_what_slug = getThing($pfx_query = "SELECT post_slug FROM " . CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_m_n} WHERE title='$pfx_F[$pfx_j]'");
						if (PREFS_CLEAN_URLS == 'yes') {
							$pfx_lnk = PREFS_SITE_URL . "{$pfx_m_n}/page/{$pfx_what_slug}/";
						} else {
							$pfx_lnk = "{$pfx_wheream_s}&amp;m=page&amp;x={$pfx_what_slug}";
						}
						echo "<div class=\"td ti\"><div class=\"title\"><a class=\"no-lightbox ajax\" href=\"{$pfx_lnk}\" title=\"{$pfx_F[$pfx_j]}\">$pfx_F[$pfx_j]</a></div></div>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'description') {
						if ( strlen($pfx_F[$pfx_j]) >= 200 ) {
							$pfx_what_slug = getThing($pfx_query = "SELECT post_slug FROM " . CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_m_n} WHERE description='$pfx_F[$pfx_j]'");
							if (PREFS_CLEAN_URLS == 'yes') {
								$pfx_lnk = PREFS_SITE_URL . "{$pfx_m_n}/page/{$pfx_what_slug}/";
							} else {
								$pfx_lnk = "{$pfx_wheream_s}&amp;m=page&amp;x={$pfx_what_slug}";
							}
							$pfx_read_more = "<a class=\"no-lightbox\" href=\"{$pfx_lnk}\">Read more</a>";
							$pfx_descr = rtrim( substr($pfx_F[$pfx_j], 0, 200) ) . "... {$pfx_read_more}";
						} else {
							$pfx_descr = $pfx_F[$pfx_j];
						}
						echo "<div class=\"td desc\"><div class=\"description\">{$pfx_descr}</div></div>";
					} else {
						echo "<div class=\"td\">$pfx_F[$pfx_j]</div>";
					}
				}
			}
			echo '</div>';
			$pfx_counter++;
		}
		echo "</div></div>\n";
	}
}


class Paginator_gallery extends Paginator {

	var $pfx_whereami;
	/*	Outputs a link set like : Previous 1 2 3 4 5 6 Next	*/
	function gallery_prev_next($pfx_m_n, $pfx_whereami) {
		$this->pfx_whereami = $pfx_whereami;
		if ($this->getPrevious()) {
			if (PREFS_CLEAN_URLS == 'yes') {
				$pfx_lnk = "{$pfx_m_n}/" . $this->getPrevious();
			} else {
				$pfx_lnk = $this->pfx_whereami . '&amp;p=' . $this->getPrevious();
			}
			echo "<a class=\"ajax previo no-lightbox\" href=\"{$pfx_lnk}\" title=\"Previous\">Previous</a>";
		}
		$pfx_links = $this->getLinkArr();
		foreach ($pfx_links as $pfx_link) {
			if ($pfx_link == $this->getCurrent()) {
				if (PREFS_CLEAN_URLS == 'yes') {
					$pfx_lnk = "{$pfx_m_n}/{$pfx_link}";
				} else {
					$pfx_lnk = $this->pfx_whereami . "&amp;p={$pfx_link}";
				}
				echo "<a class=\"ajax number no-lightbox\" href=\"{$pfx_lnk}\" title=\"Page: {$pfx_link}\" id=\"page-current\">{$pfx_link}</a>";
			} else {
				if (PREFS_CLEAN_URLS == 'yes') {
					$pfx_lnk = "{$pfx_m_n}/{$pfx_link}";
				} else {
					$pfx_lnk = $this->pfx_whereami . "&amp;p={$pfx_link}";
				}
				echo "<a class=\"ajax number no-lightbox\" href=\"{$pfx_lnk}\" title=\"Page: {$pfx_link}\">{$pfx_link}</a>";
			}
		}
		if ($this->getNext()) {
			if (PREFS_CLEAN_URLS == 'yes') {
				$pfx_lnk = "{$pfx_m_n}/" . $this->getNext();
			} else {
				$pfx_lnk = $this->pfx_whereami . '&amp;p=' . $this->getNext();
			}
			echo "<a class=\"ajax nextio no-lightbox\" href=\"{$pfx_lnk}\" title=\"Next\">Next</a>";
		}
	}
} /*	End class	*/


function gallery_create($pfx_m_n, $pfx_lang, $pfx_condition, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_exclude, $pfx_view_number, $pfx_lo, $pfx_module_jscript, $pfx_is_tag, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE, $pfx_p = FALSE) {

	echo "<div id=\"{$pfx_m_n}-table\">";
		/* Paginator code start */
		$pfx_r1 = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc}");
		if ($pfx_r1) {
			$pfx_total = mysql_num_rows($pfx_r1);
			if ($pfx_total > 0) {
				if ( (isset($pfx_table_name)) ) {
					$pfx_r = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc} limit {$pfx_lo},{$pfx_view_number}");
				}
				if ($pfx_r) {
					$pfx_rows = mysql_num_rows($pfx_r);
					$pfx_hi   = $pfx_lo + $pfx_view_number;
					if ($pfx_hi > $pfx_total) {
						$pfx_finalmax = $pfx_total - $pfx_lo;
						$pfx_hi       = $pfx_total;
					}
					$pfx_pages = ceil($pfx_total / $pfx_view_number);
				}
				$pfx_a = new Paginator_gallery($pfx_p, $pfx_total);
				$pfx_a->set_Limit($pfx_view_number);
				$pfx_a->set_Links(4);
				echo "<div class=\"{$pfx_m_n}-holder\">";
				$pfx_wheream_s = "?s={$pfx_s}";
				if (isset($pfx_m) && ($pfx_m)) {
					$pfx_wheream_m = "&amp;m={$pfx_m}";
				} else {
					$pfx_wheream_m = '';
				}
				if (isset($pfx_x) && ($pfx_x)) {
					$pfx_wheream_x = "&amp;x={$pfx_x}";
				} else {
					$pfx_wheream_x = '';
				}
				$pfx_wheream = "{$pfx_wheream_s}{$pfx_wheream_m}{$pfx_wheream_x}";
				if (isset($pfx_finalmax) && ($pfx_finalmax)) {
				} else {
				$pfx_finalmax = NULL;
				}
				$pfx_Table = new galleryTable($pfx_r, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_wheream, $pfx_s);
				$pfx_Table->galleryBody($pfx_m_n, $pfx_wheream_s);
				$pfx_loprint = $pfx_lo + 1;
				if ($pfx_pages > 1) {
					echo "\n\t\t\t\t\t\t<div id=\"{$pfx_m_n}-table-overview\">\n\t\t\t\t\t\t\t<p>{$pfx_lang['total_records']}: {$pfx_total} ({$pfx_lang['showing_from_record']} {$pfx_loprint} {$pfx_lang['to']} {$pfx_hi}) {$pfx_pages} {$pfx_lang['page(s)']}.</p>\n\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t<div id=\"{$pfx_m_n}-table-pages\">\n\t\t\t\t\t\t\t<p>";
					$pfx_a->gallery_prev_next($pfx_m_n, $pfx_wheream);
					echo '</p></div>';
				}
				echo '</div>';
			} else {
				$is_empty = "<div class=\"{$pfx_m_n}-holder\"><h3>GALLERY EMPTY<h3></div>";
			}
		}
}