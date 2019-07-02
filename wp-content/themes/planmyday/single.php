<?php
/**
 * Single post
 */
get_header(); 

$single_style = planmyday_storage_get('single_style');
if (empty($single_style)) $single_style = planmyday_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	planmyday_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_main')),
			'content' => planmyday_get_template_property($single_style, 'need_content'),
			'terms_list' => planmyday_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>