<?php
/*
Plugin Name: Content Index
Plugin URI: http://codante.org/wordpress-plugin-wp-content-index
Description: 在文章的开头添加内容索引，索引全部根据heading标签生成。
Version: 2.60
Author: 莳子
Author URI: http://codante.org
*/

//init text domain
load_textdomain('content-index', dirname(__FILE__) . "/wp-content-index-" . get_locale() . ".mo");

function content_index_version(){
	return "2.60";
}

function content_index_admininit(){
   if (current_user_can('manage_options'))
 		add_options_page("Content Index", __("Content Index", "content-index"), 8, __FILE__, 'content_index_option');
}

function content_index_option(){
	global $_POST;
	?>
<div class="wrap">
	<h2><?php _e("Content Index", "content-index");?> <font size="1"><?php echo content_index_version();?></font></h2>
	<?php
	if(isset($_POST["action"])):
	
		$_DATA = array();
	
		if ($_POST['action'] == 'save'):
			if(content_index_save()):
				echo '<div class="updated" style="padding:10px;">'.__('Save succeed', 'content-index').'</div>';
			endif;
		endif;
	endif;
	
	$_DATA = array();
	$_DATA = content_index_get_options();
	
?>
<script type="text/javascript">
function check_fixed(self,target){var self=document.getElementById(self);var target=document.getElementById(target);if(self.checked==false){target.checked=false;target.disabled=true;}else{target.disabled=false;}}
</script>
<style type="text/css">
.widefat{line-height:146%}.widefat tbody th{vertical-align:middle}.widefat tbody th,.widefat tbody td{padding:5px 10px}.widefat label{padding-left:6px}.widefat tbody th{text-align:right}.widefat tbody td{text-align:left}
</style>
<form method="post">
<div class="widget">
	<table class="widefat" width="100%" border="0" cellspacing="10" cellpadding="0">
		<tbody>
        	<tr>
				<th><?php _e("Default status", "content-index");?></th>
				<td>
					<input id="ds_0" name="ds" type="radio" value="1"<?php if($_DATA["ds"] === true) echo ' checked="checked"'?> /><label for="ds_0"><?php _e("Enabled", "content-index");?></label><span> - <?php _e("All articles/pages valid, Individually shielded.", "content-index");?></span><br />
					<input id="ds_1" name="ds" type="radio" value="0"<?php if($_DATA["ds"] === false || !isset($_DATA["ds"])) echo ' checked="checked"'?> /><label for="ds_1"><?php _e("Disable", "content-index");?></label><span> - <?php _e("All articles/pages invalid, Individually enabled.", "content-index");?></span><br />
                    <span style="color:#999;text-indent:1em"><?php _e("* Using two sets of configuration, will not interfere with each other.(Do not frequently change this option)", "content-index");?></span>
                </td>
			</tr>
			<?php /*
			<tr>
				<th><?php _e("DB cache", "content-index");?></th>
				<td>
					<input id="dbcache" name="dbcache" type="checkbox" value="1"<?php if($_DATA["dbcache"] === true) echo ' checked="checked"'?> /><label for="dbcache"><?php _e("Enable", "content-index");?></label>
				</td>
			</tr>
			*/ ?>
			<tr>
				<th><?php _e("Position", "content-index");?></th>
				<td>
					<input id="position_0" name="position" type="radio" value="left"<?php if($_DATA["position"] == 'left') echo ' checked="checked"'?> /><label for="position_0"><?php _e("Left", "content-index");?></label>
					<input id="position_1" name="position" type="radio" value="top"<?php if($_DATA["position"] == 'top' || !isset($_DATA["position"])) echo ' checked="checked"'?> /><label for="position_1"><?php _e("Top", "content-index");?></label>
					<input id="position_2" name="position" type="radio" value="right"<?php if($_DATA["position"] == 'right') echo ' checked="checked"'?> /><label for="position_2"><?php _e("Right", "content-index");?></label></td>
			</tr>
			<tr>
				<th><?php _e("List number", "content-index");?></th>
				<td>
					<input id="listnum" name="listnum" type="checkbox" value="1"<?php if($_DATA["listnum"] === true) echo ' checked="checked"'?> /><label for="listnum"><?php _e("Add a serial number in the index entry.", "content-index");?></label><br />
                	<input id="signheading" name="signheading" type="checkbox" value="1"<?php if($_DATA["signheading"] === true) echo ' checked="checked"'?> /><label for="signheading"><?php _e("Add a serial number in the heading of the article.", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th><?php _e("Title", "content-index");?></th>
				<td>
					<input onchange="check_fixed('title','title_text')" id="title" name="title" type="checkbox" value="1"<?php if($_DATA["title"] === true) echo ' checked="checked"'?> /><label for="title"><?php _e("Enable title.", "content-index");?></label><br />
					<span style="font-family:Verdana, Geneva, sans-serif">└&nbsp;</span><input<?php if($_DATA["title"] === false) echo ' disabled="disabled"';?> id="title_text" name="title_text" type="text" value="<?php echo $_DATA["title_text"];?>" /><label for="title_text"><?php _e("Custom for title.", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th><?php _e("Show Levels", "content-index");?></th>
				<td>
					<input id="level" name="level" type="checkbox" value="1"<?php if($_DATA["level"] === true) echo ' checked="checked"'?> /><label for="level"><?php _e("Enable", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th><?php _e("Show Hide button", "content-index");?></th>
				<td>
					<input id="hidebt" name="hidebt" type="checkbox" value="1"<?php if($_DATA["hidebt"] === true) echo ' checked="checked"'?> /><label for="hidebt"><?php _e("Enable", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th><?php _e("Show empty", "content-index");?></th>
				<td>
					<input id="showempty" name="showempty" type="checkbox" value="1"<?php if($_DATA["showempty"] === true) echo ' checked="checked"'?> /><label for="showempty"><?php _e("Enable", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th><?php _e("Show mirage", "content-index");?></th>
				<td>
					<input id="showmirage" name="showmirage" type="checkbox" value="1"<?php if($_DATA["showmirage"] === true) echo ' checked="checked"'?> /><label for="showmirage"><?php _e("Enable", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th><?php _e("Excerpt", "content-index");?></th>
				<td>
					<input onchange="check_fixed('excerpt_append','excerpt_fixed')" id="excerpt_append" name="excerpt_append" type="checkbox" value="1"<?php if($_DATA["excerpt_append"] === true) echo ' checked="checked"'?> /><label for="excerpt_append"><?php _e("Push excerpt into content.", "content-index");?></label><br />
					<span style="font-family:Verdana, Geneva, sans-serif">└&nbsp;</span><input<?php if($_DATA["excerpt_append"] === false) echo ' disabled="disabled"';?> id="excerpt_fixed" name="excerpt_fixed" type="checkbox" value="1"<?php if($_DATA["excerpt_fixed"] === true) echo ' checked="checked"'?> /><label for="excerpt_fixed"><?php _e("Fixed excerpt in content index.", "content-index");?></label>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input type="hidden" name="action" value="save" />
					<input class="button-secondary" type="submit" value="  <?php _e("Save", "content-index");?>  " />
				</td>
			</tr>
		</tbody>
	</table>
</div>
</form>
<div class="widget">
	<div style="margin:12px; line-height: 1.5em;">
		<span><?php _e("About the plugin: ", "content-index");?></span><a href="http://codante.org/wordpress-plugin-wp-content-index" target="_blank">http://codante.org/wordpress-plugin-wp-content-index</a>
	</div>
</div>
</div>
<script type="text/javascript">
(function($){$(".widefat tr:nth-child(even)").addClass("alternate");})(jQuery);
</script>
<?php
}

function content_index_save(){
	global $_POST;
	$_DATA["position"]         = $_POST['position'];
	$_DATA["ds"]               = $_POST['ds'] == '1' ? true : false; //1:开启, 0:关闭
	$_DATA["dbcache"]          = $_POST['dbcache'] == '1' ? true : false;
	$_DATA["listnum"]          = $_POST['listnum'] == '1' ? true : false;
	$_DATA["level"]            = $_POST['level'] == '1' ? true : false;
	$_DATA["title"]            = $_POST['title'] == '1' ? true : false;
	$_DATA["title_text"]       = trim($_POST['title_text']);
	$_DATA["hidebt"]           = $_POST['hidebt'] == '1' ? true : false;
	$_DATA["showempty"]        = $_POST['showempty'] == '1' ? true : false;
	$_DATA["showmirage"]       = $_POST['showmirage'] == '1' ? true : false;
	$_DATA["excerpt_append"]   = $_POST['excerpt_append'] == '1' ? true : false;
	$_DATA["excerpt_fixed"]    = $_POST['excerpt_fixed'] == '1' ? true : false;
	$_DATA["signheading"]      = $_POST['signheading'] == '1' ? true : false;
	
	update_option("content_index_option", $_DATA);
	
	return true;
}

function content_index_init(){
	global $post;
	if (is_object($post)) $post_id = $post->ID;
	
	$_CONFIGURE = content_index_get_options();
	if(is_singular()){
		
		if(
			($_CONFIGURE["ds"] && get_post_meta($post_id, '_content_index_disable', true) != '1')
			||
			(!$_CONFIGURE["ds"] && get_post_meta($post_id, '_content_index_enable', true) == '1')
		){
			content_index_style();
			add_filter('the_content', 'content_index_conversion', 500); //优先级置后
		}
	}
}

/**
 * 载入样式
 */
function content_index_style(){
	$currentPath = dirname(__FILE__);
	echo '<link rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins' . substr($currentPath, strrpos($currentPath, '/')) . '/style.css' . '" type="text/css" media="all" />' . "\n";
}

/**
 * 避免重复访问数据库
 */
function content_index_get_options(){
	global $content_index_options;
	!isset($content_index_options) && $content_index_options = get_option("content_index_option");
	return $content_index_options;
}

function content_index_conversion($content){
	$_CONFIGURE = content_index_get_options();
	if($_CONFIGURE["excerpt_append"]):
		if($_CONFIGURE["excerpt_fixed"]):
			$content = excerpt_into_content($content);
			$content = content_index_create($content);
		else:
			$content = content_index_create($content);
			$content = excerpt_into_content($content);
		endif;
	else:
		$content = content_index_create($content);
	endif;
	return $content;
}

function excerpt_into_content($content){
	global $post;
	$excerpt = trim($post->post_excerpt);
	if(!empty($excerpt)){
		$content = "<!--{WCI-Description}-->\n<h2>" . __("Description", "content-index") . "</h2>\n<p>" . $excerpt . "</p>\n<!--{/WCI-Description}-->\n" . $content;
	}
	return $content;
}

function content_index_analysis($content){

	$_CONFIGURE = content_index_get_options();
	
	$hasExcerpt = content_index_GCBR("/WCI-Description/", $content, 1);
	$hasExcerpt = empty($hasExcerpt) ? false : true;
	
	$hlist = content_index_GCBR("/<h(\d)([^>]*)>(.*)<\/h\d>/isU", $content, 1);
	
	if(empty($hlist)) return array();
	
	$min_level = 99;
	$new_hlist = array();
	$_tp = array();
	$_tp_level = 0;
	$_tp_sign = array();
	
	foreach($hlist as $hk => $hv) {
	
		$index = $hk + 1;
		
		$hv[2] = preg_match("/id=/", $hv[2]) ? preg_replace("/.*id=\"?([^\"]*)\"?.*/is", "$1", $hv[2]) : '';		
		$hv[3] = trim(strip_tags($hv[3]));
		
		$replace = preg_replace("/<\/?a[^>]*>/i", '', $hv[0]);
		
		if(empty($hv[2])) {
		
			if(empty($hv[3])){
				$hv[3] = __("Index", "content-index") . "_" . $index;
				$hv["empty"] = 1;
			}
			
			$idIndex = $hv[3];
			
			$replace = preg_replace("/(<h\d)([^>]*>.*<\/h\d>)/is", "$1".' id="'.$idIndex.'"'."$2", $replace, 1);
		} else {
			$idIndex = $hv[2];
		}
		
		$hv["index"] = $index;
		$hv["resource"] = $hv[0];
		$hv["replace"] = $replace;
		$hv["slug"] = $idIndex;
		$hv["content"] = $hv[3];
		$hv["level"] = intval($hv[1]);
		
		#$hv["nslug"] = 'n_'.md5($idIndex);	#name识别
		#$hv["replace"] = preg_replace("/(<h\d[^>]*>)(.*)(<\/h\d>)/is", "$1".'<a name="'.$hv["nslug"].'"></a><span>'."$2".'</span>'."$3", $hv["replace"], 1);
		
		#确认最小(浅)层级时排除摘要
		if(!($_CONFIGURE["excerpt_fixed"] && $hasExcerpt && $hk == 0)){
			$min_level = $min_level > $hv["level"] ? $hv["level"] : $min_level;
		}
		
		#清除无用项
		$hv[0] = $hv[1] = $hv[2] = $hv[3] = null;
		unset($hv[0], $hv[1], $hv[2], $hv[3]);
		
		$deep = $hv["level"] - $_tp_level;
		
		if($_tp_level > 0 && $deep > 1){
			for(;$deep > 1; $deep--){
				$new_hlist[] = array("level" => $hv["level"] - $deep + 1);
			}
		}
		$new_hlist[] = $hv;

		$_tp_level = $hv["level"];
	}

	$hlist = $new_hlist;
	$new_hlist = null;unset($new_hlist);
	
	$etp_level = $min_level - 1;
	#将摘要置于最小(浅)层级
	if($_CONFIGURE["excerpt_fixed"]) $hlist[0]["level"] = $min_level;
	#去掉没有出现的上一级(如： h1为第一级, 但h1从未出现过, 最高级只到h2, 那么h2将作为第一级出现)
	if($etp_level > 0){
		foreach($hlist as $hk => $hv){
			$hlist[$hk]["level"] -= $etp_level;
		}
	}
		
	foreach($hlist as $hk => $hv){
		
		$parent = $hv["level"] - 1;
		if($parent > 0){
			#处理子集的结构
			while(!isset($_tp[$parent]) && $parent > 1){
				$hv = array('child'=>array($hv));
				$parent --;
			}
			
			#把元素继承在父级，原来的位置保留。
			$hlist[$_tp[$parent]]["child"][$hk] = $hv;
			#在原来的元素位置上创建指针，保持索引关系不变，方便处理更深的层级。
			$hlist[$hk] = &$hlist[$_tp[$parent]]["child"][$hk];
		}
		
		$_tp_sign = array_slice($_tp_sign, 0, $hv["level"], TRUE);
		if(empty($_tp_sign[$hv["level"]])){
			$_tp_sign[$hv["level"]] = 0;
		}
		$_tp_sign[$hv["level"]]++;
		$hlist[$hk]["sign"] = join(".", $_tp_sign);
		
		$_tp[$hv["level"]] = $hk;
		$_tp = array_slice($_tp, 0, $hv["level"], TRUE);
	}
	
	#清理指针项
	foreach($hlist as $hk => $hv)
		if($hv["level"] > 1)
			unset($hlist[$hk]);
	
	return $hlist;
}

function content_index_heading_list_deep($list, &$content, $conf = array(), $deep = 1){

	foreach($list as $hk => $hv){
		
		if($conf["signheading"]){
			$pos = strpos($hv["replace"], '>');
			$hv["replace"] = substr($hv["replace"], 0, $pos + 1)."<em>".$hv["sign"].".</em>".substr($hv["replace"], $pos + 1);
			$hv["replace"] = preg_replace("/(<h\d)([^>]*>.*<\/h\d>)/is", "$1".' class="content-index-heading content-index-heading-level-'.$deep.'"'.' name=""'."$2", $hv["replace"], 1);
		}
		
		$content = str_replace($hv["resource"], $hv["replace"], $content);
		
		$v .= '<li class="content-index-level-'.$deep.'">';
		
		$v .= ''
			.'<a'
			.((($hv["index"] || $conf["showmirage"]) && (!isset($hv["empty"]) || $conf["showempty"])) ? '' : ' class="hide"')
			.(empty($hv["index"]) ? ' onclick="return false;"' : '')
			.' href="'.get_permalink()."#".$hv["slug"].'"'
			.' title="'.$hv["content"].'"'
			.'>'
				.( $conf["listnum"] ? '<em>'.$hv["sign"].'</em>' : '' )
				.'<span>'.$hv["content"].'</span>'
			.'</a>';

		if(isset($hv["child"])){
			$v .= '<ul class="children">' . content_index_heading_list_deep( $hv["child"], $content, $conf, $deep + 1) . '</ul>';
		}
		$v .= '</li>';
	}
	return $v;
}

function content_index_heading_list($list, &$content, $conf = array(), $deep = 1){
	
	foreach($list as $hk => $hv){
		
		if($conf["signheading"]){
			$pos = strpos($hv["replace"], '>');
			$hv["replace"] = substr($hv["replace"], 0, $pos + 1)."<em>".$hv["sign"].".</em>".substr($hv["replace"], $pos + 1);
			$hv["replace"] = preg_replace("/(<h\d)([^>]*>.*<\/h\d>)/is", "$1".' class="content-index-heading content-index-heading-level-'.$deep.'"'."$2", $hv["replace"], 1);
		}
		
		$content = str_replace($hv["resource"], $hv["replace"], $content);
		
		$v .= '<li class="content-index-level-'.$deep.'">';
		
		$v .= ''
			.'<a'
			.((($hv["index"] || $conf["showmirage"]) && (!isset($hv["empty"]) || $conf["showempty"])) ? '' : ' class="hide"')
			.(empty($hv["index"]) ? ' onclick="return false;"' : '')
			.' href="'.get_permalink()."#".$hv["slug"].'"'
			.' title="'.$hv["content"].'"'
			.'>'
				.( $conf["listnum"] ? '<em>'.$hv["index"].'</em>' : '' )
				.'<span>'.$hv["content"].'</span>'
			.'</a>';
		$v .= '</li>';
		
		if(isset($hv["child"])){
			$v .= content_index_heading_list( $hv["child"], $content, $conf, $deep + 1);
		}
	}
	return $v;
}

function content_index_create($content){
	$hlist = content_index_analysis($content);
	
	if(!empty($hlist)):
	
		$_CONFIGURE = content_index_get_options();
		
		if($_CONFIGURE["level"]){
			$hunits = content_index_heading_list_deep($hlist, $content, $_CONFIGURE, 1);
		} else {
			$hunits = content_index_heading_list($hlist, $content, $_CONFIGURE, 1);
		}

		if(empty($hunits)):
			$contentIndex = '';
		else:
			
			$style = '';
			if($_CONFIGURE["position"] == 'top'):
				$style = ' style="float:left;"';
			elseif($_CONFIGURE["position"] == 'left'):
				$style = ' style="margin:0 10px 10px 0;float:left;"';
			elseif($_CONFIGURE["position"] == 'right'):
				$style = ' style="margin:0 0 10px 10px;float:right;"';
			endif;

			$contentIndex = '<div id="content-index" class="content-index"'.$style.'>';
			
			if($_CONFIGURE["title"]):
				$contentIndex .= '<div class="content-index-title">'.(empty($_CONFIGURE["title_text"]) ? __("Content Index", "content-index") : $_CONFIGURE["title_text"]).'</div>';
			endif;
			
			if($_CONFIGURE["hidebt"]):
				$contentIndex .= ''
					.'<span class="content-index-toctoggle">[<a id="content-index-togglelink" href="javascript:content_index_toggleToc()">'.__("hide", "content-index").'</a>]</span>'
					.'
<script type="text/javascript" language="javascript">
window.content_index_showTocToggle=true;function content_index_toggleToc(){var tts="'.__("show", "content-index").'";var tth="'.__("hide", "content-index").'";if(window.content_index_showTocToggle){window.content_index_showTocToggle=false;document.getElementById("content-index-contents").style.display="none";document.getElementById("content-index-togglelink").innerHTML=tts}else{window.content_index_showTocToggle=true;document.getElementById("content-index-contents").style.display="block";document.getElementById("content-index-togglelink").innerHTML=tth}}
</script>
';
			endif;
			
			$contentIndex .= '<ul id="content-index-contents">'.$hunits.'</ul>';
			$contentIndex .= '</div>';
			
			if($_CONFIGURE["position"] == 'top'):
				$contentIndex .= '<div style="clear:both;overflow:hidden;height:2em"></div>';
			endif;
			
			$contentIndex .= "\n";
			
		endif;
		$content = $contentIndex . $content;
	endif;
	
	return $content;
}

/**
 * Get content by regex
 */
function content_index_GCBR($regex, &$content, $rank = 0){
	$ranks = array(PREG_PATTERN_ORDER, PREG_SET_ORDER);
	$regex && preg_match_all($regex, $content, $matche, $ranks[$rank]);
	return $matche;
}

/**
 * Get some feedback for me. 获取一些您的基本信息
 * Help us better serve you. 以便于我们更好的问您服务
 */
function content_index_feedback(){
	if(function_exists( wp_remote_post )){
		wp_remote_post("http://codante.org/stat.php", array('body'=>array("slug"=>"wp-content-index","site"=>get_bloginfo("url"),"version"=>get_bloginfo("version"),"email"=>get_bloginfo("admin_email"), "check"=>"9816O1R5I+HZ5rw9G8qpTvPAIvf+RA8HWSsZEJ7sfziGmwqYjBvD84FzL9M2", "remarks"=>"plugin_version:" . content_index_version())));
	}
}
register_activation_hook( __FILE__, 'content_index_feedback' );   #You can mask this line of code.

function content_index_metabox(){
	//if( function_exists('add_meta_box') ) {
		if( function_exists('get_post_types')){		
			$mrt_pts = get_post_types('','names');
			foreach ($mrt_pts as $pt) {
				if($pt == 'post' || $pt == 'page'){
					add_meta_box('content-index', __("Content Index", "content-index"), 'content_index_meta', $pt);
				}
			}
		} else {
			add_meta_box('content-index', __("Content Index", "content-index"), 'content_index_meta', 'post');
			add_meta_box('content-index', __("Content Index", "content-index"), 'content_index_meta', 'page');
		}

	//} else {
		//add_action('dbx_post_advanced', array($aiosp, 'add_meta_tags_textinput'));
		//add_action('dbx_page_advanced', array($aiosp, 'add_meta_tags_textinput'));
	//}
}

function content_index_meta(){
	global $post;
	$post_id = $post;
	if (is_object($post_id)) $post_id = $post_id->ID;
	
	$_CONFIGURE = content_index_get_options();
	
	if( $_CONFIGURE["ds"] ){ //默认开启状态
		$key = 'content_index_disable'; //关闭
		$msg = array(
			__('Default state for all enabled', 'content-index'),
			__('Disabled in this article/page', 'content-index'),
		);
	} else {
		$key = 'content_index_enable'; //开启
		$msg = array(
			__('Default state for all disabled', 'content-index'),
			__('Enabled in this article/page', 'content-index'),
		);
	}
	
	$status = htmlspecialchars(stripcslashes(get_post_meta($post_id, '_'.$key, true)));
?>
    <div>
        <input type="hidden" name="edit-content-index-nonce" value="<?php echo wp_create_nonce('edit-content-index-nonce') ?>" />
        <input value="content_index_edit" type="hidden" name="content_index_edit" />
        <p><strong style="color:#999"><?php echo $msg[0];?></strong></p>
        <input<?php echo $status == '1' ? ' checked="checked"' : '';?> id="<?php echo $key;?>" name="<?php echo $key;?>" type="checkbox" value="1" />
        <label for="<?php echo $key;?>"><?php echo $msg[1];?></label>
    </div>
<?php
}

function content_index_meta_tags($id){
	$content_index_edit = $_POST["content_index_edit"];
	$nonce = $_POST["edit-content-index-nonce"];
	
	$_CONFIGURE = content_index_get_options();
	$key = $_CONFIGURE["ds"] ? 'content_index_disable' : 'content_index_enable';	
	
	if(isset($content_index_edit) && !empty($content_index_edit) && wp_verify_nonce($nonce, 'edit-content-index-nonce')){
		$status = $_POST[$key];
		delete_post_meta($id, '_'.$key);
		$status == '1' && add_post_meta($id, '_'.$key, $status);
	}
}

//编辑提交
add_action('edit_post', 'content_index_meta_tags');
//发布提交
add_action('publish_post', 'content_index_meta_tags');
//保存提交
add_action('save_post', 'content_index_meta_tags');
//编辑单页提交
add_action('edit_page_form', 'content_index_meta_tags');

//菜单栏
add_action('admin_menu', 'content_index_metabox');
//菜单栏初始化
add_action('admin_menu','content_index_admininit');
//页面初始化
add_filter('wp_head','content_index_init');
?>