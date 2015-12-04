<?php
/**
 * $Id: download-upload-or-add.php 474566 2011-12-13 10:52:27Z ihacklog $
 * $Revision: 474566 $
 * $Date: 2011-12-13 10:52:27 +0000 (Tue, 13 Dec 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 */

/** Load WordPress Administration Bootstrap */
define( 'IFRAME_REQUEST' , true );
$bootstrap_file = dirname(dirname(dirname(dirname(__FILE__)))). '/wp-admin/admin.php' ;
if (file_exists( $bootstrap_file ))
{
	require $bootstrap_file;
}
else
{
	echo '<p>Failed to load bootstrap.</p>';
	exit;
}


/*Check Whether User Can upload_files*/
if (!current_user_can('manage_downloads'))
{
	wp_die(__('You do not have permission to upload files.'));
}

//enqueue the needed media stylesheet
wp_enqueue_style('media');

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

require dirname(__FILE__) . '/includes/hacklogdm_admin.class.php';
$GLOBALS['body_id'] = 'media-upload';
iframe_header( __('Hacklog DownloadManager',hacklogdm::textdomain), false );
?>

	<script type="text/javascript">
					/* <![CDATA[ */
					function insert_into_post(file_id)
					{
					var win = window.dialogArguments || opener || parent || top;
					win.send_to_editor('[download id="'+file_id+'"]'+"\n\r");
					};
					/* ]]> */
	</script>

		<div id="media-upload-header">
			<ul id='sidemenu'>
				<li id='tab-type'><a href='download-upload-or-add.php?tab=upload' <?php if ($_GET['tab'] == 'upload')
	echo "class='current'"; ?>><?php _e('upload a New File', 'hacklog-downloadmanager'); ?></a></li>
				<li id='tab-library'><a href='download-upload-or-add.php?tab=downloads' <?php if ($_GET['tab'] == 'downloads')
			echo "class='current'"; ?>><?php _e('View Downloads', 'hacklog-downloadmanager'); ?></a></li>
			</ul>
		</div>

		<?php
		// Get the Tab
		$tab = hacklogdm_admin::get('tab');
		switch ($tab)
		{
			//上传文件
			case 'upload' :
				//Form Processing		
				require dirname(__FILE__) . '/includes/upload_handler.php';
				?>
				<!-- Add A File -->
		<?php
		hacklogdm_admin::print_upload_form('download-upload-or-add.php?tab=upload');
		?>

				<script type="text/javascript">
					/* <![CDATA[ */
					jQuery('#insertdownload').click(function(){
						var win = window.dialogArguments || opener || parent || top;
						win.send_to_editor('<?php echo isset($GLOBALS['insert_download_shortcode']) ? $GLOBALS['insert_download_shortcode'] : ''; ?>]'+"\n\r");
					});
					/* ]]> */
				</script>

				<?php
				break;

			case 'downloads' :
				// Show table of downloads	
				$base_page = 'download-upload-or-add.php?tab=downloads';
?>
				<?php require dirname(__FILE__). '/includes/download_list.php'; ?>
				<p>&nbsp;</p>

		<?php
		break;
}
?>
				
<?php
iframe_footer();
?>