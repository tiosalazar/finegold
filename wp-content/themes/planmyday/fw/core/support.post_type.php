<?php
/**
 * Planmyday Framework: Supported post types settings
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Theme init
if (!function_exists('planmyday_post_type_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_post_type_theme_setup', 9 );
	function planmyday_post_type_theme_setup() {
		if ( !planmyday_options_is_used() ) return;
		$post_type = planmyday_admin_get_current_post_type();
		if (empty($post_type)) $post_type = 'post';
		$override_key = planmyday_get_override_key($post_type, 'post_type');
		if ($override_key) {
			// Set post type action
			add_action('save_post',				'planmyday_post_type_save_options');
			add_action('trx_utils_filter_override_options',		'planmyday_post_type_add_override_options');
			add_action('admin_enqueue_scripts', 'planmyday_post_type_admin_scripts');
			// Create override options
			planmyday_storage_set('post_override_options', array(
				'id' => 'post-override-options',
				'title' => esc_html__('Post Options', 'planmyday'),
				'page' => $post_type,
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array()
				)
			);
		}
	}
}


// Admin scripts
if (!function_exists('planmyday_post_type_admin_scripts')) {
	//Handler of add_action('admin_enqueue_scripts', 'planmyday_post_type_admin_scripts');
	function planmyday_post_type_admin_scripts() {
	}
}



// Add override options
if (!function_exists('planmyday_post_type_add_override_options')) {
    //add_filter('trx_utils_filter_override_options', 'planmyday_post_type_add_override_options');
    function planmyday_post_type_add_override_options($boxes = array()) {
        $boxes[] = array_merge(planmyday_storage_get('post_override_options'), array('callback' => 'planmyday_post_type_show_override_options'));
        return $boxes;
    }
}


// Callback function to show fields in override options
if (!function_exists('planmyday_post_type_show_override_options')) {
	function planmyday_post_type_show_override_options() {
		global $post;
		
		$post_type = planmyday_admin_get_current_post_type();
		$override_key = planmyday_get_override_key($post_type, 'post_type');
		
		// Use nonce for verification
		echo '<input type="hidden" name="override_options_post_nonce" value="' .esc_attr(wp_create_nonce(admin_url())).'" />';
		echo '<input type="hidden" name="override_options_post_type" value="'.esc_attr($post_type).'" />';
	
		$custom_options = apply_filters('planmyday_filter_post_load_custom_options', get_post_meta($post->ID, planmyday_storage_get('options_prefix') . '_post_options', true), $post_type, $post->ID);

		$mb = planmyday_storage_get('post_override_options');
		$post_options = planmyday_array_merge(planmyday_storage_get('options'), $mb['fields']);

		do_action('planmyday_action_post_before_show_override_options', $post_type, $post->ID);
	
		planmyday_options_page_start(array(
			'data' => $post_options,
			'add_inherit' => true,
			'create_form' => false,
			'buttons' => array('import', 'export'),
			'override' => $override_key
		));

		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$option) { 
				if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;

				$option = apply_filters('planmyday_filter_post_show_custom_field_option', $option, $id, $post_type, $post->ID);
				$meta = isset($custom_options[$id]) 
								? apply_filters('planmyday_filter_post_show_custom_field_value', $custom_options[$id], $option, $id, $post_type, $post->ID) 
								: (isset($option['inherit']) && !$option['inherit'] ? $option['std'] : '');

				do_action('planmyday_action_post_before_show_custom_field', $post_type, $post->ID, $option, $id, $meta);

				planmyday_options_show_field($id, $option, $meta);

				do_action('planmyday_action_post_after_show_custom_field', $post_type, $post->ID, $option, $id, $meta);
			}
		}
	
		planmyday_options_page_stop();
		
		do_action('planmyday_action_post_after_show_override_options', $post_type, $post->ID);
		
	}
}


// Save data from override options
if (!function_exists('planmyday_post_type_save_options')) {
	//Handler of add_action('save_post', 'planmyday_post_type_save_options');
	function planmyday_post_type_save_options($post_id) {

		// verify nonce
		if ( !wp_verify_nonce( planmyday_get_value_gp('override_options_post_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		$post_type = isset($_POST['override_options_post_type']) ? $_POST['override_options_post_type'] : $_POST['post_type'];
		$override_key = planmyday_get_override_key($post_type, 'post_type');

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		$custom_options = array();

		$post_options = array_merge(planmyday_storage_get('options'), planmyday_storage_get_array('post_override_options', 'fields'));

		if (planmyday_options_merge_new_values($post_options, $custom_options, $_POST, 'save', $override_key)) {
			update_post_meta($post_id, planmyday_storage_get('options_prefix') . '_post_options', apply_filters('planmyday_filter_post_save_custom_options', $custom_options, $post_type, $post_id));
		}

		// Init post counters
		global $post;
		if ( !empty($post->ID) && $post_id==$post->ID ) {
			planmyday_get_post_views($post_id);
			planmyday_get_post_likes($post_id);
		}
	}
}
?>