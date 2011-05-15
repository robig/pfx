/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Horizontal Page Break
 */

// Register a plugin named "pagebreak".
CKEDITOR.plugins.add( 'pfxPageBreak',
{
	lang : [ 'en' ],
	init : function( editor )
	{
		// Register the command.
		editor.addCommand( 'pfxPageBreak', CKEDITOR.plugins.pfxPageBreakCmd );

		// Register the toolbar button.
		editor.ui.addButton( 'pfxPageBreak',
			{
				label : editor.lang.langPfxPageBreak.label,
				command : 'pfxPageBreak'
			});

		// Add the style that renders our placeholder.
		editor.addCss(
			'img.cke_pfxPageBreak' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/pfxPageBreak.png' ) + ');' +
				'background-position: center center;' +
				'background-repeat: no-repeat;' +
				'clear: both;' +
				'display: block;' +
				'float: none;' +
				'width:100% !important; width:99.9% !important;' +
				'border-top: #999999 1px dotted;' +
				'border-bottom: #999999 1px dotted;' +
				'height: 5px !important;' +
				'page-break-after: always;' +

			'}' );
	},

	afterInit : function( editor )
	{
		// Register a filter to displaying placeholders after mode change.

		var dataProcessor = editor.dataProcessor,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		if ( dataFilter )
		{
			dataFilter.addRules(
				{
					elements :
					{
						span : function( element )
						{
							var attributes = element.attributes,
								style = attributes && attributes.style;

							if ( style && ( /page-break/i ).test( style ) )
								return editor.createFakeParserElement( element, 'cke_pfxPageBreak', 'span' );
						}
					}
				});
		}
	},

	requires : [ 'fakeobjects' ]
});

CKEDITOR.plugins.pfxPageBreakCmd =
{
	exec : function( editor )
	{
		// Create the element that represents a print break.
		var breakObject = CKEDITOR.dom.element.createFromHtml( '<span class="page-break"><!--more--></span>' );

		// Creates the fake image used for this element.
		breakObject = editor.createFakeElement( breakObject, 'cke_pfxPageBreak', 'span' );

		var ranges = editor.getSelection().getRanges( true );

		editor.fire( 'saveSnapshot' );

		for ( var range, i = ranges.length - 1 ; i >= 0; i-- )
		{
			range = ranges[ i ];

			if ( i < ranges.length -1 )
				breakObject = breakObject.clone( true );

			range.insertNode( breakObject );
			if ( i == ranges.length - 1 )
			{
				range.moveToPosition( breakObject, CKEDITOR.POSITION_AFTER_END );
				range.select();
			}

			var previous = breakObject.getPrevious();

			if ( previous && CKEDITOR.dtd[ previous.getName() ].span )
				breakObject.move( previous );
		}

		editor.fire( 'saveSnapshot' );
	}
};

CKEDITOR.plugins.setLang('pfxPageBreak', 'en', {
    langPfxPageBreak : {
	title: 'Page Break',
	label: 'Split the post with a more tag'
    }
});
