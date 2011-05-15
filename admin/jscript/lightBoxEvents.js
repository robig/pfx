    /* jQuery Sexy LightBox plugin */
    /* http://code.google.com/p/sexy-lightbox/ */

    var scriptsLoaded = 1, lightboxNumber = null;


function lightboxAction() {

    $j(function() {
	$j('img.lightbox').not('a img.lightbox').each(function(i) {
	  var lbImg = $j(this).attr('src');
	  $j(this).wrap('<a href="' + lbImg + '" rel="lightbox[group1]"></a>');
	});
	SexyLightbox.initialize({
	  color : 'white',
	  find : 'lightbox',
	  dir : pfxSiteUrl + 'admin/admin/theme/images',
	  background : 'bg.png',
	  backgroundIE : 'bg.gif'
	});


	return false;

    });  /* End jQuery function */


};  /* End function lightboxAction */


/* Load the scripts dynamically using ajax if not already loaded */
function includeLightboxDeps() {

    scriptsLoaded = 2; /* Set the loaded variable to 2 to indicate that the action to load the scripts has taken place; to avoid a loop. */

    $j(function() {
	$j.ajaxSetup('async', false); /* Set jQuery to load the scripts synchronously */
		/* Load easing.js via ajax */
		$j.getScript(pfxSiteUrl + 'admin/jscript/easing.js', function(){
			/* Load jQuerySwfobject.js via ajax */
			$j.getScript(pfxSiteUrl + 'admin/jscript/lightbox.js', function(){
				/* Load lightbox.js via ajax after loading jQuerySwfobject.js, using a callback */
				$j.ajaxSetup('async', true);  /* Set jQuery back to load the scripts asynchronously (The default.) */
			});  /* End load lightbox.js function */
		});  /* End load easing.js function */
    });  /* End jQuery function */


};  /* End function includeLightboxDeps */


/* The main jQuery function where the we will try to detect if we need to load the script files */
$j(function() {

    lightboxNumber = $j('.lightbox').length;

    if (lightboxNumber >= 1) { includeLightboxDeps(); /* Use the plugin and load the includes */ }

    $j(document).ajaxStop( function(r, s) {

	lightboxNumber = $j('.lightbox').length;
	if (scriptsLoaded == 1) {

	    if (lightboxNumber >= 1) { includeLightboxDeps(); /* Use the plugin and load the includes */ }

	} else if (scriptsLoaded == 2) {

	    if (lightboxNumber >= 1) { lightboxAction(); /* Use the plugin but don't load load the includes */ }

	}

    }); /* End ajaxStop function */

}); /* End jQuery function */
