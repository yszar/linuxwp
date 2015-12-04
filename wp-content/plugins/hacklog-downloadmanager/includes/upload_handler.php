<?php

/**
 * $Id: upload_handler.php 440450 2011-09-19 21:35:57Z ihacklog $
 * $Revision: 440450 $
 * $Date: 2011-09-19 21:35:57 +0000 (Mon, 19 Sep 2011) $
 * @filename upload_handler.php 
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng, admin@ihacklog.com> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 * @Description
 */

if ( !defined( 'ABSPATH' ) ) 
{ 
      header( 'HTTP/1.1 403 Forbidden', true, 403 );
      die ('Please do not load this page directly. Thanks!');
}

hacklogdm_admin::check_upload_dir();
$die = 0;

### Form Processing
if (__('Add File', 'hacklog-downloadmanager') == hacklogdm_admin::post('do'))
{
	// Add File
	$file_type = intval(hacklogdm_admin::post('file_type'));
	switch ($file_type)
	{
		// files on server
		case 0:
			$data = hacklogdm_admin::add_server_file();
			break;
		// upload local file to server
		case 1:
			$data = hacklogdm_admin::upload_local_file(hacklogdm_admin::post('file_upload_to'));
			break;
		// add remote file
		case 2:
			$data = hacklogdm_admin::add_remote_file(addslashes(trim(hacklogdm_admin::post('file_remote'))), hacklogdm_admin::post('file_save_to'), hacklogdm_admin::post('save_to_local'));
			break;
	} //end inner switch (add file )

	if (!$data)
	{
		$die = 1;
	}
	else
	{
		// duplicated file check
		if (hacklogdm_admin::check_duplicate_file(hacklogdm_admin::post('file_type'), $data['file'], $data['file_hash']))
		{
			$die = 1;
		}
	}


	if (!$die)
	{
		$do_tab = 0;
		$current_file_base_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF'];
		if (basename($current_file_base_name) == 'download-upload-or-add.php')
		{
			$do_tab = 1;
		}
		hacklogdm_admin::add_new_file($data, $do_tab);
	}
}
?>
<?php hacklogdm_admin::show_message_or_error(); ?>
