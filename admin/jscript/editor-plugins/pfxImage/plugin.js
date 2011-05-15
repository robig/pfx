/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file pfxImage plugin
 */

CKEDITOR.plugins.add( 'pfxImage',
{
	init : function( editor )
	{
		var pluginName = 'pfxImage';

		// Register the dialog.
		CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/pfxImage.js' );

		// Register the command.
		editor.addCommand( pluginName, new CKEDITOR.dialogCommand( pluginName ) );

		// Register the toolbar button.
		editor.ui.addButton( 'pfxImage',
			{
				label : editor.lang.common.image,
				command : pluginName
			});

		editor.on( 'doubleclick', function( evt )
			{
				var element = evt.data.element;

				if ( element.is( 'img' ) && !element.getAttribute( '_cke_realelement' ) )
					evt.data.dialog = 'pfxImage';
			});

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems )
		{
			editor.addMenuItems(
				{
					pfxImage :
					{
						label : editor.lang.image.menu,
						command : 'pfxImage',
						group : 'pfxImage'
					}
				});
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu )
		{
			editor.contextMenu.addListener( function( element, selection )
				{
					if ( !element || !element.is( 'img' ) || element.getAttribute( '_cke_realelement' ) || element.isReadOnly() )
						return null;

					return { pfxImage : CKEDITOR.TRISTATE_OFF };
				});
		}
	}
} );

/**
 * Whether to remove links when emptying the link URL field in the pfxImage dialog.
 * @type Boolean
 * @default true
 * @example
 * config.pfxImage_removeLinkByEmptyURL = false;
 */
CKEDITOR.config.pfxImage_removeLinkByEmptyURL = true;

/**
 *  Padding text to set off the pfxImage in preview area.
 * @name CKEDITOR.config.pfxImage_previewText
 * @type String
 * @default "Lorem ipsum dolor..." placehoder text.
 * @example
 * config.pfxImage_previewText = CKEDITOR.tools.repeat( '___ ', 100 );
 */
