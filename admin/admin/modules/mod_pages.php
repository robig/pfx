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
 * Title: All Pages with settings
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
if ( (isset($GLOBALS['pfx_user'])) && ($GLOBALS['pfx_user_privs'] >= 2) ) {
	$pfx_table_name = 'pfx_core';
	if ( (isset($pfx_delete) ) && ($pfx_delete)) {
				if ($pfx_page_type == 'module') {
					$pfx_table = adjust_prefix("pfx_module_{$pfx_page_name}");
					$pfx_table_settings = adjust_prefix("pfx_module_{$pfx_page_name}_settings");
					if (table_exists($pfx_table)) {
						safe_query("DROP TABLE {$pfx_table}");
					}
					if (table_exists($pfx_table_settings)) {
						safe_query("DROP TABLE {$pfx_table_settings}");
					}
				} else if ($pfx_page_type == 'plugin') {
					$pfx_table = adjust_prefix("pfx_plugin_{$pfx_page_name}");
					$pfx_table_settings = adjust_prefix("pfx_plugin_{$pfx_page_name}_settings");
					if (table_exists($pfx_table)) {
						safe_query("DROP TABLE {$pfx_table}");
					}
					if (table_exists($pfx_table_settings)) {
						safe_query("DROP TABLE {$pfx_table_settings}");
					}
				}
	}
	if ( (isset($pfx_empty) ) && ($pfx_empty)) {
		$pfx_rf = safe_row('*', 'pfx_core', "page_id = '{$pfx_empty}'");
		if ($pfx_rf) {
			extract($pfx_rf, EXTR_PREFIX_ALL, 'pfx');
			$pfx_rf = NULL;
			if ($pfx_page_type == 'dynamic') {
				safe_delete('pfx_dynamic_posts', "page_id = '{$pfx_empty}'");
				safe_optimize('pfx_dynamic_posts');
				if (isset($pfx_pfx_dynamic_posts)) {
					safe_repair("{$pfx_pfx_dynamic_posts}");
				}
			} else if ($pfx_page_type == 'static') {
				safe_update('pfx_core', "page_content = ''", "page_id = '{$pfx_empty}'");
				safe_optimize('pfx_core');
				safe_repair($pfx_pfx_core);
			} else {
				$pfx_table = adjust_prefix("pfx_module_{$pfx_page_name}");
				if (table_exists($pfx_table)) {
					safe_query("TRUNCATE TABLE {$pfx_table}");
					safe_optimize($pfx_table);
					safe_repair($pfx_table);
				}
			}
			if ($pfx_page_type == 'plugin') {
				$pfx_word = "{$pfx_lang['settings_plugin']}.";
			} else {
				$pfx_word = $pfx_lang['page'];
			}
			$pfx_messageok = "{$pfx_lang['all_content_deleted']} {$pfx_page_display_name} {$pfx_word}";
			logme("{$pfx_lang['all_content_deleted']} {$pfx_page_display_name} $pfx_word", 'yes', 'site');
		}
	}
	if ( (isset($pfx_edit)) && ($pfx_edit) ) {
		$pfx_rs = safe_row('*', 'pfx_core', "page_id = '{$pfx_edit}'");
		extract($pfx_rs, EXTR_PREFIX_ALL, 'pfx');
		$pfx_rs = NULL;
		if ($pfx_page_type == 'plugin') {
			$pfx_word = $pfx_lang['settings_plugin'];
		} else {
			$pfx_word = $pfx_lang['settings_page'];
		}
		echo "\n\t\t\t\t<ul id=\"page_tools\">";
		if ( ($pfx_page_name == 'deny') or ($pfx_page_name == 'comments') or ($pfx_page_name == 'rss') ) {
		} else {
			echo "<li><form class=\"input-confirm\" action=\"?s={$pfx_s}&amp;delete={$pfx_page_id}&amp;page_name={$pfx_page_name}\" method=\"post\">
			<input type=\"submit\" title=\"{$pfx_lang['delete']} {$pfx_lang['settings_page']}: {$pfx_page_display_name}\" class=\"page_delete\" value=\"{$pfx_lang['delete']} {$pfx_lang['form_this']} {$pfx_word}\" />
			</form></li>";
		}
		echo "<li><form class=\"input-confirm\" action=\"?s={$pfx_s}&amp;empty={$pfx_page_id}\" method=\"post\">
		<input type=\"submit\" title=\"{$pfx_lang['empty']} {$pfx_lang['settings_page']}: {$pfx_page_display_name}\" class=\"page_delete\" value=\"{$pfx_lang['empty']} {$pfx_lang['form_this']} {$pfx_word}\" />
		</form></li>
		</ul><div id=\"page_header\"><h2>{$pfx_page_display_name}</h2></div>";
		$pfx_type    = $pfx_page_type;
		$pfx_edit_id = 'page_id';
		if ($pfx_page_type == 'static') {
			if ( (isset($pfx_table_name)) && ($pfx_table_name) ) {
				$pfx_message = admin_edit($pfx_table_name, $pfx_edit_id, $pfx_edit, $pfx_edit_exclude = array(
					'page_id',
					'page_type',
					'page_views',
					'publish',
					'admin',
					'page_content',
					'last_modified',
					'page_parent',
					'page_order'
				), $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
			}
		} else if ($pfx_page_type == 'plugin') {
			echo "\n\t\t\t\t<div class=\"helper\">\n\t\t\t\t\t<h3>{$pfx_lang['help']}</h3>\n\t\t\t\t\t<p>{$pfx_lang['helper_plugin']}</p>\n\t\t\t\t</div>";
		} else {
			if ( (isset($pfx_table_name)) && ($pfx_table_name) ) {
				$pfx_message = admin_edit($pfx_table_name, $pfx_edit_id, $pfx_edit, $pfx_edit_exclude = array(
					'page_id',
					'page_type',
					'page_views',
					'publish',
					'page_content',
					'last_modified',
					'page_parent',
					'page_order'
				), $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
			}
		}
		if ($pfx_page_type == 'dynamic') {
			$pfx_message = admin_edit('pfx_dynamic_settings', $pfx_edit_id, $pfx_edit, $pfx_edit_exclude = array(
				'settings_id',
				'page_id',
				'admin',
				'page_content'
			), $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
		}
		if ( ($pfx_page_type == 'module') or ($pfx_page_type == 'plugin') ) {
			$pfx_module_name = safe_field('page_name', 'pfx_core', "page_id = '{$pfx_edit}'");
			$pfx_table       = "pfx_module_{$pfx_module_name}_settings";
			$pfx_id          = "{$pfx_module_name}_id";
			if (table_exists($pfx_table)) {
				$pfx_message = admin_edit($pfx_table, $pfx_id, 1, $pfx_edit_exclude = array(
					'last_ip', /* Hacky, there is are better ways to do this so that modules can use it - Future feature? */
					'last_vote_id',
					$pfx_id
				), $pfx_lang, $pfx_go, $pfx_message, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
			}
		}
	} else if ( (isset($pfx_do)) && ($pfx_do == 'newpage') ) {
		if ($pfx_type == 'dynamic') {
			echo "<div id=\"page_header\">
				<h2>{$pfx_lang['settings_page_new']} {$pfx_type} {$pfx_lang['settings_page']}</h2>
			</div>";
			if ( (isset($pfx_table_name)) && ($pfx_table_name) ) {
				admin_new($pfx_lang, $pfx_table_name, $pfx_edit_exclude = array(
					'page_id',
					'page_type',
					'page_views',
					'publish',
					'admin',
					'page_content',
					'last_modified',
					'page_parent',
					'page_order'
				), $pfx_go, $pfx_edit, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
			}
		} else if ($pfx_type == 'static') {
			echo "<div id=\"page_header\">
				<h2>{$pfx_lang['settings_page_new']} {$pfx_type} {$pfx_lang['settings_page']}</h2>
			</div>";
			if ( (isset($pfx_table_name)) && ($pfx_table_name) ) {
				admin_new($pfx_lang, $pfx_table_name, $pfx_edit_exclude = array(
					'page_id',
					'page_type',
					'page_views',
					'publish',
					'admin',
					'page_content',
					'last_modified',
					'page_parent',
					'page_order'
				), $pfx_go, $pfx_edit, $pfx_page, $pfx_page_display_name, $pfx_type, $pfx_s, $pfx_m, $pfx_x);
			}
		} else {
			if ( (isset($pfx_install)) && ($pfx_install) ) {
				if ( (isset($pfx_modplug)) && ($pfx_modplug) ) {
					/* Lets install */
					$pfx_do = 'install';
					include("modules/{$pfx_modplug}.php");
					if ((isset($pfx_execute)) && ($pfx_execute)) {
						$pfx_execute = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_execute);
						safe_query($pfx_execute);
					}
					if ((isset($pfx_execute1)) && ($pfx_execute1)) {
						$pfx_execute1 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_execute1);
						safe_query($pfx_execute1);
					}
					if ((isset($pfx_execute2)) && ($pfx_execute2)) {
						$pfx_execute2 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_execute2);
						safe_query($pfx_execute2);
					}
					if ((isset($pfx_execute3)) && ($pfx_execute3)) {
						$pfx_execute3 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_execute3);
						safe_query($pfx_execute3);
					}
					if ((isset($pfx_execute4)) && ($pfx_execute4)) {
						$pfx_execute4 = str_replace('pfx_', CONFIG_TABLE_PREFIX . 'pfx_', $pfx_execute4);
						safe_query($pfx_execute4);
					}
					$pfx_do = 'info';
					include("modules/{$pfx_modplug}.php");
					if (isset($pfx_m_in_navigation)) {
					} else {
						$pfx_m_in_navigation = 'no';
					}
					/* Make a safe reference in core, not public etc */
					$pfx_sql    = "page_type = '{$pfx_m_type}', page_name = '{$pfx_modplug}', page_display_name = '{$pfx_m_name}', page_description = '{$pfx_m_description}', privs = '1', publish = '{$pfx_m_publish}', public = 'yes', in_navigation = '{$pfx_m_in_navigation}', searchable = 'no'";
					$pfx_coreok = safe_insert('pfx_core', $pfx_sql);
					if ($pfx_coreok) {
						$pfx_messageok = "{$pfx_m_name} {$pfx_lang['install_module_ok']}";
						logme($pfx_messageok, 'no', 'site');
					}
				} else {
					$pfx_message = $pfx_lang['no_module_selected'];
				}
			}
			echo "<div id=\"page_header\">
					<h2>{$pfx_lang['install_module']}</h2>
				</div>
			
				<div id=\"admin_form\">
						
					<form accept-charset=\"" . PFX_CHARSET . "\" action=\"?s=settings&amp;x=pages&amp;do=newpage&type=module\" method=\"post\" id=\"form_modplug\" class=\"form\">
						<fieldset>
							<legend>{$pfx_lang['select_module']}</legend>\n";
			$pfx_dir = 'modules/';
			if (is_dir($pfx_dir)) {
				$pfx_fd = @opendir($pfx_dir);
				if ($pfx_fd) {
					while (($pfx_part = @readdir($pfx_fd)) == TRUE) {
						if ($pfx_part != '.' && $pfx_part != '..') {
							if ($pfx_part != 'index.php' && preg_match('/^[A-Za-z].*\.php$/', $pfx_part)) {
								if (last_word($pfx_part) != 'functions.php') {
									$pfx_pname = str_replace('.php', "", $pfx_part);
									$pfx_rs    = safe_row('*', 'pfx_core', "page_name = '$pfx_pname' order by page_name asc");
									if (!$pfx_rs) {
										if (($pfx_pname == 'dynamic') or ($pfx_pname == 'static') or ($pfx_pname == 'module_template')) {
											// ignore these pages
										} else {
											$pfx_do = 'info';
											include "modules/{$pfx_part}";
											$pfx_mfound = TRUE;
											echo "\t\t\t\t\t\t\t<div class=\"amodule\"><input type=\"radio\" name=\"modplug\" value=\"{$pfx_pname}\"><h3><img src=\"admin/theme/images/icons/page_{$pfx_m_type}.png\" alt=\"{$pfx_m_type}\" /> {$pfx_m_name}</h3> {$pfx_m_description} <span class=\"m_credits\"><b>Author:</b> <a href=\"{$pfx_m_url}\" title=\"{$pfx_m_author}\">{$pfx_m_author}</a> <b>Version:</b> v{$pfx_m_version}</span></div>\n";
										}
									}
								}
							}
						}
					}
				}
			}
			if ( (isset($pfx_mfound)) or ($pfx_mfound) ) {
			    echo "<div class=\"form_row_button\" id=\"form_button\">
					<input type=\"submit\" name=\"install\" class=\"form_submit\" id=\"form_modplug_submit\" value=\"{$pfx_lang['form_button_install']}\" />
				</div>";
			} else {
				echo "\t\t\t\t\t\t<p>{$pfx_lang['all_installed']}</p>";
			}
			echo '<div class="safclear">
				  </div></fieldset></form>';
			if ( (isset($pfx_mfound)) or ($pfx_mfound) ) {
			echo "<div class=\"form_row_button\">
				<span class=\"form_button_cancel\">
					<form action=\"?s=settings\" method=\"post\">
						<input type=\"submit\" title=\"{$pfx_lang['form_button_cancel']}\" value=\"{$pfx_lang['form_button_cancel']}\" />
					</form>
				</span>
			      </div>";
			} else {
			echo "<div class=\"form_row_button\">
				<span class=\"form_button_cancel\">
					<form action=\"?s=settings\" method=\"post\">
						<input type=\"submit\" title=\"{$pfx_lang['form_button_cancel']}\" value=\"{$pfx_lang['form_button_back']}\" />
					</form>
				</span>
			      </div>";
			}
			echo '</div>';
		}
	} else {
?>
<div id="blocks" class="page-settings-block">
					<div id="admin_block_addpage" class="admin_block">
						<form accept-charset="<?php echo PFX_CHARSET; ?>" action="?s=settings&amp;x=pages" method="post" id="newpage">
							<fieldset>
							<legend><?php
		echo "{$pfx_lang['settings_page_new']} {$pfx_lang['settings_page']}";
?></legend>
							<div class="form_row">
								<div class="form_label"><label for="type"><?php
		echo $pfx_lang['page_type'];
?> <span class="form_required"><?php
		echo $pfx_lang['form_required'];
?></span></label><span class="form_help"><?php
		echo $pfx_lang['form_help_settings_page_type'];
?></span></div>
								<div class="form_item_drop"><select class="form_select" name="type" id="type">
									<option value="dynamic">Dynamic</option>
									<option value="static">Static</option>
									<option value="module">Module/Plugin</option>
								</select>
							</div>
							</div>
								<div class="form_row_button">
									<input type="submit" name="backup_submit" id="backup_submit" value="<?php
		echo $pfx_lang['form_button_create_page'];
?>" />
									<input type="hidden" name="do" value="newpage" />
								</div>
							</fieldset>

						</form>
					</div>
					<div id="admin_block_help" class="admin_block">
							<h3><?php
		echo $pfx_lang['help'];
?></h3>
							<p><?php
		echo $pfx_lang['help_settings_page_type'];
?></p>
							<ul id="help-settings">
								<li><img src="admin/theme/images/png/d-icon.png" alt="Dynamic" /> <b><?php echo $pfx_lang['dynamic']; ?></b> - <?php
		echo $pfx_lang['help_settings_page_dynamic'];
?></li>
								<li><img src="admin/theme/images/png/m-icon.png" alt="Module" /> <b><?php echo $pfx_lang['module']; ?></b> - <?php
		echo $pfx_lang['help_settings_page_module'];
?></li>
								<li><img src="admin/theme/images/png/s-icon.png" alt="Static" /> <b><?php echo $pfx_lang['static']; ?></b> - <?php
		echo $pfx_lang['help_settings_page_static'];
?></li>
								<li><img src="admin/theme/images/png/p-icon.png" alt="Module" /> <b><?php echo $pfx_lang['plugin']; ?></b> - <?php
		echo $pfx_lang['help_settings_page_plugin'];
?></li>
							</ul>
					</div>
				</div>

				<div id="pfx_content" class="page-settings">
<h2><?php echo $pfx_lang['nav1_settings']; ?></h2>
					<?php
		$pfx_rs = safe_rows('*', 'pfx_core', "public = 'yes' and in_navigation = 'yes' order by page_order asc");
		if ($pfx_rs) {
			$pfx_found = TRUE;
?>
<h3><p><?php
			echo $pfx_lang['pages_in_navigation'];
?></p></h3>
					<p><?php
			echo $pfx_lang['pages_in_navigation_info'];
?></p>
					<?php
			echo "<div class=\"page-settings-wrap\"><ul id=\"pages\" class=\"pagelist innav\">\n";
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out               = $pfx_rs[$pfx_i];
				$pfx_page_display_name = $pfx_out['page_display_name'];
				$pfx_page_name         = $pfx_out['page_name'];
				$pfx_page_type         = $pfx_out['page_type'];
				$pfx_page_id           = $pfx_out['page_id'];
				if ($pfx_page_name == str_replace('/', "", PREFS_DEFAULT_PAGE)) {
					$pfx_homestyle = "{$pfx_page_type} phome";
				} else {
					$pfx_homestyle = $pfx_page_type;
				}
				echo "\t\t\t\t\t\t<li id=\"{$pfx_page_name}\" class=\"page {$pfx_homestyle}\"><div class=\"page_handle\"><span class=\"page_title\">{$pfx_page_display_name}</span><div class=\"page_tools\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_page_id}\" class=\"page_settings\">{$pfx_lang['edit']}</a></div></div></li>\n";
				$pfx_i++;
			}
			echo "\t\t\t\t\t</ul></div>\n";
		}
		$pfx_rs = safe_rows('*', 'pfx_core', "public = 'yes' and in_navigation = 'no' and page_name != '404' and page_type != 'plugin' order by page_name asc");
		if ($pfx_rs) {
			$pfx_found = TRUE;
?>
					
					<h3><?php
			echo $pfx_lang['pages_outside_navigation'];
?></h3>
					<p class="smallerp"><?php
			echo $pfx_lang['pages_outside_navigation_info'];
?></p>
					<?php
			echo "<div class=\"page-settings-wrap\"><ul class=\"pagelist outnav\">\n";
			$pfx_rs  = safe_rows('*', 'pfx_core', "public = 'yes' and in_navigation = 'no' and page_name != '404' and page_type != 'plugin' order by page_name asc");
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out               = $pfx_rs[$pfx_i];
				$pfx_page_display_name = $pfx_out['page_display_name'];
				$pfx_page_name         = $pfx_out['page_name'];
				$pfx_page_type         = $pfx_out['page_type'];
				$pfx_page_id           = $pfx_out['page_id'];
				if ($pfx_page_name == str_replace('/', "", PREFS_DEFAULT_PAGE)) {
					$pfx_homestyle = "{$pfx_page_type} phome";
				} else {
					$pfx_homestyle = $pfx_page_type;
				}
				echo "\t\t\t\t\t\t<li id=\"{$pfx_page_name}\" class=\"page {$pfx_homestyle}\"><span class=\"page_title\">{$pfx_page_display_name}</span><div class=\"page_tools\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_page_id}\" class=\"page_settings\">{$pfx_lang['edit']}</a></div></li>\n";
				$pfx_i++;
			}
			echo "\t\t\t\t\t</ul></div>\n";
		}
		$pfx_rs = safe_rows('*', 'pfx_core', "page_type = 'plugin' order by page_name asc");
		if ($pfx_rs) {
			$pfx_found = TRUE;
?>

					<h3><?php
			echo $pfx_lang['plugins'];
?></h3>
					<p class="smallerp"><?php
			echo $pfx_lang['plugins_info'];
?></p>
					<?php
			echo "<div class=\"page-settings-wrap\"><ul class=\"pagelist plugin\">\n";
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out               = $pfx_rs[$pfx_i];
				$pfx_page_display_name = $pfx_out['page_display_name'];
				$pfx_page_name         = $pfx_out['page_name'];
				$pfx_page_type         = $pfx_out['page_type'];
				$pfx_page_id           = $pfx_out['page_id'];
				echo "\t\t\t\t\t\t<li id=\"{$pfx_page_name}\" class=\"page\"><span class=\"page_title\">{$pfx_page_display_name}</span><div class=\"page_tools\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_page_id}\" class=\"page_settings\">{$pfx_lang['edit']}</a></div></li>\n";
				$pfx_i++;
			}
			echo "\t\t\t\t\t</ul></div>\n";
		}
		$pfx_rs = safe_rows('*', 'pfx_core', "public = 'no' and page_type != 'plugin' order by page_name asc");
		if ( (isset($pfx_rs)) && ($pfx_rs) ) {
			$pfx_found = TRUE;
?>
					<h3><?php
			echo $pfx_lang['pages_disabled'];
?></h3>
					<p class="smallerp"><?php
			echo $pfx_lang['pages_disabled_info'];
?></p>
					<?php
			echo "<div class=\"page-settings-wrap\"><ul class=\"pagelist disabled\">\n";
			$pfx_num = count($pfx_rs);
			$pfx_i   = 0;
			while ($pfx_i < $pfx_num) {
				$pfx_out               = $pfx_rs[$pfx_i];
				$pfx_page_display_name = $pfx_out['page_display_name'];
				$pfx_page_name         = $pfx_out['page_name'];
				$pfx_page_type         = $pfx_out['page_type'];
				$pfx_page_id           = $pfx_out['page_id'];
				$pfx_homestyle         = $pfx_page_type;
				echo "\t\t\t\t\t\t<li id=\"{$pfx_page_name}\" class=\"page {$pfx_homestyle}\"><span class=\"page_title\">{$pfx_page_display_name}</span><div class=\"page_tools\"><a href=\"?s={$pfx_s}&amp;x={$pfx_x}&amp;edit={$pfx_page_id}\" class=\"page_settings\">{$pfx_lang['edit']}</a></div></li>\n";
				$pfx_i++;
			}
			echo "\t\t\t\t\t</ul></div>\n";
		}
		if ( (isset($pfx_found)) && ($pfx_found) ) {
		} else {
			echo "\n\t\t\t\t<div class=\"helper\">\n\t\t\t\t\t<h3>{$pfx_lang['help']}</h3>\n\t\t\t\t\t<p>{$pfx_lang['helper_nopages']}</p>\n\t\t\t\t</div>";
		}
?>
				<div class="spacer"></div></div>
<?php
	}
}