<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_price_block_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_price_block_theme_setup' );
	function planmyday_sc_price_block_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_price_block_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('planmyday_sc_price_block')) {	
	function planmyday_sc_price_block($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(planmyday_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !planmyday_param_is_off($scheme) && !planmyday_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button type="round" style="border" size="large" icon="icon-right-small" link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	planmyday_require_shortcode('trx_price_block', 'planmyday_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_price_block_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_price_block_reg_shortcodes');
	function planmyday_sc_price_block_reg_shortcodes() {
	
		planmyday_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'planmyday'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'planmyday'),
					"desc" => wp_kses_data( __("Select style for this price block", 'planmyday') ),
					"value" => 1,
					"options" => planmyday_get_list_styles(1, 1),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'planmyday'),
					"desc" => wp_kses_data( __("Block title", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'planmyday'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'planmyday'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'planmyday'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'planmyday'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'planmyday') ),
					"divider" => true,
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
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'planmyday'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'planmyday'),
					"desc" => wp_kses_data( __("Description for this price block", 'planmyday') ),
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
if ( !function_exists( 'planmyday_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_price_block_reg_shortcodes_vc');
	function planmyday_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'planmyday'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'planmyday'),
					"desc" => wp_kses_data( __("Select style of this price block", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(planmyday_get_list_styles(1, 1)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Block title", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'planmyday'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'planmyday'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'planmyday'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'planmyday'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'planmyday') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'planmyday'),
					"description" => wp_kses_data( __("Currency character", 'planmyday') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'planmyday'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'planmyday'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'planmyday') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'planmyday'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('schemes')),
					"type" => "dropdown"
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
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'planmyday'),
					"description" => wp_kses_data( __("Description for this price block", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>