<?php
if (!defined('DIRECT_ACCESS')) {
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
 * Title: Comments Plugin
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
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
switch ($pfx_do) {
	/* General information */
	case 'info' :
		$pfx_m_name        = ucfirst($pfx_m_n);
		$pfx_m_description = 'This plugin will allow visitors to leave a comment on any post in a dynamic page.';
		$pfx_m_author      = 'Scott Evans';
		$pfx_m_url         = 'http://www.toggle.uk.com';
		$pfx_m_version     = 1.2;
		$pfx_m_type        = 'plugin';
		$pfx_m_publish     = 'yes';
		$pfx_m_in_navigation     = 'no';
		break;
	/* Install */
	/* Pre (To be run before page load) */
	/* Admin of module */
	case 'admin' :
		$pfx_module_name  = $pfx_m_n;
		$pfx_table_name   = "pfx_module_{$pfx_m_n}";
		$pfx_order_by     = 'posted';
		$pfx_asc_desc     = 'desc';
		$pfx_view_exclude = array(
			"{$pfx_m_n}_id",
			'post_id',
			'page_id',
			'url',
			'admin_user'
		);
		$pfx_edit_exclude = array(
			"{$pfx_m_n}_id",
			'post_id'
		);
		$pfx_items_per_page  = 20;
		$pfx_tags         = 'no';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/* Show module */
	default :
		/* I am not here to show anything, I am used as part of the dynamic pages page. */
		break;
}