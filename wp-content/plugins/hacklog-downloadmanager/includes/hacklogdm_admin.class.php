<?php
/**
 * $Id: hacklogdm_admin.class.php 474566 2011-12-13 10:52:27Z ihacklog $
 * $Revision: 474566 $
 * $Date: 2011-12-13 10:52:27 +0000 (Tue, 13 Dec 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 */

if ( !defined( 'ABSPATH' ) ) 
{ 
      header( 'HTTP/1.1 403 Forbidden', true, 403 );
      die ('Please do not load this page directly. Thanks!');
}

/**
 * class for backend
 *
 * @author HuangYe
 */
class hacklogdm_admin
{

	private static $_message = '';
	private static $_error = '';
	const local_server_file = 0;
	const local_pc_file = 1;
	const remote_file = 2;

	public static function get($key, $default = 0)
	{
		return isset($_GET[$key]) ? $_GET[$key] : $default;
	}

	public static function post($key, $default = '')
	{
		return isset($_POST[$key]) ? $_POST[$key] : $default;
	}

	public static function add_message($msg)
	{
		self::$_message .= '<span style="color:#4e9a06;">' . $msg . '</span><br />';
	}

	public static function add_block_message($msg)
	{
		self::$_message .= $msg;
	}

	public static function add_error($err)
	{
		self::$_error .= '<span style="color:#f00;">' . $err . '</span><br />';
	}

	public static function add_block_error($err)
	{
		self::$_error .= $err;
	}

	public static function show_message_or_error($echo = 1)
	{
		$have_msg = !empty(self::$_message) || !empty(self::$_error);
		$message = $have_msg ? '<!-- Last Action --><div id="message" class="updated fade"><p>' : '';
		if (!empty(self::$_message))
		{
			$message .= self::$_message;
		}

		if (!empty(self::$_error))
		{
			$message .= self::$_error;
		}
		$message = $have_msg ? $message . '</p></div>' : '';
		if ($echo)
			echo $message;
		else
			return $message;
	}

	public static function get_opt($key, $default ='')
	{
		$hacklogdm = hacklogdm::instance();
		return $hacklogdm->get_opt($key, $default);
	}

	public static function check_upload_dir()
	{
		$path = self::get_opt('download_path');
		if (!is_dir($path))
		{
			self::add_error(sprintf(__('Error: the download path %s does not exists!', hacklogdm::textdomain), $path));
			self::show_message_or_error();
			die();
		}
		if (!is_writeable($path))
		{
			self::add_error(sprintf(__('Error: the download path %s is unwriteable,Please change the permission to <strong>0777</strong>!', hacklogdm::textdomain), $path));
			self::show_message_or_error();
			die();
		}
	}

	/*
	 * check if file id exists
	 */

	public static function id_exists($file_id)
	{
		$file_id = (int) $file_id;
		global $wpdb;
		$result = $wpdb->query("SELECT file_id FROM $wpdb->downloads WHERE file_id=$file_id");
		if ($result > 0)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 *check the file is exists and is a normal file
	 * @param string $file the full path filename
	 * @return Boolean 
	 */
	public static function is_normal_file($path, $file )
	{
		return file_exists($path . $file ) && $file != '.' && $file != '..' && $file != '.htaccess';
	}


	### Function: Get Total Download Files

	public static function get_download_files($display = true)
	{
		global $wpdb;
		$totalfiles = $wpdb->get_var("SELECT COUNT(file_id) FROM $wpdb->downloads");
		if ($display)
		{
			echo number_format_i18n($totalfiles);
		}
		else
		{
			return number_format_i18n($totalfiles);
		}
	}

	### Function Get Total Download Size

	public static function get_download_size($display = true)
	{
		global $wpdb;
		$totalsize = $wpdb->get_var("SELECT SUM(file_size) FROM $wpdb->downloads");
		if ($display)
		{
			echo format_filesize($totalsize);
		}
		else
		{
			return format_filesize($totalsize);
		}
	}

### Function: Get Total Download Hits

	public static function get_download_hits($display = true)
	{
		global $wpdb;
		$totalhits = $wpdb->get_var("SELECT SUM(file_hits) FROM $wpdb->downloads");
		if ($display)
		{
			echo number_format_i18n($totalhits);
		}
		else
		{
			return number_format_i18n($totalhits);
		}
	}

### Function: File Permission

	public static function file_permission($file_permission)
	{
		$file_permission_name = '';
		switch (intval($file_permission))
		{
			case - 2 :
				$file_permission_name = __('Hidden', 'hacklog-downloadmanager');
				break;
			case - 1 :
				$file_permission_name = __('Everyone', 'hacklog-downloadmanager');
				break;
			case 0 :
				$file_permission_name = __('Registered Users Only', 'hacklog-downloadmanager');
				break;
			case 1 :
				$file_permission_name = __('At Least Contributor Role', 'hacklog-downloadmanager');
				break;
			case 2 :
				$file_permission_name = __('At Least Author Role', 'hacklog-downloadmanager');
				break;
			case 7 :
				$file_permission_name = __('At Least Editor Role', 'hacklog-downloadmanager');
				break;
			case 10 :
				$file_permission_name = __('At Least Administrator Role', 'hacklog-downloadmanager');
				break;
		}
		return $file_permission_name;
	}

### Function: Editable Timestamp

	public static function file_timestamp($file_timestamp)
	{
		global $month;
		$day = gmdate('j', $file_timestamp);
		echo '<select id="file_timestamp_day" name="file_timestamp_day" size="1">' . "\n";
		for ($i = 1; $i <= 31; $i++)
		{
			if ($day == $i)
			{
				echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
			}
			else
			{
				echo "<option value=\"$i\">$i</option>\n";
			}
		}
		echo '</select>&nbsp;&nbsp;' . "\n";
		$month2 = gmdate('n', $file_timestamp);
		echo '<select id="file_timestamp_month" name="file_timestamp_month" size="1">' . "\n";
		for ($i = 1; $i <= 12; $i++)
		{
			if ($i < 10)
			{
				$ii = '0' . $i;
			}
			else
			{
				$ii = $i;
			}
			if ($month2 == $i)
			{
				echo "<option value=\"$i\" selected=\"selected\">$month[$ii]</option>\n";
			}
			else
			{
				echo "<option value=\"$i\">$month[$ii]</option>\n";
			}
		}
		echo '</select>&nbsp;&nbsp;' . "\n";
		$year = gmdate('Y', $file_timestamp);
		echo '<select id="file_timestamp_year" name="file_timestamp_year" size="1">' . "\n";
		for ($i = 2000; $i <= gmdate('Y'); $i++)
		{
			if ($year == $i)
			{
				echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
			}
			else
			{
				echo "<option value=\"$i\">$i</option>\n";
			}
		}
		echo '</select>&nbsp;@' . "\n";
		echo '<span dir="ltr">' . "\n";
		$hour = gmdate('H', $file_timestamp);
		echo '<select id="file_timestamp_hour" name="file_timestamp_hour" size="1">' . "\n";
		for ($i = 0; $i < 24; $i++)
		{
			if ($hour == $i)
			{
				echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
			}
			else
			{
				echo "<option value=\"$i\">$i</option>\n";
			}
		}
		echo '</select>&nbsp;:' . "\n";
		$minute = gmdate('i', $file_timestamp);
		echo '<select id="file_timestamp_minute" name="file_timestamp_minute" size="1">' . "\n";
		for ($i = 0; $i < 60; $i++)
		{
			if ($minute == $i)
			{
				echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
			}
			else
			{
				echo "<option value=\"$i\">$i</option>\n";
			}
		}

		echo '</select>&nbsp;:' . "\n";
		$second = gmdate('s', $file_timestamp);
		echo '<select id="file_timestamp_second" name="file_timestamp_second" size="1">' . "\n";
		for ($i = 0; $i <= 60; $i++)
		{
			if ($second == $i)
			{
				echo "<option value=\"$i\" selected=\"selected\">$i</option>\n";
			}
			else
			{
				echo "<option value=\"$i\">$i</option>\n";
			}
		}
		echo '</select>' . "\n";
		echo '</span>' . "\n";
	}

### Function: List Out All Files In Downloads Directory

	public static function list_downloads_folders($dir, $orginal_dir)
	{
		global $download_folders;
		if (is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..')
					{
						if (is_dir($dir . '/' . $file))
						{
							$folder = str_replace($orginal_dir, '', $dir . '/' . $file);
							$download_folders[] = $folder;
							self::list_downloads_folders($dir . '/' . $file, $orginal_dir);
						}
					}
				}
				closedir($dh);
			}
		}
	}

### Function: Print Listing Of Folders In Alphabetical Order
	/**
	 * 修正一bug,在files目录下面并无其它目录的情况下，即最首先安装插件时，$download_folders会为空的。
	 */

	public static function print_list_folders($dir, $orginal_dir)
	{
		global $download_folders;
		echo '<option value="/">/</option>' . "\n";
		self::list_downloads_folders($dir, $orginal_dir);
		if ($download_folders)
		{
			natcasesort($download_folders);
			foreach ($download_folders as $download_folder)
			{
				echo '<option value="' . $download_folder . '">' . $download_folder . '</option>' . "\n";
			}
		}
	}

###Function: tet basename of a file even if its name includes Chinese words

	public static function get_download_uniq_name($file)
	{
		$ext = hacklogdm::file_extension($file);
		if (strpos($file, '.'))
			$file_name = substr($file, 0, strrpos($file, '.'));
		else
			$file_name = $file;
		$new_name = date('Ymd') . md5($file_name) . '.' . $ext;

		return $new_name;
	}

	public static function download_uniq_name($file)
	{

		if (preg_match("/[\x7f-\xff]/", $file))
			$file = self::get_download_uniq_name($file);
		return $file;
	}

	/**
	 * rename the file or move the file ensure that it will not override the orignal file
	 * @param type $file_path
	 * @param type $file
	 * @return type 
	 */
	public static function download_rename_file($file_path, $file)
	{
		$rename = false;
		$file_old = $file;
		//对中文名直接生成唯一文件名
		if (preg_match("/[\x7f-\xff]/", $file) || @preg_match("/[\x{4e00}-\x{9fa5}]+/u", $file))
		{
			$file = self::get_download_uniq_name($file);
		}
		else
		{
			$file = preg_replace("/[^A-Za-z0-9\-._\/\[\]\(\)]/", '', $file);
		}
		$file = str_replace(' ', '_', $file);
		if ($file != $file_old)
		{
			if (file_exists($file_path . $file))
				rename($file_path . $file, $file_path . $file . '--' . date('Ymd-His') . '.bak');
			$rename = rename($file_path . $file_old, $file_path . $file);
		}
		if ($rename)
		{
			return $file;
		}
		else
		{
			return $file_old;
		}
	}

	/**
	 * 将远程服务器文件下载至本地
	 * added by 荒野无灯
	 * @param type $url
	 * @param type $local_file_path
	 * @return type 
	 */
	public static function down_remote_file($url, $local_file_path)
	{
		$download_options = self::get_opt('download_options');
		// maximum execution time in seconds
		@set_time_limit($download_options ['time_limit']);
		$fp = @fopen($url, "rb");
		if ($fp)
		{
			$local_file = @fopen($local_file_path, "wb");
			if ($local_file)
				while (!feof($fp)) {
					@fwrite($local_file, fread($fp, 1024 * 8), 1024 * 8);
				}
		}
		if ($fp)
		{
			fclose($fp);
		}
		if ($local_file)
		{
			fclose($local_file);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 计算文件哈希校验值
	 * @param type $file
	 * @return type 
	 */
	public static function get_file_hash($file)
	{
		$download_options = self::get_opt('download_options', 'md5');
		$hash_func = $download_options ['hash_func'] . '_file';
		if (file_exists($file) && function_exists($hash_func))
			return $hash_func($file);
		else
			return 'N/A';
	}

	/**
	 * if the php.ini size option value is not numeric
	 * @param int or string $value 
	 * @return int 
	 */
	public static function get_ini_size($value)
	{
		if (!is_numeric($value))
		{
			if (strpos($value, 'M') !== false)
			{
				$value = intval($value) * 1024 * 1024;
			}
			elseif (strpos($value, 'K') !== false)
			{
				$value = intval($value) * 1024;
			}
			elseif (strpos($value, 'G') !== false)
			{
				$value = intval($value) * 1024 * 1024 * 1024;
			}
		}
		return $value;
	}

### Function: Get Max File Size That Can Be Uploaded

	public static function get_max_upload_size()
	{
		$upload_maxsize = self::get_ini_size(ini_get('upload_max_filesize'));
		$post_maxsize = self::get_ini_size(ini_get('post_max_size'));
		if ($upload_maxsize < $post_maxsize)
			return $upload_maxsize;
		else
			return $post_maxsize;
	}

	/**
	 * get max_execution_time
	 * @return type 
	 */
	public static function get_max_excution_time()
	{
		return ini_get('max_execution_time');
	}

	public static function get_max_input_time()
	{
		return ini_get('max_input_time');
	}

	/**
	 * Function: Get Remote File Size
	 * 增加非下载文件判断，若是网页跳转链接直接返回未知
	 */
	public static function remote_filesize($uri)
	{
		//use wp_get_http_headers() better?
		$header_array = @get_headers($uri, 1);
		if ($header_array)
		{
			$file_size = $header_array ['Content-Length'];
			// be aware that there may be duplicated mime-type
			$mime_type = is_array($header_array['Content-Type']) ? $header_array['Content-Type'][0] : $header_array['Content-Type'];
			if (!empty($file_size) && 'text/html' != strtolower($mime_type))
			{
				return $file_size;
			}
		}
		return __('unknown', 'hacklog-downloadmanager');
	}

	public static function print_upload_form($action)
	{
		$file_path = self::get_opt('download_path');
		?>
		<form method="post"
			  action="<?php echo $action; ?>"
			  enctype="multipart/form-data"><input type="hidden" name="MAX_FILE_SIZE"
											 value="<?php echo self::get_max_upload_size(); ?>" />
			<div class="wrap">
				<div id="icon-hacklog-downloadmanager" class="icon32"><br /></div>
				<h2><?php _e('Add A File', 'hacklog-downloadmanager'); ?></h2>
				<table class="form-table">
					<tr>
						<td valign="top"><strong><?php _e('File:', hacklogdm::textdomain) ?></strong></td>
						<td><!-- Browse File --> <input type="radio" id="file_type_0"
														name="file_type" value="0" checked="checked" />&nbsp;&nbsp;<label
														for="file_type_0"><?php _e('Local File:', hacklogdm::textdomain); ?></label>&nbsp;
					<input type="text" readonly="readonly" size="50" name="file" id="hacklogdm-filetree-file"/>
							<input id="hacklogdm-filetree-button" class="button" type="button" value="<?php _e('Browse Files', hacklogdm::textdomain);?>"
									onclick="document.getElementById('file_type_0').checked = true;"
									dir="ltr" />
							<div id="hacklogdm-filetree" style="display:none;">	</div>
									 <br />
							<small><?php printf(__('Please upload the file to \'%s\' directory first.', hacklogdm::textdomain), $file_path); ?></small>
							<br />
							<br />
							<!-- Upload File --> <input type="radio" id="file_type_1"
														name="file_type" value="1" />&nbsp;&nbsp;<label for="file_type_1"><?php _e('Upload File:', hacklogdm::textdomain); ?></label>&nbsp;
							<input type="file" name="file_upload" size="25"
								   onclick="document.getElementById('file_type_1').checked = true;"
								   dir="ltr" />&nbsp;&nbsp;<?php _e('to', hacklogdm::textdomain); ?>&nbsp;&nbsp;
							<select name="file_upload_to" size="1"
									onclick="document.getElementById('file_type_1').checked = true;"
									dir="ltr">
										<?php self::print_list_folders($file_path, $file_path); ?>
							</select> <br />
							<small><?php printf(__('Maximum file size is <strong>%s</strong>.', hacklogdm::textdomain), hacklogdm::format_filesize(self::get_max_upload_size())); ?></small>
							<small><?php printf(__('Maximum upload time is <strong>%s seconds</strong>.', hacklogdm::textdomain), self::get_max_input_time()); ?></small>
							<!-- Remote File --> <br />
							<br />
							<input type="radio" id="file_type_2" name="file_type" value="2" />&nbsp;&nbsp;<label
								for="file_type_2"><?php _e('Remote File:', hacklogdm::textdomain); ?></label>&nbsp;
							<input type="text" name="file_remote" size="50" maxlength="255"
								   onclick="document.getElementById('file_type_2').checked = true;"
								   value="http://" dir="ltr" /> <br />
							<?php echo __('Save to local host', hacklogdm::textdomain); ?>:<input
								type="checkbox" name="save_to_local" value="1" />&nbsp;&nbsp;<?php _e('to dir ', hacklogdm::textdomain); ?>&nbsp;&nbsp;
							<input type="text" name="file_save_to" size="20" maxlength="50"
								   value="/remote" /> 
							<br />
							<small><?php _e('Please include <span style="color:red;">http://</span> or <span style="color:red;">ftp://</span> in front.For direcotry please include <span style="color:red;">/</span> in front!', hacklogdm::textdomain); ?></small>
						</td>
					</tr>
					<tr>
						<td><strong><?php _e('File Name:', hacklogdm::textdomain); ?></strong></td>
						<td><input type="text" size="50" maxlength="200" name="file_name" /></td>
					</tr>
					<tr>
						<td valign="top"><strong><?php _e('File Description:', hacklogdm::textdomain); ?></strong></td>
						<td><textarea rows="5" cols="50" name="file_des"></textarea></td>
					</tr>

					<tr>
						<td valign="top"><strong><?php _e('File Size:', hacklogdm::textdomain) ?></strong></td>
						<td><input type="text" size="10" name="file_size" />&nbsp;<?php _e('bytes', hacklogdm::textdomain); ?><br />
							<small><?php _e('Leave blank for auto detection. Auto detection sometimes will not work for Remote File.', hacklogdm::textdomain); ?></small></td>
					</tr>
					<tr>
						<td valign="top"><strong><?php _e('File Date:', hacklogdm::textdomain) ?></strong></td>
						<td><?php self::file_timestamp(current_time('timestamp')); ?></td>
					</tr>
					<tr>
						<td><strong><?php _e('Starting File Hits:', hacklogdm::textdomain) ?></strong></td>
						<td><input type="text" size="6" maxlength="10" name="file_hits"
								   value="0" /></td>
					</tr>
					<tr>
						<td><strong><?php _e('Allowed To Download:', hacklogdm::textdomain) ?></strong></td>
						<td><select name="file_permission" size="1">
								<option value="-2"><?php _e('Hidden', hacklogdm::textdomain); ?></option>
								<option value="-1" selected="selected"><?php _e('Everyone', hacklogdm::textdomain); ?></option>
								<option value="0"><?php _e('Registered Users Only', hacklogdm::textdomain); ?></option>
								<option value="1"><?php _e('At Least Contributor Role', hacklogdm::textdomain); ?></option>
								<option value="2"><?php _e('At Least Author Role', hacklogdm::textdomain); ?></option>
								<option value="7"><?php _e('At Least Editor Role', hacklogdm::textdomain); ?></option>
								<option value="10"><?php _e('At Least Administrator Role', hacklogdm::textdomain); ?></option>
							</select></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" name="do"
															  value="<?php _e('Add File', hacklogdm::textdomain); ?>"
															  class="button" />&nbsp;&nbsp;<input type="button" name="cancel"
															  value="<?php _e('Cancel', hacklogdm::textdomain); ?>"
															  class="button" onclick="javascript:history.go(-1)" /></td>
					</tr>
				</table>
			</div>
		</form>
		<?php
	}

	public static function check_duplicate_file($file_type, $file, $file_hash)
	{
		global $wpdb;
		if (self::local_server_file === $file_type || self::local_pc_file === $file_type)
			$duplicate = $wpdb->query("SELECT file_id FROM $wpdb->downloads WHERE file_hash='$file_hash'");
		else
			$duplicate = $wpdb->query("SELECT file_id FROM $wpdb->downloads WHERE file='$file'");
		$duplicate_id = $wpdb->get_var();
		if ($duplicate_id > 0)
		{
			self::add_error(sprintf(__('Error:File \'%s \' has already been added!The file_id is:<strong>%d</strong>', hacklogdm::textdomain), $file, $duplicate_id));
			return 1;
		}
		return 0;
	}

	public static function add_server_file()
	{
		$file_path = self::get_opt('download_path');
		//relative path (including file name ) to upload folder
		$file = addslashes(trim(hacklogdm_admin::post('file')));
		// file_name is the display name of the download file
		$file_name = hacklogdm::get_download_name($file);
		//if the file was user uploaded via FTP client ,then the file should be renamed.
		$file = self::download_rename_file($file_path, $file);
		$file_size = filesize($file_path . $file);
		$file_hash = self::get_file_hash($file_path . $file);
		return array('file_name' => $file_name,
			'file' => $file,
			'file_size' => $file_size,
			'file_hash' => $file_hash,
		);
	}

	public static function upload_local_file($file_upload_to, $is_edit = 0)
	{
		global $wpdb;
		$file_path = self::get_opt('download_path');
		if (empty($file_upload_to))
		{
			return FALSE;
		}
		if ($_FILES['file_upload']['size'] > self::get_max_upload_size())
		{
			self::add_error(sprintf(__('File Size Too Large. Maximum Size Is %s', hacklogdm::textdomain), hacklogdm::format_filesize(self::get_max_upload_size())));
			return FALSE;
		}
		else
		{
			if (empty($_FILES['file_upload']['name']))
			{
				self::add_error(__('Please select a File to be upload!', hacklogdm::textdomain));
				return FALSE;
			}

			if (is_uploaded_file($_FILES['file_upload']['tmp_name']))
			{
				if ($file_upload_to != '/')
				{
					$file_upload_to = $file_upload_to . '/';
				}
				$file_name = hacklogdm::get_download_name($_FILES['file_upload']['name']);
				$uniq_name = self::download_uniq_name($file_name);
				if ($is_edit)
				{
					$old_file_name = $wpdb->get_var("SELECT file FROM $wpdb->downloads WHERE file_id = $file_id");
					if ($old_file_name == $file_upload_to . $uniq_name) //更新文件时重命名旧文件
						@rename($file_path . $old_file_name, $file_path . $old_file_name . '--' . date('Ymd-His') . '.bak');
				}
				$full_path = $file_path . $file_upload_to . $uniq_name;
				if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $full_path))
				{
					$relative_file_path = $file_upload_to . $uniq_name;
					//remove invalid chars and rename non-latin chars
					$file = self::download_rename_file($file_path, $relative_file_path);
					$file_size = filesize($file_path . $file);
					$file_hash = self::get_file_hash($file_path . $file);
				}
				else
				{
					self::add_error(__('Error In Uploading File', hacklogdm::textdomain));
					return FALSE;
				}
			}
			else
			{
				self::add_error(__('Error In Uploading File', hacklogdm::textdomain));
				return FALSE;
			}
		}
		return array('file_name' => $file_name,
			'file' => $file,
			'file_size' => $file_size,
			'file_hash' => $file_hash,
		);
	}

	public static function add_remote_file($file_remote, $file_save_to, $save_to_local = 0)
	{
		if (empty($file_remote) || !(strlen($file_remote) > 7 ) || !hacklogdm::is_remote_file($file_remote))
		{
			self::add_error(__('Error: Please give me a valid URL.', hacklogdm::textdomain));
			return FALSE;
		}
		$file_path = self::get_opt('download_path');
		$file = addslashes(trim($file_remote));
		$uniq_name = self::download_uniq_name(hacklogdm::get_basename($file));
		$file_name = hacklogdm::get_download_name($file);
		//try to get the remote file size
		$file_size = self::remote_filesize($file);
		$file_hash = 'N/A';
		if (isset($save_to_local) && (1 == $save_to_local))
		{
			$file_path = self::get_opt('download_path');
			if ($file_save_to != '/')
			{
				$file_save_to = $file_save_to . '/';
			}
			if (!is_dir($file_path . $file_save_to))
			{
				@mkdir($file_path . $file_save_to, 0777, true);
			}

			$new_file = $file_path . $file_save_to . $uniq_name;
			//exit($new_file);
			if (!hacklogdm_admin::down_remote_file($file, $new_file))
			{
				self::add_error(__('Error In downloading File to local host!', hacklogdm::textdomain));
				return FALSE;
			}
			else
			{
				self::add_message(__('-_-Downloading File to local host successfuly!', hacklogdm::textdomain));
				//override the variables 
				$file = $file_save_to . $uniq_name; //注意这里要加上目录
				$file = self::download_rename_file($file_path . $file_save_to, $file);
				$file_size = filesize($file_path . $file);
				$file_hash = self::get_file_hash($file_path . $file);
			}
		}
		return array('file_name' => $file_name,
			'file' => $file,
			'file_size' => $file_size,
			'file_hash' => $file_hash,
		);
	}

	/**
	 * insert the file data to DB
	 * @param type $data 
	 */
	public static function add_new_file($data, $tab = 0)
	{
		global $wpdb;
		extract($data);
		if (!empty($_POST['file_name']))
		{
			$file_name = addslashes(trim($_POST['file_name']));
		}

		if (empty($file_name))
		{
			self::add_error(sprintf(__('Error:File name REQUIRED!Please assign a file name for displaying.', hacklogdm::textdomain), $file, $duplicate_id));
		}
		if (!empty($_POST['file_size']))
		{
			$file_size = intval($_POST['file_size']);
		}
		$file_des = addslashes(trim($_POST['file_des']));

		$file_hits = intval($_POST['file_hits']);
		$file_timestamp_day = intval($_POST['file_timestamp_day']);
		$file_timestamp_month = intval($_POST['file_timestamp_month']);
		$file_timestamp_year = intval($_POST['file_timestamp_year']);
		$file_timestamp_hour = intval($_POST['file_timestamp_hour']);
		$file_timestamp_minute = intval($_POST['file_timestamp_minute']);
		$file_timestamp_second = intval($_POST['file_timestamp_second']);
		$file_date = gmmktime($file_timestamp_hour, $file_timestamp_minute, $file_timestamp_second, $file_timestamp_month, $file_timestamp_day, $file_timestamp_year);
		$file_permission = intval($_POST['file_permission']);
		$addfile = $wpdb->query("INSERT INTO $wpdb->downloads VALUES (NULL, '$file', '$file_name', '$file_des', '$file_hash', '$file_size', '$file_date', '$file_date', '$file_date', $file_hits, $file_permission)");
		if (!$addfile)
		{
			self::add_error(sprintf(__('Error In Adding File \'%s (%s)\' To Database!', hacklogdm::textdomain), $file_name, $file));
		}
		else
		{
			self::add_message(sprintf(__('File \'%s (%s)\' Added Successfully!<br/><strong>File ID is: %s </strong>', hacklogdm::textdomain), $file_name, $file, $wpdb->insert_id));
			if ($tab)
			{
				$GLOBALS['insert_download_shortcode'] = '[download id="' . $wpdb->insert_id . '"';
				self::add_block_message('<div style="margin:10px auto;">');
				self::add_block_message('<h3> ' . __('Insert new download into post', 'hacklog-downloadmanager') . '</h3>');
				self::add_block_message('<p class="submit">');
				self::add_block_message('<input type="submit" id="insertdownload" class="button button-primary" name="insertintopost" value="' . __('Insert into post', 'hacklog-downloadmanager') . '" /></p>');
				self::add_block_message('</div>');
			}
		}
	}

	public static function delete_file()
	{
		global $wpdb;
		$file_path = self::get_opt('download_path');
		$file_id = intval(self::post('file_id'));
		$file = trim(self::post('file'));
		$file_name = trim(self::post('file_name'));
		$unlinkfile = intval(self::post('unlinkfile', 0));
		if ($unlinkfile == 1)
		{
			if (!@unlink($file_path . '/' . $file))
			{
				self::add_error(sprintf(__('Error In Deleting File \'%s (%s)\' From Server', hacklogdm::textdomain), $file_name, $file));
			}
			else
			{
				self::add_message(sprintf(__('File \'%s (%s)\' Deleted From Server Successfully', hacklogdm::textdomain), $file_name, $file));
			}
		}
		$deletefile = $wpdb->query("DELETE FROM $wpdb->downloads WHERE file_id = $file_id");
		if (!$deletefile)
		{
			self::add_error(sprintf(__('Error In Deleting File \'%s (%s)\'', hacklogdm::textdomain), $file_name, $file));
		}
		else
		{
			self::add_message(sprintf(__('File \'%s (%s)\' Deleted Successfully', hacklogdm::textdomain), $file_name, $file));
		}
	}
	
	public static function js_fix($text_for_js)
	{
		$text_for_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", stripslashes( $text_for_js ) );
		$text_for_js = str_replace( "\r", '', $text_for_js );
		$text_for_js = str_replace( "\n", '\\n', addslashes( $text_for_js ) );
		return $text_for_js;
	}

}

// enc class hacklogdm_admin
