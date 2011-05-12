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
 * Title: File Manager
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
include_once 'lib/lib_upload.php';
/* We need function convertBytes here */
if ( (isset($GLOBALS['pfx_user'])) && ($GLOBALS['pfx_user_privs'] >= 1) ) {
	if ( (isset($pfx_del)) && ($pfx_del) ) {
		$pfx_deldb = safe_delete('pfx_files', "file_name='{$pfx_del}'");
		if ($pfx_deldb) {
			$pfx_file_ext = substr(strrchr($pfx_del, '.'), 1);
			$pfx_file_ext = strtolower($pfx_file_ext);
			if (($pfx_file_ext == 'jpg') or ($pfx_file_ext == 'gif') or ($pfx_file_ext == 'png')) {
				$pfx_dir = '../files/images/';
			} else if (($pfx_file_ext == 'mov') or ($pfx_file_ext == 'flv') or ($pfx_file_ext == 'avi') or ($pfx_file_ext == 'm4v') or ($pfx_file_ext == 'mp4') or ($pfx_file_ext == 'mkv') or ($pfx_file_ext == 'ogv')) {
				$pfx_dir = '../files/video/';
			} else if (($pfx_file_ext == 'mp3') or ($pfx_file_ext == 'flac') or ($pfx_file_ext == 'ogg') or ($pfx_file_ext == 'wav') or ($pfx_file_ext == 'pls') or ($pfx_file_ext == 'm4a') or ($pfx_file_ext == 'xspf')) {
				$pfx_dir = '../files/audio/';
			} else {
				$pfx_dir = '../files/other/';
			}
			if (file_exists($pfx_dir . $pfx_del)) {
				$pfx_delk = file_delete("{$pfx_dir}{$pfx_del}");
			}
			if ($pfx_delk) {
				$pfx_messageok = "{$pfx_lang['file_delete_ok']} {$pfx_del}";
				logme($pfx_messageok, 'no', 'folder');
				safe_optimize('pfx_files');
				safe_repair('pfx_files');
			} else {
				$pfx_message = "{$pfx_lang['file_delete_fail']} {$pfx_del} (DBOK)";
			}
		} else {
			$pfx_message = "{$pfx_lang['file_delete_fail']} {$pfx_del}";
		}
		/* If the file cannot be deleted, lets not show a success message */
		$pfx_file_upload_check = "{$pfx_dir}{$pfx_del}";
		if (file_exists($pfx_file_upload_check)) {
			$pfx_message = $pfx_lang['file_del_filemanager_fail'];
		}
	}
	$pfx_max_size = 1024 * 100;
	if ((isset($pfx_submit_upload)) && ($pfx_submit_upload)) {
		if ($pfx_file_tags) {
			$pfx_multi_upload = new muli_files;
			$pfx_file_name    = str_replace(" ", '-', $_FILES['upload']['name'][0]);
			$pfx_file_ext     = substr(strrchr($pfx_file_name, '.'), 1);
			$pfx_file_ext     = strtolower($pfx_file_ext);
			if (($pfx_file_ext == 'jpg') or ($pfx_file_ext == 'gif') or ($pfx_file_ext == 'png')) {
				$pfx_dir       = '../files/images/';
				$pfx_file_type = 'Image';
			} else if (($pfx_file_ext == 'mov') or ($pfx_file_ext == 'flv') or ($pfx_file_ext == 'avi') or ($pfx_file_ext == 'm4v') or ($pfx_file_ext == 'mp4') or ($pfx_file_ext == 'mkv') or ($pfx_file_ext == 'ogv')) {
				$pfx_dir       = '../files/video/';
				$pfx_file_type = "Video";
			} else if (($pfx_file_ext == 'mp3') or ($pfx_file_ext == 'flac') or ($pfx_file_ext == 'ogg') or ($pfx_file_ext == 'wav') or ($pfx_file_ext == 'pls') or ($pfx_file_ext == 'm4a') or ($pfx_file_ext == 'xspf')) {
				$pfx_dir       = '../files/audio/';
				$pfx_file_type = "Audio";
			} else {
				$pfx_dir       = '../files/other/';
				$pfx_file_type = 'Other';
			}
			$pfx_multi_upload->pfx_upload_dir        = $pfx_dir;
			$pfx_multi_upload->pfx_extensions        = $pfx_extension_list;
			$pfx_multi_upload->pfx_message[]         = $pfx_multi_upload->extra_text(4);
			$pfx_multi_upload->pfx_do_filename_check = 'y';
			$pfx_multi_upload->pfx_tmp_names_array   = $_FILES['upload']['tmp_name'];
			$pfx_multi_upload->pfx_names_array       = str_replace(" ", '-', $_FILES['upload']['name']);
			$pfx_multi_upload->pfx_error_array       = $_FILES['upload']['error'];
			$pfx_multi_upload->pfx_replace           = (isset($_POST['replace'])) ? $_POST['replace'] : 'n';
			$pfx_multi_upload->upload_multi_files();
			if (lastword($pfx_multi_upload->show_error_string()) == 'uploaded.') {
				if (!isset($_POST['replace'])) {
					$pfx_sql = "file_name = '$pfx_file_name', file_extension = '$pfx_file_ext', file_type = '$pfx_file_type', tags = '$pfx_file_tags'";
					$pfx_ok  = safe_insert('pfx_files', $pfx_sql);
				} else {
					$pfx_sql     = "file_name = '$pfx_file_name', file_extension = '$pfx_file_ext', file_type = '$pfx_file_type', tags = '$pfx_file_tags'";
					$pfx_ok      = safe_update('pfx_files', "$pfx_sql", "file_name = '$pfx_file_name'");
					// sometimes a file will be present on the server but not in the file manager, we need to check for this
					$pfx_check_2 = safe_field('file_extension', 'pfx_files', "file_name ='$pfx_file_name'");
					if (!$pfx_check_2) {
						$pfx_sql = "file_name = '$pfx_file_name', file_extension = '$pfx_file_ext', file_type = '$pfx_file_type', tags = '$pfx_file_tags'";
						$pfx_ok  = safe_insert('pfx_files', $pfx_sql);
					}
				}
				if (!$pfx_ok) {
					$pfx_message = $pfx_lang['file_upload_error'];
				} else {
					$pfx_messageok = $pfx_multi_upload->show_error_string();
					logme($pfx_messageok, 'no', 'folder');
					safe_optimize('pfx_files');
					safe_repair('pfx_files');
				}
			} else {
				$pfx_message = $pfx_multi_upload->show_error_string();
			}
		} else {
			$pfx_message = $pfx_lang['file_upload_tag_error'];
		}
		// If the folder is not writeable, we need to indicate that to the user
		$pfx_file_upload_success = "{$pfx_dir}{$pfx_file_name}";
		if (!file_exists($pfx_file_upload_success)) {
			$pfx_message = $pfx_lang['upload_filemanager_fail'];
		}
	}
	if ( (isset($pfx_tag)) && ($pfx_tag) ) {
		$pfx_tag   = squash_slug($pfx_tag);
	}
	echo "<div id=\"pfx_content\" class=\"filemanager\">
					<h2>{$pfx_lang['nav2_files']}</h2>
					<input onclick=\"switchUploader();\" id=\"upload-control\" type=\"button\" value=\"Add a file\" />
					<div class=\"admin_block\" id=\"admin_block_filemanager\">
						<form class=\"hide\" accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\" id=\"upload_form\" enctype=\"multipart/form-data\">
							<fieldset>
								<legend>{$pfx_lang['upload']}</legend>
								<div class=\"form_row\">
									<div class=\"form_label\">
										<label for=\"upload\">{$pfx_lang['form_upload_file']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label>
										<span class=\"form_help\">" . $pfx_lang['filemanager_max_upload'] . convertBytes(ini_get('upload_max_filesize')) / 1048576 . "MB.</span>
									</div>
									<div class=\"form_item_file\">
										<input type=\"file\" class=\"form_text\" name=\"upload[]\" id=\"upload\" size=\"18\" />
										<div class=\"form_label\">
											<label for=\"replace\">{$pfx_lang['form_upload_replace_files']}</label>
												<span class=\"form_help\">{$pfx_lang['form_help_upload_replace_files']}</span>
										</div>
										<div class=\"form_item_check\">
											<input type=\"checkbox\" id=\"replace\" name=\"replace\" value=\"y\" />
										</div>
									</div>
								</div>
								<div class=\"form_row\">
									<div class=\"form_label\">
										<label for=\"file_tags\">{$pfx_lang['tags']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label>
										<span class=\"form_help\">{$pfx_lang['form_help_upload_tags']}</span>
									</div>
									<div class=\"form_item\"><input type=\"text\" class=\"form_text\" id=\"file_tags\" name=\"file_tags\" size=\"18\" value=\"{$pfx_lang['form_upload_file']} \" />
									</div>
									<div class=\"form_row_button\">
										<input type=\"submit\" class=\"form_submit\" name=\"submit_upload\" value=\"{$pfx_lang['upload']}\" />
										<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pfx_max_size\" />";
	if ((isset($pfx_ck)) && ($pfx_ck)) {
		echo "<input type=\"hidden\" name=\"ckFuncNumReturn\" value=\"{$pfx_CKEditorFuncNum}\" />";
	}
	if ((isset($pfx_ck)) && ($pfx_ck)) {
		echo "<input type=\"hidden\" name=\"ck\" value=\"1\" />";
	}
	if ((isset($pfx_ckfile)) && ($pfx_ckfile)) {
		echo "<input type=\"hidden\" name=\"ckfile\" value=\"1\" />";
	}
	if ((isset($pfx_ckimage)) && ($pfx_ckimage)) {
		echo "<input type=\"hidden\" name=\"ckimage\" value=\"1\" />";
	}
	echo "									</div>
								</div>
							</fieldset>
						</form>
					</div>";
					echo "<div id=\"filemanager_table\">";
					/* Paginator code start */
					$pfx_view_number = 30; /* Total records to show per page */
					if ( (isset($pfx_page)) && ($pfx_page != 1) ) {
						$pfx_lo = ($pfx_page * $pfx_view_number - $pfx_view_number);
					} else {
						$pfx_page = 1;
						$pfx_lo = 0;
					}
					$pfx_type = '';
					$pfx_table_name = 'pfx_files';
					$pfx_order_by = 'file_id';
					$pfx_asc_desc = 'DESC';
					if ( (isset($pfx_ckimage)) && ($pfx_ckimage == 1) ) {
						$pfx_condition = "WHERE file_type = 'Image'";
					} else if ( (isset($pfx_tag)) && ($pfx_tag) ) {
						$pfx_condition = "WHERE tags REGEXP '[[:<:]]{$pfx_tag}[[:>:]]'";
					} else {
						$pfx_condition = '';
					}
					$pfx_no_edit = 'yes';
					$pfx_no_delete = 'no';
					$pfx_last_mod = 'yes';
					$pfx_exclude = array(
								'file_id',
								'file_type'
							);
					$pfx_r1 = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc}");
					if ($pfx_r1) {
						$pfx_total = mysql_num_rows($pfx_r1);
						if ($pfx_total > 0) {
							if ((isset($pfx_table_name))) {
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
							$pfx_a = new Paginator_html($pfx_page, $pfx_total);
							if ( (isset($pfx_tag)) && ($pfx_tag) ) {
							$pfx_a->set_Limit(18446744073709551615);
							} else {
							$pfx_a->set_Limit($pfx_view_number);
							}
							$pfx_a->set_Links(4);
							echo '<div class="admin_table_holder pcontent"><div id="fman">';
							$pfx_wheream = "?s={$pfx_s}";
							if (isset($pfx_finalmax) && ($pfx_finalmax)) {
							} else {
								$pfx_finalmax = NULL;
							}
							$pfx_Table = new SuperTable($pfx_r, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_wheream, $pfx_type, $pfx_s);
							$pfx_Table->SuperBody($pfx_lang, $pfx_page_display_name, $pfx_no_edit, $pfx_no_delete, $pfx_last_mod, $pfx_ck, $pfx_CKEditorFuncNum, $pfx_ckfile, $pfx_ckimage);
							$pfx_loprint = $pfx_lo + 1;
							if ( (isset($pfx_tag)) && ($pfx_tag) ) {
							echo '</div>';
							} else {
							echo "</div>\n\t\t\t\t\t\t<div id=\"admin_table_overview\">\n\t\t\t\t\t\t\t<p>{$pfx_lang['total_records']}: {$pfx_total} ({$pfx_lang['showing_from_record']} {$pfx_loprint} {$pfx_lang['to']} {$pfx_hi}) {$pfx_pages} {$pfx_lang['page(s)']}.</p>\n\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t<div id=\"admin_table_pages\">\n\t\t\t\t\t\t\t";
							echo '<p>';
							$pfx_a->previousNext($pfx_wheream);
							echo '</p></div>';
							}
							echo '</div>';
						} else {
							echo "<div class=\"admin_table_holder pcontent\"><h3>{$pfx_lang['filemanager_empty']}<h3></div>";
						}
					}
					/* Paginator code end */
	echo '</div></div>';
	echo '<div id="blocks">';
	if ( (isset($pfx_ck)) && ($pfx_ck == 1) ) {
	} else {
		admin_block_tag_cloud('pfx_files', 'file_id >= 0', $pfx_type, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
	}
	$pfx_type = 'module';
	echo "\t\t\t\t</div>\n";
}