<?php
/**
 * Planmyday Framework: Theme options custom fields
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_options_custom_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_options_custom_theme_setup' );
	function planmyday_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'planmyday_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'planmyday_options_custom_load_scripts' ) ) {
	//Handler of add_action("admin_enqueue_scripts", 'planmyday_options_custom_load_scripts');
	function planmyday_options_custom_load_scripts() {
		wp_enqueue_script( 'planmyday-options-custom-script',	planmyday_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'planmyday_show_custom_field' ) ) {
	function planmyday_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(planmyday_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager planmyday_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'planmyday') : esc_html__( 'Choose Image', 'planmyday')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'planmyday') : esc_html__( 'Choose Image', 'planmyday')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'planmyday') : esc_html__( 'Choose Image', 'planmyday')) . '</a>';
				break;
		}
		return apply_filters('planmyday_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>