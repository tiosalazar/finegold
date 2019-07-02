<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_single_standard_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_single_standard_theme_setup', 1 );
	function planmyday_template_single_standard_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'planmyday'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'planmyday'),
			'w'		 => 1170,
			'h'		 => 659
		));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_single_standard_output' ) ) {
	function planmyday_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && planmyday_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = planmyday_get_custom_option('show_post_title')=='yes' && (planmyday_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = planmyday_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		$post_id = get_the_ID();
		$post_month = apply_filters('planmyday_filter_post_date', get_the_date('M'), $post_id, get_post_type());
		$post_day = apply_filters('planmyday_filter_post_date', get_the_date('d'), $post_id, get_post_type());

		planmyday_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center' && planmyday_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><?php planmyday_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(planmyday_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				planmyday_show_layout($post_options['dedicated']);
			} else {
				planmyday_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php planmyday_show_layout($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		planmyday_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');

		if ($show_title && $post_options['location'] != 'center' && planmyday_get_custom_option('show_page_title')=='no' && planmyday_get_custom_option('show_post_info')=='yes'){
			?>
			<div class="custom_post_containter">
			<div class="custom_post_info_date">
				
				<div class="post_info_date_month"><?php echo esc_attr($post_month); ?></div>
				<div class="post_info_date_day"><?php echo esc_attr($post_day); ?></div>

			</div><div class="custom_post_info">
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><?php planmyday_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if ($show_title && $post_options['location'] != 'center' && planmyday_get_custom_option('show_page_title')=='no' && planmyday_get_custom_option('show_post_info')=='no'){
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title post_title_wo_info"><?php planmyday_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && planmyday_get_custom_option('show_post_info')=='yes') {
			$post_options['info_parts'] = array('snippets'=>true);
			planmyday_template_set_args('post-info', array(
				'post_options' => $post_options,
				'post_data' => $post_data
			));
			require get_template_directory().'/templates/_parts/post-info.php';
			if ($show_title && $post_options['location'] != 'center' && planmyday_get_custom_option('show_page_title')=='no' && planmyday_get_custom_option('show_post_info')=='yes'){
				?>
				</div>
				</div>
				<?php
			}
		}

		planmyday_template_set_args('reviews-block', array(
			'post_options' => $post_options,
			'post_data' => $post_data,
			'avg_author' => $avg_author,
			'avg_users' => $avg_users
		));
		require get_template_directory().'/templates/_parts/reviews-block.php';
		// Post content
		if ($post_data['post_protected']) { 
			planmyday_show_layout($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			if (!planmyday_storage_empty('reviews_markup') && planmyday_strpos($post_data['post_content'], planmyday_get_reviews_placeholder())===false) 
				$post_data['post_content'] = planmyday_sc_reviews(array()) . ($post_data['post_content']);
			planmyday_show_layout(planmyday_gap_wrapper(planmyday_reviews_wrapper($post_data['post_content'])));
			wp_link_pages( array( 
				'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'planmyday' ) . '</span>', 
				'after' => '</nav>',
				'link_before' => '<span class="pager_numbers">',
				'link_after' => '</span>'
				)
			); 
			if ( planmyday_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'planmyday'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 
	

		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		planmyday_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));

		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require get_template_directory().'/templates/_parts/editor-area.php';
		}
			
		planmyday_close_wrapper();	// .post_content
			
		if (!$post_data['post_protected']) {
			require get_template_directory().'/templates/_parts/author-info.php';
			require get_template_directory().'/templates/_parts/share.php';
		}

		$sidebar_present = !planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) planmyday_close_wrapper();	// .post_item
		require get_template_directory().'/templates/_parts/related-posts.php';
		if ($sidebar_present) planmyday_close_wrapper();		// .post_item

		// Show comments
		if ( !$post_data['post_protected'] && (comments_open() || get_comments_number() != 0) ) {
			comments_template();
		}

		// Manually pop args from storage
		// after all single footer templates
		planmyday_template_get_args('single-footer');


	}
}
?>