<?php
if ( !defined('DIRECT_ACCESS' )) {
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
 * Title: lib_tz - Timezone information into an array
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
/* A list of timezones to use to set pfx's timezone with */
		$pfx_zonelist = array(
			'Pacific/Midway',
			'Pacific/Samoa',
			'Pacific/Honolulu',
			'America/Anchorage',
			'America/Los_Angeles',
			'America/Tijuana',
			'America/Denver',
			'America/Chihuahua',
			'America/Mazatlan',
			'America/Phoenix',
			'America/Regina',
			'America/Tegucigalpa',
			'America/Chicago',
			'America/Mexico_City',
			'America/Monterrey',
			'America/New_York',
			'America/Bogota',
			'America/Lima',
			'America/Rio_Branco',
			'America/Indiana/Indianapolis',
			'America/Caracas',
			'America/Halifax',
			'America/Manaus',
			'America/Santiago',
			'America/La_Paz',
			'America/St_Johns',
			'America/Argentina/Buenos_Aires',
			'America/Sao_Paulo',
			'America/Godthab',
			'America/Montevideo',
			'Atlantic/South_Georgia',
			'Atlantic/Azores',
			'Atlantic/Cape_Verde',
			'Europe/Dublin',
			'Europe/Lisbon',
			'Europe/London',
			'Africa/Monrovia',
			'Atlantic/Reykjavik',
			'Africa/Casablanca',
			'Europe/Belgrade',
			'Europe/Bratislava',
			'Europe/Budapest',
			'Europe/Ljubljana',
			'Europe/Prague',
			'Europe/Sarajevo',
			'Europe/Skopje',
			'Europe/Warsaw',
			'Europe/Zagreb',
			'Europe/Brussels',
			'Europe/Copenhagen',
			'Europe/Madrid',
			'Europe/Paris',
			'Africa/Algiers',
			'Europe/Amsterdam',
			'Europe/Berlin',
			'Europe/Rome',
			'Europe/Stockholm',
			'Europe/Vienna',
			'Europe/Minsk',
			'Africa/Cairo',
			'Europe/Helsinki',
			'Europe/Riga',
			'Europe/Sofia',
			'Europe/Tallinn',
			'Europe/Vilnius',
			'Europe/Athens',
			'Europe/Bucharest',
			'Europe/Istanbul',
			'Asia/Jerusalem',
			'Asia/Amman',
			'Asia/Beirut',
			'Africa/Windhoek',
			'Africa/Harare',
			'Asia/Kuwait',
			'Asia/Riyadh',
			'Asia/Baghdad',
			'Africa/Nairobi',
			'Asia/Tbilisi',
			'Europe/Moscow',
			'Europe/Volgograd',
			'Asia/Tehran',
			'Asia/Muscat',
			'Asia/Baku',
			'Asia/Yerevan',
			'Asia/Yekaterinburg',
			'Asia/Karachi',
			'Asia/Tashkent',
			'Asia/Kolkata',
			'Asia/Colombo',
			'Asia/Katmandu',
			'Asia/Dhaka',
			'Asia/Almaty',
			'Asia/Novosibirsk',
			'Asia/Rangoon',
			'Asia/Krasnoyarsk',
			'Asia/Bangkok',
			'Asia/Jakarta',
			'Asia/Brunei',
			'Asia/Chongqing',
			'Asia/Hong_Kong',
			'Asia/Urumqi',
			'Asia/Irkutsk',
			'Asia/Ulaanbaatar',
			'Asia/Kuala_Lumpur',
			'Asia/Singapore',
			'Asia/Taipei',
			'Australia/Perth',
			'Asia/Seoul',
			'Asia/Tokyo',
			'Asia/Yakutsk',
			'Australia/Darwin',
			'Australia/Adelaide',
			'Australia/Canberra',
			'Australia/Melbourne',
			'Australia/Sydney',
			'Australia/Brisbane',
			'Australia/Hobart',
			'Asia/Vladivostok',
			'Pacific/Guam',
			'Pacific/Port_Moresby',
			'Asia/Magadan',
			'Pacific/Fiji',
			'Asia/Kamchatka',
			'Pacific/Auckland',
			'Pacific/Tongatapu'
		);
		/* Add more here if you want to... */
		sort($pfx_zonelist);
		/* Sort by area/city name. */