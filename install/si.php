<?php
if (!defined('DIRECT_ACCESS')) { exit( header( 'Location: ../' ) ); }
header('Content-type: text/xml; charset=UTF-8');
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
 * Title: Installer site information form processing
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$_SESSION['langu'] = sterilise_txt($pfx_langu);
$_SESSION['server_timezone'] = str_replace( 'BREAK1', '_', sterilise($pfx_server_timezone) );
$_SESSION['url'] = sterilise_url($pfx_url);
$_SESSION['sitename'] = htmlspecialchars( addslashes( sterilise($pfx_sitename) ), ENT_QUOTES,'UTF-8' );
if ( (isset($pfx_clean_urls_check)) or ($pfx_clean_urls_check) ) {
	$_SESSION['clean_urls_check'] = sterilise_txt($pfx_clean_urls_check);
} else {
	$_SESSION['clean_urls_check'] = 'no';
}
date_default_timezone_set( sterilise_txt($_SESSION['server_timezone']) );
/* Here we just validate that the url is actually an url */
include_once '../admin/lib/lib_validate.php';
$pfx_check = new Validator();
$pfx_check->validateURL($_SESSION['url'], "{$pfx_lang['site_url_error']} ");
if ( $pfx_check->foundErrors() ) {
	$pfx_error .= $pfx_check->listErrors('x');
	$pfx_err   = explode('|', $pfx_error);
	$pfx_error = $pfx_err[0];
	echo "<?xml version=\"1.0\"?><root><message>{$pfx_error}</message></root>";
} else {
	if (@is_writable('../.htaccess')) {
	} else {
		if (@chmod('../.htaccess', 0777)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../.htaccess ';
			} else {
				$pfx_error = '../.htaccess ';
			}
		}
	}
	if (@is_writable('../robots.txt')) {
	} else {
		if (@chmod('../robots.txt', 0777)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../robots.txt ';
			} else {
				$pfx_error = '../robots.txt ';
			}
		}
	}
	if (@is_writable('../admin/config.php')) {
	} else {
		if (@chmod('../admin/config.php', 0777)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../admin/config.php ';
			} else {
				$pfx_error = '../admin/config.php ';
			}
		}
	}
	if (@is_writable('../files/')) {
	} else {
		/* Some badly configured server accounts may need 0777 here but it's a risk */
		if (@chmod('../files/', 0755)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../files/ ';
			} else {
				$pfx_error = '../files/ ';
			}
		}
	}
	if (@is_writable('../files/audio/')) {
	} else {
		if (@chmod('../files/audio/', 0755)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../files/audio/ ';
			} else {
				$pfx_error = '../files/audio/ ';
			}
		}
	}
	if (@is_writable('../files/cache/')) {
	} else {
		if (@chmod('../files/cache/', 0755)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../files/cache/ ';
			} else {
				$pfx_error = '../files/cache/ ';
			}
		}
	}
	if (@is_writable('../files/images/')) {
	} else {
		if (@chmod('../files/images/', 0755)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../files/images/ ';
			} else {
				$pfx_error = '../files/images/ ';
			}
		}
	}
	if (@is_writable('../files/other/')) {
	} else {
		if (@chmod('../files/other/', 0755)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../files/other/ ';
			} else {
				$pfx_error = '../files/other/ ';
			}
		}
	}
	if (@is_writable('../files/sqlbackups/')) {
	} else {
		if (@chmod('../files/sqlbackups/', 0755)) {
		} else {
			if ( ($pfx_error) && (isset($pfx_error)) ) {
				$pfx_error .= '../files/sqlbackups/ ';
			} else {
				$pfx_error = '../files/sqlbackups/ ';
			}
		}
	}
	if ( (isset($pfx_error)) && ($pfx_error) ) {
		echo "<?xml version=\"1.0\"?><root><message>{$pfx_error} must be writeable to continue. Please set write permission on the file or folder to continue</message></root>";
	} else {
		echo '<?xml version="1.0"?><root><message>Step 1</message></root>';
	}
}