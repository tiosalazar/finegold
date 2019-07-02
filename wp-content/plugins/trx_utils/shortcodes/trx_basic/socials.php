<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_socials_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_socials_theme_setup' );
	function planmyday_sc_socials_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_socials_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('planmyday_sc_socials')) {	
	function planmyday_sc_socials($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => planmyday_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		planmyday_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? planmyday_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) planmyday_storage_set_array('sc_social_data', 'icons', $list);
		} else if (planmyday_param_is_on($custom))
			$content = do_shortcode($content);
		if (planmyday_storage_get_array('sc_social_data', 'icons')===false) planmyday_storage_set_array('sc_social_data', 'icons', planmyday_get_custom_option('social_icons'));
		$output = planmyday_prepare_socials(planmyday_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	planmyday_require_shortcode('trx_socials', 'planmyday_sc_socials');
}


if (!function_exists('planmyday_sc_social_item')) {	
	function planmyday_sc_social_item($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (empty($icon)) {
			if (!empty($name)) {
				$type = planmyday_storage_get_array('sc_social_data', 'type');
				if ($type=='images') {
					if (file_exists(planmyday_get_socials_dir($name.'.png')))
						$icon = planmyday_get_socials_url($name.'.png');
				} else
					$icon = 'icon-'.esc_attr($name);
			}
		} else if ((int) $icon > 0) {
			$attach = wp_get_attachment_image_src( $icon, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$icon = $attach[0];
		}
		if (!empty($icon) && !empty($url)) {
			if (planmyday_storage_get_array('sc_social_data', 'icons')===false) planmyday_storage_set_array('sc_social_data', 'icons', array());
			planmyday_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	planmyday_require_shortcode('trx_social_item', 'planmyday_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_socials_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_socials_reg_shortcodes');
	function planmyday_sc_socials_reg_shortcodes() {
	
		planmyday_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'planmyday'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'planmyday') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'planmyday'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'planmyday') ),
					"value" => planmyday_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'planmyday'),
						'images' => esc_html__('Images', 'planmyday')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'planmyday'),
					"desc" => wp_kses_data( __("Size of the icons", 'planmyday') ),
					"value" => "small",
					"options" => planmyday_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'planmyday'),
					"desc" => wp_kses_data( __("Shape of the icons", 'planmyday') ),
					"value" => "square",
					"options" => planmyday_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'planmyday'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'planmyday'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'planmyday') ),
					"divider" => true,
					"value" => "no",
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
			),
			"children" => array(
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'planmyday'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'planmyday') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'planmyday'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'planmyday'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'planmyday'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'planmyday') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_socials_reg_shortcodes_vc');
	function planmyday_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'planmyday'),
			"description" => wp_kses_data( __("Custom social icons", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'planmyday'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'planmyday') ),
					"class" => "",
					"std" => planmyday_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'planmyday') => 'icons',
						esc_html__('Images', 'planmyday') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'planmyday'),
					"description" => wp_kses_data( __("Size of the icons", 'planmyday') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(planmyday_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'planmyday'),
					"description" => wp_kses_data( __("Shape of the icons", 'planmyday') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(planmyday_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'planmyday'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'planmyday'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'planmyday') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'planmyday') => 'yes'),
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'planmyday'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'planmyday') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'planmyday'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'planmyday'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'planmyday'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends PLANMYDAY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>