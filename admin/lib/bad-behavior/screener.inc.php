<?php if (!defined('BB2_CWD')) die("I said no cheating!");

// Bad Behavior browser screener

function bb2_screener_cookie($settings, $package, $cookie_name, $cookie_value)
{
	// FIXME: Set the real cookie
	setcookie($cookie_name, $cookie_value, 0, bb2_relative_path());
}

function bb2_screener_javascript($settings, $package, $cookie_name, $cookie_value)
{
	define( 'BB2_CN', $cookie_name);
	define( 'BB2_CV', $cookie_value);

}

function bb2_screener($settings, $package)
{
	$cookie_name = BB2_COOKIE;

	// Set up a simple cookie
	$screener = array(time(), $package['ip']);
	if (isset($package['headers_mixed']['X-Forwarded-For'])) {
		array_push($screener, $package['headers_mixed']['X-Forwarded-For']);
	}
	if (isset($package['headers_mixed']['Client-Ip'])) {
		array_push($screener, $package['headers_mixed']['Client-Ip']);
	}

	$cookie_value = implode(" ", $screener);

	bb2_screener_cookie($settings, $package, BB2_COOKIE, $cookie_value);
	bb2_screener_javascript($settings, $package, BB2_COOKIE, $cookie_value);
}