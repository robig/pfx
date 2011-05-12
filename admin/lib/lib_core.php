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
 * Title: lib_core
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
// Adjust the table prefix
if (!function_exists('adjust_prefix')) {
	function adjust_prefix($pfx_table) {
		if (stripos($pfx_table, CONFIG_TABLE_PREFIX) === 0)
			return $pfx_table;
		else
			return CONFIG_TABLE_PREFIX . $pfx_table;
	}
}
// ------------------------------------------------------------------
// A super class for displaying contents of a db table
class SuperTable {
	var $pfx_Res;
	var $pfx_exclude = array();
	var $pfx_table_name;
	var $pfx_view_number;
	var $pfx_lo;
	var $pfx_finalmax;
	var $pfx_whereami;
	var $pfx_a_array = array();
	var $pfx_edit;
	function SuperTable($pfx_Res, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_whereami, $pfx_type, $pfx_s) {
		$this->Res       = $pfx_Res;
		$this->exclude   = $pfx_exclude;
		$this->table     = $pfx_table_name;
		$this->limit     = $pfx_view_number;
		$this->num       = $pfx_lo;
		$this->finalmax  = $pfx_finalmax;
		$this->whereami  = $pfx_whereami;
		$this->page_type = $pfx_type;
		$this->s         = $pfx_s;
	}
	function SuperBody($pfx_lang, $pfx_page_display_name = FALSE, $pfx_no_edit = FALSE, $pfx_no_delete = FALSE, $pfx_last_mod = FALSE, $pfx_ck = FALSE, $pfx_CKEditorFuncNum = FALSE, $pfx_ckfile = FALSE, $pfx_ckimage = FALSE) {
		echo "\t<table class=\"tbl " . $this->table . " lightbox\" summary=\"{$pfx_lang['results_from']} $this->table.\">
							<thead>
								<tr>";
		for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
			if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude))
				if ((isset($pfx_arlen)) && (isset($pfx_sum))) {
					$pfx_arlen[$pfx_j] = mysql_field_len($this->Res, $pfx_j);
					$pfx_sum += $pfx_arlen[$pfx_j];
				}
		}
		for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
			if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude)) {
				$pfx_fieldname = simplify(mysql_field_name($this->Res, $pfx_j));
				if ((isset($pfx_lang['form_' . mysql_field_name($this->Res, $pfx_j)])) && ($pfx_lang['form_' . mysql_field_name($this->Res, $pfx_j)])) {
					$pfx_fieldname = $pfx_lang['form_' . mysql_field_name($this->Res, $pfx_j)];
				}
				if (mysql_field_name($this->Res, $pfx_j) == 'file_extension') {
				echo "<th class=\"tbl_heading ico\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">&nbsp;</th>";
				} else if (mysql_field_name($this->Res, $pfx_j) == 'tags') {
				echo "<th class=\"tbl_heading usi\" id=\"thetags\">{$pfx_lang['tags']}</th>";
				} else if (mysql_field_name($this->Res, $pfx_j) == 'log_icon') {
				echo "<th class=\"tbl_heading ico\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				} else if (mysql_field_name($this->Res, $pfx_j) == 'log_time') {
				echo "<th class=\"tbl_heading dte\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				} else if ( (mysql_field_name($this->Res, $pfx_j) == 'user_id') or (mysql_field_name($this->Res, $pfx_j) == 'user_ip') ) {
				echo "<th class=\"tbl_heading usi\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				} else {
				echo "<th class=\"tbl_heading\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				}
			}
		}
		if ($pfx_last_mod == 'yes') {
			echo "<th class=\"tbl_heading dte\" id=\"file_date_mod\">{$pfx_lang['filedate']}</th>";
		}
		if ($pfx_no_edit == 'yes') {
		} else {
			echo '<th class="tbl_heading edt" id="page_edit"></th>';
		}
		if ($pfx_no_delete == 'yes') {
		} else {
			if ( (isset($pfx_ck)) && ($pfx_ck) ) {
				echo '<th class="tbl_heading sel" id="file_select"></th>';
			}
			echo '<th class="tbl_heading del" id="page_delete"></th>';
		}
		echo '</tr></thead>';
		if ($this->finalmax)
			$this->limit = $this->finalmax;
		echo '<tbody>';
		$pfx_counter = NULL;
		while ($pfx_counter < $this->limit) {
			$pfx_F = mysql_fetch_array($this->Res);
			if (is_even($pfx_counter))
				$pfx_trclass = 'odd';
			else
				$pfx_trclass = 'even';
			echo "<tr class=\"$pfx_trclass\">\n";
			for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
				if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude)) {
					if (mysql_field_type($this->Res, $pfx_j) == 'timestamp') {
						$pfx_logunix = rUnixtimestamp($pfx_F[$pfx_j]);
						$pfx_date    = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
						echo "<td class=\"tbl_row dte\">{$pfx_date}</td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'url') {
						echo "<td class=\"tbl_row\"><a href=\"{$pfx_F[$pfx_j]}\" target=\"_blank\">{$pfx_F[$pfx_j]}</a></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'email') {
						echo "<td class=\"tbl_row\"><a href=\"mailto:{$pfx_F[$pfx_j]}\">{$pfx_F[$pfx_j]}</a></td>";
					} else if ($pfx_F[$pfx_j] == "") {
						echo "<td class=\"tbl_row\">&nbsp;</td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'log_icon') {
						echo "<td class=\"tbl_row ico\"><img src=\"admin/theme/images/icons/{$pfx_F[$pfx_j]}.png\" alt=\"{$pfx_F[$pfx_j]} icon\" /></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'user_id') {
						$pfx_user_ip = getThing($pfx_query = "SELECT user_ip FROM " . CONFIG_TABLE_PREFIX . "pfx_log WHERE user_id='$pfx_F[$pfx_j]'");
						echo "<td class=\"tbl_row\"><a rel=\"nofollow\" href=\"http://network-tools.com/default.asp?prog=lookup&amp;host={$pfx_user_ip}\" title=\"Lookup IP: {$pfx_user_ip}\" target=\"_blank\">{$pfx_F[$pfx_j]}</a></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'user_ip') {
						echo "<td class=\"tbl_row usi\"><a rel=\"nofollow\" href=\"http://network-tools.com/default.asp?prog=lookup&amp;host={$pfx_F[$pfx_j]}\" title=\"Lookup IP: {$pfx_F[$pfx_j]}\" target=\"_blank\">{$pfx_F[$pfx_j]}</a></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'file_extension') {
						if (file_exists("admin/theme/images/icons/file_{$pfx_F[$pfx_j]}.png")) {
							$pfx_img = "admin/theme/images/icons/file_{$pfx_F[$pfx_j]}.png";
						} else {
							$pfx_what_img = getThing($pfx_query = "SELECT file_type FROM " . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_extension='$pfx_F[$pfx_j]'");
							if ( $pfx_what_img == 'Audio' ) {
								$pfx_img = 'admin/theme/images/icons/audio.png';
							} else if ( $pfx_what_img == 'Image' ) {
								$pfx_img = 'admin/theme/images/icons/image.png';
							} else if ( $pfx_what_img == 'Video' ) {
								$pfx_img = 'admin/theme/images/icons/film.png';
							} else {
								$pfx_img = 'admin/theme/images/icons/folder.png';
							}
						}
						echo "<td class=\"tbl_row ico\"><img src=\"{$pfx_img}\" alt=\"{$pfx_F[$pfx_j]}\" /></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'file_name') {
						$pfx_this_file = $pfx_F[$pfx_j];
						$pfx_what_type = getThing($pfx_query = "SELECT file_type FROM " . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_name='$pfx_this_file'");
						if ( $pfx_what_type == 'Audio' ) {
							$pfx_link = "../files/audio/{$pfx_F[$pfx_j]}";
						} else if ( $pfx_what_type == 'Image' ) {
							$pfx_link = "../files/images/{$pfx_F[$pfx_j]}";
						} else if ( $pfx_what_type == 'Video' ) {
							$pfx_link = "../files/video/{$pfx_F[$pfx_j]}";
						} else {
							$pfx_link = "../files/other/{$pfx_F[$pfx_j]}";
						}
						$pfx_rel = pathinfo($pfx_link, PATHINFO_EXTENSION);
						if ( ($pfx_rel === 'png') or ($pfx_rel === 'jpg') or ($pfx_rel === 'gif') or ($pfx_rel === 'jpeg') or ($pfx_rel === 'bmp') ) {
							$pfx_rel = 'rel="lightbox[group1]" ';
						} else {
							$pfx_rel = '';
						}
						echo "<td class=\"tbl_row file_name\"><a href=\"{$pfx_link}\" {$pfx_rel}title=\"{$pfx_F[$pfx_j]}\" alt=\"{$pfx_F[$pfx_j]}\">{$pfx_F[$pfx_j]}</a></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'tags') {
						echo "<td class=\"tbl_row usi\">$pfx_F[$pfx_j]</td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'log_message') {
						echo "<td class=\"tbl_row usi\">$pfx_F[$pfx_j]</td>";
					} else {
						echo "<td class=\"tbl_row\">$pfx_F[$pfx_j]</td>";
					}
				}
			}
			if ($pfx_last_mod == 'yes') {
				$pfx_last_modified = filemtime($pfx_link);
				echo "<td class=\"tbl_row\">" . safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_last_modified) . "</td>";
			}
			if ($pfx_no_edit == 'yes') {
			} else {
				echo "<td class=\"tbl_row tbl_edit edt\"><a href=\"$this->whereami&amp;edit={$pfx_F[0]}\" title=\"{$pfx_lang['edit']}\">{$pfx_lang['edit']}</a></td>";
			}
			if ($pfx_no_delete == 'yes') {
			} else if ($pfx_last_mod == 'yes') {
				if ( (isset($pfx_ck)) && ($pfx_ck) ) {
					echo "<td class=\"tbl_row sel\"><a class=\"pxfinder no-lightbox\" href=\"{$pfx_link}\" alt=\"/images/{$pfx_this_file}\">{$pfx_lang['ck_select_file']}</a></td>";
				}
				echo "<td class=\"tbl_row tbl_delete del\"><a href=\"?s=publish&amp;x=filemanager";
				if ( (isset($pfx_ck)) && ($pfx_ck) && (isset($pfx_CKEditorFuncNum)) ) {
					echo "&amp;ckFuncNumReturn={$pfx_CKEditorFuncNum}";
				}
				if ( (isset($pfx_ck)) && ($pfx_ck) ) {
					echo '&amp;ck=1';
				}
				if ( (isset($pfx_ckfile)) && ($pfx_ckfile) ) {
					echo '&amp;ckfile=1';
				}
				if ( (isset($pfx_ckimage)) && ($pfx_ckimage) ) {
					echo '&amp;ckimage=1';
				}
				echo "&amp;del={$pfx_this_file}\" class=\"confirm-del fileman-del no-lightbox\">{$pfx_lang['delete']}</a></td>";
			} else {
				echo "<td class=\"tbl_row tbl_delete del\"><a href=\"$this->whereami&amp;delete={$pfx_F[0]}\" class=\"confirm-del no-lightbox\" title=\"{$pfx_lang['delete']}\">{$pfx_lang['delete']}</a></td>";
			}
			echo '</tr>';
			$pfx_counter++;
		}
		echo "</tbody></table>\n";
	}
}
// ------------------------------------------------------------------
// class for displaying contents of a db table
class ShowTable {
	var $pfx_Res;
	var $pfx_exclude = array();
	var $pfx_table_name;
	var $pfx_view_number;
	var $pfx_lo;
	var $pfx_finalmax;
	var $pfx_whereami;
	var $pfx_a_array = array();
	var $pfx_edit;
	function ShowTable($pfx_Res, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_whereami, $pfx_type, $pfx_s) {
		$this->Res       = $pfx_Res;
		$this->exclude   = $pfx_exclude;
		$this->table     = $pfx_table_name;
		$this->limit     = $pfx_view_number;
		$this->num       = $pfx_lo;
		$this->finalmax  = $pfx_finalmax;
		$this->whereami  = $pfx_whereami;
		$this->page_type = $pfx_type;
		$this->s         = $pfx_s;
	}
	function DrawBody($pfx_lang, $pfx_page_display_name = FALSE) {
		echo "\t<table class=\"tbl " . $this->table . "\" summary=\"{$pfx_lang['results_from']} $this->table.\">
							<thead>
								<tr>";
		for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
			if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude))
				if ((isset($pfx_arlen)) && (isset($pfx_sum))) {
					$pfx_arlen[$pfx_j] = mysql_field_len($this->Res, $pfx_j);
					$pfx_sum += $pfx_arlen[$pfx_j];
				}
		}
		for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
			if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude)) {
				$pfx_fieldname = simplify(mysql_field_name($this->Res, $pfx_j));
				if ((isset($pfx_lang['form_' . mysql_field_name($this->Res, $pfx_j)])) && ($pfx_lang['form_' . mysql_field_name($this->Res, $pfx_j)])) {
					$pfx_fieldname = $pfx_lang['form_' . mysql_field_name($this->Res, $pfx_j)];
				}
				if (mysql_field_name($this->Res, $pfx_j) == 'posted') {
					echo "<th class=\"tbl_heading dte\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				} else if (mysql_field_name($this->Res, $pfx_j) == 'published') {
					echo "<th class=\"tbl_heading dte\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				} else if (mysql_field_name($this->Res, $pfx_j) == 'hits') {
					echo "<th class=\"tbl_heading dte\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				} else {
					echo "<th class=\"tbl_heading\" id=\"" . mysql_field_name($this->Res, $pfx_j) . "\">$pfx_fieldname</th>";
				}
			}
		}
		echo '<th class="tbl_heading edt" id="page_edit"></th><th class="tbl_heading del" id="page_delete"></th>';
		echo '</tr></thead>';
		if ($this->finalmax)
			$this->limit = $this->finalmax;
		echo '<tbody>';
		$pfx_counter = NULL;
		while ($pfx_counter < $this->limit) {
			$pfx_F = mysql_fetch_array($this->Res);
			if (is_even($pfx_counter))
				$pfx_trclass = 'odd';
			else
				$pfx_trclass = 'even';
			echo "<tr class=\"$pfx_trclass\">\n";
			for ($pfx_j = 0; $pfx_j < mysql_num_fields($this->Res); $pfx_j++) {
				if (!in_array(mysql_field_name($this->Res, $pfx_j), $this->exclude)) {
					if (mysql_field_type($this->Res, $pfx_j) == 'timestamp') {
						$pfx_logunix = returnUnixtimestamp($pfx_F[$pfx_j]);
						$pfx_date    = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
						echo "<td class=\"tbl_row dte\">{$pfx_date}</td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'url') {
						echo "<td class=\"tbl_row\"><a href=\"{$pfx_F[$pfx_j]}\" target=\"_blank\">{$pfx_F[$pfx_j]}</a></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'email') {
						echo "<td class=\"tbl_row\"><a href=\"mailto:{$pfx_F[$pfx_j]}\">{$pfx_F[$pfx_j]}</a></td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'published') {
						echo "<td class=\"tbl_row dte\">" . strip_tags($pfx_F[$pfx_j]) . "</td>";
					} else if (mysql_field_name($this->Res, $pfx_j) == 'hits') {
						echo "<td class=\"tbl_row dte\">" . strip_tags($pfx_F[$pfx_j]) . "</td>";
					} else if ($pfx_F[$pfx_j] == "") {
						echo "<td class=\"tbl_row\">{$pfx_lang['form_not_set']}</td>";
					} else {
						echo "<td class=\"tbl_row\">" . strip_tags($pfx_F[$pfx_j]) . "</td>";
					}
				}
			}
				echo "<td class=\"tbl_row tbl_edit edt\"><a href=\"$this->whereami&amp;edit={$pfx_F[0]}\" title=\"{$pfx_lang['edit']}\">{$pfx_lang['edit']}</a></td>
				<td class=\"tbl_row tbl_delete del\"><a href=\"$this->whereami&amp;delete={$pfx_F[0]}\" class=\"confirm-del\" title=\"{$pfx_lang['delete']}\">{$pfx_lang['delete']}</a></td>
				</tr>";
			$pfx_counter++;
		}
		echo "</tbody></table>\n";
	}
}
// ------------------------------------------------------------------
// class for add/edit new records in db table
class ShowBlank {
	var $pfx_Nam;
	var $pfx_Typ;
	var $pfx_Res;
	var $pfx_Flg;
	var $pfx_Pkn;
	var $pfx_edit_exclude = array();
	var $pfx_table_name;
	function ShowBlank($pfx_Nam, $pfx_Typ, $pfx_Len, $pfx_Flg, $pfx_Res, $pfx_Pkn, $pfx_edit_exclude, $pfx_table_name) {
		$this->Nam       = $pfx_Nam;
		$this->Typ       = $pfx_Typ;
		$this->Len       = $pfx_Len;
		$this->Res       = $pfx_Res;
		$this->Flg       = $pfx_Flg;
		$this->Pkn       = $pfx_Pkn;
		$this->exclude   = $pfx_edit_exclude;
		$this->tablename = $pfx_table_name;
		$this->fields = safe_query("SHOW fields from `{$pfx_table_name}`");
	}
	function ShowBody($pfx_lang, $pfx_go = FALSE, $pfx_edit = FALSE, $pfx_page = FALSE, $pfx_page_display_name = FALSE, $pfx_type = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
		// check $pfx_edit against $pfx_x - they need to represent the same page, if not redirect.
		$pfx_checkid = safe_field('page_id', 'pfx_core', "page_name='$pfx_x'");
		if ((isset($pfx_edit)) && ($pfx_edit) && ($pfx_m == 'static')) {
			if ($pfx_edit != $pfx_checkid) {
				echo "<div class=\"helper\"><h3>{$pfx_lang['help']}</h3><p>{$pfx_lang['unknown_edit_url']}</p></div>";
				$pfx_cancel = TRUE;
			}
		}
		if (isset($pfx_cancel)) {
		} else {
			$pfx_cancel_not_set = 1;
		}
		if ($pfx_cancel_not_set == 1) {
			$pfx_Nams = explode('|', substr($this->Nam, 0, (strlen($this->Nam) - 1)));
			$pfx_Type = explode('|', substr($this->Typ, 0, (strlen($this->Typ) - 1)));
			$pfx_Leng = explode('|', substr($this->Len, 0, (strlen($this->Len) - 1)));
			$pfx_Flag = explode('|', substr($this->Flg, 0, (strlen($this->Flg) - 1)));
			$pfx_Fild = explode('|', substr($this->Res, 0, (strlen($this->Res) - 1)));
			if (!$pfx_page) {
				$pfx_page = 1;
			}
			if ( (isset($pfx_s)) && ($pfx_s == 'settings') ) {
				if (strpos($this->tablename, 'module')) {
					$pfx_formtitle = "{$pfx_lang['advanced']} {$pfx_lang['page_settings']}";
				} else if (strpos($this->tablename, 'dynamic')) {
					$pfx_formtitle = "{$pfx_lang['advanced']} {$pfx_lang['page_settings']}";
				} else {
					$pfx_formtitle = $pfx_lang['page_settings'];
				}
			} else {
				if ( (isset($pfx_edit)) && ($pfx_edit) ) {
					if ($pfx_m == 'static') {
						$pfx_formtitle = "{$pfx_lang['edit']} {$pfx_page_display_name} {$pfx_lang['settings_page']}";
					} else {
						$pfx_formtitle = $pfx_lang['edit'] . " $pfx_page_display_name " . str_replace('.', "", $pfx_lang['entry']) . " (#$pfx_edit)";
					}
				} else {
					$pfx_formtitle = $pfx_lang['new_entry'] . str_replace('.', "", $pfx_lang['entry']);
				}
			}
			if ( (isset($pfx_s)) && ($pfx_s == 'settings') ) {
				$pfx_post = "?s={$pfx_s}&amp;x={$pfx_x}";
			} else if ( ($pfx_m == 'static') && (isset($pfx_edit)) ) {
				$pfx_post = "?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;edit={$pfx_edit}&amp;page={$pfx_page}";
			} else {
				$pfx_post = "?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;page={$pfx_page}";
			}
			echo "<form accept-charset=\"" . PFX_CHARSET . "\" "; if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') ) { echo 'onSubmit="MirrorUpdate();" '; } echo "action=\"{$pfx_post}\" method=\"post\" id=\"form_addedit\" class=\"form\">\n";
			echo "\t\t\t\t\t\t<fieldset>\n\t\t\t\t\t\t<legend>{$pfx_formtitle}</legend>\n";
			echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"table_name\" value=\"$this->tablename\" maxlength=\"80\" />\n";
			for ($pfx_j = 0; $pfx_j < count($pfx_Nams); $pfx_j++) {
				/* Clears out the form as some of the fields populate */
				if ( (isset($pfx_edit)) && ($pfx_edit) ) {
				} else {
					$pfx_Fild[$pfx_j] = "";
				}
				// if comments are disabled then hide the field
				if (($pfx_Nams[$pfx_j] == 'comments') && (!public_page_exists('comments'))) {
					echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"no\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					$pfx_j++;
				}
				if (!in_array($pfx_Nams[$pfx_j], $this->exclude)) { //fields populated and output depending on type etc.
					//$pfx_searchfor = "_".first_word($pfx_Nams[$pfx_j]);
					if ($pfx_Leng[$pfx_j] < 40) {
						$pfx_ln = $pfx_Leng[$pfx_j];
					} else if ($pfx_Leng[$pfx_j] <= 400) {
						$pfx_ln = 50;
					}
					$pfx_nullf = explode(" ", $pfx_Flag[$pfx_j]);
					if ($pfx_nullf[0] == 'not_null') { // label required fields
						if ((isset($pfx_lang['form_' . $pfx_Nams[$pfx_j]]))) {
							if (($pfx_Nams[$pfx_j] != 'page_name') or ($pfx_type == 'static') or (!isset($pfx_edit)) or (!$pfx_edit)) {
								/* Prevents the editing of page_name which does not work in modules and dynamic pages */
								$pfx_displayname = $pfx_lang['form_' . $pfx_Nams[$pfx_j]] . " <span class=\"form_required\">{$pfx_lang['form_required']}</span>";
							} else {
								$pfx_displayname = "";
							}
						} else {
							$pfx_displayname = simplify($pfx_Nams[$pfx_j]) . " <span class=\"form_required\">{$pfx_lang['form_required']}</span>";
						}
					} else {
						if ( (isset($pfx_lang['form_' . $pfx_Nams[$pfx_j]])) && ($pfx_lang['form_' . $pfx_Nams[$pfx_j]]) ) {
							$pfx_displayname = $pfx_lang['form_' . $pfx_Nams[$pfx_j]] . " <span class=\"form_optional\">{$pfx_lang['form_optional']}</span>";
						} else {
							$pfx_displayname = simplify($pfx_Nams[$pfx_j]) . " <span class=\"form_optional\">{$pfx_lang['form_optional']}</span>";
						}
					}
					// check language file for any form help
					if ( (isset($pfx_lang['form_help_' . $pfx_Nams[$pfx_j]])) && ($pfx_lang['form_help_' . $pfx_Nams[$pfx_j]]) ) {
						if (($pfx_Nams[$pfx_j] != 'page_name') or ($pfx_type == 'static') or (!isset($pfx_edit)) or (!$pfx_edit)) {
							/* Prevents the editing of page_name which does not work in modules and dynamic pages */
							$pfx_form_help = "<span class=\"form_help\">" . $pfx_lang['form_help_' . $pfx_Nams[$pfx_j]] . '</span>';
						} else {
							$pfx_form_help = "";
						}
					} else {
						$pfx_form_help = "";
					}
					if ( ($pfx_Type[$pfx_j] == 'longtext') or ($pfx_Leng[$pfx_j] > 800) or ($pfx_Type[$pfx_j] == 'blob') ) {
						$pfx_containsphp = strlen(stristr(utf8_decode(($pfx_Fild[$pfx_j])), '<?php')) > 0;
						if ($GLOBALS['rte_user'] == 'no') {
							if ($pfx_containsphp) {
								if ($GLOBALS['pfx_user_privs'] >= 2) {
									$pfx_form_help .= " <span class=\"alert\">{$pfx_lang['textarea_contains_php']}</span>";
								} else {
									$pfx_form_help .= " <span class=\"alert\">{$pfx_lang['insuficient_privs']}</span>";
								}
							}
						} else {
							if ($pfx_containsphp) {
								if ($GLOBALS['pfx_user_privs'] >= 2) {
								} else {
									$pfx_form_help .= " <span class=\"alert\">{$pfx_lang['insuficient_privs']}</span>";
								}
							}
						}
					}
					echo '<div class="form_row"><div class="form_label">';
					if ( (isset($pfx_displayname)) && ($pfx_displayname) ) {
						if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') && ($pfx_Type[$pfx_j] == 'longtext') ) { 
							echo "<label class=\"content-label\" for=\"$pfx_Nams[$pfx_j]\">{$pfx_displayname}</label>{$pfx_form_help}</div>\n";
						} else if ( ($pfx_Type[$pfx_j] == 'longtext') or ($pfx_Leng[$pfx_j] > 800) or ($pfx_Type[$pfx_j] == 'blob') ) {
							echo "<label class=\"content-label\" for=\"$pfx_Nams[$pfx_j]\">{$pfx_displayname}</label>{$pfx_form_help}</div>\n";
						} else {
							echo "<label for=\"$pfx_Nams[$pfx_j]\">{$pfx_displayname}</label>{$pfx_form_help}</div>\n";
						}
					}
					if ( ($pfx_Type[$pfx_j] == 'timestamp') && (isset($pfx_edit)) && ($pfx_edit) ) {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">\n";
						date_dropdown($pfx_Fild[$pfx_j]);
						echo "\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if ($pfx_Type[$pfx_j] == 'timestamp') {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">\n";
						if ( (isset($pfx_date)) && ($pfx_date) ) {
							date_dropdown($pfx_date);
						} else {
							$pfx_date = FALSE;
							date_dropdown($pfx_date);
						}
						echo "\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if ( ($pfx_Type[$pfx_j] == 'longtext') or ($pfx_Leng[$pfx_j] > 800) or ($pfx_Type[$pfx_j] == 'blob') ) {
						$pfx_containsphp = editableArea($pfx_Fild[$pfx_j], $pfx_Nams[$pfx_j]);
						echo '</div>';
					} else if ( (strpos($pfx_Flag[$pfx_j], 'set') == TRUE) ) {
						$pfx_values = NULL;
							while ( $pfx_row = @mysql_fetch_object($this->fields) ) {
								if ($pfx_row->Field == $pfx_Nams[$pfx_j])  {
									$pfx_values = explode(',', preg_replace('/set\(|\)$/', '', $pfx_row->Type));
									break;
								}
							}
						echo '<div class="form_item_radio">';
							foreach ($pfx_values as $pfx_key => $pfx_value) {
								$pfx_val = str_replace("'", "", $pfx_value);
								if ($pfx_val == $pfx_Fild[$pfx_j])  {
									echo simplify($pfx_val) . "<input checked=\"checked\" type=\"radio\" name=\"{$pfx_Nams[$pfx_j]}\" class=\"form_radio\" value=\"{$pfx_val}\" />";
								} else if ($pfx_val == 'yes') {
									echo simplify($pfx_val) . "<input checked=\"checked\" type=\"radio\" name=\"{$pfx_Nams[$pfx_j]}\" class=\"form_radio\" value=\"{$pfx_val}\" />";
								} else {
									echo simplify($pfx_val) . "<input type=\"radio\" name=\"{$pfx_Nams[$pfx_j]}\" class=\"form_radio\" value=\"{$pfx_val}\" />";
								}
							}
						echo '</div></div>';
					} else if (first_word($pfx_Nams[$pfx_j]) == 'image') {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop image_preview\">\n";
						db_dropdown('pfx_files', $pfx_Fild[$pfx_j], $pfx_Nams[$pfx_j], "file_type = 'Image' order by file_id desc", $pfx_edit, $pfx_go);
						echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('" . $pfx_Nams[$pfx_j] . "'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if (first_word($pfx_Nams[$pfx_j]) == 'document') {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">\n";
						db_dropdown('pfx_files', $pfx_Fild[$pfx_j], $pfx_Nams[$pfx_j], "file_type = 'Other' order by file_id desc", $pfx_edit, $pfx_go);
						echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('" . $pfx_Nams[$pfx_j] . "'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if (first_word($pfx_Nams[$pfx_j]) == 'video') {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">\n";
						db_dropdown('pfx_files', $pfx_Fild[$pfx_j], $pfx_Nams[$pfx_j], "file_type = 'Video' order by file_id desc", $pfx_edit, $pfx_go);
						echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('" . $pfx_Nams[$pfx_j] . "'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if (first_word($pfx_Nams[$pfx_j]) == 'audio') {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">\n";
						db_dropdown('pfx_files', $pfx_Fild[$pfx_j], $pfx_Nams[$pfx_j], "file_type = 'Audio' order by file_id desc", $pfx_edit, $pfx_go);
						echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('" . $pfx_Nams[$pfx_j] . "'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if (first_word($pfx_Nams[$pfx_j]) == 'file') {
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">\n";
						db_dropdown('pfx_files', $pfx_Fild[$pfx_j], $pfx_Nams[$pfx_j], "file_id >= '0' order by file_id desc", $pfx_edit, $pfx_go);
						echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('" . $pfx_Nams[$pfx_j] . "'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if ($pfx_Nams[$pfx_j] == 'tags') {
						$pfx_tableid   = 0;
						$pfx_condition = $pfx_tableid . " >= '0'";
						form_tag($this->tablename, $pfx_condition, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item\">\n\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" id=\"$pfx_Nams[$pfx_j]\" value=\"$pfx_Fild[$pfx_j]\" size=\"";
						if ((isset($pfx_ln))) {
							echo $pfx_ln;
						} else {
							$pfx_ln = 25;
							echo $pfx_ln;
						}
						echo "\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if ($pfx_Nams[$pfx_j] == 'page_blocks') {
						form_blocks($pfx_lang, $pfx_s, $pfx_m, $pfx_x);
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item\">\n\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" id=\"$pfx_Nams[$pfx_j]\" value=\"$pfx_Fild[$pfx_j]\" size=\"";
						if ((isset($pfx_ln))) {
							echo $pfx_ln;
						} else {
							$pfx_ln = 25;
							echo $pfx_ln;
						}
						echo "\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t</div>\n";
					} else if ($pfx_Nams[$pfx_j] == 'privs') {
						if ($pfx_Fild[$pfx_j] == 2) {
							$pfx_adminclass    = "selected=\"selected\"";
							$pfx_everyoneclass = NULL;
						} else {
							$pfx_everyoneclass = "selected=\"selected\"";
							$pfx_adminclass    = NULL;
						}
						echo "\t\t\t\t\t\t\t\t<div class=\"form_item_drop\">
									<select class=\"form_select\" id=\"$pfx_Nams[$pfx_j]\" name=\"$pfx_Nams[$pfx_j]\">
										<option value=\"2\" $pfx_adminclass>Administrators only</option>
										<option value=\"1\" $pfx_everyoneclass>Administrators and Clients</option>
									</select>
	   							</div>\n\t\t\t\t\t\t\t</div>\n";
					} else {
						if (($pfx_Nams[$pfx_j] != 'page_name') or ($pfx_type == 'static') or (!isset($pfx_edit)) or (!$pfx_edit)) {
							/* Prevents the editing of page_name which does not work in modules and dynamic pages */
							echo "\t\t\t\t\t\t\t\t<div class=\"form_item\">\n\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" id=\"$pfx_Nams[$pfx_j]\" value=\"" . htmlspecialchars($pfx_Fild[$pfx_j], ENT_QUOTES, PFX_CHARSET) . "\" size=\"";
							if ((isset($pfx_ln))) {
								echo $pfx_ln;
							} else {
								$pfx_ln = 25;
								echo $pfx_ln;
							}
							echo "\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n\t\t\t\t\t\t\t\t</div>";
						} else {
							echo "\t\t\t\t\t\t\t\t<div style=\"display:none\" class=\"form_item\">\n\t\t\t\t\t\t\t\t<input style=\"display:none\" type=\"text\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" id=\"$pfx_Nams[$pfx_j]\" value=\"" . htmlspecialchars($pfx_Fild[$pfx_j], ENT_QUOTES, PFX_CHARSET) . "\" size=\"";
							if ((isset($pfx_ln))) {
								echo $pfx_ln;
							} else {
								$pfx_ln = 25;
								echo $pfx_ln;
							}
							echo "\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n\t\t\t\t\t\t\t\t</div>";
						}
						echo "\n\t\t\t\t\t\t\t</div>\n";
					}
					//other field types still to come: File uploads...?
					//hidden fields populated
				} else {
					if ((($pfx_Nams[$pfx_j] == 'page_id') && (isset($pfx_s)) && ($pfx_s == 'publish') && ($pfx_m == 'dynamic'))) {
						$pfx_page_id = get_page_id($pfx_x);
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"$pfx_page_id\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					} else if (last_word($pfx_Nams[$pfx_j]) == 'id') {
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"$pfx_Fild[$pfx_j]\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					} else if (($pfx_Nams[$pfx_j] == 'author')) {
						if ((isset($pfx_edit)) && ($pfx_edit)) {
							$pfx_output = $pfx_Fild[$pfx_j];
						} else {
							if (!isset($GLOBALS['pfx_user'])) {
								$GLOBALS['pfx_user'] = NULL;
							}
							$pfx_output = $GLOBALS['pfx_user'];
						}
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"{$pfx_output}\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					} else if ($pfx_Type[$pfx_j] == "timestamp") {
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"" . returnSQLtimestamp(time()) . "\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					} else if ($pfx_Nams[$pfx_j] == 'page_type') {
						if ($pfx_type) {
							$pfx_output = $pfx_type;
						} else {
							if (isset($pfx_edit)) {
								$pfx_output = safe_field('page_type', 'pfx_core', "page_id='$pfx_edit'");
							}
						}
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"{$pfx_output}\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					} else if (($pfx_Nams[$pfx_j] == 'publish' && !$pfx_edit)) {
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"yes\" maxlength=\"0\" />\n";
					} else if ($pfx_Nams[$pfx_j] == 'page_content') {
						// do nothing
					} else if ($pfx_Nams[$pfx_j] == 'admin') {
						// do nothing
					} else {
						echo "\t\t\t\t\t\t\t<input type=\"hidden\" class=\"form_text\" name=\"$pfx_Nams[$pfx_j]\" value=\"$pfx_Fild[$pfx_j]\" maxlength=\"" . $pfx_Leng[$pfx_j] . "\" />\n";
					}
				}
			}
			if ( (isset($pfx_edit)) && ($pfx_edit) ) {
				if ( ($GLOBALS['pfx_user_privs'] <= 1) && ($pfx_containsphp) ) {
				} else {
					echo "\t\t\t\t\t\t\t<div class=\"form_row_button\">\n\t\t\t\t\t\t\t\t<input type=\"submit\" name=\"submit_edit\" class=\"form_submit\" value=\"{$pfx_lang['form_button_update']}\" />\n\t\t\t\t\t\t\t</div>\n";
				}
			} else if ( (isset($pfx_go)) && ($pfx_go == 'new') ) {
				echo "\t\t\t\t\t\t\t<div class=\"form_row_button\" id=\"form_button\">\n\t\t\t\t\t\t\t\t<input type=\"submit\" name=\"submit_new\" class=\"form_submit\" value=\"{$pfx_lang['form_button_save']}\" />\n\t\t\t\t\t\t\t</div>\n";
				/* A save draft and save button button could be placed here */
			} else {
				echo "\t\t\t\t\t\t\t<div class=\"form_row_button\" id=\"form_button\">\n\t\t\t\t\t\t\t\t<input type=\"submit\" name=\"submit_new\" class=\"form_submit\" value=\"{$pfx_lang['form_button_save']}\" />\n\t\t\t\t\t\t\t</div>\n";
			}
			if ($pfx_m == 'static') {
			} else {
				echo "\t\t\t\t\t\t\t<div class=\"form_row_button\">\n\t\t\t\t\t\t\t\t<span class=\"form_button_cancel\">
							<form action=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" method=\"post\">
								<input type=\"submit\" title=\"{$pfx_lang['form_button_cancel']}\" value=\"{$pfx_lang['form_button_cancel']}\" />
							</form></span>\n\t\t\t\t\t\t\t</div>\n";
			}
			echo '<div class="safclear"></div></fieldset></form>';
		}
	}
}
// ------------------------------------------------------------------
// build module page
function admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_exclude = array(NULL), $pfx_edit_exclude = array(NULL), $pfx_view_number, $pfx_tags, $pfx_type = FALSE, $pfx_go = FALSE, $pfx_page = FALSE, $pfx_message = FALSE, $pfx_edit = FALSE, $pfx_submit_new = FALSE, $pfx_submit_edit = FALSE, $pfx_delete = FALSE, $pfx_messageok = FALSE, $pfx_new = FALSE, $pfx_search_submit = FALSE, $pfx_field = FALSE, $pfx_search_words = FALSE, $pfx_scroll = FALSE, $pfx_page_display_name = FALSE, $pfx_page_id = FALSE, $pfx_tag = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	if ( (isset($GLOBALS['pfx_user_privs'])) && ($GLOBALS['pfx_user_privs'] >= 1) ) {
		$pfx_type = 'module';
		if ( (isset($pfx_go)) && ($pfx_go == 'new') && (isset($pfx_table_name)) ) {
			admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
			admin_new($pfx_lang, $pfx_table_name, $pfx_edit_exclude, $pfx_go, $pfx_edit, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
		} else if ( (isset($pfx_edit)) && ($pfx_edit) && (isset($pfx_table_name)) ) {
			admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
			$pfx_message = admin_edit($pfx_table_name, "{$pfx_module_name}_id", $pfx_edit, $pfx_edit_exclude, $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
		} else if (isset($pfx_table_name)) {
			$pfx_scroll = admin_carousel($pfx_lang, $pfx_x, $pfx_scroll, $pfx_s);
			admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
			admin_block_search($pfx_type, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
			echo '<div id="pfx_content">';
			admin_overview($pfx_table_name, '', $pfx_order_by, $pfx_asc_desc, $pfx_exclude, $pfx_view_number, $pfx_type, $pfx_lang, $pfx_page, $pfx_message, $pfx_messageok, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_tag, $pfx_page_display_name, $pfx_s, $pfx_m, $pfx_x);
			echo '</div><div id="blocks">';
			if ( (isset($pfx_tags)) && ($pfx_tags == 'yes') ) {
				admin_block_tag_cloud($pfx_table_name, "{$pfx_module_name}_id >= 0", $pfx_type, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
			}
			echo '</div>';
		}
		return array(
			      'scroll' => $pfx_scroll,
			      'message' => $pfx_message
			    );
	}
}
// ------------------------------------------------------------------
// display page information
function admin_head($pfx_lang, $pfx_page_display_name = FALSE, $pfx_page_id = FALSE, $pfx_edit = FALSE, $pfx_go = FALSE, $pfx_tag = FALSE, $pfx_search_words = FALSE, $pfx_search_submit = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_rs = safe_row('*', 'pfx_core', "page_name = '{$pfx_x}' limit 0,1");
	if ($pfx_rs) {
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = NULL;
		if ((isset($pfx_tag)) && ($pfx_tag)) {
			$pfx_stitle = $pfx_page_display_name . " (" . ucwords($pfx_lang['all_posts_tagged']) . ": " . $pfx_tag . ")";
		} else if ($pfx_search_words) {
			$pfx_stitle = $pfx_page_display_name . " ({$pfx_lang['search']}: " . chopme(sterilise($pfx_search_words), 40) . ")";
		} else if ($pfx_search_submit) {
			$pfx_stitle = $pfx_page_display_name . " ({$pfx_lang['search']}: $pfx_search_submit)";
		} else {
			$pfx_stitle = $pfx_page_display_name;
		}
		echo "\t\t\t\t<div id=\"page_header\">
					<h2>{$pfx_stitle}</h2>
			</div>\n";
		if ( (isset($pfx_edit)) && ($pfx_edit) ) {
		} else {
			if ( (isset($pfx_go)) && ($pfx_go) ) {
			} else {
				if ( ($pfx_s == 'publish') && ($pfx_x != 'comments') ) {
					echo "<ul id=\"page_tools_publish\">
					      <li><form action=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;go=new\" method=\"post\">
					      <input type=\"submit\" title=\"{$pfx_lang['new_entry']} " . str_replace('.', "", $pfx_lang['entry']) . "\" class=\"page_new\" value=\"{$pfx_lang['new_entry']} " . str_replace('.', "", $pfx_lang['entry']) . "\" />
					      </form></li>
					      </ul>\n";
				} else if ($pfx_x == 'comments') {
					/* Do nothing */
				} else {
					echo "<ul id=\"page_tools\">
					      <li><form action=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;go=new\" method=\"post\">
					      <input type=\"submit\" title=\"{$pfx_lang['new_entry']} " . str_replace('.', "", $pfx_lang['entry']) . "\" class=\"page_new\" value=\"{$pfx_lang['new_entry']} " . str_replace('.', "", $pfx_lang['entry']) . "\" />
					      </form></li>
					      </ul>\n";
				}
			}
		}
	}
}
// ------------------------------------------------------------------
// display admin block for searching
function admin_block_search($pfx_type, $pfx_lang, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	echo "\n\t\t\t\t\t<div id=\"admin_block_search\" class=\"admin_block\">\n";
	echo "\t\t\t\t\t\t<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" method=\"post\" id=\"search\">
							<fieldset>
							<legend>{$pfx_lang['search']}</legend>
								<div class=\"form_row\">
									<div class=\"form_item\"><input type=\"text\" name=\"search_words\" id=\"search-keywords\" value=\"\" class=\"form_text\" size=\"25\" /></div>
								</div>							
								<div class=\"form_row_button\">
									<input type=\"submit\" name=\"search_submit\" id=\"search_submit\" value=\"{$pfx_lang['search']}\" />
								</div>
							</fieldset>
						</form>";
	echo "\n\t\t\t\t\t</div>\n";
}
// ------------------------------------------------------------------ 
// view table overview
function admin_overview($pfx_table_name, $pfx_condition, $pfx_order_by, $pfx_asc_desc, $pfx_exclude = array(NULL), $pfx_view_number, $pfx_type, $pfx_lang, $pfx_page = FALSE, $pfx_message = FALSE, $pfx_messageok = FALSE, $pfx_search_submit = FALSE, $pfx_field = FALSE, $pfx_search_words = FALSE, $pfx_tag = FALSE, $pfx_page_display_name = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_table_name  = adjust_prefix($pfx_table_name);
	$pfx_searchwords = trim($pfx_search_words);
	if ($pfx_page) {
		$pfx_searchwords = $pfx_search_submit;
	}
	if ( (isset($pfx_search_submit)) && ($pfx_search_submit) && (isset($pfx_table_name)) && ($pfx_table_name) ) {
		$pfx_searchwords = sterilise($pfx_searchwords, FALSE);
		/* Build search sql */
		$pfx_r2          = safe_query("show fields from {$pfx_table_name}");
		$pfx_search_sql = FALSE;
		for ($pfx_j = 0; $pfx_j < mysql_num_rows($pfx_r2); $pfx_j++) {
			if ($pfx_F = mysql_fetch_array($pfx_r2)) {
				$pfx_an[$pfx_j] = $pfx_F['Field'];
			}
			if (last_word($pfx_an[$pfx_j]) != 'id') {
				if ($pfx_an[$pfx_j] != 'posted') {
					if ($pfx_an[$pfx_j] != 'author') {
						if ($pfx_an[$pfx_j] != 'comments') {
							if ($pfx_an[$pfx_j] != 'public') {
								if (first_word($pfx_an[$pfx_j]) != 'last') {
									if ($pfx_an[$pfx_j] != 'date') {
										$pfx_search_sql .= $pfx_an[$pfx_j] . " like '%" . $pfx_searchwords . "%' OR ";
									}
								}
							}
						}
					}
				}
			}
		}
		if ( (isset($pfx_search_sql)) && ($pfx_search_sql) ) {
			$pfx_search_sql = substr($pfx_search_sql, 0, (strlen($pfx_search_sql) - 3)) . "";
		} else {
			$pfx_search_sql = FALSE;
		}
	}
	if ( (isset($pfx_tag)) && ($pfx_tag) ) {
		$pfx_tag = squash_slug($pfx_tag);
	}
	if ( (isset($pfx_table_name)) && ($pfx_table_name) ) {
		if ( (isset($pfx_search_submit)) && ($pfx_search_submit) ) {
			if ($pfx_m == 'dynamic') {
				$pfx_page_id = get_page_id($pfx_x);
				$pfx_r1      = safe_query("select * from {$pfx_table_name} where page_id = '{$pfx_page_id}' and ({$pfx_search_sql}) order by {$pfx_order_by} {$pfx_asc_desc}");
			} else {
				$pfx_r1 = safe_query("select * from {$pfx_table_name} where {$pfx_search_sql} order by {$pfx_order_by} {$pfx_asc_desc}");
			}
		} else if ( (isset($pfx_tag)) && ($pfx_tag) ) {
			$pfx_r1 = safe_query("select * from {$pfx_table_name} where tags REGEXP '[[:<:]]{$pfx_tag}[[:>:]]' order by {$pfx_order_by} {$pfx_asc_desc}");
		} else {
			$pfx_r1 = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc}");
		}
	}
	if ( (isset($pfx_r1)) && ($pfx_r1) ) {
		$pfx_total = mysql_num_rows($pfx_r1);
		if ( (!$pfx_page) && (isset($pfx_table_name)) && ($pfx_table_name) ) {
			$pfx_lo   = 0;
			$pfx_page = 1;
			if ( (isset($pfx_search_submit)) && ($pfx_search_submit) ) {
				if ($pfx_m == 'dynamic') {
					$pfx_page_id = get_page_id($pfx_x);
					$pfx_r       = safe_query("select * from {$pfx_table_name} where page_id = '{$pfx_page_id}' and ({$pfx_search_sql}) order by {$pfx_order_by} {$pfx_asc_desc}");
				} else {
					$pfx_r = safe_query("select * from {$pfx_table_name} where {$pfx_search_sql} order by {$pfx_order_by} {$pfx_asc_desc}");
				}
			} else if ( (isset($pfx_tag)) && ($pfx_tag) ) {
				$pfx_r = safe_query("select * from {$pfx_table_name} where tags REGEXP '[[:<:]]{$pfx_tag}[[:>:]]' order by {$pfx_order_by} {$pfx_asc_desc}");
			} else {
				$pfx_r = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc} limit {$pfx_lo},{$pfx_view_number}");
			}
		} else if ( (isset($pfx_table_name)) && ($pfx_table_name) ) {
			$pfx_lo = ($pfx_page - 1) * $pfx_view_number;
			if ( (isset($pfx_search_submit)) && ($pfx_search_submit) ) {
				if ($pfx_m == 'dynamic') {
					$pfx_page_id = get_page_id($pfx_x);
					$pfx_r       = safe_query("select * from {$pfx_table_name} where page_id = '{$pfx_page_id}' and ({$pfx_search_sql}) order by {$pfx_order_by} {$pfx_asc_desc}");
				} else {
					$pfx_r = safe_query("select * from {$pfx_table_name} where {$pfx_search_sql} order by {$pfx_order_by} {$pfx_asc_desc}");
				}
			} else if ( (isset($pfx_tag)) && ($pfx_tag) ) {
				$pfx_r = safe_query("select * from {$pfx_table_name} where tags REGEXP '[[:<:]]{$pfx_tag}[[:>:]]' order by {$pfx_order_by} {$pfx_asc_desc}");
			} else {
				$pfx_r = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc} limit {$pfx_lo},{$pfx_view_number}");
			}
		}
		if ($pfx_r) {
			$pfx_rows = mysql_num_rows($pfx_r);
			$pfx_hi   = $pfx_lo + $pfx_view_number;
			if ($pfx_hi > $pfx_total) {
				$pfx_finalmax = $pfx_total - $pfx_lo;
				$pfx_hi       = $pfx_total;
			}
			$pfx_pages = ceil($pfx_total / $pfx_view_number);
			if ($pfx_pages < 1) {
				$pfx_pages = 1;
			}
		}
		$pfx_a = new Paginator_html($pfx_page, $pfx_total);
		$pfx_a->set_Limit($pfx_view_number);
		$pfx_a->set_Links(4);
		$pfx_whereami = "?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}";
		if ( (isset($pfx_tag)) && ($pfx_tag) ) {
			$pfx_whereami = "?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;tag={$pfx_tag}";
		}
		if ($pfx_search_submit) {
			$pfx_whereami = "?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;search_submit={$pfx_searchwords}";
		}
		echo "\n\t\t\t\t\t<div class=\"admin_table_holder pcontent\">\n\t\t\t\t\t";
		$pfx_wheream = "?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;page={$pfx_page}";
		if ((isset($pfx_table_name)) && ($pfx_rows)) {
			if (isset($pfx_finalmax) && ($pfx_finalmax)) {
			} else {
				$pfx_finalmax = NULL;
			}
			$pfx_Table = new ShowTable($pfx_r, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_wheream, $pfx_type, $pfx_s);
			$pfx_Table->DrawBody($pfx_lang, $pfx_page_display_name);
			$pfx_loprint = $pfx_lo + 1;
			echo "\n\t\t\t\t\t\t<div id=\"admin_table_overview\">\n\t\t\t\t\t\t\t<p>{$pfx_lang['total_records']}: {$pfx_total} ({$pfx_lang['showing_from_record']} {$pfx_loprint} {$pfx_lang['to']} {$pfx_hi}) {$pfx_pages} {$pfx_lang['page(s)']}.</p>\n\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t<div id=\"admin_table_pages\">\n\t\t\t\t\t\t\t";
			echo '<p>';
			$pfx_a->previousNext($pfx_whereami);
			echo '</p>';
			echo "\n\t\t\t\t\t\t</div>";
		} else {
			if (($pfx_search_submit) or (isset($pfx_tag)) && ($pfx_tag)) {
				echo "<div class=\"helper\"><h3>{$pfx_lang['help']}</h3><p>{$pfx_lang['helper_search']}</p></div>";
			} else {
				echo "<div class=\"helper\"><h3>{$pfx_lang['help']}</h3><p>{$pfx_lang['helper_nocontent']}</p></div>";
			}
			echo "\n\t\t\t\t\t</div>\n";
		}
		if ($pfx_rows) {
			echo "\n\t\t\t\t\t</div>\n";
		}
	}
}
// ------------------------------------------------------------------
// show the page carousel
function admin_carousel($pfx_lang, $pfx_current = FALSE, $pfx_scroll = FALSE, $pfx_s = FALSE) {
	echo "<h2>{$pfx_lang['nav1_publish']}</h2>\n";
	$pfx_rz = safe_rows('*', 'pfx_core', "public = 'yes'  and publish = 'yes' order by page_order asc");
	if (count($pfx_rz) <= 1) {
		if ((isset($GLOBALS['pfx_user_privs'])) && ($GLOBALS['pfx_user_privs'] >= 2)) {
			echo "\t\t\t\t<div class=\"helper\"><h3>{$pfx_lang['help']}</h3><p>{$pfx_lang['helper_nopages404']} {$pfx_lang['helper_nopagesadmin']}</p></div>\n";
		} else {
			echo "\t\t\t\t<div class=\"helper\"><h3>{$pfx_lang['help']}</h3><p>{$pfx_lang['helper_nopages404']} {$pfx_lang['helper_nopagesuser']}</p></div>\n";
		}
	} else {
		echo "\t\t\t\t<div id=\"carousel-wrapper\"><button class=\"next\">>></button><button class=\"prev\"><<</button><div id=\"carousel-wrap\"><div id=\"carousel\"><ul id=\"mycarousel\" class=\"jcarousel-skin-tango\">\n";
		if (isset($GLOBALS['pfx_user_privs'])) {
			$pfx_rs1 = safe_rows('*', 'pfx_core', "public = 'yes' and publish = 'yes' and privs <= '{$GLOBALS['pfx_user_privs']}' order by page_order asc");
		}
		if ($pfx_rs1) {
			$pfx_num = count($pfx_rs1);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out               = $pfx_rs1[$pfx_i];
				if ($pfx_out['page_description'] == '') {
				    $pfx_page_desc = $pfx_lang['no-desc'];
				} else {
				    if ( strlen($pfx_out['page_description']) >= 41 ) {
					$pfx_page_desc = rtrim( substr($pfx_out['page_description'], 0, 41) ) . '...';
				    } else {
					$pfx_page_desc = $pfx_out['page_description'];
				    }

				}
				if ( strlen($pfx_out['page_display_name']) >= 10 ) {
					$pfx_page_display_name = rtrim( substr($pfx_out['page_display_name'], 0, 10) );
				} else {
					$pfx_page_display_name = $pfx_out['page_display_name'];
				}
				$pfx_page_name         = $pfx_out['page_name'];
				$pfx_page_type         = $pfx_out['page_type'];
				$pfx_page_id           = $pfx_out['page_id'];
				$pfx_m                 = $pfx_page_type;
				$pfx_x                 = $pfx_page_name;
				if ($pfx_current == $pfx_x) {
					$pfx_class  = 'current';
					$pfx_scroll = $pfx_i;
				} else {
					$pfx_class = "";
				}
				if ($pfx_m == 'dynamic') {
					echo "\t\t\t\t\t<li id=\"c_{$pfx_page_name}\" class=\"page innav {$pfx_class}\"><a class=\"link-dynamic c-link\" href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" title=\"{$pfx_page_desc}\">{$pfx_page_display_name}</a><h4 class=\"cskin-dynamic\"><a href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" title=\"{$pfx_page_desc}\"><img src=\"admin/theme/images/png/clear.png\" alt=\"clear-png-image\" width=\"128px\" height=\"128px\" /></a></h4></li>\n";
				} else if ($pfx_m == 'module') {
					echo "\t\t\t\t\t<li id=\"c_{$pfx_page_name}\" class=\"page innav {$pfx_class}\"><a class=\"link-module c-link\" href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" title=\"{$pfx_page_desc}\">{$pfx_page_display_name}</a><h4 class=\"cskin-module\"><a href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" title=\"{$pfx_page_desc}\"><img src=\"admin/theme/images/png/clear.png\" alt=\"clear-png-image\" width=\"128px\" height=\"128px\" /></a></h4></li>\n";
				} else if ($pfx_m == 'static') {
					echo "\t\t\t\t\t<li id=\"c_{$pfx_page_name}\" class=\"page innav {$pfx_class}\"><a class=\"link-static c-link\" href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;edit={$pfx_page_id}\" title=\"{$pfx_page_desc}\">{$pfx_page_display_name}</a><h4 class=\"cskin-static\"><a href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}&amp;edit={$pfx_page_id}\" title=\"{$pfx_page_desc}\"><img src=\"admin/theme/images/png/clear.png\" alt=\"clear-png-image\" width=\"128px\" height=\"128px\" /></a></h4></li>\n";
				} else {
					if ($pfx_m == 'plugin') {
						$pfx_m = 'module';
					}
					echo "\t\t\t\t\t<li id=\"c_{$pfx_page_name}\" class=\"page innav {$pfx_class}\"><a class=\"link-plugin c-link\" href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" title=\"{$pfx_page_desc}\">{$pfx_page_display_name}</a><h4 class=\"cskin-plugin\"><a href=\"?s={$pfx_s}&amp;m={$pfx_m}&amp;x={$pfx_x}\" title=\"{$pfx_page_desc}\"><img src=\"admin/theme/images/png/clear.png\" alt=\"clear-png-image\" width=\"128px\" height=\"128px\" /></a></h4></li>\n";
				}
				$pfx_i++;
			}
		}
		echo "\t\t\t\t</ul></div></div></div><div class=\"clear\"></div>\n";
		return $pfx_scroll;
	}

}
// ------------------------------------------------------------------
// edit table entry
function admin_edit($pfx_table_name, $pfx_edit_id, $pfx_edit, $pfx_edit_exclude, $pfx_lang, $pfx_go = FALSE, $pfx_message = FALSE, $pfx_page = FALSE, $pfx_page_display_name = FALSE, $pfx_type = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_an = NULL;
	$pfx_at = NULL;
	$pfx_al = NULL;
	$pfx_af = NULL;
	$pfx_az = NULL;
	if (isset($pfx_table_name)) {
		$pfx_table_name = adjust_prefix($pfx_table_name);
	}

	if ((isset($pfx_edit)) && (isset($pfx_table_name))) {
		$pfx_sql = "select * from $pfx_table_name where {$pfx_edit_id}={$pfx_edit}";
		$pfx_r2  = safe_query($pfx_sql);
	}
	if ($pfx_r2) {
		if ($pfx_f = mysql_fetch_array($pfx_r2)) {
			for ($pfx_j = 0; $pfx_j < mysql_num_fields($pfx_r2); $pfx_j++) {
				$pfx_an .= mysql_field_name($pfx_r2, $pfx_j) . "|";
				$pfx_at .= mysql_field_type($pfx_r2, $pfx_j) . "|";
				$pfx_al .= "50|";
				/* Some field lengths seem unset or inconsistent. CSS classes would handle this better. */
				/* $pfx_al .= mysql_field_len($pfx_r2, $pfx_j) . "|"; */
				$pfx_af .= mysql_field_flags($pfx_r2, $pfx_j) . "|";
				$pfx_az .= $pfx_f[$pfx_j] . "|";
			}
			if ($pfx_m == 'static') {
				echo "\n\t\t\t\t<div class=\"admin_form form_static\">\n\n\t\t\t\t\t";
			} else {
				echo "\n\t\t\t\t<div class=\"admin_form\">\n\n\t\t\t\t\t";
			}
			if (isset($pfx_table_name)) {
				if (!isset($pfx_nam)) {
					$pfx_nam = NULL;
				}
				$pfx_Blank = new ShowBlank($pfx_an, $pfx_at, $pfx_al, $pfx_af, $pfx_az, $pfx_nam, $pfx_edit_exclude, $pfx_table_name);
				$pfx_Blank->ShowBody($pfx_lang, $pfx_go, $pfx_edit, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
			}
			echo "\n\t\t\t\t</div>";
		}
	} else {
		return $pfx_message = $pfx_lang['form_build_fail'];
	}
}
// ------------------------------------------------------------------
// add new table entry
function admin_new($pfx_lang, $pfx_table_name, $pfx_edit_exclude, $pfx_go = FALSE, $pfx_edit = FALSE, $pfx_page = FALSE, $pfx_page_display_name = FALSE, $pfx_type = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	if (isset($pfx_table_name)) {
		$pfx_an = $pfx_at = $pfx_af = $pfx_az = $pfx_al = '';
		$pfx_r2 = safe_query('show fields from ' . CONFIG_TABLE_PREFIX . "$pfx_table_name");
		$pfx_r3 = safe_query('select * from ' . CONFIG_TABLE_PREFIX . "$pfx_table_name WHERE 1=0");
		for ($pfx_j = 0; $pfx_j < mysql_num_rows($pfx_r2); $pfx_j++) {
			$pfx_flags = mysql_field_flags($pfx_r3, $pfx_j);
			$pfx_af .= $pfx_flags . '|';
			if ($pfx_F = mysql_fetch_array($pfx_r2)) {
				$pfx_an .= "{$pfx_F['Field']}|";
				$pfx_at .= preg_replace('([()0-9]+)', "", "{$pfx_F['Type']}") . '|';
			}
			if (preg_match('([0-9]+)', $pfx_F['Type'], $pfx_str)) {
				$pfx_al .= "{$pfx_str[0]}|";
			} else {
				$pfx_al .= '|';
				$pfx_az .= "{$pfx_F['Default']}|";
			}
			if ($pfx_F['Key'] == "PRI") {
				$pfx_nam = $pfx_F['Field'];
			}
		}
		echo "\n\t\t\t\t<div id=\"admin_form\">\n\n\t\t\t\t\t";
		if (isset($pfx_table_name)) {
			$pfx_Blank = new ShowBlank($pfx_an, $pfx_at, $pfx_al, $pfx_af, $pfx_az, $pfx_nam, $pfx_edit_exclude, $pfx_table_name);
			$pfx_Blank->ShowBody($pfx_lang, $pfx_go, $pfx_edit, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
		}
		echo "\n\t\t\t\t</div>";
	}
}
// ------------------------------------------------------------------
// delete code
if ( (isset($GLOBALS['pfx_user'])) && (isset($GLOBALS['pfx_user_privs'])) && ($GLOBALS['pfx_user_privs'] >= 1) ) {
	if (isset($pfx_delete)) {
		if ((isset($pfx_s)) && ($pfx_s == 'settings') && ($pfx_delete == 1)) {
			// protect 404
		} else if ((isset($pfx_s)) && ($pfx_s == 'settings')) {
			$pfx_table = 'pfx_core';
			$pfx_id    = 'page_id';
		} else if ((isset($pfx_s)) && ($pfx_s == 'publish') && ($pfx_m == 'dynamic')) {
			$pfx_table = 'pfx_dynamic_posts';
			$pfx_id    = 'post_id';
		} else if ((isset($pfx_s)) && ($pfx_s == 'publish') && ($pfx_m == 'module')) {
			$pfx_table = "pfx_module_{$pfx_x}";
			$pfx_id    = "{$pfx_x}_id";
		}
		if ( (isset($pfx_table)) && ($pfx_table) ) {
		$pfx_table      = adjust_prefix($pfx_table);
		$pfx_table_id_delete = safe_row('*', "{$pfx_table}", "{$pfx_id}='{$pfx_delete}' limit 0,1");
		$pfx_getdetails = extract($pfx_table_id_delete, EXTR_PREFIX_ALL, 'pfx');
		$pfx_table_id_delete = FALSE;
		}
		if ( (isset($pfx_getdetails)) && ($pfx_getdetails) ) {
			$pfx_del = safe_delete($pfx_table, "{$pfx_id}='{$pfx_delete}'");
		}
		if ( (isset($pfx_del)) && ($pfx_del) ) {
			if ((isset($pfx_s)) && (isset($pfx_m)) && ($pfx_s == 'settings') && ($pfx_m == 'dynamic')) {
				$pfx_page_display_name = safe_field('page_display_name', 'pfx_core', "page_id='{$pfx_del}'");
				//do not delete the posts as one false click could destroy lots of data. Backup first?
				//safe_delete("pfx_dynamic_posts", "page_id='$pfx_delete'"); 
				safe_delete('pfx_dynamic_settings', "page_id='{$pfx_delete}'");
			}
			if ( (isset($pfx_s)) && (isset($pfx_m)) && (isset($pfx_del)) && ($pfx_s == 'settings') && ($pfx_m == 'static') ) {
				$pfx_page_display_name = safe_field('page_display_name', 'pfx_core', "page_id='{$pfx_del}'");
				safe_delete('pfx_static_posts', "page_id='{$pfx_delete}'");
			}
			if ( (isset($pfx_s)) && (isset($pfx_m)) && ($pfx_s == 'settings') && ($pfx_m == 'module') ) {
				$pfx_table_mod          = CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_page_name}";
				$pfx_table_mod_settings = CONFIG_TABLE_PREFIX . "pfx_module_{$pfx_page_name}_settings";
				$pfx_sql                = "DROP TABLE IF EXISTS {$pfx_table_mod}";
				$pfx_sql1               = "DROP TABLE IF EXISTS {$pfx_table_mod_settings}";
				//do not drop the tables as one false click could destroy lots of data. Backup first?
				//safe_query($pfx_sql);
				//safe_query($pfx_sql1);
				//do not remove the file as we might want to reinstall at a later date
				//file_delete("modules/".$pfx_page_name.".php");
			}
			if ($pfx_table == CONFIG_TABLE_PREFIX . 'pfx_core') {
				$pfx_messageok = "{$pfx_lang['ok_delete_page']} {$pfx_page_display_name} {$pfx_lang['page']}";
				$pfx_icon      = 'site';
				$pfx_alert     = 'yes';
			} else {
				$pfx_page_display_name = safe_field('page_display_name', 'pfx_core', "page_name='{$pfx_x}'");
				$pfx_messageok         = "{$pfx_lang['ok_delete_entry_1']}{$pfx_delete}{$pfx_lang['ok_delete_entry_2']} {$pfx_page_display_name} {$pfx_lang['page']}";
				$pfx_icon              = 'page';
				$pfx_alert             = 'no';
			}
			logme($pfx_messageok, $pfx_alert, $pfx_icon);
			if (isset($pfx_table_name)) {
				safe_optimize("{$pfx_table_name}");
				safe_repair("{$pfx_table_name}");
			}
		} else {
			if ( (isset($pfx_message)) && ($pfx_message) ) {
			} else {
				if ((isset($pfx_s)) && ($pfx_s == 'settings') && ($pfx_delete == 1) ) {
					$pfx_message = $pfx_lang['failed_protected_page'];
					$pfx_imp     = 'yes';
					logme($pfx_message, $pfx_imp, 'error');
				} else if ( (isset($pfx_delete)) && ($pfx_delete) ) {
					$pfx_message = "{$pfx_lang['failed_delete_1']}{$pfx_delete}{$pfx_lang['failed_delete_2']}";
					$pfx_imp     = 'no';
					logme($pfx_message, $pfx_imp, 'error');
				}
			}
		}
	}
}
// ------------------------------------------------------------------
// save and edit code
if ( (isset($GLOBALS['pfx_user'])) && (isset($GLOBALS['pfx_user_privs'])) && ($GLOBALS['pfx_user_privs'] >= 1) ) {
	if ( (isset($pfx_submit_edit)) && ($pfx_submit_edit) or (isset($pfx_submit_new)) && ($pfx_submit_new) ) {
		$pfx_rs = safe_row('*', 'pfx_core', "page_name = '{$pfx_x}' limit 0,1");
		if ($pfx_rs) {
			extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
			$pfx_rs = NULL;
		}
		foreach ($_POST as $pfx_key => $pfx_value) {
			if ( ($pfx_key == 'day') or ($pfx_key == 'month') or ($pfx_key == 'year') or ($pfx_key == 'time') ) {
				$pfx_value = str_replace(':', "", $pfx_value);
				if ($pfx_key == 'time') {
					if (strlen($pfx_value) == 3) {
						$pfx_value = 0 . $pfx_value;
					}
				}
				$pfx_timey[] = $pfx_value;
			}
			//echo "$pfx_key - $pfx_value <br>"; //enable to see $_post output
		}
		if ( (isset($pfx_timey)) && ($pfx_timey) ) {
			if ( (checkdate($pfx_timey[1], $pfx_timey[0], $pfx_timey[2])) ) {
				$pfx_minute   = substr($pfx_timey[3], 2, 4);
				$pfx_hour     = substr($pfx_timey[3], 0, 2);
				$pfx_unixtime = mktime($pfx_hour, $pfx_minute, 00, $pfx_timey[1], $pfx_timey[0], $pfx_timey[2]);
			} else {
				$pfx_error .= "{$pfx_lang['date_error']} ";
			}
		}
		$pfx_r2 = safe_query('show fields from ' . adjust_prefix($pfx_table_name));
		$pfx_r3 = safe_query('select * from ' . adjust_prefix($pfx_table_name) . ' WHERE 1=0');
		for ($pfx_j = 0; $pfx_j < mysql_num_rows($pfx_r2); $pfx_j++) {
			$pfx_flags  = mysql_field_flags($pfx_r3, $pfx_j);
			$pfx_af[$pfx_j] = $pfx_flags;
			if ($pfx_F = mysql_fetch_array($pfx_r2)) {
				$pfx_an[$pfx_j] = $pfx_F['Field'];
				$pfx_at[$pfx_j] = preg_replace('([()0-9]+)', "", $pfx_F['Type']);
			}
			//echo $pfx_an[$pfx_j]."-".$pfx_at[$pfx_j]."-".$pfx_af[$pfx_j]."<br>"; //enable to see field properties
		}
		for ($pfx_j = 0; $pfx_j < mysql_num_rows($pfx_r2); $pfx_j++) {
			$pfx_check = new Validator();
			if (isset($pfx_had_id)) {
			} else {
				$pfx_had_id = NULL;
			}
			if ($pfx_at[$pfx_j] == 'timestamp' && !array_key_exists($pfx_an[$pfx_j], $_POST)) {
				$pfx_check->validateNumber($pfx_unixtime, 'invalid time' . ' ');
				if ((isset($pfx_sql))) {
				} else {
					$pfx_sql = NULL;
				}
				$pfx_sql .= "" . $pfx_an[$pfx_j] . " = '" . returnSQLtimestamp($pfx_unixtime) . "',";
			} else if ((last_word($pfx_an[$pfx_j]) == 'id') && ($pfx_had_id === NULL)) {
				$pfx_had_id = 1;
				$pfx_editid = $_POST[$pfx_an[$pfx_j]];
				$pfx_idme   = $pfx_an[$pfx_j];
			} else if (($pfx_an[$pfx_j] == 'page_content') && (isset($pfx_s)) && ($pfx_s == 'settings')) {
				//skip it to protect the php in the page_content field
			} else if (($pfx_an[$pfx_j] == 'admin') && (isset($pfx_s)) && ($pfx_s == 'settings')) {
				//skip it to protect the php code in the admin field
			} else {
				$pfx_value = $_POST[$pfx_an[$pfx_j]];
				if ($pfx_an[$pfx_j] == 'title') {
					$pfx_tit = $pfx_value;
				}
				if ($pfx_at[$pfx_j] == 'varchar') {
					$pfx_value = htmlspecialchars($pfx_value, ENT_QUOTES, PFX_CHARSET);
				}
				// check for posts with duplicate title/slug and increment
				if ($pfx_an[$pfx_j] == 'post_slug') {
					if ( (isset($pfx_tit)) && ($pfx_tit) ) {
						$pfx_value = strtolower( make_slug($pfx_tit) );
						$pfx_searchforslug = safe_rows('*', $pfx_table_name, "post_slug REGEXP '[[:<:]]{$pfx_value}[[:>:]]'");
						if ($pfx_searchforslug) {
							$pfx_addslug = count($pfx_searchforslug);
							$pfx_value   = "{$pfx_value}-{$pfx_addslug}";
						}
					}
				}
				// check for pages with duplicate title/slug and increment
				if ($pfx_an[$pfx_j] == 'page_name') {
					$pfx_oldvalue = safe_field('page_name', $pfx_table_name, "page_id='{$pfx_editid}'");
					if ($pfx_value != $pfx_oldvalue) {
						$pfx_searchforpage = safe_rows('*', $pfx_table_name, "page_name REGEXP '[[:<:]]{$pfx_value}[[:>:]]'");
						if ($pfx_searchforpage) {
							$pfx_addpage = count($pfx_searchforpage);
							$pfx_value   = "{$pfx_value}-{$pfx_addpage}";
						}
					}
				/* Force the value to be lowercase and without spaces for slug */
				$pfx_value = strtolower( make_slug($pfx_value) );
				}
				// set a page_order, and navigation settings for a newly saved page
				if ($pfx_an[$pfx_j] == 'public') {
					if ($pfx_value == 'yes') {
						$pfx_itspublic = 'yes';
					}
				}
				if ($pfx_an[$pfx_j] == 'in_navigation') {
					if ($pfx_value == 'yes') {
						$pfx_innavigation = 'yes';
					}
				}
				if ($pfx_an[$pfx_j] == 'page_order') {
					if ($pfx_itspublic) {
						if ($pfx_value != 0) {
							if ($pfx_innavigation) {
								if ((isset($pfx_submit_new)) && ($pfx_submit_new)) {
									$pfx_value = count(safe_rows('*', $pfx_table_name, "public='yes' and in_navigation='yes' order by post_order asc")) + 1;
								}
							} else {
								$pfx_value = 0;
							}
						} else {
							if ((isset($pfx_innavigation)) && ($pfx_innavigation)) {
								$pfx_value = count(safe_rows('*', $pfx_table_name, "public='yes' and in_navigation='yes' order by post_order asc")) + 1;
							} else {
								$pfx_value = $pfx_value;
							}
						}
					} else {
						$pfx_value = 0;
					}
				}
				/* Validate and clean input */
				$pfx_value = str_replace('|', '&#124;', $pfx_value);
				if (get_magic_quotes_gpc()) {
					/* There's not much we can do about poorly configured servers - TURN get_magic_quotes_gpc OFF!!! IT IS DEPRECIATED NOW! */
				} else {
					$pfx_value = mysql_real_escape_string($pfx_value);
				}
				$pfx_value = mysql_real_escape_string($pfx_value);
				$pfx_nullf = explode(" ", $pfx_af[$pfx_j]);
				if ($pfx_at[$pfx_j] == 'longtext') {
					$pfx_is_php_code = strlen(stristr(utf8_decode(($pfx_value)), '<?php')) > 0;
					if ( ($pfx_is_php_code) && (isset($GLOBALS['pfx_user'])) && ($GLOBALS['pfx_user_privs'] >= 2) ) {
					} else {
						if ($pfx_is_php_code) {
							$pfx_value = ''; /* Non super users may not post php code */
						} else {
							if (get_magic_quotes_gpc()) {
								/* There's not much we can do about poorly configured servers - TURN get_magic_quotes_gpc OFF!!! IT IS DEPRECIATED NOW! */
							} else {
								/* $pfx_value = $pfx_purifier->purify($pfx_value); */ /* HTML Purifier strips urls here */ /* It was a nice idea to try to use HTML Purifier in PFX, unfortunately HTML Purifier is so rubbish it's hardly worth the effort. */
							}
						}
					}
				}
				if ($pfx_an[$pfx_j] == 'tags') {
					$pfx_value = make_tag($pfx_value);
				}
				if ($pfx_at[$pfx_j] == 'varchar') {
					sterilise(strip_tags($pfx_value));
				}
				if (($pfx_an[$pfx_j] == 'url') or ($pfx_an[$pfx_j] == 'website')) {
					if ($pfx_nullf[0] == 'not_null') {
						$pfx_check->validateURL($pfx_value, "{$pfx_lang['url_error']} ");
					} else if ($pfx_value != "") {
						$pfx_check->validateURL($pfx_value, "{$pfx_lang['url_error']} ");
					}
				}
				if ($pfx_an[$pfx_j] == 'email') {
					if ($pfx_nullf[0] == 'not_null') {
						$pfx_check->validateEmail($pfx_value, "{$pfx_lang['email_error']} ");
					} else if ($pfx_value != "") {
						$pfx_check->validateEmail($pfx_value, "{$pfx_lang['email_error']} ");
					}
				}
				if ( ($pfx_nullf[0] == 'not_null') ) {
					if ( ($pfx_value == "") or ($pfx_value == NULL) ) {
						$pfx_error .= ucwords($pfx_an[$pfx_j]) . ' ' . $pfx_lang['is_required'] . ' ';
					}
				}
				// if empty int set to 0
				if ($pfx_at[$pfx_j] == 'int')
					$pfx_value = ($pfx_value ? $pfx_value : 0);
				if (isset($pfx_sql)) {
				} else {
					$pfx_sql = NULL;
				}
				$pfx_sql .= "`{$pfx_an[$pfx_j]}` = '{$pfx_value}',";
				if ($pfx_check->foundErrors()) {
					$pfx_error .= $pfx_check->listErrors('x');
				}
			}
		}
		if (isset($pfx_sql)) {
		} else {
			$pfx_sql = NULL;
		}
		$pfx_sql = substr($pfx_sql, 0, (strlen($pfx_sql) - 1)) . "";
		//echo $pfx_sql; //view the SQL for current form save
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			$pfx_err     = explode('|', $pfx_error);
			$pfx_message = $pfx_err[0];
		} else {
			if ( (isset($pfx_submit_new)) && ($pfx_submit_new) ) {
				$pfx_ok       = safe_insert($pfx_table_name, $pfx_sql);
				$pfx_idofsave = mysql_insert_id();
				safe_optimize($pfx_table_name);
				safe_repair($pfx_table_name);
				if (!$pfx_ok) {
					$pfx_message = $pfx_lang['unknown_error'];
					logme($pfx_message, 'no', 'error');
				} else {
					if ( (isset($pfx_s)) && ($pfx_s == 'settings') && ($pfx_page_type == 'dynamic') ) {
						$pfx_sql = "`page_id` = '{$pfx_idofsave}', `posts_per_page` = '10', `rss` = 'yes'";
						safe_insert('pfx_dynamic_settings', $pfx_sql);
					}
					if ($pfx_table_name == 'pfx_core') {
						$pfx_output    = safe_field('page_display_name', 'pfx_core', "page_id='{$pfx_idofsave}'");
						$pfx_icon      = 'site';
						$pfx_messageok = "{$pfx_lang['saved_new_page']}: {$pfx_output}.";
					} else {
						$pfx_ptitle = $pfx_title;
						$pfx_output = $pfx_page_display_name;
						$pfx_icon   = 'page';
						if (isset($pfx_ptitle)) {
							$pfx_messageok = "{$pfx_lang['saved_new']}: {$pfx_ptitle} {$pfx_lang['in_the']} {$pfx_output} {$pfx_lang['page']}";
						} else {
							$pfx_messageok = "{$pfx_lang['saved_new']} (#{$pfx_idofsave}) {$pfx_lang['in_the']} {$pfx_output} {$pfx_lang['page']}";
						}
					}
					logme($pfx_messageok, 'no', $pfx_icon);
				}
			}
			if ( (isset($pfx_submit_edit)) && ($pfx_submit_edit) ) {
				$pfx_ok = safe_update("{$pfx_table_name}", "{$pfx_sql}", "`{$pfx_idme}` = '{$pfx_editid}'");
				if (!$pfx_ok) {
					$pfx_message = $pfx_lang['unknown_error'];
				} else {
					if ( (isset($pfx_s)) && ($pfx_s == 'settings') ) {
						$pfx_output = $pfx_page_display_name;
						$pfx_icon   = 'site';
						if ($pfx_output) {
							$pfx_messageok = "{$pfx_lang['saved_new_settings_for']} {$pfx_output} {$pfx_lang['page']}";
						} else {
							$pfx_output    = safe_field('page_display_name', 'pfx_core', "page_id='{$pfx_page_id}'");
							$pfx_messageok = "{$pfx_lang['saved_new_settings_for']} {$pfx_output} {$pfx_lang['page']}";
						}
						page_order_reset();
					}
					if ( (isset($pfx_s)) && ($pfx_s == 'publish') ) {
						if ( (isset($pfx_title)) && ($pfx_title) ) {
						$pfx_output = $pfx_title;
						} else {
						$pfx_output = NULL;
						}
						$pfx_icon   = 'page';
						$pfx_pname  = safe_field('page_display_name', 'pfx_core', "page_id='{$pfx_page_id}'");
						if ($pfx_m == 'static') {
							$pfx_messageok = "Saved updates to the {$pfx_pname} page.";
						} else {
							if ($pfx_output) {
								$pfx_messageok = "{$pfx_lang['save_update_entry']}: {$pfx_output} {$pfx_lang['on_the']} {$pfx_pname} {$pfx_lang['page']}";
							} else {
								$pfx_messageok = "{$pfx_lang['save_update_entry']} (#{$pfx_editid}) {$pfx_lang['on_the']} {$pfx_pname} {$pfx_lang['page']}";
							}
						}
					}
					logme($pfx_messageok, 'no', $pfx_icon);
				}
			}
		}
	}
}