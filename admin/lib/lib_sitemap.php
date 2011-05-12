<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../../') );
}
/**
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
 * Title: lib_sitemap - A class for generating simple google sitemaps
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author Svetoslav Marinov (svetoslav.marinov@gmail.com)
 * @link http://heydojo.co.cc
 * @link http://devquickref.com
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
class google_sitemap {
	var $pfx_header = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n\t<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n\thttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";
	var $pfx_footer = "\t</urlset>\n";
	var $pfx_items = array();
	/** Adds a new item to the channel contents.
	 *@param google_sitemap item $pfx_new_item
	 *@access public
	 */
	function add_item($pfx_new_item) {
		//Make sure $pfx_new_item is an 'google_sitemap item' object
		if ($pfx_new_item instanceof google_sitemap_item) {
		} else {
			//Stop execution with an error message
			trigger_error('Can\'t add a non-google_sitemap_item object to the sitemap items array');
		}
		$this->items[] = $pfx_new_item;
	}
	/** Generates the sitemap XML data based on object properties.
	 *@param string $pfx_file_name ( optional ) if file name is supplied the XML data is saved in it otherwise returned as a string.
	 *@access public
	 *@return [void|string]
	 */
	function build($pfx_file_name = NULL) {
		$pfx_map = $this->pfx_header . "\n";
		foreach ($this->items as $pfx_item) {
			$pfx_item->loc = htmlentities($pfx_item->loc, ENT_QUOTES);
			$pfx_map .= "\t\t<url>\n\t\t\t<loc>$pfx_item->loc</loc>\n";
			// lastmod
			if (!empty($pfx_item->lastmod))
				$pfx_map .= "\t\t\t<lastmod>$pfx_item->lastmod</lastmod>\n";
			// changefreq
			if (!empty($pfx_item->changefreq))
				$pfx_map .= "\t\t\t<changefreq>$pfx_item->changefreq</changefreq>\n";
			// priority
			if (!empty($pfx_item->priority))
				$pfx_map .= "\t\t\t<priority>$pfx_item->priority</priority>\n";
			$pfx_map .= "\t\t</url>\n\n";
		}
		$pfx_map .= $this->pfx_footer . "\n";
		if (!is_null($pfx_file_name)) {
			$pfx_fh = fopen($pfx_file_name, 'w');
			fwrite($pfx_fh, $pfx_map);
			fclose($pfx_fh);
		} else {
			return $pfx_map;
		}
	}
}
/** A class for storing google_sitemap items and will be added to google_sitemap objects.
 *@author Svetoslav Marinov <svetoslav.marinov@gmail.com>
 *@copyright 2005
 *@access public
 *@package google_sitemap_item
 *@link http://devquickref.com
 *@version 0.1
 */
class google_sitemap_item {
	/** Assigns constructor parameters to their corresponding object properties.
	 *@access public
	 *@param string $pfx_loc location
	 *@param string $pfx_lastmod date (optional) format in YYYY-MM-DD or in "ISO 8601" format
	 *@param string $pfx_changefreq (optional)( always,hourly,daily,weekly,monthly,yearly,never )
	 *@param string $pfx_priority (optional) current link's priority ( 0.0-1.0 )
	 */
	function google_sitemap_item($pfx_loc, $pfx_lastmod = '', $pfx_changefreq = '', $pfx_priority = '') {
		$this->loc        = $pfx_loc;
		$this->lastmod    = $pfx_lastmod;
		$this->changefreq = $pfx_changefreq;
		$this->priority   = $pfx_priority;
	}
}