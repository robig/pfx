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
	$pfx_scream = array();
	if ((isset($pfx_settings_edit)) && ($pfx_settings_edit)) {
		$pfx_check = new Validator();
		if (!$pfx_sitename) {
			$pfx_error .= "{$pfx_lang['site_name_error']} ";
			$pfx_scream[] = 'name';
		}
		if (!$pfx_url) {
			$pfx_error .= "{$pfx_lang['site_url_error']} ";
			$pfx_scream[] = 'url';
		}
		if ($pfx_url) {
			if (!$pfx_check->validateURL($pfx_url, "{$pfx_lang['site_url_error']} ")) {
					$pfx_scream[] = 'url';
			}
		}
		if (!$pfx_site_recaptcha_private_key) {
			$pfx_error .= "{$pfx_lang['private_key_error']} ";
			$pfx_scream[] = 'private_key';
		}
		if (!$pfx_site_recaptcha_public_key) {
			$pfx_error .= "{$pfx_lang['public_key_error']} ";
			$pfx_scream[] = 'public_key';
		}
		if ($pfx_check->foundErrors()) {
			$pfx_error .= $pfx_check->listErrors('x');
		}
		if ( (isset($pfx_sitename)) ) {
			$pfx_sitename = addslashes($pfx_sitename);
			$pfx_sitename = htmlspecialchars($pfx_sitename, ENT_QUOTES, PFX_CHARSET);
		} else {
			$pfx_sitename = $pfx_lang['form_site_name'];
		}
		$pfx_table_name    = 'pfx_settings';
		$pfx_site_url_last = $pfx_url{strlen($pfx_url) - 1};
		if ($pfx_site_url_last != '/') {
			$pfx_url = $pfx_url . '/';
		}
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			$pfx_err = explode('|', $pfx_error);
		} else {
			$pfx_sitename = sterilise_txt($pfx_sitename);
			$pfx_url = sterilise_url($pfx_url);
			$pfx_keywords = str_replace( 'BREAK1', ',', sterilise_txt(str_replace(',', 'BREAK1', $pfx_keywords)) );
			$pfx_site_auth = sterilise_txt($pfx_site_auth);
			$pfx_site_cright = sterilise_txt($pfx_site_cright);
			$pfx_default_p = sterilise_url($pfx_default_p);
			$pfx_cleanurls = sterilise_txt($pfx_cleanurls);
			$pfx_site_jquery = sterilise_txt($pfx_site_jquery);
			$pfx_site_jquery_latest = sterilise_txt($pfx_site_jquery_latest);
			$pfx_site_jquery_g = sterilise_txt($pfx_site_jquery_g);
			$pfx_site_jquery_g_loc = sterilise_txt($pfx_site_jquery_g_loc);
			$pfx_site_css_xhtml = sterilise_txt($pfx_site_css_xhtml);
			$pfx_site_lightbox = sterilise_txt($pfx_site_lightbox);
			$pfx_site_gzip = sterilise_txt($pfx_site_gzip);
			$pfx_site_ie7_compat = sterilise_txt($pfx_site_ie7_compat);
			$pfx_site_captcha = sterilise_txt($pfx_site_captcha);
			$pfx_site_recaptcha_private_key = sterilise_txt($pfx_site_recaptcha_private_key);
			$pfx_site_recaptcha_public_key = sterilise_txt($pfx_site_recaptcha_public_key);
			if ( ($pfx_site_jquery == 'no') && ($pfx_site_captcha == 'yes') ) {
			    $pfx_site_jquery == 'yes'; /* jquery is required to load reCaptcha */
			}
			if ( ($pfx_site_jquery == 'no') && ($pfx_site_lightbox == 'yes') ) {
			    $pfx_site_jquery == 'yes'; /* jquery is required to load lightbox */
			}
			$pfx_ok = safe_update('pfx_settings', "site_name = '{$pfx_sitename}', 
									site_url = '{$pfx_url}', 
									site_keywords = '{$pfx_keywords}', 
									site_author = '{$pfx_site_auth}',
									site_copyright = '{$pfx_site_cright}',
									default_page = '{$pfx_default_p}',
									clean_urls = '{$pfx_cleanurls}',
									jquery = '{$pfx_site_jquery}',
									jquery_latest = '{$pfx_site_jquery_latest}',
									jquery_g_apis = '{$pfx_site_jquery_g}',
									g_jquery_loc = '{$pfx_site_jquery_g_loc}',
									valid_css_xhtml = '{$pfx_site_css_xhtml}',
									lightbox = '{$pfx_site_lightbox}',
									gzip = '{$pfx_site_gzip}',
									ie7_compat = '{$pfx_site_ie7_compat}',
									captcha = '{$pfx_site_captcha}',
									recaptcha_private_key = '{$pfx_site_recaptcha_private_key}',
									recaptcha_public_key = '{$pfx_site_recaptcha_public_key}'",
									"settings_id ='1'");
		}
		if (!$pfx_ok) {
			$pfx_message = $pfx_err[0];
			if (!$pfx_message) {
				$pfx_message = $pfx_lang['error_save_settings'];
			}
			$pfx_site_name      = PREFS_SITE_NAME;
			$pfx_site_url       = PREFS_SITE_URL;
			$pfx_site_keywords  = PREFS_SITE_KEYWORDS;
			$pfx_site_author    = PREFS_SITE_AUTHOR;
			$pfx_site_copyright = PREFS_SITE_COPYRIGHT;
			$pfx_default_page   = PREFS_DEFAULT_PAGE;
		} else {
			if (isset($pfx_table_name)) {
				safe_optimize($pfx_table_name);
				safe_repair($pfx_table_name);
			}
			$pfx_messageok = $pfx_lang['ok_save_settings'];
		}
	}
	$pfx_r = safe_row('*', 'pfx_settings', 'settings_id = 1');
	if (in_array('name', $pfx_scream)) {
		$pfx_name_style = 'form_highlight';
	}
	if (in_array('url', $pfx_scream)) {
		$pfx_url_style = 'form_highlight';
	}
	echo "<h2>{$pfx_lang['nav2_site']} {$pfx_lang['nav2_settings']}</h2>";
	echo "\n\n\t\t\t\t<div id=\"site_settings\">
 					<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\" id=\"form_settings\" class=\"form\">	
 						<fieldset>	
 						<legend>{$pfx_lang['form_legend_site_settings']}</legend>
							<div class=\"form_row ";
	if (isset($pfx_name_style)) {
		echo $pfx_name_style;
	}
	echo "\">
								<div class=\"form_label\"><label for=\"site_name\">{$pfx_lang['form_site_name']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_name']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"sitename\" value=\"";
	echo $pfx_r['site_name'];
	echo "\" size=\"40\" maxlength=\"80\" id=\"site_name\" /></div>
							</div>
							<div class=\"form_row ";
	if (isset($pfx_url_style)) {
		echo $pfx_url_style;
	}
	echo "\">
								<div class=\"form_label\"><label for=\"url\">{$pfx_lang['form_site_url']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_url']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"url\" value=\"{$pfx_r['site_url']}\" size=\"40\" maxlength=\"80\" id=\"url\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"default_p\">{$pfx_lang['form_site_homepage']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_homepage']}</span></div>";
	$pfx_all = safe_rows('*', 'pfx_core', "public='yes'");
	$pfx_num = count($pfx_all);
	$pfx_i   = 0;
	echo "\n\t\t\t\t\t\t\t\t<div class=\"form_item_drop long-select\"><select class=\"form_select\" name=\"default_p\" id=\"default\">\n";
	while ($pfx_i < $pfx_num) {
		$pfx_out                 = $pfx_all[$pfx_i];
		$pfx_module_display_name = $pfx_out['page_display_name'];
		$pfx_module_name         = $pfx_out['page_name'];
		$pfx_page_type           = $pfx_out['page_type'];
		if (($pfx_module_name == 404) or ($pfx_module_name == 'navigation') or ($pfx_module_name == 'rss')) {
			//	do nothing
		} else if ($pfx_page_type == 'plugin') {
			// do nothing again
		} else {
			if ($pfx_r['default_page'] == $pfx_module_name . '/') {
				echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"{$pfx_module_name}/\">{$pfx_module_display_name}</option>\n";
			} else {
				echo "\t\t\t\t\t\t\t\t\t<option value=\"{$pfx_module_name}/\">{$pfx_module_display_name}</option>\n";
			}
		}
		$pfx_i++;
	}
	echo "\t\t\t\t\t\t\t\t</select></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"keywords\">{$pfx_lang['form_site_keywords']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_keywords']}</span></div>
								<div class=\"form_item\"><input type=\"text\" name=\"keywords\" class=\"form_text form-wide\" value=\"{$pfx_r['site_keywords']}\" size=\"40\" maxlength=\"200\" id=\"keywords\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"site_author\">{$pfx_lang['form_site_author']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" name=\"site_auth\" class=\"form_text\" value=\"{$pfx_r['site_author']}\" size=\"40\" maxlength=\"120\" id=\"site_author\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"site_copyright\">{$pfx_lang['form_site_copyright']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" name=\"site_cright\" class=\"form_text form-wide\" value=\"{$pfx_r['site_copyright']}\" size=\"40\" maxlength=\"80\" id=\"site_copyright\" /></div>
							</div>

							<div class=\"form_row\">";
							if ($_SERVER['HTTP_MOD_REWRITE'] == 'On') {
								$pfx_url_test = $pfx_lang['form_site_url_test_yes'];
							} else {
								$pfx_url_test = $pfx_lang['form_site_url_test_no'];
							}
							echo "<div class=\"form_label\"><label for=\"cleanurls\">{$pfx_lang['form_site_curl']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_curl']} {$pfx_url_test}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['clean_urls'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"cleanurls\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['clean_urls'] == 'no' ? " checked=\"checked\"" : "") . " name=\"cleanurls\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_jquery\">{$pfx_lang['form_site_jquery']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_jquery']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['jquery'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_jquery\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['jquery'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_jquery\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_jquery_latest\">{$pfx_lang['form_site_jquery_latest']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_jquery_latest']} admin/themes/" . PREFS_SITE_THEME . "/js/jquery.js?</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['jquery_latest'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_jquery_latest\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['jquery_latest'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_jquery_latest\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_jquery_g\">{$pfx_lang['form_site_jquery_g']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_jquery_g']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['jquery_g_apis'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_jquery_g\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['jquery_g_apis'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_jquery_g\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"site_jquery_g_loc\">{$pfx_lang['form_site_jquery_g_loc']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_jquery_g_loc']}</span></div>
								<div class=\"form_item\"><input type=\"text\" name=\"site_jquery_g_loc\" class=\"form_text form-wide\" value=\"{$pfx_r['g_jquery_loc']}\" size=\"40\" maxlength=\"200\" id=\"jquery-g-loc\" /></div>
							</div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_css_xhtml\">{$pfx_lang['form_site_css_xhtml']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_css_xhtml']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['valid_css_xhtml'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_css_xhtml\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['valid_css_xhtml'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_css_xhtml\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_lightbox\">{$pfx_lang['form_site_lightbox']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_lightbox']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['lightbox'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_lightbox\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['lightbox'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_lightbox\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">";
							if ( (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) && (@extension_loaded('zlib')) ) {
								$pfx_gzip_test = $pfx_lang['form_site_gzip_test_yes'];
							} else {
								$pfx_gzip_test = $pfx_lang['form_site_gzip_test_no'];
							}
							echo "<div class=\"form_label\"><label for=\"site_gzip\">{$pfx_lang['form_site_gzip']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_gzip']} {$pfx_gzip_test}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['gzip'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_gzip\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['gzip'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_gzip\" class=\"form_radio\" value=\"no\" />
							</div></div>";

							echo "<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_ie7_compat\">{$pfx_lang['form_site_ie7_compat']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_ie7_compat']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['ie7_compat'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_ie7_compat\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['ie7_compat'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_ie7_compat\" class=\"form_radio\" value=\"no\" />
							</div></div>";

							echo "<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"site_captcha\">{$pfx_lang['form_site_captcha']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_captcha']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_r['captcha'] == 'yes' ? " checked=\"checked\"" : "") . " name=\"site_captcha\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_r['captcha'] == 'no' ? " checked=\"checked\"" : "") . " name=\"site_captcha\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"site_recaptcha_private_key\">{$pfx_lang['form_site_recaptcha_private_key']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_recaptcha_private_key']}</span></div>
								<div class=\"form_item\"><input type=\"text\" name=\"site_recaptcha_private_key\" class=\"form_text form-wide\" value=\""; if ($pfx_r['recaptcha_private_key'] != '') { echo $pfx_r['recaptcha_private_key']; } echo "\" size=\"40\" maxlength=\"200\" id=\"recaptcha-private-key\" /></div>
							</div>

							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"site_recaptcha_public_key\">{$pfx_lang['form_site_recaptcha_public_key']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_recaptcha_public_key']}</span></div>
								<div class=\"form_item\"><input type=\"text\" name=\"site_recaptcha_public_key\" class=\"form_text form-wide\" value=\""; if ($pfx_r['recaptcha_public_key'] != '') { echo $pfx_r['recaptcha_public_key']; } echo "\" size=\"40\" maxlength=\"200\" id=\"recaptcha-public-key\" /></div>
							</div>

							<div class=\"form_row_button\" id=\"form_button\">
								<input type=\"submit\" name=\"settings_edit\" class=\"form_submit\" id=\"form_addedit_submit\" value=\"{$pfx_lang['form_button_update']}\" />
							</div>

							<div class=\"safclear\"></div>
						</fieldset>
	 				</form>
	 			</div>";
}