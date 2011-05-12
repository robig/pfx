<?php
if ( !defined('DIRECT_ACCESS') ) {
	exit( header('Location: ../../../') );
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
 * Title: Database tools
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
if ($GLOBALS['pfx_user'] && $GLOBALS['pfx_user_privs'] >= 2) {
	if ($pfx_do == 'interval') {
			$pfx_new_backup_interval = sterilise_txt($pfx_new_backup_interval);
			if ( safe_update('pfx_settings', "backup_interval = '{$pfx_new_backup_interval}'", "settings_id = '1'") ) {
				$pfx_messageok = $pfx_lang['interval_ok'];
				logme($pfx_lang['interval_ok'], 'no', 'save');
			} else {
				$pfx_message = $pfx_lang['interval_not_ok'];
			}
	} else {
		if ($pfx_do == 'backup') {
			if ( !defined('CONFIG_INPASS') ) {
				$pfx_crypt = new encryption_class;
				define( 'CONFIG_INPASS', $pfx_crypt->decrypt(CONFIG_RANDOM, CONFIG_PASS) );
			}
			$pfx_backup_obj               = new MySQL_Backup();
			$pfx_backup_obj->pfx_server       = CONFIG_HOST;
			$pfx_backup_obj->pfx_username     = CONFIG_USER;
			$pfx_backup_obj->pfx_password     = CONFIG_INPASS;
			$pfx_backup_obj->pfx_database     = CONFIG_DB;
			$pfx_backup_obj->pfx_tables       = array();
			$pfx_backup_obj->pfx_drop_tables  = true;
			$pfx_backup_obj->pfx_struct_only  = false;
			$pfx_backup_obj->pfx_comments     = true;
			$pfx_backup_obj->pfx_backup_dir   = '../files/sqlbackups/';
			$pfx_backup_obj->pfx_fname_format = 'd-m-Y-H-i-s';
			$pfx_filename                 = date('d-m-Y-H-i-s') . '.sql.gz';
			$pfx_task                     = MSB_SAVE;
			$pfx_use_gzip                 = true;
			if ($pfx_backup_obj->Execute($pfx_task, '', $pfx_use_gzip)) {
				$pfx_messageok = $pfx_lang['backup_ok'];
				logme($pfx_lang['backup_ok'], 'no', 'save');
				safe_update('pfx_settings', "last_backup = '{$pfx_filename}'", "settings_id = '1'");
			} else {
				$pfx_message = $pfx_backup_obj->pfx_error;
			}
		}
		if (isset($pfx_del)) {
			if (file_exists("../files/sqlbackups/{$pfx_del}")) {
				$pfx_current = safe_field('last_backup', 'pfx_settings', "settings_id='1'");
				if ($pfx_current != $pfx_del) {
					$pfx_delk = file_delete("../files/sqlbackups/{$pfx_del}");
				} else {
					$pfx_unable = 'yes';
				}
			}
			if ($pfx_delk) {
				$pfx_messageok = "{$pfx_lang['backup_delete_ok']} {$pfx_del}.";
				logme("{$pfx_lang['backup_delete_ok']} {$pfx_del}.", 'no', 'save');
			} else {
				if ($pfx_unable) {
					$pfx_message = $pfx_lang['backup_delete_no'];
				} else {
					$pfx_message = $pfx_lang['backup_delete_error'];
				}
			}
		}
	}
	define('MOD_DBTOOLS', fetch('backup_interval', "pfx_settings", "settings_id", 1));
?>
    <div id="blocks">
	<div id="admin_block_backup" class="admin_block">
	    <h2><?php echo $pfx_lang['database_backup']; ?></h2>
	</div>
    </div>
    <div id="pfx_content">
	<p><?php echo $pfx_lang['database_info']; ?></p>

	    <form action="?s=settings&amp;x=dbtools" method="post" id="backup_setting_interval">
		<fieldset>
		    <legend><?php echo $pfx_lang['auto_backup']; ?></legend>

<div class="form_row">
								<div class="form_label"><label for="logs"><?php echo $pfx_lang['form_pfx_interval']; ?> <span class="form_required"><?php echo $pfx_lang['form_required']; ?></span></label><span class="form_help"><?php echo $pfx_lang['form_help_pfx_interval']; ?></span></div>
								<div class="form_item_drop"><select class="form_select" name="new_backup_interval" id="backup-interval">
	<?php
if (defined('MOD_DBTOOLS')) {
	if (MOD_DBTOOLS == 0) {
		echo "<option selected=\"selected\" value=\"" . MOD_DBTOOLS . "\">{$pfx_lang['never']}</option>";
	} else {
		echo "<option value=\"0\">{$pfx_lang['never']}</option>";
	}
	if (MOD_DBTOOLS == '+1 day') {
		echo "<option selected=\"selected\" value=\"" . MOD_DBTOOLS . "\">{$pfx_lang['daily']}</option>";
	} else {
		echo "<option value=\"+1 day\">{$pfx_lang['daily']}</option>";
	}
	if (MOD_DBTOOLS == '+1 week') {
		echo "<option selected=\"selected\" value=\"" . MOD_DBTOOLS . "\">{$pfx_lang['weekly']}</option>";
	} else {
		echo "<option value=\"+1 week\">{$pfx_lang['weekly']}</option>";
	}
	if (MOD_DBTOOLS == '+1 month') {
		echo "<option selected=\"selected\" value=\"" . MOD_DBTOOLS . "\">{$pfx_lang['monthly']}</option>";
	} else {
		echo "<option value=\"+1 month\">{$pfx_lang['monthly']}</option>";
	}
}
	?>
	</select></div>
		    <div class="form_row_button">
			<input type="submit" name="interval_submit" id="interval_submit" value="<?php echo $pfx_lang['button_interval']; ?>" />
			<input type="hidden" name="do" value="interval" />
		    </div>
		</fieldset>
	    </form>

	    <form action="?s=settings&amp;x=dbtools" method="post" id="backup_save">
		<fieldset>
		    <legend><?php echo $pfx_lang['create_backup']; ?></legend>
		    <div class="form_row_button">
			<input type="submit" name="backup_submit" id="backup_submit" value="<?php echo $pfx_lang['button_backup']; ?>" />
			<input type="hidden" name="do" value="backup" />
		    </div>
		</fieldset>
	    </form>
	<div id="backup">
	    <h3><?php echo $pfx_lang['database_backups']; ?>
	    </h3>
	    <?php
	    $pfx_dir = '../files/sqlbackups/';
		if (is_dir($pfx_dir)) {
		    $pfx_fd = @opendir($pfx_dir);
			if ($pfx_fd) {
			    while ( ($pfx_part = @readdir($pfx_fd)) == TRUE ) {
				if ($pfx_part != '.' && $pfx_part != '..') {
				    $pfx_ext = pathinfo($pfx_part, PATHINFO_EXTENSION);
					if ($pfx_part != 'index.php' && $pfx_ext == 'gz') {
					    if (substr($pfx_part, 0, -8) == substr(PREFS_LAST_BACKUP, 0, -8)) {
						echo "\t\t\t\t\t\t<div class=\"backup-highlighted\"><div class=\"abackup backuplatest\"><div class=\"backup-item\"><img src=\"admin/theme/images/icons/file_sql.png\" alt=\"SQL {$pfx_lang['nav2_backup']}\" class=\"aicon\" /><span class=\"backup_fname\">" . str_replace('.sql.gz', "", $pfx_part) . "</span><a href=\"" . PREFS_SITE_URL . "files/sqlbackups/{$pfx_part}\" title=\"{$pfx_lang['download']}: {$pfx_part}\" class=\"backup_download\">{$pfx_lang['download']}</a></div></div></div>\n";
					    }
					}
				}
			    }
			}
		    $pfx_fd = @closedir($pfx_dir);
		}
	    if (is_dir($pfx_dir)) {
		    $pfx_fd = @opendir($pfx_dir);
			if ($pfx_fd) {
			    while ( ($pfx_part = @readdir($pfx_fd)) == TRUE ) {
				if ($pfx_part != '.' && $pfx_part != '..') {
				    $pfx_ext = pathinfo($pfx_part, PATHINFO_EXTENSION);
					if ($pfx_part != 'index.php' && $pfx_ext == 'gz') {
					    if (substr($pfx_part, 0, -8) !== substr(PREFS_LAST_BACKUP, 0, -8)) {
						echo "\t\t\t\t\t\t<div class=\"backup-other\"><div class=\"abackup\"><div class=\"backup-item\"><img src=\"admin/theme/images/icons/file_sql.png\" alt=\"SQL {$pfx_lang['nav2_backup']}\" class=\"aicon\" /><span class=\"backup_fname\">" . str_replace('.sql.gz', "", $pfx_part) . "</span><a href=\"" . PREFS_SITE_URL . "files/sqlbackups/$pfx_part\" title=\"{$pfx_lang['download']}: $pfx_part\" class=\"backup_download\">{$pfx_lang['download']}</a> <a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;del={$pfx_part}\" title=\"{$pfx_lang['delete']}: $pfx_part\" class=\"backup_delete confirm-del\">{$pfx_lang['delete']}</a></div></div></div>\n";
					    }
					}
				}
			    }
			}
		    $pfx_fd = @closedir($pfx_dir);
		}
?>
	</div>
    </div>
<?php
}