<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_news_portfolio_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_news_portfolio_theme_setup', 1 );
	function planmyday_template_news_portfolio_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'news-portfolio',
			'template' => 'news-portfolio',
			'mode'   => 'news',
			'title'  => esc_html__('Recent News /Style Portfolio/', 'planmyday'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'planmyday'),
			'w'		 => 370,
			'h'		 => 209
		));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_news_portfolio_output' ) ) {
	function planmyday_template_news_portfolio_output($post_options, $post_data) {
		$style = $post_options['layout'];
		$number = $post_options['number'];
		$count = $post_options['posts_on_page'];
		$columns = $post_options['columns_count'];
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
 
		if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?>"><?php
		}
		?><article id="post-<?php echo esc_html($post_data['post_id']); ?>" 
			<?php post_class( 'post_item post_layout_'.esc_attr($style)
					.' post_format_'.esc_attr($post_data['post_format']) ); ?>
			>
		
			<?php
			if ($post_data['post_flags']['sticky']) {
				?><span class="sticky_label"></span><?php
			}
			?>
			<div class="post_featured">
				<?php
				if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) {
					planmyday_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					require get_template_directory().'/templates/_parts/post-featured.php';
					if (!$post_data['post_video'] && !$post_data['post_audio'] && !$post_data['post_gallery']) {
						?>
						<div class="post_info">
							<span class="post_categories"><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?></span>
							<?php
							if (!isset($post_options['links']) || $post_options['links']) {
								?>
								<h5 class="post_title entry-title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php planmyday_show_layout($post_data['post_title']); ?></a></h5>
								<?php
							} else {
								?>
								<h5 class="post_title entry-title"><?php planmyday_show_layout($post_data['post_title']); ?></h5>
								<?php
							}
							if ( in_array( $post_data['post_type'], array( 'post', 'attachment' ) ) ) {
								?><div class="post_meta"><span class="post_meta_author"><?php planmyday_show_layout($post_data['post_author_link']); ?></span><?php
								?><span class="post_meta_date"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_date']); ?></a></span></div><?php
							}
							?>
						</div>
						<?php
					}
				}
				?>
			</div>
		</article><?php
		
		if ($columns > 1) {
			?></div><?php
		}
	}
}
?>