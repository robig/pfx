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
 * Title: upload.js - Inline upload JavaScript for modules
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

/* ajax file upload */

var temp = "";
var tfield = "";

function upswitch(field) {

	temp = $j('#' + field).parent().html(), tfield = field;
	$j('#' + field).parent().find('.more_upload').replaceWith("<span class='more_upload_start'><a href='#' onclick='cancel(); return false;' title='Cancel'>Cancel</a></span>");
	$j('.more_upload').hide();
	$j('#' + field).replaceWith("<form accept-charset=\"UTF-8\" action=\"admin/modules/ajax_fileupload.php\" method=\"post\" id=\"" + field + "\" class=\"inline_form\" enctype=\"multipart/form-data\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})\"><input type=\"file\" name=\"upload[]\" id=\"upload\" size=\"18\" /><input type=\"hidden\" name=\"field\" value=\"" + field + "\"><input type=\"submit\" name=\"submit_upload\" class=\"submit_upload\" value=\"Upload\" /><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10240\"></form>");
	$j('.form_submit').attr('disabled', 'true');
	$j('input#' + field + '_input').hide('fast');
	$j('div#' + tfield + '_container').remove();

}



function cancel() {
  
	$j('#' + tfield).replaceWith(temp);
	$j('.more_upload').show();
	$j('select#' + tfield).show();
	$j('.form_submit').removeAttr('disabled');
	$j('#' + tfield).parent().find('.more_upload_start').replaceWith("");
	$j('#' + tfield).parent().find('input').replaceWith("");
	$j('.image_preview select').bind('change', preview);
	$j('select#' + tfield).selectbox();

}



function startCallback() {
  
  	$j('input#' + tfield + '_input').remove();
	$j('select#' + tfield).remove();
	$j('.submit_upload').attr('disabled', 'true');
	$j('#' + tfield).parent().find('.more_upload_start').replaceWith("<img src='admin/theme/images/spinner.gif' alt='loading' width='32' height='32' id='upload_wait'/>");
	return true;
}



function completeCallback(response) {
	if (response) {
		alert(response);
		$j('.submit_upload').removeAttr('disabled');
		$j('#upload').removeAttr('disabled');
		$j('#upload_wait').replaceWith("<span class='more_upload_start'><a href='#' onclick='cancel(); return false;' title='Cancel'>Cancel</a></span>");

	} else {
		/* Refresh the drop down with new list, select the file and enable the button to proceed */
		$j('#upload_wait').replaceWith("");
		
		if ($j.browser.msie) {
		/* Should use jQuery.support instead of jQuery.browser */
			$j.post('admin/modules/ajax_fileupload.php', {
			    form: tfield, ie: 'true'
			}, function(data){
				$j('#' + tfield).replaceWith(data);
				$j('.more_upload').show();
				$j('select#' + tfield).selectbox();
			});
		} else {
			$j.post('admin/modules/ajax_fileupload.php', {
			    form: tfield
			}, function(data){
				$j('#' + tfield).replaceWith(data);
				$j('.more_upload').show();
				$j('select#' + tfield).selectbox();
			});	
		}
		
		$j('.form_submit').removeAttr('disabled');
	}


}
