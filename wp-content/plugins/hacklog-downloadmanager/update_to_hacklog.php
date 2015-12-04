<?php
/**
 * $Id: update_to_hacklog.php 440418 2011-09-19 20:33:35Z ihacklog $
 * $Revision: 440418 $
 * $Date: 2011-09-19 20:33:35 +0000 (Mon, 19 Sep 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * some of the code is from Lester "GaMerZ" Chan	's WP-downloadmanger plugin,
 * Thanks to  Lester "GaMerZ" Chan.  <http://lesterchan.net/wordpress/category/plugins/wp-downloadmanager/>
 */


require( dirname(__FILE__) . '/../../../wp-load.php' );
if (! current_user_can('manage_options') )
wp_die(__('Permission denied!', hacklogdm::textdomain));

header('Content-type: text/html;charget=UTF-8');
//ALTER TABLE $wpdb->downloads ADD file_category int(2) NOT NULL default '0' AFTER file_size;
//ALTER TABLE $wpdb->downloads ADD file_hash varchar(40) NOT NULL default '' AFTER file_des;

function maybe_del_column($table_name, $column_name)
{
	global $wpdb, $debug;
	foreach ($wpdb->get_col("DESC $table_name", 0) as $column ) {
		if ($debug) echo("checking $column == $column_name<br />");
		if ($column == $column_name) {
			if($wpdb->query("ALTER TABLE $table_name DROP $column_name"))
			return true;
			else
			return false;
		}
	}

	// we cannot directly tell that whether this succeeded!
	foreach ($wpdb->get_col("DESC $table_name", 0) as $column )
	{
		if ($column == $column_name)
		{
			return false;
		}
	}
	return true;
}

if(@is_file(ABSPATH.'/wp-admin/upgrade-functions.php')) {
	include_once(ABSPATH.'/wp-admin/upgrade-functions.php');
} elseif(@is_file(ABSPATH.'/wp-admin/includes/upgrade.php')) {
	include_once(ABSPATH.'/wp-admin/includes/upgrade.php');
} else {
	die('We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'');
}

header('Content-type: text/html; charset=utf-8');
if(maybe_add_column($wpdb->downloads, 'file_hash', "ALTER TABLE $wpdb->downloads ADD file_hash varchar(40) NOT NULL default '' AFTER file_des;"))
echo '<div style="margin:7em auto auto 10em;color:green;">成功添加file_hash列-_-!</div>';
else
die('<div style="margin:7em auto auto 10em;color:red;">添加列file_hash出错！</div>');

if(maybe_del_column($wpdb->downloads,'file_category'))
echo '<div style="margin:7em auto auto 10em;color:green;">成功删除file_category列，现在你可以使用 hacklog-downloadmanager了-_-!</div>';
else
die('<div style="margin:7em auto auto 10em;color:red;">删除列file_category出错！</div>');