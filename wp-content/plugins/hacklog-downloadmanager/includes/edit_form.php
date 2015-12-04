<?php

/**
 * $Id: edit_form.php 441862 2011-09-22 00:07:43Z ihacklog $
 * $Revision: 441862 $
 * $Date: 2011-09-22 00:07:43 +0000 (Thu, 22 Sep 2011) $
 * @filename edit_form.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @datetime Sep 20, 2011  1:02:58 AM
 * @version 1.0
 * @Description
  */
if ( !defined( 'ABSPATH' ) ) 
{ 
      header( 'HTTP/1.1 403 Forbidden', true, 403 );
      die ('Please do not load this page directly. Thanks!');
}

$file_path = hacklogdm_admin::get_opt('download_path');
?>
<form method="post"
	action="<?php echo $ihacklog_action_page; ?>"
	enctype="multipart/form-data"><input type="hidden" name="MAX_FILE_SIZE"
	value="<?php echo hacklogdm_admin::get_max_upload_size(); ?>" /> <input type="hidden"
	name="file_id" value="<?php echo intval($file->file_id); ?>" /> <input
	type="hidden" name="old_file"
	value="<?php echo stripslashes($file->file); ?>" />
<div class="wrap">
<div id="icon-hacklog-downloadmanager" class="icon32"><br />
</div>
<h2><?php _e('Edit A File', hacklogdm::textdomain ); ?></h2>
<table class="form-table">
	<tr>
		<td valign="top"><strong><?php _e('File:', hacklogdm::textdomain ) ?></strong></td>
		<td><!-- File Name --> <input type="radio" id="file_type_-1"
			name="file_type" value="-1" checked="checked" />&nbsp;&nbsp;<label
			for="file_type_-1"><?php _e('Current File:', hacklogdm::textdomain ); ?>&nbsp;<strong
			dir="ltr"><?php echo stripslashes($file->file); ?></strong></label>&nbsp;
		<br />
		<br />
		<!-- Browse File --> <input type="radio" id="file_type_0"
			name="file_type" value="0" />&nbsp;&nbsp;<label for="file_type_0"><?php _e('Local File:', hacklogdm::textdomain ); ?></label>&nbsp;
							<input type="text" readonly="readonly" size="50" name="file" id="hacklogdm-filetree-file"/>
							<input id="hacklogdm-filetree-button" class="button" type="button" value="<?php _e('Browse Files', hacklogdm::textdomain);?>"
									onclick="document.getElementById('file_type_0').checked = true;"
									dir="ltr" />
							<div id="hacklogdm-filetree" style="display:none;">	</div>
									 <br />
									 
		<small><?php printf(__('Please upload the file to \'%s\' directory first.', hacklogdm::textdomain ), $file_path); ?></small>
		<br />
		<br />
		<!-- Upload File --> <input type="radio" id="file_type_1"
			name="file_type" value="1" />&nbsp;&nbsp;<label for="file_type_1"><?php _e('Upload File:', hacklogdm::textdomain ); ?></label>&nbsp;
		<input type="file" name="file_upload" size="25"
			onclick="document.getElementById('file_type_1').checked = true;"
			dir="ltr" />&nbsp;&nbsp;<?php _e('to', hacklogdm::textdomain ); ?>&nbsp;&nbsp;
		<select name="file_upload_to" size="1"
			onclick="document.getElementById('file_type_1').checked = true;"
			dir="ltr">
			<?php hacklogdm_admin::print_list_folders($file_path, $file_path); ?>
		</select> <br />
		<small><?php printf(__('Maximum file size is %s.', hacklogdm::textdomain ), hacklogdm::format_filesize(hacklogdm_admin::get_max_upload_size())); ?></small>
		<!-- Remote File --> <br />
		<br />
		<input type="radio" id="file_type_2" name="file_type" value="2" />&nbsp;&nbsp;<label
			for="file_type_2"><?php _e('Remote File:', hacklogdm::textdomain ); ?></label>&nbsp;
		<input type="text" name="file_remote" size="50" maxlength="255"
			onclick="document.getElementById('file_type_2').checked = true;"
			value="http://" dir="ltr" /> <br />
		<small><?php _e('Please include http:// or ftp:// in front.', hacklogdm::textdomain ); ?></small>
		</td>
	</tr>
	<tr>
		<td><strong><?php _e('File Name:', hacklogdm::textdomain ); ?></strong></td>
		<td><input type="text" size="50" maxlength="200" name="file_name"
			value="<?php echo htmlspecialchars(stripslashes($file->file_name)); ?>" /></td>
	</tr>
	<tr>
		<td valign="top"><strong><?php _e('File Description:', hacklogdm::textdomain ); ?></strong></td>
		<td><textarea rows="5" cols="50" name="file_des"><?php echo htmlspecialchars(stripslashes($file->file_des)); ?></textarea></td>
	</tr>

	<tr>
		<td><strong><?php _e('File Size:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php echo hacklogdm::format_filesize($file->file_size); ?><br />
		<input type="text" size="10" name="file_size"
			value="<?php echo $file->file_size; ?>" />&nbsp;<?php _e('bytes', hacklogdm::textdomain ); ?><br />
		<input type="checkbox" id="auto_filesize" name="auto_filesize"
			value="1" checked="checked" />&nbsp;<label for="auto_filesize"><?php _e('Auto Detection Of File Size', hacklogdm::textdomain ) ?></label></td>
	</tr>
	<tr>
		<td valign="top"><strong><?php _e('File Hits:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php printf(_n('%s hit', '%s hits', number_format_i18n($file->file_hits),hacklogdm::textdomain ), number_format_i18n($file->file_hits)) ?><br />
		<input type="text" size="6" maxlength="10" name="file_hits"
			value="<?php echo $file->file_hits; ?>" /><br />
		<input type="checkbox" id="reset_filehits" name="reset_filehits"
			value="1" />&nbsp;<label for="reset_filehits"><?php _e('Reset File Hits', hacklogdm::textdomain ) ?></label></td>
	</tr>
	<tr>
		<td valign="top"><strong><?php _e('File Date:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php _e('Existing Timestamp:', hacklogdm::textdomain ) ?> <?php echo mysql2date(sprintf('%s @ %s', get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $file->file_date)); ?><br />
		<?php hacklogdm_admin::file_timestamp($file->file_date); ?><br />
		<input type="checkbox" id="edit_filetimestamp"
			name="edit_filetimestamp" value="1" />&nbsp;<label
			for="edit_filetimestamp"><?php _e('Edit Timestamp', hacklogdm::textdomain ) ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
			type="checkbox" id="edit_usetodaydate" value="1"
			onclick="file_usetodaydate();" />&nbsp;<label for="edit_usetodaydate"><?php _e('Use Today\'s Date', hacklogdm::textdomain ) ?></label></td>
	</tr>
	<tr>
		<td valign="top"><strong><?php _e('File Updated Date:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php echo mysql2date(sprintf('%s @ %s', get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $file->file_updated_date)); ?></td>
	</tr>
	<tr>
		<td><strong><?php _e('File Last Downloaded Date:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php echo mysql2date(sprintf('%s @ %s', get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $file->file_last_downloaded_date)); ?></td>
	</tr>
	<tr>
		<td><strong><?php _e('Allowed To Download:', hacklogdm::textdomain ) ?></strong></td>
		<td><select name="file_permission" size="1">
			<option value="-2" <?php selected('-2', $file->file_permission); ?>><?php _e('Hidden', hacklogdm::textdomain ); ?></option>
			<option value="-1" <?php selected('-1', $file->file_permission); ?>><?php _e('Everyone', hacklogdm::textdomain ); ?></option>
			<option value="0" <?php selected('0', $file->file_permission); ?>><?php _e('Registered Users Only', hacklogdm::textdomain ); ?></option>
			<option value="1" <?php selected('1', $file->file_permission); ?>><?php _e('At Least Contributor Role', hacklogdm::textdomain ); ?></option>
			<option value="2" <?php selected('2', $file->file_permission); ?>><?php _e('At Least Author Role', hacklogdm::textdomain ); ?></option>
			<option value="7" <?php selected('7', $file->file_permission); ?>><?php _e('At Least Editor Role', hacklogdm::textdomain ); ?></option>
			<option value="10" <?php selected('10', $file->file_permission); ?>><?php _e('At Least Administrator Role', hacklogdm::textdomain ); ?></option>
		</select></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="do"
			value="<?php _e('Edit File', hacklogdm::textdomain ); ?>"
			class="button" />&nbsp;&nbsp;<input type="button" name="cancel"
			value="<?php _e('Cancel', hacklogdm::textdomain ); ?>"
			class="button" onclick="javascript:history.go(-1)" /></td>
	</tr>
</table>
</div>
</form>
