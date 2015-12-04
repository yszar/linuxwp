<?php
global $wpdb;
define( 'ANYLNK', 'anylink' );
define( 'ANYLNK_DBTB', $wpdb -> prefix . 'al_urls' );
define( 'ANYLNK_DBINDEX', ANYLNK_DBTB . '_index' );
define( 'ANYLNK_PLUGIN',dirname( plugin_basename( __FILE__ ) ) );
define( 'ANYLNK_PATH',WP_PLUGIN_DIR . '/' . ANYLNK_PLUGIN );
?>