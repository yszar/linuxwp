<?php get_header(); ?>
<div class="content-wrap">
	<div class="content">
		<?php 
			if( _hui('ads_index_01_s') ) echo '<div class="ads ads-content">'.hui_get_adcode('ads_index_01').'</div>'; 
		
			if( !$paged && _hui('focusslide_s') ) hui_moloader('hui_focusslide');
		
			if( !$paged && _hui('focus_s') ) hui_moloader('hui_posts_focus');

			if( !$paged && _hui('most_list_s') ) hui_moloader('hui_recent_posts_most');

			if( !$paged && _hui('sticky_s') ) hui_moloader('hui_posts_sticky');
		?>
		<?php echo _hui('ads_index_02_s') ? '<div class="ads ads-content">'.hui_get_adcode('ads_index_02').'</div>' : '' ?>
		<?php 
			if( $paged && $paged > 1 ){
				printf('<h3 class="title"><strong>'.__('所有文章', 'haoui').'</strong> <small>'.__('第', 'haoui').$paged.__('页', 'haoui').'</small></h3>');
			}else{
				printf('<h3 class="title">'.(_hui('recent_posts_number')?'<small class="pull-right">'.__('24小时更新：', 'haoui').hui_get_recent_posts_number().__('篇', 'haoui').' &nbsp; &nbsp; '.__('一周更新：', 'haoui').hui_get_recent_posts_number(7).__('篇', 'haoui').'</small>':'').'<strong>'._hui('index_list_title').'</strong></h3>');
			}

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args = array(
			    'ignore_sticky_posts' => 1,
			    'paged' => $paged
			);

			if( _hui('notinhome') ){
				$pool = array();
				foreach (_hui('notinhome') as $key => $value) {
					if( $value ) $pool[] = $key;
				}
				$args['cat'] = '-'.implode($pool, ',-');
			}

			query_posts($args);

			get_template_part( 'excerpt' ); 
		?>
		<?php echo _hui('ads_index_03_s') ? '<div class="ads ads-content">'.hui_get_adcode('ads_index_03').'</div>' : '' ?>
	</div>
</div>
<?php get_sidebar(); get_footer(); ?>