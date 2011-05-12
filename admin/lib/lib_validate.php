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
 * Title: lib_validate - A class for validating common data from forms
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @link http://heydojo.co.cc
 * @link http://forum.weborum.com/index.php?showtopic=2507
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
// -------------------------------------------------------------
function doPass($str) {
	return hash('sha256', addslashes(strtolower($str)));
}
// -------------------------------------------------------------
class Validator {
	var $pfx_errors; // A variable to store a list of error messages
	// Validate text only
	function validateTextOnly($pfx_theinput, $pfx_description = '') {
		$pfx_result = $pfx_theinput;
		$pfx_result = preg_replace('/[^a-zA-ZÀÁÂÃÄÅĀĄĂÆÇĆČĈĊĎĐÐÈÉÊËĒĘĚĔĖĜĞĠĢĤĦÌÍÎÏĪĨĬĮİĲĴĶŁĽĹĻĿÑŃŇŅŊÒÓÔÕÖØŌŐŎŒŔŘŖŚŠŞŜȘŤŢŦȚÙÚÛÜŪŮŰŬŨŲŴÝŶŸŹŽŻÞÞàáâãäåāąăæçćčĉċďđðèéêëēęěĕėƒĝğġģĥħìíîïīĩĭįıĳĵķĸłľĺļŀñńňņŉŋòóôõöøōőŏœŕřŗšùúûüūůűŭũųŵýÿŷžżźþßſАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыэюя0-9 ]/', "", $pfx_result);
		if ($pfx_result == $pfx_theinput) {
			return TRUE;
		} else {
			$this->pfx_errors[] = $pfx_description;
			return FALSE;
		}
	}
	// Validate text only, no spaces allowed
	function validateTextOnlyNoSpaces($pfx_theinput, $pfx_description = '') {
		$pfx_result = $pfx_theinput;
		$pfx_result = str_replace(' ', '', $pfx_result);
		$pfx_result = preg_replace('/[^a-zA-ZÀÁÂÃÄÅĀĄĂÆÇĆČĈĊĎĐÐÈÉÊËĒĘĚĔĖĜĞĠĢĤĦÌÍÎÏĪĨĬĮİĲĴĶŁĽĹĻĿÑŃŇŅŊÒÓÔÕÖØŌŐŎŒŔŘŖŚŠŞŜȘŤŢŦȚÙÚÛÜŪŮŰŬŨŲŴÝŶŸŹŽŻÞÞàáâãäåāąăæçćčĉċďđðèéêëēęěĕėƒĝğġģĥħìíîïīĩĭįıĳĵķĸłľĺļŀñńňņŉŋòóôõöøōőŏœŕřŗšùúûüūůűŭũųŵýÿŷžżźþßſАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыэюя0-9 ]/', "", $pfx_result);
		if ($pfx_result == $pfx_theinput) {
			return TRUE;
		} else {
			$this->pfx_errors[] = $pfx_description;
			return FALSE;
		}
	}
	// Validate email address
	function validateEmail($pfx_themail, $pfx_description = '') {
		$pfx_pattern = '/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/';
		$pfx_result  = preg_match($pfx_pattern, $pfx_themail);
		if ($pfx_result) {
			return TRUE;
		} else {
			$this->pfx_errors[] = $pfx_description;
			return FALSE;
		}
	}
	// Validate a web address
	function validateURL($pfx_url, $pfx_description = '') {
		if ( (!preg_match('/localhost/', $pfx_url)) && (!preg_match('/127.0.0./', $pfx_url)) ) {
		    $pfx_pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
		    $pfx_result  = preg_match($pfx_pattern, $pfx_url);
		    if ($pfx_result) {
			    return TRUE;
		    } else {
			    $this->pfx_errors[] = $pfx_description;
			    return FALSE;
		    }
		} else {
		return TRUE;
		}
	}
	// Validate numbers only
	function validateNumber($pfx_theinput, $pfx_description = '') {
		if (is_numeric($pfx_theinput)) {
			return TRUE; // The value is numeric, return TRUE
		} else {
			$this->pfx_errors[] = $pfx_description; // Value not numeric! Add error description to list of errors
			return FALSE; // Return FALSE
		}
	}
	// Check whether any errors have been found (i.e. validation has returned FALSE)
	// since the object was created
	function foundErrors() {
		if (count($this->pfx_errors) > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	// Return a string containing a list of errors found,
	// Seperated by a given deliminator
	function listErrors($pfx_delim = ' ') {
		return implode($pfx_delim, $this->pfx_errors);
	}
	// Manually add something to the list of errors
	function addError($pfx_description) {
		$this->pfx_errors[] = $pfx_description;
	}
}