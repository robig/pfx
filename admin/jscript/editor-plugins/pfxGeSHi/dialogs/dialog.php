<?php
header('Content-Type: text/html; charset=UTF-8');
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
 * Title: CKEditor GeSHi Dialog
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Nigel McNie
 * @author T White
 * @link http://heydojo.co.cc/
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
if ( defined('DIRECT_ACCESS') ) {
	require_once '../../../../lib/lib_misc.php';
	exit( pfxExit() );
}
define('DIRECT_ACCESS', 1);
require_once '../../../../lib/lib_misc.php';
/* perform basic sanity checks */
bombShelter();
/* check URL size */
error_reporting(0);
$pfx_refering = NULL;
$pfx_refering = parse_url( ($_SERVER['HTTP_REFERER']) );
if ( ($pfx_refering['host'] == $_SERVER['HTTP_HOST']) ) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<meta http-equiv="X-UA-Compatible" content="IE=7,chrome=1" />

	<title>GeSHi</title>

	<style type="text/css">
	body{margin-top:0;font-family:Arial,"Lucida Grande",Verdana,Sans-Serif;font-size:12px;padding-left:1%;padding-right:1%;color:#555753}
	h3{color:#555753;font-weight:400;max-width:59%;margin-top:0px;margin-bottom:0px;margin-left:6px;margin-right:0px}
	#footer{text-align:center;font-size:80%;color:#555753;clear:both;padding-top:16px}
	a{color:#EF2929;text-decoration:none}
	a:hover{text-decoration:underline}
	textarea{background-color:#fff;border:1px solid #D3D7CF;font-size:90%;color:#555753;width:244px;margin-bottom:6px;margin-left:6px}
	p{font-size:90%}
	#clear{text-align:right;width:129px;float:left;padding-right:3%}
	#submit{width:129px;float:left}
	#style-radio{float:right;padding-top:16px;}
	#style-radio input:hover{cursor:pointer}
	#language{text-align:left;width:31%;color:#676666;background-color:#FFF;height:24px;margin-bottom:12px;margin-left:6px}
	.ui_button{font-size:12px;text-align:center;background-color:transparent;background-image:url(../../../../admin/theme/images/png/button4.png);background-position:center top;background-repeat:no-repeat;border:0;color:#555753;height:30px;width:129px}
	.ui_button:hover{background-position:center bottom;cursor:pointer;color:#2E3436}
	#center{text-align:center}
	#right{text-align:right}
<?php
	if ( (isset($_POST['submit'])) && ($_POST['style_type'] === 2) ) {
		echo $pfx_geshi->get_stylesheet(TRUE); /* Output the stylesheet. Note it doesn't output the <style> tag */
	}
?>
	</style>

    </head>
<?php
	if ( (is_readable('../../../../lib/geshi.php')) ) {
		$pfx_path = '../../../../lib/';
	} elseif ( (is_readable('geshi.php')) ) {
		$pfx_path = './';
	} else {
?>
    <body>
	<p id="center">To activate this plugin you must do the following first :</p>
	<p>
	    <ol>
		<li>Download GeSHi from <a href="http://sourceforge.net/projects/geshi/files/" target="_blank">here</a> (Version 1.0.8.6 or higher recommended)</li>
		<li>Extract the downloaded archive</li>
		<li>Copy the folder geshi/geshi/ into PFX's lib directory admin/lib/</li>
		<li>Copy the file geshi/geshi.php into PFX's lib directory admin/lib/</li>
		<li>Close this dialogue by clicking cancel</li>
		<li>Finally, click the "Post syntax highlighted code" Icon again to re-launch this dialogue.</li>
	    </ol>
	</p>
	<p id="right">Enjoy!<p>
    </body>
</html>

    <?php die(); }

	require_once "{$pfx_path}geshi.php";
	$pfx_fill_source = FALSE;
	if ( isset($_POST['submit']) ) {
		if ( get_magic_quotes_gpc() ) {
			$_POST['source'] = stripslashes($_POST['source']);
		}
		if ( !strlen(trim($_POST['source'])) ) {
			$_POST['language'] = preg_replace('#[^a-zA-Z0-9\-_]#', '', $_POST['language']);
			$_POST['source']   = implode('', @file("{$pfx_path}geshi/{$_POST['language']}.php"));
			$_POST['language'] = 'php';
		} else {
			$pfx_fill_source = TRUE;
		}
		/* Set GeSHi options */
		$pfx_geshi = new GeSHi($_POST['source'], $_POST['language']);
		if (($_POST['container-type']) == 1) {
			$pfx_geshi->set_header_type(GESHI_HEADER_DIV);
		}
		if (($_POST['container-type']) == 2) {
			$pfx_geshi->set_header_type(GESHI_HEADER_PRE_VALID);
		}
		if (($_POST['line_numbers']) == 2) {
			$pfx_geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
			$pfx_geshi->set_line_style('background: transparent;', 'background: #F0F5FE;', TRUE);
		}
		if (($_POST['line_numbers']) == 3) {
			$pfx_geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
		}
		if (($_POST['style_type']) == 2) {
			$pfx_geshi->enable_classes();
		}
		if (isset($_POST['submit'])) {
			$pfx_geshi_out = $pfx_geshi->parse_code();
		}
	} else {
		/* Don't pre-select any language */
		$_POST['language'] = NULL;
	}
?>
    <body>
<?php
	if (isset($_POST['submit'])) {
		echo $pfx_geshi_out; ?>
<script type="text/javascript">    //<![CDATA[

var CKEDITOR = window.parent.CKEDITOR, l = function(ev) {
   this._.editor.insertHtml('<?php echo preg_replace("/\r?\n/", "\\n", addslashes($pfx_geshi_out)); ?>');
   CKEDITOR.dialog.getCurrent().removeListener('ok', l);
};

CKEDITOR.dialog.getCurrent().on('ok', l);

				//]]></script>
<?php 	} else {
?>
	<form accept-charset="UTF-8" action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post">
	    <h3 id="lang">Choose a language *</h3>
	    <p>
		<div id="style-radio">
		    <input type="radio" name="style_type" value="1" checked> Use inline syles (<a href="http://qbnz.com/highlighter/geshi-doc.html#using-css-classes" target="_blank">?</a>)</input>
		    <br />
		    <input type="radio" name="style_type" value="2"> Use your own css</input>
		    <br /><br />
		    <input type="radio" name="line_numbers" value="1" checked> No Line numbers (<a href="http://qbnz.com/highlighter/geshi-doc.html#enabling-line-numbers" target="_blank">?</a>)</input>
		    <br />
		    <input type="radio" name="line_numbers" value="2"> Fancy Line numbers</input>
		    <br />
		    <input type="radio" name="line_numbers" value="3"> Normal line numbers</input>
		    <br /><br />
		    <input type="radio" name="container-type" value="1" checked> Use a div container (<a href="http://qbnz.com/highlighter/geshi-doc.html#the-code-container" target="_blank">?</a>)</input>
		    <br />
		    <input type="radio" name="container-type" value="2"> Use a (Valid) pre container</input>
		</div>

		<select name="language" id="language">
<?php
		if ( ($pfx_dir = @opendir(dirname(__FILE__) . '/../../../../lib/geshi')) ) {
		} else {
			echo '<option>No languages available!</option>';
		}
		$pfx_languages = array();
		while ( $pfx_file = readdir($pfx_dir) ) {
			if ( $pfx_file[0] == '.' or strpos($pfx_file, '.', 1) === FALSE ) {
				continue;
			}
			$pfx_language = substr( $pfx_file, 0, strpos($pfx_file, '.') );
			$pfx_languages[] = $pfx_language;
		}
		closedir($pfx_dir);
		sort($pfx_languages);
		echo '<option selected="selected" value="javascript">javascript</option>';
		foreach ($pfx_languages as $pfx_language) {
			if (isset($_POST['language']) && $_POST['language'] == $pfx_language) {
				$pfx_selected = 'selected="selected"';
			} else {
				$pfx_selected = '';
			}
			echo "<option value=\"{$pfx_language}\">{$pfx_language}</option>\n";
		}
?>
		</select>
	    </p>
	    <h3 id="src">Code to highlight *</h3>
	    <p>
		<textarea rows="6" name="source" id="source"><?php echo $pfx_fill_source ? htmlspecialchars($_POST['source']) : ''; ?></textarea>
	    </p>
	    <span id="submit">
		<input class="ui_button" type="submit" name="submit" value="Highlight" />
	    </span>
	    <span id="clear">
		<input class="ui_button" type="submit" name="clear" onclick="document.getElementById('source').value='';document.getElementById('language').value='';return false" value="Clear" />
	    </span>
	</form>
	<div id="footer">
	    <p>
		<a href="http://qbnz.com/highlighter/" target="_blank">GeSHi</a> &copy; Nigel McNie, 2004, released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU GPL</a>
	    </p>
	</div>
<?php /* End isset post submit */ } ?>
</body>
</html>

<?php
} else {
	exit( header('Location: ../../../../../') );
}