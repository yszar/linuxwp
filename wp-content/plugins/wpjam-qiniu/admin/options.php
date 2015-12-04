<?php

add_action( 'admin_menu', 'wpjam_qiniutek_admin_menu');
function wpjam_qiniutek_admin_menu() {
	add_menu_page(						'七牛镜像存储',			'七牛镜像存储',	'manage_options',	'wpjam-qiniutek',			'wpjam_qiniutek_setting_page',	'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IuWbvuWxgl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjE1My44OHB4IiBoZWlnaHQ9IjEwMy4zcHgiIHZpZXdCb3g9IjAgMCAxNTMuODggMTAzLjMiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE1My44OCAxMDMuMyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMTUzLjY4NCwwLjc5MWMtMC4yNjYtMC40OTctMC44My0wLjk2My0xLjg1LTAuNzI4Yy0xLjk4NCwwLjQ2NS0yNS4xMzksMzkuMTY5LTc0Ljg4NywzOC4wOThoLTAuMDE2DQoJYy05LjQ1MiwwLjIwMy0xOC42MjgtMS4wMy0yNi4xODgtMy4xNTZsLTQuMzI3LTEzLjk4YzAsMC0wLjgwMS0zLjQzOC00LjExNS01LjE5NmMtMi4yOTMtMS4yMDctMy42MzEtMC44MjEtMy45MTEtMC40ODQNCgljLTAuMjUyLDAuMzE2LTAuMjA0LDAuNjUzLTAuMjA0LDAuNjUzbDIuMDUzLDE1LjI2NkMxNC44OTEsMjAuNCwzLjQ3NCwwLjQwMywyLjA0MiwwLjA2M2MtMS4wMTUtMC4yMzUtMS41NzgsMC4yMy0xLjg0NSwwLjcyOA0KCWMtMC40MjcsMC44LTAuMDIxLDEuOTI5LTAuMDIxLDEuOTI5YzcuMTUyLDIxLjMxMSwyMi41ODcsMzguMTI2LDQyLjU2Nyw0Ny4wOWw1LjUwOSwzNi45MzENCgljMC4zNzQsMTAuNTMzLDcuNDE2LDE2LjU1OSwxNi41NjksMTYuNTU5aDI3LjM5YzkuMTUzLDAsMTYuMDA4LTYuNTg4LDE2LjU3NS0xNi41NTlsNS4wMTktMzAuNTM3YzAsMCwwLjA4NS0wLjQyNi0wLjE2Ni0wLjYwNA0KCWMtMC4zMTItMC4xOTEtMi42OTgtMC4yNjQtNy41MjgsMy4zMTRjLTQuODMsMy41ODItNi40NjMsOC43NTYtNi40NjMsOC43NTZzLTUuMjE5LDEyLjY5OS02LjU5MSwxOC4xMjUNCgljLTEuNDQ0LDUuNzEzLTcuODUsNS4yMDMtNy44NSw1LjIwM3MtOS43ODksMC0xNC42ODEsMGMtNC44OTUsMC01LjM5Ni00LjM5NS01LjM5Ni00LjM5NWwtOS4xNi0zMi4yMTUNCgljNi42NzEsMS42ODYsMTMuNjg0LDIuNTY4LDIwLjk2MiwyLjU0M2gwLjAxNmMzNS45NzUsMC4xNDEsNjUuODk3LTIxLjg4Myw3Ni43NTYtNTQuMjEyQzE1My43MDMsMi43MTksMTU0LjExLDEuNTksMTUzLjY4NCwwLjc5MXoiDQoJLz4NCjwvc3ZnPg0K'	);
	add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储设置',		'设置',			'manage_options',	'wpjam-qiniutek',			'wpjam_qiniutek_setting_page'	);
	if( wpjam_qiniutek_get_setting('bucket') && wpjam_qiniutek_get_setting('access') && wpjam_qiniutek_get_setting('secret') ){
		add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 文件更新',			'文件更新',		'manage_options',	'wpjam-qiniutek-update','wpjam_qiniutek_update_page'	);
		add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 上传 Robots.txt',	'Robots.txt',	'manage_options',	'wpjam-qiniutek-robots','wpjam_qiniutek_robots_page'	);
	}
	add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 充值优惠码',				'充值优惠码',		'manage_options',	'wpjam-qiniutek-coupon',	'wpjam_qiniutek_coupon_page'	);	
}

// 设置
include(WPJAM_QINIUTEK_PLUGIN_DIR.'/admin/setting.php');
include(WPJAM_QINIUTEK_PLUGIN_DIR.'/admin/thumbnail.php');

function wpjam_qiniutek_update_page(){
	global $plugin_page;

	$updates = '';

	if(isset($_GET['refresh'])){

		do_action('wpjam_refresh_static');

		update_option('timestamp',time());

		$msg = '已经刷新本地JS和CSS浏览器缓存！';
	}
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		if ( !wp_verify_nonce($_POST[$plugin_page],'wpjam_qiniutek') ){
			ob_clean();
			wp_die('非法操作');
		}
		
		$updates = ($_POST['updates']);

		$updates_array = explode("\n", $updates);

		$msg = '';
		foreach ($updates_array as $update) {
			if(trim($update)){
				$update = preg_replace('/\?.*/', '', $update);
				$msg = $msg.$update.wpjam_qiniutek_update_file($update);
			}
		}
	}

	?>
	<div class="wrap">
		<h2>更新文件</h2>

		<?php if(!empty($msg)){?>
		<div class="updated">
			<p><?php echo $msg;?></p>
		</div>
		<?php }?>

		<form method="post" action="<?php echo admin_url('admin.php?page='.$plugin_page); ?>" enctype="multipart/form-data" id="form">
		<table class="form-table" cellspacing="0">
			<tbody>
				<tr valign="top">
					<td>
						<p>请输入需要更新的文件，每行一个：</p>
						<textarea name="updates" rows="10" cols="50" id="updates" class="large-text code"><?php echo $updates; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('wpjam_qiniutek',$plugin_page); ?>
		<input type="hidden" name="action" value="edit" />
		<p class="submit"><input class="button-primary" type="submit" value="更新文件" /></p>
		</form>
		<ol>
			<li>点击“更新文件”按钮之后，只要文件后面显示更新成功，即代表更新成功。</li>
			<li>如果实时查看还是旧的文件，可能是你浏览器的缓存，你需要清理下缓存，或者等待自己更新。</li>
			<li>如果你更新的是主题或者插件的JS和CSS文件，可以再次点击下面按钮刷新本地缓存：<br />
			<a class="button" href="<?php echo admin_url('admin.php?page='.$plugin_page.'&refresh'); ?>">刷新本地JS和CSS浏览器缓存</a></li>
			<li>图片缩略图更新七牛是按照按照队列顺序进行的，需要等待一定的时间，只要看到原图更新即可。</li>
		</ol>
	</div>
<?php
}

function wpjam_qiniutek_robots_page(){
	global $plugin_page;

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		if ( !wp_verify_nonce($_POST[$plugin_page],'wpjam_qiniutek') ){
			ob_clean();
			wp_die('非法操作');
		}
		
		$robots = ($_POST['robots']);

		if($robots){
			$msg = '';

			update_option('qiniutek_robots',$robots);

			wpjam_qiniutek_update_file('robots.txt'); // 如果有，先清理。
			$msg = wpjam_qiniutek_put('robots.txt', $robots); // 再上传

		}
	}

	$qiniutek_robots = get_option('qiniutek_robots');

	if(!$qiniutek_robots){
		$qiniutek_robots = '
User-agent: *
Disallow: /
User-agent: Googlebot-Image
Allow: /
User-agent: Baiduspider-image
Allow: /
		';
	}

	?>
	<div class="wrap">
		<h2>上传 Robots.txt</h2>

		<?php if(!empty($msg)){?>
		<div class="updated">
			<p><?php echo $msg;?></p>
		</div>
		<?php }?>

		<form method="post" action="<?php echo admin_url('admin.php?page='.$plugin_page); ?>" enctype="multipart/form-data" id="form">
		<table class="form-table" cellspacing="0">
			<tbody>
				<tr valign="top">
					<td>
						<p>上传 Robots.txt 文件，防止搜索引擎索引镜像的网页。</p>
						<textarea name="robots" rows="10" cols="50" id="robots" class="large-text code"><?php echo $qiniutek_robots; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('wpjam_qiniutek',$plugin_page); ?>
		<input type="hidden" name="action" value="edit" />
		<p class="submit"><input class="button-primary" type="submit" value="更新Robots.txt" /></p>
		</form>
	</div>
<?php
}

function wpjam_qiniutek_coupon_page(){
?>
	<div class="wrap">
		<h2>如何使用七牛云存储的优惠码</h2>
		<p>简单说就是<strong>复制专属我爱水煮鱼用户的优惠码“<span style="color:red;">d706b222</span>”，充值就能享受9折优惠</strong>。</p>
		<p>1. 登陆七牛开发者平台：<a href="https://portal.qiniu.com/">https://portal.qiniu.com/</a></p>
		<p>2. 然后点击“充值”，进入充值页面</p>
		<p><img src="<?php echo WPJAM_QINIUTEK_PLUGIN_URL; ?>/static//qiniu-coupon.png" alt="使用七牛优惠码" /></p>
		<p>3. 点击“使用优惠码”，并输入优惠码“<strong><span style="color:red;">d706b222</span></strong>”，点击“使用”。</p>
		<p>4. 输入计划充值的金额，点击“马上充值”，进入支付宝页面，完成支付。<br />
		*注意七牛的优惠不是在原价上优惠，是赠送的方式，所以比如你要充值100，你只要输入90即可，这个需要数学比较好的同学算下 <img src="http://wpjam.qiniudn.com/wpjam/smilies/icon_smile.gif" alt=":-)" class="wp-smiley" />  。</p>
		<p>5. 完成支付后，可至财务->>财务概况->>账户余额 查看实际到账金额。</p>	
	</div>
<?php
}

function wpjam_qiniutek_update_file($file,$echo = true){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$file_array = parse_url($file);
	$key = str_replace($file_array['scheme'].'://'.$file_array['host'].'/', '', $file);
	$err = Qiniu_RS_Delete($qiniutek_client, $qiniutek_bucket, $key);

	if($echo){
		if ($err !== null) {
			$msg = ' 发生错误：<span style="color:red">'.$err->Err.'</span><br />';
		} else {
			$msg = ' 清理成功<br />';
		}
	}
	return $msg;
}

function wpjam_qiniutek_put_file($key, $file){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$putPolicy = new Qiniu_RS_PutPolicy($qiniutek_bucket);
	$upToken = $putPolicy->Token(null);

	if(!function_exists('Qiniu_Put')){
		require_once(WP_CONTENT_DIR."/plugins/wpjam-qiniu/sdk/io.php");
	}

	list($ret, $err) = Qiniu_PutFile($upToken, $key, $file);
	if ($err !== null) {
		$msg = ' 发生错误：<span style="color:red">'.$err->Err.'</span><br />';
	} else {
		$msg = ' 上传成功<br />';
	}
	return $msg;
}

function wpjam_qiniutek_put($key, $str){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$putPolicy = new Qiniu_RS_PutPolicy($qiniutek_bucket);
	$upToken = $putPolicy->Token(null);

	if(!function_exists('Qiniu_Put')){
		require_once(WP_CONTENT_DIR."/plugins/wpjam-qiniu/sdk/io.php");
	}

	list($ret, $err) = Qiniu_Put($upToken, $key, $str, null);

	if ($err !== null) {
		$msg = ' 发生错误：<span style="color:red">'.$err->Err.'</span><br />';
	} else {
		$msg = ' 上传成功<br />';
	}
	return $msg;
}

function wpjam_get_qiniutek_client(){

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	if(!class_exists('Qiniu_MacHttpClient')){
		require_once(WP_CONTENT_DIR."/plugins/wpjam-qiniu/sdk/rs.php");
	}	
	Qiniu_SetKeys($wpjam_qiniutek['access'], $wpjam_qiniutek['secret']);
	$qiniutek_client = new Qiniu_MacHttpClient(null);

	return $qiniutek_client;
}