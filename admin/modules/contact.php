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
 * Title: Contact
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
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
switch ($pfx_do) {
	/* General information : */
	case 'info' :
		$pfx_m_name          = ucfirst($pfx_m_n);
		$pfx_m_description   = "A simple {$pfx_m_n} form for your website with hCard/vCard Microformats.";
		$pfx_m_author        = 'Scott Evans';
		$pfx_m_url           = 'http://www.toggle.uk.com';
		$pfx_m_version       = '1.2';
		$pfx_m_type          = 'module';
		$pfx_m_publish       = 'no';
		$pfx_m_in_navigation = 'yes';
		break;
	/* Install */
	case 'install' :
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`show_profile_information` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`show_vcard_link` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'no',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
		$pfx_execute2 = "INSERT INTO `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id`, `show_profile_information`, `show_vcard_link`) VALUES (1, 'no', 'no');";
		break;
	/* The administration of the module (Add, edit, delete) */
	case 'admin' :
		break;
	/* Pre */
	case 'pre' :
		$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
		$pfx_ptitle     = ucfirst($pfx_m_n) . " - {$pfx_site_title}";
		$pfx_pinfo      = ucfirst($pfx_m_n) . " {$pfx_site_title}";
		/* If the form is submitted */
		if (isset($pfx_contact_sub)) {
			/* Check to see if the refferal is from the current site */
			if (strpos($_SERVER['HTTP_REFERER'], PREFS_SITE_URL) != FALSE) {
				die();
			}
			/* Check to see if our bot catcher has been filled in */
			if ($pfx_iam) {
				die();
			}
			if ( (isset($pfx_comment_submit)) && ($pfx_comment_submit) ) {
				if (PREFS_CAPTCHA == 'yes') {
					$pfx_resp = recaptcha_check_answer(PREFS_RECAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
					if (!$pfx_resp->is_valid) {
					    $pfx_error .= "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
					}
				}
			}
			if (isset($pfx_uemail)) {
				$pfx_domain = explode('@', $pfx_uemail);
				if (preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $pfx_uemail) && checkdnsrr($pfx_domain[1])) {
					if (isset($pfx_subject)) {
						if (isset($pfx_message)) {
							$pfx_message = sterilise($pfx_message);
							$pfx_subject = sterilise($pfx_subject);
							$pfx_uemail  = sterilise($pfx_uemail);
							$pfx_to      = safe_field('email', 'pfx_users', "user_id = '{$pfx_contact}' limit 0,1");
							$pfx_eol     = "\r\n";
							$pfx_headers  = 'MIME-Version: 1.0' . "\r\n";
							$pfx_headers .= 'Content-type: text/plain; charset=' . PFX_CHARSET . "\r\n";
							$pfx_headers .= 'Content-transfer-encoding: 8bit' . "\r\n";
							$pfx_headers .= "From: {$pfx_uemail} <{$pfx_uemail}>{$pfx_eol}";
							$pfx_headers .= "Reply-To: {$pfx_uemai}l <{$pfx_uemail}>{$pfx_eol}";
							$pfx_headers .= "Return-Path: {$pfx_uemail} <{$pfx_uemail}>{$pfx_eol}";
						} else {
							$pfx_error = 'Please enter a message.';
						}
					} else {
						$pfx_error = 'Please provide a subject.';
					}
				} else {
					$pfx_error = 'Please provide a valid email adress.';
				}
			} else {
				$pfx_error = 'Please provide your email address.';
			}
			if ( (isset($pfx_error)) && ($pfx_error) ) {
				unset($pfx_contact_sub);
			} else {
				mail($pfx_to, $pfx_subject, $pfx_message, $pfx_headers);
				/* Send the mail */
				$pfx_log_message = "{$pfx_uemail} sent a message to {$pfx_to} using the {$pfx_m_n} form.";
				logme($pfx_log_message, 'no', 'site');
				/* Log the action */
			}
		}
		break;
	/* Head */
	case 'head' :
		break;
	/* Show Module */
	default :
		$pfx_sets = safe_row('*', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id='1'");
		/* Get the settings for this page */
		extract($pfx_sets, EXTR_PREFIX_ALL, 'pfx');
		$pfx_sets = NULL;
		/* Extract them */
		echo '<h3>' . ucfirst($pfx_m_n) . '</h3>';
		if (isset($pfx_show_profile_information)) {
			if ($pfx_show_profile_information == 'yes') {
				$pfx_rs = safe_rows_start('*', 'pfx_users', '1 order by privs desc');
				while ( $pfx_a = nextRow($pfx_rs) ) {
					extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
					$pfx_a = NULL;
					echo "<div class=\"vcard\">
						<a class=\"url fn\" href=\"{$pfx_website}\"><span class=\"given-name\">";
					if (isset($pfx_realname)) {
						echo firstword($pfx_realname);
					}
					echo "</span><span class=\"family-name\"> ";
					if (isset($pfx_realname)) {
						echo lastword($pfx_realname);
					}
					echo "</span></a>
						<div class=\"org hide\">{$pfx_occupation}</div>
						<div class=\"adr\">
							<span class=\"street-address\">{$pfx_street}</span> 
							<span class=\"locality\">{$pfx_town}</span>
							<span class=\"region\">{$pfx_county}</span> 
							<span class=\"country-name\">{$pfx_country}</span>
							<span class=\"postal-code\">{$pfx_post_code}</span>
						</div>
						<span class=\"tel\">{$pfx_telephone}</span>";
					if ($pfx_show_vcard_link == 'yes') {
						if (isset($pfx_s)) {
							echo "<p class=\"extras\"><span class=\"down_vcard\"><a href=\"http://technorati.com/contacts/" . createURL($pfx_s) . "\">Download my vCard</a></span></p>";
						}
					}
					echo "
					</div>";
				}
			}
		}
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			echo "<p class=\"error\">{$pfx_error}</p>";
		}
		if (!isset($pfx_contact_sub)) {
			if (!isset($pfx_uemail)) {
				$pfx_uemail = NULL;
			}
			echo "<form accept-charset=\"" . PFX_CHARSET . "\" action=\"";
			if (isset($pfx_s)) {
				echo createURL($pfx_s);
			}
			echo "\" method=\"post\" id=\"contactform\" class=\"form\">
						<fieldset>
						<legend>Email {$pfx_site_title}</legend>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"uemail\">Enter your email <span class=\"form_required\">*</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"uemail\" maxlength=\"80\" id=\"uemail\" value=\"{$pfx_uemail}\" /></div>
							</div>
							<div class=\"form_row\" id=\"contact_list\">
								<div class=\"form_label\"><label for=\"contact\">Select Contact <span class=\"form_required\">*</span></label></div>
								<div class=\"form_item_drop\"><select class=\"form_select\" name=\"contact\" id=\"contact\">";
			$pfx_rs = safe_rows_start('*', 'pfx_users', '1 order by privs desc');
			while ( $pfx_a = nextRow($pfx_rs) ) {
				extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
				$pfx_a = NULL;
				if ($pfx_is_contact == 'yes') {
					if ((strlen($pfx_occupation) > 0) && (isset($pfx_realname))) {
						echo "<option value=\"{$pfx_user_id}\">{$pfx_realname} - ({$pfx_occupation})</option>";
					} else {
						echo "<option value=\"{$pfx_user_id}\">{$pfx_realname}</option>";
					}
				}
			}
			if (isset($pfx_subject)) {
			} else {
				$pfx_subject = FALSE;
			}
			if (isset($pfx_message)) {
			} else {
				$pfx_message = FALSE;
			}
			echo "</select></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"subject\">Subject <span class=\"form_required\">*</span></label></div>
								<div class=\"form_item\"><input type=\"text\" class=\"form_text\" name=\"subject\" value=\"{$pfx_subject}\" size=\"30\" maxlength=\"80\" id=\"subject\" /></div>
							</div>
							<div class=\"form_row\">
								<div class=\"form_label\"><label for=\"messagey\">Message <span class=\"form_required\">*</span></label></div>
								<div class=\"form_item\"><textarea name=\"message\" cols=\"35\" class=\"form_text_area\" rows=\"8\" id=\"messagey\">{$pfx_message}</textarea></div>
							</div>";
					if (PREFS_CAPTCHA == 'yes') {
						echo '<div class="form_row">
							<div id="recaptcha_div"></div>
							   <div id="captchadiv">
								<div id="recaptcha_image"></div>';
							echo "<div class=\"form_label\">
								<label>{$pfx_lang['captcha_text']}</label>
								<span class=\"form_required\">*</span>
							    </div>";
							echo '<input type="text" name="recaptcha_response_field" id="recaptcha_response_field" />
							  <div>
							  <a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
							</div>
						    <div>
						    <a href="javascript:Recaptcha.showhelp()">Help</a>
						    </div>
						    <noscript>
							<div id="no-comment">Sorry. You may not comment if javascript is disabled in your web browser, for security reasons. Thank you.</div>
						    </noscript>
						</div>
					    </div>';
					}
					echo "<div class=\"form_row_submit\">
						    <input type=\"hidden\" name=\"iam\" value=\"\" />
							<input type=\"submit\" name=\"contact_sub\" class=\"form_submit\" id=\"contact_submit\" value=\"Submit\" />
						</div>
						</fieldset>
					</form>";
		} else {
			echo '<p class="notice emailsent">Thank you for your email.</p>';
			/* Needs language */
		}
		break;
}