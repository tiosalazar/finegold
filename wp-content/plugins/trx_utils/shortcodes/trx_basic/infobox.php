<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_infobox_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_infobox_theme_setup' );
	function planmyday_sc_infobox_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_infobox_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('planmyday_sc_infobox')) {	
	function planmyday_sc_infobox($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		} else if ($icon=='none')
			$icon = '';

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (planmyday_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !planmyday_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	planmyday_require_shortcode('trx_infobox', 'planmyday_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_infobox_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_infobox_reg_shortcodes');
	function planmyday_sc_infobox_reg_shortcodes() {
	
		planmyday_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'planmyday'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'planmyday'),
					"desc" => wp_kses_data( __("Infobox style", 'planmyday') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'planmyday'),
						'info' => esc_html__('Info', 'planmyday'),
						'success' => esc_html__('Success', 'planmyday'),
						'error' => esc_html__('Error', 'planmyday')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'planmyday'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'planmyday') ),
					"value" => "no",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'planmyday'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'planmyday'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'planmyday') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'planmyday'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'planmyday') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'planmyday'),
					"desc" => wp_kses_data( __("Content for infobox", 'planmyday') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
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
if ( !function_exists( 'planmyday_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_infobox_reg_shortcodes_vc');
	function planmyday_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'planmyday'),
			"description" => wp_kses_data( __("Box with info or error message", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'planmyday'),
					"description" => wp_kses_data( __("Infobox style", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'planmyday') => 'regular',
							esc_html__('Info', 'planmyday') => 'info',
							esc_html__('Success', 'planmyday') => 'success',
							esc_html__('Error', 'planmyday') => 'error',
							esc_html__('Result', 'planmyday') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'planmyday'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'planmyday') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'planmyday') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'planmyday'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'planmyday'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends PLANMYDAY_VC_ShortCodeContainer {}
	}
}
?>