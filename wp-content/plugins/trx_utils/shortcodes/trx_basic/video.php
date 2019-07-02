<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_video_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_video_theme_setup' );
	function planmyday_sc_video_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_video_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_video_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]

if (!function_exists('planmyday_sc_video')) {	
	function planmyday_sc_video($atts, $content = null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"url" => '',
			"src" => '',
			"image" => '',
			"ratio" => '16:9',
			"autoplay" => 'off',
			"align" => '',
			"bg_image" => '',
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($autoplay)) $autoplay = 'off';
		
		$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
		$ratio_parts = explode(':', $ratio);
		if (empty($height) && empty($width)) {
			$width='100%';
			if (planmyday_param_is_off(planmyday_get_custom_option('substitute_video'))) $height="400";
		}
		$ed = planmyday_substr($width, -1);
		if (empty($height) && !empty($width) && $ed!='%') {
			$height = round($width / $ratio_parts[0] * $ratio_parts[1]);
		}
		if (!empty($height) && empty($width)) {
			$width = round($height * $ratio_parts[0] / $ratio_parts[1]);
		}
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = planmyday_get_css_dimensions_from_values($width, $height);
		$css_bg = planmyday_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
	
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		$url = $src!='' ? $src : $url;
		if ($image!='' && planmyday_param_is_off($image))
			$image = '';
		else {
			if (planmyday_param_is_on($autoplay) && is_singular() && !planmyday_storage_get('blog_streampage'))
				$image = '';
			else {
				if ($image > 0) {
					$attach = wp_get_attachment_image_src( $image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$image = $attach[0];
				}
				if ($bg_image) {
					$thumb_sizes = planmyday_get_thumb_sizes(array(
						'layout' => 'grid_3'
					));
					if (!is_single() || !empty($image)) $image = planmyday_get_resized_image_url(empty($image) ? get_the_ID() : $image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, false);
				} else
					if (!is_single() || !empty($image)) $image = planmyday_get_resized_image_url(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
				if (empty($image) && (!is_singular() || planmyday_storage_get('blog_streampage')))	// || planmyday_param_is_off($autoplay)))
					$image = planmyday_get_video_cover_image($url);
			}
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
	
		$url = planmyday_get_video_player_url($src!='' ? $src : $url);
		
		$video = '<video' . ($id ? ' id="' . esc_attr($id) . '"' : '') 
			. ' class="sc_video"'
			. ' src="' . esc_url($url) . '"'
			. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
			. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
			. ' data-ratio="'.esc_attr($ratio).'"'
			. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
			. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
			. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
			. ($class ? ' data-class="'.esc_attr($class).'"' : '')
			. ($bg_image ? ' data-bg-image="'.esc_attr($bg_image).'"' : '') 
			. ($css_bg!='' ? ' data-style="'.esc_attr($css_bg).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (($image && planmyday_param_is_on(planmyday_get_custom_option('substitute_video'))) || (planmyday_param_is_on($autoplay) && is_singular() && !planmyday_storage_get('blog_streampage')) ? ' autoplay="autoplay"' : '') 
			. ' controls="controls" loop="loop"'
			. '>'
			. '</video>';
		if (planmyday_param_is_off(planmyday_get_custom_option('substitute_video'))) {
			if (planmyday_param_is_on($frame)) $video = planmyday_get_video_frame($video, $image, $css, $css_bg);
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$video = planmyday_substitute_video($video, $width, $height, false);
			}
		}
		if (planmyday_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('planmyday_shortcode_output', $video, 'trx_video', $atts, $content);
	}
	planmyday_require_shortcode("trx_video", "planmyday_sc_video");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_video_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_video_reg_shortcodes');
	function planmyday_sc_video_reg_shortcodes() {
	
		planmyday_sc_map("trx_video", array(
			"title" => esc_html__("Video", 'planmyday'),
			"desc" => wp_kses_data( __("Insert video player", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for video file", 'planmyday'),
					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site", 'planmyday') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'planmyday'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'planmyday'),
							'update' => esc_html__('Select video file', 'planmyday')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"ratio" => array(
					"title" => esc_html__("Ratio", 'planmyday'),
					"desc" => wp_kses_data( __("Ratio of the video", 'planmyday') ),
					"value" => "16:9",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"16:9" => esc_html__("16:9", 'planmyday'),
						"4:3" => esc_html__("4:3", 'planmyday')
					)
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay video", 'planmyday'),
					"desc" => wp_kses_data( __("Autoplay video on page load", 'planmyday') ),
					"value" => "off",
					"type" => "switch",
					"options" => planmyday_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'planmyday'),
					"desc" => wp_kses_data( __("Select block alignment", 'planmyday') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('align')
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'planmyday'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for video preview", 'planmyday') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", 'planmyday'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'planmyday') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", 'planmyday'),
					"desc" => wp_kses_data( __("Top offset (padding) inside background image to video block (in percent). For example: 3%", 'planmyday') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", 'planmyday'),
					"desc" => wp_kses_data( __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", 'planmyday') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", 'planmyday'),
					"desc" => wp_kses_data( __("Left offset (padding) inside background image to video block (in percent). For example: 20%", 'planmyday') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", 'planmyday'),
					"desc" => wp_kses_data( __("Right offset (padding) inside background image to video block (in percent). For example: 12%", 'planmyday') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
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
if ( !function_exists( 'planmyday_sc_video_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_video_reg_shortcodes_vc');
	function planmyday_sc_video_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_video",
			"name" => esc_html__("Video", 'planmyday'),
			"description" => wp_kses_data( __("Insert video player", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_video',
			"class" => "trx_sc_single trx_sc_video",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for video file", 'planmyday'),
					"description" => wp_kses_data( __("Paste URL for video file", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "ratio",
					"heading" => esc_html__("Ratio", 'planmyday'),
					"description" => wp_kses_data( __("Select ratio for display video", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('16:9', 'planmyday') => "16:9",
						esc_html__('4:3', 'planmyday') => "4:3"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay video", 'planmyday'),
					"description" => wp_kses_data( __("Autoplay video on page load", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'planmyday'),
					"description" => wp_kses_data( __("Select block alignment", 'planmyday') ),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'planmyday'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for video preview", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'planmyday'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'planmyday') ),
					"group" => esc_html__('Background', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", 'planmyday'),
					"description" => wp_kses_data( __("Top offset (padding) from background image to video block (in percent). For example: 3%", 'planmyday') ),
					"group" => esc_html__('Background', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", 'planmyday'),
					"description" => wp_kses_data( __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", 'planmyday') ),
					"group" => esc_html__('Background', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", 'planmyday'),
					"description" => wp_kses_data( __("Left offset (padding) from background image to video block (in percent). For example: 20%", 'planmyday') ),
					"group" => esc_html__('Background', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", 'planmyday'),
					"description" => wp_kses_data( __("Right offset (padding) from background image to video block (in percent). For example: 12%", 'planmyday') ),
					"group" => esc_html__('Background', 'planmyday'),
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
		
		class WPBakeryShortCode_Trx_Video extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>