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
 * Title: Google Analytics Block
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author Jed Brubaker - www.whatknows.com
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 * INSTALLATION:
 * 
 * You can use this block in two different ways:
 * 
 * Cut and past the entire Google Analytics tracking block between 
 * the <!-- COMMENT TAGS --> below. Be sure to delete the code that is there!
 * 
 * OR
 * 
 * change $ga_code below to your unique tracking code (e.g., UA-xxxxxxx-x)
 * 
 * NEXT: Save, and upload into the "admin/blocks/" folder
 * 
 * You're done! Did you think it was harder than that?  
 * 
 */
$pfx_ga_code = 'UA-xxxxxxx-x';
?>
	<div id="block_google_analytics" class="block" style="display: none;">

		<!-- PASTE GOOGLE ANALYTICS TRACKING CODE HERE -->

		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		var pageTracker = _gat._getTracker("<?php echo $pfx_ga_code; ?>");
		pageTracker._trackPageview();
		</script>

		<!-- END TRACKING CODE BLOCK -->

	</div>