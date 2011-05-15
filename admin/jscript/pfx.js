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
 * Title: pfx.js - Common JavaScript
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

	var editorActive = 1;


function applyTags(tagnorm, tagselect, tagnormhover) {

		$j('input#tags').jTagging($j('div#form_tags_list'), " ", tagnorm, tagselect, tagnormhover);
		$j('input#page_blocks').jTagging($j('div#form_block_list'), " ", tagnorm, tagselect, tagnormhover);
}



function confirmer(message, callback) {
	$j('#confirm').modal({
		opacity : 80,
		closeHTML : "<a href='#' title='Close' class='modal-close'>x</a>",
		position : ["30%"],
		overlayId : 'confirm-overlay',
		containerId : 'confirm-container',
		onOpen: function (dialog) {
	dialog.overlay.fadeIn('fast', function () {
		dialog.container.fadeIn('fast', function () {
			dialog.data.fadeIn('fast');
		});
	});
		},
		onShow : function (dialog) {
			$j('.message', dialog.data[0]).append(message);

			// if the user clicks "yes"
			$j('.yes', dialog.data[0]).click(function () {
				// call the callback
				if ($j.isFunction(callback)) {
					callback.apply();
				}
				// close the dialog
				$j.modal.close();
			});
		}
	});
}



function actConfirm() {
	$j('a.confirm-del').each(function(index) {
		$j(this).click(function (e) {
			var dest = $j(this).attr('href');
			e.preventDefault();
			confirmer("Are you sure?", function () {
			  if (actionType === 'admin') {
				window.location.href = dest;
			  } else if(actionType === 'theme') {
				$j.post(dest, function(data) {
				    if ($j('a#permalinkClick').length >= 1) {
					    pLck = $j('a#permalinkClick').attr('name');
				    } else {
					    pLck = location.href.replace('#comments', ""), pLck = pLck.replace('#commentform', "");
				    }
				    setTimeout(function(){
					    $j('#ajaxContent2').load(pLck + ' #ajaxContent1');
				    }, 1600);
				});
			  }
			});
		});
	});
	if (actionType === 'admin') {
		$j('form.input-confirm').submit(function (e) {
				e.preventDefault();
			var dest = $j(this).attr('action');
				confirmer("Are you sure?", function () {
					window.location.href = dest;
				});
			});
	}
}


/* A function to apply ckeditor instances to any textarea that has the class ck-textarea */
function useCkeditor(i) {
    if ( editor )
	return;
    editorActive = 2;
    CKEDITOR.plugins.addExternal('pfxGeSHi', pfxSiteUrl + 'admin/jscript/editor-plugins/pfxGeSHi/');
    CKEDITOR.plugins.addExternal('pfxPageBreak', pfxSiteUrl + 'admin/jscript/editor-plugins/pfxPageBreak/');
    CKEDITOR.plugins.addExternal('pfxImage', pfxSiteUrl + 'admin/jscript/editor-plugins/pfxImage/');
    CKEDITOR.plugins.addExternal('pfxFind', pfxSiteUrl + 'admin/jscript/editor-plugins/pfxFind/');
    CKEDITOR.plugins.addExternal('pfxLink', pfxSiteUrl + 'admin/jscript/editor-plugins/pfxLink/');
    CKEDITOR.plugins.addExternal('codemirror', pfxSiteUrl + 'admin/jscript/editor-plugins/codemirror/');
    CKEDITOR.config.customConfig = pfxSiteUrl + 'admin/jscript/ckeditor-config.js';
    if (i === 'wysiwyg') {
	    editorMode = 1;
	    editor = CKEDITOR.replaceAll('ck-textarea');
    } else {
	if (i === 'source') {
	    editorMode = 2;
	    editor = CKEDITOR.replaceAll('ck-textarea-php');
	}
    }

}  /* End function useCkeditor */


function CloseEditor(i) {
	var oEditor = CKEDITOR.instances[i];
	oEditor.execCommand( 'mirrorSnapshot' );
	oEditor.destroy();
	editor = null;
    $j(function() {
	$j('div.editor-controls').hide();
    });
}


function SwitchToolBar(i) {
	var oEditor = CKEDITOR.instances[i];
	oEditor.execCommand( 'mirrorSnapshot' );
	if (CKEDITOR.instances[i].mode == 'wysiwyg') {
	      editorMode = 1;
	} else {
	      editorMode = 2;
	}
	oEditor.destroy();
	editor = null;

	if (pfxToolBar == 'Small') {
		pfxToolBar = 'Large';
	} else {
		pfxToolBar = 'Small';
	}
	editor = CKEDITOR.replace(i);

}


function MirrorUpdate() {

	for (i in CKEDITOR.instances) {
	    CKEDITOR.instances[i].execCommand( 'mirrorSnapshot' );
	}
	
}



function switchUploader() {

	$j('form#upload_form').slideDown('slow', 'easeInOutQuad');
	$j('input#upload-control').remove();
	
}



function showNicEdit() {

	nicOptions = {
	      iconsPath : pfxSiteUrl + 'admin/admin/theme/images/png/nicEditorIcons.png',
	      buttonList : [/*'fontSize', 'fontFamily', 'fontFormat', */'bold','italic','underline','strikeThrough', /*'left', 'center', 'right', 'justify', 'indent', 'outdent', */'ol', 'ul', 'image', 'link', /*'unlink', */'bgcolor', 'forecolor', 'xhtml' ]
	};
	if (actionType == 'admin') {
		bkLib.onDomLoaded(function() { nicEditors.allTextAreas(nicOptions) });
	} else {
		nicEditors.allTextAreas(nicOptions);
	}
	edtChk = 1;
	
}


function showCodeM() {

	$j('.form_item_textarea-codemirror').each(function(index) {
	/* http://codemirror.net/manual.html */
	var area = $j(this).attr('id'), thisPath = pfxSiteUrl + 'admin/jscript/editor-plugins/codemirror/', holderHeight = '18em', CodeMirrorConfig = {
		stylesheet: thisPath + 'css/colors.css',
		path: thisPath + 'js/',
		parserfile: 'parsemixed.js',
		passDelay: 300,
		passTime: 35,
		continuousScanning: 1000, /* Numbers lower than this suck megabytes of memory very quickly out of firefox */
		undoDepth: 1,
		height: holderHeight, /* Adapt to holder height */
		textWrapping: true,
		lineNumbers: false,
		enterMode: 'flat'
	};
	CodeMirror.fromTextArea(area, CodeMirrorConfig);
	}).removeClass('form_item_textarea-codemirror');
	edtChk = 1;
}



function editorCheck() {

	if ($j('.ck-textarea').length >= 1) {
	    useCkeditor('wysiwyg');
	} /* If ckeditor.js is loaded, lets see if we can use it on anything... */
	if ($j('.ck-textarea-php').length >= 1) {
	    useCkeditor('source');
	} /* If ckeditor.js is loaded, lets see if we can use it on anything... */
	if ($j('.form_item_textarea_no_ckeditor').length >= 1) {
		showNicEdit();
	} /* Load nicEdit */
	if ($j('.form_item_textarea-codemirror').length >= 1) {
		showCodeM();
	} /* Load codeMirror */

}



$j(function() {

	actConfirm();
	if (actionType === 'admin') {
		editorCheck();
	}

}); /* End jQuery function */

$j(document).ready(function() {
  
	$j('body').append('<div id="confirm"><div class="header"><span>Confirm</span></div><div class="message"></div><div class="buttons"><div class="no simplemodal-close">No</div><div class="yes">Yes</div></div></div>');
	if ($j.support.htmlSerialize === true) {
		var tagsLoaded = 1, tagsNum = $j('input#tags').length + $j('input#page_blocks').length, tagselect = { backgroundColor : '#EF2929', color : '#ffffff', padding : '1px 4px 1px 4px' }, tagnorm = { padding : '1px 4px 1px 4px', backgroundColor: '#ffffff', color: '#EF2929' }, tagnormhover = { backgroundColor : '#EF2929', color : '#ffffff', padding : '1px 4px 1px 4px', textDecoration: 'none' };

		if (tagsNum >= 1) {
			if (tagsLoaded === 1) {
				tagsLoaded = 2;
				applyTags(tagnorm, tagselect, tagnormhover);
			}
		}

		$j(document).ajaxStop( function(r, s) {

			if (tagsLoaded === 1) {
				tagsNum = $j('input#tags').length + $j('input#page_blocks').length;
				if (tagsNum >= 1) {
					tagsLoaded = 2;
					applyTags(tagnorm, tagselect, tagnormhover);
				}
			}

		}); /* End ajaxStop function */

	}

	$j(document).ajaxStop(function() {
		actConfirm();
		if (editorActive === 1) {
			if (actionType === 'admin') {
				editorCheck();
			}
		}
	});

});

