<?php

/**
 * Spider will crawl this file
 */

ini_set('display_errors', 0);

define('__BAIDU_START_TIME__', microtime(true));

if (!function_exists('add_action')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . './../../../wp-config.php';
}

set_time_limit(300);

$config = include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc/const.php';

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'inc/options.php';
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'inc/sitemap.php';

if (!BaidusubmitOptions::getOption('enabled')) {
    exit;
}

if (empty($_GET['p']) || $_GET['p'] != ($sppasswd = BaidusubmitOptions::getOption('sppasswd'))) {
    BaidusubmitSitemap::headerStatus(404);
    return 1;
}

$urlsuffix = "&p=$sppasswd";

//全量索引
if ('indexall' === $_GET['m']) {
    $sitemapMaxTid = (int)BaidusubmitSitemap::getSitemapMaxEnd(BaidusubmitSitemap::TYPE_ALL); //sitemap表中最大tid
    $maxTid = BaidusubmitSitemap::getMaxTid();  //论坛数据中最大tid

    $count = $maxTid - $sitemapMaxTid;

    //新数据够生成一个sitemap时，生成新的sitemap
    if (!$sitemapMaxTid || $count >= $config['sitemapItemCount']) {
        $sitemapCount = ceil($count/$config['sitemapItemCount']);
        $next_tid = $sitemapMaxTid + 1;
        for ($i = 0; $i < $sitemapCount; $i++) {
            $start_tid = $next_tid + $i*$config['sitemapItemCount'];
            $end_tid = $start_tid + $config['sitemapItemCount'] - 1;
            $url = 'm=sitemapall&start='.$start_tid;
            BaidusubmitSitemap::addSitemap($url, BaidusubmitSitemap::TYPE_ALL, $start_tid, $end_tid);
        }
    }

    BaidusubmitSitemap::printIndexHeader();

    $site = BaidusubmitOptions::getOption('siteurl');
    $sitemapCount = BaidusubmitSitemap::getSitemapCount(BaidusubmitSitemap::TYPE_ALL);
    $sitemapUrlCount = $config['sitemapUrlCount'] > 0 ? intval($config['sitemapUrlCount']) : 50000;

    //全取出来
    if ($sitemapCount <= $sitemapUrlCount) {
        $sitemaplist = BaidusubmitSitemap::getSitemapList(BaidusubmitSitemap::TYPE_ALL, 0, $sitemapCount);
        if (count($sitemaplist) > 0) {
            BaidusubmitSitemap::printSitemapList($sitemaplist, $site, $urlsuffix);
        }
        BaidusubmitSitemap::printIndexFooter();
        BaidusubmitSitemap::setIndexLastCrawl(0);
        return 1;
    }

    //分段取
    $lastcrawl = BaidusubmitSitemap::getIndexLastCrawl();
    $time = time();

    $pasttime = $time - $lastcrawl['stime'];
    if ($pasttime < $config['sitemapStepTime']) { //没到一个时段则按上次的偏移量
        $offset = intval($lastcrawl['offset']);
    } else {
        $step = $config['sitemapStepLength'];
        if ($step > $sitemapUrlCount) {
            $step = $sitemapUrlCount;
        }
        $offset = $lastcrawl['offset'] + $step * intval($pasttime / $config['sitemapStepTime']);
    }

    if ($offset > $sitemapCount) {
        $offset = 0;
    }
    $sitemaplist = BaidusubmitSitemap::getSitemapList(BaidusubmitSitemap::TYPE_ALL, $offset, $sitemapUrlCount);

    if (count($sitemaplist) > 0) {
        BaidusubmitSitemap::printSitemapList($sitemaplist, $site, $urlsuffix);
    }

    //如果溢出了
    $overflow = $offset + $sitemapUrlCount - $sitemapCount;
    if ($overflow > 0) {
        $sitemaplist = BaidusubmitSitemap::getSitemapList(BaidusubmitSitemap::TYPE_ALL, 0, $overflow);
        if (count($sitemaplist) > 0) {
            baidu_print_sitemap_list($sitemaplist, $site, $urlsuffix);
            BaidusubmitSitemap::printSitemapList($sitemaplist, $site, $urlsuffix);
        }
    }

    BaidusubmitSitemap::printIndexFooter();
    BaidusubmitSitemap::setIndexLastCrawl($offset);
}

//增量索引
if ('indexinc' === $_GET['m']) {
    $today = strtotime(date('Y-m-d'));
    $removeTime = $today - $config['HistoryDayCount'] * 24 * 3600;  //几天前时间
    BaidusubmitSitemap::deleteIncreaseHistory($removeTime);  //删除过期数据

    $lastTime = BaidusubmitSitemap::getSitemapMaxEnd(BaidusubmitSitemap::TYPE_INC);  //sitemap表中最后时间
    if (empty($lastTime)) {
        $lastTime = $today;
    }
    if ($today >= $lastTime) {
        $url = 'm=sitemapinc&start='.$today;
        BaidusubmitSitemap::addSitemap($url, BaidusubmitSitemap::TYPE_INC, $today, $today+86399);
    }

    BaidusubmitSitemap::printIndexHeader();
    $sitemaps = BaidusubmitSitemap::getSitemapList(BaidusubmitSitemap::TYPE_INC);
    if (count($sitemaps) > 0) {   //返回增量sitemap的索引文件
        $site = BaidusubmitOptions::getOption('siteurl');
        BaidusubmitSitemap::printSitemapList($sitemaps, $site, $urlsuffix);
    }
    BaidusubmitSitemap::printIndexFooter();
}

//全量sitemap
if ('sitemapall' === $_GET['m']) {
    $start_tid = intval($_GET['start']);
    $sitemap = BaidusubmitSitemap::getSitemap(BaidusubmitSitemap::TYPE_ALL, $start_tid);
    if (empty($sitemap)) {
        BaidusubmitSitemap::headerStatus(404);
        return 1;
    }

    $end_tid = $sitemap->end;

    $pidlist = BaidusubmitSitemap::getPostIdByIdRange($start_tid, $end_tid);
    $itemCount = 0;
    $fileSize = 0;
    $urlnum = 0;
    $installmaxtid = BaidusubmitOptions::getOption('installmaxtid');

    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';

    foreach ($pidlist as $pid) {
        $schema = BaidusubmitSitemap::genSchemaByPostId($pid, $post);
        $output = $schema->toXml() . "\n";
        $fileSizeCheck = $fileSize + strlen($output);
        $itemCountCheck = $itemCount + 1;

        // split sitemap file
        if ($fileSizeCheck >= 1024*1024*8 || $itemCountCheck > 5000) {
            // concurrent request
            $sp = BaidusubmitSitemap::getSitemap(BaidusubmitSitemap::TYPE_ALL, $start_tid, $end_tid);
            if ($sp) {
                $new_start_tid = $post->ID;
                $past_tid = $new_start_tid - $start_tid - 1;
                $count = ceil(($end_tid - $new_start_tid) / $past_tid);

                for ($i=0; $i<$count; $i++) {
                    $_xstart = $new_start_tid + $past_tid * $i;
                    $_xend = $_xstart + $past_tid - 1;
                    if ($_xend > $end_tid) {
                        $_xend = $end_tid;
                    }
                    $url = "m=sitemapall&start={$_xstart}";
                    BaidusubmitSitemap::addSitemap($url, BaidusubmitSitemap::TYPE_ALL, $_xstart, $_xend);
                }

                $new_end_tid = $new_start_tid - 1;
                $new_url = "m=sitemapall&start={$start_tid}";
                BaidusubmitSitemap::updateSitemap(
                        $sp->sid,
                        array('url' => $new_url, 'start' => $start_tid, 'end' => $new_end_tid));

                $end_tid = $new_end_tid;
            }
            break;
        }
        echo $output;

        $fileSize = $fileSizeCheck;
        $itemCount = $itemCountCheck;

        if ($tid <= $installmaxtid) {
            $urlnum ++;
        }

        flush();
    }
    echo '</urlset>';

    $timeLost = intval(1000 * (microtime(true) - __BAIDU_START_TIME__));
    BaidusubmitSitemap::updateSitemap(
            $sitemap->sid,
            array('item_count' => $itemCount, 'file_size' => $fileSize, 'lost_time' => $timeLost));

    BaidusubmitSitemap::updateUrlStat($urlnum);
}

//增量sitemap
if ('sitemapinc' === $_GET['m']) {
    $startTime = intval(@$_GET['start']);
    $sitemap = BaidusubmitSitemap::getSitemap(BaidusubmitSitemap::TYPE_INC, $startTime);
    if (empty($sitemap)) {
        BaidusubmitSitemap::headerStatus(404);
        return 1;
    }
    $endTime = $sitemap->end;

    define('_BAIDU_MAX_POST_COUNT_', 5000);
    $pidlist = BaidusubmitSitemap::getPostIdByTimeRange($startTime, $endTime, _BAIDU_MAX_POST_COUNT_);

    $indexsplitsitemap = false;
    $postCount = count($pidlist);
    if ($postCount >= _BAIDU_MAX_POST_COUNT_) {
        $indexsplitsitemap = true;
    }
    $itemCount = 0;
    $fileSize = 0;
    $index = 0;


    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';

    $sizesplitsitemap = false;
    foreach ($pidlist as $pid) {
        $schema = BaidusubmitSitemap::genSchemaByPostId($pid, $post);
        $output = $schema->toXml() . "\n";
        $fileSizeCheck = $fileSize + strlen($output);
        $itemCount += 1;

        if ($fileSizeCheck >= 1024*1024*8) {
            $sizesplitsitemap = true;
            break;
        }

        $fileSize = $fileSizeCheck;

        echo $output;
        flush();
    }
    echo '</urlset>';

    //分裂数据
    if ($sizesplitsitemap || ($indexsplitsitemap && $thread['lastpost']<$endTime)) {  //超过sitemap文件限制进行分裂
        $sp = BaidusubmitSitemap::getSitemap(BaidusubmitSitemap::TYPE_INC, $startTime, $endTime);
        if ($sp) {
            //计算裂变
            $newStartTime = strtotime($post->post_date);
            //裂变步长
            $stepLen = intval(($newStartTime - $startTime - 1) * 0.3);
            $curTime = time();
            //只裂变到当前时间
            $count = ceil(($curTime - $newStartTime) / $stepLen);
            for ($i=0; $i<$count; $i++) {
                $_xstart = $newStartTime + $stepLen * $i;
                $_xend = $_xstart + $stepLen - 1;
                if ($_xend > $curTime) {
                    $_xend = $curTime;
                }
                $url = "m=sitemapinc&start={$_xstart}";
                BaidusubmitSitemap::addSitemap($url, BaidusubmitSitemap::TYPE_INC, $_xstart, $_xend);
            }
            //把最后一个加上
            $nextTime = $curTime + 1;
            BaidusubmitSitemap::addSitemap("m=sitemapinc&start={$nextTime}", BaidusubmitSitemap::TYPE_INC, $nextTime, $endTime);

            $newEndTime = $newStartTime - 1;
            $newUrl = "m=sitemapinc&start={$startTime}";
            BaidusubmitSitemap::updateSitemap(
                        $sp->sid,
                        array('url' => $newUrl, 'start' => $startTime, 'end' => $newEndTime));

            $endTime = $newEndTime;
        }
    }


    //记录相关数据
    $timeLost = intval(1000 * (microtime(true) - __BAIDU_START_TIME__));

    BaidusubmitSitemap::updateSitemap(
            $sitemap->sid,
            array('item_count' => $itemCount, 'file_size' => $fileSize, 'lost_time' => $timeLost));

    BaidusubmitSitemap::updateUrlStat($itemCount);
}