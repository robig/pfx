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
 * Title: lib_cron - Scheduled automatic database backup
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
if ( PREFS_BACKUP_INTERVAL != 0 ) {
      if ( (!defined('TIME_NOW')) OR (!defined('TIME_BACKUP_NEW')) ) {
            define( 'TIME_NOW', strtotime(date('d-m-Y')) );
            define( 'TIME_BACKUP_NEW', strtotime(PREFS_BACKUP_INTERVAL, strtotime(substr(PREFS_LAST_BACKUP, 0, 10))) );
            if (TIME_NOW > TIME_BACKUP_NEW) {
                  include_once 'admin/lib/lib_backup.php';
                  $backup_obj = new MySQL_Backup();
                  $backup_obj->pfx_server = CONFIG_HOST;
                  $backup_obj->pfx_username = CONFIG_USER;
                  $backup_obj->pfx_password = CONFIG_INPASS;
                  $backup_obj->pfx_database = CONFIG_DB;
                  $backup_obj->pfx_tables = array();
                  $backup_obj->pfx_drop_tables = TRUE;
                  $backup_obj->pfx_struct_only = FALSE;
                  $backup_obj->pfx_comments = TRUE;
                  $backup_obj->pfx_backup_dir = 'files/sqlbackups/';
                  $backup_obj->pfx_fname_format = 'd-m-Y-H-i-s';
                  $pfx_cur_date = date('d-m-Y-H-i-s');
                  $filename = "{$pfx_cur_date}.sql.gz";
                  $task = MSB_SAVE;
                  $use_gzip = TRUE;
                  if ($backup_obj->Execute($task, '', $use_gzip)) {
                        logme($pfx_lang['cron_ok'], 'no', 'save');
                        safe_update('pfx_settings', "last_backup = '{$filename}'", "settings_id = '1'");
                        $dir = 'files/sqlbackups/';
                        if (is_dir($dir)) {
                              $fd = @opendir($dir);
                              if ($fd) {
                                    while ( ($part = @readdir($fd)) === TRUE ) {
                                          if ($part != '.' && $part != '..') {
                                                $diff = ( time() - filectime("files/sqlbackups/{$part}") ) / 60 / 60 / 24;
                                                if ($diff > 28) {
                                                /* Clear any files older than 4 weeks */
                                                $delk = file_delete("files/sqlbackups/{$part}");
                                                }
                                          }
                                    }
                              }
                        }
                  } else {
                        logme($pfx_lang['cron_not_ok'], 'yes', 'save');
                  }
            }
      }
}