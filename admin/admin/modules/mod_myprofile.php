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
 * Title: My Profile
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
if (isset($GLOBALS['pfx_user'])) {
	$pfx_scream = array();
	if ( (isset($pfx_profile_edit)) && ($pfx_profile_edit) ) {
		$pfx_table_name = 'pfx_users';
		$pfx_check      = new Validator();
		if ( (isset($pfx_realn)) && ($pfx_realn) ) {
			if ($pfx_realn == "") {
				$pfx_error .= "{$pfx_lang['profile_name_error']} ";
				$pfx_scream[] = 'realname';
			}
		} else {
			$pfx_error .= "{$pfx_lang['profile_name_error']} ";
			$pfx_scream[] = 'realname';
		}
		if ( (isset($pfx_mail)) && ($pfx_mail) ) {
			if ($pfx_check->validateEmail($pfx_mail, "{$pfx_lang['profile_email_error']} ")) {
			} else {
				$pfx_scream[] = 'email';
			}
		} else {
			$pfx_scream[] = 'email';
		}
		if ($pfx_site) {
			if ($pfx_check->validateURL($pfx_site, "{$pfx_lang['profile_web_error']} ")) {
			} else {
					$pfx_scream[] = 'website';
			}
		}
		if ($pfx_lnk_1) {
			if ($pfx_check->validateURL($pfx_lnk_1, "{$pfx_lang['profile_web_error']} ")) {
			} else {
				$pfx_scream[] = 'link_1';
			}
		}
		if ($pfx_lnk_2) {
			if ($pfx_check->validateURL($pfx_lnk_2, "{$pfx_lang['profile_web_error']} ")) {
			} else {
				$pfx_scream[] = 'link_2';
			}
		}
		if ($pfx_lnk_3) {
			if ($pfx_check->validateURL($pfx_lnk_3, "{$pfx_lang['profile_web_error']} ")) {
			} else {
				$pfx_scream[] = 'link_3';
			}
		}
		if ($pfx_check->foundErrors()) {
			$pfx_error .= $pfx_check->listErrors('x');
		}
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			$pfx_err     = explode('|', $pfx_error);
			$pfx_message = $pfx_err[0];
		} else {
			/* Clean the input */
			$pfx_realn   = sterilise($pfx_realn, TRUE);
			$pfx_mail      = sterilise($pfx_mail, TRUE);
			$pfx_biog = $pfx_purifier->purify($pfx_biog);
			$pfx_biog  = sterilise($pfx_biog);
			$pfx_biog  = mysql_real_escape_string($pfx_biog);
			$pfx_lnk_1     = sterilise($pfx_lnk_1, TRUE);
			$pfx_lnk_2     = sterilise($pfx_lnk_2, TRUE);
			$pfx_lnk_3     = sterilise($pfx_lnk_3, TRUE);
			$pfx_occ = sterilise($pfx_occ, TRUE);
			$pfx_site    = sterilise($pfx_site, TRUE);
			$pfx_st     = sterilise($pfx_st, TRUE);
			$pfx_place       = sterilise($pfx_place, TRUE);
			$pfx_cou     = sterilise($pfx_cou, TRUE);
			$pfx_coun    = sterilise($pfx_coun, TRUE);
			$pfx_pcode  = sterilise($pfx_pcode, TRUE);
			$pfx_phone  = sterilise($pfx_phone, TRUE);
			$pfx_user_id    = sterilise($pfx_user_id, TRUE);
			$pfx_sql        = "realname = '{$pfx_realn}', email = '{$pfx_mail}', biography = '{$pfx_biog}', link_1 = '{$pfx_lnk_1}', link_2 = '{$pfx_lnk_2}',  link_3 = '{$pfx_lnk_3}', occupation = '{$pfx_occ}', website = '{$pfx_site}', street = '{$pfx_st}', town = '{$pfx_place}', county = '{$pfx_cou}', country = '{$pfx_coun}', post_code = '{$pfx_pcode}', telephone = '{$pfx_phone}'";
			$pfx_ok         = safe_update('pfx_users', $pfx_sql, "user_id = '{$pfx_user_id}'");
			if ( (isset($pfx_ok)) && ($pfx_ok) ) {
				$pfx_messageok = $pfx_lang['profile_ok'];
			} else {
				if (isset($pfx_table_name)) {
					safe_optimize($pfx_table_name);
					safe_repair($pfx_table_name);
				}
				$pfx_message = $pfx_lang['unknown_error'];
			}
		}
	}
	if ( (isset($pfx_profile_pass)) && ($pfx_profile_pass) ) {
		sleep(3);
		$pfx_new_password = addslashes($pfx_new_password);
		/* Unfortunately, you could become permenatly logged out if you use a " in a password */
		$pfx_confirm_password = addslashes($pfx_confirm_password);
		/* If we do this first, then we need to do stripslashes() when it comes out, potentially breaking compatibility with upgraders */
		$pfx_r = safe_field('user_name', 'pfx_users', "user_name = '{$pfx_user_name}'and 
			pass = '" . doPass($pfx_current_pass) . "' and privs >= 0");
		if ( (isset($pfx_r)) && ($pfx_r) ) {
		} else {
			$pfx_error .= "{$pfx_lang['profile_password_invalid']} ";
			$pfx_scream[] = 'current';
		}
		if ( (isset($pfx_new_password)) && ($pfx_new_password) ) {
		} else {
			$pfx_error .= "{$pfx_lang['profile_password_missing']} ";
			$pfx_scream[] = 'new';
		}
		if (strlen($pfx_new_password) < 6) {
			$pfx_error .= "{$pfx_lang['profile_password_invalid_length']} ";
			$pfx_scream[] = 'new';
			$pfx_scream[] = 'confirm';
		}
		if ( (isset($pfx_confirm_password)) && ($pfx_confirm_password) ) {
		} else {
			$pfx_error .= "{$pfx_lang['profile_password_missing']} ";
			$pfx_scream[] = 'confirm';
		}
		if ($pfx_new_password != $pfx_confirm_password) {
			$pfx_error .= "{$pfx_lang['profile_password_match_error']} ";
			$pfx_scream[] = 'new';
			$pfx_scream[] = 'confirm';
		}
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			$pfx_err     = explode('|', $pfx_error);
			$pfx_message = $pfx_err[0];
		} else {
			$pfx_rs = safe_update('pfx_users', "pass = '" . doPass($pfx_new_password) . "'", "user_name='{$pfx_user_name}'");
			if ( (isset($pfx_rs)) && ($pfx_rs) ) {
				$pfx_email   = safe_field('email', 'pfx_users', "user_name='{$pfx_user_name}'");
				$pfx_subject = $pfx_lang['email_changepassword_subject_1'] . str_replace('http://', "", PREFS_SITE_URL) . $pfx_lang['email_changepassword_subject_2'];
				if ( (isset($pfx_subject)) && ($pfx_subject) ) {
				} else {
					$pfx_subject = 'A message from '. PREFS_SITE_NAME;
				}
				$pfx_emessage = "{$pfx_lang['email_newpassword_message']}{$pfx_new_password}";
				$pfx_user     = safe_field('user_name', 'pfx_users', "email='{$pfx_email}'");
				$pfx_headers  = 'MIME-Version: 1.0' . "\r\n";
				$pfx_headers .= 'Content-type: text/plain; charset=' . PFX_CHARSET . "\r\n";
				$pfx_headers .= 'Content-transfer-encoding: 8bit' . "\r\n";
				$pfx_headers  .= "From: postmaster@{$_SERVER['HTTP_HOST']}" . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n";
				mail($pfx_email, $pfx_subject, $pfx_emessage, $pfx_headers);
				$pfx_messageok = $pfx_lang['profile_password_ok'];
			} else {
				$pfx_message = $pfx_lang['profile_password_error'];
			}
		}
	}
	$pfx_uname = safe_field('user_name', 'pfx_users', "user_name='{$GLOBALS['pfx_user']}'");
	$pfx_rname = safe_field('realname', 'pfx_users', "user_name='{$GLOBALS['pfx_user']}'");
	$pfx_email   = safe_field('email', 'pfx_users', "user_name='{$GLOBALS['pfx_user']}'");
	$pfx_default = PREFS_SITE_URL . "files/images/no_grav.png";
	if ( (isset($pfx_email)) && ($pfx_email) ) {
		$pfx_grav_url = 'http://www.gravatar.com/avatar.php?gravatar_id=' . md5($pfx_email) . '&amp;default=' . urlencode($pfx_default) . '&amp;size=64';
	}
	echo "<h2>{$pfx_rname} (";
	if (isset($pfx_uname)) {
		echo $pfx_uname;
	}
	echo ')   </h2>';
	switch ($pfx_do) {
		case 'security':
			if (in_array('current', $pfx_scream)) {
				$pfx_current_style = 'form_highlight';
			}
			if (in_array('new', $pfx_scream)) {
				$pfx_new_style = 'form_highlight';
			}
			if (in_array('confirm', $pfx_scream)) {
				$pfx_confirm_style = 'form_highlight';
			}
			echo '<div id="grav">';
			if (isset($pfx_grav_url)) {
			echo "<a href=\"http://gravatar.com/\" target=\"_blank\"><img src=\"{$pfx_grav_url}\" alt=\"Gravatar Image\" class=\"gr photo\" /></a>";
			} else {
			echo "<a href=\"http://gravatar.com/\" target=\"_blank\"><img src=\"{$pfx_default}\" alt=\"Gravatar Image\" class=\"gr photo\" /></a>";
			}
			echo '</div>';
			echo "\n\n\t\t\t\t<div id=\"myprofile_edit\">
 					<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;do=security\" method=\"post\" id=\"form_myprofile\" class=\"form\">
 						<fieldset>
 						<legend>Change your password</legend>		
		 					<div class=\"form_row ";
			if (isset($pfx_current_style)) {
				echo $pfx_current_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"current_pass\">{$pfx_lang['form_current_password']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"password\" class=\"form_text\" name=\"current_pass\" value=\"\" size=\"40\" maxlength=\"80\" id=\"current_pass\" /></div>
							</div>			
							<div class=\"form_row ";
			if (isset($pfx_new_style)) {
				echo $pfx_new_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"new_password\">{$pfx_lang['form_new_password']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"password\" class=\"form_text\" name=\"new_password\" value=\"\" size=\"40\" maxlength=\"80\" id=\"new_password\" /></div>
							</div>
							<div class=\"form_row ";
			if (isset($pfx_confirm_style)) {
				echo $pfx_confirm_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"confirm_password\">{$pfx_lang['form_confirm_password']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"password\" class=\"form_text\" name=\"confirm_password\" value=\"\" size=\"40\" maxlength=\"80\" id=\"confirm_password\" /></div>
							</div>
							<div class=\"form_row_button\" id=\"form_button\">
								<input type=\"submit\" name=\"profile_pass\" class=\"form_submit\" value=\"" . $pfx_lang['form_button_update'] . "\" />
								<input type=\"hidden\" name=\"user_name\" value=\"{$GLOBALS['pfx_user']}\" maxlength=\"64\" />
							</div>
							<div class=\"safclear\"></div>
						</fieldset>
 					</form>
 				</div>";
			break;
		default:
			$pfx_uname = $GLOBALS['pfx_user'];
			if (isset($pfx_uname)) {
				$pfx_rs = safe_row('*', 'pfx_users', "user_name = '{$pfx_uname}' limit 0,1");
			}
			if ( (isset($pfx_rs)) && ($pfx_rs) ) {
				if ( (isset($pfx_error)) && ($pfx_error) ) {
				} else {
					extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
					$pfx_rs = NULL;
					$pfx_logunix = returnUnixtimestamp($pfx_last_access);
					$pfx_date    = date('l dS M y @ H:i', $pfx_logunix);
				}
			}
			if (in_array('realname', $pfx_scream)) {
				$pfx_realname_style = 'form_highlight';
			}
			if (in_array('email', $pfx_scream)) {
				$pfx_email_style = 'form_highlight';
			}
			if (in_array('website', $pfx_scream)) {
				$pfx_website_style = 'form_highlight';
			}
			if (in_array('link_1', $pfx_scream)) {
				$pfx_link_1_style = 'form_highlight';
			}
			if (in_array('link_2', $pfx_scream)) {
				$pfx_link_2_style = 'form_highlight';
			}
			if (in_array('link_3', $pfx_scream)) {
				$pfx_link_3_style = 'form_highlight';
			}
			echo '<div id="grav">';
			if (isset($pfx_grav_url)) {
			echo "<a href=\"http://gravatar.com/\" target=\"_blank\"><img src=\"{$pfx_grav_url}\" alt=\"Gravatar Image\" class=\"gr photo\" /></a>";
			} else {
			echo "<a href=\"http://gravatar.com/\" target=\"_blank\"><img src=\"{$pfx_default}\" alt=\"Gravatar Image\" class=\"gr photo\" /></a>";
			}
			echo '</div>';
			echo "\n\n\t\t\t\t<div id=\"myprofile_edit\">
 					<form accept-charset=\"" . PFX_CHARSET . "\" "; if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') ) { echo 'onSubmit="MirrorUpdate();" '; } echo "action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\" id=\"form_myprofile\" class=\"form\">
 						<fieldset>
 						<legend>Edit your profile</legend>		
							<div class=\"form_row ";
			if (isset($pfx_realname_style)) {
				echo $pfx_realname_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"realn\">{$pfx_lang['form_name']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"realn\"";
			if (isset($pfx_realname)) {
				echo " value=\"$pfx_realname\"";
			}
			echo " size=\"40\" maxlength=\"80\" id=\"realname\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"phone\">{$pfx_lang['form_telephone']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"phone\" value=\"{$pfx_telephone}\" size=\"40\" maxlength=\"80\" id=\"telephone\" /></div>
							</div>
							<div class=\"form_row ";
			if (isset($pfx_email_style)) {
				echo $pfx_email_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"mail\">{$pfx_lang['form_email']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"mail\" value=\"{$pfx_email}\" size=\"40\" maxlength=\"80\" id=\"email\" /></div>
							</div>
							<div class=\"form_row ";
			if (isset($pfx_website_style)) {
				echo $pfx_website_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"site\">{$pfx_lang['form_website']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_site_url']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"site\" value=\"{$pfx_website}\" size=\"40\" maxlength=\"80\" id=\"website\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"occ\">{$pfx_lang['form_occupation']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"occ\" value=\"{$pfx_occupation}\" size=\"40\" maxlength=\"80\" id=\"occupation\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"st\">{$pfx_lang['form_address_street']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"st\" value=\"{$pfx_street}\" size=\"40\" maxlength=\"80\" id=\"street\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"place\">{$pfx_lang['form_address_town']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"place\" value=\"{$pfx_town}\" size=\"40\" maxlength=\"80\" id=\"town\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"cou\">{$pfx_lang['form_address_county']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"cou\" value=\"{$pfx_county}\" size=\"40\" maxlength=\"80\" id=\"county\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"pcode\">{$pfx_lang['form_address_pcode']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"pcode\" value=\"{$pfx_post_code}\" size=\"30\" maxlength=\"80\" id=\"pcode\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"coun\">{$pfx_lang['form_address_country']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" name=\"coun\" class=\"form_text form-wide\" value=\"{$pfx_country}\" size=\"40\" maxlength=\"80\" id=\"country\" /></div>
							</div>
							<div class=\"form_row\">";
							if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') ) { 
								echo "<div class=\"form_label\"><label class=\"content-label\" for=\"biog\">{$pfx_lang['form_address_biography']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>";
							} else {
								echo "<div class=\"form_label\"><label for=\"biog\">{$pfx_lang['form_address_biography']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>";
							}
			$pfx_containsphp = editableArea($pfx_biography, 'biog');
			echo "<div class=\"form_row ";
			if (isset($pfx_link_1_style)) {
				echo $pfx_link_1_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"lnk_1\">";
			if (isset($pfx_lang['form_fl1'])) {
				echo $pfx_lang['form_fl1'];
			}
			echo " <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"lnk_1\" value=\"{$pfx_link_1}\" size=\"40\" maxlength=\"80\" id=\"link_1\" /></div>
							</div>
							<div class=\"form_row ";
			if (isset($pfx_link_2_style)) {
				echo $pfx_link_2_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"lnk_2\">";
			if (isset($pfx_lang['form_fl2'])) {
				echo $pfx_lang['form_fl2'];
			}
			echo " </label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"lnk_2\" value=\"{$pfx_link_2}\" size=\"40\" maxlength=\"80\" id=\"link_2\" /></div>
							</div>
							<div class=\"form_row ";
			if (isset($pfx_link_3_style)) {
				echo $pfx_link_3_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"lnk_3\">";
			if (isset($pfx_lang['form_fl3'])) {
				echo $pfx_lang['form_fl3'];
			}
			echo " </label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text form-wide\" name=\"lnk_3\" value=\"{$pfx_link_3}\" size=\"40\" maxlength=\"80\" id=\"link_3\" /></div>
							</div>";
			echo "		<div class=\"form_row_button\" id=\"form_button\">
								<input type=\"submit\" name=\"profile_edit\" class=\"form_submit\" value=\"{$pfx_lang['form_button_update']}\" />
								<input type=\"hidden\" name=\"user_id\" value=\"{$pfx_user_id}\" maxlength=\"64\" />
							</div>
							<div class=\"safclear\"></div>
						</fieldset>	
 					</form>
 				</div>";
			break;
	}
}