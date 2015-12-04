<?php
error_reporting(5);
/*
Plugin Name: 七牛图床
Plugin URI:  http://www.yangzhongchao.com/works/qiniu-tuchaung/
Description:  七牛云图床插件：在编辑器页面上传图片至七牛服务器
Author: 羊种草
Author URI: http://www.yangzhongchao.com
Version: 0.5
*/

define('PLUGIN_URL', plugins_url('', __FILE__));
define('PLUGIN_DIR', plugin_dir_path(__FILE__));

//设置菜单
function qiniu_tuchuang_menu(){
    add_options_page('qiniu', '七牛图床', 'manage_options', 'qiniu-tuchaung', 'qiniu_tuchuang_options');
}
add_action('admin_menu', 'qiniu_tuchuang_menu');

function qiniu_tuchuang_options(){
?>  
    <h1>图床设置</h1>
    <form method="post" action="options.php">
    <?php 
     settings_fields( 'qiniu_options');
     do_settings_sections( 'qiniu-settings' );
     ?>
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
	</form>
<?php 
}

add_action('admin_init', 'register_qiniu_settings');

function register_qiniu_settings() {
	register_setting('qiniu_options', 'qiniu_options');
	add_settings_section('qiniu_defaults', '默认设置', 'defaults_output', 'qiniu-settings');
	add_settings_field('qiniu-tuchaung-host', '七牛绑定域名', 'host_output', 'qiniu-settings','qiniu_defaults');
    add_settings_field('qiniu-tuchaung-hostprefix', '前缀', 'prefix_output', 'qiniu-settings','qiniu_defaults');
	add_settings_field('qiniu-tuchaung-hostbucket', 'bucket', 'bucket_output', 'qiniu-settings','qiniu_defaults');
	add_settings_field('qiniu-tuchaung-hostimgurl', '图片链接形式', 'imgurl_output', 'qiniu-settings','qiniu_defaults');
	add_settings_field('qiniu-tuchaung-hostaccesskey', 'AccessKey', 'accesskey_output', 'qiniu-settings','qiniu_defaults');
	add_settings_field('qiniu-tuchaung-hostsecretkey', 'SecretKey', 'secretkey_output', 'qiniu-settings','qiniu_defaults');
}

function host_output() {
	$options = get_option('qiniu_options');
	echo "<input id='host' name='qiniu_options[host]' size='50' type='text' value='{$options['host']}' />";
    echo "<div>设置为你在七牛绑定的域名,没绑定则填写七牛域名<br />仅作显示图片的链接使用,注意要域名前面要加上<strong>  http:// </strong></div>";
}
function prefix_output() {
	$options = get_option('qiniu_options');
	echo "<input id='prefix' name='qiniu_options[prefix]' size='50' type='text' value='{$options['prefix']}' />";
	echo "<div>如果你想像七牛一样在上传的图片前加前缀则填写<br />例如:<strong>img</strong>,多个前缀:<strong>img/2015</strong>,不想则留空<br/>添加前缀后图片的链接类似于：七牛绑定域名/前缀/图片名称 </div>";

}
function bucket_output() {
	$options = get_option('qiniu_options');
	echo "<input id='bucket' name='qiniu_options[bucket]' size='50' type='text' value='{$options['bucket']}' />";
}
function accesskey_output() {
	$options = get_option('qiniu_options');
	echo "<input id='accesskey' name='qiniu_options[accesskey]' size='50' type='text' value='{$options['accesskey']}' />";
    echo "<div>在七牛帐号-密钥中查看</div>";
}
function secretkey_output() {
	$options = get_option('qiniu_options');
	echo "<input id='secretkey' name='qiniu_options[secretkey]' size='50' type='text' value='{$options['secretkey']}' />";
    echo "<div>在七牛帐号-密钥中查看</div>";
}

function imgurl_output() {
    $options = get_option('qiniu_options');
    if($options['imgurl']) { $checked = ' checked="checked" '; } else { $checked = ''; }
    echo "<input ".$checked." id='imgurl' name='qiniu_options[imgurl]' type='checkbox' />";
    echo "<div>选择后,图片链接原始地址,图片地址类似<strong>&lt;a&gt;&lt;img&gt;&lt;/a&gt;</strong><br/>不选则无链接,类似<strong>&lt;img&gt;</strong></div>";
}

//上传窗口
add_action('submitpost_box', 'qiniu_tuchuang_script');
function qiniu_tuchuang_script(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'plupload-all' );
    wp_enqueue_script( 'qiniu', plugins_url('js/qiniu.js', __FILE__));
    wp_enqueue_script( 'qiniu-main', plugins_url('js/main.js', __FILE__ ),array( 'jquery' ));
}    
 
add_action('submitpost_box', 'qiniu_tuchuang_post_box');
function qiniu_tuchuang_post_box(){
    add_meta_box('qiniu_tuchuang_div', __('七牛图床'), 'qiniu_tuchuang_post_html', 'post', 'side');
}

add_action('submitpost_box', 'qiniu_tuchuang_style');
function qiniu_tuchuang_style(){
	wp_enqueue_style('qiniu_tuchuang_style', plugins_url('css/qiniu_tuchuang.css', __FILE__));
}

function qiniu_tuchuang_post_html(){
	$options = get_option('qiniu_options');
    $host = $options['host'];
    $bucket = $options['bucket'];
    $prefix = $options['prefix'];
    $accesskey = $options['accesskey'];
    $secretkey = $options['secretkey'];
    $imgurl = $options['imgurl'];
    echo "<script>";
    if (!empty($prefix)) {
        echo 'var savekey = true;';
    }else echo 'var savekey = false;';
    if (!empty($imgurl)) {
        echo 'var imgurl = true;';
    }else echo 'var imgurl = false;';
    echo 'var host =\''.  $host . '\';';
    echo 'var uptokenurl=\''. plugins_url('uptoken.php', __FILE__) . '?sk='. $secretkey . '&ak=' . $accesskey . '&bucket=' . $bucket . '&prefix=' . $prefix .'\'</script>';
    echo '<div id="qiniu_tuchuang_post"><div id="pickfiles" href="#" ><span id="spantxt">上传图片</span></div></div>';
}
?>