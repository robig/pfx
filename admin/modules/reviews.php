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
 * Title: Reviews module
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Tony White
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
switch ($pfx_do) {
	/* General information */
	case 'info' :
		$pfx_m_name        = ucfirst($pfx_m_n);
		$pfx_m_description = ucfirst($pfx_m_n) . ' of anything you like (Wrapped in the hReview microformat.) Also supports tagging.';
		$pfx_m_author      = 'Scott Evans';
		$pfx_m_url         = 'http://www.toggle.uk.com/';
		$pfx_m_version     = '1.2';
		$pfx_m_type        = 'module';
		$pfx_m_publish     = 'yes';
		$pfx_m_in_navigation     = 'yes';
		break;
	/* Install */
	case 'install' :
		/* Create any required tables */
		$pfx_execute = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,`date` timestamp NOT NULL default '0000-00-00 00:00:00',`title` varchar(235) collate " . PFX_DB_COLLATE . " NOT NULL,`company` varchar(200) collate " . PFX_DB_COLLATE . " default NULL,`image` varchar(5) collate " . PFX_DB_COLLATE . " NOT NULL,`{$pfx_m_n}_content` longtext collate " . PFX_DB_COLLATE . " NOT NULL,`tags` varchar(255) collate " . PFX_DB_COLLATE . " NOT NULL,`author` varchar(150) collate " . PFX_DB_COLLATE . " NOT NULL,`updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,`{$pfx_m_n}_views` int(11) NULL default '1',`post_slug` varchar(255) collate " . PFX_DB_COLLATE . " NOT NULL,PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=1 ;";
		break;
	/* Pre (To be run before page load) */
	case 'pre' :
		switch ($pfx_m) {
			case 'tag' :
				$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
				$pfx_ptitle     = $pfx_site_title . ' - ' . ucfirst($pfx_m_n) . ' -  Tagged - ' . ucwords($pfx_x);
				break;
			case 'permalink' :
				$pfx_title = safe_field('title', "pfx_module_{$pfx_m_n}", "post_slug='{$pfx_x}'");
				if ($pfx_title) {
					$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
					$pfx_ptitle     = $pfx_site_title . ' - ' . substr( ucfirst($pfx_m_n), 0, -1 ) . ' - ' . $pfx_title;
				} else {
					sterilise_url($pfx_s);
					$pfx_redirect = createURL($pfx_s);
					exit(header("Location: {$pfx_redirect}"));
				}
				break;
			default :
				$pfx_site_title = safe_field('site_name', 'pfx_settings', "settings_id = '1'");
				if ($pfx_m == 'most-popular') {
					$pfx_ptitle = "{$pfx_site_title} - " . ucfirst($pfx_m_n) . " - Popular";
				} else if ($pfx_m == 'recent') {
					$pfx_ptitle = "{$pfx_site_title} - " . ucfirst($pfx_m_n) . " - Most recent";
				} else {
					$pfx_ptitle = "{$pfx_site_title} - " . ucfirst($pfx_m_n);
				}
				break;
		}
		break;
	/* Head (To be run in the head) */
	case 'head' :
		break;
	/* Admin of module */
	case 'admin' :
		$pfx_module_name  = ucfirst($pfx_m_n);
		$pfx_table_name   = "pfx_module_{$pfx_m_n}";
		$pfx_order_by     = 'title';
		$pfx_asc_desc     = 'asc';
		$pfx_view_exclude = array(
			"{$pfx_m_n}_id",
			'image',
			"{$pfx_m_n}_content",
			'tags',
			'updated',
			'company',
			'post_slug',
			'author',
			"{$pfx_m_n}_views"
		);
		$pfx_edit_exclude = array(
			"{$pfx_m_n}_id",
			'updated',
			'post_slug',
			'author',
			"{$pfx_m_n}_views"
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 15;
		$pfx_tags         = 'yes';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/* Show module */
	default :
		switch ($pfx_m) {
			case 'permalink':
				$pfx_rs = safe_row('*', "pfx_module_{$pfx_m_n}", "post_slug = '{$pfx_x}' limit 0,1");
				if ( (isset($pfx_rs)) && ($pfx_rs) ) {
					extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
					$pfx_rs = NULL;
					safe_update("pfx_module_{$pfx_m_n}", "{$pfx_m_n}_views  = {$pfx_reviews_views} + 1", "{$pfx_m_n}_id = '{$pfx_reviews_id}'");
					$pfx_image       = safe_field('file_name', 'pfx_files', "file_id = '{$pfx_image}'");
					$pfx_logunix     = returnUnixtimestamp($pfx_date);
					$pfx_microformat = safe_strftime($pfx_lang, '%Y-%m-%dT%T%z', $pfx_logunix);
					$pfx_date        = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
					if ($pfx_tags) {
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
							$pfx_tag_list .= '<a href="' . PREFS_SITE_URL . "{$pfx_s}/tag/{$pfx_ncurrent}/\" title=\"View portfolio items tagged {$pfx_current}\" rel=\"tag\">{$pfx_current}</a>, ";
						}
						$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
					}
					echo "<div class=\"hreview single_review\">
						      <h3>{$pfx_title} " . substr($pfx_m_n, 0, -1 ) . "
						      </h3>
							<img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" class=\"photo\" />
							
							<div class=\"item\">
								<ul class=\"{$pfx_m_n}_credits\">";
					if ($pfx_company != "") {
						echo "<li class=\"company\"><p class=\"reviews-company\"><strong>Company:</strong>{$pfx_company}</p></li>";
					}
									echo "<li class=\"{$pfx_m_n}_date\"><strong>Date:</strong> <abbr class=\"dtreviewed\" title=\"{$pfx_microformat}\">{$pfx_date}</abbr></li>";
					echo "<li class=\"reviewer vcard\"><strong>By:</strong> <a class=\"fn url\" href=\"" . PREFS_SITE_URL . "\" title=\"{$pfx_author}\">{$pfx_author}</a></li>
					      <li class=\"{$pfx_m_n}_tags\"><strong>Tags:</strong> {$pfx_tag_list}</li>
								</ul>
							</div>
							
							<div class=\"{$pfx_m_n}_body\">
								<div class=\"description\"><p class=\"reviews-content\">{$pfx_reviews_content}</p>
								</div>
							</div>
						</div><br /><p class=\"back\">Back to <a href=\"" . createURL($pfx_s) . "\" title=\"Back to full list of {$pfx_m_n}\">full list of {$pfx_m_n} &raquo;</a></p>";
				}
				break;
			default :
				switch ($pfx_m) {
					case 'tag':
						$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= 1 and tags REGEXP '[[:<:]]{$pfx_x}[[:>:]]' order by title asc");
						break;
					case 'most-popular':
						$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= 1 order by {$pfx_m_n}_views desc");
						break;
					case 'recent':
						$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= 1 order by date desc");
						break;
					default:
						$pfx_rs = safe_rows_start('*', "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id >= 1 order by title asc");
						break;
				}
				$pfx_i = 0;
				echo "<h3>{$pfx_page_display_name}";
				if ($pfx_m == 'tag') {
					echo " (Tag: {$pfx_x})";
				}
				echo "</h3>\n";
				if ( (isset($pfx_rs)) && ($pfx_rs) ) {
					while ($pfx_a = nextRow($pfx_rs)) {
						extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
						$pfx_a = NULL;
						if (!is_even($pfx_i)) {
							$pfx_class = 'even';
						} else {
							$pfx_class = 'odd';
						}
						$pfx_image       = safe_field('file_name', 'pfx_files', "file_id = '{$pfx_image}'");
						$pfx_post        = get_extended($pfx_reviews_content);
						$pfx_logunix     = returnUnixtimestamp($pfx_date);
						$pfx_microformat = safe_strftime($pfx_lang, '%Y-%m-%dT%T%z', $pfx_logunix);
						$pfx_date        = safe_strftime($pfx_lang, PREFS_DATE_FORMAT, $pfx_logunix);
						if ($pfx_tags) {
							$pfx_all_tags        = strip_tags($pfx_tags);
							$pfx_all_tags        = str_replace('&quot;', "", $pfx_tags);
							$pfx_tags_array_temp = explode(' ', $pfx_all_tags);
							for ($pfx_count = 0; $pfx_count < (count($pfx_tags_array_temp)); $pfx_count++) {
								$pfx_current = $pfx_tags_array_temp[$pfx_count];
								$pfx_first   = $pfx_current{strlen($pfx_current) - strlen($pfx_current)};
								if ($pfx_first == ' ') {
									$pfx_current = substr($pfx_current, 1, strlen($pfx_current) - 1);
								}
								$pfx_ncurrent = make_slug($pfx_current);
								$pfx_tag_list .= '<a href="' . PREFS_SITE_URL . "{$pfx_s}/tag/{$pfx_ncurrent}/\" title=\"View portfolio items tagged {$pfx_current}\" rel=\"tag\">{$pfx_current}</a>, ";
							}
							$pfx_tag_list = substr($pfx_tag_list, 0, (strlen($pfx_tag_list) - 2)) . "";
						}
						echo "<div class=\"hreview-{$pfx_m_n}-{$pfx_class}\">
							<a href=\"" . createURL($pfx_s, 'permalink', $pfx_post_slug) . "\" title=\"Read the full review\">
								<img src=\"" . PREFS_SITE_URL . "files/images/{$pfx_image}\" alt=\"{$pfx_title}\" class=\"photo\" />
							</a>
							<div class=\"reviews-body\">
								<div class=\"item\">
									<h4 class=\"fn\"><p class=\"reviews-title\"><a href=\"" . createURL($pfx_s, 'permalink', $pfx_post_slug) . "\" class=\"url\" title=\"Read the full review\">{$pfx_title}</a></p></h4>
									<ul class=\"reviews-credits\">";
						if ($pfx_company != "") {
							echo "<li class=\"company\"><p class=\"reviews-company\"><strong>Company : </strong>{$pfx_company}</p></li>";
						}
						echo "<li class=\"reviews-date\"><strong>Date :</strong> <abbr class=\"dtreviewed\" title=\"{$pfx_microformat}\">{$pfx_date}</abbr></li>
						      <li class=\"reviewer vcard\"><strong>By :</strong> <a class=\"fn url\" href=\"" . PREFS_SITE_URL . "\" title=\"{$pfx_author}\">{$pfx_author}</a></li>
						      <li class=\"reviews-tags\"><strong>Tags :</strong> {$pfx_tag_list}</li>";
						if ($post['extended']) {
							echo "<p class=\"reviews-more\"><a href=\"" . createURL($pfx_s, 'permalink', $pfx_post_slug) . "\" title=\"Read the full review\">Read full review...</a></p>";
						} else {
							echo "<li class=\"reviews-more\"><a href=\"" . createURL($pfx_s, 'permalink', $pfx_post_slug) . "\" title=\"Permalink\">Permalink</a></li>";
						}
						echo "</ul></div>
								<div class=\"summary\"><p class=\"reviews-content\">{$pfx_reviews_content}</p>";
						echo "</div>
							</div>
						</div>
						<div class=\"reviews-divide clear\"></div>";
						$pfx_i++;
						echo "<div class=\"reviews-footer\"><br /><br /><p class=\"underline\">Sort by <a href=\"" . createURL($pfx_s, 'most-popular') . "\" title=\"Show me the most popular\">most popular</a>, <a href=\"" . createURL($pfx_s, 'recent') . "\" title=\"Show me the most recent {$pfx_m_n}\">by date</a> or <a href=\"" . createURL($pfx_s) . "\" title=\"Show me the {$pfx_m_n} listed alphabetically (default)\">alphabetically</a>.</p></div>";
					}
				}
				break;
		}
		break;
}