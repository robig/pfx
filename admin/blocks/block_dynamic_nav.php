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
 * Title: Dynamic Sub Navigation Block
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
?>
	    <li>
		<a href="<?php echo createURL($pfx_page_name, 'archives'); ?>" title="<?php echo "{$pfx_page_display_name}: {$pfx_lang['archives']}"; ?>"<?php if ( (isset($pfx_m)) && ($pfx_m == 'archives') ) { echo " class=\"sub_nav_current_1 replace\""; } else { echo " class=\"replace\""; } ?>><?php echo $pfx_lang['archives']; ?>
		    <span>
		    </span>
		</a>
	    </li>
	    <li>
		<a href="<?php echo createURL($pfx_page_name, 'popular'); ?>" title="<?php echo "{$pfx_page_display_name}: {$pfx_lang['popular_posts']}"; ?>"<?php if ( (isset($pfx_m)) && ($pfx_m == 'popular') ) { echo " class=\"sub_nav_current_1 replace\""; } else { echo " class=\"replace\""; } ?>><?php echo $pfx_lang['popular_posts']; ?>
		    <span>
		    </span>
		</a>
	    </li>
	    <li>
		<a href="<?php echo createURL($pfx_page_name, 'tags'); ?>" title="<?php echo "{$pfx_page_display_name}: {$pfx_lang['tags']}"; ?>"<?php if ( (isset($pfx_m)) && ($pfx_m == 'tags') ) { echo " class=\"sub_nav_current_1 replace\""; } else { echo " class=\"replace\""; } ?>><?php echo $pfx_lang['tags']; ?>
		    <span>
		    </span>
		</a>
	    </li>