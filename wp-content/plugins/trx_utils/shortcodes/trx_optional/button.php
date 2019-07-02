<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_button_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_button_theme_setup' );
	function planmyday_sc_button_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_button_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('planmyday_sc_button')) {	
	function planmyday_sc_button($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
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
		$css .= planmyday_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (planmyday_param_is_on($popup)) planmyday_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (planmyday_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	planmyday_require_shortcode('trx_button', 'planmyday_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_button_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_button_reg_shortcodes');
	function planmyday_sc_button_reg_shortcodes() {
	
		planmyday_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'planmyday'),
			"desc" => wp_kses_data( __("Button with link", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'planmyday'),
					"desc" => wp_kses_data( __("Button caption", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'planmyday'),
					"desc" => wp_kses_data( __("Select button's shape", 'planmyday') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'planmyday'),
						'round' => esc_html__('Round', 'planmyday')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'planmyday'),
					"desc" => wp_kses_data( __("Select button's style", 'planmyday') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'planmyday'),
						'border' => esc_html__('Border', 'planmyday'),
						'border_black' => esc_html__('Border Black', 'planmyday'),
						'border_only_icon' => esc_html__('Border Only With Icon', 'planmyday'),
						'simple' => esc_html__('Simple', 'planmyday')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'planmyday'),
					"desc" => wp_kses_data( __("Select button's size", 'planmyday') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'planmyday'),
						'medium' => esc_html__('Medium', 'planmyday'),
						'large' => esc_html__('Large', 'planmyday')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'planmyday'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'planmyday'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'planmyday') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'planmyday'),
					"desc" => wp_kses_data( __("Any color for button's background", 'planmyday') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'planmyday'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'planmyday') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'planmyday'),
					"desc" => wp_kses_data( __("URL for link on button click", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'planmyday'),
					"desc" => wp_kses_data( __("Target for link on button click", 'planmyday') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'planmyday'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'planmyday') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'planmyday'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'planmyday') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
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
if ( !function_exists( 'planmyday_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_button_reg_shortcodes_vc');
	function planmyday_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'planmyday'),
			"description" => wp_kses_data( __("Button with link", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'planmyday'),
					"description" => wp_kses_data( __("Button caption", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'planmyday'),
					"description" => wp_kses_data( __("Select button's shape", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'planmyday') => 'square',
						esc_html__('Round', 'planmyday') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'planmyday'),
					"description" => wp_kses_data( __("Select button's style", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'planmyday') => 'filled',
						esc_html__('Border', 'planmyday') => 'border',
						esc_html__('Border Black', 'planmyday') => 'border_black',
						esc_html__('Border Only With Icon', 'planmyday') => 'border_only_icon',
						esc_html__('Simple', 'planmyday') => 'simple'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'planmyday'),
					"description" => wp_kses_data( __("Select button's size", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'planmyday') => 'small',
						esc_html__('Medium', 'planmyday') => 'medium',
						esc_html__('Large', 'planmyday') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'planmyday'),
					"description" => wp_kses_data( __("Any color for button's caption", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'planmyday'),
					"description" => wp_kses_data( __("Any color for button's background", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'planmyday'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'planmyday') ),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'planmyday'),
					"description" => wp_kses_data( __("URL for the link on button click", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Link', 'planmyday'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'planmyday'),
					"description" => wp_kses_data( __("Target for the link on button click", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Link', 'planmyday'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'planmyday'),
					"description" => wp_kses_data( __("Open link target in popup window", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Link', 'planmyday'),
					"value" => array(esc_html__('Open in popup', 'planmyday') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'planmyday'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Link', 'planmyday'),
					"value" => "",
					"type" => "textfield"
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
		
		class WPBakeryShortCode_Trx_Button extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>