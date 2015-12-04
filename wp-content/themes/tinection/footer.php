<?php
/**
 * Main Template of Tinection WordPress Theme
 *
 * @package   Tinection
 * @version   1.1.7
 * @date      2015.3.6
 * @author    Zhiyan <chinash2010@gmail.com>
 * @site      Zhiyanblog <www.zhiyanblog.com>
 * @copyright Copyright (c) 2014-2015, Zhiyan
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      http://www.zhiyanblog.com/tinection.html
**/

?>
<!-- Footer -->
<!-- Footer Wrap -->
<?php if(!tin_is_mobile()&&(int)ot_get_option('footer-widgets')>0) { ?>
<div id="footer-wrap">
	<div id="footer" class="layout-wrap">
		<!-- Footer Widgets -->
		<div id="footer-widgets">
			<?php
			$total = 3;
			if ( ot_get_option( 'footer-widgets' ) != '' ) {			
				$total = ot_get_option( 'footer-widgets' );
				if( $total == 1) $class = 'footer-widgets-one-full';
				if( $total == 2) $class = 'footer-widgets-one-half';
				if( $total == 3) $class = 'footer-widgets-one-third';
				if( $total == 4) $class = 'footer-widgets-one-fourth';
			}
			if ( (  is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) && $total > 0 ) { ?>
				<?php $i = 0; while ( $i < $total ) { $i++; ?>
					<?php if ( is_active_sidebar( 'footer-' . $i ) ) { ?>
		
				<div class="footer-widgets-<?php echo $i; ?> <?php echo $class; ?> <?php if ( $i == $total ) { echo 'last'; } ?>">
					<?php dynamic_sidebar( 'footer-' . $i ); ?>
				</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
		<div class="clr"></div>
		<!-- /.Footer Widgets -->
	</div>
</div>
<?php } ?>
<!-- /.Footer Wrap -->
<!-- Footer Nav Wrap -->
<footer id="footer-nav-wrap">
	<div id="footer-nav" class="layout-wrap">
		<div id="footer-nav-left">
			<!-- Footer Nav -->
			<?php wp_nav_menu(array('theme_location'=>'footbar','container_id'=>'footermenu','menu_class'=>'footermenu','menu_id' => 'footer-nav-links', 'depth'=> '1','fallback_cb'=> 'topbar')); ?>
			<!-- /.Footer Nav -->
			<!-- Copyright -->
			<div id="footer-copyright">&copy;<?php echo tin_copyright_year(); ?>
				<?php if(ot_get_option('copyright')) echo ot_get_option('copyright'); ?>&nbsp;|&nbsp;&nbsp;
				<a href="http://www.linuxwp.com"  target="_blank">LinuxWP</a>
				<?php if(ot_get_option('statisticcode')) echo '&nbsp;|&nbsp;'.ot_get_option('statisticcode'); ?>
			<?php if(ot_get_option('beian')) echo '&nbsp;|&nbsp;<a href="http://www.miitbeian.gov.cn/" target="_blank">'.ot_get_option('beian').'</a>'; ?>
			<!--<?php echo get_num_queries();?> queries in <?php timer_stop(1); ?> seconds.-->			
			</div>
			<!-- /.Copyright -->
		</div>
		<div id="footer-nav-right">
			<?php get_template_part('includes/footer-user'); ?>
		</div>
	</div>	
</footer>
<script type="text/javascript">
	$('.site_loading').animate({'width':'90%'},50);  //第四个节点
</script>
</div>
</section>
<?php get_template_part('includes/loginbox'); ?>
<?php get_template_part('includes/floatbutton'); ?>
<!-- /.Footer Nav Wrap -->
<?php $thelayout = the_layout(); if(is_home()&&$thelayout=='blocks'){ ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/jquery.masonry.js"></script>
<script type="text/javascript">
	$(function(){
		var $container = $('#fluid_blocks'),
			$items = $('#fluid_blocks .masonry-box');
		$items.imagesLoaded(function(){
			$items.fadeIn();
			$container.masonry({
				itemSelector:'.masonry-box',
				gutterWidth:0,
				isAnimated:false
			});
		});
	});
</script>
<?php } ?>
<script type="text/javascript">
/* <![CDATA[ */
var ajax_sign_object = <?php echo ajax_sign_object(); ?>;
/* ]]> */
</script>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/function.js"></script>
<script type="text/javascript">var defaultEncoding = 0; var translateDelay = 100; var cookieDomain = "<?php echo get_bloginfo('url'); ?>";</script>
<!-- 百度分享 -->
<script type="text/javascript" id="bdshare_js" data="type=tools&mini=2"></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
		//在这里定义bds_config
		var bds_config = {'snsKey':{'tsina':"<?php echo ot_get_option('tin_open_weibo_key','2884429244'); ?>",'tqq':"<?php echo ot_get_option('tin_open_qq_id','101166664'); ?>"}};
		document.getElementById('bdshell_js').src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
<!-- 引入用户自定义代码 -->
<?php if(ot_get_option('footercode')) echo ot_get_option('footercode'); ?>
<!-- 引入主题js -->
<?php wp_enqueue_script('tinection'); ?>
<?php wp_localize_script('tinection', 'tin', array('ajax_url' => admin_url('admin-ajax.php'),'tin_url' => get_bloginfo('template_directory'),'Tracker' => tin_tracker_param(),'uid' => get_current_user_id())); ?>
<?php wp_footer(); ?>
<!-- /.Footer -->
<script type="text/javascript">
	$('.site_loading').animate({'width':'100%'},50);  //第五个节点
</script>
</body>
</html>