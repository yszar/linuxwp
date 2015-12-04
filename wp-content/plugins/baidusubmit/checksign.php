<?php

if (!function_exists('add_action')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './../../../wp-config.php';
}

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'inc/options.php';

$checksign = $_GET['checksign'];
if (!$checksign || strlen($checksign) !== 32 ){
    exit;
}
$data = BaidusubmitOptions::getOption('checksign', null, true);
if (isset($data['time']) && $data['value'] == $checksign && time()-$data['time'] < 30) {
    echo $data['value'];
}

