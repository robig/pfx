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
 * Title: Terms & Conditions Module
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
$pfx_m_n = sterilise_txt( basename(__FILE__, '.php') ); /* The name of this file */
/* The module is loaded into PFX in many different instances, the variable */
/* $pfx_do is used to run the module in different ways. */
switch ($pfx_do) {
	/* General information : */
	/* The general information is used to show information about the module within PFX. */
	/* Simply enter details of your module here : */
	case 'info' :
		/* The name of your module */
		$pfx_m_name          = ucfirst($pfx_m_n);
		/* A description of your module */
		$pfx_m_description   = 'Terms & conditions of service.';
		/* Who is the module author? */
		$pfx_m_author        = 'Tony White';
		/* What is the URL of your homepage */
		$pfx_m_url           = 'http://heydojo.co.cc/';
		/* What version is this? */
		$pfx_m_version       = 1.0;
		/* Can be set to module or plugin. */
		$pfx_m_type          = 'module';
		/* Is this a module that needs publishing to? */
		$pfx_m_publish       = 'no';
		/* Put this module in the navigation by default? */
		$pfx_m_in_navigation = 'no';
		break;
	/* Install */
	/* This section contains the SQL needed to create your modules tables */
	case 'install' :
		/* Create any required tables */
		$pfx_execute  = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}` (`{$pfx_m_n}_id` int(4) NOT NULL auto_increment,PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=0 ;";
		$pfx_execute1 = "CREATE TABLE IF NOT EXISTS `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id` mediumint(1) NOT NULL auto_increment,`terms` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`privacy` LONGTEXT collate " . PFX_DB_COLLATE . " default '',`disclaimer` LONGTEXT collate " . PFX_DB_COLLATE . " default '',PRIMARY KEY  (`{$pfx_m_n}_id`)) ENGINE=MyISAM  DEFAULT CHARSET=" . PFX_DB_CHARSET . " COLLATE=" . PFX_DB_COLLATE . " AUTO_INCREMENT=2 ;";
		$pfx_execute2 = "INSERT INTO `pfx_module_{$pfx_m_n}_settings` (`{$pfx_m_n}_id`, `terms`, `privacy`, `disclaimer`) VALUES (1,
				'<div>
  <h3>
    <span>" . PREFS_SITE_NAME . " usage terms and conditions.</span></h3>
  <p>
    <span>Welcome to </span><span>" . PREFS_SITE_NAME . "</span><span>. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern </span><span>" . PREFS_SITE_NAME . "</span><span>&rsquo;s relationship with you in relation to this website. </span><span>If you disagree with any part of these terms and conditions, please do not use our website.</span></p>
  <p>
    <span>The term &lsquo;</span><span>" . PREFS_SITE_NAME . "</span><span>&rsquo; or &lsquo;us&rsquo; or &lsquo;we&rsquo; refers to the owner of the website. The term &lsquo;you&rsquo; refers to the user or viewer of our website.</span></p>
  <p>
    <span>The use of this website is subject to the following terms of use :</span></p>
  <ul>
    <li>
      &nbsp;<span>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</span></li>
    <li>
      &nbsp;<span>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</span></li>
    <li>
      &nbsp;<span>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</span></li>
    <li>
      &nbsp;<span>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is not prohibited other than in accordance with the </span><span>appropriate licensing terms</span><span>, which forms part of these terms and conditions.</span></li>
    <li>
      &nbsp;<span>All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</span></li>
    <li>
      &nbsp;<span>Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.</span></li>
    <li>
      &nbsp;<span>From time to time, this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</span></li>
    <li>
      &nbsp;<span>Your use of this website and any dispute arising out of such use of the website is subject to the laws of England, Northern Ireland, Scotland and Wales.</span></li>
  </ul>
</div>',
				'<div>
  <h3><span>" . PREFS_SITE_NAME . "</span><span> privacy policy.</span></h3>
  <p>
    <span>This privacy policy sets out how </span><span>" . PREFS_SITE_NAME . "</span><span> uses and protects any information that you give </span><span>" . PREFS_SITE_NAME . "</span><span> when you use this website.</span></p>
  <p>
    <span>" . PREFS_SITE_NAME . "</span><span> is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement.</span></p>
  <p>
    <span>" . PREFS_SITE_NAME . "</span><span> may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes. This policy is effective from </span><span>" . date( 'd/m/Y', time() ) . "</span><span>.</span></p>
  <p>
    <span><b>What we collect</b></span></p>
  <p>
    <span>We may collect the following information :</span></p>
  <p>
    <span>Your <span>name &amp; contact information including email address</span></span>
  </p>
  <p>
    <span><b>What we do with the information we gather</b></span></p>
  <p>
    <span>We require this information to understand your needs and provide you with a better service, and in particular for the following reasons :</span></p>
  <p>
    <span>Internal record keeping &amp; we may use the information to improve our products and services.</span>
  </p>
  <p>
    <span><b>Security</b></span></p>
  <p>
    <span>We are committed to ensuring that your information is secure. In order to prevent unauthorised access or disclosure,</span><span>&nbsp;</span><span>we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online. </span></p>
  <p>
    <span><b>How we use cookies</b></span></p>
  <p>
    <span>A cookie is a small file which asks permission to be placed on your computer&#39;s hard drive. Once you agree, the file is added and the cookie helps analyse web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences. </span></p>
  <p>
    <span>We use traffic log cookies to identify which pages are being used. This helps us analyse data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. </span></p>
  <p>
    <span>Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us. </span></p>
  <p>
    <span>You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</span></p>
  <p>
    <span><b>Links to other websites</b></span></p>
  <p>
    <span>Our website may contain links to other websites of interest. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</span></p>
  <p>
    <span><b>Controlling your personal information</b></span></p>
  <p>
    <span>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law to do so.</span></p>
  <p>
    <span>You may request details of personal information which we hold about you under the Data Protection Act 1998. A small fee will be payable. If you would like a copy of the information held on you please </span><span><a href=\"" . createURL('contact') . "\" title=\"Contact\">contact us</a></span><span>.</span></p>
  <p>
    <span>If you believe that any information we are holding on you is incorrect or incomplete, please </span><span><a href=\"" . createURL('contact') . "\" title=\"Contact\">contact us</a></span><span> as soon as possible. We will promptly correct any information found to be incorrect.</span></p>
</div>' ,
				'<div>
  <h3>
    <span>" . PREFS_SITE_NAME . "</span><span> disclaimer.</span></h3>
  <p>
    <span>The information contained in this website is for general information purposes only. The information is provided by us and while we endeavour to keep the information up to date and correct, we make no representations or warranties of any kind, express or implied, about the completeness, accuracy, reliability, suitability or availability with respect to the website or the information, products, services, or related graphics contained on the website for any purpose. Any reliance you place on such information is therefore strictly at your own risk.</span></p>
  <p>
    <span>In no event will we be liable for any loss or damage including without limitation, indirect or consequential loss or damage, or any loss or damage whatsoever arising from loss of data or profits arising out of, or in connection with, the use of this website.</span></p>
  <p>
    <span>Through this website you are able to link to other websites which are not under the control of </span><span>" . PREFS_SITE_NAME . "</span><span>. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.</span></p>
  <p>
    <span>Every effort is made to keep the website up and running smoothly. However, </span><span>" . PREFS_SITE_NAME . "</span><span> takes no responsibility for, and will not be liable for, the website being temporarily unavailable due to technical issues beyond our control.</span></p>
</div>');";
		break;
	/* The administration of the module (add, edit, delete) */
	/* This is where PFX really saves you time, these few lines of code will create the entire admin interface */
	case 'admin' :
		/* The name of your module */
		$pfx_module_name    = ucfirst($pfx_m_n);
		/* The name of the table */
		$pfx_table_name     = "pfx_module_{$pfx_m_n}";
		/* The field to order by in table view */
		$pfx_order_by       = 'link_title';
		/* Ascending (asc) or decending (desc) */
		$pfx_asc_desc       = 'asc';
		/* Fields you want to exclude in your table view */
		$pfx_view_exclude   = array(
			"{$pfx_m_n}_id"
		);
		/* Fields you do not want people to be able to edit */
		$pfx_edit_exclude   = array(
			"{$pfx_m_n}_id"
		);
		/* The number of items per page in the table view */
		$pfx_items_per_page = 1;
		/* Does this module support tags (yes or no) */
		$pfx_tags           = 'no';
		$pfx_admin_module = admin_module($pfx_lang, $pfx_module_name, $pfx_table_name, $pfx_order_by, $pfx_asc_desc, $pfx_view_exclude, $pfx_edit_exclude, $pfx_items_per_page, $pfx_tags, $pfx_type, $pfx_go, $pfx_page, $pfx_message, $pfx_edit, $pfx_submit_new, $pfx_submit_edit, $pfx_delete, $pfx_messageok, $pfx_new, $pfx_search_submit, $pfx_field, $pfx_search_words, $pfx_scroll, $pfx_page_display_name, $pfx_page_id, $pfx_tag, $pfx_s, $pfx_m, $pfx_x);
		extract($pfx_admin_module, EXTR_PREFIX_ALL, 'pfx');
		$pfx_admin_module = NULL;
		break;
	/* The three sections below are all for the module output, a module is loaded at three different stages of a page build. */
	/* Pre */
	/* Any code to be run before HTML output, any redirects or header changes must occur here */
	case 'pre' :
		break;
	/* Head */
	/* This will output code into the end of the head section of the HTML, this allows you to load in external CSS, etc */
	case 'head' :
		break;
	/* Show Module */
	/* This is where your module will output into the content div on the page */
	default :
		/* Switch $pfx_m (our second variable from the URL) and adjust ouput accordingly */
		switch ($pfx_m) {
			/* $pfx_m is set to tag the we want to filter our links page to only check this tag */
			default:
				/* Get the page display name from the database */
				if (isset($pfx_s)) {
					define( 'SITE_TERMS', fetch('terms', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1) );
					define( 'SITE_PRIVACY', fetch('privacy', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1) );
					define( 'SITE_DISCLAIMER', fetch('disclaimer', "pfx_module_{$pfx_m_n}_settings", "{$pfx_m_n}_id", 1) );
				}
				echo '<div>' . SITE_TERMS . SITE_PRIVACY . SITE_DISCLAIMER . '</div>';
				break;
		}
		break;
}