<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_quote_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_quote_theme_setup' );
	function planmyday_sc_quote_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_quote_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('planmyday_sc_quote')) {	
	function planmyday_sc_quote($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"cite" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width);
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (planmyday_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'. (!empty($class) ? ' '.esc_attr($class) : '').'"' 
			. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	planmyday_require_shortcode('trx_quote', 'planmyday_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_quote_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_quote_reg_shortcodes');
	function planmyday_sc_quote_reg_shortcodes() {
	
		planmyday_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'planmyday'),
			"desc" => wp_kses_data( __("Quote text", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'planmyday'),
					"desc" => wp_kses_data( __("URL for quote cite", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", 'planmyday'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'planmyday'),
					"desc" => wp_kses_data( __("Quote content", 'planmyday') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"width" => planmyday_shortcodes_width(),
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
if ( !function_exists( 'planmyday_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_quote_reg_shortcodes_vc');
	function planmyday_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'planmyday'),
			"description" => wp_kses_data( __("Quote text", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'planmyday'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'planmyday'),
					"description" => wp_kses_data( __("Quote title (author name)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'planmyday'),
					"description" => wp_kses_data( __("Quote content", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_vc_width(),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>