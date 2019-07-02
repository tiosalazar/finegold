<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('planmyday_sc_skills_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_skills_theme_setup' );
	function planmyday_sc_skills_theme_setup() {
		add_action('planmyday_action_shortcodes_list', 		'planmyday_sc_skills_reg_shortcodes');
		if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
			add_action('planmyday_action_shortcodes_list_vc','planmyday_sc_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('planmyday_sc_skills')) {	
	function planmyday_sc_skills($atts, $content=null){	
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => esc_html__("Skills", 'planmyday'),
			"pie_compact" => "on",
			"pie_cutout" => 0,
			"title" => "",
			"addinfo" => "",
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
		planmyday_storage_set('sc_skills_data', array(
			'counter' => 0,
            'columns' => 0,
            'height'  => 0,
            'type'    => $type,
            'pie_compact' => planmyday_param_is_on($pie_compact) ? 'on' : 'off',
            'pie_cutout'  => max(0, min(99, $pie_cutout)),
            'color'   => $color,
            'bg_color'=> $bg_color,
            'border_color'=> $border_color,
            'legend'  => '',
            'data'    => ''
			)
		);
		planmyday_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') planmyday_storage_set_array('sc_skills_data', 'columns', $columns);
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			planmyday_storage_set_array('sc_skills_data', 'style', $style);
		}
		planmyday_storage_set_array('sc_skills_data', 'max', $max_value);
		planmyday_storage_set_array('sc_skills_data', 'dir', $dir);
		planmyday_storage_set_array('sc_skills_data', 'height', planmyday_prepare_css_value($height));
		$class .= ($class ? ' ' : '') . planmyday_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= planmyday_get_css_dimensions_from_values($width);
		if (!planmyday_storage_empty('sc_skills_data', 'height') && (planmyday_storage_get_array('sc_skills_data', 'type') == 'arc' || (planmyday_storage_get_array('sc_skills_data', 'type') == 'pie' && planmyday_param_is_on(planmyday_storage_get_array('sc_skills_data', 'pie_compact')))))
			$css .= 'height: '.planmyday_storage_get_array('sc_skills_data', 'height');
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'pie_compact')) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!planmyday_param_is_off($animation) ? ' data-animation="'.esc_attr(planmyday_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.(planmyday_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . (planmyday_storage_get_array('sc_skills_data', 'data')) . '</div>'
						  )
						: '')
					. ($type=='pie' && planmyday_param_is_on(planmyday_storage_get_array('sc_skills_data', 'pie_compact'))
						? ('<div class="sc_skills_legend">'.(planmyday_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie_canvas" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . (planmyday_storage_get_array('sc_skills_data', 'data')) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('planmyday_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	planmyday_require_shortcode('trx_skills', 'planmyday_sc_skills');
}


if (!function_exists('planmyday_sc_skills_item')) {	
	function planmyday_sc_skills_item($atts, $content=null) {
		if (planmyday_in_shortcode_blogger()) return '';
		extract(planmyday_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"addinfo" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		planmyday_storage_inc_array('sc_skills_data', 'counter');
		$ed = planmyday_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if (planmyday_storage_get_array('sc_skills_data', 'max') < $value) planmyday_storage_set_array('sc_skills_data', 'max', $value);
		$percent = round($value / planmyday_storage_get_array('sc_skills_data', 'max') * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round(planmyday_storage_get_array('sc_skills_data', 'max')/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label">' . ($title) . '</div>' 
			. (!empty($addinfo) ? '<div class="sc_skills_addinfo">' . ($addinfo) . '</div>' : '')
			. '</div>';
		$old_color = $color;
		if (empty($color)) $color = planmyday_storage_get_array('sc_skills_data', 'color');
		if (empty($color)) $color = planmyday_get_scheme_color('text_link', $color);
		if (empty($bg_color)) $bg_color = planmyday_storage_get_array('sc_skills_data', 'bg_color');
		if (empty($bg_color)) $bg_color = planmyday_get_scheme_color('bg_color', $bg_color);
		if (empty($border_color)) $border_color = planmyday_storage_get_array('sc_skills_data', 'border_color');
		if (empty($border_color)) $border_color = planmyday_get_scheme_color('bd_color', $border_color);;
		if (empty($style)) $style = planmyday_storage_get_array('sc_skills_data', 'style');
		$style = max(1, min(4, $style));
		$output = '';
		if (planmyday_storage_get_array('sc_skills_data', 'type') == 'arc' || (planmyday_storage_get_array('sc_skills_data', 'type') == 'pie' && planmyday_param_is_on(planmyday_storage_get_array('sc_skills_data', 'pie_compact')))) {
			if (planmyday_storage_get_array('sc_skills_data', 'type') == 'arc' && empty($old_color)) {
				$rgb = planmyday_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*(planmyday_storage_get_array('sc_skills_data', 'counter')-1)).')';
			}
			planmyday_storage_concat_array('sc_skills_data', 'legend', 
				'<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>'
			);
			planmyday_storage_concat_array('sc_skills_data', 'data', 
				'<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'type')).'"'
					. (planmyday_storage_get_array('sc_skills_data', 'type')=='pie'
						? ( ' data-start="'.esc_attr($start).'"'
							. ' data-stop="'.esc_attr($stop).'"'
							. ' data-step="'.esc_attr($step).'"'
							. ' data-steps="'.esc_attr($steps).'"'
							. ' data-max="'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'max')).'"'
							. ' data-speed="'.esc_attr($speed).'"'
							. ' data-duration="'.esc_attr($animation).'"'
							. ' data-color="'.esc_attr($color).'"'
							. ' data-bg_color="'.esc_attr($bg_color).'"'
							. ' data-border_color="'.esc_attr($border_color).'"'
							. ' data-cutout="'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
							. ' data-easing="easeOutCirc"'
							. ' data-ed="'.esc_attr($ed).'"'
							)
						: '')
					. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>'
			);
		} else {
			$output .= (planmyday_storage_get_array('sc_skills_data', 'columns') > 0 
							? '<div class="sc_skills_column column-1_'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'columns')).'">' 
							: '')
					. (planmyday_storage_get_array('sc_skills_data', 'type')=='bar' && planmyday_storage_get_array('sc_skills_data', 'dir')=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. (planmyday_storage_get_array('sc_skills_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
							. (planmyday_storage_get_array('sc_skills_data', 'counter') == 1 ? ' first' : '') 
							. '"'
						. (planmyday_storage_get_array('sc_skills_data', 'height') !='' || $css 
							? ' style="' 
								. (planmyday_storage_get_array('sc_skills_data', 'height') !='' 
										? 'height: '.esc_attr(planmyday_storage_get_array('sc_skills_data', 'height')).';' 
										: '') 
								. ($css) 
								. '"' 
							: '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
			if (in_array(planmyday_storage_get_array('sc_skills_data', 'type'), array('bar', 'counter'))) {
                $output .= '<div class="sc_skills_total"'
                            . ' data-start="'.esc_attr($start).'"'
                            . ' data-stop="'.esc_attr($stop).'"'
                            . ' data-step="'.esc_attr($step).'"'
                            . ' data-max="'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'max')).'"'
                            . ' data-speed="'.esc_attr($speed).'"'
                            . ' data-duration="'.esc_attr($animation).'"'
                            . ' data-ed="'.esc_attr($ed).'">'
                            . ($start) . ($ed)
                            .'</div>'
                            .'<div class="sc_skills_count"' . (planmyday_storage_get_array('sc_skills_data', 'type')=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' : '') . '>'
                            . '</div>';
			} else if (planmyday_storage_get_array('sc_skills_data', 'type')=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'max')).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr(planmyday_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>'
					.'<canvas id="'.esc_attr($id).'_canvas"></canvas>';
			}
			$output .= 
					  (planmyday_storage_get_array('sc_skills_data', 'type')=='counter' ? $title_block : '')
					. '</div>'
					. (planmyday_storage_get_array('sc_skills_data', 'type')=='bar' && planmyday_storage_get_array('sc_skills_data', 'dir')=='vertical' || planmyday_storage_get_array('sc_skills_data', 'type') == 'pie' ? $title_block : '')
					. (planmyday_storage_get_array('sc_skills_data', 'columns') > 0 ? '</div>' : '');
		}
		return apply_filters('planmyday_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	planmyday_require_shortcode('trx_skills_item', 'planmyday_sc_skills_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'planmyday_sc_skills_reg_shortcodes' ) ) {
	//add_action('planmyday_action_shortcodes_list', 'planmyday_sc_skills_reg_shortcodes');
	function planmyday_sc_skills_reg_shortcodes() {
	
		planmyday_sc_map("trx_skills", array(
			"title" => esc_html__("Skills", 'planmyday'),
			"desc" => wp_kses_data( __("Insert skills diagramm in your page (post)", 'planmyday') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", 'planmyday'),
					"desc" => wp_kses_data( __("Max value for skills items", 'planmyday') ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"type" => array(
					"title" => esc_html__("Skills type", 'planmyday'),
					"desc" => wp_kses_data( __("Select type of skills block", 'planmyday') ),
					"value" => "bar",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'bar' => esc_html__('Bar', 'planmyday'),
						'pie' => esc_html__('Pie chart', 'planmyday'),
						'counter' => esc_html__('Counter', 'planmyday')
						// 'arc' => esc_html__('Arc', 'planmyday')
					)
				), 
				"layout" => array(
					"title" => esc_html__("Skills layout", 'planmyday'),
					"desc" => wp_kses_data( __("Select layout of skills block", 'planmyday') ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "rows",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'rows' => esc_html__('Rows', 'planmyday'),
						'columns' => esc_html__('Columns', 'planmyday')
					)
				),
				"dir" => array(
					"title" => esc_html__("Direction", 'planmyday'),
					"desc" => wp_kses_data( __("Select direction of skills block", 'planmyday') ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "horizontal",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('dir')
				), 
				"style" => array(
					"title" => esc_html__("Counters style", 'planmyday'),
					"desc" => wp_kses_data( __("Select style of skills items (only for type=counter)", 'planmyday') ),
					"dependency" => array(
						'type' => array('counter')
					),
					"value" => 1,
					"options" => planmyday_get_list_styles(1, 1),
					"type" => "checklist"
				), 
				// "columns" - autodetect, not set manual
				"color" => array(
					"title" => esc_html__("Skills items color", 'planmyday'),
					"desc" => wp_kses_data( __("Color for all skills items", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'planmyday'),
					"desc" => wp_kses_data( __("Background color for all skills items (only for type=pie)", 'planmyday') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"border_color" => array(
					"title" => esc_html__("Border color", 'planmyday'),
					"desc" => wp_kses_data( __("Border color for all skills items (only for type=pie)", 'planmyday') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Align skills block", 'planmyday'),
					"desc" => wp_kses_data( __("Align skills block to left or right side", 'planmyday') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => planmyday_get_sc_param('float')
				), 
				// "arc_caption" => array(
				// 	"title" => esc_html__("Arc Caption", 'planmyday'),
				// 	"desc" => wp_kses_data( __("Arc caption - text in the center of the diagram", 'planmyday') ),
				// 	"dependency" => array(
				// 		'type' => array('arc')
				// 	),
				// 	"value" => "",
				// 	"type" => "text"
				// ),
				"pie_compact" => array(
					"title" => esc_html__("Pie compact", 'planmyday'),
					"desc" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", 'planmyday') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
				),
				"pie_cutout" => array(
					"title" => esc_html__("Pie cutout", 'planmyday'),
					"desc" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'planmyday') ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => 0,
					"min" => 0,
					"max" => 99,
					"type" => "spinner"
				),
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
			),
			"children" => array(
				"name" => "trx_skills_item",
				"title" => esc_html__("Skill", 'planmyday'),
				"desc" => wp_kses_data( __("Skills item", 'planmyday') ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", 'planmyday'),
						"desc" => wp_kses_data( __("Current skills item title", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"addinfo" => array(
						"title" => esc_html__("Additional info", 'planmyday'),
						"desc" => wp_kses_data( __("Additional info for current skills item", 'planmyday') ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", 'planmyday'),
						"desc" => wp_kses_data( __("Current skills level", 'planmyday') ),
						"value" => 50,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", 'planmyday'),
						"desc" => wp_kses_data( __("Current skills item color", 'planmyday') ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", 'planmyday'),
						"desc" => wp_kses_data( __("Current skills item background color (only for type=pie)", 'planmyday') ),
						"value" => "",
						"type" => "color"
					),
					"border_color" => array(
						"title" => esc_html__("Border color", 'planmyday'),
						"desc" => wp_kses_data( __("Current skills item border color (only for type=pie)", 'planmyday') ),
						"value" => "",
						"type" => "color"
					),
					"style" => array(
						"title" => esc_html__("Counter style", 'planmyday'),
						"desc" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", 'planmyday') ),
						"value" => 1,
						"options" => planmyday_get_list_styles(1, 1),
						"type" => "checklist"
					), 
					"icon" => array(
						"title" => esc_html__("Counter icon",  'planmyday'),
						"desc" => wp_kses_data( __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'planmyday') ),
						"value" => "",
						"type" => "icons",
						"options" => planmyday_get_sc_param('icons')
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
if ( !function_exists( 'planmyday_sc_skills_reg_shortcodes_vc' ) ) {
	//add_action('planmyday_action_shortcodes_list_vc', 'planmyday_sc_skills_reg_shortcodes_vc');
	function planmyday_sc_skills_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_skills",
			"name" => esc_html__("Skills", 'planmyday'),
			"description" => wp_kses_data( __("Insert skills diagramm", 'planmyday') ),
			"category" => esc_html__('Content', 'planmyday'),
			'icon' => 'icon_trx_skills',
			"class" => "trx_sc_collection trx_sc_skills",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_skills_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", 'planmyday'),
					"description" => wp_kses_data( __("Max value for skills items", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Skills type", 'planmyday'),
					"description" => wp_kses_data( __("Select type of skills block", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Bar', 'planmyday') => 'bar',
						esc_html__('Pie chart', 'planmyday') => 'pie',
						esc_html__('Counter', 'planmyday') => 'counter',
						// esc_html__('Arc', 'planmyday') => 'arc'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "layout",
					"heading" => esc_html__("Skills layout", 'planmyday'),
					"description" => wp_kses_data( __("Select layout of skills block", 'planmyday') ),
					"admin_label" => true,
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter','bar','pie')
					),
					"class" => "",
					"value" => array(
						esc_html__('Rows', 'planmyday') => 'rows',
						esc_html__('Columns', 'planmyday') => 'columns'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", 'planmyday'),
					"description" => wp_kses_data( __("Select direction of skills block", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Horizontal', 'planmyday') => 'horizontal'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counters style", 'planmyday'),
					"description" => wp_kses_data( __("Select style of skills items (only for type=counter)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_list_styles(1, 1)),
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", 'planmyday'),
					"description" => wp_kses_data( __("Skills columns count (required)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'planmyday'),
					"description" => wp_kses_data( __("Color for all skills items", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'planmyday'),
					"description" => wp_kses_data( __("Background color for all skills items (only for type=pie)", 'planmyday') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", 'planmyday'),
					"description" => wp_kses_data( __("Border color for all skills items (only for type=pie)", 'planmyday') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'planmyday'),
					"description" => wp_kses_data( __("Align skills block to left or right side", 'planmyday') ),
					"class" => "",
					"value" => array_flip(planmyday_get_sc_param('float')),
					"type" => "dropdown"
				),
				// array(
				// 	"param_name" => "arc_caption",
				// 	"heading" => esc_html__("Arc caption", 'planmyday'),
				// 	"description" => wp_kses_data( __("Arc caption - text in the center of the diagram", 'planmyday') ),
				// 	'dependency' => array(
				// 		'element' => 'type',
				// 		'value' => array('arc')
				// 	),
				// 	"class" => "",
				// 	"value" => "",
				// 	"type" => "textfield"
				// ),
				array(
					"param_name" => "pie_compact",
					"heading" => esc_html__("Pie compact", 'planmyday'),
					"description" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", 'planmyday') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => array(esc_html__('Show separate skills', 'planmyday') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pie_cutout",
					"heading" => esc_html__("Pie cutout", 'planmyday'),
					"description" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", 'planmyday') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
		
		
		vc_map( array(
			"base" => "trx_skills_item",
			"name" => esc_html__("Skill", 'planmyday'),
			"description" => wp_kses_data( __("Skills item", 'planmyday') ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_skills_item',
			"class" => "trx_sc_single trx_sc_skills_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_skills'),
			"as_parent" => array('except' => 'trx_skills'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'planmyday'),
					"description" => wp_kses_data( __("Title for the current skills item", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'planmyday'),
					"description" => wp_kses_data( __("Value for the current skills item", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "addinfo",
					"heading" => esc_html__("Additional info", 'planmyday'),
					"description" => wp_kses_data( __("Additional info for current skills item", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'planmyday'),
					"description" => wp_kses_data( __("Color for current skills item", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'planmyday'),
					"description" => wp_kses_data( __("Background color for current skills item (only for type=pie)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", 'planmyday'),
					"description" => wp_kses_data( __("Border color for current skills item (only for type=pie)", 'planmyday') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counter style", 'planmyday'),
					"description" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", 'planmyday') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(planmyday_get_list_styles(1, 1)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Counter icon", 'planmyday'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set, placed before counter (only for type=counter)", 'planmyday') ),
					"class" => "",
					"value" => planmyday_get_sc_param('icons'),
					"type" => "dropdown"
				),
				planmyday_get_vc_param('id'),
				planmyday_get_vc_param('class'),
				planmyday_get_vc_param('css'),
			)
		) );
		
		class WPBakeryShortCode_Trx_Skills extends PLANMYDAY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Skills_Item extends PLANMYDAY_VC_ShortCodeSingle {}
	}
}
?>