<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('planmyday_instagram_widget_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_instagram_widget_theme_setup', 1 );
	function planmyday_instagram_widget_theme_setup() {
		if (planmyday_exists_instagram_widget()) {
			add_action( 'planmyday_action_add_styles', 						'planmyday_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'planmyday_filter_importer_required_plugins',		'planmyday_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'planmyday_filter_required_plugins',					'planmyday_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'planmyday_exists_instagram_widget' ) ) {
	function planmyday_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'planmyday_instagram_widget_required_plugins' ) ) {
	//Handler of add_filter('planmyday_filter_required_plugins',	'planmyday_instagram_widget_required_plugins');
	function planmyday_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', planmyday_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'planmyday'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'planmyday_instagram_widget_frontend_scripts' ) ) {
	//Handler of add_action( 'planmyday_action_add_styles', 'planmyday_instagram_widget_frontend_scripts' );
	function planmyday_instagram_widget_frontend_scripts() {
		if (file_exists(planmyday_get_file_dir('css/plugin.instagram-widget.css')))
			wp_enqueue_style( 'planmyday-plugin.instagram-widget-style',  planmyday_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'planmyday_instagram_widget_importer_required_plugins' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_required_plugins',	'planmyday_instagram_widget_importer_required_plugins', 10, 2 );
	function planmyday_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (planmyday_strpos($list, 'instagram_widget')!==false && !planmyday_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'planmyday');
		return $not_installed;
	}
}
?>