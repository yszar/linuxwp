<?php
/*
Plugin Name: WP AutoTags
Description: Often publish articles and updates people loves forgets to set tags when editing an article, the article automatically add keywords tag. tag extraction based on TF-IDF implementation. Built-in TF-IDF interface does not work when you try to find an existing tag in WordPress. If found, these markers are added to the post automatically each time you save the post.
Version: 0.1.5
Author: Zhys
Author URI: https://www.9sep.org/author/zhys
Plugin URI: http://www.9sep.org/wp-auto-tags
License: GPLv2
*/

function wp_aatags_html2text($ep){
    $search = array("'<script[^>]*?>.*?</script>'si",
        "'<[\/\!]*?[^<>]*?>'si",
        "'([\r\n])[\s]+'",
        "'&(quot|#34|#034|#x22);'i",
        "'&(amp|#38|#038|#x26);'i",
        "'&(lt|#60|#060|#x3c);'i",
        "'&(gt|#62|#062|#x3e);'i",
        "'&(nbsp|#160|#xa0);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&(reg|#174);'i",
        "'&(deg|#176);'i",
        "'&(#39|#039|#x27);'",
        "'&(euro|#8364);'i",
        "'&a(uml|UML);'",
        "'&o(uml|UML);'",
        "'&u(uml|UML);'",
        "'&A(uml|UML);'",
        "'&O(uml|UML);'",
        "'&U(uml|UML);'",
        "'&szlig;'i",
    );
    $replace = array("",
        "",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
        chr(161),
        chr(162),
        chr(163),
        chr(169),
        chr(174),
        chr(176),
        chr(39),
        chr(128),
        "ä",
        "ö",
        "ü",
        "Ä",
        "Ö",
        "Ü",
        "ß",
    );
    return preg_replace($search, $replace, $ep);
}

function wp_aatags_keycontents($keys,$num){
    $request = wp_remote_request('http://cws.9sep.org/extract/json',array('method'=>'POST','timeout'=>20,'body'=>array('text'=>$keys,'topk'=>$num)));
    if(wp_remote_retrieve_response_code($request) != 200){
        return 'rEr';
    }else{
        return wp_remote_retrieve_body($request);
    }
}

function wp_aatags_kwsiconv($kws){
    return @json_decode($kws,true)['kws'];
}

function wp_aatags_alts($post_ID,$post_title,$post_content){
    $tags = get_tags( array('hide_empty' => false) );
    $tagx=get_option('wp_aatags_opts');
    $number=get_option('wp_aatags_aadnumber');
    switch ($tagx) {
        case 3:
            $d = strtolower($post_title.' '.wp_trim_words($post_content,333,''));
            break;
        case 2:
            $d = strtolower($post_title.' '.wp_trim_words($post_content,999,''));
            break;
        default:
            $d = strtolower($post_title);
            break;
    }
    if ($tags) {
        $i=0;
        foreach ( $tags as $tag ) {
            if ( strpos($d, strtolower($tag->name)) !== false ){
                wp_set_post_tags( $post_ID, $tag->name, true );
                $i++;
            }
            if ($i == $number) break;
        }
    }
}

function wp_aatags_run($post_ID){
    $tags=get_option('wp_aatags_opts');
    $number=get_option('wp_aatags_aadnumber');
    global $wpdb;
    if(get_post($post_ID)->post_type == 'post' && !wp_is_post_revision($post_ID) && !get_the_tags($post_ID)) {
        $post_title = get_post($post_ID)->post_title;
        $post_content = get_post($post_ID)->post_content;
        switch ($tags) {
            case 3:
                $requix = strtolower($post_title.' '.wp_trim_words($post_content,333,''));
                break;
            case 2:
                $requix = strtolower($post_title.' '.wp_trim_words($post_content,999,''));
                break;
            default:
                $requix = strtolower($post_title);
                break;
        }
        $body = wp_aatags_keycontents(wp_aatags_html2text($requix),$number);
        if ($body != 'rEr') {
            $keywords = wp_aatags_kwsiconv($body);
            wp_add_post_tags($post_ID , $keywords);
        }else{
            wp_aatags_alts($post_ID,$post_title,$post_content);
        }
    }
}

function wp_aatags_admin_init(){
    if(get_bloginfo('language')=='zh-CN'||get_bloginfo('language')=='zh-TW'){
        $wp_aatags_setting='处理范围';
        $wp_aatags_number='标签数量';
    }else{
        $wp_aatags_setting='Matching range';
        $wp_aatags_number='Automatic Tags number';
    }
    add_settings_field('wp_aatags_opts',$wp_aatags_setting,'wp_aatags_setting','writing','default');
    add_settings_field('wp_aatags_aadnumber',$wp_aatags_number,'wp_aatags_aadnumber','writing','default');

    register_setting( 'writing', 'wp_aatags_opts' );
    register_setting( 'writing', 'wp_aatags_aadnumber' );
}

function wp_aatags_install($obj){
    add_option('wp_aatags_opts',3);
    add_option('wp_aatags_aadnumber',3);
}

function wp_aatags_uninstall(){
    delete_option('wp_aatags_opts');
    delete_option('wp_aatags_aadnumber');
    remove_action('admin_init','wp_aatags_admin_init');
}

function wp_aatags_setting(){
    $wp_aatags_opts = get_option('wp_aatags_opts');
?>

<select name="wp_aatags_opts">
    <option value="1" <?php selected('1', $wp_aatags_opts ); ?>><?php if(get_bloginfo('language')=='zh-CN'||get_bloginfo('language')=='zh-TW'): ?>仅匹配文章标题<?php else: ?>Only Posts Title<?php endif; ?></option>
    <option value="2" <?php selected('2', $wp_aatags_opts ); ?>><?php if(get_bloginfo('language')=='zh-CN'||get_bloginfo('language')=='zh-TW'): ?>文章内容前999字<?php else: ?>Only Posts Content before 999.<?php endif; ?></option>
    <option value="3" <?php selected('3', $wp_aatags_opts ); ?>><?php if(get_bloginfo('language')=='zh-CN'||get_bloginfo('language')=='zh-TW'): ?>标题+正文前333字<?php else: ?>Posts Title&Content before 333.<?php endif; ?></option>
</select>

<?php
}

function wp_aatags_aadnumber(){
    $wp_aatags_aadnumber = get_option('wp_aatags_aadnumber');
?>

<p><label><input name="wp_aatags_aadnumber" type="radio" value="3" <?php checked('3', $wp_aatags_aadnumber ); ?>>3 </label><label><input name="wp_aatags_aadnumber" type="radio" value="5" <?php checked('5', $wp_aatags_aadnumber ); ?>> 5 </label><label><input name="wp_aatags_aadnumber" type="radio" value="9" <?php checked('9', $wp_aatags_aadnumber ); ?>> 9 </label><label><input name="wp_aatags_aadnumber" type="radio" value="15" <?php checked('15', $wp_aatags_aadnumber ); ?>> 15</label></p>

<?php
}

register_activation_hook(__FILE__,'wp_aatags_install');
register_deactivation_hook(__FILE__,'wp_aatags_uninstall');

add_action('admin_init','wp_aatags_admin_init');

add_action('publish_post','wp_aatags_run');
add_action('edit_post','wp_aatags_run');


