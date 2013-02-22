<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header( 'Location: ../../' ) );
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
 * Title: lib theme
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
function theme_meta($pfx_ptitle = FALSE, $pfx_page_description = FALSE, $pfx_pinfo = FALSE) {
/* http://www.webmarketingnow.com/tips/meta-tags-uncovered.html */ ?>
	<meta http-equiv="imagetoolbar" content="no" />
	<?php if (PREFS_IE7_COMPAT == 'yes') { ?>
<meta http-equiv="X-UA-Compatible" content="IE=7,chrome=1" />
	<?php } else { ?>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
	<?php } ?>
<meta http-equiv="content-type" content="text/html;charset=<?php echo PFX_CHARSET; ?>" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta name="title" content="<?php echo $pfx_ptitle; ?>" />
	<meta name="description" content="<?php if ( (isset($pfx_pinfo)) && ($pfx_pinfo) ) { echo strip_tags($pfx_pinfo); } else if ( (isset($pfx_page_description)) && ($pfx_page_description) ) { echo strip_tags($pfx_page_description); } else { echo $pfx_ptitle; } ?>" />
	<meta name="keywords" content="<?php echo PREFS_SITE_KEYWORDS; ?>" />
	<meta name="author" content="<?php echo PREFS_SITE_AUTHOR; ?>" />
	<meta name="copyright" content="<?php echo PREFS_SITE_COPYRIGHT; ?>" />
	<meta name="generator" content="PFX <?php echo PREFS_VERSION; ?> - Copyright (C) <?php echo date('Y'); ?>." />
	<?php
    return TRUE;

}

function theme_tools($skip_to_nav, $skip_to_content) {
?>
			    <div id="tools">
				<ul id="tools_list">
				    <li id="tool_skip_navigation">
					<a href="#navigation" title="<?php echo $skip_to_nav; ?>"><?php echo $skip_to_nav; ?></a>
				    </li>
				    <li id="tool_skip_content">
					<a href="#content" title="<?php echo $skip_to_content; ?>"><?php echo $skip_to_content; ?>
					</a>
				    </li>
				</ul>
			    </div>
<?php
    return TRUE;

}

function theme_strapline($pfx_page_description = FALSE, $pfx_pinfo = FALSE) {

    echo '<h2 id="site_strapline" title="';
    if ( (isset($pfx_pinfo)) && ($pfx_pinfo) ) {
	echo strip_tags($pfx_pinfo);
    } else if ( (isset($pfx_page_description)) && ($pfx_page_description) ) {
	echo strip_tags($pfx_page_description);
    } else {
	echo 'No Title';
    }
    echo '" class="replace">';
    if ( (isset($pfx_pinfo)) && ($pfx_pinfo) ) {
	echo strip_tags($pfx_pinfo);
    } else if ( (isset($pfx_page_description)) && ($pfx_page_description) ) {
	echo strip_tags($pfx_page_description);
    } else {
	echo 'No Title';
    }
    echo '</h2>';

}

function theme_subscribe() {

    if (public_page_exists('rss')) {
	echo "<li id=\"cred_rss\"><a href=\"" . createURL('rss') . "\" title=\"Subscribe\">Subscribe</a></li>\n";
    }

}

function theme_legal() {

    if (public_page_exists('legal')) {
	echo "<li id=\"legal\"><a class=\"ajax\"href=\"" . createURL('legal') . "\" title=\"Terms and conditions\">Terms and conditions</a></li>\n";
    }

}

function pfx_link_footer() {

    echo '<a id="pfxPowered" href="http://heydojo.co.cc" title="Get PFX">PFX Powered</a>';

}

function theme_javascript($pfx_s, $pfx_rel_path) {

    if ( (isset($pfx_s)) && ($pfx_s == 404) ) {
	return FALSE;
    } else {
	if ( (PREFS_JQUERY == 'yes') or (PREFS_CAPTCHA == 'yes') ) {
	    /* Use jQuery from googleapis */
	    if (PREFS_JQUERY_G_APIS == 'yes') {
		if ( url_exist(PREFS_G_JQUERY_LOC) ) { ?>
	    <script type="text/javascript" src="<?php echo PREFS_G_JQUERY_LOC; ?>" charset="UTF-8"></script>
	  <?php } else { ?>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/themes/<?php echo PREFS_SITE_THEME; ?>/js/jquery.js" charset="UTF-8"></script>
	  <?php } /* End if url exists */
	    } else { /* End Use jQuery from googleapis */
		if (PREFS_JQUERY_LATEST == 'yes') { /* Use latest jQuery from the template's directory */ ?>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/themes/<?php echo PREFS_SITE_THEME; ?>/js/jquery.js" charset="UTF-8"></script>
	  <?php } else { /* End jquery_latest */ ?>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/jquery.js" charset="UTF-8"></script>
	  <?php } /* End does not equal yes */
	    } /* End does not equal yes */
	} /* End template jquery */ ?>
  <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/jsVars.php?<?php
	echo 'action_type=index';
	if (isset($pfx_s)) {
		echo "&amp;s={$pfx_s}";
	}
	if (defined('PREFS_SITE_URL')) {
		echo '&amp;site_url=' . htmlspecialchars( urlencode(PREFS_SITE_URL) );
	}
	if (defined('PREFS_SITE_THEME')) {
		echo '&amp;site_theme=' . htmlspecialchars( urlencode(PREFS_SITE_THEME) );
	}
	if (defined('PREFS_SITE_NAME')) {
		echo '&amp;site_name=' . htmlspecialchars( urlencode(PREFS_SITE_NAME) );
	}
	if (defined('PREFS_EDITOR_ENTER_MODE')) {
		echo '&amp;editor_enter_mode=' . htmlspecialchars( urlencode(PREFS_EDITOR_ENTER_MODE) );
	}
	if (defined('PREFS_EDITOR_IMAGE_CLASS')) {
		echo '&amp;editor_image_class=' . htmlspecialchars( urlencode(PREFS_EDITOR_IMAGE_CLASS) );
	}
	if (PREFS_CAPTCHA == 'yes') {
			echo '&amp;pub=' . htmlspecialchars( urlencode(PREFS_RECAPTCHA_PUBLIC_KEY) );
			echo '&amp;captcha=yes';
	} else {
		echo '&amp;captcha=no';
	} ?>" charset="UTF-8"></script>
	<?php if (PREFS_CAPTCHA == 'yes') { ?>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/reCaptchaEvents.js" charset="UTF-8"></script>
	<?php }
	if ( (isset($_COOKIE['pfx_login'])) && ($_COOKIE['pfx_login']) && (PREFS_JQUERY == 'yes') ) {
		if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') ) { ?>
    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/ckeditor/ckeditor.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/editor-plugins/codemirror/js/codemirror.js" charset="UTF-8"></script>
	<?php } else { if ($GLOBALS['pfx_user_privs'] >= 2) { ?>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/editor-plugins/codemirror/js/codemirror.js" charset="UTF-8"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/nicEdit/nicEdit.js" charset="UTF-8"></script>
<?php } ?>
    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/tags.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/simplemodal.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/jqueryForm.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/validate.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/pfx.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/selectbox.js" charset="UTF-8"></script>
	    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/liveEdit.js" charset="UTF-8"></script>
	<?php
		# load theme javascripts: 
		if(is_dir($pfx_rel_path."admin/themes/".PREFS_SITE_THEME."/js")){
			if($handle=opendir($pfx_rel_path."admin/themes/".PREFS_SITE_THEME."/js")){
				while (false !== ($file = readdir($handle))) { ?>
		<script type="text/javascript" src="<?php echo $pfx_rel_path."admin/themes/".PREFS_SITE_THEME."/js".$file;?>" charset="UTF-8"></script>
     			<?php 
				}
				closedir($handle);
			}
		
    
		}
	}
	return TRUE;
    } /* End don't load if 404 */

}

function theme_lightbox($pfx_rel_path, $pfx_in) {

    if ( (PREFS_LIGHTBOX == 'yes') && ($pfx_in == 'js') ) { ?>
    <script type="text/javascript" src="<?php echo $pfx_rel_path; ?>admin/jscript/lightBoxEvents.js" charset="UTF-8"></script>
    <?php }
    if ( (PREFS_LIGHTBOX == 'yes') && ($pfx_in == 'css') ) { ?>
	<link rel="stylesheet" href="<?php echo $pfx_rel_path; ?>admin/admin/theme/lightbox.css" type="text/css" media="screen" />
    <?php }

}

function theme_live_edit($pfx_rel_path) {

	if ( (isset($_COOKIE['pfx_login'])) && ($_COOKIE['pfx_login']) && (PREFS_JQUERY == 'yes') ) { ?>
<link rel="stylesheet" href="<?php echo $pfx_rel_path; ?>admin/admin/theme/live-edit.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $pfx_rel_path; ?>admin/admin/theme/selectbox.css" type="text/css" media="screen" />
<?php }

}

function bad_bot_link() {

    if ( file_exists('admin/modules/deny.php') ) {
	    echo '<a class="hide" href="' . PREFS_SITE_URL .'bad.php" title="Do not click">Clicking on this link will get you banned</a>';
    }
}
?>
