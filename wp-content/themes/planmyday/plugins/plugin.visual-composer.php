<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('planmyday_vc_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_vc_theme_setup', 1 );
	function planmyday_vc_theme_setup() {
		if (planmyday_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'planmyday_filter_importer_options',				'planmyday_vc_importer_set_options' );
			}
			add_action('planmyday_action_add_styles',		 				'planmyday_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'planmyday_filter_importer_required_plugins',		'planmyday_vc_importer_required_plugins', 10, 2 );
			add_filter( 'planmyday_filter_required_plugins',					'planmyday_vc_required_plugins' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'planmyday_exists_visual_composer' ) ) {
	function planmyday_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'planmyday_vc_is_frontend' ) ) {
	function planmyday_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'planmyday_vc_required_plugins' ) ) {
	//Handler of add_filter('planmyday_filter_required_plugins',	'planmyday_vc_required_plugins');
	function planmyday_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', planmyday_storage_get('required_plugins'))) {
			$path = planmyday_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WPBakery PageBuilder', 'planmyday'),
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'planmyday_vc_frontend_scripts' ) ) {
	//Handler of add_action( 'planmyday_action_add_styles', 'planmyday_vc_frontend_scripts' );
	function planmyday_vc_frontend_scripts() {
		if (file_exists(planmyday_get_file_dir('css/plugin.visual-composer.css')))
			wp_enqueue_style( 'planmyday-plugin.visual-composer-style',  planmyday_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'planmyday_vc_importer_required_plugins' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_required_plugins',	'planmyday_vc_importer_required_plugins', 10, 2 );
	function planmyday_vc_importer_required_plugins($not_installed='', $list='') {
		if (!planmyday_exists_visual_composer() )		// && planmyday_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>' . esc_html__('WPBakery PageBuilder', 'planmyday');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'planmyday_vc_importer_set_options' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_options',	'planmyday_vc_importer_set_options' );
	function planmyday_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', planmyday_storage_get('required_plugins')) && planmyday_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>