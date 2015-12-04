<?php
/**
 * $Id: download-add.php 440391 2011-09-19 19:38:29Z ihacklog $
 * $Revision: 440391 $
 * $Date: 2011-09-19 19:38:29 +0000 (Mon, 19 Sep 2011) $
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

require dirname(__FILE__) . '/includes/hacklogdm_admin.class.php';

require dirname(__FILE__) . '/includes/upload_handler.php';
?>
<!-- Add A File -->
<?php
hacklogdm_admin::print_upload_form( admin_url('admin.php?page=' . plugin_basename(__FILE__)) );
?>
