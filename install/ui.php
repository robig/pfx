<?php
if (!defined('DIRECT_ACCESS')) { exit( header( 'Location: ../' ) ); }
header("Content-Type: text/html; charset={$pfx_charset}");
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
 * Title: Installer User Interface
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

	<!-- 
	PFX Powered (heydojo.co.cc)
	Licence: GNU General Public License v3
	Copyright (C) 2008 <?php
echo date('Y');
?>, Tony White
	Pixie Copyright (C) 2008 - 2010, Scott Evans

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see http://www.gnu.org/licenses/   

	heydojo.co.cc
	-->
	
	<!-- meta tags -->
	<meta http-equiv="cache-control" CONTENT="no-cache" />
	<meta http-equiv="content-type" content="text/html;charset=<?php echo strtolower($pfx_charset); ?>" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<meta name="googlebot" content="noindex" />
	<meta name="keywords" content="PFX, cms, installer" />
	<meta name="description" content="PFX is an open source web application that will help you quickly create and maintain your own website. PFX is available at heydojo.co.cc" />
	<meta name="author" content="Tony White" />
	<meta name="copyright" content="Tony White" />

	<title>PFX - (heydojo.co.cc) - Installer</title>

	<link rel="stylesheet" type="text/css" href="../admin/admin/theme/reset-min.css">
	<link rel="stylesheet" type="text/css" href="../admin/admin/theme/core.css">
	<link rel="stylesheet" type="text/css" href="../admin/admin/theme/selectbox.css">
	<link rel="stylesheet" type="text/css" href="css/install.css">

	<!-- site icons-->
	<link rel="Shortcut Icon" type="image/x-icon" href="../admin/admin/theme/images/favicon.ico" />

</head>

<body>
	<div id="top1" class="top">
    	<div id="top-small"></div>
    </div><!-- end top -->
    <div id="top2" class="top">
        <div id="top-big"></div>
    </div><!-- end top -->
	<div id="header">
		<div id="headerWrap">
		    <div id="roundContent4" class="roundHeader">
			<div class="boxContent">
			    <div id="top">
<div id="menu-wrap">
    	<ul id="menu">
       	  <li class="slide-link"><a href="#box1" class="link" id="link1"><?php echo $pfx_lang['installer_step1'];?></a></li>
            <li class="slide-link"><a href="#box2" class="link" id="link2"><?php echo $pfx_lang['installer_step2'];?></a></li>
            <li class="slide-link"><a href="#box3" class="link" id="link3"><?php echo $pfx_lang['installer_step3'];?></a></li>
            <li class="slide-link"><a href="#box4" class="link" id="link4"><?php echo $pfx_lang['installer_title4'];?></a></li>
      </ul>
</div>
				<div id="title-wrapper">
				    <h1 id="pfx_title" title="PFX"><span><a href="<?php echo "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}"; ?>" rel="home">PFX</a></span></h1>
					<h2 id="pfx_strapline" title="<?php
	echo "v{$GLOBALS['pfx_version']} - {$pfx_lang['installer']}";
?>"><span><?php
	echo "v{$GLOBALS['pfx_version']} - {$pfx_lang['installer']}";
?></span></h2>
				</div>
				<div id="ajaxHeader2">
				    <div id="ajaxHeader1">
<div class="nav-list">
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		</div>
		<b class="roundBottom"><b class="f4"></b><b class="f3"></b><b class="f2"></b><b class="f1"></b></b>
	    <!-- header end -->
	    </div>
<div id="nav_1">
			</div>
	    </div>
	    <div class="colmask rightmenu">
		<div class="colleft">
		    <div class="col1wrap">
			<div class="col1">
			<!-- Column 1 start -->
			    <div id="mainContent">
				<div id="ajaxMessage">
				    <div id="ajaxTimer"><?php echo "{$pfx_lang['installer_progress_info']}"; ?></div>
				</div>
				<div id="ajaxLoading">
				</div>
				<b class="roundTop"><b class="e1"></b><b class="e2"></b><b class="e3"></b><b class="e4"></b></b>
				<div id="contentWrap">
				    <div id="roundContent3" class="roundContentMain">
					<div id="wrapEverything" class="boxContent">
					    <div id="pfx_body">
					    <noscript>
						<p>
						    <label class="error">
							<?php echo $pfx_lang['install_js_warn']; ?>
						    </label>
						</p>
					    </noscript>
						<div id="ajaxContent2">
						    <div id="ajaxContent1">
							<div id="content">
<div id="license"><div id="license-wrap"><?php
$file = '../license.txt';
$contents= file($file);
$string = implode($contents);
echo nl2br( htmlspecialchars($string, ENT_QUOTES, $pfx_charset ) );
?>
</div>
<div class="form_item"><h3><b><?php echo $pfx_lang['installer_license_agree'];?><span class="form_required"> * </span></b></h3><span id="form-agree"><b><?php echo $pfx_lang['form_yes']; ?></b> <input id="license-agree" type="checkbox" selected="selected" /></span></div>
</div>
							    <div id="message-wrap">
								<div id="message-pad">
								    <div id="message">
</div>
								</div>
							    </div>
	<div id="wrapper">
    	<ul id="mask">
        	<li id="box1" class="box">
            	<a name="box1"></a>
                <div class="content"><div class="inner"><p class="error"></p><h2><?php echo $pfx_lang['installer_step1'];?></h2>
		<p class="toptext"><?php echo $pfx_lang['installer_welcome1']; ?> <a href="http://heydojo.co.cc" alt="Get PFX!" target="_blank">PFX</a> <?php echo $pfx_lang['installer_welcome2'];?></p>
		
		<form accept-charset="<?php echo $pfx_charset; ?>" action="index.php?<?php echo SID;?>" method="post" id="form_site" class="form">
			<fieldset>
			<legend><?php echo $pfx_lang['installer_title1'];?></legend>
				<div class="form_row">
					<div class="form_label"><label for="langu"><?php echo $pfx_lang['form_pfx_language'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['form_help_pfx_language'];?>)</span></div>
					<div class="form_item_drop">
						<select class="form_select" name="langu" id="langu">
							<option selected="selected" value="en-gb">English</option>
						</select>
					</div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="server_timezone"><?php echo $pfx_lang['form_pfx_timezone'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['form_help_pfx_timezone'];?>)</span></div>
					<div class="form_item_drop">
						<select class="form_select" name="server_timezone" id="server_timezoneselect">
							<option selected="selected" value="<?php
		if ( isset($pfx_server_timezone) ) { echo $pfx_server_timezone; } else { echo 'Europe/London'; }
?>"><?php
		if ( isset($pfx_server_timezone) ) { echo $pfx_server_timezone; } else { echo 'Europe/London'; }
?></option>
							<?php
		foreach ($pfx_zonelist as $pfx_tzselect) {
			// Output all the timezones
			Echo "<option value=\"" . str_replace('_', 'BREAK1', $pfx_tzselect) . "\">$pfx_tzselect</option>";
		}
?>
						</select>
					</div>
				</div>
				<div class="form_row ">
					<div class="form_label"><label for="url"><?php echo $pfx_lang['form_site_url'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['form_help_site_url'];?>)</span></div>
					<div class="form_item"><input type="text" class="form_text" name="url" value="<?php
		echo $pfx_url;
?>" size="40" maxlength="80" id="url" /></div>
				</div>
				<div class="form_row ">
					<div class="form_label"><label for="site_name"><?php echo $pfx_lang['form_site_name'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['form_help_site_name'];?>)</span></div>
					<div class="form_item"><input type="text" class="form_text" name="sitename" value="<?php
		if ((isset($pfx_sitename)) && ($pfx_sitename)) {
			echo $pfx_sitename;
		} else {
			echo 'My PFX Site';
		}
?>" size="40" maxlength="80" id="site_name" /></div>
				</div>
				<div class="form_row">
<?php
		if ( (isset($_SERVER['HTTP_MOD_REWRITE'])) && ($_SERVER['HTTP_MOD_REWRITE'] == 'On') ) {?>
					<div class="form_item"><input id="clean-urls-check" type="checkbox" name="clean_urls_check" selected="selected" value="yes" checked /> <?php echo $pfx_lang['installer_curl1'];?> <span class="form_help">(<?php echo $pfx_lang['installer_curl2'];?>)</span></div>
<?php } ?>
				</div>
				<div class="form_row">
				<div class="form_row_button" id="form_button">
					<input type="hidden" name="step" value="1" />
					<input type="submit" name="next" class="form_submit" value="<?php echo $pfx_lang['installer_next'];?> &raquo;" />
				</div>
				</div>
				<div class="safclear"></div>
			</fieldset>	
		</form>

</div></div>
            </li><!-- end box1 -->
            <li id="box2" class="box">
            	<a name="box2"></a>
                <div class="content"><div class="inner"><p class="error"></p><h2><?php echo $pfx_lang['installer_step2'];?></h2>

		<p class="toptext"><?php echo $pfx_lang['installer_welcome3'];?></p>
		
		<form accept-charset="<?php echo $pfx_charset; ?>" action="index.php?<?php echo SID;?>" method="post" id="form_db" class="form">
			<fieldset>
			<legend><?php echo $pfx_lang['installer_title2'];?></legend>
				<div class="form_row ">
					<div class="form_label"><label for="host"><?php echo $pfx_lang['installer_host1'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['installer_host2'];?> "localhost")</span></div>
					<div class="form_item"><input type="text" class="form_text" name="host" value="<?php
		if ((isset($pfx_host)) && ($pfx_host)) {
			echo $pfx_host;
		} else {
			echo 'localhost';
		}
?>" size="40" maxlength="80" id="host" /></div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="username"><?php echo $pfx_lang['installer_db_user_name'];?> <span class="form_required"> * </span></label></div>
					<div class="form_item"><input type="text" class="form_text" name="db_username" value="<?php
		if ((isset($pfx_db_username)) && ($pfx_db_username)) {
			echo $pfx_db_username;
		}
?>" size="40" maxlength="80" id="db-username" /></div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="db_usr_password"><?php echo $pfx_lang['installer_db_pwd'];?> <span class="form_required"> * </span></label></div>
					<div class="form_item"><input type="password" class="form_text" name="db_usr_password" value="<?php
		if ((isset($pfx_db_usr_password)) && ($pfx_db_usr_password)) {
			echo $pfx_db_usr_password;
		}
?>" size="40" maxlength="80" id="password" /></div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="database"><?php echo $pfx_lang['installer_db_name'];?> <span class="form_required"> * </span></label></div>
					<div class="form_item"><input type="text" class="form_text" name="database" value="<?php
		if ((isset($pfx_database)) && ($pfx_database)) {
			echo $pfx_database;
		}
?>" size="40" maxlength="80" id="database" /></div>
				</div>
				<div class="form_row">
					<div class="form_item"><input id="create-db" type="checkbox" name="create_db" selected="selected" value="yes" /> <?php echo $pfx_lang['installer_db_create1'];?> <span class="form_help">(<?php echo $pfx_lang['installer_db_create2'];?>)</span></div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="prefix"><?php echo $pfx_lang['installer_db_prefix1'];?> <span class="form_optional"><?php echo $pfx_lang['form_optional'];?></span></label></div>
					<div class="form_item"><input type="text" class="form_text" name="prefix" value="<?php
		if ((isset($pfx_prefix)) && ($pfx_prefix)) {
			echo $pfx_prefix;
		}
?>" size="40" maxlength="80" id="prefix" /><span class="form_help"> (<?php echo $pfx_lang['installer_db_prefix2'];?>)</span></div>
				</div>

				<div class="form_row_button" id="form_button">
					<input type="hidden" name="step" value="2" />
					<input type="submit" name="next" class="form_submit" value="<?php echo $pfx_lang['installer_next'];?> &raquo;" />
				</div>
				<div class="safclear"></div>
			</fieldset>	
 		</form>

</div></div>
            </li><!-- end box2 -->
            <li id="box3" class="box">
            	<a name="box3"></a>
                <div class="content"><div class="inner"><p class="error"></p><h2><?php echo $pfx_lang['installer_step3'];?></h2>

		<p class="toptext"><?php echo $pfx_lang['installer_welcome4'];?></p>
	
		<form accept-charset="<?php echo $pfx_charset; ?>" action="index.php?<?php echo SID;?>" method="post" id="form_user" class="form">
			<fieldset>
			<legend><?php echo $pfx_lang['installer_title3'];?></legend>
				<div class="form_row">
					<div class="form_label"><label for="name"><?php echo $pfx_lang['installer_real_name1'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['installer_real_name2'];?>)</span></div>
					<div class="form_item"><input id="realname" type="text" class="form_text" name="name" value="<?php
		if ((isset($pfx_name)) && ($pfx_name)) {
			echo $pfx_name;
		}
?>" size="40" maxlength="80" /></div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="username"><?php echo $pfx_lang['form_username'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['installer_usr_name'];?>)</span></div>
					<div class="form_item"><input id="username" type="text" class="form_text" name="login_username" value="<?php
		if ((isset($pfx_login_username)) && ($pfx_login_username)) {
			echo $pfx_login_username;
		}
?>" size="40" maxlength="80" /></div>
				</div>
	
				<div class="form_row">
					<div class="form_label"><label for="email"><?php echo $pfx_lang['form_email'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['installer_usr_email'];?>)</span></div>
					<div class="form_item"><input type="text" class="form_text" name="email" value="<?php
		if ((isset($pfx_email)) && ($pfx_email)) {
			echo $pfx_email;
		}
?>" size="40" maxlength="80" id="email" /></div>
				</div>
				<div class="form_row">
					<div class="form_label"><label for="login_password"><?php echo $pfx_lang['form_password'];?> <span class="form_required"> * </span></label><span class="form_help">(<?php echo $pfx_lang['installer_usr_pwd'];?>)</span></div>
					<div class="form_item"><input type="password" class="form_text" name="login_password" value="<?php
		if ((isset($pfx_login_password)) && ($pfx_login_password)) {
			echo $pfx_login_password;
		}
?>" size="40" maxlength="80" id="password" /></div>
				</div>
				<div class="form_row_button" id="form_button">
					<input type="hidden" name="step" value="3" />
					<input type="submit" name="next" class="form_submit" value="<?php echo $pfx_lang['installer_finish'];?>" />
				</div>
				<div class="safclear"></div>
			</fieldset>	
		</form>

</div></div>
            </li><!-- end box3 -->
            <li id="box4" class="box">
            	<a name="box4"></a>
                <div class="content"><div class="inner"><p class="error"></p><h2><?php echo $pfx_lang['installer_title4'];?></h2>
		<form accept-charset="<?php echo $pfx_charset; ?>" action="index.php?<?php echo SID;?>" method="post" id="form_process" class="form">
			<fieldset>
				<div class="form_row_button" id="form_button">
					<input type="hidden" name="step" value="4" />
					<input type="submit" name="next" class="form_submit" id="hidden_form" value="process" />
				</div>
				<div class="safclear"></div>
			</fieldset>	
		</form>

		      <div id="result"></div>
		      <div id="result2">
		      <?php echo "<p>{$pfx_lang['installer_complete1']}</p>
			<p>{$pfx_lang['installer_complete2']} <a href=\"{$pfx_url}admin/\" title=\"{$pfx_lang['form_login']}\" target=\"_blank\">{$pfx_lang['installer_complete3']}</a> {$pfx_lang['installer_complete4']}</p>
			<p><a id=\"frontpage-url\" href=\"{$pfx_url}\" title=\"{$pfx_lang['installer_complete5']}\" target=\"_blank\">{$pfx_lang['installer_complete6']}</a>?</p><br /><br />
			<p><b>{$pfx_lang['installer_complete7']}</b></p>"; ?>
		      </div>
</div></div>
            </li><!-- end box4 -->
        </ul><!-- end mask -->
    </div><!-- end wrapper -->
							</div>
						    </div>
						</div>
					    </div>
					    <div class="clearThis">
					    </div>
					    <div id="copyRight" class="small">
				<ul id="pfx-credits">
<li id="cred_pfx"><a href="http://heydojo.co.cc/" title="Get PFX" target="_blank">PFX Powered.</a></li>
					<li id="cred_licence"><?php
	echo $pfx_lang['license'];
?> <a href="<?php
	echo '../license.txt';
?>" title="<?php
	echo $pfx_lang['license'];
?> GNU General Public License v3" rel="license" target="_blank">GNU General Public License v3</a>.</li>
				</ul>
					    </div>
					</div>
				    </div>
				</div>
				<b class="roundBottom"><b class="d4"></b><b class="d3"></b><b class="d2"></b><b class="d1"></b></b>
			    </div>
			<!-- Column 1 end -->
			</div>
		    </div>
		    <div class="col2">
		    <!-- Column 2 start -->

		    <!-- Column 2 end -->
		    </div>
		</div>
	    </div>
	    <div id="footer">
		<b class="roundTop"><b class="c1"></b><b class="c2"></b><b class="c3"></b><b class="c4"></b></b>
		<div id="footerWrap">
		    <div id="roundContent2" class="roundContentFooter">
			<div class="boxContent">
			    <div id="credits">
			    </div>
			</div>
		    </div>
		</div>
	    </div><!-- end footer -->

    <script type="text/javascript" src="../admin/jscript/jquery.js"></script>
    <script type="text/javascript" src="js/jqueryScrollTo.js"></script>
    <script type="text/javascript" src="../admin/jscript/jqueryForm.js"></script>
    <script type="text/javascript" src="../admin/jscript/validate.js"></script>
    <script type="text/javascript" src="../admin/jscript/selectbox.js"></script>
    <script type="text/javascript" src="js/jsLangVars.php?<?php echo 'js_lang_site_url=' . htmlspecialchars( urlencode($pfx_lang['site_url_error']) ) . '&amp;js_lang_site_name=' . htmlspecialchars( urlencode($pfx_lang['site_name_error']) ) . '&amp;js_lang_host_name=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_hostname']) ) . '&amp;js_lang_user_name=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_username']) ) . '&amp;js_lang_pwd=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_pwd']) ) . '&amp;js_lang_db_pwd=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_db_pwd']) ) . '&amp;js_lang_real_name=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_real_name']) ) . '&amp;js_lang_login_name=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_login_name']) ) . '&amp;js_lang_email=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_email']) ) . '&amp;js_lang_login_pwd=' . htmlspecialchars( urlencode($pfx_lang['installer_js_lang_login_pwd']) ); ?>"></script>
    <script type="text/javascript" src="js/install.js"></script>

</body>
</html>
