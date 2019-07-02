<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_icon_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_icon_theme_setup' );
	function planmyday_sc_icon_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_icon_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('planmyday_sc_icon')) {	
	function planmyday_sc_icon($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !planmyday_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(planmyday_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || planmyday_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !planmyday_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	planmyday_require_shortcode('trx_icon', 'planmyday_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_icon_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_icon_reg_shortcodes');
	function planmyday_sc_icon_reg_shortcodes() {
	
		planmyday_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'planmyday'),
			"desc" => wp_kses_data( __("Insert icon", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'planmyday'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'planmyday'),
					"desc" => wp_kses_data( __("Icon's color", 'planmyday') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'planmyday'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'planmyday') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'planmyday'),
						'round' => esc_html__('Round', 'planmyday'),
						'square' => esc_html__('Square', 'planmyday')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'planmyday'),
					"desc" => wp_kses_data( __("Icon's background color", 'planmyday') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'planmyday'),
					"desc" => wp_kses_data( __("Icon's font size", 'planmyday') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'planmyday'),
					"desc" => wp_kses_data( __("Icon font weight", 'planmyday') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'planmyday'),
						'300' => esc_html__('Light (300)', 'planmyday'),
						'400' => esc_html__('Normal (400)', 'planmyday'),
						'700' => esc_html__('Bold (700)', 'planmyday')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'planmyday'),
					"desc" => wp_kses_data( __("Icon text alignment", 'planmyday') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'planmyday'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"top" => planmyday_get_sc_param('top'),
				"bottom" => planmyday_get_sc_param('bottom'),
				"left" => planmyday_get_sc_param('left'),
				"right" => planmyday_get_sc_param('right'),
				"id" => planmyday_get_sc_param('id'),
				"class" => planmyday_get_sc_param('class'),
				"css" => planmyday_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_icon_reg_shortcodes_vc');
	function planmyday_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'planmyday'),
			"description" => wp_kses_data( __("Insert the icon", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'planmyday'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'planmyday'),
					"description" => wp_kses_data( __("Icon's color", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'planmyday'),
					"description" => wp_kses_data( __("Background color for the icon", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'planmyday'),
					"description" => wp_kses_data( __("Shape of the icon background", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'planmyday') => 'none',
						esc_html__('Round', 'planmyday') => 'round',
						esc_html__('Square', 'planmyday') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'planmyday'),
					"description" => wp_kses_data( __("Icon's font size", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'planmyday'),
					"description" => wp_kses_data( __("Icon's font weight", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'planmyday') => 'inherit',
						esc_html__('Thin (100)', 'planmyday') => '100',
						esc_html__('Light (300)', 'planmyday') => '300',
						esc_html__('Normal (400)', 'planmyday') => '400',
						esc_html__('Bold (700)', 'planmyday') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'planmyday'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'planmyday'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('css'),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>