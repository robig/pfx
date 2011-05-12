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
 * Title: Installer Apache PHP PFX .htaccess
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
$pfx_self = $_SERVER['PHP_SELF'];
$pfx_clean = str_replace('/install/index.php', "", $pfx_self);
if ($pfx_clean) {
} else {
	$pfx_clean = '/';
}
$pfx_xy = str_replace('www.', '', "{$_SERVER['SERVER_NAME']}");
$pfx_xyz = str_replace('.', '\.', "{$pfx_xy}");
$pfx_hta = "#
# Apache PHP PFX .htaccess
#
# PFX Powered (http://heydojo.co.cc)
# Licence: GNU General Public License v3

# Pixie. Copyright (C) 2008 - Scott Evans
# PFX. Copyright (C) 2010 - Tony White

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program. If not, see http://www.gnu.org/licenses/   

# http://heydojo.co.cc

# This file was automatically created for you by the PFX Installer.

# .htaccess rules  - Start :

# Set the default charset
AddDefaultCharset " . strtolower($pfx_charset) . "
# Set the default handler.
DirectoryIndex index.php
# Add some document types we will use.
AddType application/x-javascript .js
AddType text/css .css
AddType text/xml .xml

# Rewrite rules - Start :
<IfModule mod_rewrite.c>
Options +SymLinksIfOwnerMatch
RewriteEngine On
SetEnv HTTP_MOD_REWRITE On

# If your site can be accessed both with and without the 'www.' prefix, you
# can use one of the following settings to redirect users to your preferred
# URL, either WITH or WITHOUT the 'www.' prefix.
# By default your users can usually access your site using http://www.{$pfx_xy}
# or http://{$pfx_xy} but it is highly advised that you use the
# actual domain http://yoursite.com by redirecting to it using this file
# because http://www.{$pfx_xy} is simply a subdomain of http://{$pfx_xy}
# the www. is pointless in most applications.
# Choose ONLY one option:

# To redirect all users to access the site WITH the 'www.' prefix,
# (http://{$pfx_xy}/... will be redirected to http://www.{$pfx_xy}/...)
# adapt and uncomment the following two lines :

# RewriteCond %{HTTP_HOST} ^{$pfx_xyz}\$ [NC]
# RewriteRule ^(.*)\$ http://www.{$pfx_xy}/\$1 [L,R=301]

# This next one is the one everyone is advised to select.

# To redirect all users to access the site WITHOUT the 'www.' prefix,
# (http://www.{$pfx_xy}/... will be redirected to http://{$pfx_xy}/...)
# uncomment and adapt the following two lines :

RewriteCond %{HTTP_HOST} ^www\.{$pfx_xyz}\$ [NC]
RewriteRule ^(.*)\$ http://{$pfx_xy}/\$1 [L,R=301]

# You can change the RewriteBase if you have installed PFX into
# a subdirectory or in a VirtualDocumentRoot and clean urls
# do not function correctly after you have turned them on :

RewriteBase {$pfx_clean}

# Rewrite rules to prevent common exploits - Start :
# Block out any script trying to set proc/self/environ
RewriteCond %{QUERY_STRING} proc/self/environ [OR]   
# Block out any script trying to set a mosConfig value through the URL
RewriteCond %{QUERY_STRING} mosConfig_[a-zA-Z_]{1,21}(=|\%3D) [OR]
# Block out any script trying to base64_encode junk to send via URL
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [OR]
# Block out any script that includes a <script> tag in URL
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
# Block out any script trying to set a PHP GLOBALS variable via URL
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
# Block out any script trying to modify a _REQUEST variable via URL
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2})
# Send all blocked request to homepage with 403 Forbidden error!
RewriteRule ^(.*)\$ index.php [F,L]
# End - Rewrite rules to prevent common exploits

# Rewrite rules to prevent hot-linking - Start :
RewriteCond %{HTTP_REFERER} !^http://(.+\.)?{$pfx_xyz}/ [NC]
RewriteCond %{HTTP_REFERER} !^\$
RewriteRule .*\.(png|jpe?g|gif|bmp|svg|gz|zip)\$ - [F]
# End - Rewrite rules to prevent hot-linking

# PFX's core mod rewrite rules - Start :
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*) index.php?%{QUERY_STRING} [L]
# End - PFX's core mod rewrite rules

</IfModule>

# End - rewrite rules

# Protect files and directories
<FilesMatch \"\.(engine|inc|info|install|module|profile|test|po|sh|.*sql|theme|tpl(\.php)?|xtmpl|svn-base)\$|^(code-style\.pl|Entries.*|Repository|Root|Tag|Template|all-wcprops|entries|format)\$\">
Order allow,deny
</FilesMatch>

# Don't show directory listings for URLs which map to a directory.
Options -Indexes

# Make PFX handle any 404 errors.
ErrorDocument 404 /index.php

# Deny access to extension xml files (Comment out to de-activate.) - Start :
<Files ~ \"\.xml\$\">
Order allow,deny
Deny from all
Satisfy all
</Files>
# End - Deny access to extension xml files

# Deny access to htaccess and htpasswd files (Comment out to de-activate.) - Start :
<Files ~ \"\.ht\$\">
order allow,deny
deny from all
Satisfy all
</Files>
# End - Deny access to extension htaccess and htpasswd files

# Extra features - Start :

# Requires mod_expires to be enabled. mod_expires rules - Start :
<IfModule mod_expires.c>
# Enable expirations
ExpiresActive On
# Cache all files for 1 week after access (A).
ExpiresDefault A604800
# Do not cache dynamically generated pages.
ExpiresByType text/html A1
</IfModule>
# End - mod_expires rules

# Requires mod_gzip to be enabled. mod_gzip rules - Start :
<IfModule mod_gzip.c>
mod_gzip_on Yes
mod_gzip_item_include mime ^application/x-javascript\$
mod_gzip_item_include mime ^text/.*\$
mod_gzip_item_include file \.html\$
mod_gzip_item_include file \.php\$
mod_gzip_item_include file \.js\$
mod_gzip_item_include file \.css\$
mod_gzip_item_include file \.txt\$
mod_gzip_item_include file \.xml\$
mod_gzip_send_vary On
Header append Vary Accept-Encoding
<filesMatch \".js\$\">
AddHandler application/x-httpd-php .js
</filesMatch>
<filesMatch \".css\$\">
AddHandler application/x-httpd-php .css
</filesMatch>
<filesMatch \".txt\$\">
AddHandler application/x-httpd-php .txt
</filesMatch>
</IfModule>
# End - mod_gzip rules

# End - Extra features

# End - .htaccess rules";