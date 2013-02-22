<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

	<!--
	Pixie Powered (www.getpixie.co.uk)
	Licence: GNU General Public License v3
	Copyright (C) <?php
		print date('Y');
?>, Scott Evans

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

	www.getpixie.co.uk
	-->

	<!-- Meta tags -->
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<meta name="keywords" content="<?php
		print $site_keywords;
?>" />
	<meta name="description" content="<?php
		if (isset($pinfo)) {
			print strip_tags($pinfo);
		} else {
			print strip_tags($page_description);
		}
?>" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="7 days" />
	<meta name="author" content="<?php
		print $site_author;
?>" />
	<meta name="copyright" content="<?php
		print $site_copyright;
?>" />
	<meta name="generator" content="Pixie <?php
		print $version;
?> - Copyright (C) 2006 - <?php
		print date('Y');
?>." />

	<!-- Title -->
	<title><?php
		if ((isset($ptitle)) && ($ptitle)) {
			echo $ptitle;
		} else {
			build_title();
		}
?></title>

	<?php
		if (!isset($theme_g_apis_jquery)) {
			$theme_g_apis_jquery = 'no';
		}
		if (!isset($theme_g_apis_jquery_loc)) {
			$theme_g_apis_jquery_loc = NULL;
		}
		if (!isset($theme_swfobject_g_apis)) {
			$theme_swfobject_g_apis = 'no';
		}
		if (!isset($theme_swfobject_g_apis_loc)) {
			$theme_swfobject_g_apis_loc = NULL;
		}
?>
	<?php
		/* Chain stuff to load by condition, either yes or no */
		if ($theme_g_apis_jquery == 'yes') {
			$jquery = 'yes';
		}
?>
	<!--Cufon to make the h1 look nice -->
	<script type="text/javascript" src="<?php print $rel_path; ?>admin/themes/<?php print $site_theme; ?>/js/cufon-yui.js"></script>
	<script type="text/javascript" src="<?php print $rel_path; ?>admin/themes/<?php print $site_theme; ?>/js/sansation_400.font.js"></script>
    <script type="text/javascript">
		Cufon.replace('h1');
		Cufon.replace('h2');
		Cufon.replace('h4');
	</script>

	<!-- Javascript -->
	<?php
		if ($jquery == 'yes') {
?>
	<?php
			if ($theme_g_apis_jquery == 'yes') {
				/* Use jQuery from googleapis */
?>
	<script type="text/javascript" src="<?php
				print $theme_g_apis_jquery_loc;
?>"></script>
	<?php
			} else {
?>
	<script type="text/javascript" src="<?php
				print $rel_path;
?>admin/jscript/jquery.js"></script>
	<?php
			}
			/* End Use jQuery from googleapis */
?>
	<!-- heise socialshareprivacy for jQuery -->
  <script type="text/javascript" src="admin/socialshareprivacy/jquery.socialshareprivacy.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function($){
      if($('#socialshareprivacy').length > 0){
        $('#socialshareprivacy').socialSharePrivacy({
			services : {
				facebook: {
					'action': 'like'
				}
			},
			'css_path': 'admin/socialshareprivacy/socialshareprivacy.css'
		}); 
      }
    });
  </script>
  <!-- end heise -->
	<?php
			if ($theme_swfobject_g_apis == 'yes') {
				/* Use swfobject from googleapis */
?>
	<script type="text/javascript" src="<?php
				print $theme_swfobject_g_apis_loc;
?>"></script>
	<?php
			} else {
?>
	<script type="text/javascript" src="<?php
				print $rel_path;
?>admin/jscript/swfobject.js"></script>
	<?php
			}
			/* End Use swfobject from googleapis */
?>
	<script type="text/javascript" src="<?php
			print $rel_path;
?>admin/jscript/public.js.php<?php
			if (isset($s)) {
				print "?s={$s}";
			}
?>"></script>
	<?php
		}
		/* End jquery = yes */
?>
	<!-- Bad Behavior -->
	<?php
		bb2_insert_head();
?>

	<!-- CSS -->
	<link rel="stylesheet" href="<?php
		print $rel_path;
?>admin/themes/style.php?theme=<?php
		print $site_theme;
?>&amp;s=<?php
		print $style;
?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php
		print $rel_path;
?>admin/themes/<?php
		print $site_theme;
?>/print.css" type="text/css" media="print" />
	<?php
		/* Check for IE specific style files */
		$cssie  = "{$rel_path}admin/themes/{$site_theme}/ie.css";
		$cssie6 = "{$rel_path}admin/themes/{$site_theme}/ie6.css";
		$cssie7 = "{$rel_path}admin/themes/{$site_theme}/ie7.css";
		if (file_exists($cssie)) {
			echo "\n\t<!--[if IE]><link href=\"{$cssie}\" type=\"text/css\" rel=\"stylesheet\" media=\"screen\" /><![endif]-->\n";
		}
		if (file_exists($cssie6)) {
			echo "\n\t<!--[if IE 6]><link href=\"{$cssie6}\" type=\"text/css\" rel=\"stylesheet\" media=\"screen\" /><![endif]-->\n";
		}
		if (file_exists($cssie7)) {
			echo "\n\t<!--[if IE 7]><link href=\"{$cssie7}\" type=\"text/css\" rel=\"stylesheet\" media=\"screen\" /><![endif]-->\n";
		}
		/* Check for handheld style file */
		$csshandheld = "{$rel_path}admin/themes/{$site_theme}/handheld.css";
		if (file_exists($csshandheld)) {
			echo "\n\t<link href=\"{$csshandheld}\" rel=\"stylesheet\" media=\"handheld\" />\n";
		}
?>

	<!-- Site icons-->
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php
		print $rel_path;
?>admin/themes/<?php
		print $site_theme;
?>/favicon.ico" />
	<link rel="apple-touch-icon" href="<?php
		print $site_url;
?>files/images/apple_touch_icon.jpg"/>

	<!-- RSS feeds-->
	<?php
		build_rss();
?>
	<?php
		$do = 'head';
		if (($page_type == 'module') && (isset($s))) {
			include("admin/modules/{$s}.php");
		}
?>

</head>

<?php
		if ($gzip_theme_output == 'yes') {
			@ob_flush();
		}
		flush();
		/* Send the head so that the browser has something to do whilst it waits */
?>

<body id="pixie" class="pixie <?php
		$date_array = getdate();
		print "y{$date_array['year']}";
		print " m{$date_array['mon']}";
		print " d{$date_array['mday']}";
		print " h{$date_array['hours']}";
		if ((isset($s)) && ($s)) {
			print " s_{$s}";
		}
		if ($m) {
			print " m_{$m}";
		}
		if ($x) {
			print " x_{$x}";
		}
		if ($p) {
			print " p_{$p}";
		}
?>">

	<?php
		build_head();
?>

	<div id="wrap" class="hfeed">
		<div id="placeholder">
			<div id="header">
            <div id="logo">
<a href="<?php print $site_url;?>" rel="home" title="<?php print $site_name;?>">
<h1><?php print $site_name;?></h1>
<h2><?php print $page_description; ?></h2>
</a></div> 

				<div id="tools">
					<ul id="tools_list">
						<li id="tool_skip_navigation"><a href="#navigation" title="<?php
		print $lang['skip_to_nav'];
?>"><?php
		print $lang['skip_to_nav'];
?></a></li>
						<li id="tool_skip_content"><a href="#content" title="<?php
		print $lang['skip_to_content'];
?>"><?php
		print $lang['skip_to_content'];
?></a></li>
					</ul>
				</div>

				<h1 id="site_title" title="<?php
		print $site_name;
?>"><a href="<?php
		print $site_url;
?>" rel="home" class="replace"><?php
		print $site_name;
?><span></span></a></h1>
				<h2 id="site_strapline" title="<?php
		if (isset($pinfo)) {
			print strip_tags($pinfo);
		} else {
			print strip_tags($page_description);
		}
?>" class="replace"><?php
		if (isset($pinfo)) {
			print strip_tags($pinfo);
		} else {
			print strip_tags($page_description);
		}
?><span></span></h2>

			</div>

			<div id="navigation"><?php
		build_navigation();
?></div>

			<div id="pixie_body">

				<?php
		if ($contentfirst == 'no') {
			echo "\n";
?>
				<div id="content_blocks"><?php
			build_blocks();
?></div>
				<?php
		}
		echo "\n";
?>

				<div id="content"><?php
		$do = 'default';
		if ($page_type == 'static') {
			include('admin/modules/static.php');
		} else if ($page_type == 'dynamic') {
			include('admin/modules/dynamic.php');
		} else {
			if (isset($s)) {
				include("admin/modules/{$s}.php");
			}
		}
?></div>

				<?php
		if ($contentfirst == 'yes') {
			echo "\n";
?>
				<div id="content_blocks"><?php
			build_blocks();
?></div>
				<?php
		}
		echo "\n";
?>

			</div>

		</div>

		<div id="footer">
			<div id="credits">
				<ul id="credits_list">

					<?php
		if (public_page_exists('rss')) {
			echo "<li id=\"cred_rss\"><a href=\"" . createURL('rss') . "\" title=\"Subscribe\">Subscribe</a></li>\n";
		} else {
			echo "\n";
		}
?>
					<!-- The attribution at the bottom of every page -->
					<li id="cred_pixie"><?php print $site_name;?> is <a href="http://www.getpixie.co.uk" title="Get Pixie">Pixie Powered</a></li>
					<li id="cred_theme"><?php
		print $lang['theme'];
		print " : {$theme_name}";
?> by <a href="<?php
		print $theme_link;
?>" title="<?php
		print "{$theme_name} by {$theme_creator}";
?>"><?php
		print $theme_creator;
?></a></li>
					<li> <a href="admin/">admin</a>
				</ul>
			</div>
		</div>

		<!-- Use for extra style as required -->
		<div id="extradiv_c"><span></span></div>
		<div id="extradiv_d"><span></span></div>

	</div>
    <script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
