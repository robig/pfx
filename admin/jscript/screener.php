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
 * Title: Bad behaviour javascript
 * Bad Behavior
 * Copyright (C) 2005-2010 Michael Hampton
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Michael Hampton
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
		require_once '../lib/lib_misc.php';
		exit( pfxExit() );
	}
	define('DIRECT_ACCESS', 1);
	require_once '../lib/lib_misc.php';
	/* perform basic sanity checks */
	bombShelter();
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
function bb2_addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			oldonload();
			func();
		}
	}
}

bb2_addLoadEvent(function() {
	for ( i = 0; i < document.forms.length; i++ ) {
		if (document.forms[i].method == 'post') {
			var myElement = document.createElement('input');
			myElement.setAttribute('type', 'hidden');
			myElement.name = '<?php echo htmlspecialchars_decode( urldecode($pfx_cookie_name) ); ?>';
			myElement.value = '<?php echo htmlspecialchars_decode( urldecode($pfx_cookie_value) ); ?>';
			document.forms[i].appendChild(myElement);
		}
	}
});
    //]]>
<?php
} else {
	exit( header('Location: ../../../') );
}