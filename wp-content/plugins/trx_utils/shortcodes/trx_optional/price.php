<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_price_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_price_theme_setup' );
	function planmyday_sc_price_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_price_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_price_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('planmyday_sc_price')) {	
	function planmyday_sc_price($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
			$m = explode('.', str_replace(',', '.', $money));
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($m[0]).'</span>'
				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
				. (!empty($m[1]) ? '<span class="sc_price_penny">'.($m[1]).'</span>' : '')
				. (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
				. (!empty($m[1]) ? '</span>' : '')
				. '</div>';
		}
		return apply_filters('planmyday_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	planmyday_require_shortcode('trx_price', 'planmyday_sc_price');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_price_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_price_reg_shortcodes');
	function planmyday_sc_price_reg_shortcodes() {
	
		planmyday_sc_map("trx_price", array(
			"title" => esc_html__("Price", 'planmyday'),
			"desc" => wp_kses_data( __("Insert price with decoration", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"money" => array(
					"title" => esc_html__("Money", 'planmyday'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'planmyday'),
					"desc" => wp_kses_data( __("Currency character", 'planmyday') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'planmyday'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'planmyday'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('float')
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
if ( !function_exists( 'planmyday_sc_price_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_price_reg_shortcodes_vc');
	function planmyday_sc_price_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price",
			"name" => esc_html__("Price", 'planmyday'),
			"description" => wp_kses_data( __("Insert price with decoration", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_price',
			"class" => "trx_sc_single trx_sc_price",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'planmyday'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'planmyday'),
					"description" => wp_kses_data( __("Currency character", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'planmyday'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'planmyday'),
					"description" => wp_kses_data( __("Align price to left or right side", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('float')),
					"type" => "dropdown"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('css'),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Price extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>