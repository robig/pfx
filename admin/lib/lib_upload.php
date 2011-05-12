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
 * Title: lib_upload
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
//------------------------------------------------------------------ 
/* This pfx function is used by both uploaders to inform the user that their max upload file size php setting */
/* The failing of a too large file still needs to be logged. Todo. Currently it just fails silently with no error message. */

/* This file needs language strings */
/**
 * Convert a shorthand byte value from a PHP configuration directive to an integer value
 * @param    string   $pfx_value
 * @return   int
 */
function convertBytes($pfx_value) {
	if (is_numeric($pfx_value)) {
		return $pfx_value;
	} else {
		$pfx_value_length = strlen($pfx_value);
		$pfx_qty          = substr($pfx_value, 0, $pfx_value_length - 1);
		$pfx_unit         = strtolower(substr($pfx_value, $pfx_value_length - 1));
		switch ($pfx_unit) {
			case 'k':
				$pfx_qty *= 1024;
				break;
			case 'm':
				$pfx_qty *= 1048576;
				break;
			case 'g':
				$pfx_qty *= 1073741824;
				break;
		}
		return $pfx_qty;
	}
}
// End function convertBytes
class file_upload {
	var $pfx_the_file;
	var $pfx_the_temp_file;
	var $pfx_upload_dir;
	var $pfx_replace;
	var $pfx_do_filename_check;
	var $pfx_max_length_filename = 100;
	var $pfx_extensions;
	var $pfx_ext_string;
	var $pfx_language;
	var $pfx_http_error;
	var $pfx_rename_file;
	var $pfx_file_copy;
	var $pfx_message = array();
	var $pfx_create_directory = TRUE;
	function file_upload() {
		$this->pfx_language    = 'en';
		$this->pfx_rename_file = FALSE;
		$this->pfx_ext_string  = "";
	}
	function show_error_string() {
		$pfx_msg_string = "";
		foreach ($this->pfx_message as $pfx_value) {
			$pfx_msg_string = $pfx_value . "";
		}
		return $pfx_msg_string;
	}
	function set_file_name($pfx_new_name = "") {
		if ($this->pfx_rename_file) {
			if ($this->pfx_the_file == "")
				return;
			$pfx_name = ($pfx_new_name == "") ? strtotime('now') : $pfx_new_name;
			$pfx_name = $pfx_name . $this->get_extension($this->pfx_the_file);
		} else {
			$pfx_name = $this->pfx_the_file;
		}
		return $pfx_name;
	}
	function upload($pfx_to_name = "") {
		$pfx_new_name = $this->set_file_name($pfx_to_name);
		if ($this->check_file_name($pfx_new_name)) {
			if ($this->validateExtension()) {
				if (is_uploaded_file($this->pfx_the_temp_file)) {
					$this->pfx_file_copy = $pfx_new_name;
					if ($this->move_upload($this->pfx_the_temp_file, $this->pfx_file_copy)) {
						$this->pfx_message[] = $this->error_text($this->pfx_http_error);
						if ($this->pfx_rename_file)
							$this->pfx_message[] = $this->error_text(16);
						return TRUE;
					}
				} else {
					$this->pfx_message[] = $this->error_text($this->pfx_http_error);
					return FALSE;
				}
			} else {
				$this->show_extensions();
				$this->pfx_message[] = $this->error_text(11);
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	function check_file_name($pfx_the_name) {
		if ($pfx_the_name != "") {
			if (strlen($pfx_the_name) > $this->pfx_max_length_filename) {
				$this->pfx_message[] = $this->error_text(13);
				return FALSE;
			} else {
				if ($this->pfx_do_filename_check == 'y') {
					if (preg_match("/^[^<>:\"\/\\|\?\*]*$/i", $pfx_the_name)) {
						return TRUE;
					} else {
						$this->pfx_message[] = $this->error_text(12);
						return FALSE;
					}
				} else {
					return TRUE;
				}
			}
		} else {
			$this->pfx_message[] = $this->error_text(10);
			return FALSE;
		}
	}
	function get_extension($pfx_from_file) {
		$pfx_ext = strtolower(strrchr($pfx_from_file, '.'));
		return $pfx_ext;
	}
	function validateExtension() {
		$pfx_extension = $this->get_extension($this->pfx_the_file);
		$pfx_ext_array = $this->pfx_extensions;
		if (in_array($pfx_extension, $pfx_ext_array)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function show_extensions() {
		$this->pfx_ext_string = implode(" ", $this->pfx_extensions);
	}
	function move_upload($pfx_tmp_file, $pfx_new_file) {
		umask(0);
		if ($this->existing_file($pfx_new_file)) {
			$pfx_newfile = $this->pfx_upload_dir . $pfx_new_file;
			if ($this->check_dir($this->pfx_upload_dir)) {
				if (move_uploaded_file($pfx_tmp_file, $pfx_newfile)) {
					if ($this->pfx_replace == 'y') {
						//system("chmod 0777 $pfx_newfile");
						@chmod($pfx_newfile, 0777);
					} else {
						// system("chmod 0755 $pfx_newfile");
						@chmod($pfx_newfile, 0755);
					}
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				$this->pfx_message[] = $this->error_text(14);
				return FALSE;
			}
		} else {
			$this->pfx_message[] = $this->error_text(15);
			return FALSE;
		}
	}
	function check_dir($pfx_directory) {
		if (!is_dir($pfx_directory)) {
			if ($this->pfx_create_directory) {
				umask(0);
				mkdir($pfx_directory, 0777);
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return TRUE;
		}
	}
	function existing_file($pfx_file_name) {
		if ($this->pfx_replace == 'y') {
			return TRUE;
		} else {
			if (file_exists($this->pfx_upload_dir . $pfx_file_name)) {
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}
	function get_uploaded_file_info($pfx_name) {
		$pfx_str = 'File name: ' . basename($pfx_name) . "\n";
		$pfx_str .= "File size: " . filesize($pfx_name) . " bytes\n";
		if (function_exists('mime_content_type')) {
			$pfx_str .= 'Mime type: ' . mime_content_type($pfx_name) . "\n";
		}
		if ($pfx_img_dim = getimagesize($pfx_name)) {
			$pfx_str .= 'Image dimensions: x = ' . $pfx_img_dim[0] . 'px, y = ' . $pfx_img_dim[1] . "px\n";
		}
		return $pfx_str;
	}
	function del_temp_file($pfx_file) {
		$pfx_delete = @unlink($pfx_file);
		clearstatcache();
		if (@file_exists($pfx_file)) {
			$pfx_filesys = str_replace('/', '\\', $pfx_file);
			$pfx_delete  = @system("del {$pfx_filesys}");
			clearstatcache();
			if (@file_exists($pfx_file)) {
				$pfx_delete = @chmod($pfx_file, 0775);
				$pfx_delete = @unlink($pfx_file);
				$pfx_delete = @system("del {$pfx_filesys}");
			}
		}
	}
	// some error (HTTP)reporting, change the messages or remove options if you like. need some better handling of this with language file
	function error_text($pfx_err_num) {
		switch ($this->pfx_language) {
			default:
				// start http errors
				$pfx_error[0]  = "" . $this->pfx_the_file . ' was successfully uploaded.';
				$pfx_error[1]  = 'The uploaded file exceeds the max. upload filesize directive in the server configuration.';
				$pfx_error[2]  = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.';
				$pfx_error[3]  = 'The uploaded file was only partially uploaded. Try uploading the file again.';
				$pfx_error[4]  = 'No file was uploaded.';
				// end  http errors
				$pfx_error[10] = 'Please select a file for upload.';
				$pfx_error[11] = 'Only files with the following extensions are allowed: ' . $this->pfx_ext_string . "";
				$pfx_error[12] = 'The filename contains invalid characters. Use only alphanumerical chars and separate parts of the name (if needed) with an underscore. A valid filename ends with one dot followed by the extension.';
				$pfx_error[13] = 'The filename exceeds the maximum length of ' . $this->pfx_max_length_filename . ' characters.';
				$pfx_error[14] = 'The upload directory does not exist';
				$pfx_error[15] = 'A file with that name already exist.';
				$pfx_error[16] = 'The uploaded file was renamed to ' . $this->pfx_file_copy . '.';
		}
		return $pfx_error[$pfx_err_num];
	}
}
class muli_files extends file_upload {
	var $pfx_number_of_files = 0;
	var $pfx_names_array;
	var $pfx_tmp_names_array;
	var $pfx_error_array;
	var $pfx_wrong_extensions = 0;
	var $pfx_bad_filenames = 0;
	function extra_text($pfx_msg_num) {
		switch ($this->pfx_language) {
			default:
				$pfx_extra_msg[1] = 'Error for: ' . $this->pfx_the_file . "";
				$pfx_extra_msg[2] = 'You have tried to upload ' . $this->pfx_wrong_extensions . ' files with a bad extension, the following extensions are allowed: ' . $this->pfx_ext_string . "";
				$pfx_extra_msg[3] = 'Select a file for upload.';
				$pfx_extra_msg[4] = 'Select the file(s) for upload.';
				$pfx_extra_msg[5] = 'You have tried to upload ' . $this->pfx_bad_filenames . ' files with invalid characters inside the filename.';
		}
		return $pfx_extra_msg[$pfx_msg_num];
	}
	function count_files() {
		foreach ($this->pfx_names_array as $pfx_test) {
			if ($pfx_test != "") {
				$this->pfx_number_of_files++;
			}
		}
		if ($this->pfx_number_of_files > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function upload_multi_files() {
		$this->pfx_message = "";
		if ($this->count_files()) {
			foreach ($this->pfx_names_array as $pfx_key => $pfx_value) {
				if ($pfx_value != "") {
					$this->pfx_the_file = $pfx_value;
					$pfx_new_name       = $this->set_file_name();
					if ($this->check_file_name($pfx_new_name)) {
						if ($this->validateExtension()) {
							$this->pfx_file_copy     = $pfx_new_name;
							$this->pfx_the_temp_file = $this->pfx_tmp_names_array[$pfx_key];
							if (is_uploaded_file($this->pfx_the_temp_file)) {
								if ($this->move_upload($this->pfx_the_temp_file, $this->pfx_file_copy)) {
									$this->pfx_message[] = $this->error_text($this->pfx_error_array[$pfx_key]);
									if ($this->pfx_rename_file)
										$this->pfx_message[] = $this->error_text(16);
									sleep(1);
								}
							} else {
								$this->pfx_message[] = $this->extra_text(1);
								$this->pfx_message[] = $this->error_text($this->pfx_error_array[$pfx_key]);
							}
						} else {
							$this->pfx_wrong_extensions++;
						}
					} else {
						$this->pfx_bad_filenames++;
					}
				}
			}
			if ($this->pfx_bad_filenames > 0)
				$this->pfx_message[] = $this->extra_text(5);
			if ($this->pfx_wrong_extensions > 0) {
				$this->show_extensions();
				$this->pfx_message[] = $this->extra_text(2);
			}
		} else {
			$this->pfx_message[] = $this->extra_text(3);
		}
	}
}
$pfx_extension_list        = array(
				'.png',
				'.jpg',
				'.gif',
				'.zip',
				'.mp3',
				'.pdf',
				'.exe',
				'.rar',
				'.swf',
				'.vcf',
				'.css',
				'.dmg',
				'.php',
				'.doc',
				'.xls',
				'.xml',
				'.eps',
				'.rtf',
				'.iso',
				'.psd',
				'.txt',
				'.ppt',
				'.mov',
				'.flv',
				'.avi',
				'.m4v',
				'.mp4',
				'.gz',
				'.bz2',
				'.tar',
				'.7z',
				'.svg',
				'.svgz',
				'.lzma',
				'.sig',
				'.sign',
				'.js',
				'.rb',
				'.ttf',
				'.html',
				'.phtml',
				'.flac',
				'.ogg',
				'.wav',
				'.mkv',
				'.pls',
				'.m4a',
				'.xspf',
				'.kml',
				'.kmz',
				'.ogv'
			);