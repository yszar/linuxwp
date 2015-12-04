<?php
/**
 * $Id: download-options.php 449165 2011-10-09 18:15:58Z ihacklog $
 * $Revision: 449165 $
 * $Date: 2011-10-09 18:15:58 +0000 (Sun, 09 Oct 2011) $
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
	die('Access Denied');
}

//load the admin class
require dirname(__FILE__) . '/includes/hacklogdm_admin.class.php';

### Variables Variables Variables
$base_name = plugin_basename('hacklog-downloadmanager/download-manager.php');
$base_page = 'admin.php?page=' . $base_name;

global $wp_rewrite;
### If Form Is Submitted
if (isset($_POST['Submit']))
{
	$download_path = trim(hacklogdm_admin::post('download_path'));
	$download_path_url = trim(hacklogdm_admin::post('download_path_url'));
	$download_options_nice_permalink = intval(hacklogdm_admin::post('download_options_nice_permalink'));
	$download_options_use_filename = intval(hacklogdm_admin::post('download_options_use_filename'));
	$download_options_download_slug = trim(hacklogdm_admin::post('download_options_download_slug'));
	$download_options_time_limit = trim(hacklogdm_admin::post('download_options_time_limit')) * 60;
	$download_options_hash_func = trim(hacklogdm_admin::post('download_options_hash_func'));
	$download_options_check_referer = trim(hacklogdm_admin::post('download_options_check_referer'));
	$download_method = intval(hacklogdm_admin::post('download_method'));
	$download_display_type = intval($_POST['download_display_type']);
	$download_template_custom_css = trim( hacklogdm_admin::post('download_template_custom_css') );
	$download_template_popup [] = trim(hacklogdm_admin::post('download_template_popup'));
	$download_template_popup [] = trim(hacklogdm_admin::post('download_template_popup_2'));
	$download_template_embedded [] = trim(hacklogdm_admin::post('download_template_embedded'));
	$download_template_embedded [] = trim(hacklogdm_admin::post('download_template_embedded_2'));

	$download_options = array(
		'use_filename' => $download_options_use_filename,
		'download_slug' => $download_options_download_slug,
		'nice_permalink' => $download_options_nice_permalink,
		'time_limit' => $download_options_time_limit,
		'hash_func' => $download_options_hash_func,
		'check_referer' => $download_options_check_referer
	);
	$update_download_queries = array();
	$update_download_text = array();

	if (is_dir($download_path))
	{
		$update_download_queries [] = update_option('download_path', untrailingslashit($download_path));
	}
	else
	{// if the site has moved to another SERVER and the dir is not exists anymore ... 
		if (function_exists('is_site_admin'))
		{
			global $blog_id;
			$update_download_queries [] = update_option('download_path', str_replace("\\", '/', WP_CONTENT_DIR) . '/blogs.dir/' . $blog_id . '/files');
		}
		else
		{
			$update_download_queries [] = update_option('download_path', str_replace("\\", '/', WP_CONTENT_DIR) . '/files');
		}
	}
	$update_download_queries [] = update_option('download_path_url', untrailingslashit($download_path_url));
	$update_download_queries [] = update_option('download_options', $download_options);
	if (1 == $download_options ['nice_permalink'])
	{
		$permalink_structure = get_option('permalink_structure');
		if ($permalink_structure)
		{
			$wp_rewrite->flush_rules(false);
		}
	}
	$update_download_queries [] = update_option('download_method', $download_method);
	//flush the rewrite rules
	if (intval(hacklogdm_admin::get_opt('download_method')) != intval($download_method))
	{
		flush_rewrite_rules();
	}
	$update_download_queries [] = update_option('download_display_type', $download_display_type);
	$update_download_queries [] = update_option('download_template_custom_css', $download_template_custom_css);

	$update_download_queries [] = update_option('download_template_popup', $download_template_popup);
	$update_download_queries [] = update_option('download_template_embedded', $download_template_embedded);

	$update_download_text [] = __('Download Path', hacklogdm::textdomain);
	$update_download_text [] = __('Download Path URL', hacklogdm::textdomain);
	$update_download_text [] = __('Download Options(Use filename or Not、Download slug、Use Nice Permalink or Not、Time Limit、Hash Function、Check HTTP Referer)', hacklogdm::textdomain);
	$update_download_text [] = __('Download Method', hacklogdm::textdomain);
	$update_download_text [] = __('Download Display Type', hacklogdm::textdomain);
	$update_download_text [] = __('Download Custom CSS', hacklogdm::textdomain);
	$update_download_text [] = __('Download Popup Template', hacklogdm::textdomain);
	$update_download_text [] = __('Download Embedded Template', hacklogdm::textdomain);
	$i = 0;
	foreach ($update_download_queries as $update_download_query)
	{
		if ($update_download_query)
		{
			hacklogdm_admin::add_message($update_download_text [$i] . ' ' . __('Updated', hacklogdm::textdomain));
		}
		$i++;
	}
}


### Get Download Options
$download_options = hacklogdm_admin::get_opt('download_options');

$download_template_custom_css = hacklogdm_admin::get_opt('download_template_custom_css');
### Get File Download Method
$download_method = intval(hacklogdm_admin::get_opt('download_method'));
//display style 0 :embedded 1: popup
$download_display_type = intval(hacklogdm_admin::get_opt('download_display_type'));

$download_template_popup = hacklogdm_admin::get_opt('download_template_popup');
$download_template_embedded = hacklogdm_admin::get_opt('download_template_embedded');
?>
<?php
hacklogdm_admin::show_message_or_error();
?>
<form method="post"
	  action="<?php
echo $_SERVER ['PHP_SELF'];
?>?page=<?php
echo plugin_basename(__FILE__);
?>">
	<div class="wrap">
		<div id="icon-hacklog-downloadmanager" class="icon32"><br />
		</div>
		<h2><?php
			_e('Download Options', hacklogdm::textdomain);
?></h2>
		<h3><?php
			_e('Download Options', hacklogdm::textdomain);
?></h3>
		<table class="form-table">
			<tr valign="top">
				<th><?php
						   _e('Download Path:', hacklogdm::textdomain);
?></th>
				<td><input type="text" name="download_path"
						   value="<?php
						   echo stripslashes(hacklogdm_admin::get_opt('download_path'));
?>"
						   size="50" dir="ltr" /><br />
						   <?php
						   _e('The absolute path to the directory where all the files are stored (without trailing slash).', hacklogdm::textdomain);
						   ?></td>
			</tr>
			<tr valign="top">
				<th><?php
						   _e('Download Path URL:', hacklogdm::textdomain);
						   ?></th>
				<td><input type="text" name="download_path_url"
						   value="<?php
						   echo stripslashes(hacklogdm_admin::get_opt('download_path_url'));
						   ?>"
						   size="50" dir="ltr" /><br />
<?php
_e('The url to the directory where all the files are stored (without trailing slash).', hacklogdm::textdomain);
?></td>
			</tr>

			<tr valign="top">
				<th><?php
						   _e('Use File Name Or File ID In Download URL?', hacklogdm::textdomain);
?></th>
				<td><input type="radio" id="download_options_use_filename-0"
						   name="download_options_use_filename" value="0"
							<?php
							checked('0', $download_options ['use_filename']);
							?>>&nbsp;<label
						   for="download_options_use_filename-0"><?php
							_e('File ID', hacklogdm::textdomain);
							?><br />
						<span dir="ltr">- <?php
							echo get_option('home');
							?>/<?php
						   echo stripslashes($download_options ['download_slug']);
							?>/1/</span><br />
						<span dir="ltr">- <?php
						   echo get_option('home');
							?>/?dl_id=1</span></label>
					<br />
					<input type="radio" id="download_options_use_filename-1"
						   name="download_options_use_filename" value="1"
							<?php
							checked('1', $download_options ['use_filename']);
							?>>&nbsp;<label
						   for="download_options_use_filename-1"><?php
							_e('File Name', hacklogdm::textdomain);
							?><br />
						<span dir="ltr">- <?php
							echo get_option('home');
							?>/<?php
					echo stripslashes($download_options ['download_slug']);
							?>/filename.ext</span><br />
						<span dir="ltr">- <?php
					echo get_option('home');
							?>/?dl_name=filename.ext</span></label>
					<br />
					<?php
					_e('Change it to <strong>File ID</strong> when you encounter 404 error.', hacklogdm::textdomain);
					?>
				</td>
			</tr>

			<tr valign="top">
				<th><?php
					_e('Download Nice Permalink:', hacklogdm::textdomain);
					?></th>
				<td><input type="radio" id="download_options_nice_permalink-1"
						   name="download_options_nice_permalink" value="1"
							<?php
							checked('1', $download_options ['nice_permalink']);
							?>>&nbsp;<label
						   for="download_options_nice_permalink-1"><?php
							_e('Yes', hacklogdm::textdomain);
							?><br />
						<span dir="ltr">- <?php
							echo get_option('home');
							?>/<?php
							echo stripslashes($download_options ['download_slug']);
							?>/1/</span><br />
						<span dir="ltr">- <?php
						   echo get_option('home');
							?>/<?php
						   echo stripslashes($download_options ['download_slug']);
							?>/filename.ext</span></label>
					<br />
					<input type="radio" id="download_options_nice_permalink-0"
						   name="download_options_nice_permalink" value="0"
							<?php
							checked('0', $download_options ['nice_permalink']);
							?>>&nbsp;<label
						   for="download_options_nice_permalink-0"><?php
							_e('No', hacklogdm::textdomain);
							?><br />
						<span dir="ltr">- <?php
					echo get_option('home');
							?>/?dl_id=1</span><br />
						<span dir="ltr">- <?php
					echo get_option('home');
							?>/?dl_name=filename.ext</span></label>
					<br />
					<?php
					_e('Change it to <strong>No</strong> when you encounter 404 error.', hacklogdm::textdomain);
					?>
				</td>
			</tr>

			<tr valign="top">
				<th><?php
					_e('Download Slug:', hacklogdm::textdomain);
					?></th>
				<td><input type="text" name="download_options_download_slug" size="30"
						   maxlength="50"
						   value="<?php
					echo stripslashes($download_options ['download_slug']);
					?>">
					<br />
					<?php
					_e('This only affects when you have enabled <strong>Download Nice Permalink</strong> .', hacklogdm::textdomain);
					?>
				</td>
			</tr>

			<tr valign="top">
				<th><?php
					_e('Remote Download Time Limit:', hacklogdm::textdomain);
					?></th>
				<td><input type="text" name="download_options_time_limit" size="30"
						   maxlength="50"
						   value="<?php
					echo stripslashes($download_options ['time_limit']) / 60;
					?>"><?php
					_e('Minutes', hacklogdm::textdomain);
					?>
					<br />
					<?php
					_e('This only affects when the plugin are downloading <strong>Remote File</strong> to your local server.', hacklogdm::textdomain);
					?>
				</td>
			</tr>

			<tr valign="top">
				<th><?php
					_e('Hash Function:', hacklogdm::textdomain);
					?></th>
				<td><select name="download_options_hash_func" size="1">
						<option value="md5"
<?php
selected('md5', $download_options ['hash_func']);
?>>MD5</option>
						<option value="sha1"
					<?php
					selected('sha1', $download_options ['hash_func']);
					?>>SHA1</option>
					</select></td>
			</tr>

			<tr valign="top">
				<th><?php
					_e('Download Method:', hacklogdm::textdomain);
					?></th>
				<td><select name="download_method" size="1">
						<option value="0" <?php
								selected('0', $download_method);
					?>><?php
					_e('Output File', hacklogdm::textdomain);
					?></option>
						<option value="1" <?php
					selected('1', $download_method);
					?>><?php
					_e('Redirect To File', hacklogdm::textdomain);
					?></option>
					</select> <br />
					<?php
					_e('Change it to <strong>Redirect To File</strong> when you have problem with large files.', hacklogdm::textdomain);
					?>
				</td>
			</tr>

			<tr valign="top">
				<th><?php
					_e('Check HTTP referer:', hacklogdm::textdomain);
					?></th>
				<td><select name="download_options_check_referer" size="1">
						<option value="0"
									<?php
									selected('0', $download_options ['check_referer']);
									?>><?php
									_e('Not enabled', hacklogdm::textdomain);
									?></option>
						<option value="1"
<?php
selected('1', $download_options ['check_referer']);
?>><?php
_e('Enabled', hacklogdm::textdomain);
?></option>
					</select> <br />
					<?php
					_e('<strong>Enable this could save your a lot of bandwidth.</strong>', hacklogdm::textdomain);
					?>
				</td>
			</tr>


			<tr valign="top">
				<th><?php
					_e('Download display type:', hacklogdm::textdomain);
					?></th>
				<td><select name="download_display_type" size="1" id="download_display_type" onchange="set_template_table(this.value);">
						<option value="0"
									<?php
									selected('0', $download_display_type);
									?>><?php
									_e('Embeded', hacklogdm::textdomain);
									?></option>
						<option value="1"
<?php
selected('1', $download_display_type);
?>><?php
_e('Popup', hacklogdm::textdomain);
?></option>
					</select> <br />

				</td>
			</tr>


			<tr valign="top">
				<th><?php
					_e('Download Templates Custom CSS', hacklogdm::textdomain);
?>
					<input type="button" name="RestoreDefault"
						   value="<?php
						_e('Restore Default CSS', hacklogdm::textdomain);
?>"
						   onclick="download_default_templates('custom_css');" class="button" />
				</th>
				<td>	
					<textarea name="download_template_custom_css" id="download_template_custom_css" cols="80" rows="10">
			<?php echo htmlspecialchars(stripslashes($download_template_custom_css)); ?>
					</textarea>

				</td>
			</tr>

		</table>

		<h3><?php
			_e('Download Templates (With Permission)', hacklogdm::textdomain);
			?></h3>
		<table class="form-table" id="table_download_template_embedded">
			<tr valign="top">
				<td width="30%"><strong><?php
			_e('Download Template', hacklogdm::textdomain);
			?></strong><br />
<?php
_e('Displayed when you embedded a file within a post or a page and users have permission to download the file.', hacklogdm::textdomain);
?><br />
					<br />
<?php
_e('Allowed Variables:', hacklogdm::textdomain);
?><br />
					- %FILE_ID%<br />
					- %FILE%<br />
					- %FILE_ICON%<br />
					- %FILE_NAME%<br />
					- %FILE_DESCRIPTION%<br />
					- %FILE_HASH%<br />
					- %FILE_SIZE%<br />
					- %FILE_DATE%<br />
					- %FILE_TIME%<br />
					- %FILE_UPDATED_DATE%<br />
					- %FILE_UPDATED_TIME%<br />
					- %FILE_HITS%<br />
					- %FILE_DOWNLOAD_URL%<br />
					<br />
					<input type="button" name="RestoreDefault"
						   value="<?php
_e('Restore Default Template', hacklogdm::textdomain);
?>"
						   onclick="download_default_templates('embedded');" class="button" /></td>
				<td><textarea cols="80" rows="20" id="download_template_embedded"
							  name="download_template_embedded"><?php
echo htmlspecialchars(stripslashes($download_template_embedded [0]));
?></textarea></td>
			</tr>
		</table>


		<table class="form-table" id="table_download_template_popup" style="display:none;">
			<tr valign="top">
				<td width="30%"><strong><?php
						_e('Download popup div template', hacklogdm::textdomain);
?></strong><br />
<?php
_e('Displayed the download info in a popup window when you embedded a file within a post or a page and users have permission to download the file.', hacklogdm::textdomain);
?><br />
					<br />
<?php
_e('Allowed Variables:', hacklogdm::textdomain);
?><br />
					- %FILE_ID%<br />
					- %FILE%<br />
					- %FILE_ICON%<br />
					- %FILE_NAME%<br />
					- %FILE_DESCRIPTION%<br />
					- %FILE_HASH%<br />
					- %FILE_SIZE%<br />
					- %FILE_DATE%<br />
					- %FILE_TIME%<br />
					- %FILE_UPDATED_DATE%<br />
					- %FILE_UPDATED_TIME%<br />
					- %FILE_HITS%<br />
					- %FILE_DOWNLOAD_URL%<br />
					<br />
					<input type="button" name="RestoreDefault"
						   value="<?php
_e('Restore Default Template', hacklogdm::textdomain);
?>"
						   onclick="download_default_templates('popup');" class="button" /></td>
				<td><textarea cols="80" rows="20" id="download_template_popup"
							  name="download_template_popup"><?php
echo htmlspecialchars(stripslashes($download_template_popup [0]));
?></textarea></td>
			</tr>
		</table>





		<h3><?php
			_e('Download Templates (Without Permission)', hacklogdm::textdomain);
?></h3>
		<table class="form-table" id="table_download_template_embedded_2">

			<tr valign="top">
				<td width="30%"><strong><?php
						_e('Download Template', hacklogdm::textdomain);
?></strong><br />
<?php
_e('Displayed when you embedded a file within a post or a page and users <strong>DO NOT</strong> have permission to download the file.', hacklogdm::textdomain);
?><br />
					<br />
<?php
_e('Allowed Variables:', hacklogdm::textdomain);
?><br />
					- %FILE_ID%<br />
					- %FILE%<br />
					- %FILE_ICON%<br />
					- %FILE_NAME%<br />
					- %FILE_DESCRIPTION%<br />
					- %FILE_HASH%<br />
					- %FILE_SIZE%<br />
					- %FILE_DATE%<br />
					- %FILE_TIME%<br />
					- %FILE_UPDATED_DATE%<br />
					- %FILE_UPDATED_TIME%<br />
					- %FILE_HITS%<br />
					- %FILE_DOWNLOAD_URL%<br />
					<br />
					<input type="button" name="RestoreDefault"
						   value="<?php
_e('Restore Default Template', hacklogdm::textdomain);
?>"
						   onclick="download_default_templates('embedded_2');" class="button" />
				</td>
				<td><textarea cols="80" rows="20" id="download_template_embedded_2"
							  name="download_template_embedded_2"><?php
echo htmlspecialchars(stripslashes($download_template_embedded [1]));
?></textarea></td>
			</tr>

		</table>



		<table class="form-table" id="table_download_template_popup_2"  style="display:none;">

			<tr valign="top">
				<td width="30%"><strong><?php
_e('Download popup div template', hacklogdm::textdomain);
?></strong><br />
<?php
_e('Displayed when you embedded a file within a post or a page and users <strong>DO NOT</strong> have permission to download the file.', hacklogdm::textdomain);
?><br />
					<br />
<?php
_e('Allowed Variables:', hacklogdm::textdomain);
?><br />
					- %FILE_ID%<br />
					- %FILE%<br />
					- %FILE_ICON%<br />
					- %FILE_NAME%<br />
					- %FILE_DESCRIPTION%<br />
					- %FILE_HASH%<br />
					- %FILE_SIZE%<br />
					- %FILE_DATE%<br />
					- %FILE_TIME%<br />
					- %FILE_UPDATED_DATE%<br />
					- %FILE_UPDATED_TIME%<br />
					- %FILE_HITS%<br />
					- %FILE_DOWNLOAD_URL%<br />
					<br />
					<input type="button" name="RestoreDefault"
						   value="<?php
_e('Restore Default Template', hacklogdm::textdomain);
?>"
						   onclick="download_default_templates('popup_2');" class="button" />
				</td>
				<td><textarea cols="80" rows="20" id="download_template_popup_2"
							  name="download_template_popup_2"><?php
echo htmlspecialchars(stripslashes($download_template_popup [1]));
?></textarea></td>
			</tr>

		</table>


		<p class="submit" align="center"><input type="submit" name="Submit"
												class="button"
												value="<?php
												_e('Save Changes', hacklogdm::textdomain);
?>" /></p>
	</div>
</form>
<?php
$download_template_embedded_default = hacklogdm::get_default_value('download_template_embedded');
$download_template_custom_css_default = hacklogdm::get_default_value('download_template_custom_css');
$download_template_popup_default = hacklogdm::get_default_value('download_template_popup');
?>
<script type="text/javascript">
	/* <![CDATA[*/
	function download_default_templates(template) {
		var default_template;
		switch(template) {
			case "embedded":
				default_template = "<?php echo hacklogdm_admin::js_fix($download_template_embedded_default[0]); ?>";
				break;
			case "embedded_2":
				default_template = "<?php echo hacklogdm_admin::js_fix($download_template_embedded_default[1]); ?>";
				case "popup_2":
					default_template = "<?php echo hacklogdm_admin::js_fix($download_template_popup_default[1]); ?>";
					break;
				case 'custom_css':
					default_template = "<?php echo hacklogdm_admin::js_fix($download_template_custom_css_default); ?>";
					break;
				case "popup":
					default_template ="<?php echo hacklogdm_admin::js_fix($download_template_popup_default[0]); ?>";
				}
				jQuery("#download_template_" + template).val(default_template);
			}
			/* ]]> */

			function set_template_table(type)
			{
				type = parseInt(type);
				switch(type)
				{
					case 0:
						jQuery('#table_download_template_embedded').show();
						jQuery('#table_download_template_embedded_2').show();
						jQuery('#table_download_template_popup').hide();
						jQuery('#table_download_template_popup_2').hide();
						break;
			
					case 1:
						jQuery('#table_download_template_embedded').hide();
						jQuery('#table_download_template_embedded_2').hide();	
						jQuery('#table_download_template_popup').show();
						jQuery('#table_download_template_popup_2').show();	
						break;
				}
			}
			jQuery(function($){
				set_template_table($('#download_display_type').val() );
			});
</script>
