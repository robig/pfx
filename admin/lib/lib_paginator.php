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
 * Title: lib_paginator - Class to help make pagination easyier
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author Ted Kappes (pesoto74@soltec.net)
 * @link http://heydojo.co.cc
 * @link http://www.phpclasses.org/browse/package/1239.html
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
class Paginator {
	/*	All variables are made private.	*/
	var $pfx_previous;
	var $pfx_current;
	var $pfx_next;
	var $pfx_page;
	var $pfx_total_pages;
	var $pfx_link_arr;
	var $pfx_range1;
	var $pfx_range2;
	var $pfx_num_rows;
	var $pfx_first;
	var $pfx_last;
	var $pfx_first_of;
	var $pfx_second_of;
	var $pfx_limit;
	var $pfx_prev_next;
	var $pfx_base_page_num;
	var $pfx_extra_page_num;
	var $pfx_total_items;
	var $pfx_pagename;
	/*	The constructor for the paginator.  Takes the current page and the number of items
		as the source data and sets the current page ($this->pfx_page) and the total
		items in the source ($this->pfx_total_items).	*/
	function Paginator($pfx_page, $pfx_num_rows) {
		if (!$pfx_page) {
			$this->pfx_page = 1;
		} else {
			$this->pfx_page = $pfx_page;
		}
		$this->pfx_num_rows    = $pfx_num_rows;
		$this->pfx_total_items = $this->pfx_num_rows;
	}
	/*	Takes $pfx_limit and sets $this->limit.
		Calls private mehods setBasePage() and setExtraPage() which use $this->limit.	*/
	function set_Limit($pfx_limit = 5) {
		$this->pfx_limit = $pfx_limit;
		$this->setBasePage();
		$this->setExtraPage();
	}
	/*	This method creates a number that setExtraPage() uses to if there are
		and extra pages after limit has divided the total number of pages.	*/
	function setBasePage() {
		$pfx_div                 = $this->pfx_num_rows / $this->pfx_limit;
		$this->pfx_base_page_num = floor($pfx_div);
	}
	function setExtraPage() {
		$this->pfx_extra_page_num = $this->pfx_num_rows - ($this->pfx_base_page_num * $this->pfx_limit);
	}
	/*	Used to make numbered links.  Sets the number of links infront of and 
		behind the current page.  For example if there is a possiblity of
		20 numbered links, this was set to 5 and the current link was 10
		the result would equal : 5 6 7 8 9 10 11 12 13 14 15.	*/
	function set_Links($pfx_prev_next = 5) {
		$this->pfx_prev_next = $pfx_prev_next;
	}
	/*	A method to get the total items.	*/
	function getTotalItems() {
		$this->pfx_total_items = $this->pfx_num_rows;
		return $this->pfx_total_items;
	}
	/*	A method to get the base number to use in queries and such.	*/
	function getRange1() {
		$this->pfx_range1 = ($this->pfx_limit * $this->pfx_page) - $this->pfx_limit;
		return $this->pfx_range1;
	}
	/*	A method to get the offset.	*/
	function getRange2() {
		if ($this->pfx_page == $this->pfx_base_page_num + 1) {
			$this->pfx_range2 = $this->pfx_extra_page_num;
		} else {
			$this->pfx_range2 = $this->pfx_limit;
		}
		return $this->pfx_range2;
	}
	/*	A method to get the first of number as in 5 of x.	*/
	function getFirstOf() {
		$this->pfx_first_of = $this->pfx_range1 + 1;
		return $this->pfx_first_of;
	}
	/*	A method to get the second number in a series as in 5 of 8.	*/
	function getSecondOf() {
		if ($this->pfx_page == $this->pfx_base_page_num + 1) {
			$this->pfx_second_of = $this->pfx_range1 + $this->pfx_extra_page_num;
		} else {
			$this->pfx_second_of = $this->pfx_range1 + $this->pfx_limit;
		}
		return $this->pfx_second_of;
	}
	/*	A method to get the total number of pages.	*/
	function getTotalPages() {
		if ($this->pfx_extra_page_num) {
			$this->pfx_total_pages = $this->pfx_base_page_num + 1;
		} else {
			$this->pfx_total_pages = $this->pfx_base_page_num;
		}
		return $this->pfx_total_pages;
	}
	/*	A method to get the first link number.	*/
	function getFirst() {
		$this->pfx_first = 1;
		return $this->pfx_first;
	}
	/*	A method to get the last link number.	*/
	function getLast() {
		if ($this->pfx_page == $this->pfx_total_pages) {
			$this->pfx_last = 0;
		} else {
			$this->pfx_last = $this->pfx_total_pages;
		}
		return $this->pfx_last;
	}
	function getPrevious() {
		if ($this->pfx_page > 1) {
			$this->pfx_previous = $this->pfx_page - 1;
		}
		return $this->pfx_previous;
	}
	/*	A method to get the number of the link before to current link.	*/
	function getCurrent() {
		$this->current = $this->pfx_page;
		return $this->current;
	}
	/*	A method to get the current page name. Mostly used in links to the next page.	*/
	function getPageName() {
		$this->pfx_pagename = $_SERVER['PHP_SELF'];
		return $this->pfx_pagename;
	}
	/*	A method to get the number of the link after the current link.	*/
	function getNext() {
		$this->getTotalPages();
		if ($this->pfx_total_pages != $this->pfx_page) {
			$this->pfx_next = $this->pfx_page + 1;
		}
		return $this->pfx_next;
	}
	/*	A method that returns an array of the numbered links which will be displayed.	*/
	function getLinkArr() {
		/*	Get the top range	*/
		$pfx_top = $this->getTotalPages() - $this->getCurrent();
		if ($pfx_top <= $this->pfx_prev_next) {
			$pfx_top       = $pfx_top;
			$pfx_top_range = $this->getCurrent() + $pfx_top;
		} else {
			$pfx_top       = $this->pfx_prev_next;
			$pfx_top_range = $this->getCurrent() + $pfx_top;
		}
		/*	Get the bottom range	*/
		$pfx_bottom = $this->getCurrent() - 1;
		if ($pfx_bottom <= $this->pfx_prev_next) {
			$pfx_bottom       = $pfx_bottom;
			$pfx_bottom_range = $this->getCurrent() - $pfx_bottom;
		} else {
			$pfx_bottom       = $this->pfx_prev_next;
			$pfx_bottom_range = $this->getCurrent() - $pfx_bottom;
		}
		$pfx_j = 0;
		foreach (range($pfx_bottom_range, $pfx_top_range) as $pfx_i) {
			$this->pfx_link_arr[$pfx_j] = $pfx_i;
			$pfx_j++;
		}
		return $this->pfx_link_arr;
	}
} /*	End Paginator class	*/
class Paginator_html extends Paginator {
	var $pfx_whereami;
	/*	Outputs a link set like : Previous 1 2 3 4 5 6 Next	*/
	function previousNext($pfx_whereami) {
		$this->pfx_whereami = $pfx_whereami;
		if ($this->getPrevious()) {
			echo "<a class=\"previo no-thickbox\" href=\"" . $this->getPageName() . $this->pfx_whereami . '&amp;page=' . $this->getPrevious() . "\" title=\"Previous\">Previous</a>";
		}
		$pfx_links = $this->getLinkArr();
		foreach ($pfx_links as $pfx_link) {
			if ($pfx_link == $this->getCurrent()) {
				echo "<a class=\"number no-thickbox\" href=\"" . $this->getPageName() . $this->pfx_whereami . "&amp;page={$pfx_link}\" title=\"Page: {$pfx_link}\" id=\"admin_page_current\">{$pfx_link}</a>";
			} else {
				echo "<a class=\"number no-thickbox\" href=\"" . $this->getPageName() . $this->pfx_whereami . "&amp;page={$pfx_link}\" title=\"Page: {$pfx_link}\">{$pfx_link}</a>";
			}
		}
		if ($this->getNext()) {
			echo "<a class=\"nextio no-thickbox\" href=\"" . $this->getPageName() . $this->pfx_whereami . '&amp;page=' . $this->getNext() . "\" title=\"Next\">Next</a>";
		}
	}
} /*	End class	*/