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
 * Title: AJAX File Upload
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
$pfx_refering = NULL;
$pfx_refering = parse_url(($_SERVER['HTTP_REFERER']));
if ( ($pfx_refering['host'] == $_SERVER['HTTP_HOST']) ) {
	if ( (defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG')) ) {
		require_once '../../lib/lib_misc.php';
		exit(pfxExit());
	}
	define('DIRECT_ACCESS', 1);
	require_once '../../lib/lib_misc.php';
	/* perform basic sanity checks */
	bombShelter();
	/* check URL size */
	if (PFX_DEBUG == 'yes') {
		error_reporting(-1);
	} else {
		error_reporting(0);
	}
	require_once '../../config.php';
	require_once '../../lib/lib_crypt.php';
	/* Import crypt library */
	$crypt = new encryption_class;
	include_once '../../lib/lib_db.php';
	include_once '../../lib/lib_validate.php';
	include_once '../../lib/lib_auth.php';
	include_once '../../lib/lib_date.php';
	include_once '../../lib/lib_upload.php';
	include_once '../../lib/lib_rss.php';
	include_once '../../lib/lib_tags.php';
	include_once '../../lib/lib_logs.php';
	include_once '../../lib/lib_lang.php';
	if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 1) {
		extract($_REQUEST, EXTR_PREFIX_ALL, 'pfx');
		$_REQUEST = NULL;
		get_prefs();
		header('Content-Type: text/html; charset=' . PFX_CHARSET);
		if ($pfx_form) {
			if (first_word($pfx_form) == 'image') {
				db_dropdown('pfx_files', "", $pfx_form, "file_type = 'Image' order by file_id desc", $pfx_edit, $pfx_go);
				if (!$pfx_ie) {
					echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('{$pfx_form}'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n";
				}
			} else if (first_word($pfx_form) == 'document') {
				db_dropdown('pfx_files', "", $pfx_form, "file_type = 'Other' order by file_id desc", $pfx_edit, $pfx_go);
				if (!$pfx_ie) {
					echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('{$pfx_form}'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n";
				}
			} else if (first_word($pfx_form) == 'video') {
				db_dropdown('pfx_files', "", $pfx_form, "file_type = 'Video' order by file_id desc", $pfx_edit, $pfx_go);
				if (!$pfx_ie) {
					echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('{$pfx_form}'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n";
				}
			} else if (first_word($pfx_form) == 'audio') {
				db_dropdown('pfx_files', "", $pfx_form, "file_type = 'Audio' order by file_id desc", $pfx_edit, $pfx_go);
				if (!$pfx_ie) {
					echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('{$pfx_form}'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n";
				}
			} else {
				db_dropdown('pfx_files', "", $pfx_form, "file_id >= '0' order by file_id desc", $pfx_edit, $pfx_go);
				if (!$pfx_ie) {
					echo "\n\t\t\t\t\t\t\t\t<span class=\"more_upload\">or <a href=\"#\" onclick=\"upswitch('{$pfx_form}'); return false;\" title=\"{$pfx_lang['upload']}\">" . strtolower($pfx_lang['upload']) . "...</a></span>\n\t\t\t\t\t\t\t\t</div>\n";
				}
			}
			die();
		}
		$pfx_max_size     = 1024 * 100;
		$pfx_multi_upload = new muli_files;
		$pfx_file_name    = $_FILES['upload']['name'][0];
		$pfx_file_ext     = substr(strrchr($pfx_file_name, '.'), 1);
		$pfx_file_ext     = strtolower($pfx_file_ext);
		if (($pfx_file_ext == 'jpg') or ($pfx_file_ext == 'gif') or ($pfx_file_ext == 'png')) {
			$pfx_dir       = '../../../files/images/';
			$pfx_file_type = 'Image';
		} else if (($pfx_file_ext == 'mov') or ($pfx_file_ext == 'flv') or ($pfx_file_ext == 'avi') or ($pfx_file_ext == 'm4v') or ($pfx_file_ext == 'mp4') or ($pfx_file_ext == 'mkv') or ($pfx_file_ext == 'ogv')) {
			$pfx_dir       = '../../../files/video/';
			$pfx_file_type = 'Video';
		} else if (($pfx_file_ext == 'mp3') or ($pfx_file_ext == 'flac') or ($pfx_file_ext == 'ogg') or ($pfx_file_ext == 'wav') or ($pfx_file_ext == 'pls') or ($pfx_file_ext == 'm4a') or ($pfx_file_ext == 'xspf')) {
			$pfx_dir       = '../../../files/audio/';
			$pfx_file_type = 'Audio';
		} else {
			$pfx_dir       = '../../../files/other/';
			$pfx_file_type = 'Other';
		}
		$pfx_file_tags                       = str_replace('_', " ", $pfx_field);
		$pfx_multi_upload->pfx_upload_dir        = $pfx_dir;
		$pfx_multi_upload->pfx_message[]         = $pfx_multi_upload->extra_text(4);
		$pfx_multi_upload->pfx_do_filename_check = 'y';
		$pfx_multi_upload->pfx_tmp_names_array   = $_FILES['upload']['tmp_name'];
		$pfx_multi_upload->pfx_names_array       = $_FILES['upload']['name'];
		$pfx_multi_upload->pfx_error_array       = $_FILES['upload']['error'];
		$pfx_multi_upload->pfx_replace           = (isset($_POST['replace'])) ? $_POST['replace'] : 'n';
		$pfx_multi_upload->pfx_extensions        = $pfx_extension_list;
		$pfx_multi_upload->upload_multi_files();
		if (lastword($pfx_multi_upload->show_error_string()) == 'uploaded.') {
			$pfx_sql = "file_name = '{$pfx_file_name}', file_extension = '{$pfx_file_ext}', file_type = '{$pfx_file_type}', tags = '{$pfx_file_tags}'";
			$pfx_ok  = safe_insert('pfx_files', $pfx_sql);
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
		echo $pfx_message;
	}
	/* This file should be merged as an include or merged directly into another file instead of it being directly accessed like this. */
} else {
	exit( header('Location: ../../../') );
}