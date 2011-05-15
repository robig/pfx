/**
 * @fileOverview The "codemirror" plugin. It's indented to enhance the
 *  "sourcearea" editing mode, which displays source code with
 *  syntax highlighting and optional line numbers.
 * @see http://codemirror.net/ for the CodeMirror editor which this
 *  plugin is using.
 */

CKEDITOR.plugins.add( 'codemirror', {
	requires : [ 'sourcearea' ],

	/**
	 * This's a command-less plugin, auto loaded as soon as switch to 'source' mode  
	 * and 'textarea' plugin is activeated.
	 * @param {Object} editor
	 */

	init : function( editor ) {
		var thisPath = this.path;
		editor.on( 'mode', function() {
			if ( editor.mode == 'source' ) {
			  /* We can disable plugins like this : */
			  var noUsePlugs = [ 'newpage', 'preview', 'selectAll' ];
			  for (i in noUsePlugs) {
				  editor.getCommand(noUsePlugs[i]).setState(CKEDITOR.TRISTATE_DISABLED);
			  }
			  // if ( CKEDITOR.env.gecko ) { /* Doesn't work in Firefox - So disable it */
			 //   editor.getCommand('maximize').setState(CKEDITOR.TRISTATE_DISABLED);
			 // } /* Should work but doesn't */
					  var holderHeight = editor.textarea.$.clientHeight + 'px';
					  /* http://codemirror.net/manual.html */
					  var codemirrorInit =
					  CodeMirror.fromTextArea( editor.textarea.$, {
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
					} );
					
					/* Commit source data back into 'source' mode. */
					editor.on( 'beforeCommandExec', function( e ){
						/* Listen to this event once. */
						e.removeListener();
						editor.textarea.setValue( codemirrorInit.getCode() );
						editor.fire( 'dataReady' );
					} );

				CKEDITOR.plugins.mirrorSnapshotCmd = {
					exec : function( editor ) {
						if ( editor.mode == 'source' ) {
							editor.textarea.setValue( codemirrorInit.getCode() );
							editor.fire( 'dataReady' );
						}
					},
					async : true,
					canUndo : false,
					editorFocus : false
				}

				editor.addCommand( 'mirrorSnapshot', CKEDITOR.plugins.mirrorSnapshotCmd );

			}
		} );

		editor.on( 'instanceReady', function(e) {
			e.removeListener();
			if ( editor.mode == 'wysiwyg' ) {
				editor.ogHolderHeight = editor.config.height;
				if (editorMode === 2) {
					editor.execCommand('source');
				}
			}
			if ( editor.mode == 'source' ) {
				editor.ogHolderHeight = editor.textarea.$.clientHeight + 'px';
			}
		} );

		editor.on( 'resize', function() {
			if ( editor.mode == 'source' ) {
				var holderHeight = editor.textarea.getParent().$.clientHeight + 'px';
				var allDivs = new Array();
				var allDivs = document.getElementsByTagName('div');
				if (editor.getCommand( 'maximize' ).state === 2) {
					var newHeight = holderHeight;
				} else {
					var newHeight = editor.ogHolderHeight;
				}
				for (i = 0; i < allDivs.length; i++) {
					if (allDivs[i].className == 'CodeMirror-wrapping') {
						allDivs[i].style.height = newHeight;
					}
				}
			}
		} );

	}

});
