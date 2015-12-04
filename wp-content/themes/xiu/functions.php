<?php 
/*
 *本主题由君子不器(Junzibuqi.Com)分享，若是有问题请到http://junzibuqi.com提出。
*/
include 'functions.xiu.php';
/*
*君子不器(Junzibuqi.Com)友情提示
*为了保持代码整洁于清晰，凡是需要添加到模板函数（functions.php）文件的代码请添加到下面的开始于结束之间
*/
//开始（添加到下面）
/*    if ( has_action( 'wp_print_styles', 'wp_syntax_style' ) ) {

    remove_action( 'wp_print_styles', 'wp_syntax_style' );

};*/

    /**
	    *
	    *      * Plugin Name: WPDX Replace Open Sans
	    *
	    *           * Plugin URI:  http://www.wpdaxue.com/dw-replace-open-sans.html
	    *
	    *                * Description: Change the load address of Open Sans.
	    *
	    *                     * Author:      Changmeng Hu
	    *
	    *                          * Author URI:  http://www.wpdaxue.com/
	    *
	    *                               * Version:     1.0
	    *
	    *                                    * License:     GPL
	    *
	    *                                         */


    //提高搜索结果相关性

    if(is_search()){

        add_filter('posts_orderby_request', 'search_orderby_filter');

            }

                function search_orderby_filter($orderby = ''){

                    	global $wpdb;

                    	    	$keyword = $wpdb->prepare($_REQUEST['s']);

			return "((CASE WHEN {$wpdb->posts}.post_title LIKE '%{$keyword}%' THEN 2 ELSE 0 END) + (CASE WHEN {$wpdb->posts}.post_content LIKE '%{$keyword}%' THEN 1 ELSE 0 END)) DESC,{$wpdb->posts}.post_modified DESC, {$wpdb->posts}.ID ASC"; 

                    	    	    	        }
function wpdx_replace_open_sans() {

	          wp_deregister_style('open-sans');

		        wp_register_style( 'open-sans', '//fonts.useso.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600' );

		        if(is_admin()) wp_enqueue_style( 'open-sans');

			    }

add_action( 'init', 'wpdx_replace_open_sans' );

add_filter('got_rewrite', 'nginx_has_rewrites');
function nginx_has_rewrites() {
    return true;
}
/*    function translate_chinese_post_title_to_en_for_slug( $title ) {

	            /*
		     *
		     *         transtype：
		     *
		     *                 trans
		     *
		     *                         realtime
		     *
		     *                                  */

/*	            $translation_render = 'http://fanyi.baidu.com/v2transapi?from=zh&to=en&transtype=realtime&simple_means_flag=3&query='.$title;

		            $wp_http_get = wp_safe_remote_get( $translation_render );

		            if ( empty( $wp_http_get->errors ) ) { 

				                if ( ! empty( $wp_http_get['body'] ) ) {

							                $trans_result = json_decode( $wp_http_get['body'], true );

									                $trans_title = $trans_result['trans_result']['data'][0]['dst'];

									                return $trans_title;

											            }

						        }

			            return $title;

			        } 

						add_filter( 'sanitize_title', 'translate_chinese_post_title_to_en_for_slug', 1 );*/
//结束（添加到上面）
