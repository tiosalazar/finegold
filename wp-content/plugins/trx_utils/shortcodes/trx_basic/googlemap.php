<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_googlemap_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_googlemap_theme_setup' );
	function planmyday_sc_googlemap_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_googlemap_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('planmyday_sc_googlemap')) {	
	function planmyday_sc_googlemap($atts, $content = null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = planmyday_get_custom_option('googlemap_style');
		$api_key = planmyday_get_theme_option('api_google');
		wp_enqueue_script( 'googlemap', planmyday_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		wp_enqueue_script( 'planmyday-googlemap-script', planmyday_get_file_url('js/core.googlemap.js'), array(), null, true );
		planmyday_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = planmyday_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => planmyday_get_custom_option('googlemap_title'),
				'description' => planmyday_strmacros(planmyday_get_custom_option('googlemap_description')),
				'latlng' => planmyday_get_custom_option('googlemap_latlng'),
				'address' => planmyday_get_custom_option('googlemap_address'),
				'point' => planmyday_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !planmyday_param_is_off($scheme) && !planmyday_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(planmyday_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('planmyday_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	planmyday_require_shortcode("trx_googlemap", "planmyday_sc_googlemap");
}


if (!function_exists('planmyday_sc_googlemap_marker')) {	
	function planmyday_sc_googlemap_marker($atts, $content = null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		planmyday_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : planmyday_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	planmyday_require_shortcode("trx_googlemap_marker", "planmyday_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_googlemap_reg_shortcodes');
	function planmyday_sc_googlemap_reg_shortcodes() {
	
		planmyday_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'planmyday'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'planmyday'),
					"desc" => wp_kses_data( __("Map zoom factor", 'planmyday') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'planmyday'),
					"desc" => wp_kses_data( __("Select map style", 'planmyday') ),
					"value" => "default",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('schemes')
				),
				"width" => planmyday_shortcodes_width('100%'),
				"height" => planmyday_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'planmyday'),
				"desc" => wp_kses_data( __("Google map marker", 'planmyday') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'planmyday'),
						"desc" => wp_kses_data( __("Address of this marker", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'planmyday'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'planmyday'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'planmyday') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'planmyday'),
						"desc" => wp_kses_data( __("Title for this marker", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'planmyday'),
						"desc" => wp_kses_data( __("Description for this marker", 'planmyday') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => planmyday_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_googlemap_reg_shortcodes_vc');
	function planmyday_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'planmyday'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'planmyday'),
					"description" => wp_kses_data( __("Map zoom factor", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'planmyday'),
					"description" => wp_kses_data( __("Map custom style", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'planmyday'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_vc_width('100%'),
				planmyday_vc_height(240),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'planmyday'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'planmyday') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'planmyday'),
					"description" => wp_kses_data( __("Address of this marker", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'planmyday'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Title for this marker", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'planmyday'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				planmyday_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends PLANMYDAY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends PLANMYDAY_VC_ShortCodeCollection {}
	}
}
?>