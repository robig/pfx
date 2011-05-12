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
 * Title: del.icio.us Block
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author Craig Smith
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
/* Your delicious id */
$pfx_delicious_id = 'elev3n';
?>
	<div id="block_delicious" class="block">
		<div class="block_header">
			<h4>del.icio.us</h4>
		</div>
		<div class="block_body">
			<script type="text/javascript" src="http://feeds.delicious.com/v2/js/networkbadge/<?php echo $pfx_delicious_id;?>?showadd&amp;icon=m&amp;name&amp;itemcount&amp;nwcount&amp;fancount" charset="<?php echo PFX_CHARSET; ?>"></script>
		</div>
		<div class="block_footer">
		</div>
	</div>