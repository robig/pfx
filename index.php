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
 * Title: Index
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
ini_set('error_log', "/home/robig/web/testing/pfx/log/error_log");
error_reporting(E_ALL|E_STRICT);
/* Prevent any kind of predefinition of DIRECT_ACCESS or pfx_DEBUG */
if ((defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG'))) {
	require_once 'admin/lib/lib_misc.php';
	exit(pfxExit());
}
/* 1 for yes */
	define('DIRECT_ACCESS', 1);
if ( (defined('CONFIG_COMMON')) ) {
} else {
	define('CONFIG_COMMON', 'index');
}
require_once 'admin/lib/lib_theme.php';
require_once 'admin/lib/lib_common.php';
header('Content-Type: text/html; charset=' . PFX_CHARSET);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<?php echo PFX_LICENSE; ?>
<head>
	
	<!-- meta tags -->

<?php theme_meta($pfx_ptitle, $pfx_page_description, $pfx_pinfo); ?>

	<!-- title -->
	<title><?php echo $pfx_ptitle; ?></title>
	<link rel="home" href="<?php echo $pfx_rel_path; ?>" />

	<!-- site icons-->
	<link rel="SHORTCUT ICON" type="image/vnd.microsoft.icon" href="<?php echo $pfx_rel_path; ?>admin/themes/<?php echo PREFS_SITE_THEME; ?>/images/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php echo $pfx_rel_path; ?>admin/themes/<?php echo PREFS_SITE_THEME; ?>/images/apple_touch_icon.png" />

	<!-- css -->
	<link rel="stylesheet" href="<?php echo $pfx_rel_path; ?>admin/admin/theme/reset-min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $pfx_rel_path; ?>admin/themes/style.php?theme=<?php echo PREFS_SITE_THEME; ?>&amp;s=<?php echo $pfx_style; ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $pfx_rel_path; ?>admin/themes/<?php echo PREFS_SITE_THEME; ?>/print.css" type="text/css" media="print" />
	<?php theme_live_edit($pfx_rel_path); ?>
	<?php theme_lightbox($pfx_rel_path, 'css'); ?>
	<?php $pfx_do = 'head'; if ( ($pfx_page_type == 'module') && (isset($pfx_s)) ) { include "admin/modules/{$pfx_s}.php"; } ?>


	<!-- rss feeds-->
	<?php build_rss(); ?>

	</head>
	<?php
if (PREFS_GZIP == 'yes') {
	@ob_flush();
}
flush();
/* Send the head so that the browser has something to do whilst it waits */
?>

	<body id="pfx" class="main">
	<?php build_head($pfx_lang); ?>
    <div id="header">
	    <!-- header start -->
		<div id="headerWrap">
		    <div id="roundContent4" class="roundHeader">
			<div class="boxContent">
			  <?php theme_tools($pfx_lang['skip_to_nav'], $pfx_lang['skip_to_content']); ?>
			    <div id="top">
				<div id="title-wrapper">
				    <h1 id="site_title" title="<?php echo PREFS_SITE_NAME; ?>">
					<a href="<?php echo PREFS_SITE_URL; ?>" rel="home" class="replace"><?php echo PREFS_SITE_NAME; ?></a>
				    </h1>
				</div>
				<div id="ajaxHeader2">
				    <div id="ajaxHeader1">
					<?php theme_strapline($pfx_page_description, $pfx_pinfo); ?>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		</div>
	    <!-- header end -->
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
				<div id="contentWrap">
				    <div id="roundContent3" class="roundContentMain">
					<div id="wrapEverything" class="boxContent">
					    <div id="pfx_body">
						<div id="searchresults">
						</div>
						<div id="ajaxContent2">
						    <div id="ajaxContent1">
							<div id="content">
							    <?php ie6_check($pfx_lang['ie6_warn']);
								  $pfx_do = 'default';
								  if ($pfx_page_type == 'static') {
									  include 'admin/modules/static.php';
								  } else if ($pfx_page_type == 'dynamic') {
									  include 'admin/modules/dynamic.php';
								  } else {
									  if (isset($pfx_s)) {
										  include "admin/modules/{$pfx_s}.php";
									  }
								  } ?>
							</div>
						    </div>
						</div>
					    </div>
					    <div id="returnLink" class="hide">
						<?php echo 'You can return to the homepage by clicking '; ?><a id="return" href="<?php echo PREFS_SITE_URL; ?>" title="Visit the homepage">here</a>.
					    </div>
					    <div class="clearThis">
					    </div>
					</div>
				    </div>
				</div>
			    </div>
			<!-- Column 1 end -->
			</div>
		    </div>
		    <div class="col2">
		    <!-- Column 2 start -->
			<div id="navigation">
			    <div id="navWrap">
				<div id="roundContent1" class="roundContent">
				    <div class="boxContent">
					<div id="ajaxNavigation2">
					    <div id="ajaxNavigation1">
						<div class="nav-list">
							<?php 	if ( (isset($pfx_nested_nav)) && ($pfx_nested_nav) ) {
								} else {
									$pfx_nested_nav = NULL;
								}
								build_navigation($pfx_lang, $pfx_nested_nav, $pfx_s);
							?>
						</div>
						<div id="content_blocks">
						    <?php build_blocks($pfx_lang, $pfx_page_blocks, $pfx_s, $pfx_m, $pfx_x); ?>
						</div>
					    </div>
					</div>
				    </div>
				</div>
			    </div>
			</div>
		    <!-- Column 2 end -->
		    </div>
		</div>
	    </div>
	    <div id="footer">
		<div id="externalLinksMarker">
		</div>
		<div id="footerWrap">
		    <div id="roundContent2" class="roundContentFooter">
			<div class="boxContent">
			    <div id="credits">
				<ul id="credits_list">
				    <?php theme_subscribe(); theme_legal(); ?>
				    <li id="cred_pfx"><?php if (PREFS_VALID_CSS_XHTML == 'yes') { echo 'Valid '; } pfx_link_footer();
	      if (PREFS_VALID_CSS_XHTML == 'yes') { ?>
 <a rel="nofollow" id="xhtmlPowered" href="http://validator.w3.org/check/referer">XHTML</a> &amp; <a rel="nofollow" id="cssPowered" href="http://jigsaw.w3.org/css-validator/validator?uri=<?php echo urlencode(PREFS_SITE_URL . 'admin/themes/'. PREFS_SITE_THEME . '/layout.css'); ?>&amp;profile=css3&amp;usermedium=all&amp;warning=1">CSS</a><?php
	      } ?></li>
	<?php if (PREFS_JQUERY == 'yes') { ?>
			    <li id="cred_jQuery">Enhanced With <a id="jqueryPowered" href="http://jquery.com/">jQuery</a></li>
	<?php } ?>
			    <li id="cred_theme"><?php echo "{$pfx_lang['theme']} &#58; {$pfx_theme_name} by <a href=\"{$pfx_theme_link}\" title=\"{$pfx_theme_name} by {$pfx_theme_creator}\">{$pfx_theme_creator}</a>"; ?></li>
				</ul>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	<?php
if (PREFS_GZIP == 'yes') {
	@ob_flush();
}
flush();
/* Send most of the body so that the browser has something to do whilst it waits */
?>
    <!-- JavaScript includes are placed after the content at the very bottom of the page, just before the closing body tag. -->
	    <!-- This ensures that all content is loaded before manipulation of the DOM occurs. -->
<?php
theme_javascript($pfx_s, $pfx_rel_path);
/* lightbox jquery plugin */
theme_lightbox($pfx_rel_path, 'js');
/* Module js */
if (defined('THEME_JS')) { echo THEME_JS; }
/* bad behavior */
bb2_insert_head('index', $pfx_rel_path);
bad_bot_link(); ?>


    </body>

</html>
	<!--
	Page generated in: <?php pagetime('print'); ?>

	-->
	<?php
if (PREFS_GZIP == 'yes') {
	@ob_end_flush();
}
flush();
include_once 'admin/lib/lib_cron.php';
