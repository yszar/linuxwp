<?php
/**
 * $Id: download-manager.php 449173 2011-10-09 18:40:00Z ihacklog $
 * $Revision: 449173 $
 * $Date: 2011-10-09 18:40:00 +0000 (Sun, 09 Oct 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 */
### Check Whether User Can Manage Downloads
if (!current_user_can('manage_downloads'))
{
	wp_die('Access Denied');
}

//load the admin class
require dirname(__FILE__) . '/includes/hacklogdm_admin.class.php';

//variables 
$base_name = plugin_basename('hacklog-downloadmanager/download-manager.php');
$base_page = 'admin.php?page=' . $base_name;
$mode = trim(hacklogdm_admin::get('mode'));
$file_id = intval(hacklogdm_admin::get('id', 0));

$die = 0;


//Form Processing
if (isset($_POST['do']))
{
	// Decide What To Do
	switch (hacklogdm_admin::post('do'))
	{
/*************************************************************************************************
 * action update
 **************************************************************************************************/			
		case __('Edit File', hacklogdm::textdomain):
			$file_size_sql = '';
			$file_sql = '';
			$file_id = intval(hacklogdm_admin::post('file_id', 0));
			$file_type = intval(hacklogdm_admin::post('file_type', -1));
			//the variable to use
			$file_name = addslashes(trim(hacklogdm_admin::post('file_name')));
			$file_path = hacklogdm_admin::get_opt('download_path');
			switch ($file_type)
			{
				// edit orignal file
				case -1:
					$file = hacklogdm_admin::post('old_file');
					if (hacklogdm::is_remote_file($file))
					{
						$file_size = hacklogdm_admin::remote_filesize($file);
						$file_hash = 'N/A';
					}
					else
					{
						$file_size = filesize($file_path . $file);
						$file_hash = hacklogdm_admin::get_file_hash($file_path . $file);
					}
					break;
				// use server file
				case 0:
					$file = addslashes(trim(hacklogdm_admin::post('file')));
					$file = hacklogdm_admin::download_rename_file($file_path, $file);
					$file_size = filesize($file_path . $file);
					$file_hash = hacklogdm_admin::get_file_hash($file_path . $file);
					break;
				// upload local file	
				case 1:
					//do edit upload
					$data = hacklogdm_admin::upload_local_file(hacklogdm_admin::post('file_upload_to'), 1);
					if (!$data)
					{
						$die = 1;
					}
					else
					{
						$file_name = $data['file_name'];
						$file = $data['file'];
						$file_size = $data['file_size'];
						$file_hash = $data['file_hash'];
					}
					break;
				// use remote file
				case 2:
					$file = addslashes(trim(hacklogdm_admin::post('file_remote')));
					if (!hacklogdm::is_remote_file($file))
					{
						hacklogdm_admin::add_error(__('Error: Please give me a valid URL.', hacklogdm::textdomain));
						$die = 1;
					}
					else
					{
						$file_name = hacklogdm::get_basename($file);
						$file_size = hacklogdm_admin::remote_filesize($file);
						$file_hash = 'N/A';
					}
					break;
			}
			if (!$die)
			{
				if ($file_type > -1)
				{
					$file_sql = "file = '$file',";
					if (empty($file_name) && isset($_POST['file_name']) && !empty($_POST['file_name']))
					{
						$file_name = addslashes(trim($_POST['file_name']));
					}
				}
				$file_des = addslashes(trim(hacklogdm_admin::post('file_des')));
				$file_hits = intval(hacklogdm_admin::post('file_hits'));
				$edit_filetimestamp = intval(hacklogdm_admin::post('edit_filetimestamp'));
				if (intval(hacklogdm_admin::post('auto_filesize', 0)) != 1)
				{
					$file_size = intval(hacklogdm_admin::post('file_size'));
				}
				$file_size_sql = "file_size = '$file_size',";
				$reset_filehits = intval(hacklogdm_admin::post('reset_filehits'));
				$hits_sql = '';
				if ($reset_filehits == 1)
				{
					$hits_sql = ', file_hits = 0';
				}
				else
				{
					$hits_sql = ", file_hits = $file_hits";
				}
				$timestamp_sql = '';
				if ($edit_filetimestamp == 1)
				{
					$file_timestamp_day = intval(hacklogdm_admin::post('file_timestamp_day'));
					$file_timestamp_month = intval(hacklogdm_admin::post('file_timestamp_month'));
					$file_timestamp_year = intval(hacklogdm_admin::post('file_timestamp_year'));
					$file_timestamp_hour = intval(hacklogdm_admin::post('file_timestamp_hour'));
					$file_timestamp_minute = intval(hacklogdm_admin::post('file_timestamp_minute'));
					$file_timestamp_second = intval(hacklogdm_admin::post('file_timestamp_second'));
					$timestamp_sql = ", file_date = '" . gmmktime($file_timestamp_hour, $file_timestamp_minute, $file_timestamp_second, $file_timestamp_month, $file_timestamp_day, $file_timestamp_year) . "'";
				}
				$file_permission = intval(hacklogdm_admin::post('file_permission'));
				$file_updated_date = current_time('timestamp');
				$editfile = $wpdb->query("UPDATE $wpdb->downloads SET $file_sql file_name = '$file_name', file_des = '$file_des', file_hash = '$file_hash', $file_size_sql file_permission = $file_permission, file_updated_date = '$file_updated_date' $timestamp_sql $hits_sql WHERE file_id = $file_id;");
				if (!$editfile)
				{
					hacklogdm_admin::add_error(sprintf(__('Error In Editing File \'%s (%s)\'', hacklogdm::textdomain), $file_name, $file));
				}
				else
				{
					hacklogdm_admin::add_message(sprintf(__('File \'%s (%s)\' Edited Successfully', hacklogdm::textdomain), $file_name, $file));
				}
			}
			break;
/*************************************************************************************************
 * action delete
 **************************************************************************************************/	
		case __('Delete File', hacklogdm::textdomain);
			hacklogdm_admin::delete_file();
			break;
	}
}

// Determines Which Mode It Is
switch ($mode)
{
	
/*************************************************************************************************
 * Edit A File
 **************************************************************************************************/		
	case 'edit':
		// check the  file_id to see if the file exists.
		if (!hacklogdm_admin::id_exists($file_id))
		{
			hacklogdm_admin::add_error(__('Error file_id!File id does not exists.', hacklogdm::textdomain));
			hacklogdm_admin::add_block_error('<p><a href="' . $_SERVER['HTTP_REFERER'] . '" >' . __('Return', hacklogdm::textdomain) . '</a></p>');
			hacklogdm_admin::show_message_or_error();
			exit();
		}
		$file = $wpdb->get_row("SELECT * FROM $wpdb->downloads WHERE file_id = $file_id");
		?>
		<script type="text/javascript">
			/* <![CDATA[*/
			var actual_day = "<?php echo gmdate('j', $file->file_date); ?>";
			var actual_month = "<?php echo gmdate('n', $file->file_date); ?>";
			var actual_year = "<?php echo gmdate('Y', $file->file_date); ?>";
			var actual_hour = "<?php echo gmdate('G', $file->file_date); ?>";
			var actual_minute = "<?php echo intval(gmdate('i', $file->file_date)); ?>";
			var actual_second = "<?php echo intval(gmdate('s', $file->file_date)); ?>";
			function file_usetodaydate() {
				if(jQuery('#edit_usetodaydate').is(':checked')) {
					jQuery('#edit_filetimestamp').attr('checked', true);
					jQuery('#file_timestamp_day').val("<?php echo gmdate('j', current_time('timestamp')); ?>");
					jQuery('#file_timestamp_month').val("<?php echo gmdate('n', current_time('timestamp')); ?>");
					jQuery('#file_timestamp_year').val("<?php echo gmdate('Y', current_time('timestamp')); ?>");
					jQuery('#file_timestamp_hour').val("<?php echo gmdate('G', current_time('timestamp')); ?>");
					jQuery('#file_timestamp_minute').val("<?php echo intval(gmdate('i', current_time('timestamp'))); ?>");
					jQuery('#file_timestamp_second').val("<?php echo intval(gmdate('s', current_time('timestamp'))); ?>");
				} else {
					jQuery('#edit_filetimestamp').attr('checked', false);
					jQuery('#file_timestamp_day').val(actual_day);
					jQuery('#file_timestamp_month').val(actual_month);
					jQuery('#file_timestamp_year').val(actual_year);
					jQuery('#file_timestamp_hour').val(actual_hour);
					jQuery('#file_timestamp_minute').val(actual_minute);
					jQuery('#file_timestamp_second').val(actual_second);
				}
			}
			/* ]]> */
		</script>
		<?php hacklogdm_admin::show_message_or_error(); ?>
		<!-- Edit A File -->
		<?php
		$ihacklog_action_page = admin_url('admin.php?page=' . plugin_basename(__FILE__) . '&amp;mode=edit&amp;id=' . intval($file->file_id));
		require dirname(__FILE__) . '/includes/edit_form.php';
		break;
		
/*************************************************************************************************
 * Delete A File
 **************************************************************************************************/		

	case 'delete':
		if (!hacklogdm_admin::id_exists($file_id))
		{
			hacklogdm_admin::add_error(__('Error file_id!File id does not exists.', hacklogdm::textdomain));
			hacklogdm_admin::add_block_error('<p><a href="' . $_SERVER['HTTP_REFERER'] . '" >' . __('Return', hacklogdm::textdomain) . '</a></p>');
			hacklogdm_admin::show_message_or_error();
			exit();
		}
		$file = $wpdb->get_row("SELECT * FROM $wpdb->downloads WHERE file_id = $file_id");
		?>
		<?php hacklogdm_admin::show_message_or_error(); ?>
		<!-- Delete A File -->
		<?php
		$ihacklog_action_page = admin_url('admin.php?page=' . plugin_basename(__FILE__));
		require dirname(__FILE__) . '/includes/delete_form.php';
		break;

/*************************************************************************************************
 *  Main Page.list the files
 **************************************************************************************************/	
	default:
		### Get Total Files
		$total_file = $wpdb->get_var("SELECT COUNT(file_id) FROM $wpdb->downloads WHERE 1=1");
		$total_bandwidth = $wpdb->get_var("SELECT SUM(file_hits*file_size) AS total_bandwidth FROM $wpdb->downloads WHERE file_size != '" . __('unknown', hacklogdm::textdomain) . "'");
		$total_filesize = $wpdb->get_var("SELECT SUM(file_size) AS total_filesize FROM $wpdb->downloads WHERE file_size != '" . __('unknown', hacklogdm::textdomain) . "'");
		$total_filehits = $wpdb->get_var("SELECT SUM(file_hits) AS total_filehits FROM $wpdb->downloads");
		?>
		<?php hacklogdm_admin::show_message_or_error(); ?>
		<!-- Manage Downloads -->
		<?php
		$download_list_action = admin_url('admin.php?page=' . plugin_basename(__FILE__));
		require dirname(__FILE__) . '/includes/download_list.php';
		?>
		<p>&nbsp;</p>

		<!-- Download Stats -->
		<div class="wrap">
			<h3><?php _e('Download Stats', hacklogdm::textdomain); ?></h3>
			<br style="" />
			<table class="widefat">
				<tr>
					<th><?php _e('Total Files:', hacklogdm::textdomain); ?></th>
					<td><?php echo number_format_i18n($total_file); ?></td>
				</tr>
				<tr class="alternate">
					<th><?php _e('Total Size:', hacklogdm::textdomain); ?></th>
					<td><?php echo hacklogdm::format_filesize($total_filesize); ?></td>
				</tr>
				<tr>
					<th><?php _e('Total Hits:', hacklogdm::textdomain); ?></th>
					<td><?php echo number_format_i18n($total_filehits); ?></td>
				</tr>
				<tr class="alternate">
					<th><?php _e('Total Bandwidth:', hacklogdm::textdomain); ?></th>
					<td><?php echo hacklogdm::format_filesize($total_bandwidth); ?></td>
				</tr>
			</table>
		</div>
	<?php
} // End switch($mode)
?>
