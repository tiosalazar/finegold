<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_accordion_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_accordion_theme_setup' );
	function planmyday_sc_accordion_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_accordion_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_accordion_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_accordion counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('planmyday_sc_accordion')) {	
	function planmyday_sc_accordion($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$initial = max(0, (int) $initial);
		planmyday_storage_set('sc_accordion_data', array(
			'counter' => 0,
            'show_counter' => planmyday_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || planmyday_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || planmyday_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (planmyday_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	planmyday_require_shortcode('trx_accordion', 'planmyday_sc_accordion');
}


if (!function_exists('planmyday_sc_accordion_item')) {	
	function planmyday_sc_accordion_item($atts, $content=null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		planmyday_storage_inc_array('sc_accordion_data', 'counter');
		if (empty($icon_closed) || planmyday_param_is_inherit($icon_closed)) $icon_closed = planmyday_storage_get_array('sc_accordion_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || planmyday_param_is_inherit($icon_opened)) $icon_opened = planmyday_storage_get_array('sc_accordion_data', 'icon_opened', '', "icon-minus");
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. (planmyday_storage_get_array('sc_accordion_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
				. (planmyday_storage_get_array('sc_accordion_data', 'counter') == 1 ? ' first' : '') 
				. '">'
				. '<h5 class="sc_accordion_title">'
				. (!planmyday_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!planmyday_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. (planmyday_storage_get_array('sc_accordion_data', 'show_counter') ? '<span class="sc_items_counter">'.(planmyday_storage_get_array('sc_accordion_data', 'counter')).'</span>' : '')
				. ($title)
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	planmyday_require_shortcode('trx_accordion_item', 'planmyday_sc_accordion_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_accordion_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_accordion_reg_shortcodes');
	function planmyday_sc_accordion_reg_shortcodes() {
	
		planmyday_sc_map("trx_accordion", array(
			"title" => esc_html__("Accordion", 'planmyday'),
			"desc" => wp_kses_data( __("Accordion items", 'planmyday') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'planmyday'),
					"desc" => wp_kses_data( __("Display counter before each accordion title", 'planmyday') ),
					"value" => "off",
					"type" => "switch",
					"options" => planmyday_get_sc_param('on_off')
				),
				"initial" => array(
					"title" => esc_html__("Initially opened item", 'planmyday'),
					"desc" => wp_kses_data( __("Number of initially opened item", 'planmyday') ),
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'planmyday'),
					"desc" => wp_kses_data( __('Select icon for the closed accordion item from Fontello icons set',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'planmyday'),
					"desc" => wp_kses_data( __('Select icon for the opened accordion item from Fontello icons set',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"top" => planmyday_get_sc_param('top'),
				"bottom" => planmyday_get_sc_param('bottom'),
				"left" => planmyday_get_sc_param('left'),
				"right" => planmyday_get_sc_param('right'),
				"id" => planmyday_get_sc_param('id'),
				"class" => planmyday_get_sc_param('class'),
				"animation" => planmyday_get_sc_param('animation'),
				"css" => planmyday_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_accordion_item",
				"title" => esc_html__("Item", 'planmyday'),
				"desc" => wp_kses_data( __("Accordion item", 'planmyday') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Accordion item title", 'planmyday'),
						"desc" => wp_kses_data( __("Title for current accordion item", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'planmyday'),
						"desc" => wp_kses_data( __('Select icon for the closed accordion item from Fontello icons set',  'planmyday') ),
						"value" => "",
						"type" => "icons",
						"options" => planmyday_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'planmyday'),
						"desc" => wp_kses_data( __('Select icon for the opened accordion item from Fontello icons set',  'planmyday') ),
						"value" => "",
						"type" => "icons",
						"options" => planmyday_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Accordion item content", 'planmyday'),
						"desc" => wp_kses_data( __("Current accordion item content", 'planmyday') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => planmyday_get_sc_param('id'),
					"class" => planmyday_get_sc_param('class'),
					"css" => planmyday_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_accordion_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_accordion_reg_shortcodes_vc');
	function planmyday_sc_accordion_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_accordion",
			"name" => esc_html__("Accordion", 'planmyday'),
			"description" => wp_kses_data( __("Accordion items", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_accordion',
			"class" => "trx_sc_collection trx_sc_accordion",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'planmyday'),
					"description" => wp_kses_data( __("Display counter before each accordion title", 'planmyday') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened item", 'planmyday'),
					"description" => wp_kses_data( __("Number of initially opened item", 'planmyday') ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the closed accordion item from Fontello icons set", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the opened accordion item from Fontello icons set", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
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
			'default_content' => '
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'planmyday' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'planmyday' ) . '"][/trx_accordion_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'planmyday').'">'.esc_html__("Add item", 'planmyday').'</button>
				</div>
			',
			'js_view' => 'VcTrxAccordionView'
		) );
		
		
		vc_map( array(
			"base" => "trx_accordion_item",
			"name" => esc_html__("Accordion item", 'planmyday'),
			"description" => wp_kses_data( __("Inner accordion item", 'planmyday') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_accordion_item',
			"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_accordion'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Title for current accordion item", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the closed accordion item from Fontello icons set", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the opened accordion item from Fontello icons set", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('css')
			),
		  'js_view' => 'VcTrxAccordionTabView'
		) );

		class WPBakeryShortCode_Trx_Accordion extends PLANMYDAY_VC_ShortCodeAccordion {}
		class WPBakeryShortCode_Trx_Accordion_Item extends PLANMYDAY_VC_ShortCodeAccordionItem {}
	}
}
?>