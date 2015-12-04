<?php
/*
Plugin Name: WP BaiDu Submit
Description: WP BaiDu Submit帮助具有百度站长平台链接提交权限的用户自动提交最新文章，以保证新链接可以及时被百度收录。
Version: 1.2.1
Plugin URI: https://wordpress.org/plugins/wp-baidu-submit/
Author: Include
Author URI: http://www.170mv.com/
*/
/*
Publish Date: 2015-05-09
Last Update: 2015-05-27
*/
date_default_timezone_set('Asia/Shanghai');
add_action('publish_post', 'publish_bd_submit');
function publish_bd_submit($post_ID){
	global $post;
	$bd_submit_enabled = get_option('bd_submit_enabled');
	if($bd_submit_enabled){
		$bd_submit_site = get_option('bd_submit_site');
		$bd_submit_token = get_option('bd_submit_token');
		if( empty($post_ID) || empty($bd_submit_site) || empty($bd_submit_token) ) return;
		$api = 'http://data.zz.baidu.com/urls?site='.$bd_submit_site.'&token='.$bd_submit_token;
		$status = $post->post_status;
		if($status != '' && $status != 'publish'){
			$url = get_permalink($post_ID);
			$ch = curl_init();
			$options =  array(
				CURLOPT_URL => $api,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $url,
				CURLOPT_HTTPHEADER => array('Content-Type: text/plain')
			);
			curl_setopt_array($ch, $options);
			$result = curl_exec($ch);
			$bd_submit_log_enabled = get_option('bd_submit_log_enabled');
			if(!$bd_submit_log_enabled) return;
			$result = json_decode($result, true);
			if($result['message']){
				update_option('bd_submit_error',$result['message']);
			}
			if($result['success']){
				delete_option('bd_submit_error');
				$time = nowtime();
				$submit_file = dirname(__FILE__).'/submit_log.txt';
				$robots_file = dirname(__FILE__).'/robots_log.txt';  
				if(date('Y-m-d',filemtime($submit_file)) != date('Y-m-d')){
					file_put_contents($robots_file, '');
					$handle = fopen($submit_file,"w");
				}else{
					$handle = fopen($submit_file,"a");
				}
				fwrite($handle,"$time|$url||");
				fclose($handle);
			}
		}
	}
}

add_action('wp_footer', 'robots_log');
function robots_log($post_ID){
	$bd_submit_log_enabled = get_option('bd_submit_log_enabled');
	if(!$bd_submit_log_enabled) return;
	$searchbot = get_naps_bot();
	if ($searchbot) {
		$tlc_thispage = addslashes($_SERVER['HTTP_USER_AGENT']);  
		$url = $_SERVER['HTTP_REFERER']; 
		$submit_file = dirname(__FILE__).'/submit_log.txt';
		$robots_file = dirname(__FILE__).'/robots_log.txt';  
		$time = nowtime();
		$PR = home_url().$_SERVER['REQUEST_URI']; 
		if(date('Y-m-d',filemtime($robots_file)) != date('Y-m-d')){
			file_put_contents($submit_file, '');
			$handle = fopen($robots_file,"w");
		}else{
			$handle = fopen($robots_file,"a");
		} 
		fwrite($handle,"$time|$searchbot|$PR||");  
		fclose($handle);
	}
}

add_action('admin_menu', 'bd_submit_add_page');
function bd_submit_add_page() {
	add_menu_page( 'WP BaiDu Submit 设置', 'BaiDu Submit', 'manage_options', 'wp_baidu_submit', 'bd_submit_settings' );
	add_submenu_page('wp_baidu_submit', 'WP BaiDu Submit 设置', '设置', 'manage_options', 'wp_baidu_submit', 'bd_submit_settings' );
	add_submenu_page('wp_baidu_submit', 'WP BaiDu Submit 提交结果', '提交结果', 'manage_options', 'wp_baidu_submit_result', 'bd_submit_result' );
}
function bd_submit_settings() {
	if($_POST['submit']){
		$bd_submit_site = trim($_POST['bd_submit_site']);
		$bd_submit_token = trim($_POST['bd_submit_token']);
		$bd_submit_enabled = $_POST['bd_submit_enabled'];
		$bd_submit_log_enabled = $_POST['bd_submit_log_enabled'];
		if( empty($bd_submit_site) || empty($bd_submit_token) ){
			$bd_submit_enabled = 0;
		}
		if( empty($bd_submit_site) || empty($bd_submit_token) || $bd_submit_enabled == 0 ){
			$bd_submit_log_enabled = 0;
		}
		update_option('bd_submit_site',$bd_submit_site);
		update_option('bd_submit_token',$bd_submit_token);
		update_option('bd_submit_enabled',$bd_submit_enabled);
		update_option('bd_submit_log_enabled',$bd_submit_log_enabled);
	}else{
		$bd_submit_site = get_option('bd_submit_site');
		$bd_submit_token = get_option('bd_submit_token');
		$bd_submit_enabled = get_option('bd_submit_enabled');
		$bd_submit_log_enabled = get_option('bd_submit_log_enabled');
	}
?>
	<div class="wrap">
		<h2 style="border-bottom: 1px solid #DFDFDF;">WP BaiDu Submit 1.2.1</h2>
		<ul class="subsubsub" style="float:none;">
		<li style="padding-right:20px"> <a href="admin.php?page=wp_baidu_submit" class="current">设置</a></li>
		<li style="padding-right:20px"> <a href="admin.php?page=wp_baidu_submit_result">提交结果</a></li>
		</ul>
		<form method="post" action="#">
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="bd_submit_site">验证站点域名</label></th>
					<td><input type="text" name="bd_submit_site" id="bd_submit_site" value="<?php echo $bd_submit_site; ?>" class="regular-text" /><br />
					 	在站长平台验证的站点，比如www.example.com
					</td>
				</tr>
				<tr valign="top"><th scope="row"><label for="bd_submit_token">站点准入密钥</label></th>
					<td><input type="text" name="bd_submit_token" id="bd_submit_token" value="<?php echo $bd_submit_token; ?>" class="regular-text" /><br />
						在站长平台申请的推送用的准入密钥，比如：3sM2Wity6fP8TbR0
					</td>
				</tr>
				<tr valign="top"><th scope="row">开启自动提交？</th>
					<td>
					<label for="bd_submit_enabled">
					<input name="bd_submit_enabled" type="checkbox" <?php if($bd_submit_enabled) echo "checked"; ?> id="bd_submit_enabled" value="1" />
					是否开启自动提交，勾选开启，仅对新发布文章有效
					</label>
					</td>
				</tr>
				<tr valign="top"><th scope="row">开启提交记录？</th>
					<td>
					<label for="bd_submit_log_enabled">
					<input name="bd_submit_log_enabled" type="checkbox" <?php if($bd_submit_log_enabled) echo "checked"; ?> id="bd_submit_log_enabled" value="1" />
					是否开启提交记录，勾选开启，仅记录当日提交结果
					</label>
					</td>
				</tr>
				<tr valign="top"><th scope="row">自动提交使用建议</th>
					<td>
					建议：在发布高质量文章前开启，大量自动提交垃圾文章可能导致失去权限。
					<a href="http://zhanzhang.baidu.com/sitemap/pingindex?site=<?php echo empty($bd_submit_site)?'':'http://'.$bd_submit_site.'/'; ?>" target="_blank">查看主动推送效果</a>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>		
		</form>
	</div>
	<?php	
}
function bd_submit_result() {
	$robots_log_file = dirname(__FILE__).'/robots_log.txt';
	$submit_log_file = dirname(__FILE__).'/submit_log.txt';
	$bd_submit_error = get_option('bd_submit_error');
	$bd_submit_log_enabled = get_option('bd_submit_log_enabled');
?>
		<style type="text/css">
		#urlstat { border: 1px solid #DDDDDD; border-collapse:collapse; }
		#urlstat th, #urlstat td { border:1px solid #DDDDDD; padding: 5px 15px; text-align: center; }
		</style>
		<div class="wrap">
		<h2 style="border-bottom: 1px solid #DFDFDF;">WP BaiDu Submit 1.2.1</h2>
		<ul class="subsubsub" style="float:none;">
		<li style="padding-right:20px"> <a href="admin.php?page=wp_baidu_submit">设置</a></li>
		<li style="padding-right:20px"> <a href="admin.php?page=wp_baidu_submit_result" class="current">提交结果</a></li>
		</ul>
<?php
	if($bd_submit_log_enabled){
		if(!is_writable($submit_log_file) || !is_writable($robots_log_file)){
?>
		<h3>提交成功记录</h3>
		<font color="red">插件目录下的文件：robots_log.txt、submit_log.txt不可写，请修改文件权限允许写入。</font>
<?php }else{ ?>
		<h3>提交成功记录</h3>
		<table id="urlstat" class="form-table">
		<tbody>
		<tr><th>提交网址</th><th>提交状态</th><th>提交时间</th><th>抓取状态</th><th>抓取时间</th></tr>
			<?php
				if(file_exists($submit_log_file) && file_exists($robots_log_file)){
					$robots_log = file_get_contents($robots_log_file);
					$robots_log_info = explode('||',$robots_log);
					$submit_log = file_get_contents($submit_log_file);
					$submit_log_info = explode('||',$submit_log);
					$robots_log_url  = array();
					$submit_log_url  = array();
					foreach($robots_log_info as $value){
						trim($value) && $robots_log_url[] = explode("|",$value);
					}
					foreach($submit_log_info as $value){
						trim($value) && $submit_log_url[] = explode("|",$value);
					}
					foreach ($submit_log_url as $url) {
						if(!empty($url['1'])){
							foreach($robots_log_url as $a){
								if($a['2'] == $url['1']){
									echo '<tr><td>'.$url['1'].'</td><td>成功</td><td>'.$url['0'].'</td><td>已抓取</td><td>'.$a['0'].'</td></tr>';
									$is = 1;
									break;
								}else{
									$is = 0;
								}
							}
							if($is == 0){
								echo '<tr><td>'.$url['1'].'</td><td>成功</td><td>'.$url['0'].'</td><td>未抓取</td><td>无</td></tr>';
							}
						}
					}
				}
			?>
		</tbody>
		</table>
		<br />
<?php } ?>
		<h3>错误信息记录</h3>
		<table class="form-table">
			<tr valign="top"><th scope="row">提交返回错误</th>
				<td>
				错误信息：<?php if($bd_submit_error){  echo '<font color="red">'.$bd_submit_error.'</font>'; }else{ echo '<font color="green">恭喜，目前没有错误信息</font>'; } ?>
				</td>
			</tr>
		</table>
<?php }else{ ?>
		<h3>未开启提交记录</h3>
<?php } ?>
</div>
<?php
}
function nowtime($time = ''){
	date_default_timezone_set('Asia/Shanghai');
	if($time != ''){
		$date = date("Y-m-d H:i:s", $time);
	}else{
		$date = date("Y-m-d H:i:s");
	}
	return $date;  
}
function get_naps_bot(){
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);  
	if (strpos($useragent, 'baiduspider') !== false){  
		return 'Baiduspider';  
	}
	return false;
}