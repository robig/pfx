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
 * Title: admin.js - Core Admin JavaScript
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

$j(document).ready(function() {

	    $j('.page_handle').hover(function() { /* Ensure we get a pointer cursor on hover to indicate you can sort the table */
		$j(this).css('cursor','move');
	    }, function() {
		$j(this).css('cursor','auto');
	    });

	if ($j('.file_name a').length >= 1) {
		$j('.file_name a:[title]').tooltip({
			tipClass : 'fileman-tip',
			layout :'<div>',
			effect :'fade',
			position : 'center right',
			onShow : function() {
				var imgGrab = this.getTrigger().attr('href').match(/\.(jpe?g|png|gif|bmp)/gi) ? this.getTrigger().attr('href') : 'No preview available';
				this.getTrigger().attr('href').match(/\.(jpe?g|png|gif|bmp)/gi) ? this.getTip().empty().append('<img src="' + imgGrab + '"></img>') : this.getTip().empty().append(imgGrab);
			}
		});
	}

	$j('#pages').Sortable( {
		accept : 'page',
		activeclass : 'sortableactive',
		hoverclass : 'sortablehover',
		helperclass : 'sorthelper',
		opacity : 0.5,
		handle : '.page_handle',
		fit : true,
		axis : 'vertically',
		revert : true,
		onChange : function(ser)
		{
			serial = $j.SortSerialize('pages');
			$j.ajax({
				type: 'POST',
				url: 'admin/modules/ajax_pages.php',
				data: serial.hash,
				success: function(msg){
				}
 			});
		}
	})

	$j('.more_upload').show();
	$j('.image_preview select').bind('change', preview);

});  /* End jQuery document ready function */


/* preview image */
function preview() {

	$j('.lightbox').remove();
	var image = $j(this).find('option[@selected]').text(), check = $j(this).parent().find('.lightbox').html();
	if (image != '-') {
		$j(this).parent().find('.more_upload').prepend("<a href=\"../files/images/" + image + "\" onclick=\"\" class=\"lightbox\">preview</a> ");
		tb_init('a.lightbox');
	} else {
		$j(this).parent().find('.lightbox').hide();
	}


}


/* A function to apply the carousel */
function carouselInit() {
    /* A function to move the carousel to the current page */
	$j(function() {
	    $j('#carousel').jCarouselLite({
	    start: pfxScroll - 1,
	    visible: 3,
	    scroll: 2,
	    btnNext: '.next',
	    btnPrev: '.prev',
	    circular: true,
	    easing: 'easeInOutQuad',
	    speed: 250,
	    mouseWheel: true
	    });

	    $j('ul#mycarousel li').not('ul#mycarousel li.current').hover(function() { /* Ensure we get a pointer cursor on hover to indicate you can sort the table */
		$j(this).children('a.link-dynamic').css('color', '#204A87');
		$j(this).children('a.link-module').css('color', '#4E9A06');
		$j(this).children('a.link-static').css('color', '#8F5902');
		$j(this).children('a.link-plugin').css('color', '#5C3566');
	    }, function() {
		$j(this).children('a.c-link').css('color', '#2E3436');
	    });
	});


    $j(document).ready(function(){
	$j('.innav a.c-link:[title]').tooltip({
	  tipClass: 'carousel-tip',
	  layout:'<div><div/>',
	  effect:'fade',
	  position: 'top center'
	});
    });
    
}  /* End function carouselInit */


/* A function to apply the table sorter */
function applyTablesort() {

    $j(function() {

	$j(document).ready(function() {
	    $j('.tbl').tablesorter('debug', false);
	    $j('.tbl_heading').hover(function(index) { /* Ensure we get a pointer cursor on hover to indicate you can sort the table */
		$j(this).css('cursor','pointer');
	    }, function() {
		$j(this).css('cursor','auto');
	    });
	});

    });  /* End jQuery function */


}  /* End function applyTablesort */


/* A function to load javascript via ajax and then call the applyTablesort function to use it  */
function fetchJs(scriptName) {

	$j.ajaxSetup('async', false); /* Set jQuery to load the scripts synchronously */
	    $j.getScript('jscript/' + scriptName + '.js', function() { /* Load js via ajax, using a callback */
		    $j.ajaxSetup('async', true);  /* Set jQuery back to load the scripts asynchronously (The default.) */
			    if (scriptName === 'tablesorter') {
				if ($j('.tbl').length >= 1) {
				    $j(document).ready(function() {
					applyTablesort();
				    }); /* Apply the tablesorter by calling it's function */ }
			    }
	    });  /* End $j.getScript function */


}  /* End function fetchJs */



function pfxMessage() {
    $j(document).ready(function(){
      if ($j('.sys-message span').length >= 1) {
      $j('.sys-message').clone().appendTo('#message');
      $j('#message .sys-message').show();
      $j('div#message-wrap').slideDown('normal', 'easeInOutQuad');
      }
    });

} /* End function pfxMessage */



function pxfinderInit() {
    $j('.pxfinder').each(function(index) { /* Stop the default click action */
	$j(this).click(function(event) {
	    event.preventDefault();
	    var pxfinderFilename =  $j(this).attr('alt'), pxfinderUrl =  pfxSiteUrl + 'files' + pxfinderFilename; /* Create the url for the ckeditor plugin to insert into your post */
	    window.opener.CKEDITOR.tools.callFunction(funcNum, pxfinderUrl);
	    window.close();
	});
    });

} /* End function pxfinderInit */


function autoName() {

	$j('.auto-name div div input#uname').one('focus', function(event) {

	    if ($j('input#uname').val() === '') {
		var realName = null;
		realName = $j('input#realname').val();
		realName = (realName).toLowerCase();

		    function getWord(str, pos) {
		    var SplitString = str.split(' ');
		    return (SplitString[parseInt(pos) - 1]);

		    }

		var userName = getWord(realName, 1);
		$j(this).val(userName);
	    }
	});

} /* End function autoName */


/* The main jQuery function */
$j(function() {

	pfxMessage();
	
	if ($j('#carousel').length >= 1) {
	    carouselInit();
	} /* If the carousel container is present, load up the carousel... */
	    /* Table sorter code */
	    scriptName = 'tablesorter';
	    fetchJs(scriptName);
	    /* lightbox code */
	    scriptName = 'lightbox';
	    fetchJs(scriptName);
	if ($j('.pxfinder').length >= 1) {
	    pxfinderInit();
	} /* If the filemanager container is loaded as pxfinder, load up pxfinder... */
	if ($j('.auto-name').length >= 1) {
	    autoName();
	} /* If the filemanager container is loaded as pxfinder, load up pxfinder... */

}); /* End jQuery function */
