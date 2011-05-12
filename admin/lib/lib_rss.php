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
 * Title: lib_rss
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
// finds all dynamic pages with rss enabled and outputs list
function showRss($pfx_number_of_items, $pfx_rss_url, $pfx_show_errors, $pfx_new_tab, $pfx_cache_admin) {
	if ( (isset($pfx_number_of_items)) && (isset($pfx_rss_url)) && (isset($pfx_show_errors)) ) {
		echo "\n";
		$pfx_feed = new SimplePie();
		$pfx_feed->set_timeout(30);
		$pfx_feed->set_feed_url($pfx_rss_url);
		$pfx_feed->enable_cache(TRUE);
		if ( (isset($pfx_cache_admin)) && ($pfx_cache_admin == 'yes') ) {
			$pfx_feed->set_cache_location('../files/cache');
		} else {
			$pfx_feed->set_cache_location('files/cache');
		}
		$pfx_feed->set_item_limit($pfx_number_of_items);
		$pfx_feed->set_cache_duration(900);
		$pfx_feed->init();
		$pfx_feed->handle_content_type();
		$pfx_feed_items = $pfx_feed->get_items(0, $pfx_number_of_items);
		if ( ($pfx_show_errors == 'yes') && ($pfx_feed->error()) ) {
			echo $pfx_feed->error();
		}
		foreach ($pfx_feed_items as $pfx_item):
			$pfx_item_link  = $pfx_item->get_permalink();
			$pfx_item_title = $pfx_item->get_title();
			if ( ($pfx_new_tab == 'yes') ) {
				echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_item_link}\" target=\"_blank\">{$pfx_item_title}</a></li>\n";
			} else {
				echo "\t\t\t\t\t\t\t<li><a href=\"{$pfx_item_link}\">{$pfx_item_title}</a></li>\n";
			}
		endforeach;
		echo "<li style=\"display:none;\"></li>\n";
		/* Prevents invalid markup if the list is empty */
		return TRUE;
	} else {
		return FALSE;
	}
}
// ------------------------------------------------------------------
// finds all dynamic pages with rss enabled and outputs list
function build_rss() {
	if (public_page_exists('rss')) {
		$pfx_i   = 0;
		/* Prevents insecure undefined variable $pfx_i */
		$pfx_rs  = safe_rows_start('*', 'pfx_module_rss', "1 order by feed_display_name desc");
		$pfx_num = count($pfx_rs);
		if ($pfx_rs) {
			while ($pfx_a = nextRow($pfx_rs)) {
				extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
				$pfx_a = NULL;
				echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"{$pfx_feed_display_name}\" href=\"{$pfx_url}\" />\n\t";
				$pfx_i++;
			}
		}
	} else {
		$pfx_rs  = safe_rows('*', 'pfx_dynamic_settings', "rss = 'yes'");
		$pfx_num = count($pfx_rs);
		if ($pfx_rs) {
			$pfx_i = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out     = $pfx_rs[$pfx_i];
				$pfx_page_id = $pfx_out['page_id'];
				$pfx_rs1     = safe_row('*', 'pfx_core', "page_id = '{$pfx_page_id}' limit 0,1");
				extract($pfx_rs1, EXTR_PREFIX_ALL, 'pfx');
				$pfx_rs1 = NULL;
				echo "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"" . PREFS_SITE_NAME . " - {$pfx_page_display_name}\" href=\"" . PREFS_SITE_URL . "{$pfx_page_name}/rss/\" />\n\t";
				$pfx_i++;
			}
		}
	}
}
// ------------------------------------------------------------------
// Build an RSS output of the current dynamic page
function rss($pfx_page_name, $pfx_page_display_name, $pfx_page_id, $pfx_lang, $pfx_s = FALSE) {
	header("Content-type: text/xml");
	echo ('<?xml version="1.0" encoding="' . PFX_CHARSET . '"?>');
?>
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:dc="http://purl.org/dc/elements/1.1/">

		<channel>
			<title><?php
	echo PREFS_SITE_NAME . " - {$pfx_page_display_name} ({$pfx_lang['rss_feed']})";
?></title>
			<description><?php
	echo PREFS_SITE_NAME . " - {$pfx_page_display_name}";
?></description>
			<link><?php
	if (PREFS_CLEAN_URLS == 'yes') {
		echo createURL($pfx_page_name);
	} else {
		echo createURL("{$pfx_page_name}?referrer=rss");
	}
?></link>
			<generator>PFX installed @ <?php
	echo PREFS_SITE_URL;
?></generator>
			<language>en</language>
			<image>
				<url><?php
	echo PREFS_SITE_URL;
?>files/images/rss_feed_icon.png</url>
				<link><?php
	if (PREFS_CLEAN_URLS == 'yes') {
		echo createURL($pfx_page_name);
	} else {
		echo createURL("{$pfx_page_name}?referrer=rss");
	}
?></link>
				<title><?php
	echo PREFS_SITE_NAME . " - {$pfx_page_display_name} ({$pfx_lang['rss_feed']})";
?></title>
			</image>

			<atom:link href="<?php
	print createURL($pfx_page_name, 'rss');
?>" rel="self" type="application/rss+xml" />
<?php
	$pfx_dynamic_page_id = safe_row('*', 'pfx_dynamic_settings', "page_id='{$pfx_page_id}' limit 0,1");
	extract($pfx_dynamic_page_id, EXTR_PREFIX_ALL, 'pfx');
	$pfx_dynamic_page_id = NULL;
	if ($pfx_rss) {
		$pfx_max   = $pfx_posts_per_page;
		$pfx_data  = safe_rows('*', 'pfx_dynamic_posts', "public = 'yes' and page_id = '{$pfx_page_id}' and posted < utc_timestamp() order by posted desc");
		$pfx_total = count($pfx_data);
		if ($pfx_total) {
			if ($pfx_total < $pfx_max) {
				$pfx_max = $pfx_total;
			}
			$pfx_i = 0;
			while ($pfx_i < $pfx_max) {
				$pfx_out      = $pfx_data[$pfx_i];
				$pfx_title    = $pfx_out['title'];
				$pfx_content  = $pfx_out['content'];
				$pfx_posted   = $pfx_out['posted'];
				$pfx_author   = $pfx_out['author'];
				$pfx_tags     = $pfx_out['tags'];
				$pfx_timeunix = returnUnixtimestamp($pfx_posted);
				$pfx_date     = date('r', $pfx_timeunix);
				$pfx_slug     = $pfx_out['post_slug'];
				if (PREFS_CLEAN_URLS == 'yes') {
					$pfx_urllink = createURL($pfx_page_name, 'permalink', $pfx_slug);
				} else {
					$pfx_urllink = createURL($pfx_page_name, 'permalink', "{$pfx_slug}&referrer=rss");
				}
				echo "
		<item>
			<title>{$pfx_title}</title>
			<link>{$pfx_urllink}</link>
			<comments>" . createURL($pfx_page_name, 'permalink', "{$pfx_slug}#comments") . "</comments>
			<pubDate>{$pfx_date}</pubDate>
			<dc:creator>{$pfx_author}</dc:creator>\n\t\t\t";
				if ((isset($pfx_tags)) && ($pfx_tags)) {
					$pfx_tag_list        = "";
					$pfx_all_tags        = strip_tags($pfx_tags);
					$pfx_tags_array_temp = explode(" ", $pfx_all_tags);
					$pfx_total_tag       = count($pfx_tags_array_temp);
					$pfx_j               = 0;
					while ($pfx_j < $pfx_total_tag) {
						if ($pfx_tags_array_temp[$pfx_j] != "") {
							echo '<category>' . str_replace(" ", "", $pfx_tags_array_temp[$pfx_j]) . '</category>';
						}
						$pfx_j++;
					}
					for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
						$pfx_current = $pfx_tags_array_temp[$pfx_count];
						$pfx_first   = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
						if ($pfx_first == " ") {
							$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
						}
						$pfx_ncurrent = make_slug($pfx_current);
						if (isset($pfx_s)) {
							$pfx_tag_list .= "<a href=\"" . createURL($pfx_s, 'tag', $pfx_ncurrent) . "\" title=\"View all posts in {$pfx_current}\">{$pfx_current}</a>, ";
						}
					}
					$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
				}
				echo "
			<guid isPermaLink=\"true\">{$pfx_urllink}</guid>
			<description><![CDATA[\n";
				$pfx_post = get_extended($pfx_content);
				echo "\t\t\t{$pfx_post['main']}\n";
				if ($pfx_post['extended']) {
					echo "\t\t\t{$pfx_post['extended']}\n";
				}
				if ($pfx_tag_list) {
					echo "\t\t\t<p>Tagged: {$pfx_tag_list}</p>\n";
				}
				echo "\t\t\t]]></description>
			<wfw:commentRss>http://transformr.co.uk/hatom/" . createURL($pfx_page_name, 'permalink', $pfx_slug) . "</wfw:commentRss>
		</item>";
				$pfx_i++;
			}
		}
	}
	echo "\n\t</channel>\n</rss>";
}
// ------------------------------------------------------------------
// check if the current page has rss
function public_check_rss($pfx_page_name) {
	$pfx_core_page_name = safe_row('*', 'pfx_core', "page_name='{$pfx_page_name}' limit 0,1");
	extract($pfx_core_page_name, EXTR_PREFIX_ALL, 'pfx');
	$pfx_core_page_name = NULL;
	$pfx_dynamic_page_id = safe_row('*', 'pfx_dynamic_settings', "page_id='{$pfx_page_id}' limit 0,1");
	extract($pfx_dynamic_page_id, EXTR_PREFIX_ALL, 'pfx');
	$pfx_dynamic_page_id = NULL;
	if ($pfx_rss == 'yes') {
		return TRUE;
	} else {
		return FALSE;
	}
}
// ------------------------------------------------------------------
// build admin RSS feed
function adminrss($pfx_user, $pfx_nonce, $pfx_lang, $pfx_s = FALSE) {
	if (safe_field('nonce', 'pfx_users', "nonce='{$pfx_nonce}'")) {
		header('Content-type: text/xml'); // Note : header should ALWAYS go at the top of a document. See php header(); in the php manual.
		echo ('<?xml version="1.0" encoding="' . PFX_CHARSET . '"?>');
?>
	
<rss version="2.0" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/">

		<channel>
			<title><?php
		echo PREFS_SITE_NAME . " - {$pfx_lang['latest_activity']} ({$pfx_lang['rss_feed']})";
?></title>
			<description><?php
		echo PREFS_SITE_NAME . " - {$pfx_lang['latest_activity']}";
?></description>
			<link><?php
		echo PREFS_SITE_URL . "/admin/?s=myaccount&amp;do=rss&amp;user={$pfx_user}&amp;referrer=rss";
?></link>
			<generator>PFX installed @ http://<?php
		echo PREFS_SITE_URL;
?></generator>
			<language>en</language>
			<image>
				<url><?php
		echo PREFS_SITE_URL;
?>files/images/rss_feed_icon.png</url>
				<link><?php
		echo PREFS_SITE_URL . "admin/?s=myaccount&amp;do=rss&amp;user={$pfx_user}&amp;referrer=rss";
?></link>
				<title><?php
		echo PREFS_SITE_NAME;
?></title>
			</image>
<?php
		$pfx_max   = 60;
		$pfx_data  = safe_rows('*', 'pfx_log', "log_type = 'system' order by log_time desc");
		$pfx_total = count($pfx_data);
		if ($pfx_total) {
			if ($pfx_total < $pfx_max) {
				$pfx_max = $pfx_total;
			}
		}
		$pfx_i = 0;
		while ($pfx_i < $pfx_max) {
			$pfx_out     = $pfx_data[$pfx_i];
			$pfx_title   = $pfx_out['log_message'];
			$pfx_link    = PREFS_SITE_URL;
			$pfx_author  = $pfx_out['user_id'];
			$pfx_time    = $pfx_out['log_time'];
			$pfx_logunix = returnUnixtimestamp($pfx_time);
			$pfx_time    = date('r', $pfx_logunix);
			$pfx_site    = str_replace('http://', "", PREFS_SITE_URL);
			echo "  		
		<item>
			<title>(" . PREFS_SITE_NAME . ") - {$pfx_author}: {$pfx_title}</title>
			<link>{$pfx_link}?referrer=rss</link>
			<author>{$pfx_author}</author>
			<pubdate>{$pfx_time}</pubdate>
		</item>";
			$pfx_i++;
		}
		echo "\n\t</channel>\n</rss>";
	} else {
		// the user has attempted to access the RSS feed with an invalid nonce
		logme($pfx_lang['rss_access_attempt'], 'yes', 'error');
		echo $pfx_lang['rss_access_attempt'];
	}
}