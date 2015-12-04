<?php
/*
+------------------------------------------------------------------------------+
|	$Id: download-uninstall.php 449165 2011-10-09 18:15:58Z ihacklog $				       |
|	$Revision: 449165 $															       |
|	$Date: 2011-10-09 18:15:58 +0000 (Sun, 09 Oct 2011) $ 					  	       |
|	WordPress Plugin: Hacklog-DownloadManager									       |
|	Copyright (c) 2009 Lester "GaMerZ" Chan										       |
|																			       |
|	File Written By:															       |
|	- Lester "GaMerZ" Chan														       |
|	- http://lesterchan.net														       |
|																			       |
|	-Modified By 荒野无灯														       |
|	-http://ihacklog.com														       |
|																			       |
+------------------------------------------------------------------------------+
*/


### Check Whether User Can Manage Downloads
if(!current_user_can('manage_downloads')) {
	die('Access Denied');
}

//load the admin class
require dirname(__FILE__). '/includes/hacklogdm_admin.class.php';

### Variables Variables Variables
$base_name = plugin_basename('hacklog-downloadmanager/download-manager.php');
$base_page = 'admin.php?page='.$base_name;
$mode = trim( hacklogdm_admin::get('mode'));
$downloads_tables = array($wpdb->downloads);
$downloads_settings = hacklogdm::get_opt_keys();


### Form Processing
if(isset($_POST['do'])) {
	// Decide What To Do
	switch( hacklogdm_admin::post('do')) 
			{
		//  Uninstall Hacklog-DownloadManager
		case __('UNINSTALL Hacklog-DownloadManager', hacklogdm::textdomain) :
			if(trim(hacklogdm_admin::post('uninstall_download_yes')) == 'yes') {
				echo '<div id="message" class="updated fade">';
				echo '<p>';
				foreach($downloads_tables as $table) {
					$wpdb->query("DROP TABLE {$table}");
					echo '<span style="color: green;">';
					printf(__('Table \'%s\' has been deleted.', hacklogdm::textdomain), "<strong><em>{$table}</em></strong>");
					echo '</span><br />';
				}
				echo '</p>';
				echo '<p>';
				foreach($downloads_settings as $setting) {
					$delete_setting = delete_option($setting);
					if($delete_setting) {
						echo '<span style="color:green;">';
						printf(__('Setting Key \'%s\' has been deleted.', hacklogdm::textdomain), "<strong><em>{$setting}</em></strong>");
						echo '</span><br />';
					} else {
						echo '<span style="color:red;">';
						printf(__('Error deleting Setting Key \'%s\'.', hacklogdm::textdomain), "<strong><em>{$setting}</em></strong>");
						echo '</span><br />';
					}
				}
				echo '</p>';
				echo '<p style="color: blue;">';
				_e('The download files uploaded by Hacklog-DownloadManager <strong>WILL NOT</strong> be deleted. You will have to delete it manually.',  hacklogdm::textdomain);
				echo '<br />';
				printf(__('The path to the downloads folder is <strong>\'%s\'</strong>.',  hacklogdm::textdomain), hacklogdm_admin::get_opt('download_path') );
				echo '</p>';
				echo '</div>';
				$mode = 'end-UNINSTALL';
			}
			break;
	}
}


### Determines Which Mode It Is
switch($mode) {
	//  Deactivating Hacklog-DownloadManager
	case 'end-UNINSTALL':
		flush_rewrite_rules();
		$deactivate_url = 'plugins.php?action=deactivate&amp;plugin=hacklog-downloadmanager/hacklog-downloadmanager.php';
		if(function_exists('wp_nonce_url')) {
			$deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_hacklog-downloadmanager/hacklog-downloadmanager.php');
		}
		echo '<div class="wrap">';
		echo '<div id="icon-hacklog-downloadmanager" class="icon32"><br /></div>';
		echo '<h2>'.__('Uninstall Hacklog-DownloadManager', hacklogdm::textdomain).'</h2>';
		echo '<p><strong>'.sprintf(__('<a href="%s">Click Here</a> To Finish The Uninstallation And Hacklog-DownloadManager Will Be Deactivated Automatically.', hacklogdm::textdomain), $deactivate_url).'</strong></p>';
		echo '</div>';
		break;
		// Main Page
	default:
		?>
<!-- Uninstall Hacklog-DownloadManager -->
<form method="post"
	action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
<div class="wrap">
<div id="icon-hacklog-downloadmanager" class="icon32"><br />
</div>
<h2><?php _e('Uninstall Hacklog-DownloadManager',  hacklogdm::textdomain); ?></h2>
<p><?php _e('Deactivating Hacklog-DownloadManager plugin does not remove any data that may have been created, such as the download options and the download data. To completely remove this plugin, you can uninstall it here.',  hacklogdm::textdomain); ?>
</p>
<p style="color: red"><strong><?php _e('NOTE:',  hacklogdm::textdomain); ?></strong><br />
		<?php _e('The download files uploaded by Hacklog-DownloadManager <strong>WILL NOT</strong> be deleted. You will have to delete it manually.',  hacklogdm::textdomain); ?><br />
		<?php printf(__('The path to the downloads folder is <strong>\'%s\'</strong>.', hacklogdm::textdomain), hacklogdm_admin::get_opt('download_path') ); ?>
</p>
<p style="color: red"><strong><?php _e('WARNING:', 'hacklog-downloadmanager'); ?></strong><br />
		<?php _e('Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.',  hacklogdm::textdomain); ?>
</p>
<p style="color: red"><strong><?php _e('The following WordPress Options/Tables will be DELETED:',  hacklogdm::textdomain); ?></strong><br />
</p>
<table class="widefat">
	<thead>
		<tr>
			<th><?php _e('WordPress Options', hacklogdm::textdomain); ?></th>
			<th><strong><?php _e('WordPress Tables',  hacklogdm::textdomain); ?></strong></th>
		</tr>
	</thead>
	<tr>
		<td valign="top">
		<ol>
		<?php
		foreach($downloads_settings as $settings) {
			echo '<li>'.$settings.'</li>'."\n";
		}
		?>
		</ol>
		</td>
		<td valign="top" class="alternate">
		<ol>
		<?php
		foreach($downloads_tables as $tables) {
			echo '<li>'.$tables.'</li>'."\n";
		}
		?>
		</ol>
		</td>
	</tr>
</table>
<p>&nbsp;</p>
<p style="text-align: center;"><input type="checkbox"
	name="uninstall_download_yes" value="yes" />&nbsp;<?php _e('Yes', hacklogdm::textdomain); ?><br />
<br />
<input type="submit" name="do"
	value="<?php _e('UNINSTALL Hacklog-DownloadManager',  hacklogdm::textdomain); ?>"
	class="button"
	onclick="return confirm('<?php _e('You Are About To Uninstall Hacklog-DownloadManager From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.',  hacklogdm::textdomain); ?>')" />
</p>
</div>
</form>
		<?php
} // End switch($mode)
?>
