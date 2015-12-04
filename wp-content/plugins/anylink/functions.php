<?php
defined( 'ABSPATH' ) OR exit;
if(!function_exists('_log')){
  function _log( $str = '', $message ) {
    if( WP_DEBUG === true ){
      if( is_array( $message ) || is_object( $message ) ){
		error_log( $str );
        error_log( print_r( $message, true ) );
      } else {
		error_log( $str );
        error_log( $message );
      }
    }
  }
}
function indexOf( $substr, $str ) {
	if( strpos( $str, $substr ) === 0 )
		return true;
	else
		return false;
}
function index2Of( $str1, $str2 ) {
	if( indexOf( $str1, $str2 ) || indexOf( $str2, $str1 ) )
		return true;
	else
		return false;
}

/**
 * Covert any link to be used as a parameter
 * 
 * if a link is not in an article, you can call method anylink anywhere
 * to covert it manually.
 *
 * @param string $link external link to be covert
 * @param integer $postId if the link doesn't belongs any post it will be set to 0
 *
 * @example call anylink() like this:
 * <?
 * 		echo function_exists('anylink') ? anylink('http://dudo.org', get_the_ID()) : 'http://dudo.org';
 * ?>
 * @since version 0.1.5
 */
 
function anylink( $link, $postId = 0 ) {
	require_once( ANYLNK_PATH . '/config.php' );
	require_once( ANYLNK_PATH . '/classes/al_covert.php' );
	require_once( ANYLNK_PATH . '/classes/al_filter.php' );
	require_once( ANYLNK_PATH . '/classes/al_slug.php' );
	require_once( ANYLNK_PATH . '/classes/al_option.php' );
	
	$covert = new al_covert;
	$filter = new al_filter;
	$id = $covert -> storeExtLnks( ( array )$link );
	//because method storeRel will delete relationships
	//so we must get all relationships to compare
	$urls = $filter -> getAllLnks( $postId );
	if( ! empty( $urls ) ) {
		foreach( $urls as $url ){
			$oldUrlIds[] = $url['al_id'];
		}
	}
	$urlIds = array_merge( $oldUrlIds, $id );
	$covert -> storeRel( $postId, $urlIds );
	$objSlug = $filter -> getSlugById( $id );
	return $filter -> getInternalLinkBySlug( $objSlug['al_slug'] );
}
//Install
function anylnkInstall() {
	global $wpdb;
	//create tables
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$charset_collate = '';
	if( $wpdb->has_cap( 'collation' ) ){
		if( ! empty( $wpdb->charset ) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if( ! empty( $wpdb->collate ) )
		$charset_collate = $charset_collate . " COLLATE $wpdb->collate";
	}
	$sqlAnylnk = "CREATE TABLE " . ANYLNK_DBTB . " (
		al_id mediumint(8) NOT NULL AUTO_INCREMENT,
		al_slug varchar(255) NOT NULL,
		al_crtime datetime NOT NULL,
		al_origURL text NOT NULL,
		al_count mediumint(8) DEFAULT 0 NOT NULL,
		al_isAuto boolean DEFAULT 1,
		PRIMARY KEY  (al_id),
		KEY al_count (al_count),
		KEY al_slug (al_slug)
	) {$charset_collate};";
	$sqlIndex = "CREATE TABLE " . ANYLNK_DBINDEX. " (
		al_index_id mediumint(8) NOT NULL AUTO_INCREMENT,
		al_url_id mediumint(8) NOT NULL,
		al_post_id mediumint(8) NOT NULL,
		al_comm_id mediumint(8) DEFAULT 0 NOT NULL,
		PRIMARY KEY  (al_index_id),
		KEY al_url_id (al_url_id),
		KEY al_post_id (al_post_id),
		KEY al_comm_id (al_comm_id)
	) {$charset_collate};";
	dbDelta( $sqlAnylnk );
	dbDelta( $sqlIndex );
	//add options
	if( ! get_option( 'anylink_options' ) ) {
		add_option( 'anylink_options', 
			array( 
					'version' => 14,
					'redirectCat' => 'goto', 
					'oldCat' => 'goto',
					'redirectType' => '307',
					'oldRedirectType' => '307',
					'slugNum' => '4',
					'oldSlugNum' => '4',
					'slugChar' => '2',
					'oldSlugChar' => '2',
					'postType' => array(
										'post',
										'page',
										),
                    'rel' => 'nofollow',
                    'filter-comment' => 1,
					),
			'', 'no' );
		//add and flush rewrite rule
		add_rewrite_rule( "goto/([0-9a-z]{4,})/?$", 'index.php?goto=$matches[1]', 'top' );
		flush_rewrite_rules();
	} else {
		/* update option */
		$al_option = get_option( 'anylink_options' );
		if( ! isset( $al_option['version'] ) ) {
			$al_option['version'] = '0.12';
			$al_option['oldRedirectType'] = $al_option['redirectType'];
			update_option( 'anylink_options', $al_option );
		} 
		if ( ( float )$al_option['version'] < 14 ) {
			$al_option['postType'] = array( 'post', 'page' );
			$al_option['version'] = 14;
			update_option( 'anylink_options', $al_option );
		}
        if( (float )$al_option['version'] < 19 ) {
            global $wp_rewrite;
            $wp_rewrite -> flush_rules( true );
			add_rewrite_rule( '$cat/([0-9a-z]{4,})/?$', 'index.php?' . $al_option['redirectCat'] . '=$matches[1]', 'top' );
			flush_rewrite_rules();
            $al_option['version'] = 19;
            update_option( 'anylink_options', $al_option );
        }
        if( (float )$al_option['version'] < 20 ) {
            $al_option['filter-comment'] = 1;
            $al_option['version'] = 20;
            update_option( 'anylink_options', $al_option );
        }
	}		
}
function al_load_textdomain() {
	$pluginDir = basename( dirname( __FILE__ ) );
	load_plugin_textdomain( 'anylink', false, $pluginDir . '/i18n' );
}
?>