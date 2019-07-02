<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_section_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_section_theme_setup' );
	function planmyday_sc_section_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_section_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

planmyday_storage_set('sc_section_dedicated', '');

if (!function_exists('planmyday_sc_section')) {	
	function planmyday_sc_section($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'planmyday'),
			"link" => '',
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = planmyday_get_scheme_color('bg');
			$rgb = planmyday_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(planmyday_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!planmyday_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(planmyday_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !planmyday_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = planmyday_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && planmyday_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = planmyday_prepare_css_value($width);
		$height = planmyday_prepare_css_value($height);
	
		if ((!planmyday_param_is_off($scroll) || !planmyday_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!planmyday_param_is_off($scroll)) planmyday_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !planmyday_param_is_off($scheme) && !planmyday_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. '"'
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner'
					. (planmyday_param_is_on($scroll) && !planmyday_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || planmyday_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (planmyday_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (planmyday_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(planmyday_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_section_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_with_descr') . '">' . trim(planmyday_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(planmyday_strmacros($description)) . '</div>' : '')
					. (planmyday_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (planmyday_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.planmyday_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (planmyday_param_is_on($pan) ? '</div>' : '')
					. (planmyday_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!planmyday_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || planmyday_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (planmyday_param_is_on($dedicated)) {
			if (planmyday_storage_get('sc_section_dedicated')=='') {
				planmyday_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('planmyday_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	planmyday_require_shortcode('trx_section', 'planmyday_sc_section');
}

if (!function_exists('planmyday_sc_block')) {	
	function planmyday_sc_block($atts, $content=null) {
        if (empty($atts)) $atts = array();
        $atts['class'] = (!empty($atts['class']) ? $atts['class'] . ' ' : '') . 'sc_section_block';
		return apply_filters('planmyday_shortcode_output', planmyday_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	planmyday_require_shortcode('trx_block', 'planmyday_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_section_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_section_reg_shortcodes');
	function planmyday_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'planmyday'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'planmyday') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'planmyday'),
					"desc" => wp_kses_data( __("Title for the block", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'planmyday'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'planmyday'),
					"desc" => wp_kses_data( __("Short description for the block", 'planmyday') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'planmyday'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'planmyday'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'planmyday'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'planmyday') ),
					"value" => "no",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'planmyday'),
					"desc" => wp_kses_data( __("Select block alignment", 'planmyday') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'planmyday'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'planmyday') ),
					"value" => "none",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'planmyday'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'planmyday') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'planmyday'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'planmyday') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'planmyday'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'planmyday') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'planmyday'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'planmyday') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'planmyday'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'planmyday'),
					"desc" => wp_kses_data( __("Any background color for this section", 'planmyday') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'planmyday'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'planmyday') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'planmyday'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'planmyday') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'planmyday'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'planmyday') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'planmyday'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'planmyday') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'planmyday'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'planmyday') ),
					"value" => "yes",					
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'planmyday'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'planmyday'),
					"desc" => wp_kses_data( __("Font weight of the text", 'planmyday') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'planmyday'),
						'300' => esc_html__('Light (300)', 'planmyday'),
						'400' => esc_html__('Normal (400)', 'planmyday'),
						'700' => esc_html__('Bold (700)', 'planmyday')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'planmyday'),
					"desc" => wp_kses_data( __("Content for section container", 'planmyday') ),
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
		);
		planmyday_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'planmyday');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'planmyday');
		planmyday_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_section_reg_shortcodes_vc');
	function planmyday_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'planmyday'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'planmyday'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'planmyday') => 'yes'),
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
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'planmyday'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('columns')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Title for the block", 'planmyday') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'planmyday'),
					"description" => wp_kses_data( __("Subtitle for the block", 'planmyday') ),
					"group" => esc_html__('Captions', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'planmyday'),
					"description" => wp_kses_data( __("Description for the block", 'planmyday') ),
					"group" => esc_html__('Captions', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'planmyday'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'planmyday') ),
					"group" => esc_html__('Captions', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'planmyday'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'planmyday') ),
					"group" => esc_html__('Captions', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'planmyday'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'planmyday') ),
					"group" => esc_html__('Scroll', 'planmyday'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'planmyday') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'planmyday'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'planmyday') ),
					"group" => esc_html__('Scroll', 'planmyday'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'planmyday') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'planmyday'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'planmyday'),
					"value" => array_flip(planmyday_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'planmyday'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'planmyday') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'planmyday'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(planmyday_get_sc_param('controls')),
					"type" => "dropdown"
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
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'planmyday'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'planmyday'),
					"description" => wp_kses_data( __("Any background color for this section", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'planmyday'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'planmyday'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'planmyday') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'planmyday'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'planmyday'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'planmyday'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'planmyday') ),
					"group" => esc_html__('Colors and Images', 'planmyday'),
					"class" => "",					
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'planmyday') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'planmyday'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'planmyday'),
					"description" => wp_kses_data( __("Font weight of the text", 'planmyday') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'planmyday') => 'inherit',
						esc_html__('Thin (100)', 'planmyday') => '100',
						esc_html__('Light (300)', 'planmyday') => '300',
						esc_html__('Normal (400)', 'planmyday') => '400',
						esc_html__('Bold (700)', 'planmyday') => '700'
					),
					"type" => "dropdown"
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'planmyday');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'planmyday') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends PLANMYDAY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends PLANMYDAY_VC_ShortCodeCollection {}
	}
}
?>