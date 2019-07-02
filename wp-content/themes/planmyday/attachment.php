<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move planmyday_set_post_views to the javascript - counter will work under cache system
	if (planmyday_get_custom_option('use_ajax_views_counter')=='no') {
		planmyday_set_post_views(get_the_ID());
	}

	planmyday_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>