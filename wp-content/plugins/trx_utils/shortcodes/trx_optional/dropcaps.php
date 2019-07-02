<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_dropcaps_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_dropcaps_theme_setup' );
	function planmyday_sc_dropcaps_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_dropcaps_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('planmyday_sc_dropcaps')) {	
	function planmyday_sc_dropcaps($atts, $content=null){
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = planmyday_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(planmyday_substr($content, 0, 1)) . '</span>' . trim(planmyday_substr($content, 1))
			. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	planmyday_require_shortcode('trx_dropcaps', 'planmyday_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_dropcaps_reg_shortcodes');
	function planmyday_sc_dropcaps_reg_shortcodes() {
	
		planmyday_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'planmyday'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'planmyday'),
					"desc" => wp_kses_data( __("Dropcaps style", 'planmyday') ),
					"value" => "1",
					"type" => "checklist",
					"options" => planmyday_get_list_styles(1, 4)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'planmyday'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'planmyday') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => planmyday_shortcodes_width(),
				"height" => planmyday_shortcodes_height(),
				"top" => planmyday_get_sc_param('top'),
				"bottom" => planmyday_get_sc_param('bottom'),
				"left" => planmyday_get_sc_param('left'),
				"right" => planmyday_get_sc_param('right'),
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
if ( !function_exists( 'planmyday_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_dropcaps_reg_shortcodes_vc');
	function planmyday_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'planmyday'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'planmyday'),
					"description" => wp_kses_data( __("Dropcaps style", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_vc_width(),
				planmyday_vc_height(),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends PLANMYDAY_VC_ShortCodeContainer {}
	}
}
?>