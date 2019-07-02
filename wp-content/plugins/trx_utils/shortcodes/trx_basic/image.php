<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_image_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_image_theme_setup' );
	function planmyday_sc_image_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_image_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('planmyday_sc_image')) {	
	function planmyday_sc_image($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = planmyday_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) planmyday_enqueue_popup();
        $alt = basename($src);
        $alt = substr($alt,0,strlen($alt) - 4);
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="'.esc_html($alt).'" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('planmyday_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	planmyday_require_shortcode('trx_image', 'planmyday_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_image_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_image_reg_shortcodes');
	function planmyday_sc_image_reg_shortcodes() {
	
		planmyday_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'planmyday'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'planmyday'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'planmyday') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'planmyday'),
					"desc" => wp_kses_data( __("Image title (if need)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'planmyday'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'planmyday') ),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'planmyday'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'planmyday'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'planmyday') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'planmyday'),
						"round" => esc_html__('Round', 'planmyday')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'planmyday'),
					"desc" => wp_kses_data( __("The link URL from the image", 'planmyday') ),
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
if ( !function_exists( 'planmyday_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_image_reg_shortcodes_vc');
	function planmyday_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'planmyday'),
			"description" => wp_kses_data( __("Insert image", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'planmyday'),
					"description" => wp_kses_data( __("Select image from library", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'planmyday'),
					"description" => wp_kses_data( __("Align image to left or right side", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'planmyday'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'planmyday') => 'square',
						esc_html__('Round', 'planmyday') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Image's title", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'planmyday'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'planmyday'),
					"description" => wp_kses_data( __("The link URL from the image", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Image extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>