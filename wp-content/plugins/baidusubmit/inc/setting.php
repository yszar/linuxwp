<?php

/**
 * Administrator operate this file.
 */

class BaidusubmitSetting
{
    const UPDATED_COOKIE_NAME = 'bds_setting_updated';

    static public $isUpdated = false;

    static function showJson($var)
    {
        echo json_encode($var);
        exit;
    }

    static function authSite()
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php';
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sitemap.php';
        BaidusubmitOptions::setOption('siteurl', $_POST['siteurl']);

        $config = include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'const.php';

        $site = $_POST['siteurl'];
        //去站长平台获取随机串
        $result = BaidusubmitSitemap::httpSend($config['zzplatform'] . '/getCheckSign?siteurl=' . urlencode($site) . '&sitetype=' . $config['siteTypeKey']);
        $data = json_decode($result);

        if (isset($data->status) && '0' != $data->status) {
            self::showJson(array(
                'error' => 1,
                'msg' => __('Failed to get Checksign'),
            ));
        }

        BaidusubmitOptions::setOption('siteurl', $data->siteurl);
        BaidusubmitOptions::setOption('checksign', $data->checksign);

        //站长平台回调的URL
        $siteurl = BaidusubmitOptions::getOption('siteurl');
        $url = $siteurl . BAIDUSUBMIT_PLUGIN_PATH . 'baidusubmit/checksign.php?checksign=' . $data->checksign;
        $sigurl = $config['zzplatform'] . '/auth?checksign=' . $data->checksign . '&checkurl=' . urlencode($url) . '&siteurl=' . urlencode($siteurl);

        $authData = BaidusubmitSitemap::httpSend($sigurl);   //去站长平台进行验证
        BaidusubmitOptions::deleteOption('checksign');
        $output = json_decode($authData);

        if (isset($output->status) && '0' == $output->status) {
            //token
            $token = $output->token;

            //保存下旧密码
            $old_sppasswd = BaidusubmitOptions::getOption('sppasswd');

            //只有初次安装时才提交sitemap
            if (empty($old_sppasswd)) {
                $sppasswd = BaidusubmitSitemap::genSitemapPasswd();

                $result = 0;
                $sign = md5($siteurl.$token);
                //提交全量索引
                $allreturnjson = BaidusubmitSitemap::submitIndex('add', BaidusubmitSitemap::TYPE_ALL, $siteurl, $sppasswd, $sign);
                $allresult = json_decode($allreturnjson['body']);
                if (!isset($allresult->status) || '0' != $allresult->status) {
                    self::showJson(array(
                        'error' => 1,
                        'msg' => __('Sitemap submission failed', 'sitemap') . "[URL:{$allreturnjson['url']}]",
                    ));
                }
                $result += (int)$allresult->status;

                //提交增量索引
                $incresultjson = BaidusubmitSitemap::submitIndex('add', BaidusubmitSitemap::TYPE_INC, $siteurl, $sppasswd, $sign);
                $incresult = json_decode($incresultjson['body']);
                if (!isset($incresult->status) || '0' != $allresult->status) {
                    self::showJson(array(
                        'error' => 1,
                        'msg' => __('Sitemap submission failed', 'sitemap') . '[URL:' . $incresultjson['url'] . ']',
                    ));
                }
                $result += (int)$incresult->status;

                if ($result === 0) {
                    //tid节点
                    BaidusubmitOptions::setOption('installmaxtid', BaidusubmitSitemap::getMaxTid());
                    //保存sppasswd
                    BaidusubmitOptions::setOption('sppasswd', $sppasswd);
                    //为了避免提交sitemap超时，把token放在下。提交成功后再写token.
                    BaidusubmitOptions::setOption('pingtoken', $token);

                    self::showJson(array(
                        'error' => 0,
                        'msg' => __('Verification successful', 'sitemap'),
                    ));
                } else {
                    // delete sppassword
                    BaidusubmitOptions::setOption('sppasswd', '');
                    self::showJson(array(
                        'error' => 1,
                        'msg' => __('Sitemap submission failed', 'sitemap'),
                    ));
                }
            }
            else {
                //为了避免提交sitemap超时，把token放在下。提交成功后再写token.
                BaidusubmitOptions::setOption('pingtoken', $token);
                self::showJson(array(
                    'error' => 0,
                    'msg' => __('Verification successful', 'sitemap'),
                ));
            }

        }
        elseif (in_array($output->status, array(1, 2001, 2002, 2003,2008))) {
            $e = array(
                1    => __('Parameter error', 'sitemap'),
                2    => __('No site information', 'sitemap'),
                100  => __('System error', 'sitemap'),
                2001 => __('Checksign does not exists', 'sitemap'),
                2002 => __('Sign detection failed', 'sitemap'),
                2003 => __('Checkurl request failed', 'sitemap'),
                2008 => __('Checkurl does not belong to siteurl', 'sitemap'),
            );
            self::showJson(array(
                'error' => 1,
                'msg' => $e[$output->status],
            ));
        }
        else {
            self::showJson(array(
                'error' => 1,
                'msg' => __('Verification failed, please try again', 'sitemap'),
            ));
        }

    }

    static function saveSettings()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php';
            BaidusubmitOptions::setOption('openping', (int)(bool)$_POST['openping']);
            setcookie(self::UPDATED_COOKIE_NAME, 1, time()+300);
            header('Location: admin.php?page=baidu_admin');
        }
    }

    static function checkUpdated()
    {
        self::$isUpdated = isset($_COOKIE[self::UPDATED_COOKIE_NAME]);
        if (self::$isUpdated) {
            setcookie(self::UPDATED_COOKIE_NAME, '', time()-86400*10);
        }
    }

    static function showAdminPage()
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'options.php';

        $siteurl = BaidusubmitOptions::getOption('siteurl');
        $openping = BaidusubmitOptions::getOption('openping');
        $token = BaidusubmitOptions::getOption('pingtoken');

        self::showHeader('admin');

        if (self::$isUpdated) {
            echo '<div class="updated"><strong><p>', __('Configuration saved', 'sitemap'), '</p></strong></div>';
        }

        echo '<div class="titlebar">', __('Introduction & Description', 'sitemap'), '</div>';
        echo '<ul>
            <li>', __('After installing Baidu Sitemaps, you can quickly and completely submit webpages and content to Baidu. It helps you with the following:', 'sitemap'), '</li>
            <li>
                ', __('1) Baidu Spider will better understand and include your site', 'sitemap'), '
            </li>
            <li>
                ', __('2) Baidu Search will better display results from your site', 'sitemap'), '
            </li>
            <!--<li>
                ', __('You can also go to Baidu Webmaster Platform and submit: Sitemap | Structured Data | Broken Link Submission', 'sitemap'), '
            </li>-->
            <li>
                ', __('When using our products, please send any comments or suggestions to the Baidu Webmaster Feedback Center', 'sitemap'), '
            </li>
            </ul>';
?>
<script type="text/javascript">
function baidu_ajaxpost2(formid, showid, submitid)
{
    var $ = jQuery;
    $('#'+showid).html('<?php echo __('Verifying...', 'sitemap'); ?>');
    var curform = $('#'+formid)[0];

    $('#'+submitid)[0].disabled = true;
    $('#'+showid)[0].className = '';

    var postdata = {
        'siteurl' : curform.elements['siteurl'].value
    };

    $.post(curform.action, postdata, function(s) {
        s = eval('(' + s + ')');
        $('#'+showid).html(s.msg);
        if (s.error === 0) {
            $('#'+showid)[0].className = authStatus.success.msgclass;
            $('#'+submitid)[0].value = authStatus.success.btntext;
            $('#siteurl')[0].disabled = true;
            $('#siteurl')[0].className = authStatus.success.inputclass;
            //打开全局
            bds_isExist = 1;
        } else {
            $('#'+showid)[0].className = authStatus.failed.msgclass;
            $('#'+submitid)[0].value = authStatus.failed.btntext;
            $('#siteurl')[0].disabled = false;
            $('#siteurl')[0].className = authStatus.failed.inputclass;
        }
        $('#'+submitid)[0].disabled = false;
    });

    return false;
}
var bds_isExist = <?php if($token){ echo '1'; }else{ echo '0'; } ?>;

var authStatus = {
    'success':{
        'btntext': '<?php echo __('Re-verify', 'sitemap'); ?>',
        'msgclass': 'success-msg',
        'inputclass': 'dsclass'
    },
    'failed':{
        'btntext': '<?php echo __('Verify', 'sitemap'); ?>',
        'msgclass': 'failed-msg',
        'inputclass': ''
    }
};

function formsubmit()
{
    if (1 === bds_isExist) {
        var $ = jQuery;
        $('#submitbutton')[0].value = '<?php echo __('Verify', 'sitemap'); ?>';
        $('#siteurl')[0].disabled = false;
        $('#siteurl')[0].className = '';
        $('#returnmessage')[0].innerHTML = '';
        bds_isExist = 0;
    } else {
        baidu_ajaxpost2('authform', 'returnmessage', 'submitbutton');
    }
    return false;
}
</script>
<style type="text/css">
h2 { border-bottom: 1px solid #DFDFDF; padding: 0 0 10px !important }
div.updated { margin: 5px 0 15px; }
.titlebar { background:#DFDFDF; padding:5px;}
.dsclass { background:#EEEEEE !important; }
.failed-msg { color:#FF0000; }
.success-msg {  }
.radio-box {overflow:auto; zoom:1}
.radio-box li { display: inline-block; float: left; margin-right: 20px;}
#authform input {margin-left: 15px; }
#returnmessage {margin-left: 15px; }
</style>
<?php
        echo '<div class="titlebar">', __('Site verification', 'sitemap'), '</div>';
        echo '<ul><li><form action="admin.php?page=baidu_admin&action=auth" method="post" id="authform" onsubmit="return formsubmit()">',
            __('System installation URL path', 'sitemap'),
            '<input type="text" id="siteurl" name="siteurl" style="width:300px;" ',
                ($siteurl&&$token ? 'disabled="disabled" class="dsclass" value="'.$siteurl.'"' : 'value="'. get_option('siteurl') .'"'),
                ' />',
            '<input id="submitbutton" type="submit" class="button botton-secondary" value="', __(($siteurl&&$token ? 'Re-verify' : 'Verify') , 'sitemap'), '" /><span id="returnmessage"></span>',
            '</form></li></ul>';

        echo '<div class="titlebar">', __('Active submit', 'sitemap'), '</div>',
            '<form action="admin.php?page=baidu_admin&action=savesetting" method="post">',
            '<ul class="radio-box">',
            '<li><label for="baidusubmit-openping-1">', '<input type="radio" value="1" name="openping"' . ($openping ? ' checked="checked"' : '') . ' />', __('Enable'), '</label></li>',
            '<li><label for="baidusubmit-openping-0">', '<input type="radio" value="0" name="openping"' . (!$openping ? ' checked="checked"' : '') . ' />', __('Disable'), '</label></li>',
            '<li><input type="submit" class="button button-secondary" value="', __('Save'), '" /></li>',
            '</ul>',
            '</form>';
    }

    static function showUrlstatPage()
    {
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'sitemap.php';
        $list = BaidusubmitSitemap::getUrlStat();
        self::showHeader('urlstat');
        echo '<table id="urlstat">',
             '<tr><th>', __('Date', 'sitemap'), '</th><th>', __('Quantity', 'sitemap'), '</th><th>', __('Total', 'sitemap'), '</th></tr>';
        foreach ($list as $x) {
             echo '<tr><td>', date('Y-m-d', $x->ctime), '</td><td>', $x->urlnum, '</td><td>', $x->urlcount, '</td></tr>';
        }
        echo '</table>';
?>
<style type="text/css">
#urlstat { border: 1px solid #DDDDDD; border-collapse:collapse; }
#urlstat th, #urlstat td { border:1px solid #DDDDDD; padding: 5px 15px; text-align: center; }
</style>
<?php
    }

    static function genLinks($current='')
    {
        $ret = array();
        $ret[] = '<a href="admin.php?page=baidu_admin"' . ('admin'===$current ? ' class="current"' : '') . '>' . __('Manage', 'sitemap') . '</a>';
        $ret[] = '<a href="admin.php?page=baidu_urlstat"' . ('urlstat'===$current ? ' class="current"' : '') . '>' . __('Submission History', 'sitemap') . '</a>';
        return $ret;
    }

    static function showHeader($current)
    {
        echo '<h2>', __('Baidu Sitemaps', 'sitemap'), BAIDUSUBMIT_VERSION, '</h2>';
        echo '<ul class="subsubsub" style="float:none;">';
        $links = self::genLinks($current);
        foreach ($links as $x) {
            echo '<li style="padding-right:20px"> ', $x, ' </li>';
        }
        echo '</ul>';
    }
}
