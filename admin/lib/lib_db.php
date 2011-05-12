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
 * Title: lib_db - Class to interface with MySQL DB
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author Dean Allen
 * @link http://heydojo.co.cc
 * @link http://textpattern.com
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
//------------------------------------------------------------------
class DB {
	function getTzdiff() {
		extract(getdate(), EXTR_PREFIX_ALL, 'pfx');
		$pfx_serveroffset = gmmktime(0, 0, 0, $pfx_mon, $pfx_mday, $pfx_year) - mktime(0, 0, 0, $pfx_mon, $pfx_mday, $pfx_year);
		return $pfx_serveroffset / 3600;
	}
	function DB() {
		if (defined('CONFIG_DB')) {
			$pfx_crypt = new encryption_class;
			if (defined('CONFIG_INPASS')) {
			} else {
				define( 'CONFIG_INPASS', $pfx_crypt->decrypt(CONFIG_RANDOM, CONFIG_PASS) );
			}
			$this->host = CONFIG_HOST;
			$this->db   = CONFIG_DB;
			$this->user = CONFIG_USER;
			$this->pass = CONFIG_INPASS;
			$this->link = mysql_connect($this->host, $this->user, $this->pass, TRUE);
			if ($this->link) {
				/* Connect to the database */
				mysql_select_db($this->db) or die( db_down() );
				$_GLOBALS['connected'] = TRUE;
				/* Set the character set for database connection */
				mysql_set_charset('PFX_DB_CHARSET', $this->link);
				$pfx_diff = $this->getTzdiff();
				if ($pfx_diff >= 0) {
					$pfx_diff = "+{$pfx_diff}";
				}
				mysql_query("set time_zone = '{$pfx_diff}:00'");
			} else {
				$_GLOBALS['connected'] = FALSE;
				db_down();
			}
		} else {
			$_GLOBALS['connected'] = FALSE;
			db_down();
		}
	}
}
//------------------------------------------------------------------
/* Adjust the table prefix */
//------------------------------------------------------------------
function adjust_prefix($pfx_table) {
	if (defined('CONFIG_TABLE_PREFIX')) {
		if (stripos($pfx_table, CONFIG_TABLE_PREFIX) === 0) {
			return $pfx_table;
		} else {
			return CONFIG_TABLE_PREFIX . $pfx_table;
		}
	} else {
		return FALSE;
	}
}
//------------------------------------------------------------------
function safe_query($pfx_q, $pfx_debug = FALSE, $pfx_unbuf = FALSE) {
	$pfx_DB = new DB;
	$pfx_method = ($pfx_unbuf) ? 'mysql_unbuffered_query' : 'mysql_query';
	if ( ($pfx_q == '') or ($pfx_q == NULL) or ($pfx_q === FALSE) ) {
		return FALSE;
	}
	if ( (isset($pfx_q)) && ($pfx_q) ) {
		$pfx_result = $pfx_method($pfx_q, $pfx_DB->link);
	}
	if ( (isset($pfx_result)) && ($pfx_result) && (is_resource($pfx_result)) or (isset($pfx_result)) && ($pfx_result == TRUE) ) {
		return $pfx_result;
	} else {
	    if (PFX_DEBUG == 'yes') {
		error_log("MySQL Query: {$pfx_q} - MySQL Result : {$pfx_result} MySQL Error : " . mysql_error(), 0);
	    } else {
		return FALSE;
	    }
	}
}
//------------------------------------------------------------------
function safe_delete($pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "delete from {$pfx_table} where {$pfx_where}";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
//------------------------------------------------------------------
function safe_update($pfx_table, $pfx_set, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "update {$pfx_table} set {$pfx_set} where {$pfx_where}";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
//------------------------------------------------------------------
function safe_insert($pfx_table, $pfx_set, $pfx_debug = '') {
	$pfx_DB = new DB;
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "insert into {$pfx_table} set {$pfx_set}";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		$pfx_id = mysql_insert_id($pfx_DB->link);
		return ($pfx_id === 0 ? TRUE : $pfx_id);
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_alter($pfx_table, $pfx_alter, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "alter table {$pfx_table} {$pfx_alter}";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		return TRUE;
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_optimize($pfx_table, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "optimize table {$pfx_table}";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		return TRUE;
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_repair($pfx_table, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "repair table {$pfx_table}";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		return TRUE;
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_field($pfx_thing, $pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "select {$pfx_thing} from {$pfx_table} where {$pfx_where}";
	$pfx_r     = safe_query($pfx_q, $pfx_debug);
	if (mysql_num_rows($pfx_r) > 0) {
		return mysql_result($pfx_r, 0);
	}
	return FALSE;
}
//------------------------------------------------------------------
function getRows($pfx_query, $pfx_debug = '') {
	if ($pfx_r = safe_query($pfx_query, $pfx_debug)) {
		if (mysql_num_rows($pfx_r) > 0) {
			while ($pfx_a = mysql_fetch_assoc($pfx_r))
				$pfx_out[] = $pfx_a;
			return $pfx_out;
		}
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_column($pfx_thing, $pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "select {$pfx_thing} from {$pfx_table} where {$pfx_where}";
	$pfx_rs    = getRows($pfx_q, $pfx_debug);
	if ($pfx_rs) {
		foreach ($pfx_rs as $pfx_a) {
			$pfx_v       = array_shift($pfx_a);
			$pfx_out[$pfx_v] = $pfx_v;
		}
		return $pfx_out;
	}
	return array();
}
//------------------------------------------------------------------
function getRow($pfx_query, $pfx_debug = '') {
	if ($pfx_r = safe_query($pfx_query, $pfx_debug)) {
		return (mysql_num_rows($pfx_r) > 0) ? mysql_fetch_assoc($pfx_r) : FALSE;
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_row($pfx_things, $pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "select {$pfx_things} from {$pfx_table} where {$pfx_where}";
	$pfx_rs    = getRow($pfx_q, $pfx_debug);
	if ($pfx_rs) {
		return $pfx_rs;
	}
	return array();
}
//------------------------------------------------------------------
function safe_rows($pfx_things, $pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "select {$pfx_things} from {$pfx_table} where {$pfx_where}";
	$pfx_rs    = getRows($pfx_q, $pfx_debug);
	if ($pfx_rs) {
		return $pfx_rs;
	}
	return array();
}
//------------------------------------------------------------------
function startRows($pfx_query, $pfx_debug = '') {
	return safe_query($pfx_query, $pfx_debug);
}
//------------------------------------------------------------------
function safe_rows_start($pfx_things, $pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "select {$pfx_things} from {$pfx_table} where {$pfx_where}";
	return startRows($pfx_q, $pfx_debug);
}
//------------------------------------------------------------------
function getThing($pfx_query, $pfx_debug = '') {
	if ($pfx_r = safe_query($pfx_query, $pfx_debug)) {
		return (mysql_num_rows($pfx_r) != 0) ? mysql_result($pfx_r, 0) : '';
	}
	return FALSE;
}
//------------------------------------------------------------------
function safe_count($pfx_table, $pfx_where, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	return getThing("select count(*) from {$pfx_table} where {$pfx_where}", $pfx_debug);
}
//------------------------------------------------------------------
function fetch($pfx_col, $pfx_table, $pfx_key, $pfx_val, $pfx_debug = '') {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_q     = "select {$pfx_col} from {$pfx_table} where `{$pfx_key}` = '{$pfx_val}' limit 1";
	if ($pfx_r = safe_query($pfx_q, $pfx_debug)) {
		return (mysql_num_rows($pfx_r) > 0) ? mysql_result($pfx_r, 0) : '';
	}
	return FALSE;
}
//------------------------------------------------------------------
function nextRow($pfx_r) {
	return mysql_fetch_assoc($pfx_r);
}
//------------------------------------------------------------------
function getThings($pfx_query, $pfx_debug = '')
// return values of one column from multiple rows in an num indexed array
	{
	$pfx_rs = getRows($pfx_query, $pfx_debug);
	if ($pfx_rs) {
		foreach ($pfx_rs as $pfx_a)
			$pfx_out[] = array_shift($pfx_a);
		return $pfx_out;
	}
	return array();
}
//------------------------------------------------------------------
function get_prefs() {
	if ( defined('PREFS_SITE_URL') ) {
		return TRUE;
	} else {
		$pfx_r = safe_row('*', 'pfx_settings', 'settings_id = 1');
		if ($pfx_r) {
			define('PREFS_SITE_NAME', $pfx_r['site_name']);
			define('PREFS_SITE_KEYWORDS', $pfx_r['site_keywords']);
			$pfx_site_url_last = $pfx_r['site_url']{strlen($pfx_r['site_url']) - 1};
			if ($pfx_site_url_last != '/') {
				$pfx_r['site_url'] = "{$pfx_r['site_url']}/";
			}
			define('PREFS_SITE_URL', $pfx_r['site_url']);
			define('PREFS_SITE_THEME', $pfx_r['site_theme']);
			define('PREFS_SITE_COPYRIGHT', $pfx_r['site_copyright']);
			define('PREFS_SITE_AUTHOR', $pfx_r['site_author']);
			define('PREFS_DEFAULT_PAGE', $pfx_r['default_page']);
			define('PREFS_CLEAN_URLS', $pfx_r['clean_urls']);
			define('PREFS_JQUERY', $pfx_r['jquery']);
			define('PREFS_JQUERY_LATEST', $pfx_r['jquery_latest']);
			define('PREFS_JQUERY_G_APIS', $pfx_r['jquery_g_apis']);
			define('PREFS_G_JQUERY_LOC', $pfx_r['g_jquery_loc']);
			define('PREFS_VALID_CSS_XHTML', $pfx_r['valid_css_xhtml']);
			define('PREFS_LIGHTBOX', $pfx_r['lightbox']);
			define('PREFS_GZIP', $pfx_r['gzip']);
			define('PREFS_IE7_COMPAT', $pfx_r['ie7_compat']);
			define('PREFS_CAPTCHA', $pfx_r['captcha']);
			define('PREFS_VERSION', $pfx_r['version']);
			define('PREFS_LANGUAGE', $pfx_r['language']);
			define('PREFS_TIMEZONE', $pfx_r['timezone']);
			define('PREFS_CHARSET', $pfx_r['charset']);
			define('PREFS_DATE_FORMAT', $pfx_r['date_format']);
			define('PREFS_LOGS_EXPIRE', $pfx_r['logs_expire']);
			define('PREFS_RICH_TEXT_EDITOR', $pfx_r['rich_text_editor']);
			define('PREFS_EDITOR_ENTER_MODE', $pfx_r['editor_enter_mode']);
			define('PREFS_EDITOR_IMAGE_CLASS', $pfx_r['editor_image_class']);
			define('PREFS_SYSTEM_MESSAGE', $pfx_r['system_message']);
			define('PREFS_BB2_INSTALLED', $pfx_r['bb2_installed']);
			define('PREFS_LAST_BACKUP', $pfx_r['last_backup']);
			define('PREFS_BACKUP_INTERVAL', $pfx_r['backup_interval']);
			define('PREFS_RECAPTCHA_PRIVATE_KEY', $pfx_r['recaptcha_private_key']);
			define('PREFS_RECAPTCHA_PUBLIC_KEY', $pfx_r['recaptcha_public_key']);
			define('PREFS_LOG_BOTS', $pfx_r['log_bots']);
		}
		/* Set the charset */
		if (defined('PREFS_CHARSET')) {
			ini_set('default_charset', PREFS_CHARSET);
			define('PFX_CHARSET', PREFS_CHARSET);
			define('PFX_DB_CHARSET', str_replace('-', '', strtolower(PREFS_CHARSET)) );
		} else if (defined('CONFIG_CHARSET')) {
			ini_set('default_charset', CONFIG_CHARSET);
			define('PFX_CHARSET', CONFIG_CHARSET);
			define('PFX_DB_CHARSET', str_replace('-', '', strtolower(CONFIG_CHARSET)) );
		} else {
			ini_set('default_charset', 'UTF-8');
			define('PFX_CHARSET', 'UTF-8');
			define('PFX_DB_CHARSET', 'utf8');
		}
		/* Set the collation */
		if (PFX_CHARSET == 'KOI8-R') {
			define('PFX_DB_COLLATE', 'koi8r_general_ci');
		} else if (PFX_CHARSET == 'BIG5') {
			define('PFX_DB_COLLATE', 'big5_chinese_ci');
		} else if (PFX_CHARSET == 'GB2312') {
			define('PFX_DB_COLLATE', 'gb2312_chinese_ci');
		} else if (PFX_CHARSET == 'SJIS') {
			define('PFX_DB_COLLATE', 'sjis_japanese_ci');
		} else if (PFX_CHARSET == 'cp866') {
			define('PFX_DB_COLLATE', 'cp866_general_ci');
		} else if (PFX_CHARSET == 'cp1251') {
			define('PFX_DB_COLLATE', 'cp1251_general_ci');
		} else {
			define('PFX_DB_COLLATE', 'utf8_unicode_ci');
		}
		return TRUE;
	}
}
//------------------------------------------------------------------
// Creates a drop down menu box from a db
function db_dropdown($pfx_table, $pfx_current, $pfx_name, $pfx_condition, $pfx_edit = FALSE, $pfx_go = FALSE) {
	$pfx_table = adjust_prefix($pfx_table);
	$pfx_rs    = safe_query("select * from {$pfx_table} where {$pfx_condition}");
	$pfx_num   = mysql_num_rows($pfx_rs);
	$pfx_i     = 0;
	echo "\t\t\t\t\t\t\t\t<select class=\"form_select inline-upload\" name=\"{$pfx_name}\" id=\"{$pfx_name}\">\n";
	if ( (!$pfx_current) && (isset($pfx_go)) && ($pfx_go == 'new') ) {
		echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"FALSE\">None</option>\n";
	} else if ( ($pfx_current === NULL) && (isset($pfx_edit)) && ($pfx_edit) ) {
		echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"FALSE\">None</option>\n";
	} else if ( (isset($pfx_edit)) && ($pfx_edit) ) {
		echo "\t\t\t\t\t\t\t\t\t<option value=\"FALSE\">None</option>\n";
	}
	while ($pfx_i < $pfx_num) {
		$pfx_F = mysql_fetch_array($pfx_rs);
		for ($pfx_j = 0; $pfx_j < mysql_num_fields($pfx_rs); $pfx_j++) {
			if (last_word(mysql_field_name($pfx_rs, $pfx_j)) == 'id') {
				$pfx_id = simplify($pfx_F[$pfx_j]);
			} else {
				$pfx_fieldname = $pfx_F[3];
			}
		}
		if ($pfx_current == $pfx_id) {
			echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"{$pfx_id}\">{$pfx_fieldname}</option>\n";
		} else {
			echo "\t\t\t\t\t\t\t\t\t<option value=\"{$pfx_id}\">{$pfx_fieldname}</option>\n";
		}
		$pfx_i++;
	}
	echo "\t\t\t\t\t\t\t\t</select>";
	mysql_free_result( mysql_query("select * from {$pfx_table} where {$pfx_condition}") );
}
//------------------------------------------------------------------
function table_exists($pfx_table_name) {
	if (isset($pfx_table_name)) {
		$pfx_table_name = adjust_prefix($pfx_table_name);
		$pfx_rs         = safe_query("select * from {$pfx_table_name} WHERE 1=0");
	}
	if ((isset($pfx_rs)) && ($pfx_rs)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
//------------------------------------------------------------------
function getSqlVersion() {
	$pfx_output = getThing('SELECT VERSION()');
	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $pfx_output, $pfx_sql_version);
	if (isset($pfx_sql_version[0])) {
		return $pfx_sql_version[0];
	} else {
		return FALSE;
	}
}
function editableArea($pfx_content_to_edit, $pfx_textarea_name) {
	$pfx_containsphp = strlen(stristr(utf8_decode(($pfx_content_to_edit)), '<?php')) > 0;
	if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') ) {
		if ($pfx_containsphp) {
			if ($GLOBALS['pfx_user_privs'] >= 2) {
				echo "<div class=\"editor-controls\"><input onclick=\"SwitchToolBar('{$pfx_textarea_name}-editor');\" class=\"editor-control\" type=\"button\" value=\"Toggle Toolbar\" /><input onclick=\"CloseEditor('{$pfx_textarea_name}-editor');\" class=\"editor-control\" type=\"button\" value=\"Close Editor\" /></div>";
				echo "\t\t\t\t\t\t\t\t<div class=\"form_item_textarea_ckeditor\">\n\t\t\t\t\t\t\t\t\t\t<textarea name=\"$pfx_textarea_name\" id=\"{$pfx_textarea_name}-editor\" cols=\"50\" class=\"ck-textarea-php\" rows=\"10\">" . htmlspecialchars(stripslashes($pfx_content_to_edit), ENT_QUOTES, PFX_CHARSET) . "</textarea>\n\t\t\t\t\t\t\t\t\t</div>";
			} else {
				echo "\t\t\t\t\t\t\t\t<div class=\"form_item_textarea_ckeditor\">\n\t\t\t\t\t\t\t\t\t\t<textarea readonly=\"readonly\" name=\"$pfx_textarea_name\" id=\"{$pfx_textarea_name}-editor\" cols=\"50\" class=\"form_item_textarea-php hide\" rows=\"10\">" . htmlspecialchars(stripslashes($pfx_content_to_edit), ENT_QUOTES, PFX_CHARSET) . "</textarea>\n\t\t\t\t\t\t\t\t\t</div>";
			}
		} else {
			echo "<div class=\"editor-controls\"><input onclick=\"SwitchToolBar('{$pfx_textarea_name}-editor');\" class=\"editor-control\" type=\"button\" value=\"Toggle Toolbar\" /><input onclick=\"CloseEditor('{$pfx_textarea_name}-editor');\" class=\"editor-control\" type=\"button\" value=\"Close Editor\" /></div>";
			echo "\t\t\t\t\t\t\t\t<div class=\"form_item_textarea_ckeditor\">\n\t\t\t\t\t\t\t\t\t\t<textarea name=\"$pfx_textarea_name\" id=\"{$pfx_textarea_name}-editor\" cols=\"50\" class=\"ck-textarea\" rows=\"10\">" . htmlspecialchars(stripslashes($pfx_content_to_edit), ENT_QUOTES, PFX_CHARSET) . "</textarea>\n\t\t\t\t\t\t\t\t\t</div>";
		}
	} else if ($pfx_containsphp) {
		if ($GLOBALS['pfx_user_privs'] >= 2) {
			echo "\t\t\t\t\t\t\t\t<div class=\"form_item_textarea\">\n\t\t\t\t\t\t\t\t<textarea name=\"{$pfx_textarea_name}\" id=\"{$pfx_textarea_name}-editor\" class=\"form_item_textarea-codemirror\">" . htmlspecialchars(stripslashes($pfx_content_to_edit), ENT_QUOTES, PFX_CHARSET) . "</textarea>\n\t\t\t\t\t\t\t\t</div>";
		} else {
			echo "\t\t\t\t\t\t\t\t<div class=\"form_item_textarea\">\n\t\t\t\t\t\t\t\t<textarea readonly=\"readonly\" name=\"{$pfx_textarea_name}\" class=\"form_item_textarea-php hide\">" . htmlspecialchars(stripslashes($pfx_content_to_edit), ENT_QUOTES, PFX_CHARSET) . "</textarea>\n\t\t\t\t\t\t\t\t</div>";
		}
	} else {
		echo "\t\t\t\t\t\t\t\t<div class=\"form_item_textarea\">\n\t\t\t\t\t\t\t\t<textarea name=\"{$pfx_textarea_name}\" class=\"form_item_textarea_no_ckeditor\">" . htmlspecialchars(stripslashes($pfx_content_to_edit), ENT_QUOTES, PFX_CHARSET) . "</textarea>\n\t\t\t\t\t\t\t\t</div>";
	}
	return $pfx_containsphp; /* TRUE if the editable area contains php code */
}
//------------------------------------------------------------------
function db_down() {
if ( (file_exists('admin/config.php')) && (filesize('admin/config.php') < 10) ) {
	/* check for config */
	if (file_exists('install/index.php')) {
		exit( header('Location: install/') );
	}
}
if ( (file_exists('config.php')) && (filesize('config.php') < 10) ) {
	/* check for config */
	if (file_exists('../install/index.php')) {
		exit( header('Location: ../install/') );
	}
}
if (defined('PFX_CHARSET')) {
	$pfx_db_charset = PFX_CHARSET;
} else {
	$pfx_db_charset = 'utf-8';
}
header('Status: 503 Service Unavailable'); /* 503 status might discourage search engines from indexing or caching the error message */
		return <<<eod
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset={$pfx_db_charset}" />
	<title>PFX (heydojo.co.cc) - Unable to connect to database</title>
	<style type="text/css">
		body{font-family:Arial,'Lucida Grande',Verdana,Sans-Serif;color:#333}
		a, a:visited{text-decoration:none;color:#0497d3}
		a:hover{color:#191919;text-decoration:none}
		.helper{position:relative;top:60px;border:5px solid#e1e1e1;clear:left;padding:15px 30px;margin:0 auto;background-color:#F0F0F0;width:500px;line-height:15pt}
	</style>
</head>
<body>
<div class="helper">
	<h3>Database Unavailable</h3><p><a href="http://heydojo.co.cc" alt="Get PFX!">PFX</a> has not been able to display the website your are visiting because a database connection could not be established. Please try to visit the site again in a few moments.</p>
</div>
</body>
</html>
eod;
exit();
}