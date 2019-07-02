<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'planmyday_shortcodes_is_used' ) ) {
	function planmyday_shortcodes_is_used() {
		return planmyday_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page']=='vc-roles')										// VC Role Manager
			|| (function_exists('planmyday_vc_is_frontend') && planmyday_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'planmyday_shortcodes_width' ) ) {
	function planmyday_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'planmyday'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'planmyday_shortcodes_height' ) ) {
	function planmyday_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'planmyday'),
			"desc" => wp_kses_data( __("Width and height of the element", 'planmyday') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'planmyday_get_sc_param' ) ) {
	function planmyday_get_sc_param($prm) {
		return planmyday_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'planmyday_set_sc_param' ) ) {
	function planmyday_set_sc_param($prm, $val) {
		planmyday_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'planmyday_sc_map' ) ) {
	function planmyday_sc_map($sc_name, $sc_settings) {
		planmyday_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'planmyday_sc_map_after' ) ) {
	function planmyday_sc_map_after($after, $sc_name, $sc_settings='') {
		planmyday_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'planmyday_sc_map_before' ) ) {
	function planmyday_sc_map_before($before, $sc_name, $sc_settings='') {
		planmyday_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'planmyday_compare_sc_title' ) ) {
	function planmyday_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_shortcodes_settings_theme_setup' ) ) {
//	if ( planmyday_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'planmyday_action_before_init_theme', 'planmyday_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'planmyday_action_after_init_theme', 'planmyday_shortcodes_settings_theme_setup' );
	function planmyday_shortcodes_settings_theme_setup() {
		if (planmyday_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = planmyday_storage_get('registered_templates');
			ksort($tmp);
			planmyday_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			planmyday_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'planmyday'),
					"desc" => wp_kses_data( __("ID for current element", 'planmyday') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'planmyday'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'planmyday'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'planmyday'),
					'ol'	=> esc_html__('Ordered', 'planmyday'),
					'iconed'=> esc_html__('Iconed', 'planmyday')
				),

				'yes_no'	=> planmyday_get_list_yesno(),
				'on_off'	=> planmyday_get_list_onoff(),
				'dir' 		=> planmyday_get_list_directions(),
				'align'		=> planmyday_get_list_alignments(),
				'float'		=> planmyday_get_list_floats(),
				'hpos'		=> planmyday_get_list_hpos(),
				'show_hide'	=> planmyday_get_list_showhide(),
				'sorting' 	=> planmyday_get_list_sortings(),
				'ordering' 	=> planmyday_get_list_orderings(),
				'shapes'	=> planmyday_get_list_shapes(),
				'sizes'		=> planmyday_get_list_sizes(),
				'sliders'	=> planmyday_get_list_sliders(),
				'controls'	=> planmyday_get_list_controls(),
                    'categories'=> is_admin() && planmyday_get_value_gp('action')=='vc_edit_form' && substr(planmyday_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                        ? planmyday_get_list_terms(false, planmyday_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                        : planmyday_get_list_categories(),
				'columns'	=> planmyday_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), planmyday_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), planmyday_get_list_icons()),
				'locations'	=> planmyday_get_list_dedicated_locations(),
				'filters'	=> planmyday_get_list_portfolio_filters(),
				'formats'	=> planmyday_get_list_post_formats_filters(),
				'hovers'	=> planmyday_get_list_hovers(true),
				'hovers_dir'=> planmyday_get_list_hovers_directions(true),
				'schemes'	=> planmyday_get_list_color_schemes(true),
				'animations'		=> planmyday_get_list_animations_in(),
				'margins' 			=> planmyday_get_list_margins(true),
				'blogger_styles'	=> planmyday_get_list_templates_blogger(),
				'forms'				=> planmyday_get_list_templates_forms(),
				'posts_types'		=> planmyday_get_list_posts_types(),
				'googlemap_styles'	=> planmyday_get_list_googlemap_styles(),
				'field_types'		=> planmyday_get_list_field_types(),
				'label_positions'	=> planmyday_get_list_label_positions()
				)
			);

			// Common params
			planmyday_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'planmyday'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'planmyday') ),
				"value" => "none",
				"type" => "select",
				"options" => planmyday_get_sc_param('animations')
				)
			);
			planmyday_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'planmyday'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => planmyday_get_sc_param('margins')
				)
			);
			planmyday_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'planmyday'),
				"value" => "inherit",
				"type" => "select",
				"options" => planmyday_get_sc_param('margins')
				)
			);
			planmyday_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'planmyday'),
				"value" => "inherit",
				"type" => "select",
				"options" => planmyday_get_sc_param('margins')
				)
			);
			planmyday_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'planmyday'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'planmyday') ),
				"value" => "inherit",
				"type" => "select",
				"options" => planmyday_get_sc_param('margins')
				)
			);

			planmyday_storage_set('sc_params', apply_filters('planmyday_filter_shortcodes_params', planmyday_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			planmyday_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('planmyday_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = planmyday_storage_get('shortcodes');
			uasort($tmp, 'planmyday_compare_sc_title');
			planmyday_storage_set('shortcodes', $tmp);
		}
	}
}
?>