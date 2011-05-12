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
 * Title: MySQL database backup class, version 1.0.0
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author Vagharshak Tozalakyan (vagh@armdex.com)
 * @link http://heydojo.co.cc
 * @link http://www.phpclasses.org/browse/package/2779.html
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
//------------------------------------------------------------------
define('MSB_VERSION', '1.0.0');
define('MSB_NL', "\r\n");
define('MSB_STRING', 0);
define('MSB_DOWNLOAD', 1);
define('MSB_SAVE', 2);
class MySQL_Backup {
	var $pfx_server = 'localhost';
	var $pfx_port = 3306;
	var $pfx_username = 'root';
	var $pfx_password = '';
	var $pfx_database = '';
	var $pfx_link_id = -1;
	var $pfx_connected = FALSE;
	var $pfx_tables = array();
	var $pfx_drop_tables = TRUE;
	var $pfx_struct_only = FALSE;
	var $pfx_comments = TRUE;
	var $pfx_backup_dir = '';
	var $pfx_fname_format = 'd-m-Y-H-i-s';
	var $pfx_error = FALSE;
	function Execute($pfx_task = MSB_STRING, $pfx_fname = '', $pfx_compress = FALSE) {
		if (!($pfx_sql = $this->_Retrieve())) {
			return FALSE;
		}
		if ($pfx_task == MSB_SAVE) {
			if (empty($pfx_fname)) {
				$pfx_fname = $this->pfx_backup_dir;
				$pfx_fname .= date($this->pfx_fname_format);
				$pfx_fname .= ($pfx_compress ? '.sql.gz' : '.sql');
			}
			return $this->_SaveToFile($pfx_fname, $pfx_sql, $pfx_compress);
		} elseif ($pfx_task == MSB_DOWNLOAD) {
			if (empty($pfx_fname)) {
				$pfx_fname = date($this->pfx_fname_format);
				$pfx_fname .= ($pfx_compress ? '.sql.gz' : '.sql');
			}
			return $this->_DownloadFile($pfx_fname, $pfx_sql, $pfx_compress);
		} else {
			return $pfx_sql;
		}
	}
	function _Connect() {
		$pfx_value = FALSE;
		if (!$this->pfx_connected) {
			$pfx_host          = $this->pfx_server . ':' . $this->pfx_port;
			$this->pfx_link_id = mysql_connect($pfx_host, $this->pfx_username, $this->pfx_password);
		}
		if ($this->pfx_link_id) {
			if (empty($this->pfx_database)) {
				$pfx_value = TRUE;
			} elseif ($this->pfx_link_id !== -1) {
				$pfx_value = mysql_select_db($this->pfx_database, $this->pfx_link_id);
			} else {
				$pfx_value = mysql_select_db($this->pfx_database);
			}
		}
		if (!$pfx_value) {
			$this->pfx_error = mysql_error();
		}
		return $pfx_value;
	}
	function _Query($pfx_sql) {
		if ($this->pfx_link_id !== -1) {
			$pfx_result = mysql_query($pfx_sql, $this->pfx_link_id);
		} else {
			$pfx_result = mysql_query($pfx_sql);
		}
		if (!$pfx_result) {
			$this->pfx_error = mysql_error();
		}
		return $pfx_result;
	}
	function _GetTables() {
		$pfx_value = array();
		if (!($pfx_result = $this->_Query('SHOW TABLES'))) {
			return FALSE;
		}
		while ($pfx_row = mysql_fetch_row($pfx_result)) {
			if (empty($this->pfx_tables) or in_array($pfx_row[0], $this->pfx_tables)) {
				$pfx_value[] = $pfx_row[0];
			}
		}
		if (!sizeof($pfx_value)) {
			$this->pfx_error = 'No tables found in database.';
			return FALSE;
		}
		return $pfx_value;
	}
	function _DumpTable($pfx_table) {
		$pfx_value = '';
		$this->_Query("LOCK TABLES {$pfx_table} WRITE");
		if ($this->pfx_comments) {
			$pfx_value .= '#' . MSB_NL;
			$pfx_value .= '# Table structure for table `' . $pfx_table . '`' . MSB_NL;
			$pfx_value .= '#' . MSB_NL . MSB_NL;
		}
		if ($this->pfx_drop_tables) {
			$pfx_value .= 'DROP TABLE IF EXISTS `' . $pfx_table . '`;' . MSB_NL;
		}
		if (!($pfx_result = $this->_Query('SHOW CREATE TABLE ' . $pfx_table))) {
			return FALSE;
		}
		$pfx_row = mysql_fetch_assoc($pfx_result);
		$pfx_value .= str_replace('\n', MSB_NL, $pfx_row['Create Table']) . ';';
		$pfx_value .= MSB_NL . MSB_NL;
		if (!$this->pfx_struct_only) {
			if ($this->pfx_comments) {
				$pfx_value .= '#' . MSB_NL;
				$pfx_value .= '# Dumping data for table `' . $pfx_table . '`' . MSB_NL;
				$pfx_value .= '#' . MSB_NL . MSB_NL;
			}
			$pfx_value .= $this->_GetInserts($pfx_table);
		}
		$pfx_value .= MSB_NL . MSB_NL;
		$this->_Query('UNLOCK TABLES');
		return $pfx_value;
	}
	function _GetInserts($pfx_table) {
		$pfx_value = '';
		if (!($pfx_result = $this->_Query("SELECT * FROM {$pfx_table}"))) {
			return FALSE;
		}
		while ($pfx_row = mysql_fetch_row($pfx_result)) {
			$pfx_values = '';
			foreach ($pfx_row as $pfx_data) {
				$pfx_values .= '\'' . addslashes($pfx_data) . '\', ';
			}
			$pfx_values = substr($pfx_values, 0, - 2);
			$pfx_value .= 'INSERT INTO ' . $pfx_table . ' VALUES (' . $pfx_values . ');' . MSB_NL;
		}
		return $pfx_value;
	}
	function _Retrieve() {
		$pfx_value = '';
		if (!$this->_Connect()) {
			return FALSE;
		}
		if ($this->pfx_comments) {
			$pfx_value .= '#' . MSB_NL;
			$pfx_value .= '# MySQL database dump' . MSB_NL;
			$pfx_value .= '# Created by MySQL_Backup class, ver. ' . MSB_VERSION . MSB_NL;
			$pfx_value .= '#' . MSB_NL;
			$pfx_value .= '# Host: ' . $this->pfx_server . MSB_NL;
			$pfx_value .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . MSB_NL;
			$pfx_value .= '# MySQL version: ' . mysql_get_server_info() . MSB_NL;
			$pfx_value .= '# PHP version: ' . phpversion() . MSB_NL;
			if (!empty($this->pfx_database)) {
				$pfx_value .= '#' . MSB_NL;
				$pfx_value .= '# Database: `' . $this->pfx_database . '`' . MSB_NL;
			}
			$pfx_value .= '#' . MSB_NL . MSB_NL . MSB_NL;
		}
		if (!($pfx_tables = $this->_GetTables())) {
			return FALSE;
		}
		foreach ($pfx_tables as $pfx_table) {
			if (!($pfx_table_dump = $this->_DumpTable($pfx_table))) {
				$this->pfx_error = mysql_error();
				return FALSE;
			}
			$pfx_value .= $pfx_table_dump;
		}
		return $pfx_value;
	}
	function _SaveToFile($pfx_fname, $pfx_sql, $pfx_compress) {
		if ($pfx_compress) {
			if (!($pfx_zf = gzopen($pfx_fname, 'w9'))) {
				$this->pfx_error = 'Can\'t create the output file.';
				return FALSE;
			}
			gzwrite($pfx_zf, $pfx_sql);
			gzclose($pfx_zf);
		} else {
			if (!($pfx_f = fopen($pfx_fname, 'w'))) {
				$this->pfx_error = 'Can\'t create the output file.';
				return FALSE;
			}
			fwrite($pfx_f, $pfx_sql);
			fclose($pfx_f);
		}
		return TRUE;
	}
	function _DownloadFile($pfx_fname, $pfx_sql, $pfx_compress) {
		header("Content-disposition: filename={$pfx_fname}");
		header('Content-type: application/octetstream');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo ($pfx_compress ? gzencode($pfx_sql) : $pfx_sql);
		return TRUE;
	}
}