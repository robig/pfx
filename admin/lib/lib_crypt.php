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
 * Title: A reversible password encryption routine
 * @copyright 2008-2010 A J Marston
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author A J Marston
 * @author M. Kolar - http://mkolar.org
 * @link http://heydojo.co.cc
 * @link http://www.tonymarston.net
 * @link http://www.tonymarston.co.uk/php-mysql/encryption.html
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 * @info Use mb_substr() if it is available (for multibyte characters)
 *
 */
class encryption_class {
	var $scramble1; // 1st string of ASCII characters
	var $scramble2; // 2nd string of ASCII characters
	var $errors; // array of error messages
	var $adj; // 1st adjustment value (optional)
	var $mod; // 2nd adjustment value (optional)
	// ****************************************************************************
	// class constructor
	// ****************************************************************************
	function encryption_class() {
		$this->errors    = array();
		// Each of these two strings must contain the same characters, but in a different order.
		// Use only printable characters from the ASCII table.
		// Do not use single quote, double quote or backslash as these have special meanings in PHP.
		// Each character can only appear once in each string.
		$this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
		$this->scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';
		if (strlen($this->scramble1) <> strlen($this->scramble2)) {
			trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
		} // if
		$this->adj = 1.75; // this value is added to the rolling fudgefactors
		$this->mod = 3; // if divisible by this the adjustment is made negative
	} // constructor
	// ****************************************************************************
	function decrypt($key, $source)
	// decrypt string into its original form
		{
		$this->errors = array();
		// convert $key into a sequence of numbers
		$fudgefactor  = $this->_convertKey($key);
		if ($this->errors)
			return;
		if (empty($source)) {
			$this->errors[] = 'No value has been supplied for decryption';
			return;
		} // if
		$target  = null;
		$factor2 = 0;
		for ($i = 0; $i < strlen($source); $i++) {
			// extract a (multibyte) character from $source
			if (function_exists('mb_substr')) {
				$char2 = mb_substr($source, $i, 1);
			} else {
				$char2 = substr($source, $i, 1);
			} // if
			// identify its position in $scramble2
			$num2 = strpos($this->scramble2, $char2);
			if ($num2 === false) {
				$this->errors[] = "Source string contains an invalid character ({$char2})";
				return;
			} // if
			// get an adjustment value using $fudgefactor
			$adj     = $this->_applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj; // accumulate in $factor1
			$num1    = $num2 - round($factor1); // generate offset for $scramble1
			$num1    = $this->_checkRange($num1); // check range
			$factor2 = $factor1 + $num2; // accumulate in $factor2
			// extract (multibyte) character from $scramble1
			if (function_exists('mb_substr')) {
				$char1 = mb_substr($this->scramble1, $num1, 1);
			} else {
				$char1 = substr($this->scramble1, $num1, 1);
			} // if
			// append to $target string
			$target .= $char1;
			//echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";
		} // for
		return rtrim($target);
	} // decrypt
	// ****************************************************************************
	function encrypt($key, $source, $sourcelen = 0)
	// encrypt string into a garbled form
		{
		$this->errors = array();
		// convert $key into a sequence of numbers
		$fudgefactor  = $this->_convertKey($key);
		if ($this->errors)
			return;
		if (empty($source)) {
			$this->errors[] = 'No value has been supplied for encryption';
			return;
		} // if
		// pad $source with spaces up to $sourcelen
		$source  = str_pad($source, $sourcelen);
		$target  = null;
		$factor2 = 0;
		for ($i = 0; $i < strlen($source); $i++) {
			// extract a (multibyte) character from $source
			if (function_exists('mb_substr')) {
				$char1 = mb_substr($source, $i, 1);
			} else {
				$char1 = substr($source, $i, 1);
			} // if
			// identify its position in $scramble1
			$num1 = strpos($this->scramble1, $char1);
			if ($num1 === false) {
				$this->errors[] = "Source string contains an invalid character ({$char1})";
				return;
			} // if
			// get an adjustment value using $fudgefactor
			$adj     = $this->_applyFudgeFactor($fudgefactor);
			$factor1 = $factor2 + $adj; // accumulate in $factor1
			$num2    = round($factor1) + $num1; // generate offset for $scramble2
			$num2    = $this->_checkRange($num2); // check range
			$factor2 = $factor1 + $num2; // accumulate in $factor2
			// extract (multibyte) character from $scramble2
			if (function_exists('mb_substr')) {
				$char2 = mb_substr($this->scramble2, $num2, 1);
			} else {
				$char2 = substr($this->scramble2, $num2, 1);
			} // if
			// append to $target string
			$target .= $char2;
			//echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";
		} // for
		return $target;
	} // encrypt
	// ****************************************************************************
	function getAdjustment()
	// return the adjustment value
		{
		return $this->adj;
	} // setAdjustment
	// ****************************************************************************
	function getModulus()
	// return the modulus value
		{
		return $this->mod;
	} // setModulus
	// ****************************************************************************
	function setAdjustment($adj)
	// set the adjustment value
		{
		$this->adj = (float) $adj;
	} // setAdjustment
	// ****************************************************************************
	function setModulus($mod)
	// set the modulus value
		{
		$this->mod = (int) abs($mod); // must be a positive whole number
	} // setModulus
	// ****************************************************************************
	// private methods
	// ****************************************************************************
	function _applyFudgeFactor(&$fudgefactor)
	// return an adjustment value  based on the contents of $fudgefactor
	// NOTE: $fudgefactor is passed by reference so that it can be modified
		{
		$fudge         = array_shift($fudgefactor); // extract 1st number from array
		$fudge         = $fudge + $this->adj; // add in adjustment value
		$fudgefactor[] = $fudge; // put it back at end of array
		if (!empty($this->mod)) { // if modifier has been supplied
			if ($fudge % $this->mod == 0) { // if it is divisible by modifier
				$fudge = $fudge * -1; // make it negative
			} // if
		} // if
		return $fudge;
	} // _applyFudgeFactor
	// ****************************************************************************
	function _checkRange($num)
	// check that $num points to an entry in $this->scramble1
		{
		$num   = round($num); // round up to nearest whole number
		$limit = strlen($this->scramble1);
		while ($num >= $limit) {
			$num = $num - $limit; // value too high, so reduce it
		} // while
		while ($num < 0) {
			$num = $num + $limit; // value too low, so increase it
		} // while
		return $num;
	} // _checkRange
	// ****************************************************************************
	function _convertKey($key)
	// convert $key into an array of numbers
		{
		if (empty($key)) {
			$this->errors[] = 'No value has been supplied for the encryption key';
			return;
		} // if
		$array[] = strlen($key); // first entry in array is length of $key
		$tot     = 0;
		for ($i = 0; $i < strlen($key); $i++) {
			// extract a (multibyte) character from $key
			if (function_exists('mb_substr')) {
				$char = mb_substr($key, $i, 1);
			} else {
				$char = substr($key, $i, 1);
			} // if
			// identify its position in $scramble1
			$num = strpos($this->scramble1, $char);
			if ($num === false) {
				$this->errors[] = "Key contains an invalid character ({$char})";
				return;
			} // if
			$array[] = $num; // store in output array
			$tot     = $tot + $num; // accumulate total for later
		} // for
		$array[] = $tot; // insert total as last entry in array
		return $array;
	} // _convertKey
	// ****************************************************************************
} // end encryption_class
// ****************************************************************************