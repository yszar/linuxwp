<?php 
/*
Plugin Name: 网易云音乐
Plugin URI: http://fatesinger.com/74369
Description: 网易云音乐
Version: 1.9.0
Author: Bigfa
Author URI: http://fatesinger.com
*/	

define('NM_VERSION', '1.9.0');
define('NM_URL', plugins_url('', __FILE__));
define('NM_PATH', dirname( __FILE__ ));
define('NM_ADMIN_URL', admin_url());

require NM_PATH . '/nmjson.php';

require NM_PATH . '/functions.php';



