<?php
header("content-type: application/x-javascript");
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
 * Title: Installer JavaScript language variables
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
$pfx_refering = NULL;
$pfx_refering = parse_url( ($_SERVER['HTTP_REFERER']) );
if ( ($pfx_refering['host'] == $_SERVER['HTTP_HOST']) ) {
	if ( (defined('DIRECT_ACCESS')) or (defined('PFX_DEBUG')) ) {
		require_once '../../admin/lib/lib_misc.php';
		exit( pfxExit() );
	}
	define('DIRECT_ACCESS', 1);
	require_once '../../admin/lib/lib_misc.php';
	/* perform basic sanity checks */
	bombShelter(1000);
	/* check URL size */
	if (PFX_DEBUG == 'yes') {
	error_reporting(-1);
	} else {
	error_reporting(0);
	}
	/* Note : If you use this file, any global vars now have the prefix pfx, so what was $s is now $pfx_s */
	/* !IMPORTANT - This file thinks it's being run from admin/ */
	/* instead of admin/jscript so paths are relative to admin */
	extract($_REQUEST, EXTR_PREFIX_ALL, 'pfx');
	$_REQUEST = NULL;
?>
    //<![CDATA[
    var pfxJsLangSiteUrl = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_site_url) ); ?>';
    var pfxJsLangSiteName = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_site_name) ); ?>';
    var pfxJsLangHostName = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_host_name) ); ?>';
    var pfxJsLangUserName = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_user_name) ); ?>';
    var pfxJsLangPwd = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_pwd) ); ?>';
    var pfxJsLangDbPwd = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_db_pwd) ); ?>';
    var pfxJsLangRealName = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_real_name) ); ?>';
    var pfxJsLangLoginName = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_login_name) ); ?>';
    var pfxJsLangEmail = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_email) ); ?>';
    var pfxJsLangLoginPwd = '<?php echo htmlspecialchars_decode( urldecode($pfx_js_lang_login_pwd) ); ?>';

    //]]>
<?php
} else {
	exit( header('Location: ../../../') );
}