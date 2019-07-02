<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_excerpt_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_excerpt_theme_setup', 1 );
	function planmyday_template_excerpt_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'excerpt',
			'mode'   => 'blog',
			'title'  => esc_html__('Excerpt', 'planmyday'),
			'thumb_title'  => esc_html__('Large image (crop)', 'planmyday'),
			'need_terms' => true,
//			'w'		 => 770,
//			'h'		 => 434
		));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_excerpt_output' ) ) {
	function planmyday_template_excerpt_output($post_options, $post_data) {
		$show_title = true;
		$tag = planmyday_in_shortcode_blogger(true) ? 'div' : 'article';
		$post_id = get_the_ID();
		$post_month = apply_filters('planmyday_filter_post_date', get_the_date('M'), $post_id, get_post_type());
		$post_day = apply_filters('planmyday_filter_post_date', get_the_date('d'), $post_id, get_post_type());
		?>
		<<?php planmyday_show_layout($tag); ?> <?php post_class('post_item post_item_excerpt post_featured_' . esc_attr($post_options['post_class']) . ' post_format_'.esc_attr($post_data['post_format']) . ($post_options['number']%2==0 ? ' even' : ' odd') . ($post_options['number']==0 ? ' first' : '') . ($post_options['number']==$post_options['posts_on_page']? ' last' : '') . ($post_options['add_view_more'] ? ' viewmore' : '')); ?>>
			<?php
			if ($post_data['post_flags']['sticky']) {
				?><span class="sticky_label"></span><?php
			}

			if ($show_title && $post_options['location'] == 'center' && !empty($post_data['post_title'])) {
				?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php planmyday_show_layout($post_data['post_title']); ?></a></h3><?php
			}
			
			if (!$post_data['post_protected'] && (!empty($post_options['dedicated']) || $post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio'])) {
				?>
				<div class="post_featured">
				<?php
				if (!empty($post_options['dedicated'])) {
					planmyday_show_layout($post_options['dedicated']);
				} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio']) {
					planmyday_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					require get_template_directory().'/templates/_parts/post-featured.php';
				}
				?>
				</div>
			<?php
			}
			?>
	
			<div class="post_content clearfix">
				<div class="custom_post_containter">
					<div class="custom_post_info_date">
						
						<div class="post_info_date_month"><?php echo esc_attr($post_month); ?></div>
						<div class="post_info_date_day"><?php echo esc_attr($post_day); ?></div>

					</div><div class="custom_post_info">
						<?php
						if ($show_title && $post_options['location'] != 'center' && !empty($post_data['post_title'])) {
							?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php planmyday_show_layout($post_data['post_title']); ?></a></h3><?php 
						}
						
						if (!$post_data['post_protected'] && $post_options['info']) {
							planmyday_template_set_args('post-info', array(
								'post_options' => $post_options,
								'post_data' => $post_data
							));
							require get_template_directory().'/templates/_parts/post-info.php';
						}
						?>
					</div>
				</div>
				<div class="post_descr">
				<?php
					if ($post_data['post_protected']) {
						planmyday_show_layout($post_data['post_excerpt']); 
					} else {
						// Uncomment next rows to show full content in the blogger if descr==0
						//if ($post_data['post_content'] && isset($post_options['descr']) && $post_options['descr']==0 ) {
						//	echo $post_data['post_content'];
						//} else 
						if ($post_data['post_excerpt']) {
							echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(planmyday_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : planmyday_get_custom_option('post_excerpt_maxlength'))).'</p>';
						}
					}
					if (empty($post_options['readmore'])) $post_options['readmore'] = esc_html__('Read more', 'planmyday');
					if (!planmyday_param_is_off($post_options['readmore']) && !in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) && function_exists('planmyday_sc_button')) {
						planmyday_show_layout(planmyday_sc_button(array('size'=>'small', 'type'=>"round", 'style'=>'border_black',  'icon'=>"icon-right-small", 'link'=>$post_data['post_link']), $post_options['readmore']));
					}
				?>
				</div>

			</div>	<!-- /.post_content -->
			<!--<div class="sc_line_style_dot_line"></div>-->

		</<?php planmyday_show_layout($tag); ?>>	<!-- /.post_item -->

	<?php
	}
}
?>