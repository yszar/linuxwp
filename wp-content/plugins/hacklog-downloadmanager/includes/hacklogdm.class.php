<?php

/**
 * $Id: hacklogdm.class.php 474597 2011-12-13 11:48:01Z ihacklog $
 * $Revision: 474597 $
 * $Date: 2011-12-13 11:48:01 +0000 (Tue, 13 Dec 2011) $
 * @package Hacklog-DownloadManager
 * @encoding UTF-8 
 * @author 荒野无灯 <HuangYeWuDeng> 
 * @link http://ihacklog.com 
 * @copyright Copyright (C) 2011 荒野无灯 
 * @license http://www.gnu.org/licenses/
 */
if (!defined('ABSPATH'))
{
	header('HTTP/1.1 403 Forbidden', true, 403);
	die('Please do not load this page directly. Thanks!');
}

/**
 * class for front
 *
 * @author HuangYe
 */
class hacklogdm
{
	const method_redirect = 0;
	const method_force = 1;
	const display_type_default = 0;
	const display_type_popup = 1;
	const textdomain = 'hacklog-downloadmanager';
	const version = '2.1.2';
	private static $_instance = null;
	private static $_singular_only = TRUE;
	private static $_add_js = FALSE;
	private $_opts = array(
		'download_path' => '',
		'download_path_url' => '',
		'download_method' => 0,
		'download_template_embedded' => array('', ''),
		'download_options' => array(
			'use_filename' => 0,
			'download_slug' => 'getfile',
			'nice_permalink' => 0,
			'time_limit' => 480,
			'hash_func' => 'md5',
			'check_referer' => 1
		),
		'download_template_custom_css' => '',
		'download_template_popup' => array('', ''),
		'download_display_type' => 0,
	);

	function __construct()
	{
		//Downloads Table Name
		global $wpdb;
		$wpdb->downloads = $wpdb->prefix . 'downloads';
		$all_keys = self::get_opt_keys();
		foreach ((array) $all_keys as $key)
		{
			$this->_opts[$key] = get_option($key);
		}
	}

	/**
	 * singleton
	 * @return type 
	 */
	public static function instance()
	{
		if (!(self::$_instance instanceof hacklogdm))
		{
			self::$_instance = new hacklogdm();
		}
		return self::$_instance;
	}

	public static function init()
	{
		$hacklogdm = hacklogdm::instance();
// Create text domain for translations
		add_action('init', 'hacklogdm::load_textdomain');
		// installation 
		add_action('activate_hacklog-downloadmanager/hacklog-downloadmanager.php', array($hacklogdm, 'create_download_table'));
		// add plugin "Settings" action on plugin list , the plugin_basename function must get the parent __FILE__
		add_action('plugin_action_links_' . plugin_basename(HACKLOGDM_LOADER), 'hacklogdm::add_plugin_actions');
		//add menu
		add_action('admin_menu', array($hacklogdm, 'downloads_menu'));
		//add admin css
		add_action('admin_print_styles', 'hacklogdm::stylesheets_admin');
		add_action('admin_print_styles', array($hacklogdm, 'enqueue_backend_css'));
		add_action('admin_print_footer_scripts', array($hacklogdm, 'print_backend_js'));
		//add footer js
		add_action('admin_footer-post-new.php', 'hacklogdm::footer_admin_js');
		add_action('admin_footer-post.php', 'hacklogdm::footer_admin_js');
		add_action('admin_footer-page-new.php', 'hacklogdm::footer_admin_js');
		add_action('admin_footer-page.php', 'hacklogdm::footer_admin_js');
		// add editor button
		add_action('media_buttons', array($hacklogdm, 'add_media_button'), 20);
		add_action('init', array($hacklogdm, 'tinymce_addbuttons'));

		//add rewrite rule
		add_filter('query_vars', 'hacklogdm::add_download_query_vars');
		add_filter('generate_rewrite_rules', array($hacklogdm, 'download_rewrite_rule'));
		// do sutff
		add_action('template_redirect', array($hacklogdm, 'download_file'), 5);

		add_filter('favorite_actions', 'hacklogdm::favorite_actions');

		//add the shortcode
		add_shortcode('download', array($hacklogdm, 'download_shortcode'));


		//register the js first
		add_action('init', 'hacklogdm::register_front_js');
		add_action('wp_footer', array($hacklogdm, 'print_front_js'));
		/*
		 * add popup effect css
		 * register with hook 'wp_print_styles'
		 */
		add_action('wp_print_styles', array($hacklogdm, 'enqueue_css'), -999);
		/**
		 * add user custom css
		 * this ensure our custom css can override the default one
		 */
		add_action('wp_head', array($hacklogdm, 'print_custom_stylesheet'), 999);
	}

	public static function get_opt_keys()
	{
		return array(
			'download_path',
			'download_path_url',
			'download_method',
			'download_template_embedded',
			'download_options',
			'download_template_custom_css',
			'download_template_popup',
			'download_display_type',
		);
	}

	public static function get_default_value($key)
	{
		$ret = null;
		switch ($key)
		{
			case 'download_template_embedded':
				$ret = array('<p><img src="' . plugins_url('hacklog-downloadmanager/images/ext') . '/%FILE_ICON%" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;<strong><a href="%FILE_DOWNLOAD_URL%">%FILE_NAME%</a></strong> (%FILE_SIZE%' . __(',', self::textdomain) . ' %FILE_HITS% ' . __('hits', self::textdomain) . ')</p>',
					'<p><img src="' . plugins_url('hacklog-downloadmanager/images/ext') . '/%FILE_ICON%" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;<strong>%FILE_NAME%</strong> (%FILE_SIZE%' . __(',', self::textdomain) . ' %FILE_HITS% ' . __('hits', self::textdomain) . ')<br /><i>' . __('You do not have permission to download this file.', self::textdomain) . '</i></p>');
				break;
			case 'download_options':
				$ret = array('use_filename' => 0, 'download_slug' => 'getfile', 'nice_permalink' => 0, 'time_limit' => 300, 'hash_func' => 'md5', 'check_referer' => 1);
				break;
			case 'download_template_custom_css':
				$ret = '.hacklogdownload_downlinks{width:500px}.hacklogdownload_down_link{margin-top:10px;background:#e0e2e4;border:1px solid #330;color:#222;padding:5px 5px 5px 20px}.hacklogdownload_down_link a{color:#57d}.hacklogdownload_views{color:red}.hacklogdownload_box{border-bottom:1px solid #aaa;padding:10px 0}.hacklogdownload_box_content{line-height:18px;padding:0 0 0 10px}.hacklogdownload_box_content p{margin:5px 0}.hacklogdownload_box_content a{color:#d54e21}.hacklogdownload_box_content a:hover{color:#1d1d1d}.hacklogdownload_left{float:left;width:320px}.hacklogdownload_right{width:160px;float:right;margin:0 auto}.hacklogdownload_right img{max-width:160px}.hacklogdownload_notice{padding-top:10px;text-align:center}#facebox .content{width:600px;background:none repeat scroll 0 0 #e0e2e4;color:#333}#facebox .popup{border:6px solid #444}';
				break;
			case 'download_template_popup':
				$ret = array('<div id="hacklog_download_list%FILE_ID%" style="display:none">
			<div class="hacklogdownload_box">
				<strong>' . __('Download statement', self::textdomain) . '：</strong>
				<div class="hacklogdownload_box_content">
					<p>
					1. ' . __('Download statement', self::textdomain) . ' 1
					</p>
					<p>
					2. ' . __('Download statement', self::textdomain) . ' 2
					</p>
				</div>
			</div>	
			<div class="hacklogdownload_box">
				<strong>' . __('File info', self::textdomain) . '：</strong>
				<div class="hacklogdownload_box_content">
					<div class="hacklogdownload_left">
						<p>' . __('File name', self::textdomain) . '：<img src="' . plugins_url('hacklog-downloadmanager/images/ext') . '/%FILE_ICON%" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;%FILE_NAME% </p>
						<p>' . __('File hash', self::textdomain) . '：%FILE_HASH%</p>
						<p>' . __('File size', self::textdomain) . '：%FILE_SIZE%</p>
						<p>' . __('File uploaded', self::textdomain) . '：%FILE_DATE%</p>
						<p>' . __('File updated', self::textdomain) . '：%FILE_UPDATED_DATE%</p>
						<p>' . __('File description', self::textdomain) . '：%FILE_DESCRIPTION%</p>
					</div>
					
					<div class="hacklogdownload_right">
					<strong>' . __('Download URL', self::textdomain) . '：</strong><a href="%FILE_DOWNLOAD_URL%" title="download %FILE_NAME%"><img style="vertical-align: middle;" src="' . plugins_url('hacklog-downloadmanager/images') . '/download.png" alt="download"/></a>
					</div>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="hacklogdownload_notice">
			<span style="color:#f00;">' . __('Other notice', self::textdomain) . '</span>
			</div>
</div><!-- end hacklog_download_list%FILE_ID% -->

<div class="hacklogdownload_down_link"><img src="' . plugins_url('hacklog-downloadmanager/images/ext') . '/%FILE_ICON%" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;<span class="hacklogdownload_filename">%FILE_NAME%</span>&nbsp;&nbsp;<strong><a rel="facebox" href="#hacklog_download_list%FILE_ID%" title="download %FILE_NAME%">' . __('Download', 'hacklog-downloadmanager') . '</a></strong> (%FILE_SIZE%, %FILE_HITS% ' . __('hits', self::textdomain) . ')</div>
',
					'<p><img src="' . plugins_url('hacklog-downloadmanager/images/ext') . '/%FILE_ICON%" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;<strong>%FILE_NAME%</strong> (%FILE_SIZE%' . __(',', self::textdomain) . ' %FILE_HITS% ' . __('hits', self::textdomain) . ')<br /><i>' . __('You do not have permission to download this file.', self::textdomain) . '</i></p>'
				);
		}
		return $ret;
	}

	public static function plugin_dir_url()
	{
		return plugin_dir_url(dirname(__FILE__));
	}

	public function get_opt($name, $default = '')
	{
		$ret = null;
		$ret = !empty($this->_opts[$name]) ? $this->_opts[$name] : $default;
		return $ret;
	}

	public static function check_headers_sent()
	{
		if (ob_get_length() > 0)
		{
			wp_die(__('Error: Content already sent! Please contact the site administrator to solve this problem.', self::textdomain));
		}
		if (headers_sent($file, $line))
		{
			if (WP_DEBUG)
			{
				wp_die('Error: header already sent in file <strong>' . $file . '</strong> line <strong>' . $line . '</strong>.Please check your server configure or contact the administrator.');
			}
			else
			{
				wp_die(__('Error: header already sent! Please contact the site administrator to solve this problem.', self::textdomain));
			}
		}
	}

	public function enqueue_css()
	{
		if (self::$_singular_only && !is_singular())
		{
			return;
		}
		$display_type = $this->get_opt('download_display_type', 0);
		if ($display_type)
		{
			wp_enqueue_style('facebox', self::plugin_dir_url() . 'js/facebox/facebox.css.php');
		}
	}

	/*
	 * print custom css 
	 */

	public function print_custom_stylesheet()
	{
		if (self::$_singular_only && !is_singular())
		{
			return;
		}
		$css = $this->get_opt('download_template_custom_css');
		if (!empty($css))
		{
			echo '<style type="text/css">';
			echo $css;
			echo '</style>';
		}
	}

	//removed the jquery dependencies,checked in print_front_js
	public static function register_front_js()
	{
		wp_register_script('facebox', self::plugin_dir_url() . 'js/facebox/facebox.js.php', array(), '1.2', TRUE);
	}

	public function print_front_js()
	{
		if (self::$_singular_only && !is_singular())
		{
			return;
		}
		$display_type = $this->get_opt('download_display_type', 0);
		if (self::$_add_js && $display_type)
		{
			//if jQuery not enqueued by WP standard wp_enqueue_method yet,try to load the js ...
			global $wp_scripts;
			$handle = 'jquery';
			if(  ($wp_scripts instanceof WP_Scripts) &&  (!in_array($handle, $wp_scripts->done) || !$wp_scripts->query($handle)) )
			{
				$jq_url = site_url('wp-includes/js/jquery/jquery.js');
				echo "\n<script type=\"text/javascript\">";
				echo "!window.jQuery && document.write('<script src=\"{$jq_url}\" type=\"text/javascript\"><\/script>');";
				echo "</script>\n";
			}
			wp_print_scripts('facebox');
		}
	}

	public function enqueue_backend_css()
	{
		wp_enqueue_style('jquery-filetree', self::plugin_dir_url() . 'js/jqueryFileTree/jqueryFileTree.css');
	}

	public function print_backend_js()
	{
		//SCRIPT_NAME  for download-upload-or-add.php
		$current_script = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : $_SERVER['SCRIPT_NAME'];
		// only load the script when needed.
		if (in_array(basename($current_script), array('download-add.php', 'download-manager.php', 'download-upload-or-add.php')))
		{
			wp_enqueue_script('jquery');
//		wp_enqueue_script('jquery-filetree', self::plugin_dir_url() . 'js/jqueryFileTree/jqueryFileTree.js', array('jquery'), '1.0.1', TRUE);
			$connector = plugins_url('hacklog-downloadmanager/js/jqueryFileTree/jqueryFileTree.php');
			$jquery_filetree_js_src = self::plugin_dir_url() . 'js/jqueryFileTree/jqueryFileTree.js';
			echo "<script type='text/javascript'>
			jQuery(function($) {
		$.getScript('$jquery_filetree_js_src',
		function()
		{
			$('#hacklogdm-filetree').fileTree({ root: '/', script: '$connector', folderEvent: 'click', expandSpeed: 200, collapseSpeed: 100, multiFolder: true }, function(file) {
		$('#hacklogdm-filetree-file').val(file);
		$('#hacklogdm-filetree').slideToggle('fast');
});
		});

		$('#hacklogdm-filetree-button').click(function(){
		 $('#hacklogdm-filetree').slideToggle('slow');
		});
});
		</script>
			";
		}
	}

	/**
	 * Enqueue Downloads Stylesheets In WP-Admin
	 * @see http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 * @see http://codex.wordpress.org/Plugin_API/Action_Reference/admin_print_styles
	 */
	public static function stylesheets_admin()
	{
		wp_enqueue_style('hacklog-downloadmanager-admin', plugins_url('hacklog-downloadmanager/download-admin-css.css'), false, '2.0.0', 'all');
	}

	/**
	 * Displays Download Manager Footer  js In WP-Admin
	 * js_escape is deprecated in wp 2.8 
	 */
	public static function footer_admin_js()
	{
		//this code is modified by 荒野无灯,use the new Quicktags API function,@see quicktags.dev.js line 274
		echo '<script type="text/javascript">' . "\n";
		echo "\t" . 'var downloadsEdL10n = {' . "\n";
		echo "\t\t" . 'enter_download_id: "' . esc_js(__('Enter File ID (Separate Multiple IDs By A Comma)', self::textdomain)) . '",' . "\n";
		echo "\t\t" . 'download: "' . esc_js(__('Download', self::textdomain)) . '",' . "\n";
		echo "\t\t" . 'insert_download: "' . esc_js(__('Insert File Download', self::textdomain)) . '",' . "\n";
		echo "\t" . '};' . "\n";
		echo "\t" . 'function insertDownload(where) {' . "\n";
		echo "\t\t" . 'var download_id = jQuery.trim(prompt(downloadsEdL10n.enter_download_id));' . "\n";
		echo "\t\t" . 'if(download_id == null || download_id == "") {' . "\n";
		echo "\t\t\t" . 'return;' . "\n";
		echo "\t\t" . '} else {' . "\n";
		echo "\t\t\t" . 'if(where == "code") {' . "\n";
		echo "\t\t\t\t" . 'QTags.insertContent("[download id=\"" + download_id + "\"]");' . "\n";
		echo "\t\t\t" . '} else {' . "\n";
		echo "\t\t\t\t" . 'return "[download id=\"" + download_id + "\"]";' . "\n";
		echo "\t\t\t" . '}' . "\n";
		echo "\t\t" . '}' . "\n";
		echo "\t" . '}' . "\n";
		echo "\t" . 'if(document.getElementById("ed_toolbar")){' . "\n";
		echo "\t\t" . 'QTags.addButton( "ed_downloadmanager", downloadsEdL10n.download ,function () { insertDownload(\'code\');},"","",downloadsEdL10n.insert_download );' . "\n";
		echo "\t" . 'downloadsEdL10n.insert_download' . "\n";
		echo "\t" . '}' . "\n";
		echo '</script>' . "\n";
	}

	public static function load_textdomain()
	{
		load_plugin_textdomain('hacklog-downloadmanager', false, dirname( plugin_basename( HACKLOGDM_LOADER ) ) . '/languages/' );
	}

	/**
	 * Add "Settings" action on installed plugin list
	 * @param type $links
	 * @return array
	 */
	public static function add_plugin_actions($links)
	{
		array_unshift($links, '<a href="'. admin_url('admin.php?page=hacklog-downloadmanager/download-options.php'). '">' . __('Settings') . '</a>');
		return $links;
	}

	/**
	 * add Downloads Administration Menu
	 */
	public function downloads_menu()
	{
		if (function_exists('add_menu_page'))
		{
			add_menu_page(__('Downloads', self::textdomain), __('Downloads', self::textdomain), 'manage_downloads', 'hacklog-downloadmanager/download-manager.php', '', plugins_url('hacklog-downloadmanager/images/drive.png'));
		}
		if (function_exists('add_submenu_page'))
		{
			add_submenu_page('hacklog-downloadmanager/download-manager.php', __('Manage Downloads', 'hacklog-downloadmanager'), __('Manage Downloads', 'hacklog-downloadmanager'), 'manage_downloads', 'hacklog-downloadmanager/download-manager.php');
			add_submenu_page('hacklog-downloadmanager/download-manager.php', __('Add File', 'hacklog-downloadmanager'), __('Add File', 'hacklog-downloadmanager'), 'manage_downloads', 'hacklog-downloadmanager/download-add.php');
			add_submenu_page('hacklog-downloadmanager/download-manager.php', __('Download Options', 'hacklog-downloadmanager'), __('Download Options', 'hacklog-downloadmanager'), 'manage_downloads', 'hacklog-downloadmanager/download-options.php');
			add_submenu_page('hacklog-downloadmanager/download-manager.php', __('Uninstall Hacklog-DownloadManager', 'hacklog-downloadmanager'), __('Uninstall Hacklog-DownloadManager', 'hacklog-downloadmanager'), 'manage_downloads', 'hacklog-downloadmanager/download-uninstall.php');
		}
	}

	/**
	 * Add Favourite Actions >= WordPress 2.7
	 * @param type $favorite_actions
	 * @return string 
	 */
	public static function favorite_actions($favorite_actions)
	{
		$favorite_actions ['admin.php?page=hacklog-downloadmanager/download-add.php'] = array(__('Add File', self::textdomain), 'manage_downloads');
		return $favorite_actions;
	}

	/**
	 * Add Quick Tag For Poll In TinyMCE >= WordPress 2.5
	 * @return type 
	 */
	public function tinymce_addbuttons()
	{
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		{
			return;
		}
		if (get_user_option('rich_editing') == 'true')
		{
			add_filter("mce_external_plugins", 'hacklogdm::tinymce_addplugin');
			add_filter('mce_buttons', 'hacklogdm::tinymce_registerbutton');
		}
	}

	/**
	 * used by tinymce_addbuttons
	 * @param type $buttons
	 * @return type 
	 */
	public static function tinymce_registerbutton($buttons)
	{
		array_push($buttons, 'separator', 'downloadmanager');
		return $buttons;
	}

	/**
	 * used by tinymce_addbuttons
	 * @param array $plugin_array
	 * @return type 
	 */
	public static function tinymce_addplugin($plugin_array)
	{
		$plugin_array ['downloadmanager'] = plugins_url('hacklog-downloadmanager/tinymce/plugins/downloadmanager/editor_plugin.js');
		return $plugin_array;
	}

	public static function add_media_button($editor_id = 'content')
	{
		$url = WP_PLUGIN_URL . '/hacklog-downloadmanager/download-upload-or-add.php?tab=upload&TB_iframe=true&width=740&height=500';
		$admin_icon = WP_PLUGIN_URL . '/hacklog-downloadmanager/images/download_admin_icon.png';
		if (is_ssl())
		{
			$url = str_replace('http://', 'https://', $url);
		}
		$alt = __('Add Download', self::textdomain);
		$img = '<img src="' . esc_url( $admin_icon ) . '" width="15" height="15" alt="'. esc_attr($alt) . '" />';

		echo '<a href="' . esc_url( $url ) . '" class="thickbox add_download" id="' . esc_attr( $editor_id ) . '-add_download" title="' . esc_attr__('Add Download', self::textdomain) . '" onclick="return false;">' . $img  . '</a>';

	}

	/**
	 * Add Download Query Vars
	 * @param type $public_query_vars
	 * @return string 
	 */
	public static function add_download_query_vars($public_query_vars)
	{
		$public_query_vars [] = "dl_id";
		$public_query_vars [] = "dl_name";
		return $public_query_vars;
	}

	/**
	 * Download htaccess ReWrite Rules
	 * @param type $wp_rewrite 
	 */
	public function download_rewrite_rule($wp_rewrite)
	{
		$download_options = $this->get_opt('download_options');
		$wp_rewrite->rules = array_merge(array($download_options ['download_slug'] . '/([0-9]{1,})/?$' => 'index.php?dl_id=$matches[1]', $download_options ['download_slug'] . '/(.*)$' => 'index.php?dl_name=$matches[1]'), $wp_rewrite->rules);
	}

	/**
	 * Download File
	 * @global type $wpdb
	 * @global type $user_ID 
	 */
	public function download_file()
	{
		global $wpdb, $user_ID;
		$dl_id = (int) get_query_var('dl_id');
		$dl_name = get_query_var('dl_name');
		$dl_name = addslashes($this->download_file_name_decode($dl_name));
		//do this ONLY when dl_name is NOT EMPTY and is NOT remote file!
		if (!self::is_remote_file($dl_name) && !empty($dl_name) && '/' != substr($dl_name, 0, 1))
		{
			$dl_name = '/' . $dl_name;
		}
		$download_options = $this->get_opt('download_options');

		if ($dl_id > 0 || !empty($dl_name))
		{
			//check if the header already sent.This may be PHP error messages genareated by other WordPress plugins.
			hacklogdm::check_headers_sent();
			if ($dl_id > 0 && $download_options ['use_filename'] == 0)
			{
				$file = $wpdb->get_row("SELECT file_id, file, file_name , file_permission FROM $wpdb->downloads WHERE file_id = $dl_id AND file_permission != -2");
			}
			elseif (!empty($dl_name) && $download_options ['use_filename'] == 1)
			{
				$file = $wpdb->get_row("SELECT file_id, file,  file_name , file_permission FROM $wpdb->downloads WHERE file = '$dl_name' AND file_permission != -2");
			}
			if (!$file)
			{
				status_header(404);
				wp_die(__('Invalid File ID or File Name.', self::textdomain));
			}
			$file_path = stripslashes($this->get_opt('download_path'));
			$file_url = stripslashes($this->get_opt('download_path_url'));
			$download_method = intval($this->get_opt('download_method'));
			$file_id = intval($file->file_id);
			$file_name = stripslashes($file->file);
			$down_name = stripslashes($file->file_name);
			$file_permission = intval($file->file_permission);
			$current_user = wp_get_current_user();
			if (($file_permission > 0 && intval($current_user->wp_user_level) >= $file_permission && intval($user_ID) > 0) || ($file_permission == 0 && intval($user_ID) > 0) || $file_permission == - 1)
			{
				if ($download_options ['check_referer'])
				{
					if (!isset($_SERVER ['HTTP_REFERER']) || $_SERVER ['HTTP_REFERER'] == '')
						wp_die(__('Please do not leech.', self::textdomain));
					$refererhost = parse_url($_SERVER ['HTTP_REFERER']);
					//如果本站下载也被误认为盗链，请修改下面www.your-domain.com为你的博客域名
					$validReferer = array('www.your-domain.com', $_SERVER ['HTTP_HOST']);
					if (!(in_array($refererhost ['host'], $validReferer)))
					{
						wp_die(__('Please do not leech.', self::textdomain));
					}
				}
				if (!self::is_remote_file($file_name))
				{
					if (!is_file($file_path . $file_name))
					{
						status_header(404);
						wp_die(__('File does not exist.', self::textdomain));
					}
					$update_hits = $wpdb->query("UPDATE $wpdb->downloads SET file_hits = (file_hits + 1), file_last_downloaded_date = '" . current_time('timestamp') . "' WHERE file_id = $file_id AND file_permission != -2");
					if ($download_method == 0)
					{
						//这里还是重新计算一下大小
						$filesize = filesize($file_path . $file_name);
						$fp = fopen($file_path . $file_name, 'rb');
						if (!$fp)
						{
							wp_die(__('Error: can not read the file!Please contact the webmaster.', self::textdomain));
						}

						if ($filesize <= 0)
						{
							wp_die(__('Error: filesize is zero.', self::textdomain));
						}

						header("Pragma: public");
						header("Expires: 0");
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header("Content-Type: application/force-download");
						header("Content-Type: application/octet-stream");
						header("Content-Type: application/download");
						header('Content-Disposition: attachment; ' . self::_header_filename(htmlspecialchars_decode(self::get_download_name($down_name))));
						header("Content-Transfer-Encoding: binary");
						header("Content-Length: " . $filesize);
						$download_options = $this->get_opt('download_options');
						// maximum execution time in seconds
						@set_time_limit($download_options ['time_limit']);
						//memory linit 256M
						@ini_set('memory_limit', 8 * 1024 * 1024 * 256);

						$length = $filesize;
						define('CHUNK_SIZE', 4096);

						$data = '';
						while ($length > 0) {
							$to_read = $length > CHUNK_SIZE ? CHUNK_SIZE : $length;
							echo fread($fp, $to_read);
							$length -= $to_read;
						}
						fclose($fp);
						//@readfile ( $file_path . $file_name );
					}
					else
					{
						header('Location: ' . $file_url . $file_name);
					}
					exit();
				}
				else
				{
					$update_hits = $wpdb->query("UPDATE $wpdb->downloads SET file_hits = (file_hits + 1), file_last_downloaded_date = '" . current_time('timestamp') . "' WHERE file_id = $file_id AND file_permission != -2");
					header('Location: ' . $file_name);
					exit();
				}
			}
			else
			{
				wp_die(__('You do not have permission to download this file.', self::textdomain));
			}
		}
	}

	/* ###########################################################################	
	 * private functions
	 * ########################################################################## */

	### Function: Get File Extension Images

	private static function file_extension_images()
	{
		$file_ext_images = array();
		$dir = WP_PLUGIN_DIR . '/hacklog-downloadmanager/images/ext';
		if (is_dir($dir))
		{
			if ($dh = opendir($dir))
			{
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..')
					{
						$file_ext_images [] = $file;
					}
				}
				closedir($dh);
			}
		}
		return $file_ext_images;
	}

	/**
	 * Get a browser friendly UTF-8 encoded filename
	 */
	private static function _header_filename($file)
	{
		$user_agent = $_SERVER ['HTTP_USER_AGENT'];
		$user_agent = (!empty($user_agent)) ? htmlspecialchars((string) $user_agent) : '';

		// There be dragons here.
		// Not many follows the RFC...
		if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Safari') !== false || strpos($user_agent, 'Konqueror') !== false)
		{
			return "filename=" . rawurlencode($file);
		}

		// follow the RFC for extended filename for the rest
		return "filename*=UTF-8''" . rawurlencode($file);
	}

	private function download_file_name_encode($file_name)
	{
		$download_options = get_option('download_options');
		if ($download_options['check_referer'])
		{
			$file_name = base64_encode(date('Ymd') . 'xxoo_F-u-c-k_GxxoFxxooW' . stripslashes($file_name));
		}
		else
		{
			$file_name = base64_encode('xxoo_F-u-c-k_GxxoFxxooW' . stripslashes($file_name));
		}
		return $file_name;
	}

	private function download_file_name_decode($file_name)
	{
		$download_options = $this->get_opt('download_options');
		if ($download_options['check_referer'])
		{
			$file_name = str_replace(date('Ymd') . 'xxoo_F-u-c-k_GxxoFxxooW', '', base64_decode($file_name));
		}
		else
		{
			$file_name = str_replace('xxoo_F-u-c-k_GxxoFxxooW', '', base64_decode($file_name));
		}
		return $file_name;
	}

	/**
	 * 生成下载文件的URL
	 * the site_url uses get_site_url which will auto add  prefix '/' before path
	 * @param type $file_id
	 * @param type $file_name
	 * @return string 
	 */
	private function get_download_file_url($file_id, $file_name)
	{
		$download_options = get_option('download_options');
		$download_use_filename = intval($download_options ['use_filename']);
		$download_nice_permalink = intval($download_options ['nice_permalink']);
		$file_id = intval($file_id);
		$file_name = ltrim($file_name, '/');
		$file_name = $this->download_file_name_encode($file_name);
		if ($download_nice_permalink == 1)
		{
			if ($download_use_filename == 1)
			{
				$download_file_url = home_url($download_options ['download_slug'] . '/' . $file_name);
			}
			else
			{
				$download_file_url = home_url($download_options ['download_slug'] . '/' . $file_id . '/');
			}
		}
		else
		{
			if ($download_use_filename == 1)
			{
				$download_file_url = home_url('?dl_name=' . $file_name);
			}
			else
			{
				$download_file_url = home_url('?dl_id=' . $file_id);
			}
		}
		return $download_file_url;
	}

	/**
	 * Download Embedded
	 * @global type $wpdb
	 * @global type $user_ID
	 * @param string $condition
	 * @param type $display
	 * @return type 
	 */
	private function download_embedded($condition = '', $display = 'both')
	{
		global $wpdb, $user_ID;
		$output = '';
		if ($condition !== '')
		{
			$condition .= ' AND ';
		}
		$files = $wpdb->get_results("SELECT * FROM $wpdb->downloads WHERE $condition file_permission != -2");
		if ($files)
		{
			$current_user = wp_get_current_user();
			$file_extensions_images = self::file_extension_images();
			$download_display_type = $this->get_opt('download_display_type', 0);
			$template_download_embedded_temp = '';
			$template_download_embedded = '';
			switch ($download_display_type)
			{
				case 0:
					$template_download_embedded_temp = $this->get_opt('download_template_embedded');
					break;
				case 1:
					$template_download_embedded_temp = $this->get_opt('download_template_popup');
			}
			foreach ($files as $file)
			{
				$template_download_embedded = $template_download_embedded_temp;
				$file_permission = intval($file->file_permission);

				if (($file_permission > 0 && intval($current_user->wp_user_level) >= $file_permission && intval($user_ID) > 0) || ($file_permission == 0 && intval($user_ID) > 0) || $file_permission == - 1)
				{
					$template_download_embedded = stripslashes($template_download_embedded [0]);
				}
				else
				{
					$template_download_embedded = stripslashes($template_download_embedded [1]);
				}
				$template_download_embedded = str_replace("%FILE_ID%", $file->file_id, $template_download_embedded);
				$template_download_embedded = str_replace("%FILE%", stripslashes($file->file), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_NAME%", stripslashes($file->file_name), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_ICON%", self::file_extension_image(stripslashes($file->file), $file_extensions_images), $template_download_embedded);
				if ($display == 'both')
				{
					$template_download_embedded = str_replace("%FILE_DESCRIPTION%", nl2br( stripslashes($file->file_des) ), $template_download_embedded);
				}
				else
				{
					$template_download_embedded = str_replace("%FILE_DESCRIPTION%", '', $template_download_embedded);
				}
				$template_download_embedded = str_replace("%FILE_HASH%", $file->file_hash, $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_SIZE%", self::format_filesize($file->file_size), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_DATE%", mysql2date(get_option('date_format'), gmdate('Y-m-d H:i:s', $file->file_date)), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_TIME%", mysql2date(get_option('time_format'), gmdate('Y-m-d H:i:s', $file->file_date)), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_UPDATED_DATE%", mysql2date(get_option('date_format'), gmdate('Y-m-d H:i:s', $file->file_updated_date)), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_UPDATED_TIME%", mysql2date(get_option('time_format'), gmdate('Y-m-d H:i:s', $file->file_updated_date)), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_HITS%", number_format_i18n($file->file_hits), $template_download_embedded);
				$template_download_embedded = str_replace("%FILE_DOWNLOAD_URL%", $this->get_download_file_url($file->file_id, $file->file), $template_download_embedded);
				$output .= $template_download_embedded;
			}
			return apply_filters('download_embedded', $output);
		}
	}

	/* ###########################################################################	
	 * public functions
	 * ########################################################################## */

	/**
	 * 将原始文件名空格替换为下划线
	 * @param type $file_name
	 * @return type 
	 */
	public static function get_download_name($file_name)
	{
		$file_name = self::get_basename($file_name);
		$file_name = str_replace(' ', '_', $file_name);
		return $file_name;
	}

	/**
	 * @author 荒野无灯
	 * 2010 0506修正，可取得网址文件名
	 * 2011-09-21 fixed,make the function perform like  PHP origenal basename function
	 * @param string $file_name
	 * @param string $suffix
	 * @return string 
	 */
	public static function get_basename($file_name, $suffix = '')
	{
		//for windows servers
		$file_name = str_replace("\\", '/', $file_name);
		if (false !== strpos($file_name, '/'))
		{
			/*
			  $baseDir=dirname($file_name);
			  $basename=str_replace($baseDir,'',$file_name);
			  $basename=str_replace('/','',$basename);
			 */
			$basename = substr($file_name, strrpos($file_name, '/') + 1);
		}
		else
		{
			$basename = $file_name;
		}
		if (!empty($suffix))
		{
			$basename = substr($basename, 0, strlen($basename) - strlen($suffix));
		}
		return $basename;
	}

	/**
	 * Get File Extension
	 * @param type $filename
	 * @return type 
	 */
	public static function file_extension($filename)
	{
		$file_ext = explode('.', $filename);
		$file_ext = $file_ext [sizeof($file_ext) - 1];
		$file_ext = strtolower($file_ext);
		return $file_ext;
	}

	/**
	 * Print Out File Extension Image
	 * @param type $file_name
	 * @param type $file_ext_images
	 * @return string 
	 */
	private static function file_extension_image($file_name, $file_ext_images)
	{
		$file_ext = self::file_extension($file_name);
		$file_ext .= '.gif';
		if (in_array($file_ext, $file_ext_images))
		{
			return $file_ext;
		}
		else
		{
			return 'unknown.gif';
		}
	}

### Function: Format Bytes Into TiB/GiB/MiB/KiB/Bytes

	public static function format_filesize($rawSize)
	{
		if ($rawSize / 1099511627776 > 1)
		{
			return number_format_i18n($rawSize / 1099511627776, 1) . ' ' . __('TiB', self::textdomain);
		}
		elseif ($rawSize / 1073741824 > 1)
		{
			return number_format_i18n($rawSize / 1073741824, 1) . ' ' . __('GiB', self::textdomain);
		}
		elseif ($rawSize / 1048576 > 1)
		{
			return number_format_i18n($rawSize / 1048576, 1) . ' ' . __('MiB', self::textdomain);
		}
		elseif ($rawSize / 1024 > 1)
		{
			return number_format_i18n($rawSize / 1024, 1) . ' ' . __('KiB', self::textdomain);
		}
		elseif ($rawSize > 1)
		{
			return number_format_i18n($rawSize, 0) . ' ' . __('bytes', self::textdomain);
		}
		else
		{
			return __('unknown', self::textdomain);
		}
	}

	/**
	 * Snippet Text
	 * @param type $text
	 * @param type $length
	 * @return type 
	 */
	public static function snippet_text($text, $length = 0)
	{
		if (defined('MB_OVERLOAD_STRING'))
		{
			$text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
			if (mb_strlen($text) > $length)
			{
				return htmlentities(mb_substr($text, 0, $length), ENT_COMPAT, get_option('blog_charset')) . '...';
			}
			else
			{
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
			}
		}
		else
		{
			$text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
			if (strlen($text) > $length)
			{
				return htmlentities(substr($text, 0, $length), ENT_COMPAT, get_option('blog_charset')) . '...';
			}
			else
			{
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
			}
		}
	}

	/**
	 * check if Is Remote File
	 * @param type $file_name
	 * @return type 
	 */
	public static function is_remote_file($file_name)
	{
		$file_name = strtolower($file_name);
		if (strpos($file_name, 'http://') === false && strpos($file_name, 'https://') === false && strpos($file_name, 'ftp://') === false)
		{
			return false;
		}
		return true;
	}

### Function: Short Code For Inserting Files Download Into Posts

	public function download_shortcode($atts)
	{
		//in last line of shortcodes : add_filter('the_content', 'do_shortcode', 11); 
		// so the shortcode is trigger before wp_footer
		self::$_add_js = TRUE;

		extract(shortcode_atts(array('id' => '0', 'display' => 'both'), $atts));
		if (!is_feed())
		{
			$conditions = array();
			if ($id != '0')
			{
				if (strpos($id, ',') !== false)
				{
					$conditions [] = "file_id IN ($id)";
				}
				else
				{
					$conditions [] = "file_id = $id";
				}
			}

			if ($conditions)
			{
				return $this->download_embedded(implode(' AND ', $conditions), $display);
			}
			else
			{
				return '';
			}
		}
		else
		{
			return sprintf(__('Note: There is a file embedded within this post, please visit <a href="%s">this post</a> to download the file.', 'hacklog-downloadmanager'), get_permalink());
		}
	}

	/**
	 * Create Downloads Table and add default options
	 * @global type $wpdb
	 * @global type $blog_id 
	 */
	public function create_download_table()
	{
		global $wpdb, $blog_id;
		$this->load_textdomain();
		if (@is_file(ABSPATH . '/wp-admin/includes/upgrade.php'))
		{
			include_once (ABSPATH . '/wp-admin/includes/upgrade.php');
		}
		elseif (@is_file(ABSPATH . '/wp-admin/upgrade-functions.php'))
		{
			include_once (ABSPATH . '/wp-admin/upgrade-functions.php');
		}
		else
		{
			wp_die(__('We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'', self::textdomain));
		}
		$charset_collate = '';
		if ($wpdb->supports_collation())
		{
			if (!empty($wpdb->charset))
			{
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if (!empty($wpdb->collate))
			{
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}
		// Create WP-Downloads Table
		$create_table = "CREATE TABLE $wpdb->downloads (" . "file_id int(10) NOT NULL auto_increment," . "file tinytext NOT NULL," . "file_name text NOT NULL," . "file_des text NOT NULL," . "file_hash varchar(40) NOT NULL default ''," . "file_size varchar(20) NOT NULL default ''," . "file_date varchar(20) NOT NULL default ''," . "file_updated_date varchar(20) NOT NULL default ''," . "file_last_downloaded_date varchar(20) NOT NULL default ''," . "file_hits int(10) NOT NULL default '0'," . "file_permission TINYINT(2) NOT NULL default '0'," . "PRIMARY KEY (file_id)) $charset_collate;";
		maybe_create_table($wpdb->downloads, $create_table);
		// WP-Downloads Options
		if (function_exists('is_site_admin'))
		{
			add_option('download_path', str_replace("\\", '/', WP_CONTENT_DIR) . '/blogs.dir/' . $blog_id . '/files');
			add_option('download_path_url', WP_CONTENT_URL . '/blogs.dir/' . $blog_id . '/files');
		}
		else
		{
			add_option('download_path', str_replace("\\", '/', WP_CONTENT_DIR) . '/files');
			add_option('download_path_url', content_url('files'));
		}
		add_option('download_method', 0);
		add_option('download_display_type', 0);
		add_option('download_template_embedded', self::get_default_value('download_template_embedded'));
		add_option('download_options', self::get_default_value('download_options'));
		add_option('download_template_custom_css', self::get_default_value('download_template_custom_css'));
		add_option('download_template_popup', self::get_default_value('download_template_popup'));

		//$wpdb->query("UPDATE $wpdb->downloads SET file_permission = -2 WHERE file_permission = -1;");
		//$wpdb->query("UPDATE $wpdb->downloads SET file_permission = -1 WHERE file_permission = 0;");
		//$wpdb->query("UPDATE $wpdb->downloads SET file_permission = 0 WHERE file_permission = 1;");
		// Create Files Folder
		if (function_exists('is_site_admin'))
		{
			if (!is_dir(str_replace("\\", '/', WP_CONTENT_DIR) . '/blogs.dir/' . $blog_id . '/files/'))
			{
				mkdir(str_replace("\\", '/', WP_CONTENT_DIR) . '/blogs.dir/' . $blog_id . '/files/', 0777, true);
			}
		}
		else
		{
			if (!is_dir(str_replace("\\", '/', WP_CONTENT_DIR) . '/files/'))
			{
				mkdir(str_replace("\\", '/', WP_CONTENT_DIR) . '/files/', 0777, true);
			}
		}
		// Set 'manage_downloads' Capabilities To Administrator
		$role = get_role('administrator');
		if (!$role->has_cap('manage_downloads'))
		{
			$role->add_cap('manage_downloads');
		}
	}

}

// end class
