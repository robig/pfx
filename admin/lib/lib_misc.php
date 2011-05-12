<?php
if (!defined('DIRECT_ACCESS')) {
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
 * Title: lib_misc
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
// ------------------------------------------------------------------
/* Set up debugging */
// ------------------------------------------------------------------
if ( defined('PFX_DEBUG') ) {
	exit( pfxExit() );
}
define('PFX_DEBUG', 'no');
/* Set debug to yes to log errors */
// ------------------------------------------------------------------
/* An exit on error function */
// ------------------------------------------------------------------
function pfxExit() {
if (defined('PFX_CHARSET')) {
	$pfx_db_charset = PFX_CHARSET;
} else {
	$pfx_db_charset = 'utf-8';
}
header('Status: 503 Service Unavailable');  /* 503 status might discourage search engines from indexing or caching the error message */

		return <<<eod
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
<head>
<meta http-equiv=\"content-type\" content=\"text/html; charset={$pfx_db_charset}\" />
<title>PFX (heydojo.co.cc) - Security Warning</title>
<style type=\"text/css\">
body{font-family:Arial,'Lucida Grande',Verdana,Sans-Serif;color:#333}
a, a:visited{text-decoration:none;color:#0497d3}
a:hover{color:#191919;text-decoration:none}
.helper{position:relative;top:60px;border:5px solid #e1e1e1;clear:left;padding:15px 30px;margin:0 auto;background-color:#F0F0F0;width:500px;line-height:15pt}
</style>
</head>
<body>
<div class=\"helper\"><h3>Security Warning</h3>
<p><a href=\"http://heydojo.co.cc\" alt=\"Get PFX!\">PFX</a> has blocked your request to this site due to security concerns. The site administrator has been notified of your details. Please try to visit this site again later if you have recieved this message in error.</p>
</div>
</body>
</html>
eod;

}
// ------------------------------------------------------------------
/* Protection against those who'd bomb the site by GET */
// ------------------------------------------------------------------
function bombShelter($pfx_url_length = 260)
{
	$pfx_in = serverset( 'REQUEST_URI' );
	$pfx_ip = $_SERVER['REMOTE_ADDR'];
	
	if ( strlen( $pfx_in ) > $pfx_url_length ) {
		pfxExit();
	}

// MIGHT NEED TO USE THIS IF get_magic_quotes_gpc IS ON AND IT SCREWS WITH PFX'S INPUT
/*	if (get_magic_quotes_gpc()) {
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				}
			}
		}
		unset($process);
	}*/
}
//-------------------------------------------------------------------
/* Sterilise urls */
//-------------------------------------------------------------------
function sterilise_url( $pfx_url ) {

	/* preg_replace( '/[^a-zA-Z0-9]/', '', $pfx_url ); */ /* OLD WAY */
	filter_var($pfx_url,  FILTER_SANITIZE_URL); /* NEW WAY USING PHP'S FILTERS */
	return $pfx_url;
	
}
//-------------------------------------------------------------------
/* Don't call sterilise unless necessary */
//-------------------------------------------------------------------
function sterilise_txt( $pfx_txt, $pfx_is_sql = FALSE ) {

	if ( !preg_match( '/^[a-zA-ZÀÁÂÃÄÅĀĄĂÆÇĆČĈĊĎĐÐÈÉÊËĒĘĚĔĖĜĞĠĢĤĦÌÍÎÏĪĨĬĮİĲĴĶŁĽĹĻĿÑŃŇŅŊÒÓÔÕÖØŌŐŎŒŔŘŖŚŠŞŜȘŤŢŦȚÙÚÛÜŪŮŰŬŨŲŴÝŶŸŹŽŻÞÞàáâãäåāąăæçćčĉċďđðèéêëēęěĕėƒĝğġģĥħìíîïīĩĭįıĳĵķĸłľĺļŀñńňņŉŋòóôõöøōőŏœŕřŗšùúûüūůűŭũųŵýÿŷžżźþßſАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыэюя0-9\_]+$/', $pfx_txt ) )
		return sterilise( $pfx_txt, $pfx_is_sql );
	
	return $pfx_txt;
	
}
//-------------------------------------------------------------------
/* Sterilise user input, security against XSS etc */
//-------------------------------------------------------------------
function sterilise( $pfx_val, $pfx_is_sql = FALSE ) {
	/* 	Remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	this prevents some character re-spacing such as <java\0script>
	note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs	 */
	
	$pfx_val = preg_replace( '/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $pfx_val );
	
	/* 	Straight replacements, the user should never need these since they're normal characters
	this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29> 	*/
	
	$pfx_search = 'abcdefghijklmnopqrstuvwxyz';
	$pfx_search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$pfx_search .= '1234567890!@#$%^&*()';
	$pfx_search .= '~`";:?+/={}[]-_|\'\\';
	$pfx_search .= 'ÀÁÂÃÄÅĀĄĂÆÇĆČĈĊĎĐÐÈÉÊËĒĘĚĔĖĜĞĠĢĤĦÌÍÎÏĪĨĬĮİĲĴĶŁĽĹĻĿÑŃŇŅŊÒÓÔÕÖØŌŐŎŒŔŘŖŚŠŞŜȘŤŢŦȚÙÚÛÜŪŮŰŬŨŲŴÝŶŸŹŽŻÞÞàáâãäåāąăæçćčĉċďđðèéêëēęěĕėƒĝğġģĥħìíîïīĩĭįıĳĵķĸłľĺļŀñńňņŉŋòóôõöøōőŏœŕřŗšùúûüūůűŭũųŵýÿŷžżźþßſАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыэюя';
	
	for ( $pfx_i = 0; $pfx_i < strlen( $pfx_search ); $pfx_i++ ) {
		/* 	;? matches the ;, which is optional
		0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		
		&#x0040 @ search for the hex values 	*/
		
		$pfx_val = preg_replace( '/(&#[xX]0{0,8}' . dechex( ord( $pfx_search[$pfx_i] ) ) . ';?)/i', $pfx_search[$pfx_i], $pfx_val );
		/* With a ; */
		
		/* &#00064 @ 0{0,7} matches '0' zero to seven times */
		
		$pfx_val = preg_replace( '/(&#0{0,8}' . ord( $pfx_search[$pfx_i] ) . ';?)/', $pfx_search[$pfx_i], $pfx_val );
		/* With a ; */
	}
	
	/* now the only remaining whitespace attacks are \t, \n, and \r */
	
	$pfx_ra1   = Array(
		 'javascript',
		'vbscript',
		'expression',
		'applet',
		'meta',
		'xml',
		'blink',
		'link',
		'style',
		'script',
		'embed',
		'object',
		'iframe',
		'frame',
		'frameset',
		'ilayer',
		'layer',
		'bgsound',
		'title',
		'base' 
	);
	$pfx_ra2   = Array(
		 'onabort',
		'onactivate',
		'onafterprint',
		'onafterupdate',
		'onbeforeactivate',
		'onbeforecopy',
		'onbeforecut',
		'onbeforedeactivate',
		'onbeforeeditfocus',
		'onbeforepaste',
		'onbeforeprint',
		'onbeforeunload',
		'onbeforeupdate',
		'onblur',
		'onbounce',
		'oncellchange',
		'onchange',
		'onclick',
		'oncontextmenu',
		'oncontrolselect',
		'oncopy',
		'oncut',
		'ondataavailable',
		'ondatasetchanged',
		'ondatasetcomplete',
		'ondblclick',
		'ondeactivate',
		'ondrag',
		'ondragend',
		'ondragenter',
		'ondragleave',
		'ondragover',
		'ondragstart',
		'ondrop',
		'onerror',
		'onerrorupdate',
		'onfilterchange',
		'onfinish',
		'onfocus',
		'onfocusin',
		'onfocusout',
		'onhelp',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onlayoutcomplete',
		'onload',
		'onlosecapture',
		'onmousedown',
		'onmouseenter',
		'onmouseleave',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
		'onmousewheel',
		'onmove',
		'onmoveend',
		'onmovestart',
		'onpaste',
		'onpropertychange',
		'onreadystatechange',
		'onreset',
		'onresize',
		'onresizeend',
		'onresizestart',
		'onrowenter',
		'onrowexit',
		'onrowsdelete',
		'onrowsinserted',
		'onscroll',
		'onselect',
		'onselectionchange',
		'onselectstart',
		'onstart',
		'onstop',
		'onsubmit',
		'onunload' 
	);
	$pfx_ra    = array_merge( $pfx_ra1, $pfx_ra2 );
	$pfx_found = TRUE;
	/* keep replacing as long as the previous round replaced something */
	while ( $pfx_found === TRUE ) {
		$pfx_val_before = $pfx_val;
		
		for ( $pfx_i = 0; $pfx_i < sizeof( $pfx_ra ); $pfx_i++ ) {
			$pfx_pattern = '/';
			
			for ( $pfx_j = 0; $pfx_j < strlen( $pfx_ra[$pfx_i] ); $pfx_j++ ) {
				if ( $pfx_j > 0 ) {
					$pfx_pattern .= '(';
					$pfx_pattern .= '(&#[xX]0{0,8}([9ab]);)';
					$pfx_pattern .= '|';
					$pfx_pattern .= '|(&#0{0,8}([9|10|13]);)';
					$pfx_pattern .= ')*';
				}
				$pfx_pattern .= $pfx_ra[$pfx_i][$pfx_j];
			}
			$pfx_pattern .= '/i';
			$pfx_replacement = substr( $pfx_ra[$pfx_i], 0, 2 ) . '<x>' . substr( $pfx_ra[$pfx_i], 2 );
			/* Add in <> to nerf the tag */
			$pfx_val         = preg_replace( $pfx_pattern, $pfx_replacement, $pfx_val );
			/* Filter out the hex tags */
			if ( $pfx_val_before == $pfx_val ) {
				/* No replacements were made, so exit the loop */
				$pfx_found = FALSE;
			}
		}
	}
	
	if ( $pfx_is_sql ) {
		$pfx_val = mysql_real_escape_string( $pfx_val );
	}
	
	return $pfx_val;
	
}
//-------------------------------------------------------------------
/* A function for checking if its 404 time */
//-------------------------------------------------------------------
function check_404( $pfx_section )
{
	$pfx_check = file_exists( "admin/modules/mod_{$pfx_section}.php" );
	
	if ( $pfx_check ) {
		return $pfx_section;
		
	} else {
		$pfx_section = '404';
		
		return $pfx_section;
		
	}
	
}
//-------------------------------------------------------------------
/* A Function for checking if its 404 time from public hit */
//-------------------------------------------------------------------
function public_check_404( $pfx_section )
{
	$pfx_section = str_replace( '<x>', "", $pfx_section );
	
	if ( $pfx_section === 'rss' ) {
		$pfx_check = safe_row( '*', 'pfx_core', "page_name = '{$pfx_section}' AND public = 'yes' limit 0,1" );
		
	} else {
		$pfx_check = safe_row( '*', 'pfx_core', "page_name = '{$pfx_section}' AND public = 'yes' AND page_type != 'plugin' limit 0,1" );
	}
	
	if ( $pfx_check ) {
		return $pfx_section;
		
	} else {
		$pfx_section = '404';
		
		return $pfx_section;
	}
	
}
//-------------------------------------------------------------------
/* A function for checking what type of page we are dealing with */
//-------------------------------------------------------------------
function check_type( $pfx_section )
{
	$pfx_core_page_section = safe_row( '*', 'pfx_core', "page_name = '{$pfx_section}' AND public = 'yes' limit 0,1" );
	extract($pfx_core_page_section, EXTR_PREFIX_ALL, 'pfx');
	$pfx_core_page_section = NULL;
	if ( $pfx_page_type ) {
		return $pfx_page_type;
		
	} else {
		return 'Unable to find type of page in pfx_core. Has the page been deleted?';
		
	}
	
}
//-------------------------------------------------------------------
/* A function to return current page id */
//-------------------------------------------------------------------
function get_page_id( $pfx_section ) {
	$pfx_page_id = safe_field( 'page_id', 'pfx_core', "page_name = '{$pfx_section}' AND public = 'yes' limit 0,1" );
	
	if ( $pfx_page_id ) {
		return $pfx_page_id;
		
	}
	
}
//-------------------------------------------------------------------
/* A function to check if a page is installed and public */
//-------------------------------------------------------------------
function public_page_exists($pfx_page_name) {
	$pfx_rs = safe_row( '*', 'pfx_core', "page_name = '{$pfx_page_name}' AND public = 'yes' limit 0,1" );
	
	if ( $pfx_rs ) {
		return TRUE;
		
	} else {
		return FALSE;
		
	}
	
}
//-------------------------------------------------------------------
/*  Create a clean or ugly url based on the PFX setting */
//-------------------------------------------------------------------
function createURL($pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE, $pfx_p = FALSE) {
	if ( !defined('PFX_SU') ) {
		define( 'PFX_SU', fetch('site_url', 'pfx_settings', 'settings_id', 1) );
	}
	if ( !defined('PFX_CU') ) {
		define( 'PFX_CU', fetch('clean_urls', 'pfx_settings', 'settings_id', 1) );
	}
	if ( PFX_CU === 'yes' ) {
		$pfx_return = PFX_SU . "{$pfx_s}/{$pfx_m}/{$pfx_x}/{$pfx_p}";
		$pfx_slash  = array(
			 '//',
			'///',
			'////' 
		);
		$pfx_return = str_replace( $pfx_slash, "", $pfx_return );
		$pfx_return = str_replace( 'http:', 'http://', $pfx_return );
		$pfx_last   = $pfx_return{strlen( $pfx_return ) - 1};
		
		if ( $pfx_last != '/' ) {
			$pfx_return = "{$pfx_return}/";
		}
		
		return strtolower($pfx_return);
		
	} else {
		$pfx_return = PFX_SU . "?s={$pfx_s}&m={$pfx_m}&x={$pfx_x}&p={$pfx_p}";
		$pfx_return = str_replace( '&m=&x=&p=', "", $pfx_return );
		$pfx_return = str_replace( '&x=&p=', "", $pfx_return );
		
		if ( (isset($pfx_p)) && ($pfx_p) ) {
		} else {
			$pfx_return = str_replace( '&p=', "", $pfx_return );
		}
		$pfx_return = htmlspecialchars($pfx_return, ENT_QUOTES, PFX_CHARSET);
		
		return strtolower($pfx_return);
		
	}
	
}
//-------------------------------------------------------------------
/* Get extended entry info (<!--more-->) */
//-------------------------------------------------------------------
function get_extended($pfx_post) {
	/* Match the more links */
	
	if ( preg_match( '/<!--more-->/', $pfx_post, $pfx_matches ) ) {
		list( $pfx_main, $pfx_extended ) = explode( $pfx_matches[0], $pfx_post, 2 );
		
	} else {
		$pfx_main     = $pfx_post;
		$pfx_extended = '';
	}
	
	/* Strip leading and trailing whitespace */
	
	$pfx_main     = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $pfx_main );
	$pfx_extended = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $pfx_extended );
	
	return array(
		 'main' => $pfx_main,
		'extended' => $pfx_extended 
	);
	
}
//-------------------------------------------------------------------
/* Output title of current section for admin area */
//-------------------------------------------------------------------
function build_admin_title($pfx_lang, $pfx_do = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	/* myaccount */
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'myaccount' ) ) {
		$pfx_title = "{$pfx_lang['nav1_home']} - {$pfx_lang['nav2_home']}";
	}
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'myaccount' ) && ( $pfx_x == 'myprofile' ) ) {
		$pfx_title = "{$pfx_lang['nav1_home']} - {$pfx_lang['nav2_profile']}";
	}
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'myaccount' ) && ( $pfx_x == 'myprofile' ) && ( $pfx_do == 'security' ) ) {
		$pfx_title = "{$pfx_lang['nav1_home']} - {$pfx_lang['nav2_security']}";
	}
	/* publish - (needs expanding!) */
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'publish' ) ) {
		$pfx_title = $pfx_lang['nav1_publish'];
	}
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'publish' ) && ( $pfx_x == 'filemanager' ) ) {
		$pfx_title = "{$pfx_lang['nav1_publish']} - {$pfx_lang['nav2_files']}";
	}
	/* settings - needs expanding! */
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'settings' ) ) {
		$pfx_title = $pfx_lang['nav1_settings'];
	}
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'settings' ) && ( $pfx_m == 'theme' ) ) {
		$pfx_title = "{$pfx_lang['nav1_settings']} - {$pfx_lang['nav2_theme']}";
	}
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'settings' ) && ( $pfx_m == 'users' ) ) {
		$pfx_title = "{$pfx_lang['nav1_settings']} - {$pfx_lang['nav2_users']}";
	}
	if ( ( isset( $pfx_s ) ) && ( $pfx_s == 'settings' ) && ( $pfx_x == 'dbtools' ) ) {
		$pfx_title = "{$pfx_lang['nav1_settings']} - {$pfx_lang['nav2_backup']}";
	}
	if ( ( defined( 'PREFS_VERSION' ) ) && ( isset( $pfx_title ) ) ) {
		echo 'PFX v' . PREFS_VERSION . " : {$pfx_title}";
	} else {
		echo 'PFX v' . PREFS_VERSION;
	}
	
}
//-------------------------------------------------------------------
/* Create list of blocks with form adder */
//-------------------------------------------------------------------
function form_blocks($pfx_lang, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_dir = './blocks';
	if ( ( is_dir( $pfx_dir ) ) ) {
		$pfx_fd = @opendir( $pfx_dir );
		
		if ( $pfx_fd ) {
			while ( ( $pfx_part = @readdir( $pfx_fd ) ) == TRUE ) {
				if ( ( $pfx_part != '.' ) && ( $pfx_part != '..' ) ) {
					if ( ( $pfx_part != 'index.php' ) && ( preg_match( '/^block_.*\.php$/', $pfx_part ) ) ) {
						$pfx_part = str_replace( 'block_', "", $pfx_part );
						$pfx_part = str_replace( '.php', "", $pfx_part );
						
						if ( isset( $pfx_cloud ) ) {
						} else {
							$pfx_cloud = NULL;
						}
						
						$pfx_cloud .= "\t\t\t\t\t\t\t\t\t<a href=\"#\" title=\"Add block: {$pfx_part}\">{$pfx_part}</a>\n";
					}
				}
			}
		}
	}
	
	if ( ( isset( $pfx_cloud ) ) && ( $pfx_cloud ) ) {
		$pfx_cloud = substr( $pfx_cloud, 0, ( strlen( $pfx_cloud ) - 1 ) ) . "";
		echo "\t\t\t\t\t\t\t\t<div class=\"form_block_suggestions\" id=\"form_block_list\">";
		echo "<span class=\"form_block_suggestions_text\">{$pfx_lang['form_help_current_blocks']}</span>\n {$pfx_cloud}\n";
		echo "\t\t\t\t\t\t\t\t</div>\n";
	}
	
}
//-------------------------------------------------------------------
/* A function to revert slug / used for tags only */
//-------------------------------------------------------------------
function squash_slug( $pfx_title ) {
	$pfx_slug = str_replace( '-', " ", $pfx_title );
	
	return strtolower( $pfx_slug );
	
}
// ------------------------------------------------------------------
/* Validate an url exists */
// ------------------------------------------------------------------
function url_exist($pfx_url_string) {
	$pfx_headers_return = @get_headers($pfx_url_string);
	return is_array($pfx_headers_return) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/', $pfx_headers_return[0]) : FALSE;
}
// ------------------------------------------------------------------
/* Get the first word in a _ seperated string */
// ------------------------------------------------------------------
function first_word( $pfx_theString ) {
	$pfx_stringParts = explode( '_', $pfx_theString );
	return $pfx_stringParts[0];
	
}
// ------------------------------------------------------------------
/* Get the last word in a _ seperated string */
// ------------------------------------------------------------------
function last_word( $pfx_theString ) {
	$pfx_stringParts = explode( '_', $pfx_theString );
	return array_pop( $pfx_stringParts );
	
}
// ------------------------------------------------------------------
/* Get the first word in a string */
// ------------------------------------------------------------------
function firstword( $pfx_theString ) {
	$pfx_stringParts = explode( " ", $pfx_theString );
	return $pfx_stringParts[0];
	
}
// ------------------------------------------------------------------
/* Get the last word in a string */
// ------------------------------------------------------------------
function lastword( $pfx_theString ) {
	$pfx_stringParts = explode( " ", $pfx_theString );
	return array_pop( $pfx_stringParts );
	
}
// ------------------------------------------------------------------
/* Get a var from $pfx__SERVER global array, or create it */
// ------------------------------------------------------------------
function serverSet( $pfx_thing ) {
	return ( isset( $_SERVER[$pfx_thing] ) ) ? $_SERVER[$pfx_thing] : '';
}
// ------------------------------------------------------------------
/* An array function */
// ------------------------------------------------------------------
function doArray( $pfx_in, $pfx_function ) {
	return is_array( $pfx_in ) ? array_map( $pfx_function, $pfx_in ) : $pfx_function( $pfx_in );
	
}
//-------------------------------------------------------------------
/* A function to simply string in item_name format */
// ------------------------------------------------------------------
function simplify( $pfx_string ) {
	$pfx_out    = str_replace( '_', " ", $pfx_string );
	$pfx_strlen = strlen( $pfx_out );
	$pfx_max    = 150; // find somwhere better for this?
	
	if ( $pfx_strlen > $pfx_max ) {
		$pfx_out = substr( $pfx_out, 0, $pfx_max ) . '...';
	}
	
	return ucfirst( $pfx_out );
	
}
//-------------------------------------------------------------------
/* A function chop length of string */
// ------------------------------------------------------------------
function chopme( $pfx_string, $pfx_length ) {
	$pfx_strlen = strlen( $pfx_string );
	$pfx_max    = $pfx_length;
	
	if ( $pfx_strlen > $pfx_max ) {
		$pfx_string = substr( $pfx_string, 0, $pfx_max ) . '...';
	}
	
	return $pfx_string;
	
}
//-------------------------------------------------------------------
/* function to create a safe string from special characters like those found in non-English languages */
//-------------------------------------------------------------------
function safe_string( $pfx_string ) {
	$pfx_from   = explode( ',', '&lt;,&gt;,&#039;,&amp;,&quot;,À,Á,Â,Ã,Ä,&Auml;,Å,Ā,Ą,Ă,Æ,Ç,Ć,Č,Ĉ,Ċ,Ď,Đ,Ð,È,É,Ê,Ë,Ē,Ę,Ě,Ĕ,Ė,Ĝ,Ğ,Ġ,Ģ,Ĥ,Ħ,Ì,Í,Î,Ï,Ī,Ĩ,Ĭ,Į,İ,Ĳ,Ĵ,Ķ,Ł,Ľ,Ĺ,Ļ,Ŀ,Ñ,Ń,Ň,Ņ,Ŋ,Ò,Ó,Ô,Õ,Ö,&Ouml;,Ø,Ō,Ő,Ŏ,Œ,Ŕ,Ř,Ŗ,Ś,Š,Ş,Ŝ,Ș,Ť,Ţ,Ŧ,Ț,Ù,Ú,Û,Ü,Ū,&Uuml;,Ů,Ű,Ŭ,Ũ,Ų,Ŵ,Ý,Ŷ,Ÿ,Ź,Ž,Ż,Þ,Þ,à,á,â,ã,ä,&auml;,å,ā,ą,ă,æ,ç,ć,č,ĉ,ċ,ď,đ,ð,è,é,ê,ë,ē,ę,ě,ĕ,ė,ƒ,ĝ,ğ,ġ,ģ,ĥ,ħ,ì,í,î,ï,ī,ĩ,ĭ,į,ı,ĳ,ĵ,ķ,ĸ,ł,ľ,ĺ,ļ,ŀ,ñ,ń,ň,ņ,ŉ,ŋ,ò,ó,ô,õ,ö,&ouml;,ø,ō,ő,ŏ,œ,ŕ,ř,ŗ,š,ù,ú,û,ü,ū,&uuml;,ů,ű,ŭ,ũ,ų,ŵ,ý,ÿ,ŷ,ž,ż,ź,þ,ß,ſ,А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ъ,Ы,Э,Ю,Я,а,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ъ,ы,э,ю,я' );
	$pfx_to     = explode( ',', ',,,,,A,A,A,A,Ae,A,A,A,A,A,Ae,C,C,C,C,C,D,D,D,E,E,E,E,E,E,E,E,E,G,G,G,G,H,H,I,I,I,I,I,I,I,I,I,IJ,J,K,K,K,K,K,K,N,N,N,N,N,O,O,O,O,Oe,Oe,O,O,O,O,OE,R,R,R,S,S,S,S,S,T,T,T,T,U,U,U,Ue,U,Ue,U,U,U,U,U,W,Y,Y,Y,Z,Z,Z,T,T,a,a,a,a,ae,ae,a,a,a,a,ae,c,c,c,c,c,d,d,d,e,e,e,e,e,e,e,e,e,f,g,g,g,g,h,h,i,i,i,i,i,i,i,i,i,ij,j,k,k,l,l,l,l,l,n,n,n,n,n,n,o,o,o,o,oe,oe,o,o,o,o,oe,r,r,r,s,u,u,u,ue,u,ue,u,u,u,u,u,w,y,y,y,z,z,z,t,ss,ss,A,B,V,G,D,E,YO,ZH,Z,I,Y,K,L,M,N,O,P,R,S,T,U,F,H,C,CH,SH,SCH,Y,Y,E,YU,YA,a,b,v,g,d,e,yo,zh,z,i,y,k,l,m,n,o,p,r,s,t,u,f,h,c,ch,sh,sch,y,y,e,yu,ya' );
	$pfx_string = urldecode( str_replace( $pfx_from, $pfx_to, $pfx_string ) );
	$pfx_string = preg_replace( '/[^a-zA-Z0-9 ]/', "", $pfx_string );
	
	return ( $pfx_string );
	
}
//-------------------------------------------------------------------
/* function to return a slug from post name */
//-------------------------------------------------------------------
function make_slug( $pfx_slug ) {
	$pfx_dash = array(
		 '--',
		'---',
		'----',
		'-----' 
	);
	$pfx_slug = strtolower( str_replace( $pfx_dash, '-', str_replace( ' ', '-', str_replace( '_', '-', safe_string($pfx_slug) ) ) ) );
	
	return ( $pfx_slug );
	
}
//-------------------------------------------------------------------
/* A function to correctly form tags */
//-------------------------------------------------------------------
function make_tag( $pfx_tags ) {
	if ( isset( $pfx_tags ) ) {
		$pfx_tags = explode( " ", $pfx_tags );
		
		for ( $pfx_count = 0; $pfx_count < ( count( $pfx_tags ) ); $pfx_count++ ) {
			$pfx_current = $pfx_tags[$pfx_count];
			
			if ( $pfx_current != "" ) {
				$pfx_current = safe_string( $pfx_current );
				
				if ( (isset($pfx_all_tag)) && ($pfx_all_tag) ) {
				} else {
					$pfx_all_tag = FALSE;
				}
				
				$pfx_all_tag .= "{$pfx_current} ";
			}
		}
		if ( (isset($pfx_all_tag)) && ($pfx_all_tag) ) {

			return rtrim( $pfx_all_tag );

		} else {

			return FALSE;
		}
		
	}
	
}
//-------------------------------------------------------------------
/* Reset the page order */
//-------------------------------------------------------------------
function page_order_reset() {
	$pfx_pages = safe_rows( '*', 'pfx_core', "public = 'yes' and in_navigation = 'yes' order by page_order asc" );
	$pfx_num   = count( $pfx_pages );
	$pfx_i     = 0;
	
	while ( $pfx_i < $pfx_num ) {
		$pfx_out     = $pfx_pages[$pfx_i];
		$pfx_page_id = $pfx_out['page_id'];
		safe_update( 'pfx_core', "page_order  = {$pfx_i} + 1", "page_id = '{$pfx_page_id}'" );
		$pfx_i++;
	}
	
}
//-------------------------------------------------------------------
/* Protect email from spam bots */
//-------------------------------------------------------------------
function encode_email( $pfx_emailaddy, $pfx_mailto = 0 ) {
	$pfx_emailNOSPAMaddy = '';
	srand( (float) microtime() * 1000000 );
	
	for ( $pfx_i = 0; $pfx_i < strlen( $pfx_emailaddy ); $pfx_i = $pfx_i + 1 ) {
		$pfx_j = floor( rand( 0, 1 + $pfx_mailto ) );
		
		if ( $pfx_j == 0 ) {
			$pfx_emailNOSPAMaddy .= '&#' . ord( substr( $pfx_emailaddy, $pfx_i, 1 ) ) . ';';
			
		} elseif ( $pfx_j === 1 ) {
			$pfx_emailNOSPAMaddy .= substr( $pfx_emailaddy, $pfx_i, 1 );
			
		} elseif ( $pfx_j === 2 ) {
			$pfx_emailNOSPAMaddy .= '%' . zeroise( dechex( ord( substr( $pfx_emailaddy, $pfx_i, 1 ) ) ), 2 );
		}
	}
	
	$pfx_emailNOSPAMaddy = str_replace( '@', '&#64;', $pfx_emailNOSPAMaddy );
	
	return $pfx_emailNOSPAMaddy;
	
}
// ------------------------------------------------------------------
/* Generate a unique hash from a string */
// ------------------------------------------------------------------
function genHashUnique($pfx_in, $pfx_slt = NULL, $pfx_mode = 'sha256') {
	/* Hash the text */
	$pfx_txt_hash  = hash($pfx_mode, $pfx_in);
	/* Set where salt will appear in hash */
	$pfx_slt_start = strlen($pfx_in);
	/* If no salt given create random one */
	if ($pfx_slt == NULL) {
		$pfx_slt = hash($pfx_mode, uniqid(rand(), true));
	}
	/* Add salt into text hash at pass length position and hash it */
	if ($pfx_slt_start > 0 && $pfx_slt_start < strlen($pfx_slt)) {
		$pfx_out_hash_start = substr($pfx_txt_hash, 0, $pfx_slt_start);
		$pfx_out_hash_end   = substr($pfx_txt_hash, $pfx_slt_start, strlen($pfx_slt));
		$pfx_out_hash       = hash($pfx_mode, "{$pfx_out_hash_end}{$pfx_slt}{$pfx_out_hash_start}");
	} elseif ($pfx_slt_start > (strlen($pfx_slt) - 1)) {
		$pfx_out_hash = hash($pfx_mode, "{$pfx_txt_hash}{$pfx_slt}");
	} else {
		$pfx_out_hash = hash($pfx_mode, "{$pfx_slt}{$pfx_txt_hash}");
	}
	/* Put salt at front of hash */
	$pfx_out = "{$pfx_slt}{$pfx_out_hash}";
	return $pfx_out;
}
// ------------------------------------------------------------------
/* Generate a new password */
// ------------------------------------------------------------------
function generate_password( $pfx_length = 10 ) {
	$pfx_pass  = "";
	$pfx_chars = '023456789bcdfghjkmnpqrstvwxyz';
	$pfx_i     = 0;
	
	while ( $pfx_i < $pfx_length ) {
		$pfx_char = substr( $pfx_chars, mt_rand( 0, strlen( $pfx_chars ) - 1 ), 1 );
		
		if ( !strstr( $pfx_pass, $pfx_char ) ) {
			$pfx_pass .= $pfx_char;
			$pfx_i++;
		}
	}
	
	return $pfx_pass;
	
}
//-------------------------------------------------------------------
/* A function for deleting a file */
//-------------------------------------------------------------------
function file_delete( $pfx_file ) {
	if ( unlink( $pfx_file ) ) {
		return TRUE;
		
	} else {
		return FALSE;
		
	}
	
}
//-------------------------------------------------------------------
/* A function to check if a number is odd or even */
//-------------------------------------------------------------------
function is_even( $pfx_number ) {
	$pfx_result = $pfx_number % 2;
	
	if ( $pfx_result == 0 ) {
		return TRUE;
		
	} else {
		return FALSE;
		
	}
	
}
//-------------------------------------------------------------------
/* Allow PHP/HTML to be written into textarea */
//-------------------------------------------------------------------
function textarea_encode( $pfx_html_code ) {
	$pfx_from      = array(
		 '<',
		'>' 
	);
	$pfx_to        = array(
		 '#&50',
		'#&52' 
	);
	$pfx_html_code = str_replace( $pfx_from, $pfx_to, $pfx_html_code );
	
	return $pfx_html_code;
	
}
//-------------------------------------------------------------------
/* A function to return current directory */
//-------------------------------------------------------------------
function current_dir() {
	$pfx_path     = dirname( $_SERVER['PHP_SELF'] );
	$pfx_position = strrpos( $pfx_path, '/' ) + 1;
	
	return substr( $pfx_path, $pfx_position );
	
}