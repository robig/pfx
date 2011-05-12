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
 * Title: Users
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
	if ((isset($pfx_uname)) && (isset($pfx_uname))) {
		$pfx_uname = sterilise_txt($pfx_uname);
	}
	if ((isset($pfx_realname)) && (isset($pfx_realname))) {
		$pfx_realname = sterilise_txt($pfx_realname);
	}
	if ((isset($pfx_email)) && (isset($pfx_email))) {
		$pfx_email = sterilise_txt($pfx_email);
	}
	if ((isset($pfx_password)) && (isset($pfx_password))) {
		$pfx_password = sterilise($pfx_password);
	}
	if ((isset($pfx_nonce)) && (isset($pfx_nonce))) {
		$pfx_nonce = sterilise_txt($pfx_nonce);
	}
	if ((isset($pfx_privilege)) && (isset($pfx_privilege))) {
		$pfx_privilege = sterilise($pfx_privilege);
	}
	if ((isset($pfx_is_contact)) && (isset($pfx_is_contact))) {
		$pfx_is_contact = sterilise_txt($pfx_is_contact);
	}
	if ((isset($pfx_rte_user)) && (isset($pfx_rte_user))) {
		$pfx_rte_user = sterilise_txt($pfx_rte_user);
	}
	$pfx_scream = array();
	if ( (isset($pfx_del)) && ($pfx_del) ) {
		$pfx_del = sterilise($pfx_del);
		$pfx_cuser = $GLOBALS['pfx_user'];
		$pfx_rs    = safe_row('*', 'pfx_users', "user_id = '{$pfx_del}' limit 0,1");
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = NULL;
		if ($pfx_privs != '3') {
			$pfx_delete = safe_delete('pfx_users', "user_id='{$pfx_del}'");
		}
		if ((isset($pfx_delete)) && (isset($pfx_realname))) {
			$pfx_emessage = $pfx_lang['email_account_close_message_1'] . PREFS_SITE_URL . $pfx_lang['email_account_close_message_2'];
			$pfx_subject  = $pfx_lang['email_account_close_subject_1'] . PREFS_SITE_URL . $pfx_lang['email_account_close_subject_2'];
			if (!isset($pfx_subject)) {
				$pfx_subject = NULL;
			}
			if (isset($pfx_email)) {
				$pfx_headers  = 'MIME-Version: 1.0' . "\r\n";
				$pfx_headers .= 'Content-type: text/plain; charset=' . PFX_CHARSET . "\r\n";
				$pfx_headers .= 'Content-transfer-encoding: 8bit' . "\r\n";
				$pfx_headers  .= "From: postmaster@{$_SERVER['HTTP_HOST']}" . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n";
				mail($pfx_email, $pfx_subject, $pfx_emessage, $pfx_headers);
			}
			$pfx_messageok = "{$pfx_lang['user']} {$pfx_realname} {$pfx_lang['user_delete_ok']}";
			logme($pfx_messageok, 'no', 'user');
			safe_optimize('pfx_users');
			safe_repair('pfx_users');
		} else {
			$pfx_message = "{$pfx_lang['user_delete_error']} {$pfx_realname}";
			logme($pfx_message, 'no', 'user');
		}
	}
	if ( (isset($pfx_user_edit)) && ($pfx_user_edit) ) {
		$pfx_user_edit = sterilise($pfx_user_edit);
		$pfx_table_name = 'pfx_users';
		$pfx_check      = new Validator();
		if ( (isset($pfx_uname)) && ($pfx_uname) ) {
			if ($pfx_uname == "") {
				$pfx_error .= "{$pfx_lang['user_name_missing']} ";
				$pfx_scream[] = 'uname';
			} else {
				$pfx_uname = str_replace(" ", "", preg_replace('/\s\s+/', ' ', trim($pfx_uname)));
			}
		} else {
			$pfx_error .= "{$pfx_lang['user_name_missing']} ";
			$pfx_scream[] = 'uname';
		}
		if ( (isset($pfx_realname)) && ($pfx_realname) ) {
			if ($pfx_realname == "") {
				$pfx_error .= "{$pfx_lang['user_realname_missing']} ";
				$pfx_scream[] = 'realname';
			}
		} else {
			$pfx_error .= "{$pfx_lang['user_realname_missing']} ";
			$pfx_scream[] = 'realname';
		}
		if ( (isset($pfx_email)) && ($pfx_email) ) {
			if ($pfx_check->validateEmail($pfx_email, "{$pfx_lang['user_email_error']} ")) {
			} else {
				$pfx_scream[] = 'email';
			}
		} else {
			$pfx_scream[] = 'email';
		}
		if ($pfx_check->foundErrors()) {
			$pfx_error .= $pfx_check->listErrors('x');
		}
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			$pfx_edit = $pfx_user_id;
			if (isset($pfx_uname)) {
				$pfx_user_name = $pfx_uname;
			}
			$pfx_err     = explode("|", $pfx_error);
			$pfx_message = $pfx_err[0];
		} else {
			$pfx_sql = "user_name = '{$pfx_uname}', realname = '{$pfx_realname}', email = '{$pfx_email}', privs = '{$pfx_privilege}', is_contact = '{$pfx_is_contact}', rte_user = '{$pfx_rte_user}'";
			$pfx_ok  = safe_update('pfx_users', "{$pfx_sql}", "user_id = '{$pfx_user_id}'");
			if (!$pfx_ok) {
				$pfx_message = $pfx_lang['unknown_error'];
			} else {
				$pfx_messageok = "{$pfx_lang['user_update_ok']} {$pfx_realname}.";
				logme($pfx_messageok, 'no', 'user');
			}
		}
	}
	if ( (isset($pfx_user_new)) && ($pfx_user_new) ) {
		$pfx_user_new = sterilise($pfx_user_new);
		$pfx_table_name = 'pfx_users';
		$pfx_check      = new Validator();
		if ( (!isset($pfx_uname)) or ($pfx_uname == "") ) {
			$pfx_error .= "{$pfx_lang['user_name_missing']} ";
			$pfx_scream[] = 'uname';
		}
		if (isset($pfx_uname)) {
			$pfx_uname = str_replace( " ", "", preg_replace('/\s\s+/', ' ', trim($pfx_uname)) );
		}
		if ( (isset($pfx_realname)) && ($pfx_realname) ) {
			if ($pfx_realname == "") {
				$pfx_error .= "{$pfx_lang['user_realname_missing']} ";
				$pfx_scream[] = 'realname';
			}
		} else {
			$pfx_error .= "{$pfx_lang['user_realname_missing']} ";
			$pfx_scream[] = 'realname';
		}
		if ( (isset($pfx_email)) && ($pfx_email) ) {
			if ($pfx_check->validateEmail($pfx_email, "{$pfx_lang['user_email_error']} ")) {
			} else {
				$pfx_scream[] = 'email';
			}
		} else {
			$pfx_scream[] = 'email';
		}
		if ($pfx_check->foundErrors()) {
			$pfx_error .= $pfx_check->listErrors('x');
		}
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			$pfx_do      = 'newuser';
			$pfx_err     = explode('|', $pfx_error);
			$pfx_message = $pfx_err[0];
		} else {
			$pfx_password = generate_password(6);
			$pfx_nonce    = hash('sha256', uniqid( rand(), TRUE) );
			$pfx_sql      = "user_name = '{$pfx_uname}', realname = '{$pfx_realname}', email = '{$pfx_email}', pass = '" . doPass($pfx_password) . "', nonce = '{$pfx_nonce}', privs = '{$pfx_privilege}', link_1 = 'http://heydojo.co.cc', link_2 = 'http://www.google.co.uk', link_3 = 'http://www.slashdot.org', biography='', is_contact = '{$pfx_is_contact}', rte_user = '{$pfx_rte_user}'";
			if (isset($pfx_table_name)) {
				$pfx_ok = safe_insert($pfx_table_name, $pfx_sql);
			}
			if ( (isset($pfx_ok)) && ($pfx_ok) ) {
				// needs to be added to language file
				$pfx_emessage = '
			
You have been invited to help maintain the website ' . PREFS_SITE_URL . ". Your account information is:

username: {$pfx_uname}
password: {$pfx_password}

visit: " . PREFS_SITE_URL . 'admin to login.';
				$pfx_subject  = $pfx_lang['email_account_new_subject_1'] . str_replace('http://', "", PREFS_SITE_URL) . $pfx_lang['email_account_new_subject_2'];
				if (!isset($pfx_subject)) {
				} else {
					$pfx_subject = NULL;
				}
				$pfx_headers  = 'MIME-Version: 1.0' . "\r\n";
				$pfx_headers .= 'Content-type: text/plain; charset=' . PFX_CHARSET . "\r\n";
				$pfx_headers .= 'Content-transfer-encoding: 8bit' . "\r\n";
				$pfx_headers  .= "From: postmaster@{$_SERVER['HTTP_HOST']}" . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n";
				mail($pfx_email, $pfx_subject, $pfx_emessage, $pfx_headers);
				$pfx_messageok = "{$pfx_lang['user_new_ok']} {$pfx_realname}  :::  [ {$pfx_lang['form_username']} : {$pfx_uname} ]  :::  [ {$pfx_lang['form_password']} : {$pfx_password} ]";
				$pfx_logok = "{$pfx_lang['user_new_ok']} {$pfx_realname}";
				logme($pfx_logok, 'no', 'user');
			} else {
				$pfx_message = $pfx_lang['user_duplicate'];
				$pfx_do      = 'newuser';
			}
		}
	}
	if ( (isset($pfx_edit)) && ($pfx_edit) ) {
		if ( (isset($pfx_user_edit)) && ($pfx_user_edit) ) {
		} else {
			$pfx_edit = sterilise($pfx_edit);
			$pfx_rs = safe_row('*', 'pfx_users', "user_id = '{$pfx_edit}' limit 0,1");
			if ($pfx_rs) {
				extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
				$pfx_rs = NULL;
			}
		}
		if ( ($pfx_privs == 3) && ($GLOBALS['pfx_user_privs'] != 3) ) {
			// editing of super user is not allowed unless you are super user
		} else {
			if (in_array('email', $pfx_scream)) {
				$pfx_email_style = 'form_highlight';
			}
			if (in_array('uname', $pfx_scream)) {
				$pfx_uname_style = 'form_highlight';
			}
			if (in_array('realname', $pfx_scream)) {
				$pfx_realname_style = 'form_highlight';
			}
			echo "<h2>{$pfx_lang['edit_user']} ({$pfx_user_name})</h2>";
			echo "\n\n\t\t\t\t<div id=\"users_newedit\">
 					<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\" class=\"form\">
 						<fieldset>
 						<legend>{$pfx_lang['form_legend_user_settings']}</legend>
							<div class=\"form_row ";
			if (isset($pfx_realname_style)) {
				echo $pfx_realname_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"realname\">{$pfx_lang['form_user_realname']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_user_realname']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"realname\"";
			if (isset($pfx_realname)) {
				echo " value=\"{$pfx_realname}\"";
			}
			echo " size=\"50\" maxlength=\"80\" id=\"realname\" /></div>
							</div>
		 					<div class=\"form_row ";
			if (isset($pfx_uname_style)) {
				echo $pfx_uname_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"uname\">{$pfx_lang['form_user_username']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_user_username']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"uname\" value=\"{$pfx_user_name}\" size=\"50\" maxlength=\"80\" id=\"uname\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"privilege\">{$pfx_lang['form_user_permissions']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_user_permissions']}</span></div>
								<div class=\"form_item_drop\"><select class=\"form_select\" name=\"privilege\" id=\"privilege\">";
			if ($pfx_privs == 3) {
				echo "<option selected=\"selected\" value=\"3\">Super User</option>";
			} else {
				if ($pfx_privs == 2) {
					echo "<option selected=\"selected\" value=\"2\">Administrator</option>";
				} else {
					echo "<option value=\"2\">Administrator</option>";
				}
				if ($pfx_privs == 1) {
					echo "<option selected=\"selected\" value=\"1\">Client</option>";
				} else {
					echo "<option value=\"1\">Client</option>";
				}
				if ($pfx_privs == 0) {
					echo "<option selected=\"selected\" value=\"0\">User</option>";
				} else {
					echo "<option value=\"0\">User</option>";
				}
			}
			echo "</select></div></div>

							<div class=\"form_row ";
			if (isset($pfx_email_style)) {
				echo $pfx_email_style;
			}
			echo "\">
								<div class=\"form_label\"><label for=\"email\">Email <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"email\"";
			if (isset($pfx_email)) {
				echo " value=\"{$pfx_email}\"";
			}
			echo " size=\"50\" maxlength=\"80\" id=\"email\" /></div>
							</div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"is_contact\">{$pfx_lang['form_is_contact']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_is_contact']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_is_contact == 'yes' ? " checked=\"checked\"" : "") . " name=\"is_contact\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_is_contact == 'no' ? " checked=\"checked\"" : "") . " name=\"is_contact\" class=\"form_radio\" value=\"no\" />
							</div></div>

						<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"rte_user\">{$pfx_lang['form_rte_user']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_rte_user']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_rte_user == 'yes' ? " checked=\"checked\"" : "") . " name=\"rte_user\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_rte_user == 'no' ? " checked=\"checked\"" : "") . " name=\"rte_user\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row_button\" id=\"form_button\">
								<input type=\"submit\" name=\"user_edit\" class=\"form_submit\" value=\"{$pfx_lang['form_button_update']}\" />
								<input type=\"hidden\" class=\"form_text\" name=\"user_id\" value=\"{$pfx_user_id}\" maxlength=\"64\" />
							</div>
							<div class=\"form_row_button\">
								<span class=\"form_button_cancel\">
									<form action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\">
										<input type=\"submit\" title=\"{$pfx_lang['form_button_cancel']}\" value=\"{$pfx_lang['form_button_cancel']}\" />
									</form>
								</span>
							</div>
							<div class=\"safclear\"></div>
						</fieldset>
 					</form>
 				</div>\n";
		}
	} else if (isset($pfx_do) && $pfx_do == 'newuser') {
		if (in_array('email', $pfx_scream)) {
			$pfx_email_style = 'form_highlight';
		}
		if (in_array('uname', $pfx_scream)) {
			$pfx_uname_style = 'form_highlight';
		}
		if (in_array('realname', $pfx_scream)) {
			$pfx_realname_style = 'form_highlight';
		}
		echo "<h2>{$pfx_lang['create_user']}</h2>
 				<p>{$pfx_lang['create_user_info']}</p>";
		echo "\n\n\t\t\t<div id=\"users_newedit\">
 					<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x={$pfx_x}\" method=\"post\" class=\"form\">
 						<fieldset class=\"auto-name\">
 						<legend>{$pfx_lang['form_legend_user_settings']}</legend>
							<div class=\"form_row ";
		if (isset($pfx_realname_style)) {
			echo $pfx_realname_style;
		}
		echo "\">
								<div class=\"form_label\"><label for=\"realname\">{$pfx_lang['form_user_realname']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_user_realname']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"realname\"";
		if (isset($pfx_realname)) {
			echo " value=\"{$pfx_realname}\"";
		}
		echo " size=\"50\" maxlength=\"80\" id=\"realname\" /></div>
							</div>
		 					<div class=\"form_row ";
		if (isset($pfx_uname_style)) {
			echo $pfx_uname_style;
		}
		echo "\">
								<div class=\"form_label\"><label for=\"uname\">{$pfx_lang['form_user_username']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_user_username']}</span></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"uname\"";
		if (isset($pfx_uname)) {
			echo " value=\"{$pfx_uname}\"";
		}
		echo " size=\"50\" maxlength=\"80\" id=\"uname\"  /></div>
							</div>
							<div class=\"form_row ";
		if (isset($pfx_email_style)) {
			echo $pfx_email_style;
		}
		echo "\">
								<div class=\"form_label\"><label for=\"email\">Email <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"email\"";
		if (isset($pfx_email)) {
			echo " value=\"{$pfx_email}\"";
		}
		echo " size=\"50\" maxlength=\"80\" id=\"email\" /></div>
							</div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"is_contact\">{$pfx_lang['form_is_contact']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_is_contact']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\" name=\"is_contact\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\" checked=\"checked\" name=\"is_contact\" class=\"form_radio\" value=\"no\" />
							</div></div>

							<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"rte_user\">{$pfx_lang['form_rte_user']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_rte_user']}</span></div>
							<div class=\"form_item_radio\">
								Yes<input type=\"radio\"" . ($pfx_privilege == '2' ? " checked=\"checked\"" : "") . " name=\"rte_user\" class=\"form_radio\" value=\"yes\" />
								No<input type=\"radio\"" . ($pfx_privilege != '2' ? "" : " checked=\"checked\"") . " name=\"rte_user\" class=\"form_radio\" value=\"no\" />
							</div></div>
								<input type=\"hidden\" name=\"privilege\" value=\"{$pfx_privilege}\" />
							<div class=\"form_row_button\" id=\"form_button\">
								<input type=\"submit\" name=\"user_new\" class=\"form_submit\" value=\"{$pfx_lang['form_button_save']}\" />
							</div>
							<div class=\"form_row_button\">
								<span class=\"form_button_cancel\">
									<form action=\"?s=settings&amp;x=users\" method=\"post\">
										<input type=\"submit\" title=\"{$pfx_lang['form_button_cancel']}\" value=\"{$pfx_lang['form_button_cancel']}\" />
									</form>
								</span>
							</div>
							<div class=\"safclear\"></div>
						</fieldset>
 					</form>
 				</div>\n";
	} else {
?>
			<div id="pfx_content">
			<div id="users-block">
				<div id="admin_block_user" class="admin_block">
<?php
		echo "<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s={$pfx_s}&amp;x=users\" method=\"post\">
 					<fieldset>
					    <legend>{$pfx_lang['create_user']}</legend>
						<div class=\"form_row\">
							<div class=\"form_label\"><label for=\"privilege\">{$pfx_lang['form_user_permissions']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label><span class=\"form_help\">{$pfx_lang['form_help_user_permissions_block']}</span></div>
							<div class=\"form_item_drop\"><select class=\"form_select\" name=\"privilege\" id=\"perms\">
								<option value=\"2\">Administrator</option>
								<option selected=\"selected\" value=\"1\">Client</option>
								<option value=\"0\">User</option>
							</select></div>
						</div>
						<div class=\"form_row_button\" id=\"form_button\">
							<input type=\"submit\" name=\"user\" class=\"form_submit\" value=\"{$pfx_lang['form_button_create_user']}\" />
							<input type=\"hidden\" name=\"do\" value=\"newuser\" />
						</div>
					</fieldset>
 					</form>";
?>			
					</div>
				</div>
<?php
		safe_optimize('pfx_users');
		safe_repair('pfx_users');
		echo "\t\t\t\t\t<h2>{$pfx_lang['nav2_users']}</h2>\n\t\t\t\t\t<p>{$pfx_lang['user_info']}</p>";
			echo "\n\t\t\t\t\t<div id=\"users-wrap\">";
		$pfx_rs = safe_rows('*', 'pfx_users', 'privs >= 2 order by realname asc');
		if ($pfx_rs) {
			echo "\n\t\t\t\t\t<div id=\"user_admins\">
						<h3>{$pfx_lang['admins']}</h3>\n";
			// last seen "commenting on: XXXXX & DATE"
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out       = $pfx_rs[$pfx_i];
				$pfx_user_name = $pfx_out['user_name'];
				$pfx_realname  = $pfx_out['realname'];
				$pfx_email     = $pfx_out['email'];
				$pfx_privs     = $pfx_out['privs'];
				$pfx_userid    = $pfx_out['user_id'];
				if ( ($pfx_privs == 3) && (isset($pfx_email)) && (isset($pfx_realname)) ) {
					if ($GLOBALS['pfx_user'] == $pfx_user_name) {
						echo "\t\t\t\t\t\t<div class=\"auser superuser vcard\"><img src=\"admin/theme/images/icons/user_tie.png\" alt=\"User image\" class=\"aicon\" /><span class=\"uname fn\"><a href=\"mailto:{$pfx_email}\" class=\"email\" title=\"Email {$pfx_realname}\">{$pfx_realname}</a> ({$pfx_user_name})</span><span class=\"uedit\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_userid}\">{$pfx_lang['edit']}</a></span></div>\n";
					} else {
						echo "\t\t\t\t\t\t<div class=\"auser superuser vcard\"><img src=\"admin/theme/images/icons/user_tie.png\" alt=\"User image\" class=\"aicon\" /><span class=\"uname fn\"><a href=\"mailto:{$pfx_email}\" class=\"email\" title=\"Email {$pfx_realname}\">{$pfx_realname}</a> ({$pfx_user_name})</span><span class=\"suser\">Super User</span></div>\n";
					}
				} else {
					echo "\t\t\t\t\t\t<div class=\"auser vcard\"><img src=\"admin/theme/images/icons/user_tie.png\" alt=\"User image\" class=\"aicon\" /><span class=\"uname fn\"><a href=\"mailto:{$pfx_email}\" class=\"email\" title=\"Email {$pfx_realname}\">{$pfx_realname}</a> ({$pfx_user_name})</span><span class=\"uedit\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_userid}\">{$pfx_lang['edit']}</a></span><span class=\"udelete\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;del={$pfx_userid}\" class=\"confirm-del\">{$pfx_lang['delete']}</a></span></div>\n";
				}
				$pfx_i++;
			}
			echo "\t\t\t\t\t</div>\n";
		}
		$pfx_rs = safe_rows('*', 'pfx_users', 'privs = 1 order by realname asc');
		if ($pfx_rs) {
			echo "\t\t\t\t\t<div id=\"user_clients\">
						<h3>{$pfx_lang['clients']}</h3>\n";
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out       = $pfx_rs[$pfx_i];
				$pfx_user_name = $pfx_out['user_name'];
				$pfx_realname  = $pfx_out['realname'];
				$pfx_email     = $pfx_out['email'];
				$pfx_privs     = $pfx_out['privs'];
				$pfx_userid    = $pfx_out['user_id'];
				if ( (isset($pfx_email)) && (isset($pfx_realname)) ) {
					echo "\t\t\t\t\t\t<div class=\"auser vcard\"><img src=\"admin/theme/images/icons/user_hat.png\" alt=\"User image\" class=\"aicon\" /><span class=\"uname fn\"><a href=\"mailto:{$pfx_email}\" class=\"email\" title=\"Email {$pfx_realname}\">{$pfx_realname}</a> ({$pfx_user_name})</span><span class=\"uedit\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_userid}\">{$pfx_lang['edit']}</a></span><span class=\"udelete\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;del={$pfx_userid}\" class=\"confirm-del\">{$pfx_lang['delete']}</a></span></div>\n";
				}
				$pfx_i++;
			}
			echo "\t\t\t\t\t</div>\n";
		}
		$pfx_rs = safe_rows('*', 'pfx_users', 'privs = 0 order by realname asc');
		if ($pfx_rs) {
			echo "\t\t\t\t\t<div id=\"user_users\">
						<h3>{$pfx_lang['nav2_users']}</h3>\n";
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out       = $pfx_rs[$pfx_i];
				$pfx_user_name = $pfx_out['user_name'];
				$pfx_realname  = $pfx_out['realname'];
				$pfx_email     = $pfx_out['email'];
				$pfx_privs     = $pfx_out['privs'];
				$pfx_userid    = $pfx_out['user_id'];
				if ( (isset($pfx_email)) && (isset($pfx_realname)) ) {
					echo "\t\t\t\t\t\t<div class=\"auser vcard\"><img src=\"admin/theme/images/icons/user.png\" alt=\"User image\" class=\"aicon\" /><span class=\"uname fn\"><a href=\"mailto:{$pfx_email}\" class=\"email\" title=\"Email {$pfx_realname}\">{$pfx_realname}</a> ({$pfx_user_name})</span><span class=\"uedit\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_userid}\">{$pfx_lang['edit']}</a></span><span class=\"udelete\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;del={$pfx_userid}\" class=\"confirm-del\">{$pfx_lang['delete']}</a></span></div>\n";
				}
				$pfx_i++;
			}
			echo '</div>';
		}
		echo '</div></div>';
?>
					<div id="admin_block_help" class="admin_block">
						<h3><?php
		echo $pfx_lang['help'];
?></h3>
						<p><?php
		echo $pfx_lang['help_settings_user_type'];
?></p>
						<ul>
							<li><img src="admin/theme/images/icons/user_tie.png" alt="Administrator" /> <?php
		echo $pfx_lang['help_settings_user_admin'];
?></li>
							<li><img src="admin/theme/images/icons/user_hat.png" alt="Client" /> <?php
		echo $pfx_lang['help_settings_user_client'];
?></li>
							<li><img src="admin/theme/images/icons/user.png" alt="User" /> <?php
		echo $pfx_lang['help_settings_user_user'];
?></li>
						</ul>
					</div>
<?php
	}
}