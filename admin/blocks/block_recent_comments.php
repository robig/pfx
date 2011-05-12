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
 * Title: Recent comments Block
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
$pfx_post_limit = 5; /* Adjust the limit here to set a different number of posts */
?>
	<div id="block_comments" class="block">
		<div class="block_header">
			<h4>Recent Comments</h4>
		</div>
		<div class="block_body">
			<ul>
				<?php
				$pfx_r1 = safe_rows('*', 'pfx_module_comments', "comments_id >= 1 order by posted desc limit {$pfx_post_limit}");
				$pfx_total = count($pfx_r1);
					if ($pfx_r1) {
						$pfx_i = 0;
							while ($pfx_i < $pfx_total){
								extract($pfx_r1[$pfx_i], EXTR_PREFIX_ALL, 'pfx');
								$pfx_r1 = NULL;
								$pfx_linkto = safe_field('post_slug', 'pfx_dynamic_posts', "post_id = '{$pfx_post_id}'");
								$pfx_logunix = returnUnixtimestamp($pfx_posted);
								$pfx_days_ago = safe_strftime($pfx_lang, 'since', $pfx_logunix);
								$pfx_page_id = safe_field('page_id', 'pfx_dynamic_posts', "post_id = '{$pfx_post_id}'");
								$pfx_page = safe_field('page_name', 'pfx_core', "page_id = '{$pfx_page_id}'");
								echo "\t\t\t\t\t\t\t<li><a href=\"" . createURL($pfx_page, 'permalink', $pfx_linkto) . "#comments\">" . strip_tags( chopme($pfx_comment, 70) ) . "</a><br/><span class=\"recent_author\">by {$pfx_name}</span> <span class=\"recent_time\">{$pfx_days_ago}</span></li>\n";
								$pfx_i++;
							}
					}
				?>
			</ul>
		</div>
		<div class="block_footer">
		</div>
	</div>