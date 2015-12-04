<?php
/**
Plugin Name: anyLink
Plugin URI: http://dudo.org/anylink
Description: Anylink 是一款链接转换插件，它可以把长链接转换成短链接，也可以将外部链接转换成内部链接，同时还可以隐藏真实的链接地址。Anylink针对搜索引擎进行了专门的SEO处理，可以有效防止网站权重对外传递。同时它不会修改wordpress自带的数据库，也不会修改文章中的任何内容，无论什么时候都不会影响到你数据的完整性。是同类插件中安全性较高、方便灵活的轻量级插件。
Version: 0.2.4
Author: dudo
Author URI: http://dudo.org/about
License: GPL2 or later
*/
defined( 'ABSPATH' ) OR exit;
require_once( 'config.php' );
require_once( ANYLNK_PATH . '/classes/al_covert.php' );
require_once( ANYLNK_PATH . '/classes/al_filter.php' );
require_once( ANYLNK_PATH . '/classes/al_slug.php' );
require_once( ANYLNK_PATH . '/functions.php' );
require_once( ANYLNK_PATH . '/classes/al_option.php' );

$filter = new al_filter();
$alOption = new al_option();

register_activation_hook( __FILE__, 'anylnkInstall' );
add_action( 'transition_post_status', 'post_published', 10, 3 );
add_action( 'wp_loaded','checkFlush' );
add_filter( 'query_vars', array( $filter, 'addQueryVars' ) );
add_action( 'parse_request', array( &$filter, 'alter_the_query' ) );
add_action( 'plugins_loaded', 'al_load_textdomain' );
add_filter( 'the_content', 'filterByType' );
add_filter( 'rewrite_rules_array','anylink_rewrite_rules' );
add_action( 'wp_insert_comment', 'new_comment',1000, 2);
add_filter( 'comment_text', 'filter_comment', 100, 2 );
add_filter( 'get_comment_author_url', 'filter_comment_urls', 100 );
/**
 * Check rewrite rules and flush
 *
 * Checking if rewrite rules are included, if not we should re-flush it;
 *
 * @see http://codex.wordpress.org/Class_Reference/WP_Rewrite#Examples
 * @since version 0.1.5
 */
function checkFlush() {
	$rules = get_option( 'rewrite_rules' );
	$alOption = get_option( 'anylink_options' );
	$cat = $alOption['redirectCat'];
	if( ! isset( $rules[$cat . '/([0-9a-z]{4,})/?$'] ) ){
		global $wp_rewrite;
		$wp_rewrite -> flush_rules();
	}
}

//add rewrite rules
function anylink_rewrite_rules( $rules ) {
	$alOption = get_option( 'anylink_options' );
	$cat = $alOption['redirectCat'];
	$newrules = array();
	$newrules[$cat . '/([0-9a-z]{4,})/?$'] = 'index.php?' . $cat . '=$matches[1]';
	return $newrules + $rules;
}

/**
 * This function is to replace old ACTTION hook 'publish_post'
 *
 * This change can filter and covert all post types besides
 * post itself.
 *
 * @param string $newStatus the new status of a post
 * @param string $oldStatus the old status of a post, if new to publish, it's new
 * @param string $post the post to add action
 *
 * @since version 0.1.4
 */ 
function post_published( $newStatus, $oldStatus, $post) {
	if( $newStatus == 'publish' ) {
		$covert = new al_covert();
		$covert -> covertURLs( $post -> ID );
	}
}

/**
 * This function is to filter the post whose post type is specified
 *
 * Anylink covert all external links at all time, 
 * but only filter the specified ones, the benefit of this is you needn't
 * regenerate slug any time you changed the post type
 *
 * @param object $content provided by hook
 * @return object $content
 *
 * @since version 0.1.4
 */
function filterByType( $content ) {
	$type  = get_post_type();
	$types = get_option( 'anylink_options' );
	$types = $types['postType'];
	if( empty( $types ) ){
		return $content;
	}
	if( ( is_string( $types ) && ( $types == $type ) ) || ( is_array( $types ) && array_search( $type, $types ) !== false ) ){
		$filter = new al_filter();
        $id = get_the_id();
		return $filter -> applyFilter( $content, $id );
		
	} else {
		return $content;
	}
}

/**
 * Call actions when a new comment added
 * @param int $comment_id is the id of the comment
 * @param object $comment is the comment object
 * @since 0.1.9
 */
 function new_comment( $comment_id, $comment) {
    $covert = new al_covert();
    $covert -> covertURLs( $comment_id, true );
 }
 /**
  * 把评论内容中的外链转换成内容
  * 
  * @param string $comment_text 评论内容
  * @param object $comment comment对象，默认为null
  * @return string $comment_text 返回处理后的评论内容以在页面上显示
  * @since 0.2
  */ 
function filter_comment( $comment_text, $comment = null ){
    $filter = new al_filter();
    if( is_null( $comment ) )
        return $comment_text;
    return $filter -> applyFilter( $comment_text, $comment -> comment_ID, true );
}
/**
 * 把留言者的链接转换成内链
 * 
 * @param string $comment_url 评论者留下的链接地址 
 * @since 0.2
 */
function filter_comment_urls( $comment_url ) {
    //如果没有留下链接，放弃处理
    if( empty( $comment_url ) )
        return $comment_url;
    $anylink_option = get_option( 'anylink_options' );
    $anylink_comment_option = $anylink_option['filter-comment'];
    //如果设置了不对评论中的链接进行处理则原样返回
    if( !$anylink_comment_option )
        return $comment_url;
    $filter = new al_filter();
    //通过链接查找对应的slug
    $new_comment_url_slug = $filter -> get_slug_by_url( $comment_url );
    if( !empty( $new_comment_url_slug ) ) {
        //根据slug产生内部链接，并返回给模板显示
        return $filter -> getInternalLinkBySlug( $new_comment_url_slug['al_slug'] );
    } else {    //如果链接表中没有索引这个链接，那么按原样返回
        return $comment_url;
    }
}
?>