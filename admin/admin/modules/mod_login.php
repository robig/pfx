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
 * Title: Login
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
if (isset($pfx_login_forgotten)) {
	sleep(3);
	if (!isset($pfx_username)) {
		$pfx_username = NULL;
	}
	$pfx_username = sterilise($pfx_username, TRUE);
	$pfx_r1       = safe_field('email', 'pfx_users', "email='{$pfx_username}'");
	$pfx_r2       = safe_field('user_name', 'pfx_users', "user_name='{$pfx_username}'");
	if ($pfx_r1 or $pfx_r2) {
		if ($pfx_r1) {
			$pfx_rs = $pfx_r1;
		} else {
			$pfx_rs = safe_field('email', 'pfx_users', "user_name='{$pfx_username}'");
		}
		if ($pfx_rs) {
			$pfx_password = generate_password(8);
			$pfx_nonce    = hash('sha256', uniqid( rand(), TRUE) );
			$pfx_sql      = "pass = '" . doPass($pfx_password) . "', nonce = '{$pfx_nonce}'";
			$pfx_ok       = safe_update('pfx_users', "{$pfx_sql}", "email = '{$pfx_rs}'");
			if ((isset($pfx_rs)) && ($pfx_ok)) {
				$pfx_email   = $pfx_rs;
				$pfx_subject = $pfx_lang['email_newpassword_subject_1'] . str_replace('http://', "", PREFS_SITE_URL) . $pfx_lang['email_newpassword_subject_2'];
				if (isset($pfx_subject)) {
				} else {
					$pfx_subject = FALSE;
				}
				$pfx_emessage = "{$pfx_lang['email_newpassword_message']} {$pfx_password}";
				$pfx_user     = safe_field('realname', 'pfx_users', "email='{$pfx_email}'");
				$pfx_headers  = 'MIME-Version: 1.0' . "\r\n";
				$pfx_headers .= 'Content-type: text/plain; charset=' . PFX_CHARSET . "\r\n";
				$pfx_headers .= 'Content-transfer-encoding: 8bit' . "\r\n";
				$pfx_headers  .= "From: postmaster@{$_SERVER['HTTP_HOST']}" . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n";
				mail($pfx_email, $pfx_subject, $pfx_emessage, $pfx_headers);
				$pfx_messageok = $pfx_lang['forgotten_ok'];
				logme($pfx_lang['forgotten_log_ok'] . $pfx_user . ' (' . $pfx_email . ').', 'yes', 'user');
				$pfx_m = 'ok';
			} else {
				$pfx_message = $pfx_lang['unknown_error'];
			}
		}
	} else {
		$pfx_message = $pfx_lang['forgotten_missing'];
		logme($pfx_lang['forgotten_log_error'], 'yes', 'error');
	}
}
if ( (isset($pfx_m)) && ($pfx_m == 'forgotten') ) {
?>
				<div id="login">
					<form accept-charset="<?php echo PFX_CHARSET; ?>" action="?s=login&amp;m=forgotten" method="post" id="form_forgotten" class="form">
						<fieldset>
							<legend>Forgotten your password?</legend>
							<p><?php echo $pfx_lang['form_help_forgotten']; ?></p>		
							<div class="form_row">
								<div class="form_label">
									<label for="username"><?php echo $pfx_lang['form_usernameoremail']; ?></label>
								</div>
								<div class="form_item"><input type="text" class="form_text" tabindex="1" name="username" id="username" size="30" /></div>
							</div>
							<div class="form_row_button" id="form_button">
								<input type="submit" name="login_forgotten" tabindex="2" value="<?php echo $pfx_lang['form_resetpassword']; ?>" class="form_submit" />
							</div>
							<div class="safclear"></div>
						</fieldset>
					</form>
					<ul>
						<li class="return"><a href="<?php echo PREFS_SITE_URL; ?>" title="<?php echo $pfx_lang['view_site']; ?>"><?php echo $pfx_lang['form_returnto'] . str_replace('http://', "", PREFS_SITE_URL); ?></a></li>
					</ul>	
				</div>
<?php } else { ?>
				<div id="login">
					<form accept-charset="<?php echo PFX_CHARSET; ?>" action="index.php" method="post" id="form_login" class="form">
						<fieldset>
							<legend class="admin-title"><?php echo $pfx_lang['form_login']; ?></legend>		
							<div class="form_row">
								<div class="form_label">
									<label for="username"><?php echo $pfx_lang['form_username']; ?></label>
								</div>
								<div class="form_item">
									<input type="text" class="form_text" tabindex="1" name="username" id="username" size="30" />
								</div>
							</div>
							<div class="form_row">
								<div class="form_label">
									<label for="password"><?php echo $pfx_lang['form_password']; ?></label>
								</div>
								<div class="form_item">
									<input type="password" class="form_text" tabindex="2" name="password" id="password" size="30" />
								</div>
							</div>
							<div class="form_row">
								<div class="form_label_small">
									<label for="remember"><?php echo $pfx_lang['form_rememberme']; ?></label>
									<input type="checkbox" tabindex="3" class="form_check" name="remember" value="checked" id="remember" />
								</div>
							</div>
							<div class="form_row_button" id="form_button">
								<input type="submit" name="login_submit" tabindex="4" value="<?php echo $pfx_lang['form_login']; ?>" class="form_submit" />
							</div>
							<div class="safclear"></div>
						</fieldset>
					</form>
					<ul>
						<li class="forgotten"><a href="?s=login&amp;m=forgotten"><?php echo $pfx_lang['form_forgotten']; ?></a></li>
						<li class="return"><a href="<?php echo PREFS_SITE_URL; ?>" title="<?php echo $pfx_lang['view_site']; ?>"><?php echo $pfx_lang['form_returnto'] . str_replace('http://', "", PREFS_SITE_URL); ?></a></li>
					</ul>	
				</div>
<?php
}