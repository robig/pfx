

var edtChk = 1;



function quickEdit(pageLock) {

    if ($j('a.quick-edit').length >= 1) {
	$j('a.quick-edit').each(function(index) {
	    var editLink = $j(this).attr('href');
		$j(this).click(function(event) {
		    event.preventDefault();
		    $j('#content').load(editLink + ' .admin_form');
		});
	});
    }

      function reAct(pageLock, i) {
	setTimeout(function(){
	$j('#ajaxContent2').load(pageLock + ' #ajaxContent1');
	edtChk = 1, i = false;
	}, 1600);
      }

      function transit(i) {
	      if (editorActive === 2) {
		      CKEDITOR.instances[i].updateElement();
	      }
      }

    if ($j('form').not('form#contactform').not('form#comment-form').length >= 1) {

if ($j('span.form_button_cancel').length >= 1) {
  $j('span.form_button_cancel').remove();
}
      var formAc = $j('form').attr('action'), i = $j('.form_item_textarea_ckeditor textarea').attr('id');

if (edtChk === 1) {
  edtChk = 2;
editorCheck();
} else {
if (editorActive === 2) {
	  CKEDITOR.instances = {};
    if ($j('table.cke_editor').length <= 0) {
	  editorCheck();
    }
}
  }

if ($j('a#permalinkClick').length >= 1) {
  pageLock = $j('a#permalinkClick').attr('name');
}
$j('select').selectbox();
	$j('form').validate( {
rules: {  
		title : {  
		    required : true  
		}  
	    },  
	    messages : {  
		title : 'Please add a title.'
	    },
	    submitHandler:  function(form) {  
var options = {
  beforeSubmit:  transit(i), /* Must send CKEditor it's updateElement function, it tries to do what this plugin does but after it, so it updates the textarea after the ajax submit, which no good. Thus, we do this first before submitting the form to fix it. */
url : pfxSiteUrl + 'admin/index.php' + formAc,
/*    success :   alert(queryString) */
    success :   reAct(pageLock, i)
};
		$j(form).ajaxSubmit(options);
		return false;

	    }

	});

	return false;
    }
}

$j(function() {

    quickEdit(false);

});

$j(document).ready(function() {
  
	var pageLock = location.href;
	$j(document).ajaxStop(function() {
		quickEdit(pageLock);
	});

});
