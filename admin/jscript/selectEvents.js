    /* jQuery select box functions */

    /* http://www.brainfault.com/2008/02/10/new-release-of-jquery-selectbox-replacement/ */

    var isLoaded = 1, selectNumber = null;



function styleSelect() {

    $j('select').selectbox();


};  /* End function styleSelect */



function includeSelectPlugin() {

    if (isLoaded == 1) { 
	isLoaded = 2; /* Set the loaded variable to 2 to indicate that the action to load the scripts has taken place; to avoid a loop. */

	$j(function() {

	    $j.ajaxSetup('async', false); /* Set jQuery to load the scripts synchronously */
		/* Load selectbox.js via ajax */
		$j.getScript('jscript/selectbox.js', function(){
		    /* Load jqueryForm.js via ajax after loading validate.js, using a callback */
			$j.ajaxSetup('async', true);  /* Set jQuery back to load the scripts asynchronously (The default.) */
				if ($j('select').length >= 1) { styleSelect(); /* Style the input selection box */ }
		});  /* End load selectbox.js function */

	});  /* End jQuery function */
    } else {
	styleSelect();
    }

};  /* End function includeSelectPlugin */


/* The main jQuery function where the script will try to detect if we need the script files */
$j(function() {

    selectNumber = $j('select').length;
    if (selectNumber >= 1) { includeSelectPlugin(); /* Use the plugin to style the input selection box */ }


}); /* End jQuery function */
