<?php



class BaidusubmitOptions
{
    const PREFIX = 'baidusubmit_';

    static protected $_keys = array(
        'siteurl',
        'sppasswd',
        'pingtoken',
        'installmaxtid',
        'openping',
        'checksign',
        'lastcrawl',
        'enabled',
    );

    static function getOption($name, $default=null, $inc_time=false)
    {
        if (!in_array($name, self::$_keys)) return null;
        $value = get_option(self::PREFIX.$name, $default);
        $time = null;
        if ("\n\n" == substr($value, -12, 2) && preg_match('#^\d+$#', ($time = substr($value, -10)))) {
            $value = substr($value, 0, -12);
        }
        return $inc_time ? array('value' => $value, 'time' => $time) : $value;
    }

    static function setOption($name, $value)
    {
        if (!in_array($name, self::$_keys)) return false;
        $time = time();
        return update_option(self::PREFIX.$name, $value."\n\n".$time);
    }

    static function deleteOption($name)
    {
        delete_option(self::PREFIX.$name);
    }

    static function clearAllOptions()
    {
        foreach (self::$_keys as $key) {
            delete_option(self::PREFIX.$key);
        }
        global $wpdb;
        $wpdb->delete($wpdb->options, "option_name LIKE 'baidusubmit_%'");
    }
}