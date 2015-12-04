<?php

/**
 * $Id: delete_form.php 440477 2011-09-19 22:19:18Z ihacklog $
 * $Revision: 440477 $
 * $Date: 2011-09-19 22:19:18 +0000 (Mon, 19 Sep 2011) $
 * @filename delete_form.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @datetime Sep 20, 2011  12:59:17 AM
 * @version 1.0
 * @Description
  */
if ( !defined( 'ABSPATH' ) ) 
{ 
      header( 'HTTP/1.1 403 Forbidden', true, 403 );
      die ('Please do not load this page directly. Thanks!');
}
?>
<form method="post"
	action="<?php echo $ihacklog_action_page ?>">
<input type="hidden" name="file_id"
	value="<?php echo intval($file->file_id); ?>" /> <input type="hidden"
	name="file" value="<?php echo stripslashes($file->file); ?>" /> <input
	type="hidden" name="file_name"
	value="<?php echo htmlspecialchars(stripslashes($file->file_name)); ?>" />
<div class="wrap">
<div id="icon-hacklog-downloadmanager" class="icon32"><br />
</div>
<h2><?php _e('Delete A File', hacklogdm::textdomain ); ?></h2>
<br style="" />
<table class="widefat">
	<tr>
		<td valign="top"><strong><?php _e('File:', hacklogdm::textdomain ) ?></strong></td>
		<td><span dir="ltr"><?php echo stripslashes($file->file); ?></span></td>
	</tr>
	<tr class="alternate">
		<td><strong><?php _e('File Name:', hacklogdm::textdomain ); ?></strong></td>
		<td><?php echo stripslashes($file->file_name); ?></td>
	</tr>
	<tr>
		<td valign="top"><strong><?php _e('File Description:', hacklogdm::textdomain ); ?></strong></td>
		<td><?php echo stripslashes($file->file_des); ?></td>
	</tr>
	<tr>
		<td><strong><?php _e('File Size:', hacklogdm::textdomain ); ?></strong></td>
		<td><?php echo hacklogdm::format_filesize($file->file_size); ?></td>
	</tr>
	<tr class="alternate">
		<td><strong><?php _e('File Hits', hacklogdm::textdomain ); ?></strong></td>
		<td><?php echo number_format_i18n($file->file_hits); ?> <?php _e('hits', hacklogdm::textdomain ); ?></td>
	</tr>
	<tr>
		<td><strong><?php _e('File Date', hacklogdm::textdomain ); ?></strong></td>
		<td><?php echo mysql2date(sprintf('%s @ %s', get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $file->file_date)); ?></td>
	</tr>
	<tr class="alternate">
		<td><strong><?php _e('File Updated Date:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php echo mysql2date(sprintf('%s @ %s', get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $file->file_updated_date)); ?></td>
	</tr>
	<tr>
		<td><strong><?php _e('File Last Downloaded Date:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php echo mysql2date(sprintf('%s @ %s', get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $file->file_last_downloaded_date)); ?></td>
	</tr>
	<tr class="alternate">
		<td><strong><?php _e('Allowed To Download:', hacklogdm::textdomain ) ?></strong></td>
		<td><?php echo hacklogdm_admin::file_permission($file->file_permission); ?></td>
	</tr>
	<?php if(!hacklogdm::is_remote_file(stripslashes($file->file))): ?>
	<tr>
		<td colspan="2" align="center"><input type="checkbox" id="unlinkfile" name="unlinkfile" value="1" />&nbsp;<label for="unlinkfile"><?php _e('Delete File From Server?', hacklogdm::textdomain ); ?></label></td>
	</tr>
	<?php endif; ?>
	<tr class="alternate">
		<td colspan="2" align="center"><input type="submit" name="do"
			value="<?php _e('Delete File', hacklogdm::textdomain ); ?>"
			class="button"
			onclick="return confirm('<?php echo sprintf( __("You Are About To The Delete This File \\'%s(%s)\\'.\\nThis Action Is Not Reversible.\\n\\n Choose \\'Cancel\\' to stop, \\'OK\\' to delete.",hacklogdm::textdomain),stripslashes(strip_tags($file->file_name)),stripslashes($file->file) ) ;?> ');" />&nbsp;&nbsp;<input
			type="button" name="cancel"
			value="<?php _e('Cancel', hacklogdm::textdomain ); ?>"
			class="button" onclick="javascript:history.go(-1)" /></td>
	</tr>
</table>
</div>
</form>
