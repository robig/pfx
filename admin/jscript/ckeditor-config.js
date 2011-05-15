/*
PFX advanced ckeditor config - Clear your browser cache after every edit or you won't see the changes.
*/

	var minBar = [ 'Maximize', 'Italic', 'Bold', 'Underline', 'Strike', '-', 'BulletedList', 'JustifyCenter', '-', 'pfxLink', 'pfxImage', 'BGColor', 'TextColor', 'SpecialChar', 'pfxPageBreak', '-', 'Redo', 'Undo', 'Source' ];


/* Tell ckeditor how we want it to be configured */
CKEDITOR.editorConfig = function(config) {

	config.baseHref = pfxSiteUrl;
	config.startupFocus = false;
/*	config.docType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'; */ /* That's the default */
	config.contentsCss = pfxSiteUrl + 'admin/admin/theme/ckPfx/contents.css'; /* Fixes annoying v8 or webkit bug where if you use a local path, it doesn't load the file contents.css */ /* Was : './admin/admin/theme/ckPfx/contents.css' */
	config.filebrowserBrowseUrl = pfxSiteUrl + 'admin/index.php?s=publish&x=filemanager&ck=1&ckfile=1';
/* 	config.filebrowserImageBrowseUrl = '?s=publish&x=filemanager&ck=1&ckimage=1'; */ /* If the regular CKEditor image plugin is used instead */
	config.filebrowserPfxImageBrowseUrl = pfxSiteUrl + 'admin/index.php?s=publish&x=filemanager&ck=1&ckimage=1';
	config.filebrowserWindowWidth = '800';
        config.filebrowserWindowHeight = '600';
	config.skin = 'ckPfx,../../admin/theme/ckPfx/';
	config.height = '20em';
	config.protectedSource.push(/<\?[\s\S]*?\?>/g); /* Protect PHP Code from being stripped when moving to source mode */
	config.extraPlugins = 'pfxPageBreak,pfxGeSHi,pfxImage,codemirror,pfxLink,pfxFind';
	config.emailProtection = 'encode'; /* Protect email links from spammers */
	config.resize_enabled = false; /* Many will never want to or even realise that they can, click full screen instead */
	config.colorButton_enableMore = true;
	config.autogrow = false;
	config.removePlugins = 'autogrow,contextmenu,elementspath,resize,Link,Find,cut,copy,paste,save,pastetext,pastefromword,print,spellchecker,form,checkbox,radio,textfield,textarea,flash,select,button,imagebutton,hiddenfield,subscript,superscript,creatediv,anchor,table,pagebreak,image,forms'; /* elementspath is the plugin responsible for the name of elements at the bottom of the editor */
	config.toolbarCanCollapse = false; /* Remove the collapsing button of the toolbar */
        config.toolbar_Small = [
        minBar
        ];
        config.toolbar_Large = [
	['Font', 'FontSize', 'Format', 'Styles', '-', 'ShowBlocks', 'Templates', 'RemoveFormat', 'Smiley', '-', 'About'],
	'/',
	['NumberedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Outdent', 'Indent', '-', 'pfxFind', 'pfxReplace', 'Scayt', '-', 'HorizontalRule', 'Blockquote', '-', 'NewPage', '-', 'Preview', '-', 'SelectAll', '-', 'pfxGeSHi'],
	'/',
	minBar
        ];
	config.toolbar = pfxToolBar;
	if (editorBrEnterMode == 'yes') {
	config.enterMode = CKEDITOR.ENTER_BR; /* Enter key means br not p */
	config.shiftEnterMode = CKEDITOR.ENTER_P; /* Paragraphs are now made by pressing shift and enter together instead */
	config.dialog_backgroundCoverColor = 'black';
	config.disableObjectResizing = true;
	config.resize_enabled = false;
	config.paste_removeStyles = false;
	config.paste_removeStylesWebkit = true;
	config.dialog_backgroundCoverOpacity = 0.8;
	}
	/* Define changes to the advanced configuration here. For example: */
	/* config.language = 'en-gb'; */ /* Not required. ckeditor automatically selects language based on what your browser is set to */
	/* config.contentsLangDirection = 'rtl'; */  /* Unhash this setting if you are using a right to left language like Japanese */

};


