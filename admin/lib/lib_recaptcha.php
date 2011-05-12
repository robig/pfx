<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../../') );
}
/**
 * PFX: Pixie Fork Xtreme.
 * Copyright (C) 2010, Tony White
 *
 * Largely based on code derived from :
 *
 * Pixie: The Small, Simple, Site Maker.
 * 
 * Licence: GNU General Public License v3
 * Copyright (C) 2010, Scott Evans
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/
 *
 * Title: A PHP library that handles calling reCAPTCHA
 *
 * @package PFX
 * @copyright 2010 Tony White
 * @copyright 2010 reCAPTCHA -- http://recaptcha.net
 * @author Mike Crawford
 * @author Ben Maurer
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 * This is a PHP library that handles calling reCAPTCHA.
 *    - Documentation and latest version
 *          http://recaptcha.net/plugins/php/
 *    - Get a reCAPTCHA API Key
 *          http://recaptcha.net/api/getkey
 *    - Discussion group
 *          http://groups.google.com/group/recaptcha
 *
 * Copyright (c) 2010 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * The reCAPTCHA server URL's
 */
define('RECAPTCHA_API_SERVER', 'http://api.recaptcha.net');
define('RECAPTCHA_API_SECURE_SERVER', 'https://api-secure.recaptcha.net');
define('RECAPTCHA_VERIFY_SERVER', 'api-verify.recaptcha.net');
/**
 * Encodes the given data into a query string format
 * @param $data - array of string elements to be encoded
 * @return string - encoded request
 */
function _recaptcha_qsencode($data) {
	$req = "";
	foreach ($data as $key => $value)
		$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
	// Cut the last '&'
	$req = substr($req, 0, strlen($req) - 1);
	return $req;
}
/**
 * Submits an HTTP POST to a reCAPTCHA server
 * @param string $host
 * @param string $path
 * @param array $data
 * @param int port
 * @return array response
 */
function _recaptcha_http_post($host, $path, $data, $port = 80) {
	$req          = _recaptcha_qsencode($data);
	$http_request = "POST {$path} HTTP/1.0\r\n";
	$http_request .= "Host: {$host}\r\n";
	$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
	$http_request .= 'Content-Length: ' . strlen($req) . "\r\n";
	$http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
	$http_request .= "\r\n";
	$http_request .= $req;
	$response = '';
	if (FALSE == ($fs = fsockopen($host, $port, $errno, $errstr, 10))) {
		echo 'Could not open socket';
		return FALSE;
	}
	fwrite($fs, $http_request);
	while (!feof($fs))
		$response .= fgets($fs, 1160); // One TCP-IP packet
	fclose($fs);
	$response = explode("\r\n\r\n", $response, 2);
	return $response;
}
/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is NULL)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is FALSE)
 
 * @return string - The HTML to be embedded in the user's form.
 */
function recaptcha_get_html($pubkey, $error = NULL, $use_ssl = FALSE) {
	if ($pubkey == NULL OR $pubkey == '') {
		echo 'To use reCAPTCHA you must get an API key from <a href="http://recaptcha.net/api/getkey">http://recaptcha.net/api/getkey</a>';
		return FALSE;
	}
	if ($use_ssl) {
		$server = RECAPTCHA_API_SECURE_SERVER;
	} else {
		$server = RECAPTCHA_API_SERVER;
	}
	$errorpart = "";
	if ($error) {
		$errorpart = "&amp;error={$error}";
	}
	return array ($server, $pubkey, $errorpart);
}
/**
 * A ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class ReCaptchaResponse {
	var $is_valid;
	var $error;
}
/**
 * Calls an HTTP POST function to verify if the user's guess was correct
 * @param string $privkey
 * @param string $remoteip
 * @param string $challenge
 * @param string $response
 * @param array $extra_params an array of extra variables to post to the server
 * @return ReCaptchaResponse
 */
function recaptcha_check_answer($privkey, $remoteip, $challenge, $response, $extra_params = array()) {
	if ($privkey == NULL or $privkey == '') {
		echo 'To use reCAPTCHA you must get an API key from <a href="http://recaptcha.net/api/getkey">http://recaptcha.net/api/getkey</a>';
		return FALSE;
	}
	if ($remoteip == NULL or $remoteip == '') {
		echo 'For security reasons, you must pass the remote ip to reCAPTCHA';
		return FALSE;
	}
	//discard spam submissions
	if ($challenge == NULL or strlen($challenge) == 0 or $response == NULL or strlen($response) == 0) {
		$recaptcha_response           = new ReCaptchaResponse();
		$recaptcha_response->is_valid = FALSE;
		$recaptcha_response->error    = 'incorrect-captcha-sol';
		return $recaptcha_response;
	}
	$response           = _recaptcha_http_post(RECAPTCHA_VERIFY_SERVER, '/verify', array(
		'privatekey' => $privkey,
		'remoteip' => $remoteip,
		'challenge' => $challenge,
		'response' => $response
	) + $extra_params);
	$answers            = explode("\n", $response[1]);
	$recaptcha_response = new ReCaptchaResponse();
	if (trim($answers[0]) == 'true') {
		$recaptcha_response->is_valid = TRUE;
	} else {
		$recaptcha_response->is_valid = FALSE;
		$recaptcha_response->error    = $answers[1];
	}
	return $recaptcha_response;
}
/**
 * gets a URL where the user can sign up for reCAPTCHA. If your application
 * has a configuration page where you enter a key, you should provide a link
 * using this function.
 * @param string $domain The domain where the page is hosted
 * @param string $appname The name of your application
 */
function recaptcha_get_signup_url($domain = NULL, $appname = NULL) {
	return 'http://recaptcha.net/api/getkey?' . _recaptcha_qsencode(array(
		'domain' => $domain,
		'app' => $appname
	));
}
function _recaptcha_aes_pad($val) {
	$block_size = 16;
	$numpad     = $block_size - (strlen($val) % $block_size);
	return str_pad($val, strlen($val) + $numpad, chr($numpad));
}
/* Mailhide related code */
function _recaptcha_aes_encrypt($val, $ky) {
	if (!function_exists('mcrypt_encrypt')) {
		echo 'To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.';
		return FALSE;
	}
	$mode = MCRYPT_MODE_CBC;
	$enc  = MCRYPT_RIJNDAEL_128;
	$val  = _recaptcha_aes_pad($val);
	return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
}
function _recaptcha_mailhide_urlbase64($x) {
	return strtr(base64_encode($x), '+/', '-_');
}
/* gets the reCAPTCHA Mailhide url for a given email, public key and private key */
function recaptcha_mailhide_url($pubkey, $privkey, $email) {
	if ($pubkey == '' or $pubkey == NULL or $privkey == "" or $privkey == NULL) {
		echo 'To use reCAPTCHA Mailhide, you have to sign up for a public and private key, you can do so at <a href="http://mailhide.recaptcha.net/apikey">http://mailhide.recaptcha.net/apikey</a>';
		return FALSE;
	}
	$ky        = pack('H*', $privkey);
	$cryptmail = _recaptcha_aes_encrypt($email, $ky);
	return "http://mailhide.recaptcha.net/d?k={$pubkey}&amp;c=" . _recaptcha_mailhide_urlbase64($cryptmail);
}
/**
 * gets the parts of the email to expose to the user.
 * eg, given johndoe@example,com return ["john", "example.com"].
 * the email is then displayed as john...@example.com
 */
function _recaptcha_mailhide_email_parts($email) {
	$arr = preg_split('/@/', $email);
	if (strlen($arr[0]) <= 4) {
		$arr[0] = substr($arr[0], 0, 1);
	} else if (strlen($arr[0]) <= 6) {
		$arr[0] = substr($arr[0], 0, 3);
	} else {
		$arr[0] = substr($arr[0], 0, 4);
	}
	return $arr;
}
/**
 * Gets html to display an email address given a public an private key.
 * to get a key, go to:
 *
 * http://mailhide.recaptcha.net/apikey
 */
function recaptcha_mailhide_html($pubkey, $privkey, $email) {
	$emailparts = _recaptcha_mailhide_email_parts($email);
	$url        = recaptcha_mailhide_url($pubkey, $privkey, $email);
	return htmlentities($emailparts[0]) . "<a href=\"" . htmlentities($url) . "\" onclick=\"window.open('" . htmlentities($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities( $emailparts[1] );
}