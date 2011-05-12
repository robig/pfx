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
 * Title: lib_date
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
// ------------------------------------------------------------------
// Converts an Unix-timestamp to a mysql-timestamp
function returnSQLtimestamp($pfx_timestamp) {
	return strftime('%Y%m%d%H%M%S', $pfx_timestamp);
}
// ------------------------------------------------------------------
// Converts an mysql-timestamp to a Unix-timestamp
function returnUnixtimestamp($pfx_timestamp) {
	$pfx_timestamp = str_replace('-', "", $pfx_timestamp);
	$pfx_timestamp = str_replace(" ", "", $pfx_timestamp);
	$pfx_timestamp = str_replace(':', "", $pfx_timestamp);
	$pfx_year      = substr($pfx_timestamp, 0, 4);
	$pfx_month     = substr($pfx_timestamp, 4, 2);
	$pfx_day       = substr($pfx_timestamp, 6, 2);
	$pfx_hour      = substr($pfx_timestamp, 8, 2);
	$pfx_min       = substr($pfx_timestamp, 10, 2);
	$pfx_sec       = substr($pfx_timestamp, 12, 2);
	return mktime($pfx_hour, $pfx_min, $pfx_sec, $pfx_month, $pfx_day, $pfx_year);
}
// ------------------------------------------------------------------
// Converts an mysql-timestamp to a Unix-timestamp
function rUnixtimestamp($pfx_timestamp) {
	$pfx_timestamp = str_replace('-', "", $pfx_timestamp);
	$pfx_timestamp = str_replace(" ", "", $pfx_timestamp);
	$pfx_timestamp = str_replace(':', "", $pfx_timestamp);
	$pfx_year      = substr($pfx_timestamp, 0, 4);
	$pfx_month     = substr($pfx_timestamp, 4, 2);
	$pfx_day       = substr($pfx_timestamp, 6, 2);
	$pfx_hour      = substr($pfx_timestamp, 8, 2);
	$pfx_min       = substr($pfx_timestamp, 10, 2);
	$pfx_sec       = substr($pfx_timestamp, 12, 2);
	return gmmktime($pfx_hour, $pfx_min, $pfx_sec, $pfx_month, $pfx_day, $pfx_year);
}
// -------------------------------------------------------------
// Format a time
function safe_strftime($pfx_lang, $pfx_format = FALSE, $pfx_time = FALSE) {

	if ($pfx_format == 'since') {
		$pfx_str = since($pfx_lang, $pfx_time);
	} else {
		$pfx_str = strftime($pfx_format, $pfx_time);
	}
	return $pfx_str;
}
// -------------------------------------------------------------
function since($pfx_lang, $pfx_stamp) {
	$pfx_diff = (time() - $pfx_stamp);
	if ($pfx_diff <= 3600) {
		$pfx_mins  = round($pfx_diff / 60);
		$pfx_since = ($pfx_mins <= 1) ? ($pfx_mins == 1) ? $pfx_lang['a_minute'] : $pfx_lang['a_few_seconds'] : "$pfx_mins " . $pfx_lang['minutes'];
	} else if (($pfx_diff <= 86400) && ($pfx_diff > 3600)) {
		$pfx_hours = round($pfx_diff / 3600);
		if ($pfx_hours <= 1) {
			$pfx_since = $pfx_lang['a_hour'];
		} else {
			$pfx_since = "{$pfx_hours} {$pfx_lang['hours']}";
		}
	} else if ($pfx_diff >= 86400) {
		$pfx_days = round($pfx_diff / 86400);
		if ($pfx_days <= 1) {
			$pfx_since = $pfx_lang['a_day'];
		} else {
			$pfx_since = "{$pfx_days} {$pfx_lang['days']}";
		}
	}
	return "{$pfx_since} {$pfx_lang['ago']}";
}
// ------------------------------------------------------------------
// creates a drop down selection for date input
function date_dropdown($pfx_date) {
	$pfx_months = array(
		'',
		'Jan',
		'Feb',
		'Mar',
		'Apr',
		'May',
		'Jun',
		'Jul',
		'Aug',
		'Sep',
		'Oct',
		'Nov',
		'Dec'
	);
	/* Could go in language file? - needs language */
	if ( (isset($pfx_date)) && ($pfx_date) ) {
		$pfx_unixtime   = returnUnixtimestamp($pfx_date);
		$pfx_this_day   = date('d', $pfx_unixtime);
		$pfx_this_month = date('n', $pfx_unixtime);
		$pfx_this_year  = date('Y', $pfx_unixtime);
		$pfx_time       = date('H' . ':' . 'i', $pfx_unixtime);
	} else {
		$pfx_this_day   = date('d', time());
		$pfx_this_month = date('n', time());
		$pfx_this_year  = date('Y', time());
		$pfx_time       = date('H' . ':' . 'i', time());
	}
	$pfx_max_day = 31;
	$pfx_min_day = 1;
	echo "\t\t\t\t\t\t\t\t<select class=\"form_select\" id=\"date\" name=\"day\">\n";
	while ($pfx_min_day <= $pfx_max_day) {
		if ($pfx_min_day == $pfx_this_day) {
			echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"{$pfx_min_day}\">{$pfx_min_day}</option>\n";
		} else {
			echo "\t\t\t\t\t\t\t\t\t<option value=\"{$pfx_min_day}\">{$pfx_min_day}</option>\n";
		}
		$pfx_min_day++;
	}
	echo '</select>';
	$pfx_max_month = 12;
	$pfx_min_month = 1;
	echo '<select class="form_select" name="month">';
	while ($pfx_min_month <= $pfx_max_month) {
		if ($pfx_min_month == $pfx_this_month) {
			echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"{$pfx_min_month}\">$pfx_months[$pfx_min_month]</option>\n";
		} else {
			echo "\t\t\t\t\t\t\t\t\t<option value=\"{$pfx_min_month}\">$pfx_months[$pfx_min_month]</option>\n";
		}
		$pfx_min_month++;
	}
	echo '</select>';
	$pfx_max_year = $pfx_this_year + 5;
	$pfx_min_year = $pfx_this_year - 5;
	echo '<select class="form_select" name="year">';
	while ($pfx_min_year <= $pfx_max_year) {
		if ($pfx_min_year == $pfx_this_year) {
			echo "\t\t\t\t\t\t\t\t\t<option selected=\"selected\" value=\"{$pfx_min_year}\">{$pfx_min_year}</option>\n";
		} else {
			echo "\t\t\t\t\t\t\t\t\t<option value=\"{$pfx_min_year}\">{$pfx_min_year}</option>\n";
		}
		$pfx_min_year++;
	}
	echo '</select>';
	echo "\n\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form_text\" name=\"time\" value=\"{$pfx_time}\" size=\"5\" maxlength=\"5\" />";
}