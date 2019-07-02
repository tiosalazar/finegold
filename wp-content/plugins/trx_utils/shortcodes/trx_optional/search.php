<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_search_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_search_theme_setup' );
	function planmyday_sc_search_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_search_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('planmyday_sc_search')) {	
	function planmyday_sc_search($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'planmyday'),
			"scheme" => "original",
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
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = planmyday_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = planmyday_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = planmyday_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		planmyday_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (planmyday_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'planmyday') : esc_attr__('Start search', 'planmyday')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (planmyday_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !planmyday_param_is_off($scheme) && !planmyday_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	planmyday_require_shortcode('trx_search', 'planmyday_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_search_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_search_reg_shortcodes');
	function planmyday_sc_search_reg_shortcodes() {
	
		planmyday_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'planmyday'),
			"desc" => wp_kses_data( __("Show search form", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'planmyday'),
					"desc" => wp_kses_data( __("Select style to display search field", 'planmyday') ),
					"value" => "regular",
					"options" => planmyday_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'planmyday'),
					"desc" => wp_kses_data( __("Select search field initial state", 'planmyday') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'planmyday'),
						"opened" => esc_html__('Opened', 'planmyday'),
						"closed" => esc_html__('Closed', 'planmyday')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'planmyday'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'planmyday') ),
					"value" => esc_html__("Search &hellip;", 'planmyday'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'planmyday'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'planmyday') ),
					"value" => "yes",
					"options" => planmyday_get_sc_param('yes_no'),
					"type" => "switch"
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
if ( !function_exists( 'planmyday_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_search_reg_shortcodes_vc');
	function planmyday_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'planmyday'),
			"description" => wp_kses_data( __("Insert search form", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'planmyday'),
					"description" => wp_kses_data( __("Select style to display search field", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'planmyday'),
					"description" => wp_kses_data( __("Select search field initial state", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'planmyday')  => "fixed",
						esc_html__('Opened', 'planmyday') => "opened",
						esc_html__('Closed', 'planmyday') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'planmyday'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'planmyday'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'planmyday') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'planmyday') => 'yes'),
					"type" => "checkbox"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>