/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var pfxImageDialog = function( editor, dialogType )
	{
		// Load pfxImage preview.
		var IMAGE = 1,
			LINK = 2,
			CLEANUP = 8,
			regexGetSize = /^\s*(\d+)((px)|\%)?\s*$/i,
			regexGetSizeOrEmpty = /(^\s*(\d+)((px)|\%)?\s*$)|^$/i,
			pxLengthRegex = /^\d+px$/;

		// Custom commit dialog logic, where we're intended to give inline style
		// field (txtdlgGenStyle) higher priority to avoid overwriting styles contribute
		// by other fields.
		function commitContent()
		{
			var args = arguments;
			var inlineStyleField = this.getContentElement( 'advanced', 'txtdlgGenStyle' );
			inlineStyleField && inlineStyleField.commit.apply( inlineStyleField, args );

			this.foreach( function( widget )
			{
				if ( widget.commit &&  widget.id != 'txtdlgGenStyle' )
					widget.commit.apply( widget, args );
			});
		}

		// Avoid recursions.
		var incommit;

		// Synchronous field values to other impacted fields is required, e.g. border
		// size change should alter inline-style text as well.
		function commitInternally( targetFields )
		{
			if ( incommit )
				return;

			incommit = 1;

			var dialog = this.getDialog(),
				element = dialog.pfxImageElement;
			if ( element )
			{
				// Commit this field and broadcast to target fields.
				this.commit( IMAGE, element );

				targetFields = [].concat( targetFields );
				var length = targetFields.length,
					field;

				for ( var i = 0; i < length; i++ )
				{
					field = dialog.getContentElement.apply( dialog, targetFields[ i ].split( ':' ) );
					// May cause recursion.
					field && field.setup( IMAGE, element );
				}
			}

			incommit = 0;
		}

		var onImgLoadEvent = function()
		{
			// pfxImage is ready.
			var original = this.originalElement;
			original.setCustomData( 'isReady', 'true' );
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			if ( this.firstLoad )
				this.firstLoad = false;
		};

		var onImgLoadErrorEvent = function()
		{
			// Error. pfxImage is not loaded.
			var original = this.originalElement;
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );
		};

		return {
			title : ( dialogType == 'pfxImage' ) ? editor.lang.image.title : editor.lang.image.titleButton,
			minWidth : 420,
			minHeight : 310,
			onShow : function()
			{
				this.pfxImageElement = false;
				this.linkElement = false;

				// Default: create a new element.
				this.pfxImageEditMode = false;
				this.linkEditMode = false;

				this.lockRatio = true;
				this.firstLoad = true;
				this.addLink = false;

				var editor = this.getParentEditor(),
					sel = this.getParentEditor().getSelection(),
					element = sel.getSelectedElement(),
					link = element && element.getAscendant( 'a' );

				// Copy of the pfxImage
				this.originalElement = editor.document.createElement( 'img' );
				this.originalElement.setAttribute( 'alt', '' );
				this.originalElement.setCustomData( 'isReady', 'false' );

				if ( link )
				{
					this.linkElement = link;
					this.linkEditMode = true;

					// Look for pfxImage element.
					var linkChildren = link.getChildren();
					if ( linkChildren.count() == 1 )			// 1 child.
					{
						var childTagName = linkChildren.getItem( 0 ).getName();
						if ( childTagName == 'img' || childTagName == 'input' )
						{
							this.pfxImageElement = linkChildren.getItem( 0 );
							if ( this.pfxImageElement.getName() == 'img' )
								this.pfxImageEditMode = 'img';
							else if ( this.pfxImageElement.getName() == 'input' )
								this.pfxImageEditMode = 'input';
						}
					}
					// Fill out all fields.
					if ( dialogType == 'pfxImage' )
						this.setupContent( LINK, link );
				}

				if ( element && element.getName() == 'img' && !element.getAttribute( '_cke_realelement' )
					|| element && element.getName() == 'input' && element.getAttribute( 'type' ) == 'pfxImage' )
				{
					this.pfxImageEditMode = element.getName();
					this.pfxImageElement = element;
				}

				if ( this.pfxImageEditMode )
				{
					// Use the original element as a buffer from  since we don't want
					// temporary changes to be committed, e.g. if the dialog is canceled.
					this.cleanpfxImageElement = this.pfxImageElement;
					this.pfxImageElement = this.cleanpfxImageElement.clone( true, true );

					// Fill out all fields.
					this.setupContent( IMAGE, this.pfxImageElement );
				}
				else
					this.pfxImageElement =  editor.document.createElement( 'img' );

			},
			onOk : function()
			{
				// Edit existing pfxImage.
				if ( this.pfxImageEditMode )
				{
					var imgTagName = this.pfxImageEditMode;

					// pfxImage dialog and Input element.
					if ( dialogType == 'pfxImage' && imgTagName == 'input' && confirm( editor.lang.image.button2Img ) )
					{
						// Replace INPUT-> IMG
						imgTagName = 'img';
						this.pfxImageElement = editor.document.createElement( 'img' );
						this.pfxImageElement.setAttribute( 'alt', '' );
						editor.insertElement( this.pfxImageElement );
					}
					// pfxImageButton dialog and pfxImage element.
					else if ( dialogType != 'pfxImage' && imgTagName == 'img' && confirm( editor.lang.image.img2Button ))
					{
						// Replace IMG -> INPUT
						imgTagName = 'input';
						this.pfxImageElement = editor.document.createElement( 'input' );
						this.pfxImageElement.setAttributes(
							{
								type : 'pfxImage',
								alt : ''
							}
						);
						editor.insertElement( this.pfxImageElement );
					}
					else
					{
						// Restore the original element before all commits.
						this.pfxImageElement = this.cleanpfxImageElement;
						delete this.cleanpfxImageElement;
					}
				}
				else	// Create a new pfxImage.
				{
					// pfxImage dialog -> create IMG element.
					if ( dialogType == 'pfxImage' )
						this.pfxImageElement = editor.document.createElement( 'img' );
					else
					{
						this.pfxImageElement = editor.document.createElement( 'input' );
						this.pfxImageElement.setAttribute ( 'type' ,'pfxImage' );
					}
					this.pfxImageElement.setAttribute( 'alt', '' );
				}

				// Create a new link.
				if ( !this.linkEditMode )
					this.linkElement = editor.document.createElement( 'a' );

				// Set attributes.
				this.commitContent( IMAGE, this.pfxImageElement );
				this.commitContent( LINK, this.linkElement );

				// Remove empty style attribute.
				if ( !this.pfxImageElement.getAttribute( 'style' ) )
					this.pfxImageElement.removeAttribute( 'style' );

				// Insert a new pfxImage.
				if ( !this.pfxImageEditMode )
				{
					if ( this.addLink )
					{
						//Insert a new Link.
						if ( !this.linkEditMode )
						{
							editor.insertElement(this.linkElement);
							this.linkElement.append(this.pfxImageElement, false);
						}
						else	 //Link already exists, pfxImage not.
							editor.insertElement(this.pfxImageElement );
					}
					else
						editor.insertElement( this.pfxImageElement );
				}
				else		// pfxImage already exists.
				{
					//Add a new link element.
					if ( !this.linkEditMode && this.addLink )
					{
						editor.insertElement( this.linkElement );
						this.pfxImageElement.appendTo( this.linkElement );
					}
					//Remove Link, pfxImage exists.
					else if ( this.linkEditMode && !this.addLink )
					{
						editor.getSelection().selectElement( this.linkElement );
						editor.insertElement( this.pfxImageElement );
					}
				}
			},
			onLoad : function()
			{
				if ( dialogType != 'pfxImage' )
					this.hidePage( 'Link' );		//Hide Link tab.
				var doc = this._.element.getDocument();

				this.commitContent = commitContent;
			},
			onHide : function()
			{
				if ( this.originalElement )
				{
					this.originalElement.removeListener( 'load', onImgLoadEvent );
					this.originalElement.removeListener( 'error', onImgLoadErrorEvent );
					this.originalElement.removeListener( 'abort', onImgLoadErrorEvent );
					this.originalElement.remove();
					this.originalElement = false;		// Dialog is closed.
				}

				delete this.pfxImageElement;
			},
			contents : [
				{
					id : 'info',
					label : editor.lang.image.infoTab,
					accessKey : 'I',
					elements :
					[
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'hbox',
									align : 'right',
									children :
									[
										{
											id : 'txtUrl',
											type : 'text',
											label : editor.lang.common.url,
											required: true,
											onChange : function()
											{
												var dialog = this.getDialog(),
													newUrl = this.getValue();

												//Update original pfxImage
												if ( newUrl.length > 0 )	//Prevent from load before onShow
												{
													dialog = this.getDialog();
													var original = dialog.originalElement;

													original.setCustomData( 'isReady', 'false' );
												}
											},
											setup : function( type, element )
											{
												if ( type == IMAGE )
												{
													var url = element.getAttribute( '_cke_saved_src' ) || element.getAttribute( 'src' );
													var field = this;

													field.setValue( url );		// And call this.onChange()
													// Manually set the initial value.(#4191)
													field.setInitValue();
													field.focus();
												}
											},
											commit : function( type, element )
											{
												if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
												{
													element.setAttribute( '_cke_saved_src', decodeURI( this.getValue() ) );
													element.setAttribute( 'src', decodeURI( this.getValue() ) );
												}
												else if ( type == CLEANUP )
												{
													element.setAttribute( 'src', '' );	// If removeAttribute doesn't work.
													element.removeAttribute( 'src' );
												}
											},
											validate : CKEDITOR.dialog.validate.notEmpty( editor.lang.image.urlMissing )
										}
									]
								}
							]
						},
 										{
											type : 'button',
											id : 'browse',
											align : 'center',
											label : editor.lang.common.browseServer,
											hidden : true,
											filebrowser : 'info:txtUrl'
										},
						{
							id : 'txtAlt',
							type : 'text',
							label : editor.lang.image.alt,
							accessKey : 'T',
							'default' : 'Image',
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'alt' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == CLEANUP )
								{
									element.removeAttribute( 'alt' );
								}
							}
						}
					]
				},
				{
					id : 'Link',
					label : editor.lang.link.title,
					padding : 0,
					elements :
					[
						{
							id : 'txtUrl',
							type : 'text',
							label : editor.lang.common.url,
							style : 'width: 100%',
							'default' : '',
							setup : function( type, element )
							{
								if ( type == LINK )
								{
									var href = element.getAttribute( '_cke_saved_href' );
									if ( !href )
										href = element.getAttribute( 'href' );
									this.setValue( href );
								}
							},
							commit : function( type, element )
							{
								if ( type == LINK )
								{
									if ( this.getValue() || this.isChanged() )
									{
										element.setAttribute( '_cke_saved_href', decodeURI( this.getValue() ) );
										element.setAttribute( 'href', 'javascript:void(0)/*' +
											CKEDITOR.tools.getNextNumber() + '*/' );

										if ( this.getValue() || !editor.config.pfxImage_removeLinkByEmptyURL )
											this.getDialog().addLink = true;
									}
								}
							}
						},
						{
							type : 'button',
							id : 'browse',
							filebrowser :
							{
								action : 'Browse',
								target: 'Link:txtUrl',
								url: ( undefined !== editor.config.filebrowserImageBrowseLinkUrl ) ? editor.config.filebrowserImageBrowseLinkUrl : editor.config.filebrowserBrowseUrl || editor.config.filebrowserImageBrowseUrl
							},
							hidden : true,
							label : editor.lang.common.browseServer
						},
						{
							id : 'cmbTarget',
							type : 'select',
							label : editor.lang.common.target,
							'default' : '',
							items :
							[
								[ editor.lang.common.notSet , ''],
								[ editor.lang.common.targetNew , '_blank'],
								[ editor.lang.common.targetTop , '_top'],
								[ editor.lang.common.targetSelf , '_self'],
								[ editor.lang.common.targetParent , '_parent']
							],
							setup : function( type, element )
							{
								if ( type == LINK )
									this.setValue( element.getAttribute( 'target' ) );
							},
							commit : function( type, element )
							{
								if ( type == LINK )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'target', this.getValue() );
								}
							}
						}
					]
				},
				{
					id : 'Upload',
					hidden : true,
					filebrowser : 'uploadButton',
					label : editor.lang.image.upload,
					elements :
					[
						{
							type : 'file',
							id : 'upload',
							label : editor.lang.image.btnUpload,
							style: 'height:40px',
							size : 38
						},
						{
							type : 'fileButton',
							id : 'uploadButton',
							filebrowser : 'info:txtUrl',
							label : editor.lang.image.btnUpload,
							'for' : [ 'Upload', 'upload' ]
						}
					]
				},
				{
					id : 'advanced',
					label : editor.lang.common.advancedTab,
					elements :
					[
						{
							type : 'hbox',
							children :
							[
								{
									type : 'text',
									id : 'linkId',
									label : editor.lang.common.id,
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'id' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'id', this.getValue() );
										}
									}
								},
								{
									type : 'text',
									id : 'txtLangCode',
									label : editor.lang.common.langCode,
									'default' : '',
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'lang' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'lang', this.getValue() );
										}
									}
								}
							]
						},
						{
							type : 'text',
							id : 'txtGenLongDescr',
							label : editor.lang.common.longDescr,
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'longDesc' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'longDesc', this.getValue() );
								}
							}
						},
						{
							type : 'hbox',
							widths : [ '50%', '50%' ],
							children :
							[
								{
									type : 'text',
									id : 'txtGenClass',
									label : editor.lang.common.cssClass,
									'default' : editorImageClass,
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'class' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'class', this.getValue() );
										}
									}
								},
								{
									type : 'text',
									id : 'txtGenTitle',
									label : editor.lang.common.advisoryTitle,
									'default' : '',
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'title' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'title', this.getValue() );
										}
										else if ( type == CLEANUP )
										{
											element.removeAttribute( 'title' );
										}
									}
								}
							]
						},
						{
							type : 'text',
							id : 'txtdlgGenStyle',
							label : editor.lang.common.cssStyle,
							'default' : '',
							setup : function( type, element )
							{
								if ( type == IMAGE )
								{
									var genStyle = element.getAttribute( 'style' );
									if ( !genStyle && element.$.style.cssText )
										genStyle = element.$.style.cssText;
									this.setValue( genStyle );

									var height = element.$.style.height,
										width = element.$.style.width,
										aMatchH  = ( height ? height : '' ).match( regexGetSize ),
										aMatchW  = ( width ? width : '').match( regexGetSize );

									this.attributesInStyle =
									{
										height : !!aMatchH,
										width : !!aMatchW
									};
								}
							},
							commit : function( type, element )
							{
								if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
								{
									element.setAttribute( 'style', this.getValue() );
								}
							}
						}
					]
				}
			]
		};
	};

	CKEDITOR.dialog.add( 'pfxImage', function( editor )
		{
			return pfxImageDialog( editor, 'pfxImage' );
		});

	CKEDITOR.dialog.add( 'pfxImagebutton', function( editor )
		{
			return pfxImageDialog( editor, 'pfxImagebutton' );
		});
})();
