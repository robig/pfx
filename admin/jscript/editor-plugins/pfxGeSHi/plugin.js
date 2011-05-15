/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file GeSHi Syntax Highlighter
 */

/* Register a plugin named "pfxGeSHi". */
CKEDITOR.plugins.add( 'pfxGeSHi',
{

	requires: [ 'iframedialog' ],
	lang : [ 'en' ],

	init : function( editor )
	{

		var pluginName = 'pfxGeSHi';

		/* Register the dialog. */
		CKEDITOR.dialog.addIframe('GeSHi', 'GeSHi Parser',this.path + 'dialogs/dialog.php',500,300,function(){ /* oniframeload */ })

		var command = editor.addCommand( 'pfxGeSHi', new CKEDITOR.dialogCommand( 'GeSHi' ) );
		command.modes = { wysiwyg:1, source:0 };
		command.canUndo = true;

		/* Set the language and the command */
		editor.ui.addButton( 'pfxGeSHi',
			{
				label : editor.lang.langPfxGeSHi.label,
				command : pluginName
			});

	}

});

CKEDITOR.plugins.setLang('pfxGeSHi', 'en', {
    langPfxGeSHi : {
	title: 'GeSHi',
	label: 'Post syntax highlighted code'
    }
});
