/*
 http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3

*/
var temp="",tfield="";
function upswitch(a){temp=$j("#"+a).parent().html();tfield=a;$j("#"+a).parent().find(".more_upload").replaceWith("<span class='more_upload_start'><a href='#' onclick='cancel(); return false;' title='Cancel'>Cancel</a></span>");$j(".more_upload").hide();$j("#"+a).replaceWith('<form accept-charset="UTF-8" action="admin/modules/ajax_fileupload.php" method="post" id="'+a+'" class="inline_form" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {\'onStart\' : startCallback, \'onComplete\' : completeCallback})"><input type="file" name="upload[]" id="upload" size="18" /><input type="hidden" name="field" value="'+a+
'"><input type="submit" name="submit_upload" class="submit_upload" value="Upload" /><input type="hidden" name="MAX_FILE_SIZE" value="10240"></form>');$j(".form_submit").attr("disabled","true");$j("input#"+a+"_input").hide("fast");$j("div#"+tfield+"_container").remove()}
function cancel(){$j("#"+tfield).replaceWith(temp);$j(".more_upload").show();$j("select#"+tfield).show();$j(".form_submit").removeAttr("disabled");$j("#"+tfield).parent().find(".more_upload_start").replaceWith("");$j("#"+tfield).parent().find("input").replaceWith("");$j(".image_preview select").bind("change",preview);$j("select#"+tfield).selectbox()}
function startCallback(){$j("input#"+tfield+"_input").remove();$j("select#"+tfield).remove();$j(".submit_upload").attr("disabled","true");$j("#"+tfield).parent().find(".more_upload_start").replaceWith("<img src='admin/theme/images/spinner.gif' alt='loading' width='32' height='32' id='upload_wait'/>");return true}
function completeCallback(a){if(a){alert(a);$j(".submit_upload").removeAttr("disabled");$j("#upload").removeAttr("disabled");$j("#upload_wait").replaceWith("<span class='more_upload_start'><a href='#' onclick='cancel(); return false;' title='Cancel'>Cancel</a></span>")}else{$j("#upload_wait").replaceWith("");$j.browser.msie?$j.post("admin/modules/ajax_fileupload.php",{form:tfield,ie:"true"},function(b){$j("#"+tfield).replaceWith(b);$j(".more_upload").show();$j("select#"+tfield).selectbox()}):$j.post("admin/modules/ajax_fileupload.php",
{form:tfield},function(b){$j("#"+tfield).replaceWith(b);$j(".more_upload").show();$j("select#"+tfield).selectbox()});$j(".form_submit").removeAttr("disabled")}};