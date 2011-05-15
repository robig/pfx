    /* reCaptcha script loader */

    var captchaLoaded = 1, captchaNum = $j('#captchadiv').length;


function runCaptcha() {

	Recaptcha.create(captchaPubkey, 'recaptcha_div', { theme : 'custom', custom_theme_widget: 'captchadiv' });
}



function grabCaptcha() {

	$j.getScript('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js', function() {
		runCaptcha();
	});
}



$j(document).ready(function() {

	if (captchaNum >= 1) {
		if (captchaLoaded === 1) {
			captchaLoaded = 2;
			grabCaptcha();
		}
	}

	$j(document).ajaxStop( function(r, s) {

		if (captchaLoaded === 1) {
			captchaNum = $j('#captchadiv').length;
			if (captchaNum >= 1) {
				captchaLoaded = 2;
				grabCaptcha();
			}
		} else if (captchaLoaded === 2) {
			captchaNum = $j('#captchadiv').length;
			if (captchaNum >= 1) {
				runCaptcha();
			}
		}

	}); /* End ajaxStop function */

}); /* End document ready function */
