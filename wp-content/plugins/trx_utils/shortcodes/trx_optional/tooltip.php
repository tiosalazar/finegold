<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_tooltip_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_tooltip_theme_setup' );
	function planmyday_sc_tooltip_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('planmyday_sc_tooltip')) {	
	function planmyday_sc_tooltip($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	planmyday_require_shortcode('trx_tooltip', 'planmyday_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_tooltip_reg_shortcodes');
	function planmyday_sc_tooltip_reg_shortcodes() {
	
		planmyday_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'planmyday'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'planmyday'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'planmyday'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'planmyday') ),
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
?>