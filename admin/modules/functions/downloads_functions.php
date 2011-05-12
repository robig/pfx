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
 * Title: downloads_functions - Common functions used in the downloads module
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

function downloads_location($pfx_location, $pfx_file) {

	if ( $pfx_location == 'Audio' ) {
		$pfx_file = PREFS_SITE_URL . "files/audio/{$pfx_file}";
	} else if ( $pfx_location == 'Image' ) {
		$pfx_file = PREFS_SITE_URL . "files/images/{$pfx_file}";
	} else if ( $pfx_location == 'Video' ) {
		$pfx_file = PREFS_SITE_URL . "files/video/{$pfx_file}";
	} else {
		$pfx_file = PREFS_SITE_URL . "files/other/{$pfx_file}";
	}
	return $pfx_file;

}


function downloads_ref_update($pfx_location, $pfx_file, $pfx_m_n, $pfx_download) {

	$pfx_checksum = hash_file( 'sha256', $pfx_file );
	safe_update("pfx_module_{$pfx_m_n}", "checksum='{$pfx_checksum}'", "{$pfx_m_n}_id='{$pfx_download}'");
	safe_update("pfx_module_{$pfx_m_n}", "download_ref='{$pfx_file}'", "{$pfx_m_n}_id='{$pfx_download}'");
	return $pfx_checksum;
}


function downloads_output($pfx_rz, $pfx_i, $pfx_downloads_lang, $pfx_m_n) {

	$pfx_out = $pfx_rz[$pfx_i];
	$pfx_published = $pfx_out['published'];
	if ($pfx_published = 'yes') {
		$pfx_download = $pfx_out['downloads_id'];
		$pfx_title = $pfx_out['title'];
		$pfx_file = $pfx_out['file'];
		$pfx_license_url = $pfx_out['license_url'];
		$pfx_license_name = $pfx_out['license_name'];
		$pfx_checksum = $pfx_out['checksum'];
		$pfx_image = $pfx_out['image'];
		$pfx_download_ref = $pfx_out['download_ref'];
		$pfx_description = $pfx_out['description'];
		$pfx_show_checksum = fetch('show_checksum', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_url = createURL($pfx_m_n, 'file', $pfx_download);
		$pfx_location = getThing($pfx_query = 'SELECT file_type FROM ' . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_file}'");
		$pfx_file = getThing($pfx_query = 'SELECT file_name FROM ' . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_file}'");
		$pfx_image = getThing($pfx_query = 'SELECT file_name FROM ' . CONFIG_TABLE_PREFIX . "pfx_files WHERE file_id='{$pfx_image}'");
		$pfx_file = downloads_location($pfx_location, $pfx_file);
		if ( url_exist($pfx_file) ) {
			if ($pfx_checksum == '') {
				$pfx_checksum = downloads_ref_update($pfx_location, $pfx_file, $pfx_m_n, $pfx_download);
			} else if ($pfx_download_ref !== $pfx_file) {
				$pfx_checksum = downloads_ref_update($pfx_location, $pfx_file, $pfx_m_n, $pfx_download);
			}
			if ($pfx_license_name == '') {
				$pfx_license_markup = '';
			} else {
				if ($pfx_license_url == '') {
					$pfx_license_markup = "<p>{$pfx_title} {$pfx_downloads_lang['license_hint']} {$pfx_license_name}</p>";
				} else {
					$pfx_license_markup = "<p>{$pfx_title} {$pfx_downloads_lang['license_hint']} <a href=\"{$pfx_license_url}\" title=\"{$pfx_license_name}\">{$pfx_license_name}</a></p>";
				}
			}
			echo '<div class="download_item">';
			if ($pfx_image == '') {
			} else {
				echo "<img class=\"{$pfx_m_n}-image\" src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_image}\" />";
			}
			echo "<div class=\"download-block\"><h4>{$pfx_title}</h4><p>{$pfx_description}</p>{$pfx_license_markup}\n";
			if ($pfx_show_checksum == 'yes') {
				echo "<p>{$pfx_downloads_lang['download_checksum']} {$pfx_checksum}</p>";
			}
			echo "</div><div class=\"link-holder\"><form action =\"{$pfx_url}\"><button class=\"download-link\" type=\"submit\">{$pfx_downloads_lang['download_hint']}</button></form></div></div>";
		}
	}

}