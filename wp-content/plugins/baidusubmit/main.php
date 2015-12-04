<?php

//I18n, This can translate behind PHPDOC
load_plugin_textdomain('sitemap', false, '/baidusubmit/lang/');


/**
 * 入口文件
 *
 * Plugin Name: Baidu Sitemaps
 * Plugin URI: http://zz.baidu.com/
 * Version: v1.0
 * Author: Baidu Inc.
 * Description: After installing Baidu Sitemaps, you can quickly and completely submit webpages and content to Baidu.
 * Text Domain: sitemap
 * Domain Path: /lang/
 */

__('After installing Baidu Sitemaps, you can quickly and completely submit webpages and content to Baidu.');

if (!class_exists('BaidusubmitGenerator')) :

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'./inc/const.php';
define('BAIDUSUBMIT_MAINFILE', plugin_basename(__FILE__));

class BaidusubmitGenerator
{
    static function init()
    {
        //Links in plugins list
        add_filter('plugin_row_meta',  array(__CLASS__, 'registerPluginLinks'), 10, 2);

        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'./inc/options.php';
        if (BaidusubmitOptions::getOption('openping')) {
            //Existing posts was trash
            add_action('trash_post',      array(__CLASS__, 'deletePost'), 9999, 1);
            add_action('trash_page',      array(__CLASS__, 'deletePost'), 9999, 1);

            //Existing post was published
            add_action('publish_post',     array(__CLASS__, 'publishPost'), 9999, 1);
            add_action('publish_page',     array(__CLASS__, 'publishPost'), 9999, 1);
        }

        //Menus
        add_action('admin_menu',       array(__CLASS__, 'registerAdminPage'));
    }

    static function registerPluginLinks($links, $file)
    {
        if (BAIDUSUBMIT_MAINFILE === $file) {
            $blinks = BaidusubmitSetting::genLinks();
            $links = array_merge($links, $blinks);
        }
        return $links;
    }

    static function activation()
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './inc/install.php';
        BaidusubmitInstall::install();
    }

    static function deactivation()
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './inc/install.php';
        BaidusubmitInstall::deactivation();
    }

    static function uninstall()
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './inc/install.php';
        BaidusubmitInstall::uninstall();
    }

    static function deletePost($postid)
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'./inc/sitemap.php';
        $url = get_permalink($postid);
        $content = BaidusubmitSitemap::genDeleteXml($url);
        //file_put_contents('/home/work/baidusubmit.log', date('[Y-m-d H:i:s]')."\n".$content, FILE_APPEND);
        $r = BaidusubmitSitemap::sendXml($content, 2);
        //file_put_contents('/home/work/baidusubmit.log', date('[Y-m-d H:i:s]')."\n".$r, FILE_APPEND);
    }

    static function publishPost($postid)
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './inc/sitemap.php';
        $schema = BaidusubmitSitemap::genSchemaByPostId($postid, $xpost);
        if ('publish' != $xpost->post_status || '' != $xpost->post_password) {
            self::deletePost($postid);
            return;
        }
        $xml = $schema->toXml();
        $content = BaidusubmitSitemap::genPostXml($xml);
        //file_put_contents('/home/work/baidusubmit.log',  date('[Y-m-d H:i:s]')."\n".$content, FILE_APPEND);
        $r = BaidusubmitSitemap::sendXml($content, 1);
        //file_put_contents('/home/work/baidusubmit.log', date('[Y-m-d H:i:s]')."\n".$r, FILE_APPEND);
    }

    static function registerAdminPage()
    {
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './inc/setting.php';

        if ('auth' === $_GET['action']) {
            BaidusubmitSetting::authSite();
            exit;
        }
        else if ('savesetting' === $_GET['action']) {
            BaidusubmitSetting::saveSettings();
            exit;
        }

        BaidusubmitSetting::checkUpdated();
        add_menu_page(
                __('Baidu Sitemap', 'sitemap'),
                __('Baidu Sitemap', 'sitemap'),
                'manage_options',
                'baidu_admin', //URL里的参数
                array('BaidusubmitSetting', 'showAdminPage'));
        add_submenu_page(
                'baidu_admin',
                __('Baidu Sitemap - Manage', 'sitemap'),
                __('Manage', 'sitemap'),
                'manage_options', 'baidu_admin', array('BaidusubmitSetting', 'showAdminPage'));
        add_submenu_page(
                'baidu_admin',
                __('Baidu Sitemap - Submission History', 'sitemap'),
                __('Submission History', 'sitemap'),
                'manage_options',
                'baidu_urlstat',  //URL里的参数
                array('BaidusubmitSetting', 'showUrlstatPage'));
    }
}

//Hooks about setup, NO take this in BaidusubmitGenerator::init
register_activation_hook(__FILE__,    array('BaidusubmitGenerator', 'activation'));
register_deactivation_hook(__FILE__,  array('BaidusubmitGenerator', 'deactivation'));
register_uninstall_hook(__FILE__,     array('BaidusubmitGenerator', 'uninstall'));

add_action('init', array('BaidusubmitGenerator', 'init'), 1000, 0);

endif;

