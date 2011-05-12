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
 * Title: Quotations Module
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Tony White
 * @author Robert Kummer -  http://robert-kummer.de/ - rok@ipunkt.biz - ipunkt.biz - mit stil im internet praesent sein
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */

switch ($pfx_do) {

	case 'info' :
		$pfx_m_name        = ucfirst($pfx_m_n);
		$pfx_m_description = "Favorite {$pfx_m_n}.";
		$pfx_m_author      = 'Robert Kummer';
		$pfx_m_url         = 'http://robert-kummer.de';
		$pfx_m_version     = '1.2';
		$pfx_m_type        = 'module';
		$pfx_m_publish     = 'yes';
		$pfx_m_in_navigation     = 'yes';

		break;

	case 'install' :	/* Create any required tables */
		$pfx_execute = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (
				`{$pfx_m_n}_id` int(10) unsigned NOT NULL auto_increment COMMENT 'primary key',
				`{$pfx_m_n}` longtext collate " . PFX_DB_COLLATE . " NOT NULL COMMENT '{$pfx_m_n}',
				`cite` varchar(255) collate " . PFX_DB_COLLATE . " default NULL COMMENT 'cite source',
				`url` varchar(255) collate " . PFX_DB_COLLATE . " default NULL COMMENT 'cite url',
				`thumbs_up` int(10) unsigned default '0' COMMENT 'thumbs up voting',
				`thumbs_down` int(10) unsigned default '0' COMMENT 'thumbs down voting',
				PRIMARY KEY  (`{$pfx_m_n}_id`) ) ENGINE=MyISAM DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " COMMENT='{$pfx_m_n} module table' AUTO_INCREMENT=1;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`enable_votes` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'no',`log_votes` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`use_{$pfx_m_n}_css_file` set('yes','no') collate " . PFX_DB_COLLATE . " NOT NULL default 'yes',`top_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`lower_description` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`last_vote_id` int(10) collate " . PFX_DB_COLLATE . " NOT NULL default '0',`last_ip` varchar(15) collate " . PFX_DB_COLLATE . " NOT NULL default '0.0.0.0',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute2 = "INSERT INTO `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id`, `{$pfx_m_n}`, `cite`, `thumbs_up`, `thumbs_down`) VALUES
				(5, 'And that was without a single drop of rum.', 'Captain Jack Sparrow', 0, 0),
				(6, 'Mmm... donuts.', 'Homer J. Simpson', 0, 0),
				(7, 'Mamma always said; life is like a box of chocolates. You never know what you''re gonna get.', 'Forest Gump', 0, 0);";
		$pfx_execute3 = "INSERT INTO `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id`) VALUES (1);";

		break;

	case 'admin' :
		$pfx_module_name    = ucfirst($pfx_m_n);
		$pfx_table_name     = "pfx_module_{$pfx_m_n}";
		$pfx_order_by       = "{$pfx_m_n}_id";
		$pfx_asc_desc       = 'desc';
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id"
		);
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id",
			'thumbs_up',
			'thumbs_down'
		);
		$pfx_items_per_page = 25;
		$pfx_tags           = 'no';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;

		break;

	case 'pre' :
		$pfx_quotes_lang = array(
				  'vote_hint' => 'Vote :',
				  'vote_score' => 'Score :',
				  'vote_success' => 'Thanks for voting!',
				  'vote_casted_up' => 'An up',
				  'vote_casted_down' => 'A down',
				  'vote_casted' => 'vote was cast on :'
				);
		$pfx_enable_votes = fetch('enable_votes', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		if ( (isset($pfx_m)) or (isset($pfx_x)) ) {
			if ($pfx_enable_votes == 'yes') {
				$pfx_quotes_field = sterilise_txt($pfx_m);
				$pfx_quotes_id = sterilise_txt($pfx_x);
				$pfx_table_field   = NULL;
				if ($pfx_quotes_field == 'up') {
					$pfx_table_field = 'thumbs_up';
				} else if ($pfx_quotes_field == 'down') {
					$pfx_table_field = 'thumbs_down';
				}
				if ( (isset($pfx_table_field)) && ($pfx_table_field !== NULL) ) {
					if (isset($_SERVER['REMOTE_ADDR'])) {
						$pfx_push_update = 'yes';
					} else {
						$pfx_push_update = 'no';
					}
					$pfx_log_votes = fetch('log_votes', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
					if ( ($pfx_log_votes == 'yes') && (isset($_SERVER['REMOTE_ADDR'])) ) {
						$pfx_last_cast_vote = getThing($pfx_query = 'SELECT MAX(log_id) FROM ' . CONFIG_TABLE_PREFIX . "pfx_log WHERE `log_type` = 'system' AND `log_icon` = 'comment' AND `user_ip` = '{$_SERVER['REMOTE_ADDR']}'");
						$pfx_last_cast = fetch('log_message', 'pfx_log', 'log_id', $pfx_last_cast_vote);
						$pfx_quotes_vote = fetch("{$pfx_m_n}", "pfx_module_{$pfx_m_n}", "{$pfx_m_n}_id", $pfx_quotes_id);
						$pfx_match_last = strpos($pfx_last_cast, $pfx_quotes_vote);
						if ( (isset($pfx_match_last)) && ($pfx_match_last) ) {
							safe_update("pfx_module_{$pfx_m_n}_settings", "`last_ip` = '0.0.0.0'", "{$pfx_m_n}_id = 1");
							safe_update("pfx_module_{$pfx_m_n}_settings", "`last_vote_id` = 0", "{$pfx_m_n}_id = 1");
							$pfx_redirect = createURL($pfx_m_n);
							exit( header("Location: {$pfx_redirect}") );
						}
					}
					if ($pfx_push_update == 'yes') {
						if ( safe_update("pfx_module_{$pfx_m_n}", "{$pfx_table_field} = {$pfx_table_field}+1", "`{$pfx_m_n}_id` = '{$pfx_quotes_id}'") ) {
							if ($pfx_log_votes == 'yes') {
								$pfx_vote_message = "{$pfx_quotes_lang['vote_casted']} <a href=\"" . createURL($pfx_s) . "\">" . mysql_real_escape_string($pfx_quotes_vote) . "</a>.";
								if ($pfx_table_field == 'thumbs_up') {
									logme("{$pfx_quotes_lang['vote_casted_up']} {$pfx_vote_message}", 'no', 'comment');
								} else {
									logme("{$pfx_quotes_lang['vote_casted_down']} {$pfx_vote_message}", 'no', 'comment');
								}
							}
							safe_update("pfx_module_{$pfx_m_n}_settings", "`last_vote_id` = '{$pfx_quotes_id}'", "{$pfx_m_n}_id = 1");
							if (isset($_SERVER['REMOTE_ADDR'])) {
								$pfx_refer = $_SERVER['REMOTE_ADDR'];
							}
							safe_update("pfx_module_{$pfx_m_n}_settings", "`last_ip` = '{$pfx_refer}'", "{$pfx_m_n}_id = 1");
							$pfx_redirect = createURL($pfx_m_n);
							exit( header("Location: {$pfx_redirect}") );
						}
					}
				}
			}
		}

		break;

	case 'head' :
		    $pfx_quotes_css = fetch("use_{$pfx_m_n}_css_file", "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		    if ($pfx_quotes_css === 'yes') {
			    echo "<link rel=\"stylesheet\" href=\"{$pfx_rel_path}admin/modules/css/{$pfx_m_n}.css\" type=\"text/css\" media=\"screen\" />";
		    }

		break;

	default :
		$pfx_q_top_descr_result = fetch('top_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_q_lower_descr_result = fetch('lower_description', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
		$pfx_rs = safe_rows_start('* , FORMAT((`thumbs_up`/(`thumbs_up`+`thumbs_down`))*10, 0) AS rate', "pfx_module_{$pfx_m_n}", "`{$pfx_m_n}_id` > 0 ORDER BY `{$pfx_m_n}_id` DESC;");
		echo "<div id=\"{$pfx_s}\"><h3>{$pfx_page_display_name}</h3>";
		if ( (isset($pfx_q_top_descr_result)) && ($pfx_q_top_descr_result) ) {
			echo "<div id=\"{$pfx_m_n}-top-descr\">{$pfx_q_top_descr_result}</div>\n";
		}
		echo "<div id=\"{$pfx_m_n}-view\">";
		if ($pfx_enable_votes == 'yes') {
			$pfx_last_vote_id = fetch('last_vote_id', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
			$pfx_last_ip = fetch('last_ip', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1);
			if ( ($pfx_last_vote_id !== 0) && ($pfx_last_ip !== '0.0.0.0') && ($pfx_last_ip !== 'unknown') ) {
				echo "<div class=\"success\">{$pfx_quotes_lang['vote_success']}</div>";
			}
		}
		if ($pfx_rs) {
			$pfx_i = 0;
			while ($pfx_a = nextRow($pfx_rs)) {
				$pfx_i++;
				extract($pfx_a, EXTR_PREFIX_ALL, 'pfx');
				$pfx_a = NULL;
				echo '<div class="quote"><div class="q1"><div class="q2"><div class="q3"><div class="q4"><div class="q5"><div class="q6"><div class="q7"><div class="q8"><div class="quote-block"><div class="quote-wrapper"><blockquote><div class="l-quote"></div>';
				echo "{$pfx_quotes}";
				echo '<div class="r-quote"></div></blockquote>';
				if (!empty($pfx_cite)) {
					if ( (isset($pfx_url)) && ($pfx_url) ) {
						echo "<p><cite class=\"citation\"> - <a href=\"{$pfx_url}\" rel=\"nofollow\" target=\"_blank\">{$pfx_cite}</a></cite></p>";
					} else if (substr($pfx_cite, 0, 4) === 'http') {
						echo "<p><cite class=\"citation\"> - <a href=\"{$pfx_cite}\" rel=\"nofollow\" target=\"_blank\">{$pfx_cite}</a></cite></p>";
					} else {
						echo "<p><cite class=\"citation\"> - {$pfx_cite}</cite></p>";
					}
				} else {
					echo '<p><cite class=\"citation\"> - Unknown</cite></p>';
				}
				if ($pfx_enable_votes == 'yes') {
					echo "<div class=\"quote-ratings\"><p><b>";
					if ( ($pfx_last_vote_id == 0) && (isset($_SERVER['REMOTE_ADDR'])) ) { /* Prevent users who hide their remote address from voting. They are either bots, spammers or are untrusted */
						echo "{$pfx_quotes_lang['vote_hint']} " . '<span class="left-a"><a rel="nofollow" class="ajax arrow" href="' . createURL($pfx_m_n, 'up', $pfx_quotes_id) . '">&uarr;</a> </span>';
					} else {
						echo "{$pfx_quotes_lang['vote_score']} ";
					}
					echo '<span class="rating">' . sprintf('%s | %s', ($pfx_rate + 0), 10) . '</span>';
					if ( ($pfx_last_vote_id == 0) && (isset($_SERVER['REMOTE_ADDR'])) ) { /* Prevent users who hide their remote address from voting. They are either bots, spammers or are untrusted */
						echo '<span class="right-a"> <a rel="nofollow" class="ajax arrow" href="' . createURL($pfx_m_n, 'down', $pfx_quotes_id) . '">&darr;</a></span>';
					}
					echo '</b></p></div>';
				}
				echo '</div></div></div></div></div></div></div></div></div></div></div>';
			}
		}
		echo '</div>';
		if ( (isset($pfx_q_lower_descr_result)) && ($pfx_q_lower_descr_result) ) {
			echo "<div id=\"{$pfx_m_n}-lower-descr\">{$pfx_q_lower_descr_result}</div>";
		}
		echo '</div>';
		safe_update("pfx_module_{$pfx_m_n}_settings", "`last_ip` = '0.0.0.0'", "{$pfx_m_n}_id = 1");
		safe_update("pfx_module_{$pfx_m_n}_settings", "`last_vote_id` = 0", "{$pfx_m_n}_id = 1");

		break;
}