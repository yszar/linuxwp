<?php

add_action( 'admin_menu', 'wpjam_qiniutek_admin_menu');
function wpjam_qiniutek_admin_menu() {
	add_menu_page(						'七牛镜像存储',			'七牛镜像存储',	'manage_options',	'wpjam-qiniutek',			'wpjam_qiniutek_setting_page',	'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IuWbvuWxgl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjE1My44OHB4IiBoZWlnaHQ9IjEwMy4zcHgiIHZpZXdCb3g9IjAgMCAxNTMuODggMTAzLjMiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE1My44OCAxMDMuMyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMTUzLjY4NCwwLjc5MWMtMC4yNjYtMC40OTctMC44My0wLjk2My0xLjg1LTAuNzI4Yy0xLjk4NCwwLjQ2NS0yNS4xMzksMzkuMTY5LTc0Ljg4NywzOC4wOThoLTAuMDE2DQoJYy05LjQ1MiwwLjIwMy0xOC42MjgtMS4wMy0yNi4xODgtMy4xNTZsLTQuMzI3LTEzLjk4YzAsMC0wLjgwMS0zLjQzOC00LjExNS01LjE5NmMtMi4yOTMtMS4yMDctMy42MzEtMC44MjEtMy45MTEtMC40ODQNCgljLTAuMjUyLDAuMzE2LTAuMjA0LDAuNjUzLTAuMjA0LDAuNjUzbDIuMDUzLDE1LjI2NkMxNC44OTEsMjAuNCwzLjQ3NCwwLjQwMywyLjA0MiwwLjA2M2MtMS4wMTUtMC4yMzUtMS41NzgsMC4yMy0xLjg0NSwwLjcyOA0KCWMtMC40MjcsMC44LTAuMDIxLDEuOTI5LTAuMDIxLDEuOTI5YzcuMTUyLDIxLjMxMSwyMi41ODcsMzguMTI2LDQyLjU2Nyw0Ny4wOWw1LjUwOSwzNi45MzENCgljMC4zNzQsMTAuNTMzLDcuNDE2LDE2LjU1OSwxNi41NjksMTYuNTU5aDI3LjM5YzkuMTUzLDAsMTYuMDA4LTYuNTg4LDE2LjU3NS0xNi41NTlsNS4wMTktMzAuNTM3YzAsMCwwLjA4NS0wLjQyNi0wLjE2Ni0wLjYwNA0KCWMtMC4zMTItMC4xOTEtMi42OTgtMC4yNjQtNy41MjgsMy4zMTRjLTQuODMsMy41ODItNi40NjMsOC43NTYtNi40NjMsOC43NTZzLTUuMjE5LDEyLjY5OS02LjU5MSwxOC4xMjUNCgljLTEuNDQ0LDUuNzEzLTcuODUsNS4yMDMtNy44NSw1LjIwM3MtOS43ODksMC0xNC42ODEsMGMtNC44OTUsMC01LjM5Ni00LjM5NS01LjM5Ni00LjM5NWwtOS4xNi0zMi4yMTUNCgljNi42NzEsMS42ODYsMTMuNjg0LDIuNTY4LDIwLjk2MiwyLjU0M2gwLjAxNmMzNS45NzUsMC4xNDEsNjUuODk3LTIxLjg4Myw3Ni43NTYtNTQuMjEyQzE1My43MDMsMi43MTksMTU0LjExLDEuNTksMTUzLjY4NCwwLjc5MXoiDQoJLz4NCjwvc3ZnPg0K'	);
	add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储设置',		'基本设置',		'manage_options',	'wpjam-qiniutek',			'wpjam_qiniutek_setting_page'	);
	if( wpjam_qiniutek_get_setting('bucket') && wpjam_qiniutek_get_setting('access') && wpjam_qiniutek_get_setting('secret') ){
		add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 文件更新',			'文件更新',		'manage_options',	'wpjam-qiniutek-update','wpjam_qiniutek_update_page'	);
		add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 上传 Robots.txt',	'Robots.txt',	'manage_options',	'wpjam-qiniutek-robots','wpjam_qiniutek_robots_page'	);
	}
	add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 充值优惠码',		'充值优惠码',		'manage_options',	'wpjam-qiniutek-coupon',	'wpjam_qiniutek_coupon_page'	);	
}

function wpjam_qiniutek_setting_page() {
	settings_errors();
	$labels =wpjam_qiniutek_get_option_labels();
	wpjam_option_page($labels, $title='七牛镜像存储设置', $type='tab');
}

add_action( 'admin_init', 'wpjam_qiniutek_admin_init' );
function wpjam_qiniutek_admin_init() {
	wpjam_add_settings(wpjam_qiniutek_get_option_labels());
}

function wpjam_qiniutek_get_option_labels(){
	$option_group               =   'wpjam-qiniutek-group';
	$option_name = $option_page =   'wpjam-qiniutek';
	$field_validate				=	'wpjam_qiniutek_validate';

	$qiniutek_fields = array(
		'host'	=> array('title'=>'七牛绑定的域名',	'type'=>'text',		'description'=>'设置为你在七牛绑定的域名即可。<strong>注意要域名前面要加上 http://</strong>。<br />如果博客安装的是在子目录下，比如 http://www.xxx.com/blog，这里也需要带上子目录 /blog '),
		'bucket'=> array('title'=>'七牛空间名',		'type'=>'text',		'description'=>'设置为你在七牛绑定的空间名即可。'),
		
		'access'=> array('title'=>'ACCESS KEY',		'type'=>'text'),
		'secret'=> array('title'=>'SECRET KEY',		'type'=>'text'),
	);

	$local_fields = array(		
		'exts'	=> array('title'=>'扩展名',			'type'=>'text',		'description'=>'设置要缓存静态文件的扩展名，请使用 | 分隔开，|前后都不要留空格。'),
		'dirs'	=> array('title'=>'目录',			'type'=>'text',		'description'=>'设置要缓存静态文件所在的目录，请使用 | 分隔开，|前后都不要留空格。'),
		'local'	=> array('title'=>'静态文件域名',		'type'=>'text',		'description'=>'如果图片等静态文件存储的域名和网站不同，可通过该字段设置。<br />使用该字段设置静态域名之后，请确保 JS 和 CSS 等文件也在该域名下，否则将不会加速。'),
		'remote'=> array('title'=>'保存远程图片',		'type'=>'checkbox',	'description'=>'自动将远程图片镜像到七牛，该功能需要你的博客支持固定链接。<br />如果上面设置的静态文件域名和博客域名不一致，该功能可能出问题。
			'),		
		'jquery'=> array('title'=>'使用 jQuery 2.0',	'type'=>'checkbox',	'description'=>'jQuery 2.0 不再支持 IE 6/7/8，如果你的网站是面向移动或者不再向低端 IE 用户提供服务，可以勾选该选项。'),
	);

	$thumb_fields = array(
		'advanced'	=> array('title'=>'高级缩略图',	'type'=>'checkbox',	'description'=>'启用高级缩略图，可以设置分类和标签缩略图。'),
		'default'	=> array('title'=>'默认缩略图',	'type'=>'text',		'description'=>'如果日志没有特色图片，没有第一张图片，也没用高级缩略图的情况下所用的缩略图。可以填本地或者七牛的地址！'),
	);

	if(isset($_GET['debug'])){
		$thumb_fields['timthumb']	= array('title'=>'timthumb',	'type'=>'checkbox',	'description'=>'启用 timthumb 测试！');
	}

	$sections = array( 
    	'qiniutek-section'	=>array('title'=>'七牛设置',		'fields'=>$qiniutek_fields,	'callback'=>'wpjam_qiniutek_section_callback',	),
    	'local-section'		=>array('title'=>'本地设置',		'fields'=>$local_fields,	'callback'=>'',	),
    	'thumb-section'		=>array('title'=>'缩略图设置',	'fields'=>$thumb_fields,	'callback'=>'',	)
	);

	$sections =  apply_filters('qiniutek_setting',$sections);

	return compact('option_group','option_name','option_page','sections','field_validate');
}

function wpjam_qiniutek_section_callback(){
	echo '<p><strong">*点击获取：<a style="color:red;" href="http://wpjam.com/go/qiniuguide">七牛镜像云存储WordPress插件使用指南</a></strong>，大版本更新，内容同步更新。</p>';
}

add_filter('wpjam_defaults', 'wpjam_qiniutek_get_defaults', 10, 2);
function wpjam_qiniutek_get_defaults($defaults, $option_name){
	if($option_name != 'wpjam-qiniutek') return $defaults;

	return wpjam_qiniutek_get_option_defaults();	
}

function wpjam_qiniutek_get_option_defaults(){
 	$defaults = array(
		'exts'		=> 'js|css|png|jpg|jpeg|gif|ico', 
		'dirs'		=> 'wp-content|wp-includes',
		'local'		=> home_url(),
	);

	return  apply_filters('qiniutek_defaults',$defaults);
}

function wpjam_qiniutek_validate( $wpjam_qiniutek ) {
	$current = get_option( 'wpjam-qiniutek' );

	foreach (array('remote','jquery','advanced','timthumb') as $key ) {
		if(empty($wpjam_qiniutek[$key])){ //checkbox 未选，Post 的时候 $_POST 中是没有的，
			$wpjam_qiniutek[$key] = 0;
		}
	}

	flush_rewrite_rules();

	return $wpjam_qiniutek;
}

/**
* 日志缩略图
**/

/**
* 优惠码 
**/

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

/**
 * 更新缓存 
 **/
function wpjam_qiniutek_update_page(){
	global $plugin_page;

	$updates = '';
	
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
			<li>图片缩略图更新七牛是按照按照队列顺序进行的，需要等待一定的时间，只要看到原图更新即可。</li>
		</ol>
	</div>
<?php
}

/**
 * 提交 Robots 
 **/
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