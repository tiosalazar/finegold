<?php 
if (is_singular()) {
	if (planmyday_get_theme_option('use_ajax_views_counter')=='yes') {
		planmyday_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => planmyday_get_post_views(get_the_ID())
		));
	} else
		planmyday_set_post_views(get_the_ID());
}
?>