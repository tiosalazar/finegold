<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_highlight_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_highlight_theme_setup' );
	function planmyday_sc_highlight_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_highlight_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('planmyday_sc_highlight')) {	
	function planmyday_sc_highlight($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(planmyday_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	planmyday_require_shortcode('trx_highlight', 'planmyday_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_highlight_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_highlight_reg_shortcodes');
	function planmyday_sc_highlight_reg_shortcodes() {
	
		planmyday_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'planmyday'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'planmyday'),
					"desc" => wp_kses_data( __("Highlight type", 'planmyday') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'planmyday'),
						1 => esc_html__('Type 1', 'planmyday'),
						2 => esc_html__('Type 2', 'planmyday'),
						3 => esc_html__('Type 3', 'planmyday')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'planmyday'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'planmyday'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'planmyday') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'planmyday'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'planmyday'),
					"desc" => wp_kses_data( __("Content for highlight", 'planmyday') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => planmyday_get_sc_param('id'),
				"class" => planmyday_get_sc_param('class'),
				"css" => planmyday_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_highlight_reg_shortcodes_vc');
	function planmyday_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'planmyday'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'planmyday'),
					"description" => wp_kses_data( __("Highlight type", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'planmyday') => 0,
							esc_html__('Type 1', 'planmyday') => 1,
							esc_html__('Type 2', 'planmyday') => 2,
							esc_html__('Type 3', 'planmyday') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'planmyday'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'planmyday'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'planmyday'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'planmyday'),
					"description" => wp_kses_data( __("Content for highlight", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>