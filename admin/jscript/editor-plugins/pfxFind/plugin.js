/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'pfxFind',
{
	init : function( editor )
	{
		var forms = CKEDITOR.plugins.find;
		editor.ui.addButton( 'pfxFind',
			{
				label : editor.lang.findAndReplace.find,
				command : 'pfxFind'
			});
		var findCommand = editor.addCommand( 'pfxFind', new CKEDITOR.dialogCommand( 'pfxFind' ) );
		findCommand.canUndo = false;

		editor.ui.addButton( 'pfxReplace',
			{
				label : editor.lang.findAndReplace.replace,
				command : 'pfxReplace'
			});
		var replaceCommand = editor.addCommand( 'pfxReplace', new CKEDITOR.dialogCommand( 'pfxReplace' ) );
		replaceCommand.canUndo = false;

		CKEDITOR.dialog.add( 'pfxFind',	this.path + 'dialogs/pfxFind.js' );
		CKEDITOR.dialog.add( 'pfxReplace',	this.path + 'dialogs/pfxFind.js' );
	},

	requires : [ 'styles' ]
} );

/**
 * Defines the style to be used to highlight results with the find dialog.
 * @type Object
 * @default { element : 'span', styles : { 'background-color' : '#004', 'color' : '#fff' } }
 * @example
 * // Highlight search results with blue on yellow.
 * config.find_highlight =
 *     {
 *         element : 'span',
 *         styles : { 'background-color' : '#ff0', 'color' : '#00f' }
 *     };
 */
CKEDITOR.config.find_highlight = { element : 'span', styles : { 'background-color' : '#004', 'color' : '#fff' } };
