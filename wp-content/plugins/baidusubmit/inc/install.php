<?php


class BaidusubmitInstall
{
    static $createTableSql = array(
            'baidusubmit_sitemap' =>
"CREATE TABLE `{TABLEPREFIX}baidusubmit_sitemap`(
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(4) NOT NULL,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `start` int(11) DEFAULT '0',
  `end` int(11) DEFAULT '0',
  `item_count` int(10) unsigned DEFAULT '0',
  `file_size` int(10) unsigned DEFAULT '0',
  `lost_time` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `start` (`start`),
  KEY `end` (`end`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8",

            'baidusubmit_urlstat' =>
"CREATE TABLE `{TABLEPREFIX}baidusubmit_urlstat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctime` int(10) NOT NULL DEFAULT '0',
  `urlnum` int(10) NOT NULL DEFAULT '0',
  `urlcount` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ctime` (`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8",
        );

    static function install()
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php';
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sitemap.php';
        BaidusubmitOptions::setOption('enabled', '1');
        if (null === BaidusubmitOptions::getOption('installmaxtid', null)) {
            BaidusubmitOptions::setOption('installmaxtid', BaidusubmitSitemap::getMaxTid());
        }
        if (null === BaidusubmitOptions::getOption('openping', null)) {
            BaidusubmitOptions::setOption('openping', '1');
        }

        global $wpdb;
        foreach (array_keys(self::$createTableSql) as $table) {
            $fulltable = $wpdb->prefix.$table;
            if ($table !== $wpdb->get_var("SHOW TABLES LIKE '$fulltable'")) {
                $sql = str_replace('{TABLEPREFIX}', $wpdb->prefix, self::$createTableSql[$table]);
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
            }
        }
    }

    static function deactivation()
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php';
        BaidusubmitOptions::setOption('enabled', 0);
    }

    static function uninstall()
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php';
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sitemap.php';
        $siteurl = BaidusubmitOptions::getOption('siteurl');
        $token = BaidusubmitOptions::getOption('pingtoken');
        $sppasswd = BaidusubmitOptions::getOption('sppasswd');
        $sign = md5($siteurl.$token);

        BaidusubmitSitemap::submitIndex('del', BaidusubmitSitemap::TYPE_ALL, $siteurl, $sppasswd, $sign);
        BaidusubmitSitemap::submitIndex('del', BaidusubmitSitemap::TYPE_INC, $siteurl, $sppasswd, $sign);

        BaidusubmitOptions::clearAllOptions();

        global $wpdb;
        foreach (array_keys(self::$createTableSql) as $table) {
            $fulltable = $wpdb->prefix.$table;
            $wpdb->query("DROP TABLE $fulltable");
        }
    }
}