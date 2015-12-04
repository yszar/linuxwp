<?php



!defined('BAIDUSUBMIT_VERSION') && define('BAIDUSUBMIT_VERSION', '1.0');
!defined('BAIDUSUBMIT_PLUGIN_PATH') && define('BAIDUSUBMIT_PLUGIN_PATH', substr(WP_PLUGIN_URL, strlen(get_option('siteurl'))+1) . '/');

//return configuration
return array(
    'zzplatform'        => 'http://zhanzhang.baidu.com/api/opensitemap',
    'siteTypeKey'       => '92f2baebb9446f25e2c1dcee635b47d5',
    'zzpingurl'         => 'http://ping.baidu.com/sitemap',
    'sitemapItemCount'  => 5000,  //一个sitemap文件中包含主题数
    'sitemapUrlCount'   => 50000, //一个sitemap索引里包含几个sitemap地
    'sitemapStepTime'   => 86400, //每次偏移时间
    'sitemapStepLength' => 500, //每次偏移步长
    'HistoryDayCount'   => 7,   //增量历史保留的天数
);