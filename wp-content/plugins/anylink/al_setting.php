<?php
defined( 'ABSPATH' ) OR exit;
?>
<div class="wrap">
	<div id="icon-link-manager" class="icon32"></div><h2><?php _e( 'anylink Settings', 'anylink' ); ?></h2>
	<form action="options.php" method="post">
	<?php settings_fields( 'anylink_options_group' ); ?>
	<?php do_settings_sections( 'anyLinkSetting' ); ?>
	<?php submit_button() ;?>
	</form>
		<h3><?php _e( 'Establish Index', 'anylink' ); ?></h3>
	<form action="<?php echo admin_url( 'options-general.php?page=anyLinkSetting' ); ?>" method="post">
	<div id="anylink_index">
		<span class="plain"><?php _e( 'For the first time after you running anylink, you need indexing ALL posts. It\'ll let you establish index for the posts already in your Wordpress. For the newly publish or update post, index is done automatically. And you needn\'t do anything.', 'anylink' ); ?></span>
		<div id="anylink_bar">
			<div id="anylink_proceeding"> </div>
		</div>
		<input name="action" value="anylink_scan" type="hidden" />
		<?php submit_button( __( 'Establish Index', 'anylink' ), 'secondary' ) ;?>
	</div>
	</form>
	<form action="<?php echo admin_url( 'options-general.php?page=anyLinkSetting' ); ?>" method="post">
		<span class="plain"><?php _e( 'For the first time running, you need scan all the exist comments manually.' ) ?></b></span>
		<div id="slug_bar">
			<div id="anylink_comment_proceeding"> </div>
		</div>
		<input name="action" value="anylink_comment_scan" type="hidden" />
		<?php submit_button( __( 'Regenerate comment slugs', 'anylink' ), 'secondary' ); ?>
	</form>
	<form action="<?php echo admin_url( 'options-general.php?page=anyLinkSetting' ); ?>" method="post">
		<span class="plain"><?php _e( 'Allows you to generate slugs manually. Keep in mind that please do not regenerate slugs unless you changed slug settings. Search engines may think that you have modified your articles.', 'anylink' ); ?><br /><b><?php _e( 'Note: It won\'t work unless slug settings are changed', 'anylink' ); ?></b></span>
		<div id="slug_bar">
			<div id="anylink_slug_proceeding"> </div>
		</div>
		<input name="action" value="anylink_regnerate" type="hidden" />
		<?php submit_button( __( 'Regenerate slugs', 'anylink' ), 'secondary' ); ?>
	</form>
</div>
<?php
if( isset( $_POST['action'] ) && $_POST['action'] == 'anylink_comment_scan' ) {
    flush();
    set_time_limit( 0 );
   	require_once( ANYLNK_PATH . "/classes/al_covert.php" );
	$objAllPost = new al_covert();
    $comment_IDs = $objAllPost -> get_all_comments();
    $j = count( $comment_IDs );
    $k = 0;
    foreach( $comment_IDs as $comment_ID ) {
        $objAllPost -> covertURLs( $comment_ID, true );
        $k += 1;
 ?>
 <script type="text/javascript">setDivStyle( "anylink_comment_proceeding", <?php echo round( $k / $j, 4 ); ?> ); </script> 
 <?php
    }
}
?>
<?php
if( isset( $_POST['action'] ) && $_POST['action'] == 'anylink_scan' ) {
	flush();
	set_time_limit( 0 );
	require_once( ANYLNK_PATH . "/classes/al_covert.php" );
	$objAllPost = new al_covert();
	$arrPostID = array();
	$arrPostID = $objAllPost -> arrGetPostIDs();
	$j = count( $arrPostID );
	$k = 0;
	foreach( $arrPostID as $ID ) {
		$objAllPost -> covertURLs( $ID );
		$k = $k + 1;
?>
<script type="text/javascript">setDivStyle( "anylink_proceeding", <?php echo round( $k / $j, 4 ); ?> ); </script> 
<?php
	flush();
	}
}
if( isset( $_POST['action'] ) && $_POST['action'] == 'anylink_regnerate' ) {
	$alOption = get_option( 'anylink_options' );
	if( $alOption['slugNum'] != $alOption['oldSlugNum'] || $alOption['slugChar'] != $alOption['oldSlugChar'] ) { 
		flush();
		set_time_limit( 0 );
		require_once( ANYLNK_PATH . "/classes/al_covert.php" );
		$objAllSlug = new al_covert();
		$arrSlugID = $objAllSlug -> getAllSlugID();
		$all = count( $arrSlugID );
		if( $all == 0 )
			$all = 1;
		$p = 0;
		$alOption['oldSlugNum'] = $alOption['slugNum'];
		$alOption['oldSlugChar'] = $alOption['slugChar'];
		update_option( 'anylink_options', $alOption );
		foreach( $arrSlugID as $slugID ) {
			$objAllSlug -> regenerateSlugByID( $slugID );
			$p = $p +1;
	?>
	<script type="text/javascript">setDivStyle( "anylink_slug_proceeding", <?php echo round( $p / $all, 4 ); ?> ); </script> 
	<?php
		flush();
		}
	}
}
?>