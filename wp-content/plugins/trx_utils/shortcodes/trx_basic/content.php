<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_content_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_content_theme_setup' );
	function planmyday_sc_content_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_content_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('planmyday_sc_content')) {	
	function planmyday_sc_content($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !planmyday_param_is_off($scheme) && !planmyday_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	planmyday_require_shortcode('trx_content', 'planmyday_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_content_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_content_reg_shortcodes');
	function planmyday_sc_content_reg_shortcodes() {
	
		planmyday_sc_map("trx_content", array(
			"title" => esc_html__("Content block", 'planmyday'),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", 'planmyday') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('schemes')
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'planmyday'),
					"desc" => wp_kses_data( __("Content for section container", 'planmyday') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => planmyday_get_sc_param('top'),
				"bottom" => planmyday_get_sc_param('bottom'),
				"id" => planmyday_get_sc_param('id'),
				"class" => planmyday_get_sc_param('class'),
				"animation" => planmyday_get_sc_param('animation'),
				"css" => planmyday_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_content_reg_shortcodes_vc');
	function planmyday_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", 'planmyday'),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'planmyday'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends PLANMYDAY_VC_ShortCodeCollection {}
	}
}
?>