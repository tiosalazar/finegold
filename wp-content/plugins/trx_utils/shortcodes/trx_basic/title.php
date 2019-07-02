<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_title_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_title_theme_setup' );
	function planmyday_sc_title_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_title_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('planmyday_sc_title')) {	
	function planmyday_sc_title($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !planmyday_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !planmyday_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}

        $alt = basename($picture);
        $alt = substr($alt,0,strlen($alt) - 4);

        $pic = $style!='iconed'
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="'.esc_html($alt).'" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(planmyday_strpos($image, 'http')===0 ? $image : planmyday_get_file_url('images/icons/'.($image).'.png')).'" alt="'.esc_html($image).'" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !planmyday_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	planmyday_require_shortcode('trx_title', 'planmyday_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_title_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_title_reg_shortcodes');
	function planmyday_sc_title_reg_shortcodes() {
	
		planmyday_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'planmyday'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'planmyday') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'planmyday'),
					"desc" => wp_kses_data( __("Title content", 'planmyday') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'planmyday'),
					"desc" => wp_kses_data( __("Title type (header level)", 'planmyday') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'planmyday'),
						'2' => esc_html__('Header 2', 'planmyday'),
						'3' => esc_html__('Header 3', 'planmyday'),
						'4' => esc_html__('Header 4', 'planmyday'),
						'5' => esc_html__('Header 5', 'planmyday'),
						'6' => esc_html__('Header 6', 'planmyday'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'planmyday'),
					"desc" => wp_kses_data( __("Title style", 'planmyday') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'planmyday'),
						'underline' => esc_html__('Underline', 'planmyday'),
						'underline_ring' => esc_html__('Underline - Ring', 'planmyday'),
						'underline_heart' => esc_html__('Underline - Heart', 'planmyday'),
						'underline_comment' => esc_html__('Underline - Comment', 'planmyday'),
						'underline_bell' => esc_html__('Underline - Bell', 'planmyday'),
						'divider' => esc_html__('Divider', 'planmyday'),
						'iconed' => esc_html__('With icon (image)', 'planmyday')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'planmyday'),
					"desc" => wp_kses_data( __("Title text alignment", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'planmyday'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'planmyday'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'planmyday') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'planmyday'),
						'100' => esc_html__('Thin (100)', 'planmyday'),
						'300' => esc_html__('Light (300)', 'planmyday'),
						'400' => esc_html__('Normal (400)', 'planmyday'),
						'600' => esc_html__('Semibold (600)', 'planmyday'),
						'700' => esc_html__('Bold (700)', 'planmyday'),
						'900' => esc_html__('Black (900)', 'planmyday')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'planmyday'),
					"desc" => wp_kses_data( __("Select color for the title", 'planmyday') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'planmyday'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'planmyday') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => planmyday_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'planmyday'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'planmyday') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => planmyday_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'planmyday'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'planmyday') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'planmyday'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'planmyday') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'planmyday'),
						'medium' => esc_html__('Medium', 'planmyday'),
						'large' => esc_html__('Large', 'planmyday')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'planmyday'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'planmyday') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'planmyday'),
						'left' => esc_html__('Left', 'planmyday')
					)
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
if ( !function_exists( 'planmyday_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_title_reg_shortcodes_vc');
	function planmyday_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'planmyday'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'planmyday'),
					"description" => wp_kses_data( __("Title content", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'planmyday'),
					"description" => wp_kses_data( __("Title type (header level)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'planmyday') => '1',
						esc_html__('Header 2', 'planmyday') => '2',
						esc_html__('Header 3', 'planmyday') => '3',
						esc_html__('Header 4', 'planmyday') => '4',
						esc_html__('Header 5', 'planmyday') => '5',
						esc_html__('Header 6', 'planmyday') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'planmyday'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'planmyday') => 'regular',
						esc_html__('Underline', 'planmyday') => 'underline',
						esc_html__('Underline - Ring', 'planmyday') => 'underline_ring',
						esc_html__('Underline - Heart', 'planmyday') => 'underline_heart',
						esc_html__('Underline - Comment', 'planmyday') => 'underline_comment',
						esc_html__('Underline - Bell', 'planmyday') => 'underline_bell',
						esc_html__('Divider', 'planmyday') => 'divider',
						esc_html__('With icon (image)', 'planmyday') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'planmyday'),
					"description" => wp_kses_data( __("Title text alignment", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'planmyday'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'planmyday'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'planmyday') => 'inherit',
						esc_html__('Thin (100)', 'planmyday') => '100',
						esc_html__('Light (300)', 'planmyday') => '300',
						esc_html__('Normal (400)', 'planmyday') => '400',
						esc_html__('Semibold (600)', 'planmyday') => '600',
						esc_html__('Bold (700)', 'planmyday') => '700',
						esc_html__('Black (900)', 'planmyday') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'planmyday'),
					"description" => wp_kses_data( __("Select color for the title", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'planmyday'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'planmyday'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'planmyday'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'planmyday'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => planmyday_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'planmyday'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'planmyday') ),
					"group" => esc_html__('Icon &amp; Image', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'planmyday'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'planmyday') ),
					"group" => esc_html__('Icon &amp; Image', 'planmyday'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'planmyday') => 'small',
						esc_html__('Medium', 'planmyday') => 'medium',
						esc_html__('Large', 'planmyday') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'planmyday'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'planmyday') ),
					"group" => esc_html__('Icon &amp; Image', 'planmyday'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'planmyday') => 'top',
						esc_html__('Left', 'planmyday') => 'left'
					),
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>