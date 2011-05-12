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
 * Title: Site Settings
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
if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 2) {
include_once 'lib/lib_tz.php';
	if ( (isset($pfx_settings_edit)) && ($pfx_settings_edit) ) {
		$pfx_table_name = 'pfx_settings';
		$pfx_rte = sterilise_txt($pfx_rte);
		$pfx_editor_image = sterilise_txt($pfx_editor_image);
		$pfx_editor_enter = sterilise_txt($pfx_editor_enter);
		$pfx_logs = sterilise_txt($pfx_logs);
		$pfx_sysmess = sterilise($pfx_sysmess);
		$pfx_sysmess  = mysql_real_escape_string($pfx_sysmess);
		$pfx_time_zone = str_replace( 'BREAK1', '_', sterilise($pfx_time_zone) );
		$pfx_dateformat = sterilise_txt($pfx_dateformat);
		$pfx_langu = sterilise_txt($pfx_langu);
		$pfx_charset = sterilise($pfx_charset);
		$pfx_bot_log = sterilise_txt($pfx_bot_log);
		if ( (isset($pfx_charset)) && ($pfx_charset) && ($pfx_charset == PREFS_CHARSET) ) {
		} else {
			$pfx_tables = safe_query('SHOW TABLES');
			if ($pfx_charset == 'KOI8-R') {
				define('DB_COLLATE', 'koi8r_general_ci');
			} else if ($pfx_charset == 'BIG5') {
				define('DB_COLLATE', 'big5_chinese_ci');
			} else if ($pfx_charset == 'GB2312') {
				define('DB_COLLATE', 'gb2312_chinese_ci');
			} else if ($pfx_charset == 'SJIS') {
				define('DB_COLLATE', 'sjis_japanese_ci');
			} else if ($pfx_charset == 'cp866') {
				define('DB_COLLATE', 'cp866_general_ci');
			} else if ($pfx_charset == 'cp1251') {
				define('DB_COLLATE', 'cp1251_general_ci');
			} else {
				define('DB_COLLATE', 'utf8_unicode_ci');
			}
			while ($pfx_row = mysql_fetch_array($pfx_tables)) {
				foreach ($pfx_row as $pfx_key => $pfx_table) {
					$pfx_query = safe_query("ALTER TABLE {$pfx_table} CONVERT TO CHARACTER SET " . str_replace('-', '', strtolower($pfx_charset)) . ' COLLATE ' . DB_COLLATE);
					if ($pfx_query) {
					} else {
						$pfx_error .= "<span>{$pfx_key} =&gt; {$pfx_table} CHARACTER SET CONVERSION FAILED</span><br />";
					}
				}
			}
			if ( (isset($pfx_error)) && ($pfx_error) ) { /* Try to undo any changes if a failure occured */
				$pfx_charset = PREFS_CHARSET;
				$pfx_error = FALSE;
				while ($pfx_row = mysql_fetch_array($pfx_tables)) {
					foreach ($pfx_row as $pfx_key => $pfx_table) {
						$pfx_query = safe_query("ALTER TABLE {$pfx_table} CONVERT TO CHARACTER SET " . str_replace('-', '', strtolower($pfx_charset)) . ' COLLATE ' . DB_COLLATE);
						if ($pfx_query) {
						} else {
							$pfx_error .= "<span>{$pfx_key} =&gt; {$pfx_table} CHARACTER SET CONVERSION FAILED</span><br />";
						}
					}
				}
			} else {
				safe_query('ALTER DATABASE ' . CONFIG_DB . " DEFAULT CHARACTER SET " . str_replace('-', '', strtolower($pfx_charset)) . " COLLATE " . DB_COLLATE);
			}
		}
		$pfx_ok = safe_update('pfx_settings', "rich_text_editor = '{$pfx_rte}', 
								logs_expire = '{$pfx_logs}', 
								system_message = '{$pfx_sysmess}',
								timezone = '{$pfx_time_zone}',
								date_format = '{$pfx_dateformat}',
								language = '{$pfx_langu}',
								editor_image_class = '{$pfx_editor_image}',
								editor_enter_mode = '{$pfx_editor_enter}',
								charset = '{$pfx_charset}',
								log_bots = '{$pfx_bot_log}'",
								"settings_id ='1'");
		if ( (isset($pfx_ok)) && ($pfx_ok) ) {
			if (isset($pfx_table_name)) {
				safe_optimize($pfx_table_name);
				safe_repair($pfx_table_name);
			}
			$pfx_messageok = $pfx_lang['ok_save_settings'];
		} else {
			$pfx_message = $pfx_lang['error_save_settings'];
		}
	}
	$pfx_r = safe_row('*', 'pfx_settings', 'settings_id = 1');
	echo "<h2>PFX {$pfx_lang['nav2_settings']}</h2>";
	echo "\n\n\t\t\t\t<div id=\"pfx_settings\">";
	if (strnatcmp(phpversion(), '5.2.14') <= 0) {
		echo "<label class=\"error\">{$pfx_lang['installer_php_version_warn1']} " . phpversion() . " {$pfx_lang['installer_php_version_warn2']}</label>";
	} else {
		echo "<label class=\"notice\">{$pfx_lang['admin_php_ver']} " . phpversion() . "</label>";
	}
	if (get_magic_quotes_gpc()) {
		echo "<label class=\"error\">{$pfx_lang['magic_quotes_suck']}</label>";
	}

 					echo "<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\" id=\"form_settings\" class=\"form\">	
 						<fieldset>	
 						<legend>{$pfx_lang['form_legend_pfx_settings']}</legend>
 							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"langu\">{$pfx_lang['form_pfx_language']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_language']}</span></div>
								<div class=\"form_item_drop\"><select class=\"form_select\" name=\"langu\" id=\"langu\">";
	if ($pfx_r['language'] == 'en-gb') {
		echo "<option selected=\"selected\" value=\"en-gb\">English (GB)</option>";
	} else {
		echo "<option value=\"en-gb\">English (GB)</option>";
	}
	/* English (GB) */
	echo "</select></div>
							</div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"timezone\">{$pfx_lang['form_pfx_timezone']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_timezone']}</span></div>
									<div class=\"form_item_drop\">
								<select class=\"form_select\" name=\"time_zone\" id=\"timezone\">\n"; ?>
							<option selected="selected" value="<?php
		if ( isset($pfx_r['timezone']) ) { echo $pfx_r['timezone']; } else { echo 'Europe/London'; }
?>"><?php
		if ( isset($pfx_r['timezone']) ) { echo $pfx_r['timezone']; } else { echo 'Europe/London'; }
?></option>
							<?php
		foreach ($pfx_zonelist as $pfx_tzselect) {
			// Output all the timezones
			Echo "<option value=\"" . str_replace('_', 'BREAK1', $pfx_tzselect) . "\">{$pfx_tzselect}</option>";
		}
		echo "\t\t\t\t\t\t\t\t</select>
									</div>
								</div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"dateformat\">{$pfx_lang['form_pfx_date']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_date']}</span></div>
								<div class=\"form_item_drop long-select\">
								<select class=\"form_select\" name=\"dateformat\" id=\"dateformat\">\n";
	$pfx_dayname    = '%A';
	$pfx_dayshort   = '%a';
	$pfx_daynum     = is_numeric(strftime('%e')) ? '%e' : '%d';
	$pfx_daynumlead = '%d';
	$pfx_daynumord  = is_numeric(substr(trim(strftime('%Oe')), 0, 1)) ? '%Oe' : $pfx_daynum;
	$pfx_monthname  = '%B';
	$pfx_monthshort = '%b';
	$pfx_monthnum   = '%m';
	$pfx_year       = '%Y';
	$pfx_yearshort  = '%y';
	$pfx_time24     = '%H:%M';
	$pfx_time12     = strftime('%p') ? '%I:%M %p' : $pfx_time24;
	$pfx_date       = strftime('%x') ? '%x' : '%Y-%m-%d';
	$pfx_formats    = array(
		"{$pfx_monthshort} {$pfx_daynumord}, {$pfx_time12}",
		"{$pfx_daynum}.{$pfx_monthnum}.{$pfx_yearshort}",
		"{$pfx_daynumord} {$pfx_monthname}, {$pfx_time12}",
		"{$pfx_yearshort}.{$pfx_monthnum}.{$pfx_daynumlead}, {$pfx_time12}",
		"{$pfx_dayshort} {$pfx_monthshort} {$pfx_daynumord}, {$pfx_time12}",
		"{$pfx_dayname} {$pfx_monthname} {$pfx_daynumord}, {$pfx_year}",
		"{$pfx_dayname} {$pfx_monthname} {$pfx_daynumord}, {$pfx_year} @ {$pfx_time24}",
		"{$pfx_monthshort} {$pfx_daynumord}",
		"{$pfx_daynumord} {$pfx_monthname} {$pfx_yearshort}",
		"{$pfx_daynumord} {$pfx_monthnum} {$pfx_year} - {$pfx_time24}",
		"{$pfx_daynumord} {$pfx_monthname} {$pfx_year}",
		"{$pfx_daynumord} {$pfx_monthname} {$pfx_year}, {$pfx_time24}",
		"{$pfx_daynumord}. {$pfx_monthname} {$pfx_year}",
		"{$pfx_daynumord}. {$pfx_monthname} {$pfx_year}, {$pfx_time24}",
		"{$pfx_year}-{$pfx_monthnum}-{$pfx_daynumlead}",
		"{$pfx_year}-{$pfx_daynumlead}-{$pfx_monthnum}",
		"{$pfx_date} {$pfx_time12}",
		"{$pfx_date}",
		"{$pfx_time24}",
		"{$pfx_time12}",
		"{$pfx_year}-{$pfx_monthnum}-{$pfx_daynumlead} {$pfx_time24}"
	);
	$pfx_vals       = array();
	foreach ($pfx_formats as $pfx_f) {
		if ($pfx_d = safe_strftime($pfx_lang, $pfx_f, time())) {
			$pfx_vals[$pfx_f] = $pfx_d;
			if ($pfx_f == $pfx_r['date_format']) {
				echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"{$pfx_f}\">{$pfx_d}</option>\n";
			} else {
				echo "\t\t\t\t\t\t\t\t\t<option value=\"{$pfx_f}\">{$pfx_d}</option>\n";
			}
		}
	}
	echo "\t\t\t\t\t\t\t\t</select>
								</div>
							</div>

 							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"charset\">{$pfx_lang['form_pfx_charset']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_charset']}</span></div>
								<div class=\"form_item_drop\"><select class=\"form_select\" name=\"charset\" id=\"charset\">";
/* We won't bother with the latin charsets here because the mysql people have screwed that up. MySQL does not appear to know what ISO-8859-15 is and they also think that latin1 is cp1252. It's not.
http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html
http://php.virginmedia.com/manual/en/function.html-entity-decode.php
Saying that though, asian charsets might only be important here. UTF-8 is the only _supported_ charset.
We MUST only use php supported charsets here. MySQL don't know what they are doing with charsets and php charset support is well known as dreadful.
*/
		$pfx_charset_list = array(
			'UTF-8',
			'KOI8-R',
			'BIG5',
			'GB2312',
			'SJIS',
			'cp866',
			'cp1251'
		);
/* Could maybe use database table conversion code to deal with changing this setting correctly */
		foreach ($pfx_charset_list as $pfx_char) {
			if ($pfx_r['charset'] == $pfx_char){
				echo "<option selected=\"selected\" value=\"{$pfx_char}\">{$pfx_char}</option>";
			} else {
				echo "<option value=\"{$pfx_char}\">{$pfx_char}</option>";
			}
		}

	echo "</select></div>
							</div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"rte\">{$pfx_lang['form_pfx_rte']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_rte']}</span></div>
								<div class=\"form_item_radio\">";
	echo "On<input type=\"radio\"" . ($pfx_r['rich_text_editor'] == 1 ? " checked=\"checked\"" : "") . " name=\"rte\" class=\"form_radio\" value=\"1\" />";
	echo "Off<input type=\"radio\"" . ($pfx_r['rich_text_editor'] == 0 ? " checked=\"checked\"" : "") . " name=\"rte\" class=\"form_radio\" value=\"0\" />";
	echo "</div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"editor_image_class\">{$pfx_lang['form_pfx_rte_img_class']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_rte_img_class']}</span></div>
								<div class=\"form_item\"><input type=\"text\" name=\"editor_image\" class=\"form_text\" value=\"{$pfx_r['editor_image_class']}\" size=\"40\" maxlength=\"80\" id=\"editor_image_class\" /></div>
							</div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"editor_enter_mode\">{$pfx_lang['form_pfx_rte_enter_mode']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_rte_enter_mode']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['editor_enter_mode'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"editor_enter\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['editor_enter_mode'] == 'no' ? " checked=\"checked\"" : "") . " name=\"editor_enter\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"logs\">{$pfx_lang['form_pfx_logs']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_logs']}</span></div>
								<div class=\"form_item_drop\"><select class=\"form_select\" name=\"logs\" id=\"logs\">";
	if ($pfx_r['logs_expire'] == 5) {
		echo '<option selected="selected" value="5">5</option>';
	} else {
		echo '<option value="5">5</option>';
	}
	if ($pfx_r['logs_expire'] == 10) {
		echo '<option selected="selected" value="10">10</option>';
	} else {
		echo '<option value="10">10</option>';
	}
	if ($pfx_r['logs_expire'] == 15) {
		echo '<option selected="selected" value="15">15</option>';
	} else {
		echo '<option value="15">15</option>';
	}
	if ($pfx_r['logs_expire'] == 30) {
		echo '<option selected="selected" value="30">30</option>';
	} else {
		echo '<option value="30">30</option>';
	}
	echo "</select></div>
							</div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"bot_log\">{$pfx_lang['form_pfx_log_bots']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_log_bots']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['log_bots'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"bot_log\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['log_bots'] == 'no' ? " checked=\"checked\"" : "") . " name=\"bot_log\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"sysmess\">{$pfx_lang['form_pfx_sysmess']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_pfx_sysmess']}</span></div>";
								$pfx_containsphp = editableArea($pfx_r['system_message'], 'sysmess');
							echo "<div class=\"form_row_button\" id=\"form_button\">
								<input type=\"submit\" name=\"settings_edit\" class=\"form_submit\" id=\"form_addedit_submit\" value=\"{$pfx_lang['form_button_update']}\" />
							</div>
							<div class=\"safclear\"></div>
						</fieldset>
	 				</form>
	 			</div>";
}