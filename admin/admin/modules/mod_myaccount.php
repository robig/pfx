<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../../../') );
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
 * Title: My Account
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
if (isset($GLOBALS['pfx_user'])) {
	$pfx_uname = $GLOBALS['pfx_user'];
	if ( $pfx_rs = safe_row('*', 'pfx_users', "user_name = '{$pfx_uname}' limit 0,1") ) {
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = TRUE;
	}
	if ($pfx_m) {
		$pfx_m = sterilise_url($pfx_m);
		include("../admin/modules/{$pfx_m}.php");
	} else if ($pfx_x) {
		$pfx_x = sterilise_url($pfx_x);
		include("mod_{$pfx_x}.php");
	} else {
	/* Clear logs past $pfx_log_expire days */
	safe_delete('pfx_log', "`log_time` < date_sub(utc_timestamp(),interval " . PREFS_LOGS_EXPIRE . " day)");
	safe_delete('pfx_bad_behavior', "`date` < date_sub(utc_timestamp(),interval " . PREFS_LOGS_EXPIRE . " day)");
	safe_optimize('pfx_log');
	safe_repair('pfx_log');
	safe_optimize('pfx_bad_behavior');
	safe_repair('pfx_bad_behavior');
	/* Users online */
	$pfx_user_count = mysql_num_rows(safe_query('select * from ' . CONFIG_TABLE_PREFIX . 'pfx_log_users_online'));
	if ($pfx_user_count > 0) {
		$pfx_user_count = $pfx_user_count - 1;
	}
	/* Number of visitors */
	$pfx_visitors  = count(safe_rows('distinct user_ip', 'pfx_log', "user_id = 'Visitor' and log_type = 'referral'"));
	/* Count page_views */
	$pfx_pageviews = mysql_num_rows(safe_query('select * from ' . CONFIG_TABLE_PREFIX . "pfx_log where user_id = 'Visitor' and log_type = 'referral'"));
	/* Last login time */
	$pfx_this_login_time = safe_field('last_access', 'pfx_users', "user_name = '{$GLOBALS['pfx_user']}' limit 0,1");
	if ($pfx_logintime = safe_field('log_time', 'pfx_log', "user_id = '{$GLOBALS['pfx_user']}' AND log_time < '{$pfx_this_login_time}' AND log_message = '{$pfx_lang['user']} {$GLOBALS['pfx_user']} {$pfx_lang['ok_login']}' ORDER BY log_id DESC limit 0,1") ) {
	} else {
		$pfx_logintime = $pfx_this_login_time;
	}
	$pfx_logunix   = rUnixtimestamp($pfx_logintime);
	$pfx_logindate = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
	if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 2) {
		if ( (isset($pfx_do)) && ($pfx_do == 'clear-syslog') ) {
			safe_delete('pfx_log', "log_type='system'");
			safe_optimize('pfx_log');
			safe_repair('pfx_log');
		}
		if ( (isset($pfx_do)) && ($pfx_do == 'clear-reflog') ) {
			safe_delete('pfx_log', "log_type='referral'");
			safe_optimize('pfx_log');
			safe_repair('pfx_log');
		}
	}
	if ( (isset($pfx_do)) && ($pfx_do == 'referral') ) {
		echo "<div id=\"pfx_content\">
				<h2>{$pfx_lang['latest']} {$pfx_lang['latest_referrals']}</h2>
				<ul id=\"log_tools\">";
					if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 2) {
						echo "<li id=\"ref_log_clear\"><a href=\"?s=myaccount&amp;do=clear-reflog\" title=\"Clear this log\" class=\"confirm-del\">Clear this log</a></li>";
					}
				echo "<li id=\"log_switch_latest\"><a href=\"?s=myaccount\" title=\"{$pfx_lang['switch_to']} {$pfx_lang['latest_activity']}\">{$pfx_lang['latest_activity']}</a></li>
				</ul><div id=\"logs_table\">";
				/* Paginator code start */
				$pfx_view_number = 30; /* Total records to show per page */
				if ( (isset($pfx_page)) && ($pfx_page != 1) ) {
					$pfx_lo = ($pfx_page * $pfx_view_number - $pfx_view_number);
				} else {
					$pfx_page = 1;
					$pfx_lo = 0;
				}
				$pfx_type = '';
				$pfx_table_name = 'pfx_log';
				if (PREFS_LOG_BOTS == 'yes') {
					$pfx_condition = "WHERE log_type = 'referral'";
				} else {
					$pfx_condition = "WHERE log_type = 'referral' NOT LIKE 'Web Bot'";
				}
				$pfx_order_by = 'log_time';
				$pfx_asc_desc = 'DESC';
				$pfx_no_edit = 'yes';
				$pfx_no_delete = 'yes';
				$pfx_last_mod = 'no';
				$pfx_exclude = array(
							'log_id',
							'log_type',
							'user_id',
							'log_important'
						);
				$pfx_r1 = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc}");
				if ($pfx_r1) {
					$pfx_total = mysql_num_rows($pfx_r1);
					if ($pfx_total > 0) {
						if (isset($pfx_table_name)) {
							$pfx_r = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc} limit {$pfx_lo},{$pfx_view_number}");
						}
						if ($pfx_r) {
							$pfx_rows = mysql_num_rows($pfx_r);
							$pfx_hi   = $pfx_lo + $pfx_view_number;
							if ($pfx_hi > $pfx_total) {
								$pfx_finalmax = $pfx_total - $pfx_lo;
								$pfx_hi       = $pfx_total;
							}
							$pfx_pages = ceil($pfx_total / $pfx_view_number);
						}
						$pfx_a = new Paginator_html($pfx_page, $pfx_total);
						$pfx_a->set_Limit($pfx_view_number);
						$pfx_a->set_Links(4);
						echo '<div class="admin_table_holder pcontent">';
						$pfx_wheream = "?s={$pfx_s}&amp;do={$pfx_do}";
						if (isset($pfx_finalmax) && ($pfx_finalmax)) {
						} else {
							$pfx_finalmax = NULL;
						}
						$pfx_Table = new SuperTable($pfx_r, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_wheream, $pfx_type, $pfx_s);
						$pfx_Table->SuperBody($pfx_lang, $pfx_page_display_name, $pfx_no_edit, $pfx_no_delete, $pfx_last_mod, $pfx_ck, $pfx_CKEditorFuncNum, $pfx_ckfile, $pfx_ckimage);
						$pfx_loprint = $pfx_lo + 1;
						echo "\n\t\t\t\t\t\t<div id=\"admin_table_overview\">\n\t\t\t\t\t\t\t<p>{$pfx_lang['total_records']}: {$pfx_total} ({$pfx_lang['showing_from_record']} {$pfx_loprint} {$pfx_lang['to']} {$pfx_hi}) {$pfx_pages} {$pfx_lang['page(s)']}.</p>\n\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t<div id=\"admin_table_pages\"><p>";
						$pfx_a->previousNext($pfx_wheream);
						echo '</p></div></div>';
					} else {
						echo "<div class=\"admin_table_holder pcontent\"><h3>{$pfx_lang['log_empty']}<h3></div>";
					}
				}
				/* Paginator code end */
	} else {
		echo "<div id=\"pfx_content\">";
		echo "<h2>{$pfx_lang['nav1_home']}</h2><ul id=\"log_tools\">";
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 2) {
			echo "<li id=\"sys_log_clear\"><a href=\"?s=myaccount&amp;do=clear-syslog\" title=\"Clear this log\" class=\"confirm-del\">Clear this log</a></li>";
		}
		echo "<li id=\"log_rss\"><a href=\"?s=myaccount&amp;do=rss&amp;nonce={$GLOBALS['pfx_nonce']}\" title=\"{$pfx_lang['feed_subscribe']}\">{$pfx_lang['feed_subscribe']}</a></li>";
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 1) {
			echo "<li id=\"log_switch_referral\"><a href=\"?s=myaccount&amp;do=referral\" title=\"{$pfx_lang['switch_to']} {$pfx_lang['latest_referrals']}\">{$pfx_lang['latest_referrals']}</a></li>";
		}
		echo '</ul><div id="logs_table">';
		$pfx_view_number = 30; /* Total records to show per page */
		if ( (isset($pfx_page)) && ($pfx_page != 1) ) {
			$pfx_lo = ($pfx_page * $pfx_view_number - $pfx_view_number);
		} else {
			$pfx_page = 1;
			$pfx_lo = 0;
		}
		$pfx_type = '';
		$pfx_table_name = 'pfx_log';
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] < 1) {
			$pfx_condition = "WHERE log_type = 'system' AND user_id = '{$GLOBALS['pfx_user']}' OR log_type = 'system' AND user_id = 'Visitor'";
		} else {
			$pfx_condition = "WHERE log_type = 'system'";
		}
		$pfx_order_by = 'log_time';
		$pfx_asc_desc = 'DESC';
		$pfx_no_edit = 'yes';
		$pfx_no_delete = 'yes';
		$pfx_last_mod = 'no';
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] < 1) {
		$pfx_exclude = array(
					'log_id',
					'user_ip',
					'user_id',
					'log_type',
					'log_important'
				);
		} else {
		$pfx_exclude = array(
					'log_id',
					'user_ip',
					'log_type',
					'log_important'
				);
		}
		$pfx_r1 = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc}");
		if ($pfx_r1) {
			$pfx_total = mysql_num_rows($pfx_r1);
			if ($pfx_total > 0) {
				if ((isset($pfx_table_name))) {
					$pfx_r = safe_query("select * from {$pfx_table_name} {$pfx_condition} order by {$pfx_order_by} {$pfx_asc_desc} limit {$pfx_lo},{$pfx_view_number}");
				}
				if ($pfx_r) {
					$pfx_rows = mysql_num_rows($pfx_r);
					$pfx_hi   = $pfx_lo + $pfx_view_number;
					if ($pfx_hi > $pfx_total) {
						$pfx_finalmax = $pfx_total - $pfx_lo;
						$pfx_hi       = $pfx_total;
					}
					$pfx_pages = ceil($pfx_total / $pfx_view_number);
				}
				$pfx_a = new Paginator_html($pfx_page, $pfx_total);
				$pfx_a->set_Limit($pfx_view_number);
				$pfx_a->set_Links(4);
				echo '<div class="admin_table_holder pcontent">';
				$pfx_wheream = "?s={$pfx_s}";
				if (isset($pfx_finalmax) && ($pfx_finalmax)) {
				} else {
					$pfx_finalmax = NULL;
				}
				$pfx_Table = new SuperTable($pfx_r, $pfx_exclude, $pfx_table_name, $pfx_view_number, $pfx_lo, $pfx_finalmax, $pfx_wheream, $pfx_type, $pfx_s);
				$pfx_Table->SuperBody($pfx_lang, $pfx_page_display_name, $pfx_no_edit, $pfx_no_delete, $pfx_last_mod, $pfx_ck, $pfx_CKEditorFuncNum, $pfx_ckfile, $pfx_ckimage);
				$pfx_loprint = $pfx_lo + 1;
				echo "\n\t\t\t\t\t\t<div id=\"admin_table_overview\">\n\t\t\t\t\t\t\t<p>{$pfx_lang['total_records']}: {$pfx_total} ({$pfx_lang['showing_from_record']} {$pfx_loprint} {$pfx_lang['to']} {$pfx_hi}) {$pfx_pages} {$pfx_lang['page(s)']}.</p>\n\t\t\t\t\t\t</div>\n\n\t\t\t\t\t\t<div id=\"admin_table_pages\"><p>";
				$pfx_a->previousNext($pfx_wheream);
				echo '</p></div></div>';
			} else {
				echo "<div class=\"admin_table_holder pcontent\"><h3>{$pfx_lang['log_empty']}<h3></div>";
			}
		}
	}
	if ( (isset($pfx_s)) && ($pfx_s == 'myaccount') && ($pfx_x != 'myprofile') ) {
		if ($pfx_rs) {
			echo "<div id=\"myaccount\"><div id=\"blocks\"><div class=\"admin_block\" id=\"admin_block_stats\">
				<h3>{$pfx_lang['statistics']}</h3>
				<span><b>" . safe_strftime( $pfx_lang, PREFS_DATE_FORMAT, time() ) . "</b></span>
				<p class=\"plogin\">{$pfx_lang['last_login_on']}<br/>{$pfx_logindate}</p>
				<p>" . PREFS_SITE_NAME . " {$pfx_lang['your_site_has']} <b>$pfx_user_count</b> {$pfx_lang['visitors_online']}</p>
				<p class=\"pstats\">{$pfx_lang['in_the_last']} " . PREFS_LOGS_EXPIRE . " {$pfx_lang['days']} {$pfx_lang['been']} :</p>
				<ul>
					<li><b>{$pfx_pageviews}</b> {$pfx_lang['page_views']}</li>
					<li><b>{$pfx_visitors}</b> {$pfx_lang['site_visitors']}</li>
				</ul>
				</div>\n";
			echo "\t\t\t\t\t<div class=\"admin_block\" id=\"admin_block_links\">\t\t\t\t\t\n\t\t\t\t\t\t<h3 class=\"qlinks\">{$pfx_lang['quick']} {$pfx_lang['links']}</h3>\n\t\t\t\t\t\t<ul>\n";
			if ( (isset($GLOBALS['pfx_user'])) && ($GLOBALS['pfx_user_privs'] >= 1) ) {
				$pfx_core_type_dynamic = safe_row('*', 'pfx_core', "page_type = 'dynamic' order by page_views desc limit 0,1");
				extract($pfx_core_type_dynamic, EXTR_PREFIX_ALL, 'pfx');
				$pfx_core_type_dynamic = NULL;
				if ($pfx_page_type == 'dynamic') {
					echo "\t\t\t\t\t\t\t<li><a href=\"" . PREFS_SITE_URL . "admin/?s=publish&amp;m=dynamic&amp;x={$pfx_page_name}&amp;go=new\" title=\"{$pfx_lang['new_entry']}{$pfx_page_display_name} {$pfx_lang['entry']}\" >{$pfx_lang['new_entry']}{$pfx_page_display_name} {$pfx_lang['entry']}</a></li>\n";
				}
				if ( (isset($GLOBALS['pfx_user'])) && ($GLOBALS['pfx_user_privs'] >= 1) ) {
					echo "\t\t\t\t\t\t\t<li><a href=\"" . PREFS_SITE_URL . "admin/index.php?s=publish&m=module&x=comments\" title=\"{$pfx_lang['comments_manage']}\" >{$pfx_lang['comments_manage']}</a></li>\n";
					echo "\t\t\t\t\t\t\t<li><a href=\"" . PREFS_SITE_URL . "admin/index.php?s=publish&x=filemanager\" title=\"{$pfx_lang['nav2_files']}\" >{$pfx_lang['nav2_files']}</a></li>\n";
					$pfx_core_type_static = safe_row('*', 'pfx_core', "page_type = 'static' order by page_views desc limit 0,1");
					extract($pfx_core_type_static, EXTR_PREFIX_ALL, 'pfx');
					$pfx_core_type_static = NULL;
					if ( ($pfx_page_type == 'static') && ($GLOBALS['pfx_user_privs'] >= 2) ) {
						echo "\t\t\t\t\t\t\t<li><a href=\"" . PREFS_SITE_URL . "admin/?s=publish&amp;m=static&amp;x={$pfx_page_name}&amp;edit={$pfx_page_id}\" title=\"{$pfx_lang['edit']}{$pfx_page_display_name} {$pfx_lang['page']}\" >{$pfx_lang['edit']}{$pfx_page_display_name} {$pfx_lang['page']}</a></li>\n";
					}
					$pfx_core_type_module = safe_row('*', 'pfx_core', "page_type = 'module' AND publish = 'yes' order by page_id desc limit 0,1");
					extract($pfx_core_type_module, EXTR_PREFIX_ALL, 'pfx');
					$pfx_core_type_module = NULL;
					if ( ($pfx_page_type == 'module') && ($pfx_page_name != 'contact') ) {
						echo "\t\t\t\t\t\t\t<li><a href=\"" . PREFS_SITE_URL . "admin/?s=publish&amp;m=module&amp;x={$pfx_page_name}&amp;go=new\" title=\"{$pfx_lang['new_entry']}{$pfx_page_display_name} {$pfx_lang['entry']}\" >{$pfx_lang['new_entry']}{$pfx_page_display_name} {$pfx_lang['entry']}</a></li>\n";
					}
				}
			}
			echo "\t\t\t\t\t\t\t<li class=\"linkspfx\"><a href=\"http://heydojo.co.cc/blog/\" title=\"PFX {$pfx_lang['blog']}\" target=\"_blank\">PFX {$pfx_lang['blog']}</a></li>\n";
			echo "\t\t\t\t\t\t\t<li><a href=\"http://heydojo.co.cc/forums/\" title=\"PFX {$pfx_lang['forums']}\" target=\"_blank\">PFX {$pfx_lang['forums']}</a></li>\n";
			echo "\t\t\t\t\t\t\t<li><a href=\"http://heydojo.co.cc/downloads/\" title=\"PFX {$pfx_lang['downloads']}\" target=\"_blank\">PFX {$pfx_lang['downloads']}</a></li>\n\t\t\t\t\t\t</ul>\n";
		}
			echo '</div><div class="admin_block\" id="admin_block_my_links">';
			echo "\n\t\t\t\t\t\t<h3 class=\"plinks\">" . firstword($GLOBALS['pfx_real_name']) . "'s {$pfx_lang['links']}</h3>\n\t\t\t\t\t\t<ul>\n";
			echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_link_1}\" title=\"Visit {$pfx_link_1}\" target=\"_blank\">" . str_replace('http://', "", $pfx_link_1) . "</a></li>\n";
			echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_link_2}\" title=\"Visit {$pfx_link_2}\" target=\"_blank\">" . str_replace('http://', "", $pfx_link_2) . "</a></li>\n";
			echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_link_3}\" title=\"Visit {$pfx_link_3}\" target=\"_blank\">" . str_replace('http://', "", $pfx_link_3) . "</a></li>\n";
			echo '</ul></div>';
		/* This RSS feature should be optional, not default and configurable */
		/*
		if (!defined('PFX_RSS_URL')) {
			define('PFX_RSS_URL', 'http://heydojo.co.cc/blog/rss');
		}
		if (url_exist(PFX_RSS_URL)) {
			include_once 'lib/lib_simplepie.php';
			$pfx_number_of_items = 24;
			$pfx_show_errors     = 'no';
			$pfx_new_tab         = 'yes';
			$pfx_cache_admin         = 'yes';
			echo '<div class="admin_block" id="admin_block_pfx_rss"><h3 class="plinks">PFX Blog</h3><ul>';
			showRss($pfx_number_of_items, PFX_RSS_URL, $pfx_show_errors, $pfx_new_tab, $pfx_cache_admin);
			echo '</ul></div>';
		}
		*/
		echo '</div>';
	}
		echo '</div></div></div>';
		safe_delete('pfx_log', "`log_time` < date_sub(utc_timestamp(),interval " . PREFS_LOGS_EXPIRE . " day)");
		safe_optimize('pfx_log');
		safe_repair('pfx_log');
		if (PREFS_SYSTEM_MESSAGE !== '') {
			$pfx_message = PREFS_SYSTEM_MESSAGE;
		}
	}
}