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
 * Title: Core PFX JavaScript variables
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
    var $j = jQuery.noConflict(), pfxSiteUrl = '<?php echo htmlspecialchars_decode( urldecode($pfx_site_url) ); ?>', editor, pfxToolBar = 'Small', editorMode = 1;
    <?php if ( (isset($pfx_editor_image_class)) && ($pfx_editor_image_class) ) { ?>
    var editorImageClass = '<?php echo htmlspecialchars_decode( urldecode($pfx_editor_image_class) ); ?>';
    <?php } else { ?>
    var editorImageClass = '';
    <?php } ?>
    <?php if ( (isset($pfx_editor_enter_mode)) && ($pfx_editor_enter_mode) ) { ?>
    var editorBrEnterMode = '<?php echo htmlspecialchars_decode( urldecode($pfx_editor_enter_mode) ); ?>';
    <?php } else { ?>
    var editorBrEnterMode = 'yes';
    <?php } ?>
<?php if ($pfx_action_type == 'admin') { ?>
    var actionType = 'admin';
	  <?php if ( (isset($pfx_s)) && ($pfx_s !== 'login') ) {
	      if ( (isset($pfx_scroll)) && ($pfx_scroll) ) { ?>
    var pfxScroll = <?php echo urldecode($pfx_scroll); ?>;
	<?php } else { ?>
    var pfxScroll = 0;
	<?php }
		if (isset($pfx_ckFuncNumReturn)) { ?>
			var funcNum = '<?php echo htmlspecialchars_decode( urldecode($pfx_ckFuncNumReturn) ); ?>';
		<?php } else { ?>
function getUrlParam(paramName) { /* Helper function to get parameters from the query string. */
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i'), match = window.location.search.match(reParam);

  return (match && match.length > 1) ? match[1] : '' ;
}
var funcNum = getUrlParam('CKEditorFuncNum');
		<?php }
	  } /* End if not logged in */
} else { /* End if action_type equals admin */ ?>
    var actionType = 'theme';
    var pfxThemeName = '<?php echo htmlspecialchars_decode( urldecode($pfx_site_theme) ); ?>';
    <?php if ( (isset($pfx_captcha)) && ($pfx_captcha == 'yes') ) { ?>
    var captchaPubkey = '<?php echo htmlspecialchars_decode( urldecode($pfx_pub) ); ?>';
    <?php }?>
<?php } /* End if action_type does not equal admin */ ?>

    //]]>
<?php
} else {
	exit( header('Location: ../../../') );
}