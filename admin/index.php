<?php
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
 * Title: Admin Index
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
if ((defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG'))) {
	require_once 'lib/lib_misc.php';
	exit(pfxExit());
}
/* Prevent any kind of predefinition of DIRECT_ACCESS or pfx_DEBUG */
define('DIRECT_ACCESS', 1);
if ( (defined('CONFIG_COMMON')) ) {
} else {
	define('CONFIG_COMMON', 'admin');
}
require_once 'lib/lib_common.php';
header('Content-Type: text/html; charset=' . PFX_CHARSET);
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

	<?php echo PFX_LICENSE; ?>
<head>
	
	<!-- meta tags -->
	<meta http-equiv="content-type" content="text/html;charset=<?php echo PFX_CHARSET; ?>" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="X-UA-Compatible" content="IE=7,chrome=1" />
	<meta name="googlebot" content="noindex" />
	<meta name="title" content="The PFX admin area." />
	<meta name="description" content="PFX is an open source web application that will help you quickly create and maintain your own website. PFX is available at heydojo.co.cc." />
	<meta name="keywords" content="pfx,admin,login" />
	<meta name="author" content="<?php echo PREFS_SITE_AUTHOR; ?>" />
	<meta name="copyright" content="<?php echo PREFS_SITE_COPYRIGHT; ?>" />
	<meta name="generator" content="PFX <?php echo PREFS_VERSION; ?> - Copyright (C) <?php echo date('Y'); ?>." />

	<!-- title -->
	<title><?php build_admin_title($pfx_lang, $pfx_do, $pfx_s, $pfx_m, $pfx_x); ?></title>

	<!-- css -->
	<link rel="stylesheet" href="admin/theme/reset-min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="admin/theme/core.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="admin/theme/layout.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="admin/theme/cskin.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="admin/theme/lightbox.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="admin/theme/selectbox.css" type="text/css" media="screen" />
	<?php if ( (isset($pfx_ck)) && ($pfx_ck == 1) ) { ?>
	<link rel="stylesheet" href="admin/theme/filemanager.css" type="text/css" media="screen" />
	<?php } ?>

	<!-- site icon -->
	<link rel="SHORTCUT ICON" type="image/vnd.microsoft.icon" href="admin/theme/images/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php echo PREFS_SITE_URL; ?>files/images/apple_touch_icon.png"/>

	<!-- rss feeds-->
	<link rel="alternate" type="application/rss+xml" title="PFX - <?php echo str_replace('.', "", $pfx_lang['blog']); ?>" href="http://feeds.feedburner.com/heydojo-blog-rss" />
	<?php if (isset($pfx_GLOBALS['pfx_user'])) { ?>
	<link rel="alternate" type="application/rss+xml" title="PFX - <?php echo $pfx_lang['latest_activity']; ?>" href="?s=myaccount&amp;do=rss&amp;user=<?php echo safe_field('nonce', 'pfx_users', "user_name ='{$pfx_GLOBALS['pfx_user']}'"); ?>" />
	<?php } ?>
	</head>
<?php if (PREFS_GZIP == 'yes') { @ob_flush(); }
flush(); /* Send the head so that the browser has something to do whilst it waits */
?>
<body class="pfx">
	    <div id="header">
	    <!-- header start -->
		<div id="headerWrap">
		    <div id="roundContent4" class="roundHeader">
			<div class="boxContent">
			    <div id="top">
				<div id="title-wrapper">
				    <h1 id="pfx_title" title="PFX"><span><a href="<?php echo PREFS_SITE_URL; ?>admin/" rel="home">PFX</a></span></h1>
					<h2 id="pfx_strapline" title="<?php echo 'v' . PREFS_VERSION . " - {$pfx_lang['tag_line']}"; ?>"><span><?php echo 'v' . PREFS_VERSION . " - {$pfx_lang['tag_line']}"; ?></span></h2>
				</div>
				<div id="ajaxHeader2">
				    <div id="ajaxHeader1">
<div class="nav-list">
			    <div id="tools">
				<ul id="tools_list">
						<li id="tool_skip">
						    <a href="#content" title="<?php echo $pfx_lang['skip_to']; ?>"><?php echo $pfx_lang['skip_to']; ?></a>
						</li>
<?php if ( isset($pfx_s) ) {
	if ( isset($GLOBALS['pfx_user']) ) {
	    if ($pfx_s != 'login') {
?>
						<li id="tool_logout"><a href="?s=logout" title="<?php echo $pfx_lang['logout']; ?>"><?php echo $pfx_lang['logout']; ?></a>
						</li>
<?php }
echo "\n";
	}
	    }
?>
						<li id="tool_view"><a rel="nofollow" href="<?php echo PREFS_SITE_URL; ?>" title="<?php echo $pfx_lang['view_site']; ?>"><?php echo $pfx_lang['view_site']; ?></a></li>
				</ul>
			    </div>
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
<?php echo "\n";
	if (isset($pfx_s)) {
		if ($pfx_s != 'login') {
?>
						<?php
			if ($pfx_s != '404') {
			    if ( ($pfx_s == 'publish') or ($pfx_s == 'myaccount') or ($pfx_s == 'settings') ) {
				include_once "admin/modules/nav_{$pfx_s}.php";
			    }
			}
		}
	}
?>
				</div>
	    </div>
	    <div class="colmask rightmenu">
		<div class="colleft">
		    <div class="col1wrap">
			<div class="col1">
			<!-- Column 1 start -->
			    <div id="mainContent">
				<div id="ajaxMessage">
				</div>
				<div id="ajaxLoading">
				    <div id="ajaxTimer">
				    </div>
				</div>
				<b class="roundTop"><b class="e1"></b><b class="e2"></b><b class="e3"></b><b class="e4"></b></b>
				<div id="contentWrap">
				    <div id="roundContent3" class="roundContentMain">
					<div id="wrapEverything" class="boxContent">
					    <div id="pfx_body">
						<div id="ajaxContent2">
						    <div id="ajaxContent1">
							<div id="content">
							    <?php ie6_check($pfx_lang['ie6_warn']); ?>
								<noscript>
								    <p>
									<label class="error">
									    <?php echo PREFS_SITE_NAME . " {$pfx_lang['js_warn']}"; ?>
									</label>
								    </p>
								</noscript>
							    <div id="message-wrap">
								<div id="message-pad">
								    <div id="message"></div>
								</div>
							    </div>
<?php if ( (isset($pfx_s)) && ($pfx_s != '404') ) {
		include "admin/modules/mod_{$pfx_s}.php";
	} else {
		include 'modules/static.php';
	}
?>
							</div>
						    </div>
						</div>
					    </div>
					    <div class="clearThis">
					    </div>
					    <div id="copyRight" class="small">
				<ul id="pfx-credits">
					<li id="cred_pfx"><a href="http://heydojo.co.cc/" title="Get PFX" target="_blank">PFX Powered.</a></li>
					<li id="cred_licence"><?php echo $pfx_lang['license']; ?> <a href="<?php echo PREFS_SITE_URL . 'license.txt'; ?>" title="<?php echo $pfx_lang['license']; ?> GNU General Public License v3" rel="license" target="_blank">GNU General Public License v3</a>.</li>
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
				<ul id="credits_list">
				    <li id="topLinkFunction">
					<a title="Return to the top of this page" class="toTop" href="#top">Up</a>
				    </li>
				    <li id="cred_site"><a href="<?php echo PREFS_SITE_URL; ?>" title="<?php echo $pfx_lang['view_site']; ?>" target="_blank"><?php echo strtolower(str_replace('http://', "", PREFS_SITE_URL)); ?></a></li>
				</ul>
			    </div>
			</div>
		    </div>
		</div>
	    </div>

	<?php if ( (isset($pfx_message)) && ($pfx_message) ) {
		echo '<div class="sys-message">';
		if ( (isset($pfx_error)) && ($pfx_error) ) {
			echo '<span class="message_text_error"><img alt="error" src="admin/theme/images/icons/error.png" />';
			echo "{$pfx_message}</span>";
			echo '<span class="message_back"> (<a href="javascript:history.go(-1);" title="Back (Will reload any submitted form data)">Go back &raquo;</a>)</span>';
		} else {
			echo "<span>{$pfx_message}</span>";
		}
		echo '</div>';
	} else if ( (isset($pfx_messageok)) && ($pfx_messageok) ) {
		echo "<div class=\"sys-message\"><span class=\"message_text_ok\"><img alt=\"OK\" src=\"admin/theme/images/icons/tick.png\" />{$pfx_messageok}</span></div>";
	} ?>

    <!-- JavaScript includes are placed after the content at the very bottom of the page, just before the closing body tag. -->
      <!-- This ensures that all content is loaded before manipulation of the DOM occurs. -->
	<!-- javascript -->
<?php if (PREFS_GZIP == 'yes') { @ob_flush(); }
flush();
/* Send most of the body so that the browser has something to do whilst it waits */
      /* Use jQuery from googleapis */
      if (PREFS_JQUERY_G_APIS == 'yes') {
	if ( url_exist(PREFS_G_JQUERY_LOC) ) { ?>
	    <script type="text/javascript" src="<?php echo PREFS_G_JQUERY_LOC; ?>" charset="UTF-8"></script>
	  <?php } else { ?>
		    <script type="text/javascript" src="jscript/jquery.js" charset="UTF-8"></script>
	  <?php } /* End if url exists */
	} else { ?>
	  <script type="text/javascript" src="jscript/jquery.js" charset="UTF-8"></script>
  <?php } /* End does not equal yes */ ?>
	<script type="text/javascript" src="jscript/jsVars.php?<?php
	echo 'action_type=admin';
	if (isset($pfx_s)) {
		echo "&amp;s={$pfx_s}";
	}
	if (isset($pfx_m)) {
		echo "&amp;m={$pfx_m}";
	}
	if (isset($pfx_x)) {
		echo "&amp;x={$pfx_x}";
	}
	if (isset($pfx_p)) {
		echo "&amp;p={$pfx_p}";
	}
	if (defined('PREFS_SITE_URL')) {
		echo '&amp;site_url=' . htmlspecialchars( urlencode(PREFS_SITE_URL) );
	}
	if (defined('PREFS_EDITOR_ENTER_MODE')) {
		echo '&amp;editor_enter_mode=' . htmlspecialchars( urlencode(PREFS_EDITOR_ENTER_MODE) );
	}
	if (defined('PREFS_EDITOR_IMAGE_CLASS')) {
		echo '&amp;editor_image_class=' . htmlspecialchars( urlencode(PREFS_EDITOR_IMAGE_CLASS) );
	}
	if (isset($pfx_scroll)) {
		echo '&amp;scroll=' . urlencode($pfx_scroll);
	}
	if (isset($pfx_ckFuncNumReturn)) {
		echo '&amp;ckFuncNumReturn=' . htmlspecialchars( urlencode($pfx_ckFuncNumReturn) );
	}
?>" charset="UTF-8"></script>
<?php if ( (isset($pfx_s)) && ($pfx_s != 'login') && ($pfx_s == 'publish') or ($pfx_s == 'settings') or ($pfx_x == 'myprofile') ) {
if ( (PREFS_RICH_TEXT_EDITOR == 1) && ($GLOBALS['rte_user'] == 'yes') ) { ?>
<script type="text/javascript" src="jscript/ckeditor/ckeditor.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/editor-plugins/codemirror/js/codemirror.js" charset="UTF-8"></script>
<?php } else { if ( (isset($GLOBALS['pfx_user'])) && (isset($GLOBALS['pfx_user_privs'])) && ($GLOBALS['pfx_user_privs'] >= 2) ) { ?>
<script type="text/javascript" src="jscript/editor-plugins/codemirror/js/codemirror.js" charset="UTF-8"></script>
<?php } ?>
<script type="text/javascript" src="jscript/nicEdit/nicEdit.js" charset="UTF-8"></script>
<?php } ?>
<script type="text/javascript" src="jscript/admin/ajaxfileupload.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/admin/upload.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/admin/mousewheel.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/admin/jcarousellite.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/admin/tooltip.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/tags.js" charset="UTF-8"></script>
<?php } ?>
<script type="text/javascript" src="jscript/easing.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/simplemodal.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/admin/sortable.js" charset="UTF-8"></script>
<?php if ( (isset($pfx_s)) && ($pfx_s != 'login') ) { ?>
<script type="text/javascript" src="jscript/pfx.js" charset="UTF-8"></script>
<?php } ?>
<script type="text/javascript" src="jscript/admin/admin.js" charset="UTF-8"></script>
<?php if ( (isset($pfx_s)) && ($pfx_s != 'login') && ($pfx_s != 'myaccount') ) { ?>
<script type="text/javascript" src="jscript/selectEvents.js" charset="UTF-8"></script>
<script type="text/javascript" src="jscript/lightBoxEvents.js" charset="UTF-8"></script>
<?php } ?>
	<!-- bad behavior -->
<?php bb2_insert_head('admin', NULL); ?>
	    </body>
	</html>
	<!--
	Page generated in: <?php pagetime('print'); ?>
	-->
<?php if (PREFS_GZIP == 'yes') { @ob_end_flush(); }