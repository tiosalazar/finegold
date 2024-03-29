<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_form_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_form_theme_setup' );
	function planmyday_sc_form_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_form_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_form_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('planmyday_sc_form')) {	
	function planmyday_sc_form($atts, $content = null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "form_custom",
			"action" => "",
			"return_url" => "",
			"return_page" => "",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_form_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width);
	
		planmyday_enqueue_messages();	// Load core messages
	
		planmyday_storage_set('sc_form_data', array(
			'id' => $id,
            'counter' => 0
            )
        );
	
		if ($style == 'form_custom')
			$content = do_shortcode($content);
		
		$fields = array();
		if (!empty($return_page)) 
			$return_url = get_permalink($return_page);
		if (!empty($return_url))
			$fields[] = array(
				'name' => 'return_url',
				'type' => 'hidden',
				'value' => $return_url
			);

		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_form_wrap'
					. ($scheme && !planmyday_param_is_off($scheme) && !planmyday_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_form'
					. ' sc_form_style_'.($style) 
					. (!empty($align) && !planmyday_param_is_off($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_form_subtitle sc_item_subtitle">' . trim(planmyday_strmacros($subtitle)) . '</h6>' 
						: '')
					. (!empty($title) 
						? '<h2 class="sc_form_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_with_descr') . '">' . trim(planmyday_strmacros($title)) . '</h2>' 
						: '')
					. (!empty($description) 
						? '<div class="sc_form_descr sc_item_descr">' . trim(planmyday_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>' 
						: '');
		
		$output .= planmyday_show_post_layout(array(
												'layout' => $style,
												'id' => $id,
												'action' => $action,
												'content' => $content,
												'fields' => $fields,
												'show' => false
												), false);

		$output .= '</div>'
				. '</div>';
	
		return apply_filters('planmyday_shortcode_output', $output, 'trx_form', $atts, $content);
	}
	planmyday_require_shortcode("trx_form", "planmyday_sc_form");
}

if (!function_exists('planmyday_sc_form_item')) {	
	function planmyday_sc_form_item($atts, $content=null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
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
	
		planmyday_storage_inc_array('sc_form_data', 'counter');
	
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		if (empty($id)) $id = planmyday_storage_get_array('sc_form_data', 'id').'_'.planmyday_storage_get_array('sc_form_data', 'counter');
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_form_item sc_form_item_'.esc_attr($type)
						.' sc_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;

		// Field output
		if ($type == 'textarea')

			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';

		else if ($type=='button' || $type=='submit')

			$output .= '<button id="' . esc_attr($id) . '">'.($label ? $label : $value).'</button>';

		else if ($type=='radio' || $type=='checkbox') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.intval($i) : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.intval($i) : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}

		} else if ($type=='select') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}

		} else if ($type=='date') {
			wp_enqueue_script( 'jquery-picker', planmyday_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-picker-date', planmyday_get_file_url('/js/picker/picker.date.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_date_wrap icon-calendar-light">'
						. '<input placeholder="' . esc_attr__('Date', 'planmyday') . '" id="' . esc_attr($id) . '" class="js__datepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';

		} else if ($type=='time') {
			wp_enqueue_script( 'jquery-picker', planmyday_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			wp_enqueue_script( 'jquery-picker-time', planmyday_get_file_url('/js/picker/picker.time.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_time_wrap icon-clock-empty">'
						. '<input placeholder="' . esc_attr__('Time', 'planmyday') . '" id="' . esc_attr($id) . '" class="js__timepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';
	
		} else

			$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';

		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('planmyday_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	planmyday_require_shortcode('trx_form_item', 'planmyday_sc_form_item');
}

// Show additional fields in the form
if ( !function_exists( 'planmyday_sc_form_show_fields' ) ) {
	function planmyday_sc_form_show_fields($fields) {
		if (is_array($fields) && count($fields)>0) {
			foreach ($fields as $f) {
				if (in_array($f['type'], array('hidden', 'text'))) {
					echo '<input type="'.esc_attr($f['type']).'" name="'.esc_attr($f['name']).'" value="'.esc_attr($f['value']).'">';
				}
			}
		}
	}
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_form_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_form_reg_shortcodes');
	function planmyday_sc_form_reg_shortcodes() {
	
		$pages = planmyday_get_list_pages(false);

		planmyday_sc_map("trx_form", array(
			"title" => esc_html__("Form", 'planmyday'),
			"desc" => wp_kses_data( __("Insert form with specified style or with set of custom fields", 'planmyday') ),
			"decorate" => true,
			"container" => false,
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
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", 'planmyday'),
					"desc" => wp_kses_data( __("Select style of the form (if 'style' is not equal 'Custom Form' - all tabs 'Field #' are ignored!)", 'planmyday') ),
					"divider" => true,
					"value" => 'form_custom',
					"options" => planmyday_get_sc_param('forms'),
					"type" => "checklist"
				), 
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"options" => planmyday_get_sc_param('schemes')
				),
				"action" => array(
					"title" => esc_html__("Action", 'planmyday'),
					"desc" => wp_kses_data( __("Contact form action (URL to handle form data). If empty - use internal action", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"return_page" => array(
					"title" => esc_html__("Page after submit", 'planmyday'),
					"desc" => wp_kses_data( __("Select page to redirect after form submit", 'planmyday') ),
					"value" => "0",
					"type" => "select",
					"options" => $pages
				),
				"return_url" => array(
					"title" => esc_html__("URL to redirect", 'planmyday'),
					"desc" => wp_kses_data( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'planmyday'),
					"desc" => wp_kses_data( __("Select form alignment", 'planmyday') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('align')
				),
				"width" => planmyday_shortcodes_width(),
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
				"name" => "trx_form_item",
				"title" => esc_html__("Field", 'planmyday'),
				"desc" => wp_kses_data( __("Custom field", 'planmyday') ),
				"container" => false,
				"params" => array(
					"type" => array(
						"title" => esc_html__("Type", 'planmyday'),
						"desc" => wp_kses_data( __("Type of the custom field", 'planmyday') ),
						"value" => "text",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => planmyday_get_sc_param('field_types')
					), 
					"name" => array(
						"title" => esc_html__("Name", 'planmyday'),
						"desc" => wp_kses_data( __("Name of the custom field", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Default value", 'planmyday'),
						"desc" => wp_kses_data( __("Default value of the custom field", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"options" => array(
						"title" => esc_html__("Options", 'planmyday'),
						"desc" => wp_kses_data( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'planmyday') ),
						"dependency" => array(
							'type' => array('radio', 'checkbox', 'select')
						),
						"value" => "",
						"type" => "text"
					),
					"label" => array(
						"title" => esc_html__("Label", 'planmyday'),
						"desc" => wp_kses_data( __("Label for the custom field", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"label_position" => array(
						"title" => esc_html__("Label position", 'planmyday'),
						"desc" => wp_kses_data( __("Label position relative to the field", 'planmyday') ),
						"value" => "top",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => planmyday_get_sc_param('label_positions')
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_form_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_form_reg_shortcodes_vc');
	function planmyday_sc_form_reg_shortcodes_vc() {

		$pages = planmyday_get_list_pages(false);
	
		vc_map( array(
			"base" => "trx_form",
			"name" => esc_html__("Form", 'planmyday'),
			"description" => wp_kses_data( __("Insert form with specefied style of with set of custom fields", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_form',
			"class" => "trx_sc_collection trx_sc_form",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('except' => 'trx_form'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'planmyday'),
					"description" => wp_kses_data( __("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"std" => "form_custom",
					"value" => array_flip(planmyday_get_sc_param('forms')),
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
				array(
					"param_name" => "action",
					"heading" => esc_html__("Action", 'planmyday'),
					"description" => wp_kses_data( __("Contact form action (URL to handle form data). If empty - use internal action", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "return_page",
					"heading" => esc_html__("Page after submit", 'planmyday'),
					"description" => wp_kses_data( __("Select page to redirect after form submit", 'planmyday') ),
					"class" => "",
					"std" => 0,
					"value" => array_flip($pages),
					"type" => "dropdown"
				),
				array(
					"param_name" => "return_url",
					"heading" => esc_html__("URL to redirect", 'planmyday'),
					"description" => wp_kses_data( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'planmyday'),
					"description" => wp_kses_data( __("Select form alignment", 'planmyday') ),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('align')),
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
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('animation'),
				planmyday_get_vc_param('css'),
				planmyday_vc_width(),
				planmyday_get_vc_param('margin_top'),
				planmyday_get_vc_param('margin_bottom'),
				planmyday_get_vc_param('margin_left'),
				planmyday_get_vc_param('margin_right')
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_form_item",
			"name" => esc_html__("Form item (custom field)", 'planmyday'),
			"description" => wp_kses_data( __("Custom field for the contact form", 'planmyday') ),
			"class" => "trx_sc_item trx_sc_form_item",
			'icon' => 'icon_trx_form_item',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_form,trx_column_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'planmyday'),
					"description" => wp_kses_data( __("Select type of the custom field", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('field_types')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", 'planmyday'),
					"description" => wp_kses_data( __("Name of the custom field", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Default value", 'planmyday'),
					"description" => wp_kses_data( __("Default value of the custom field", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "options",
					"heading" => esc_html__("Options", 'planmyday'),
					"description" => wp_kses_data( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'planmyday') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('radio','checkbox','select')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label",
					"heading" => esc_html__("Label", 'planmyday'),
					"description" => wp_kses_data( __("Label for the custom field", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label_position",
					"heading" => esc_html__("Label position", 'planmyday'),
					"description" => wp_kses_data( __("Label position relative to the field", 'planmyday') ),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('label_positions')),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Form extends PLANMYDAY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Form_Item extends PLANMYDAY_VC_ShortCodeItem {}
	}
}
?>