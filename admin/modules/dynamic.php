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
 * Title: Dynamic Page Module
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
switch ($pfx_do) {
	// Module Admin
	case 'admin' :
		if (isset($GLOBALS['pfx_user']) && $GLOBALS['pfx_user_privs'] >= 1) {
			if ($pfx_x == "") {
				$pfx_message = 'Please create a dynamic page in the settings area.';
			} else {
				$pfx_type       = 'dynamic';
				$pfx_table_name = 'pfx_dynamic_posts';
				$pfx_edit_id    = 'post_id';
				$pfx_page_id    = safe_field('page_id', 'pfx_core', "page_name='{$pfx_x}'");
				if ( (isset($pfx_go)) && ($pfx_go == 'new') ) {
					admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
					admin_new($pfx_lang, $pfx_table_name, $pfx_edit_exclude = array(
						'page_id',
						'post_id',
						'last_modified',
						'author',
						'post_views',
						'post_slug'
					), $pfx_go, $pfx_edit, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
				} else if ( (isset($pfx_edit)) && ($pfx_edit) ) {
					admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
					$pfx_message = admin_edit($pfx_table_name, $pfx_edit_id, $pfx_edit, $pfx_edit_exclude = array(
						'page_id',
						'post_id',
						'last_modified',
						'last_modified_by',
						'author',
						'post_views',
						'post_slug'
					), $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
				} else {
					$pfx_scroll = admin_carousel($pfx_lang, $pfx_x, $pfx_scroll, $pfx_s);
					admin_head($pfx_lang, $pfx_page_display_name, $pfx_page_id, $pfx_edit, $pfx_go, $pfx_tag, $pfx_search_words, $pfx_search_submit, $pfx_s, $pfx_m, $pfx_x);
					admin_block_search($pfx_type, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
					echo "\t\t\t\t<div id=\"pfx_content\">";
					admin_overview($pfx_table_name, "where page_id = '{$pfx_page_id}'", 'posted', 'desc', $pfx_exclude = array(
						'page_id',
						'post_id',
						'author',
						'public',
						'comments',
						'tags',
						'content',
						'last_modified',
						'last_modified_by',
						'post_views',
						'post_slug'
					), 15, $pfx_type, $pfx_lang, $pfx_page, $pfx_message, $pfx_messageok, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_tag, $pfx_page_display_name, $pfx_s, $pfx_m, $pfx_x);
					echo "\t\t\t\t</div>";
					echo "\t\t\t<div id=\"blocks\">\n";
					admin_block_tag_cloud($pfx_table_name, "page_id = '{$pfx_page_id}'", $pfx_type, $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
					echo "\t\t\t\t</div>\n";
				}
			}
		}
		break;
	// Show Module
	default :
		$pfx_dyn_sets = safe_row('*', 'pfx_dynamic_settings', "page_id = '{$pfx_page_id}'");
		extract($pfx_dyn_sets, EXTR_PREFIX_ALL, 'pfx');
		$pfx_dyn_sets = NULL;
		switch ($pfx_m) {
			case 'archives' :
				$pfx_mtitle = "{$pfx_page_display_name} ({$pfx_lang['archives']})";
				show_archives($pfx_lang, $pfx_mtitle, $pfx_page_display_name, $pfx_s, $pfx_m, $pfx_x);
				break;
			case 'permalink' :
				if (isset($pfx_comment_submit)) {
				    if (PREFS_CAPTCHA == 'yes') {
					$pfx_resp = recaptcha_check_answer(PREFS_RECAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
					if ($pfx_resp->is_valid) {
					} else {
					    $pfx_error .= "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
					}
				}
					if ($pfx_web == 'http://') {
						$pfx_web = "";
					}
					$pfx_howmanycomments = count( safe_rows('*', 'pfx_log', "log_message like '%{$pfx_lang['comment_save_log']}%' and user_ip = '{$_SERVER["REMOTE_ADDR"]}', INTERVAL -2 HOUR") );
					if ($pfx_howmanycomments >= 6) {
						/* Spam limit - 6 posts every 2 hours. */
						$pfx_error = $pfx_lang['comment_throttle_error'];
					}
					if ( stripos($pfx_comment, '[url=') ) { /* Prevent common BBCode spamming */
						$pfx_error .= "{$pfx_lang['comment_comment_error']} ";
					} else {
						require 'admin/lib/htmlpurifier/library/HTMLPurifier.php';
						$pfx_purify_comment_config = HTMLPurifier_Config::createDefault();
						$pfx_purify_comment_config->set('Cache.SerializerPath', 'files/cache');
						$pfx_purify_comment_config->set('AutoFormat.Linkify', TRUE);
						$pfx_comment_purifier = new HTMLPurifier($pfx_purify_comment_config);
						/* import the HTMLPurifier library */
						if (get_magic_quotes_gpc()) {
							$pfx_comment = stripslashes($pfx_comment);
						}
						$pfx_comment = $pfx_comment_purifier->purify($pfx_comment);
						$pfx_comment = nl2br( strip_tags($pfx_comment, '<strong><em><a><p>') );
						$pfx_comment = sterilise( str_replace('<a', '<a rel="external nofollow"', $pfx_comment) );
						$pfx_name    = sterilise( strip_tags($pfx_name) );
						if (isset($pfx_email)) {
							$pfx_email = sterilise( strip_tags($pfx_email) );
						}
						$pfx_web = sterilise( strip_tags($pfx_web) );
						$pfx_scream = array();
						if ( (isset($pfx_name)) && ($pfx_name) ) {
						} else {
							$pfx_error .= "{$pfx_lang['comment_name_error']} ";
							$pfx_scream[] = 'name';
						}
						$pfx_duplicate                = 0;
						if ( (isset($pfx_comment)) && ($pfx_comment) ) {
							$pfx_last_comment_last_number = getThing($pfx_query = 'SELECT * FROM ' . CONFIG_TABLE_PREFIX . 'pfx_module_comments ORDER BY comments_id DESC');
							$pfx_last_comment             = getThing($pfx_query = "SELECT comment FROM " . CONFIG_TABLE_PREFIX . "pfx_module_comments WHERE comments_id='{$pfx_last_comment_last_number}'");
							if (strcasecmp($pfx_comment, $pfx_last_comment) === 0) {
								$pfx_duplicate = 1;
							}
						} else {
							$pfx_error .= "{$pfx_lang['comment_comment_error']} ";
							$pfx_scream[] = 'comment';
						}
						$pfx_check = new Validator();
						if ($pfx_check->validateEmail($pfx_email, "{$pfx_lang['comment_email_error']} ")) {
						} else {
							$pfx_scream[] = 'email';
						}
						if ( (isset($pfx_web)) && ($pfx_web) ) {
							if ($pfx_check->validateURL($pfx_web, "{$pfx_lang['comment_web_error']} ")) {
							} else {
								$pfx_scream[] = 'web';
							}
						} else {
								$pfx_scream[] = 'web';
						}
						if ($pfx_check->foundErrors()) {
							$pfx_error .= $pfx_check->listErrors('x');
						}
					}
					$pfx_post     = sterilise_txt( strip_tags($pfx_post) );
					$pfx_commentson = safe_field('comments', 'pfx_dynamic_posts', "post_id ='{$pfx_post}'");
					if ($pfx_commentson == 'yes') {
						// PROBABLY NEED TO SAVE DATE ON COMMENT MANUALLY
						if ( (isset($pfx_error)) && ($pfx_error) ) {
							sleep(6); /* slow spammers down */
						} else {
							if ($pfx_duplicate !== 1) {
								if ( (isset($pfx_admin_user)) && ($pfx_admin_user) && (isset($pfx_comment)) && ($pfx_comment) && (isset($pfx_post)) && ($pfx_post) ) {
									$pfx_admin_user = strip_tags($pfx_admin_user);
									$pfx_sql        = "comment = '{$pfx_comment}', name = '{$pfx_name}', email = '{$pfx_email}', url = '{$pfx_web}', post_id = '{$pfx_post}', admin_user = 'yes'";
								} else if ( (isset($pfx_comment)) && ($pfx_comment) && (isset($pfx_post)) && ($pfx_post) ) {
									$pfx_sql = "comment = '{$pfx_comment}', name = '{$pfx_name}', email = '{$pfx_email}', url = '{$pfx_web}', post_id = '{$pfx_post}', admin_user = 'no'";
								}
								if ( (isset($pfx_sql)) && ($pfx_sql) ) {
									$pfx_comment_ok = safe_insert('pfx_module_comments', $pfx_sql);
									$pfx_title      = safe_field('title', 'pfx_dynamic_posts', "post_id ='{$pfx_post}'");
									/* $pfx_countcom   = count(safe_rows('*', 'pfx_module_comments', "post_id ='{$pfx_post}'")); */
								}
								if ( (isset($pfx_s)) && ($pfx_s) && (isset($pfx_sql)) && ($pfx_sql) ) {
									logme("{$pfx_name} {$pfx_lang['comment_save_log']}<a href=\"" . createURL($pfx_s, $pfx_m, $pfx_x) . "#comments\" title=\"{$pfx_title}\">{$pfx_title}</a>.", 'no', 'comment');
								}
							} else {
								if ( (isset($pfx_error)) && ($pfx_error) ) {
									$pfx_err   = explode('|', $pfx_error);
									$pfx_error = $pfx_err[0];
								}
							}
						}
					}
				}
				show_single($pfx_lang, $pfx_mtitle, $pfx_comments, $pfx_page_display_name, $pfx_comment_ok, $pfx_error, $pfx_scream, $pfx_sname, $pfx_semail, $pfx_scomment, $pfx_sweb, $pfx_s, $pfx_m, $pfx_x);
				break;
			case 'page' :
				$pfx_start  = $pfx_posts_per_page * ($pfx_x - 1);
				$pfx_mtitle = "{$pfx_page_display_name} ({$pfx_lang['dynamic_page']} {$pfx_x})";
				$pfx_rs     = safe_rows_start('*', 'pfx_dynamic_posts', "page_id = '{$pfx_page_id}' and public = 'yes' order by posted desc limit {$pfx_start},{$pfx_posts_per_page}");
				show_all($pfx_lang, $pfx_rs, $pfx_mtitle, $pfx_comments, $pfx_posts_per_page, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
				break;
			case 'popular' :
				$pfx_mtitle = "{$pfx_page_display_name} ({$pfx_lang['popular']} {$pfx_posts_per_page} {$pfx_lang['posts']})";
				$pfx_rs     = safe_rows_start('*', 'pfx_dynamic_posts', "page_id = '{$pfx_page_id}' and public = 'yes' order by post_views desc limit {$pfx_posts_per_page}");
				show_all($pfx_lang, $pfx_rs, $pfx_mtitle, $pfx_comments, $pfx_posts_per_page, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
				break;
			case 'tag' :
				if ($pfx_p) {
					$pfx_start  = $pfx_posts_per_page * ($pfx_p - 1);
					$pfx_mtitle = "{$pfx_page_display_name} ({$pfx_lang['tag']}: {$pfx_x}, {$pfx_lang['dynamic_page']} {$pfx_p})";
					$pfx_rs     = safe_rows_start('*', 'pfx_dynamic_posts', "page_id = '{$pfx_page_id}' and public = 'yes' and tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]' order by posted desc limit {$pfx_start}, {$pfx_posts_per_page}");
					show_all($pfx_lang, $pfx_rs, $pfx_mtitle, $pfx_comments, $pfx_posts_per_page, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
				} else {
					$pfx_x      = squash_slug($pfx_x);
					$pfx_mtitle = "{$pfx_page_display_name} ({$pfx_lang['tag']}: {$pfx_x})";
					$pfx_rs     = safe_rows_start('*', 'pfx_dynamic_posts', "page_id = '{$pfx_page_id}' and public = 'yes' and tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]' order by posted desc limit {$pfx_posts_per_page}");
					show_all($pfx_lang, $pfx_rs, $pfx_mtitle, $pfx_comments, $pfx_posts_per_page, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
				}
				break;
			case 'tags' :
				if (isset($pfx_s)) {
					$pfx_id = get_page_id($pfx_s);
					echo "<h3>{$pfx_page_display_name} ({$pfx_lang['tags']})</h3>\n\t\t\t\t\t<div class=\"tag_section\">\n";
					public_tag_cloud('pfx_dynamic_posts', "page_id = {$pfx_id} and public = 'yes'", $pfx_lang, $pfx_s, $pfx_m, $pfx_x);
					echo "\t\t\t\t\t</div>\n";
				}
				break;
			default :
				$pfx_mtitle = "{$pfx_page_display_name}";
				if (isset($pfx_s)) {
					$pfx_id = get_page_id($pfx_s);
					$pfx_rs = safe_rows_start('*', 'pfx_dynamic_posts', "page_id = '{$pfx_id}' and public = 'yes' order by posted desc limit {$pfx_posts_per_page}");
					show_all($pfx_lang, $pfx_rs, $pfx_mtitle, $pfx_comments, $pfx_posts_per_page, $pfx_s, $pfx_m, $pfx_x, $pfx_p);
				}
				break;
		}
		break;
}
// ------------------------------------------------------------------
// function show all
function show_all($pfx_lang, $pfx_rs, $pfx_mtitle = FALSE, $pfx_comments = FALSE, $pfx_posts_per_page = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE, $pfx_p = FALSE) {
	echo "<h3>{$pfx_mtitle}</h3>\n";
	if ( (isset($pfx_m)) && ($pfx_m) ) {
	} else {
		if ( (isset($pfx_s)) && ($pfx_s) ) {
			$pfx_page_description = safe_field('page_description', 'pfx_core', "page_name='{$pfx_s}'");
		}
	}
	$pfx_i = 0;
	if ($pfx_rs) {
		while ($pfx_a = nextRow($pfx_rs)) {
			extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
			$pfx_a = NULL;
			$pfx_logunix     = returnUnixtimestamp($pfx_posted);
			$pfx_date        = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
			$pfx_microformat = safe_strftime($pfx_lang, '%Y-%m-%dT%T%z', $pfx_logunix);
			$pfx_slug        = $pfx_post_slug;
			$pfx_fullname    = safe_field('realname', 'pfx_users', "user_name='{$pfx_author}'");
			if (public_page_exists('profiles')) {
				$pfx_mauthor = "<a href=\"" . createURL('profiles', $pfx_author) . "\" class=\"url fn\" title=\"{$pfx_lang['view']} {$pfx_fullname}'s {$pfx_lang['profile']}\">{$pfx_fullname}</a>";
			} else {
				$pfx_mauthor = "<a href=\"" . PREFS_SITE_URL . "\" class=\"url fn\" title=\"" . PREFS_SITE_URL . "\">{$pfx_fullname}</a>";
			}
			if ( (isset($pfx_tags)) && ($pfx_tags) ) {
				$pfx_all_tags        = strip_tags($pfx_tags);
				$pfx_all_tags        = str_replace('&quot;', "", $pfx_tags);
				$pfx_tags_array_temp = explode(" ", $pfx_all_tags);
				for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
					$pfx_current = $pfx_tags_array_temp[$pfx_count];
					$pfx_first   = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
					if ($pfx_first == " ") {
						$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
					}
					$pfx_ncurrent = make_slug($pfx_current);
					if ( (isset($pfx_s)) && ($pfx_s) && (isset($pfx_current)) && ($pfx_current) ) {
						if ( (isset($pfx_tag_list)) && ($pfx_tag_list) ) {
						} else {
							$pfx_tag_list = FALSE;
						}
						$pfx_tag_list .= "<a href=\"" . createURL($pfx_s, 'tag', $pfx_ncurrent) . "\" title=\"{$pfx_lang['view']} {$pfx_lang['all_posts_tagged']}: {$pfx_current}\" rel=\"tag\" >{$pfx_current}</a>, ";
					} else {
						$pfx_tag_list = FALSE;
					}
					if ( (isset($pfx_ncurrent)) && ($pfx_ncurrent != "") ) {
						if ( (isset($pfx_class_list)) && ($pfx_class_list) ) {
						} else {
							$pfx_class_list = FALSE;
						}
						$pfx_class_list .= "tag_{$pfx_ncurrent} ";
					} else {
						$pfx_class_list = FALSE;
					}
				}
				$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
			}
			$pfx_comms    = safe_rows('*', 'pfx_module_comments', "post_id = '{$pfx_post_id}'");
			$pfx_no_comms = count($pfx_comms);
			if (isset($pfx_s)) {
				$pfx_permalink = createURL($pfx_s, 'permalink', $pfx_slug);
			}
			$pfx_authorclass = strtolower($pfx_author);
			$pfx_timeclass   = safe_strftime($pfx_lang, 'y%Y m%m d%d h%H', $pfx_logunix);
			if (is_even($pfx_i + 1)) {
				$pfx_type = 'post_even';
			} else {
				$pfx_type = 'post_odd';
			}
			$pfx_num = $pfx_i + 1;
			echo "
					<div class=\"section hentry author_{$pfx_authorclass} {$pfx_class_list}{$pfx_timeclass} {$pfx_type} post_{$pfx_num}\" id=\"post_{$pfx_post_id}\">
						<h4 class=\"entry-title\"><a href=\"{$pfx_permalink}\" rel=\"bookmark\">{$pfx_title}</a></h4>
						<ul class=\"post_links\">
							<li class=\"post_date\"><abbr class=\"published\" title=\"{$pfx_microformat}\">{$pfx_date}</abbr></li>
							<li class=\"post_permalink\"><a href=\"{$pfx_permalink}\" title=\"{$pfx_lang['permalink_to']}: {$pfx_title}\">{$pfx_lang['permalink']}</a></li>";
			if (public_page_exists('comments')) {
				if (($pfx_comments == 'yes') or ($pfx_no_comms)) {
					echo "\n\t\t\t\t\t\t\t<li class=\"post_comments\"><a href=\"{$pfx_permalink}#comments\" title=\"{$pfx_lang['comments']}\">{$pfx_lang['comments']}</a> ({$pfx_no_comms})</li>";
				}
			}
			if (isset($_COOKIE['pfx_login'])) {
				list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
				$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
				if (hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash) {
					$pfx_privs = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'");
					if ($pfx_privs >= 1) {
						echo "\n\t\t\t\t\t\t\t<li class=\"post_edit\"><a class=\"quick-edit\" href=\"" . PREFS_SITE_URL . "admin/?s=publish&amp;m=dynamic";
						if (isset($pfx_s)) {
							echo "&amp;x={$pfx_s}";
						}
						echo "&amp;edit={$pfx_post_id}\" title=\"{$pfx_lang['edit_post']}\">{$pfx_lang['edit_post']}</a></li>";
					}
				}
			}
			echo "
						</ul>
						<div class=\"post entry-content\">\n";
			//<!--more-->
			$pfx_post = get_extended($pfx_content);
			echo "\t\t\t\t\t\t\t{$pfx_post['main']}";
			if ($pfx_post['extended']) {
				echo "\n\t\t\t\t\t\t\t<p><a href=\"{$pfx_permalink}\" class=\"read-more\" title=\"{$pfx_lang['continue_reading']} {$pfx_title}\">{$pfx_lang['continue_reading']} " . rtrim( substr($pfx_title, 0, 32) ) . '...</a></p>';
			}
			echo "
						</div>
						<div class=\"post_credits\">
						 	<span class=\"vcard author\">{$pfx_lang['by']} {$pfx_mauthor}</span>
						 	<span class=\"post_tags\">{$pfx_lang['tagged']}: {$pfx_tag_list}</span>
						</div>
					</div>\n\n";
			$pfx_tag_list   = "";
			$pfx_class_list = "";
			$pfx_i++;
		}
	}
	echo "\t\t\t\t\t<div id=\"nav_pages\" class=\"dynamic_bottom_nav\">\n";
	if ( (isset($pfx_m)) && ($pfx_m == 'tag') ) {
		$pfx_p          = squash_slug($pfx_p);
		$pfx_totalposts = count(safe_rows('*', 'pfx_dynamic_posts', "page_id = '{$pfx_page_id}' and public = 'yes' and tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]'"));
		if ($pfx_p) {
			$pfx_currentnum   = $pfx_posts_per_page * $pfx_p;
			$pfx_nextpage     = $pfx_p + 1;
			$pfx_previouspage = $pfx_p - 1;
		} else {
			$pfx_nextpage   = 2;
			$pfx_currentnum = $pfx_posts_per_page;
		}
		//echo $pfx_totalposts." - ".$pfx_currentnum;
		if ($pfx_totalposts > $pfx_currentnum) {
			// then we need to link onto the next page	
			echo "\t\t\t\t\t\t<div id=\"page_next\" class=\"link_next\"><a class=\"link_next_a\" href=\"" . createURL($pfx_s, $pfx_m, $pfx_x, $pfx_nextpage) . "\" title=\"{$pfx_lang['next_page']}: {$pfx_nextpage}\">{$pfx_lang['next_page']} &raquo;</a></div>\n";
		}
		if ($pfx_p >= 2) {
			if ($pfx_previouspage == 1) {
				echo "\t\t\t\t\t\t<div id=\"page_previous\" class=\"link_previous\"><a class=\"link_prev_a\" href=\"" . createURL($pfx_s, $pfx_m, $pfx_x) . "\" title=\"{$pfx_lang['previous_page']}: {$pfx_previouspage}\">&laquo; {$pfx_lang['previous_page']}</a></div>\n";
			} else {
				echo "\t\t\t\t\t\t<div id=\"page_previous\" class=\"link_previous\"><a class=\"link_prev_a\" href=\"" . createURL($pfx_s, $pfx_m, $pfx_x, $pfx_previouspage) . "\" title=\"{$pfx_lang['previous_page']}: {$pfx_previouspage}\">&laquo; {$pfx_lang['previous_page']}</a></div>\n";
			}
		}
		// pagination for tags pages needs to be different... coming soon
	} else {
		// how many posts do we have in total?
		if (isset($pfx_page_id)) {
			$pfx_totalposts = count(safe_rows('*', 'pfx_dynamic_posts', "page_id = '{$pfx_page_id}' and public = 'yes'"));
		} else {
			$pfx_totalposts = 0;
		}
		if ($pfx_m == 'page') {
			$pfx_currentnum   = $pfx_posts_per_page * $pfx_x;
			$pfx_nextpage     = $pfx_x + 1;
			$pfx_previouspage = $pfx_x - 1;
		} else {
			$pfx_nextpage   = 2;
			$pfx_currentnum = $pfx_posts_per_page;
		}
		//echo $pfx_totalposts." - ".$pfx_currentnum;
		if ($pfx_totalposts > $pfx_currentnum) {
			// then we need to link onto the next page	
			echo "\t\t\t\t\t\t<div id=\"page_next\" class=\"link_next\"><a class=\"link_next_a\" href=\"";
			if (isset($pfx_s)) {
				echo createURL($pfx_s, 'page', $pfx_nextpage);
			}
			echo "\" title=\"{$pfx_lang['next_page']}: {$pfx_nextpage}\">{$pfx_lang['next_page']} &raquo;</a></div>\n";
		}
		if ($pfx_m == 'page') {
			if ($pfx_x >= 2) {
				if ($pfx_previouspage == 1) {
					echo "\t\t\t\t\t\t<div id=\"page_previous\" class=\"link_previous\"><a class=\"link_prev_a\" href=\"";
					if (isset($pfx_s)) {
						echo createURL($pfx_s);
					}
					echo "\" title=\"{$pfx_lang['previous_page']}: {$pfx_previouspage}\">&laquo; {$pfx_lang['previous_page']}</a></div>\n";
				} else {
					echo "\t\t\t\t\t\t<div id=\"page_previous\" class=\"link_previous\"><a class=\"link_prev_a\" href=\"";
					if (isset($pfx_s)) {
						echo createURL($pfx_s, 'page', $pfx_previouspage);
					}
					echo "\" title=\"{$pfx_lang['previous_page']}: {$pfx_previouspage}\">&laquo; {$pfx_lang['previous_page']}</a></div>\n";
				}
			}
		}
	}
	echo "\t\t\t\t\t</div>\n";
}
// ------------------------------------------------------------------
// function show single
function show_single($pfx_lang, $pfx_mtitle = FALSE, $pfx_comments = FALSE, $pfx_page_display_name = FALSE, $pfx_comment_ok = FALSE, $pfx_error = FALSE, $pfx_scream = FALSE, $pfx_sname = FALSE, $pfx_semail = FALSE, $pfx_scomment = FALSE, $pfx_sweb = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_rs = safe_row('*', 'pfx_dynamic_posts', "post_slug = '{$pfx_x}' and public = 'yes' limit 0,1");
	if ($pfx_rs) {
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = NULL;
		safe_update('pfx_dynamic_posts', "post_views  = {$pfx_post_views} + 1", "post_id = '{$pfx_post_id}'");
		$pfx_logunix     = returnUnixtimestamp($pfx_posted);
		$pfx_date        = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
		$pfx_timeunix    = returnUnixtimestamp($pfx_last_modified);
		$pfx_xdate       = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_timeunix);
		$pfx_microformat = safe_strftime($pfx_lang, '%Y-%m-%dT%T%z', $pfx_logunix);
		$pfx_slug        = $pfx_post_slug;
		$pfx_fullname    = safe_field('realname', 'pfx_users', "user_name='{$pfx_author}'");
		if (public_page_exists('profiles')) {
			$pfx_mauthor = "<a href=\"" . createURL('profiles', $pfx_author) . "\" class=\"url fn\" title=\"{$pfx_lang['view']} {$pfx_fullname}'s {$pfx_lang['profile']}\">{$pfx_fullname}</a>";
		} else {
			$pfx_mauthor = "<a href=\"" . PREFS_SITE_URL . "\" class=\"url fn\" title=\"" . PREFS_SITE_URL . "\">{$pfx_fullname}</a>";
		}
		if ( (isset($pfx_tags)) && ($pfx_tags) ) {
			$pfx_all_tags        = strip_tags($pfx_tags);
			$pfx_all_tags        = str_replace('&quot;', "", $pfx_tags);
			$pfx_tags_array_temp = explode(" ", $pfx_all_tags);
			for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
				$pfx_current = $pfx_tags_array_temp[$pfx_count];
				$pfx_first   = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
				if ($pfx_first == " ") {
					$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
				}
				$pfx_ncurrent = make_slug($pfx_current);
				if ( (isset($pfx_s)) && (isset($pfx_ncurrent)) ) {
					if ( (isset($pfx_tag_list)) && ($pfx_tag_list) ) {
					} else {
						$pfx_tag_list = FALSE;
					}
					$pfx_tag_list .= "<a href=\"" . createURL($pfx_s, 'tag', $pfx_ncurrent) . "\" title=\"{$pfx_lang['view']} {$pfx_lang['all_posts_tagged']}: {$pfx_current}\"  rel=\"tag\" >{$pfx_current}</a>, ";
				}
				if ($pfx_ncurrent != "") {
					if ( (isset($pfx_class_list)) && ($pfx_class_list) ) {
					} else {
						$pfx_class_list = FALSE;
					}
					$pfx_class_list .= "tag_{$pfx_ncurrent} ";
				}
			}
			$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
		}
		if (isset($pfx_s)) {
			$pfx_permalink = createURL($pfx_s, 'permalink', $pfx_slug);
		}
		$pfx_authorclass = strtolower($pfx_author);
		$pfx_timeclass   = safe_strftime($pfx_lang, 'y%Y m%m d%d h%H', $pfx_logunix);
		echo "<div class=\"section hentry author_{$pfx_authorclass} {$pfx_class_list}{$pfx_timeclass} single\" id=\"post_{$pfx_post_id}\">
				<h4 class=\"entry-title\"><a href=\"{$pfx_permalink}\" rel=\"bookmark\">{$pfx_title}</a></h4>
					<ul class=\"post_links\">
						<li class=\"post_date\"><abbr class=\"published\" title=\"{$pfx_microformat}\">{$pfx_date}</abbr></li>";
		if (isset($_COOKIE['pfx_login'])) {
			list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
			$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
			if (hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash) {
				$pfx_privs = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'");
				if ($pfx_privs >= 1) {
					echo "\n\t\t\t\t\t\t\t<li class=\"post_edit\"><a class=\"quick-edit\" href=\"" . PREFS_SITE_URL . "admin/?s=publish&amp;m=dynamic";
					if (isset($pfx_s)) {
						echo "&amp;x={$pfx_s}";
					}
					echo "&amp;edit={$pfx_post_id}\" title=\"{$pfx_lang['edit_post']}\">{$pfx_lang['edit_post']}</a></li>";
				}
			}
		}
		echo "
						</ul>
						<div class=\"post entry-content\">\n";
		//<!--more-->
		$pfx_post = get_extended($pfx_content);
		echo "\t\t\t\t\t\t\t{$pfx_post['main']}";
		if ($pfx_post['extended']) {
			echo $pfx_post['extended'];
		}
		echo "
						</div>		
						<div class=\"post_credits\">
						 	<span class=\"vcard author\">{$pfx_lang['by']} {$pfx_mauthor}</span>
						 	<span class=\"post_tags\">{$pfx_lang['tagged']}: {$pfx_tag_list}</span>
						 	<span class=\"post_updated\">{$pfx_lang['last_updated']}: {$pfx_xdate} </span>
						</div>			
					</div>
					
					<div id=\"nav_posts\" class=\"dynamic_bottom_nav\">\n";
		// previous and next posts
		if (isset($pfx_s)) {
			$pfx_thisid = get_page_id($pfx_s);
		}
		// what post is next?
		$pfx_searchnext = safe_field('post_id', 'pfx_dynamic_posts', "page_id = '{$pfx_thisid}' and public = 'yes' and posted > '{$pfx_posted}' limit 0,1");
		if ($pfx_searchnext) {
			$pfx_ntitle = safe_field('title', 'pfx_dynamic_posts', "post_id ='{$pfx_searchnext}'");
			$pfx_nslug  = safe_field('post_slug', 'pfx_dynamic_posts', "post_id ='{$pfx_searchnext}'");
			echo "\t\t\t\t\t\t<div id=\"post_next\" class=\"link_next\"><a class=\"link_next_a\" href=\"";
			if (isset($pfx_s)) {
				echo createURL($pfx_s, 'permalink', $pfx_nslug);
			}
			echo "\" title=\"{$pfx_lang['next_post']}: {$pfx_ntitle}\">{$pfx_lang['next_post']} &raquo;</a></div>\n";
		}
		// what post is previous?
		$pfx_searchprev = safe_field('post_id', 'pfx_dynamic_posts', "page_id = '{$pfx_thisid}' and public = 'yes' and posted < '{$pfx_posted}' order by posted desc limit 0,1");
		if ($pfx_searchprev) {
			$pfx_ptitle = safe_field('title', 'pfx_dynamic_posts', "post_id ='{$pfx_searchprev}'");
			$pfx_pslug  = safe_field('post_slug', 'pfx_dynamic_posts', "post_id ='{$pfx_searchprev}'");
			echo "\t\t\t\t\t\t<div id=\"post_previous\" class=\"link_previous\"><a class=\"link_prev_a\" href=\"";
			if (isset($pfx_s)) {
				echo createURL($pfx_s, 'permalink', $pfx_pslug);
			}
			echo "\" title=\"{$pfx_lang['previous_post']}: ";
			if (isset($pfx_ptitle)) {
				echo $pfx_ptitle;
			}
			echo "\">&laquo; {$pfx_lang['previous_post']}</a></div>\n";
		}
		echo "\t\t\t\t\t</div>\n";
		$pfx_comms    = safe_rows('*', 'pfx_module_comments', "post_id = '{$pfx_post_id}'");
		$pfx_no_comms = count($pfx_comms);
		// fix to remove commenting when plug in is removed
		if (public_page_exists('comments')) {
				echo "\n\t\t\t\t\t<div id=\"comments\">
						<h4 id=\"comments_title\">{$pfx_lang['comments']}</h4>";
				if (isset($_COOKIE['pfx_login'])) {
					list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
					$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
					if (hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash) {
						$pfx_realname = safe_field('realname', 'pfx_users', "user_name='{$pfx_username}'");
						$pfx_umail    = safe_field('email', 'pfx_users', "user_name='{$pfx_username}'");
					}
				}
				$pfx_r2 = safe_rows('*', 'pfx_module_comments', "post_id = '{$pfx_post_id}' order by posted asc");
				if ($pfx_r2) {
					$pfx_i = 0;
					while ($pfx_i < $pfx_no_comms) {
						extract($pfx_r2[$pfx_i], EXTR_PREFIX_ALL, 'pfx');
						$pfx_default = PREFS_SITE_URL . 'files/images/no_grav.png';
						if (isset($pfx_email)) {
							$pfx_grav_url = 'http://www.gravatar.com/avatar.php?gravatar_id=' . md5($pfx_email) . '&amp;default=' . urlencode($pfx_default) . '&amp;size=64';
						}
						$pfx_hash = $pfx_i + 1;
						if ($pfx_url) {
							$pfx_namepr = "<span class=\"message_name author\"><a href=\"{$pfx_permalink}#comment_{$pfx_hash}\" rel=\"bookmark\" class=\"comment_permalink\">#{$pfx_hash}</a> <a href=\"" . htmlentities($pfx_url, ENT_QUOTES,PFX_CHARSET) . "\" rel=\"external nofollow\" class=\"url fn\">{$pfx_name}</a></span>";
						} else {
							$pfx_namepr = "<span class=\"message_name author\"><a href=\"{$pfx_permalink}#comment_{$pfx_hash}\" rel=\"bookmark\" class=\"comment_permalink\">#{$pfx_hash}</a> <span class=\"fn\">{$pfx_name}</span></span>";
						}
						if (is_even($pfx_i + 1)) {
							$pfx_type = 'comment_even';
						} else {
							$pfx_type = 'comment_odd';
						}
						if ($pfx_admin_user == 'yes') {
							$pfx_atype = ' comment_admin';
						} else {
							$pfx_atype = "";
						}
						$pfx_logunix            = returnUnixtimestamp($pfx_posted);
						$pfx_days_ago           = safe_strftime($pfx_lang, 'since', $pfx_logunix);
						$pfx_microformatcomment = safe_strftime($pfx_lang, '%Y-%m-%dT%T%z', $pfx_logunix);
						$pfx_commenttimeclass   = safe_strftime($pfx_lang, 'c_y%Y c_m%m c_d%d c_h%H', $pfx_logunix);
						echo "
						<div class=\"$pfx_type hentry comment comment_author_" . str_replace('-', '_', make_slug($pfx_name)) . " $pfx_commenttimeclass" . $pfx_atype . "\" id=\"comment_{$pfx_hash}\">
							<div class=\"comment_message\">
								<div class=\"message_details vcard\">
									<img src=\"{$pfx_grav_url}\" alt=\"Gravatar Image\" class=\"gr photo\" />
									{$pfx_namepr}
									<span class=\"message_time\"><abbr class=\"published\" title=\"{$pfx_microformatcomment}\">{$pfx_days_ago}</abbr></span>
								</div>
								<div class=\"message_body entry-title entry-content\"><p>{$pfx_comment}</p></div>";
						if (isset($_COOKIE['pfx_login'])) {
							list($pfx_username, $pfx_cookie_hash) = explode(',', $_COOKIE['pfx_login']);
							$pfx_nonce = safe_field('nonce', 'pfx_users', "user_name='{$pfx_username}'");
							if (hash('sha256', "{$pfx_username}{$pfx_nonce}") == $pfx_cookie_hash) {
								$pfx_privs = safe_field('privs', 'pfx_users', "user_name='{$pfx_username}'");
									if ($pfx_privs >= 1) {
										echo '<span class="post_edit"><p><a href="' . PREFS_SITE_URL . 'admin/?s=publish&amp;m=module';
										if (isset($pfx_s)) {
											echo '&amp;x=comments';
										}
										echo "&amp;page={$pfx_post_id}&amp;delete={$pfx_comments_id}\" class=\"confirm-del\" title=\"{$pfx_lang['delete']} {$pfx_lang['comment']}\">{$pfx_lang['delete']} {$pfx_lang['comment']}</a></p></span>";
									}
							}
						}
						echo '</div></div>';
						$pfx_i++;
					}
					$pfx_r2 = NULL;
				} else {
					echo "\n\t\t\t\t\t\t<span class=\"comments_none\">{$pfx_lang['no_comments']}</span>";
				}
				echo '</div><div class="comment_form" id="commentform">';
				if ( (isset($pfx_comment_ok)) && ($pfx_comment_ok) ) {
					echo "\n\t\t\t\t\t\t\t<p class=\"success\">{$pfx_lang['comment_thanks']}</p>";
				} else if ($pfx_comments == 'yes') {
					if (isset($pfx_s)) {
						$pfx_posty = createURL($pfx_s, $pfx_m, $pfx_x);
					}
					echo "<form accept-charset=\"" . PFX_CHARSET . "\" action=\"{$pfx_posty}#commentform\" method=\"post\" class=\"form\" id=\"comment-form\">
							<fieldset>
								<legend>{$pfx_lang['comment_leave']}</legend>";
					if ( (isset($pfx_error)) && ($pfx_error) ) {
						echo "\n\t\t\t\t\t\t\t\t<p class=\"error\">{$pfx_error}</p>";
						if (in_array('name', $pfx_scream)) {
							$pfx_name_style = 'form_highlight';
						}
						if (in_array('comment', $pfx_scream)) {
							$pfx_comment_style = 'form_highlight';
						}
						if (in_array('email', $pfx_scream)) {
							$pfx_email_style = 'form_highlight';
						}
						if (in_array('web', $pfx_scream)) {
							$pfx_web_style = 'form_highlight';
						}
					} else {
						echo "<p class=\"notice\">{$pfx_lang['comment_form_info']}</p>";
					}
					echo "<div class=\"form_row ";
					if (isset($pfx_name_style)) {
						echo $pfx_name_style;
					}
					echo "\"><div class=\"form_label\"><label for=\"comment_name\">{$pfx_lang['comment_name']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>";
					if ((isset($pfx_realname)) && ($pfx_realname)) {
						echo "\n\t\t\t\t\t\t\t\t\t<div class=\"form_item\"><input type=\"text\" disabled=\"disabled\" tabindex=\"1\" name=\"name\" class=\"form_text\" id=\"comment_name\"";
						if (isset($pfx_realname)) {
							echo " value=\"{$pfx_realname}\"";
						}
						echo ' /></div>';
					} else {
						echo "\n\t\t\t\t\t\t\t\t<div class=\"form_item\"><input type=\"text\" tabindex=\"1\" name=\"name\" class=\"form_text\" id=\"comment_name\" value=\"{$pfx_sname}\"/></div>";
					}
					if ($pfx_sweb == "") {
						$pfx_sweb = 'http://';
					}
					if ((isset($pfx_realname)) && ($pfx_realname)) {
						$pfx_sweb   = PREFS_SITE_URL;
						$pfx_semail = $pfx_umail;
					}
					echo '</div><div class=\"form_row ';
					if (isset($pfx_email_style)) {
						echo $pfx_email_style;
					}
					echo "\"><div class=\"form_label\"><label for=\"comment_email\">{$pfx_lang['comment_email']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
						<div class=\"form_item\"><input type=\"text\" tabindex=\"2\" name=\"email\" class=\"form_text\" id=\"comment_email\" value=\"{$pfx_semail}\" /></div>
						</div>
						<div class=\"form_row ";
					if (isset($pfx_web_style)) {
						echo $pfx_web_style;
					}
					echo "\">
						<div class=\"form_label\"><label for=\"comment_web\">{$pfx_lang['comment_web']} <span class=\"form_optional\">{$pfx_lang['form_optional']}</span></label></div>
						<div class=\"form_item\"><input type=\"text\" tabindex=\"2\" name=\"web\" class=\"form_text\" id=\"comment_web\" value=\"{$pfx_sweb}\" /></div>
						</div>
						<div class=\"form_row ";
					if (isset($pfx_comment_style)) {
						echo $pfx_comment_style;
					}
					echo "\">
						<div class=\"form_label\"><label for=\"comment\">{$pfx_lang['comment']} <span class=\"form_required\">{$pfx_lang['form_required']}</span></label></div>
						<div class=\"form_item\"><textarea name=\"comment\" tabindex=\"3\" id=\"comment\" class=\"form_text_area\" cols=\"25\" rows=\"5\">{$pfx_scomment}</textarea></div>
						</div>
						<div class=\"form_row_submit\">";
					if (PREFS_CAPTCHA == 'yes') {
	echo '<div class="form_row">
		<div id="recaptcha_div"></div>
		<div id="captchadiv">
			<div id="recaptcha_image"></div>';
echo "<div class=\"form_label\">
		<label>{$pfx_lang['captcha_text']}</label>
		<span class=\"form_required\">*</span>
	</div>";
			echo '<input type="text" name="recaptcha_response_field" id="recaptcha_response_field" />
			<div>
				<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
			</div>
			<div>
				<a href="javascript:Recaptcha.showhelp()">Help</a>
			</div>
			<noscript>
				<div id="no-comment">Sorry. You may not comment if javascript is disabled in your web browser, for security reasons. Thank you.</div>
			</noscript>
		</div>
</div>';
					}
					echo "<input type=\"submit\" name=\"comment_submit\" tabindex=\"4\" value=\"{$pfx_lang['comment_button_leave']}\" class=\"form_submit\" />
									<input type=\"hidden\" name=\"post\" value=\"{$pfx_post_id}\" />";
					if ((isset($pfx_realname)) && ($pfx_realname)) {
						echo "\n\t\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"admin_user\" value=\"" . hash('sha256', $pfx_nonce) . "\" />
									<input type=\"hidden\" name=\"name\"";
						if (isset($pfx_realname)) {
							echo " value=\"{$pfx_realname}\"";
						}
						echo " />";
					}
					echo "</div></fieldset></form>";
				} else {
					echo "\n\t\t\t\t\t\t<br /><span class=\"notice comments_closed\">{$pfx_lang['comment_closed']}</span>";
				}
				echo '</div>';
			// end if comments plugin enabled
		}
	} else {
		$pfx_core_page_404 = safe_row('*', 'pfx_core', "page_name='404'");
		extract($pfx_core_page_404, EXTR_PREFIX_ALL, 'pfx');
		$pfx_core_page_404 = NULL;
		$pfx_static_page_id = safe_row('*', 'pfx_static_posts', "page_id='{$pfx_page_id}'");
		extract($pfx_static_page_id, EXTR_PREFIX_ALL, 'pfx');
		$pfx_static_page_id = NULL;
		if (isset($pfx_s)) {
			echo "<div id=\"{$pfx_s}\">\n\t\t\t\t\t\t<h3>{$pfx_page_display_name}</h3>\n";
			eval('?>' . $pfx_page_content . '<?php ');
			echo "\n\t\t\t\t\t</div>\n";
		}
	}
}
// ------------------------------------------------------------------
// function show archives
function show_archives($pfx_lang, $pfx_mtitle = FALSE, $pfx_page_display_name = FALSE, $pfx_s = FALSE, $pfx_m = FALSE, $pfx_x = FALSE) {
	$pfx_date_array = getdate();
	$pfx_this_month = $pfx_date_array['mon'];
	$pfx_this_year  = $pfx_date_array['year'];
	if (isset($pfx_s)) {
		$pfx_id = get_page_id($pfx_s);
		$pfx_rs = safe_row('*', 'pfx_dynamic_posts', "page_id = '{$pfx_id}' and public = 'yes' order by posted asc limit 0,1");
	}
	echo "<div id=\"archives\">\n\t\t\t\t\t\t<h3>{$pfx_mtitle}</h3>\n\t\t\t\t\t\t<dl class=\"list_archives\">\n";
	if ($pfx_rs) {
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = NULL;
		$pfx_last_year = returnUnixtimestamp($pfx_posted);
		$pfx_last_year = date('Y', $pfx_last_year);
		$pfx_num       = (($pfx_this_year - $pfx_last_year) * 12) + 12;
		$pfx_i         = 0;
		while ($pfx_i < $pfx_num) {
			$pfx_smonth      = mktime(0, 0, 0, $pfx_this_month, 1, $pfx_this_year);
			$pfx_start_month = safe_strftime($pfx_lang, '%Y-%m-%d', $pfx_smonth);
			$pfx_last_day    = date('t', $pfx_smonth);
			$pfx_emonth      = mktime(23, 59, 59, $pfx_this_month, $pfx_last_day, $pfx_this_year);
			$pfx_end_month   = safe_strftime($pfx_lang, '%Y-%m-%d %H:%M:%S', $pfx_emonth);
			$pfx_search      = safe_rows('*', 'pfx_dynamic_posts', "page_id = '{$pfx_id}' and public = 'yes' and posted between '{$pfx_start_month}' and date '{$pfx_end_month}' order by posted desc");
			if ($pfx_search) {
				echo "\t\t\t\t\t\t\t<dt>" . date('F', $pfx_smonth) . " " . $pfx_this_year . "</dt>\n";
				$pfx_numy = count($pfx_search);
				$pfx_j    = 0;
				while ($pfx_j < $pfx_numy) {
					$pfx_out   = $pfx_search[$pfx_j];
					$pfx_title = $pfx_out['title'];
					$pfx_posty = $pfx_out['posted'];
					$pfx_slug  = $pfx_out['post_slug'];
					$pfx_stamp = returnUnixtimestamp($pfx_posty);
					$pfx_day   = date('d', $pfx_stamp);
					echo "\t\t\t\t\t\t\t<dd><span class=\"archive_subdate\">{$pfx_day}:</span> <a href=\"";
					if (isset($pfx_s)) {
						echo createURL($pfx_s, 'permalink', $pfx_slug);
					}
					echo "\" title=\"{$pfx_lang['permalink_to']}: {$pfx_title}\">{$pfx_title}</a></dd>\n";
					$pfx_j++;
				}
				$pfx_this_month = $pfx_this_month - 1;
				if ($pfx_this_month == 0) {
					$pfx_this_month = 12;
					$pfx_this_year  = $pfx_this_year - 1;
				}
			} else {
				$pfx_this_month = $pfx_this_month - 1;
				if ($pfx_this_month == 0) {
					$pfx_this_month = 12;
					$pfx_this_year  = $pfx_this_year - 1;
				}
			}
			$pfx_i++;
		}
	}
	echo "\t\t\t\t\t\t</dl>\n\t\t\t\t\t</div>";
}