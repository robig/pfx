<?php
if (!defined('DIRECT_ACCESS')) { exit( header( 'Location: ../' ) ); }
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
 * Title: Installer - The install actions core database schema
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
/* The pfx_bad_behaviour table */
$pfx_sql0 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_bad_behavior` (
`id` int(11) NOT NULL auto_increment,
`ip` text collate {$pfx_db_collate} NOT NULL,
`date` datetime NOT NULL default '0000-00-00 00:00:00',
`request_method` text collate {$pfx_db_collate} NOT NULL,
`request_uri` text collate {$pfx_db_collate} NOT NULL,
`server_protocol` text collate {$pfx_db_collate} NOT NULL,
`http_headers` text collate {$pfx_db_collate} NOT NULL,
`user_agent` text collate {$pfx_db_collate} NOT NULL,
`request_entity` text collate {$pfx_db_collate} NOT NULL,
`key` text collate {$pfx_db_collate} NOT NULL,
PRIMARY KEY  (`id`),
KEY `ip` (`ip`(15)),
KEY `user_agent` (`user_agent`(10))
) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
/* The pfx_core table */
$pfx_sql1 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_core` (
`page_id` smallint(11) NOT NULL auto_increment,
`page_type` set('dynamic','static','module','plugin') collate {$pfx_db_collate} NOT NULL default '',
`page_name` varchar(40) collate {$pfx_db_collate} NOT NULL default '',
`page_display_name` varchar(40) collate {$pfx_db_collate} NOT NULL default '',
`page_description` longtext collate {$pfx_db_collate} NOT NULL,
`page_blocks` varchar(200) collate {$pfx_db_collate} default NULL,
`page_content` longtext collate {$pfx_db_collate},
`page_views` int(12) default '0',
`page_parent` varchar(40) collate {$pfx_db_collate} default NULL,
`privs` tinyint(2) NOT NULL default '1',
`publish` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`public` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
`in_navigation` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`page_order` int(3) default '0',
`searchable` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`last_modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} PACK_KEYS=0 AUTO_INCREMENT=3 ;
";
/* Insert pfx_core data for the 404 and comments plugins */
$pfx_sql2 = "INSERT INTO `{$_SESSION['prefix']}pfx_core` (`page_id`, `page_type`, `page_name`, `page_display_name`,
`page_description`, `page_blocks`, `page_content`, `page_views`, `page_parent`, `privs`, `publish`,
`public`, `in_navigation`, `page_order`, `searchable`, `last_modified`) VALUES
(1, 'static', '404', 'Error 404', 'Page not found.', '',
'<p>The page you are looking for cannot be found.</p>', 11, '', 2, 'yes', 'yes', 'no', 7, 'no', '2008-01-01 00:00:11'),
(2, 'plugin', 'comments', 'Comments', 'This plugin enables commenting on dynamic pages.',
'', '', 1, '', 1, 'yes', 'yes', 'no', 2, 'no', '2008-01-01 00:00:11');
";
/* The pfx_dynamic_posts table */
$pfx_sql3 = "
CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_dynamic_posts` (
`post_id` int(11) NOT NULL auto_increment,
`page_id` int(11) NOT NULL default '0',
`posted` timestamp NOT NULL default '0000-00-00 00:00:00',
`title` varchar(235) collate {$pfx_db_collate} NOT NULL default '',
`content` longtext collate {$pfx_db_collate} NOT NULL,
`tags` varchar(200) collate {$pfx_db_collate} NOT NULL default '',
`public` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
`comments` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`author` varchar(64) collate {$pfx_db_collate} NOT NULL default '',
`last_modified` timestamp NULL default CURRENT_TIMESTAMP,
`post_views` int(12) default NULL,
`post_slug` varchar(255) collate {$pfx_db_collate} NOT NULL default '',
PRIMARY KEY  (`post_id`),
UNIQUE KEY `id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
/* The pfx_dynamic_settings table */
$pfx_sql4 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_dynamic_settings` (
`settings_id` int(11) NOT NULL auto_increment,
`page_id` int(11) NOT NULL default '0',
`posts_per_page` int(2) NOT NULL default '0',
`rss` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
PRIMARY KEY  (`settings_id`)
) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} PACK_KEYS=0 AUTO_INCREMENT=1 ;
";
/* The pfx_files table */
$pfx_sql5 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_files` (
`file_id` smallint(6) NOT NULL auto_increment,
`file_type` set('Video','Image','Audio','Other') collate {$pfx_db_collate} NOT NULL default '',
`file_extension` varchar(5) collate {$pfx_db_collate} NOT NULL default '',
`file_name` varchar(80) collate {$pfx_db_collate} NOT NULL default '',
`tags` varchar(200) collate {$pfx_db_collate} NOT NULL default '',
PRIMARY KEY  (`file_id`),
UNIQUE KEY `id` (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} PACK_KEYS=0 AUTO_INCREMENT=5 ;
";
/* Insert the default files supplied with PFX */
$pfx_sql6 = "INSERT INTO `{$_SESSION['prefix']}pfx_files` (`file_id`, `file_name`, `file_extension`, `file_type`, `tags`) VALUES
(1, 'rss_feed_icon.png', 'png', 'Image', 'Large RSS feed icon'),
(2, 'no_grav.png', 'png', 'Image', 'Gravitar icon'),
(3, 'pfx.png', 'png', 'Image', 'PFX Logo');
";
/* The pfx_log table */
$pfx_sql7 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_log` (
`log_id` int(6) NOT NULL auto_increment,
`log_icon` varchar(20) collate {$pfx_db_collate} NOT NULL default '',
`user_ip` varchar(15) collate {$pfx_db_collate} NOT NULL default '',
`user_id` varchar(40) collate {$pfx_db_collate} NOT NULL default '',
`log_type` set('referral','system') collate {$pfx_db_collate} NOT NULL default '',
`log_message` varchar(750) collate {$pfx_db_collate} NOT NULL default '',
`log_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
`log_important` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
PRIMARY KEY  (`log_id`),
UNIQUE KEY `id` (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
/* The pfx_log_users_online table */
$pfx_sql8 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_log_users_online` (
`visitor_id` int(11) NOT NULL auto_increment,
`visitor` varchar(15) collate {$pfx_db_collate} NOT NULL default '',
`last_visit` int(14) NOT NULL default '0',
PRIMARY KEY  (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
/* The pfx_module_comments table */
$pfx_sql9 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_module_comments` (
`comments_id` int(5) NOT NULL auto_increment,
`post_id` int(5) NOT NULL default '0',
`posted` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
`name` varchar(80) collate {$pfx_db_collate} NOT NULL default '',
`email` varchar(80) collate {$pfx_db_collate} NOT NULL default '',
`url` varchar(80) collate {$pfx_db_collate} default NULL,
`comment` longtext collate {$pfx_db_collate} NOT NULL,
`admin_user` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
PRIMARY KEY  (`comments_id`)
) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
/* The pfx_settings table */
$pfx_sql10 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_settings` (
`settings_id` smallint(6) NOT NULL auto_increment,
`site_name` varchar(80) collate {$pfx_db_collate} NOT NULL default '',
`site_keywords` longtext collate {$pfx_db_collate} NOT NULL,
`site_url` varchar(255) collate {$pfx_db_collate} NOT NULL default '',
`site_theme` varchar(80) collate {$pfx_db_collate} NOT NULL default '',
`site_copyright` varchar(80) collate {$pfx_db_collate} NOT NULL default 'GNU/GPL V3',
`site_author` varchar(80) collate {$pfx_db_collate} NOT NULL default '',
`default_page` varchar(40) collate {$pfx_db_collate} NOT NULL default '',
`clean_urls` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`jquery` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`jquery_latest` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
`jquery_g_apis` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
`g_jquery_loc` varchar(235) collate {$pfx_db_collate} NOT NULL default 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
`valid_css_xhtml` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`lightbox` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`gzip` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`ie7_compat` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`captcha` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
`version` varchar(30) collate {$pfx_db_collate} NOT NULL default '',
`language` varchar(30) collate {$pfx_db_collate} NOT NULL default '',
`timezone` varchar(235) collate {$pfx_db_collate} NOT NULL default '',
`date_format` varchar(30) collate {$pfx_db_collate} NOT NULL default '',
`charset` varchar(20) collate {$pfx_db_collate} NOT NULL default '',
`logs_expire` varchar(3) collate {$pfx_db_collate} NOT NULL default '',
`rich_text_editor` tinyint(1) NOT NULL default '0',
`editor_enter_mode` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',
`editor_image_class` varchar(24) collate {$pfx_db_collate} NOT NULL default 'lightbox',
`system_message` tinytext collate {$pfx_db_collate} NOT NULL,
`bb2_installed` SET('yes','no') collate {$pfx_db_collate} NOT NULL DEFAULT 'no',
`last_backup` varchar(120) collate {$pfx_db_collate} NOT NULL default '',
`backup_interval` varchar(80) collate {$pfx_db_collate} NOT NULL default '+1 month',
`recaptcha_private_key` varchar(60) collate {$pfx_db_collate} NOT NULL default '6LcQeQwAAAAAAGOgr77g-YKi6bWON0o8jKqGMvKp',
`recaptcha_public_key` varchar(60) collate {$pfx_db_collate} NOT NULL default '6LcQeQwAAAAAAEZjHs9WtiysKwaIOa9R7YYxp_qS',
`log_bots` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',
PRIMARY KEY  (`settings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
/* The pfx_users table */
$pfx_sql11 = "CREATE TABLE IF NOT EXISTS `{$_SESSION['prefix']}pfx_users` (
`user_id` int(4) NOT NULL auto_increment,
`user_name` varchar(64) collate {$pfx_db_collate} NOT NULL default '',
`realname` varchar(64) collate {$pfx_db_collate} NOT NULL default '',
`street` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`town` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`county` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`country` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`post_code` varchar(20) collate {$pfx_db_collate} NOT NULL default '',
`telephone` varchar(30) collate {$pfx_db_collate} NOT NULL default '',
`email` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`website` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`biography` mediumtext collate {$pfx_db_collate} NOT NULL,
`occupation` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`link_1` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`link_2` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`link_3` varchar(100) collate {$pfx_db_collate} NOT NULL default '',
`privs` tinyint(2) NOT NULL default '1',
`pass` varchar(255) collate {$pfx_db_collate} NOT NULL default '',
`nonce` varchar(255) collate {$pfx_db_collate} NOT NULL default '',
`user_hits` int(7) NOT NULL default '0',
`last_access` timestamp NOT NULL default CURRENT_TIMESTAMP,
`is_contact` SET('yes','no') collate {$pfx_db_collate} NOT NULL DEFAULT 'no',
`rte_user` SET('yes','no') collate {$pfx_db_collate} NOT NULL DEFAULT 'no',
PRIMARY KEY  (`user_id`),
UNIQUE KEY `name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} PACK_KEYS=1 AUTO_INCREMENT=1 ;
";
/* Place dummy settings into settings table */
$pfx_sql12 = "INSERT INTO `{$_SESSION['prefix']}pfx_settings` (`settings_id`, `site_name`, `site_keywords`, `site_url`,
`site_theme`, `site_copyright`, `site_author`, `default_page`, `clean_urls`, `version`, `language`, `timezone`,
`date_format`, `charset`, `logs_expire`, `rich_text_editor`, `system_message`, `last_backup`) VALUES
(1, '-', '-', '-', '-', '', '', '-', 'no', '-', '-', '-', '-', '-', '-', 1, '', '');
";
/* Save the default site settings to the database */
$pfx_sql13 = "site_name = '{$_SESSION['sitename']}', 
site_url = '{$_SESSION['url']}',
site_theme = 'Skelington',
version = '{$pfx_version}',
language = '{$_SESSION['langu']}',
timezone = '{$_SESSION['server_timezone']}',
date_format = '%Oe %B %Y, %H:%M',
charset = '{$pfx_charset}',
logs_expire = '30',
rich_text_editor = '1',
system_message = '<b class=\"welcome\">Welcome to {$_SESSION['sitename']} powered by PFX.</b>', 
site_keywords = 'pfx,cms,content,management,system,design,microformats,web,standards', 
default_page = 'blog/',
clean_urls = '{$_SESSION['clean_urls_check']}'
";
$pfx_sql14 = "CREATE TABLE IF NOT EXISTS `pfx_module_rss` (`rss_id` tinyint(2) NOT NULL auto_increment,`feed_display_name` varchar(80) collate {$pfx_db_collate} NOT NULL default '', `url` varchar(80) collate {$pfx_db_collate} NOT NULL default '', PRIMARY KEY  (`rss_id`)) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
$pfx_sql15 = "INSERT INTO `pfx_module_rss` (`rss_id`, `feed_display_name`, `url`) VALUES (1, '{$_SESSION['sitename']} blog', '{$pfx_rss_plugin_url}');
";
/* Adjust each file to use the table prefix if set */
$pfx_sql14 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql14);
$pfx_sql15 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql15);
/* Add some defaults and super user information */
$pfx_sql16 = "realname = '{$_SESSION['name']}'
";
$pfx_sql17 = "user_name = '{$_SESSION['login_username']}'
";
$pfx_sql18 = "email = '{$_SESSION['email']}'
";
$pfx_sql19 = "pass = '" . doPass($_SESSION['login_password']) . "', nonce = '{$pfx_nonce}', privs = '3', link_1 = 'http://heydojo.co.cc', link_2 = 'http://google.co.uk', link_3 = 'http://slashdot.org', website='{$_SESSION['url']}', `biography`='', is_contact ='yes', rte_user ='yes'
";
$pfx_sql20 = "INSERT INTO `pfx_core` (`page_id`, `page_type`, `page_name`, `page_display_name`, `page_description`, `page_blocks`, `page_content`, `page_views`, `page_parent`, `privs`, `publish`, `public`, `in_navigation`, `page_order`, `searchable`, `last_modified`) VALUES (3, 'dynamic', 'blog', 'My Blog', 'This is my Blog about stuff!', NULL, NULL, 0, '', 1, 'yes', 'yes', 'yes', 0, 'yes', '2008-03-25 10:53:10'), (4, 'static', 'about', 'About Me', '<p>This is a page all about me</p>', NULL, NULL, 0, '', 1, 'yes', 'yes', 'yes', 1, 'yes', '2008-03-25 10:54:00');
";
$pfx_sql20 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql20);
$pfx_sql21 = "INSERT INTO `pfx_dynamic_settings` (`settings_id`, `page_id`, `posts_per_page`, `rss`) VALUES (1, 3, 10, 'yes');
";
$pfx_sql21 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql21);
$pfx_sql22 = "INSERT INTO `pfx_dynamic_posts` (`post_id`, `page_id`, `posted`, `title`, `content`, `tags`, `public`, `comments`, `author`, `last_modified`, `post_views`, `post_slug`) VALUES (1, 3, '2008-03-25 11:02:00', 'My First Post', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent posuere ante sit amet odio. Nam lacus justo, aliquam nec, dictum varius, consectetuer quis, dui. Integer diam sapien, gravida vel, tristique non, dignissim eu, nisi. Morbi quis turpis. Proin ante tortor, ultricies vel, auctor quis, tempor a, sapien. Aenean magna ante, porttitor eget, molestie egestas, scelerisque at, sapien. Praesent malesuada arcu a felis. Integer ut lectus. Sed accumsan neque ac orci. </p>\r\n<p>Quisque lobortis, nibh sed facilisis volutpat, massa nunc interdum velit, eget nonummy neque velit quis neque. Donec lacus libero, porta id, hendrerit vitae, porttitor et, dui.  Suspendisse potenti. Donec consequat imperdiet eros. Morbi blandit quam ac nisi. Nulla sit amet lectus. Aenean at magna. Fusce lobortis aliquet sem. Quisque ultricies ipsum a quam. Sed laoreet. Quisque lacinia sollicitudin felis. Suspendisse potenti. Pellentesque suscipit iaculis lorem. Ut id ante quis augue porta tempor. Phasellus sed urna. Ut ante. Donec sagittis est eget justo. Proin dolor erat, molestie sit amet, hendrerit et, elementum consequat, neque. Donec at libero et felis viverra viverra. Integer cursus magna sit amet nunc. Vivamus id enim.</p>', 'my first post', 'yes', 'yes', '{$_SESSION['name']}', '2010-03-25 11:02:50', 0, 'my-first-post'); 
";
$pfx_sql22 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql22);
$pfx_sql23 = "UPDATE `pfx_core` SET `page_content` = '<p>Lorem ipsum dolor sit amet&sbquo; consectetuer adipiscing elit. Praesent posuere ante sit amet odio. Nam lacus justo&sbquo; aliquam nec&sbquo; dictum varius&sbquo; consectetuer quis&sbquo; dui. Integer diam sapien&sbquo; gravida vel&sbquo; tristique non&sbquo; dignissim eu&sbquo; nisi. Morbi quis turpis. Proin ante tortor&sbquo; ultricies vel&sbquo; auctor quis&sbquo; tempor a&sbquo; sapien. Aenean magna ante&sbquo; porttitor eget&sbquo; molestie egestas&sbquo; scelerisque at&sbquo; sapien. Praesent malesuada arcu a felis. Integer ut lectus. Sed accumsan neque ac orci. Quisque lobortis&sbquo; nibh sed facilisis volutpat&sbquo; massa nunc interdum velit&sbquo; eget nonummy neque velit quis neque. Donec lacus libero&sbquo; porta id&sbquo; hendrerit vitae&sbquo; porttitor et.</p>',`last_modified` = NOW( ) WHERE `pfx_core`.`page_id` =4 LIMIT 1 ;
";
$pfx_sql23 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql23);
$pfx_sql24 = "INSERT INTO `pfx_core` (`page_id`, `page_type`, `page_name`, `page_display_name`, `page_description`, `page_blocks`, `page_content`, `page_views`, `page_parent`, `privs`, `publish`, `public`, `in_navigation`, `page_order`, `searchable`, `last_modified`) VALUES (5, 'module', 'contact', 'Contact', '<p>A simple contact form for your website with hCard/vCard Microformats.</p>', NULL, '', 0, '', 2, 'no', 'yes', 'yes', 5, 'no', '2008-04-25 10:33:42'), (6, 'plugin', 'rss', 'RSS Plugin', 'Allows you to have control over the RSS feeds that are available to your visitors.', '', '', 0, '', 0, 'yes', 'yes', 'no', 0, 'no', '2008-04-22 18:32:36'), (7, 'module', 'events', 'Events', '<p>Events module with support for hCalendar microformat, archives and Google calendar links.</p>', '', '', 0, '', 2, 'yes', 'yes', 'yes', 3, 'no', '2008-04-25 10:33:39'), (8, 'module', 'links', 'Links', 'Store a collection of links on your website and group them by tag.', '', NULL, 0, NULL, 2, 'yes', 'yes', 'yes', 0, 'no', '2008-04-25 11:05:07');
";
$pfx_sql24 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql24);
$pfx_sql25 = "CREATE TABLE IF NOT EXISTS `pfx_module_contact` (`contact_id` mediumint(1) NOT NULL auto_increment,PRIMARY KEY  (`contact_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=2 ;
";
$pfx_sql25 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql25);
$pfx_sql26 = "CREATE TABLE IF NOT EXISTS `pfx_module_contact_settings` (`contact_id` mediumint(1) NOT NULL auto_increment,`show_profile_information` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',`show_vcard_link` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',PRIMARY KEY  (`contact_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=2 ;
";
$pfx_sql26 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql26);
$pfx_sql27 = "CREATE TABLE IF NOT EXISTS `pfx_module_events` (`events_id` int(5) NOT NULL auto_increment,`date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,`title` varchar(100) collate {$pfx_db_collate} NOT NULL default '',`description` longtext collate {$pfx_db_collate},`location` varchar(120) collate {$pfx_db_collate} default NULL,`url` varchar(140) collate {$pfx_db_collate} default NULL,`public` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'yes',PRIMARY KEY  (`events_id`),UNIQUE KEY `id` (`events_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
$pfx_sql27 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql27);
$pfx_sql28 = "CREATE TABLE IF NOT EXISTS `pfx_module_events_settings` (`events_id` mediumint(1) NOT NULL auto_increment,`google_calendar_links` set('yes','no') collate {$pfx_db_collate} NOT NULL default '',`number_of_events` varchar(3) collate {$pfx_db_collate} NOT NULL default '10',PRIMARY KEY  (`events_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=2 ;
";
$pfx_sql28 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql28);
$pfx_sql29 = "INSERT INTO `pfx_module_events_settings` (`events_id`, `google_calendar_links`, `number_of_events`) VALUES (1, 'yes', '10');
";
$pfx_sql29 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql29);
$pfx_sql30 = "CREATE TABLE IF NOT EXISTS `pfx_module_comments_settings` (`comments_id` mediumint(1) NOT NULL auto_increment,PRIMARY KEY  (`comments_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=2 ;
";
$pfx_sql30 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql30);
$pfx_sql31 = "CREATE TABLE IF NOT EXISTS `pfx_module_links_settings` (`links_id` mediumint(1) NOT NULL auto_increment,`top_description` LONGTEXT collate {$pfx_db_collate} default '',`lower_description` LONGTEXT collate {$pfx_db_collate} default '',`open_links_in_new_tabs` set('yes','no') collate {$pfx_db_collate} NOT NULL default 'no',PRIMARY KEY  (`links_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=2 ;
";
$pfx_sql31 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql31);
$pfx_sql32 = "CREATE TABLE IF NOT EXISTS `pfx_module_rss_settings` (`rss_id` mediumint(1) NOT NULL auto_increment,PRIMARY KEY  (`rss_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=2 ;
";
$pfx_sql32 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql32);
$pfx_sql33 = "CREATE TABLE IF NOT EXISTS `pfx_module_links` (`links_id` int(4) NOT NULL auto_increment,`link_title` varchar(150) collate {$pfx_db_collate} NOT NULL default '',`tags` varchar(200) collate {$pfx_db_collate} NOT NULL default '',`url` varchar(300) collate {$pfx_db_collate} NOT NULL default '',PRIMARY KEY  (`links_id`)) ENGINE=MyISAM DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=1 ;
";
$pfx_sql33 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql33);
$pfx_sql34 = "INSERT INTO `pfx_module_contact_settings` (`contact_id`, `show_profile_information`, `show_vcard_link`) VALUES (1, 'no', 'no');
";
$pfx_sql34 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql34);
$pfx_sql35 = "INSERT INTO `pfx_module_events` (`events_id`, `date`, `title`, `description`, `location`, `url`, `public`) VALUES (1, '2012-01-01 00:00:00', 'New Year!', '<p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Ut rhoncus. Pellentesque lectus sem, dictum ac, sagittis nec, tincidunt non, quam. Morbi eget lacus. In vel elit at leo lacinia viverra. Vestibulum sit amet quam non nulla sollicitudin fermentum. Donec leo. Phasellus vitae dui auctor nisi sodales condimentum. Cras turpis erat, laoreet ac, adipiscing eu, elementum non, massa. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec commodo magna in magna. Nam purus. Phasellus porta vulputate risus. Ut suscipit tincidunt tellus. </p>', 'Everywhere', '', 'yes');
";
$pfx_sql35 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql35);
$pfx_sql36 = "INSERT INTO `pfx_module_links` VALUES ('1', 'PFX', 'PFX', 'http://heydojo.co.cc');
";
$pfx_sql36 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql36);
$pfx_sql37 = "INSERT INTO `pfx_module_links` VALUES ('2', 'PFX - Blog', 'PFX', 'http://heydojo.co.cc/blog');
";
$pfx_sql37 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql37);
$pfx_sql38 = "INSERT INTO `pfx_module_links` VALUES ('3', 'PFX - RSS Feed', 'PFX', 'http://heydojo.co.cc/rss');
";
$pfx_sql38 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql38);
$pfx_sql39 = "UPDATE `pfx_core` SET `page_order` = '6' WHERE `pfx_core`.`page_name` ='links';
";
$pfx_sql39 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql39);
$pfx_sql40 = "UPDATE `pfx_core` SET `page_order` = '4' WHERE `pfx_core`.`page_name` ='rss';
";
$pfx_sql40 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql40);
$pfx_sql41 = "INSERT INTO `pfx_module_links_settings` (`links_id`) VALUES (1);
";
$pfx_sql41 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql41);
$pfx_sql42  = "CREATE TABLE IF NOT EXISTS `pfx_module_deny` (`deny_id` int(4) NOT NULL auto_increment,`ip` varchar(150) collate {$pfx_db_collate} NOT NULL default '',PRIMARY KEY  (`deny_id`)) ENGINE=MyISAM  DEFAULT CHARSET={$pfx_db_charset} COLLATE={$pfx_db_collate} AUTO_INCREMENT=0 ;";
$pfx_sql42 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_sql42);