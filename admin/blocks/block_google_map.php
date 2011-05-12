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
 * Title: Google maps Block
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
$pfx_google_api_key = 'ADD YOUR GOOGLE MAPS API KEY HERE';
$pfx_latitude = 37.4419;
$pfx_longitude = -122.1419;
$pfx_map_width = '200px';
$pfx_map_height = '200px';
?>
	<div id="block_google_map" class="block">
		<div class="block_header">
			<h4>Google Map</h4>
		</div>
		<div class="block_body">
			<div id="map_canvas" style="width: <?php echo $pfx_map_width; ?>; height: <?php echo $pfx_map_height; ?>"></div>
		</div>
		<div class="block_footer">
			<?php if ($pfx_google_api_key !== 'ADD YOUR GOOGLE MAPS API KEY HERE') { ?>
				<script type="text/javascript" src="http://www.google.com/jsapi?key=<?php echo $pfx_google_api_key; ?>"></script>
			<?php } else { ?>
				<script type="text/javascript" src="http://www.google.com/jsapi"></script>
			<?php } ?>
			<script type="text/javascript">
				//<![CDATA[
				google.load('maps', '2', {'callback' : mapsLoaded});

				function mapsLoaded() {
					if (GBrowserIsCompatible()) {
						var map = new GMap2(document.getElementById('map_canvas'));
						map.setCenter(new GLatLng(<?php echo $pfx_latitude; ?>, <?php echo $pfx_longitude; ?>), 13);
					}
				}
				//]]>
			</script>
		</div>
	</div>