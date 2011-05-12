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
 * Title: Random quote Block
 *
 * @package PFX
 * @copyright 2008-2010 Scott Evans
 * @copyright 2010 Tony White
 * @author Scott Evans
 * @author Sam Collett
 * @author Tony White
 * @author Isa Worcs
 * @author tek - http://www.enflicted.net/
 * @link http://heydojo.co.cc
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3
 *
 */
/* Add as many quotes as you like below. */
$quotes[] = 'This is a quote';
$quotes[] = 'This is another';
$quotes[] = 'quote 3';
$quotes[] = 'quote 4';
$quotes[] = 'quote 5';
$quotes[] = 'quote 6';
srand( (double) microtime() * 1000000 );
$randomquote = rand(0, count($quotes) - 1);
?>
	<div id="block_quote" class="block">
		<div class="block_header">
			<h4>Random Quote</h4>
		</div>
		<div class="block_body">
			<?php echo "\t<p>{$quotes[$randomquote]}</p>\n"; ?>
		</div>
		<div class="block_footer">
		</div>
	</div>