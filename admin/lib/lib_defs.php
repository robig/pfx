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
 * Title: lib_defs - Predifines variables
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$pfx_s = FALSE;
$pfx_m = FALSE;
$pfx_x = FALSE;
$pfx_p = FALSE;
$pfx_style = FALSE;
$pfx_page_display_name = FALSE;
$pfx_page_type = FALSE;
$pfx_page_id = FALSE;
$pfx_rel_path = FALSE;
$pfx_page_blocks = FALSE;
$pfx_page_display_name = FALSE;
$pfx_error = FALSE;
if (CONFIG_COMMON == 'index') {
	$pfx_pinfo = FALSE;
	$pfx_comments = FALSE;
	$pfx_staticpage = FALSE;
	$pfx_mtitle = FALSE;
	$pfx_comment_ok = FALSE;
	$pfx_scream = FALSE;
	$pfx_sname = FALSE;
	$pfx_semail = FALSE;
	$pfx_scomment = FALSE;
	$pfx_sweb = FALSE;
}
if (CONFIG_COMMON == 'admin') {
	$pfx_do = FALSE;
	$pfx_ck = FALSE;
	$pfx_CKEditorFuncNum = FALSE;
	$pfx_ckfile = FALSE;
	$pfx_ckimage = FALSE;
	$pfx_scroll = FALSE;
	$pfx_edit = FALSE;
	$pfx_go = FALSE;
	$pfx_tag = FALSE;
	$pfx_search_words = FALSE;
	$pfx_search_submit = FALSE;
	$pfx_page = 1;
	$pfx_message = FALSE;
	$pfx_messageok = FALSE;
	$pfx_staticpage = FALSE;
	$pfx_comments = FALSE;
	$pfx_field = FALSE;
	$pfx_type = FALSE;
	$pfx_submit_new = FALSE;
	$pfx_submit_edit = FALSE;
	$pfx_delete = FALSE;
	$pfx_new = FALSE;
	$pfx_all_tag = FALSE;
}