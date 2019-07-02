<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('planmyday_mailchimp_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_mailchimp_theme_setup', 1 );
	function planmyday_mailchimp_theme_setup() {
		if (planmyday_exists_mailchimp()) {
			if (is_admin()) {
				add_filter( 'planmyday_filter_importer_options',				'planmyday_mailchimp_importer_set_options' );
				add_action( 'planmyday_action_importer_params',				'planmyday_mailchimp_importer_show_params', 10, 1 );
				add_filter( 'planmyday_filter_importer_import_row',			'planmyday_mailchimp_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'planmyday_filter_importer_required_plugins',		'planmyday_mailchimp_importer_required_plugins', 10, 2 );
			add_filter( 'planmyday_filter_required_plugins',					'planmyday_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'planmyday_exists_mailchimp' ) ) {
	function planmyday_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'planmyday_mailchimp_required_plugins' ) ) {
	//Handler of add_filter('planmyday_filter_required_plugins',	'planmyday_mailchimp_required_plugins');
	function planmyday_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', planmyday_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'planmyday'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'planmyday_mailchimp_importer_required_plugins' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_required_plugins',	'planmyday_mailchimp_importer_required_plugins', 10, 2 );
	function planmyday_mailchimp_importer_required_plugins($not_installed='', $list='') {
		if (planmyday_strpos($list, 'mailchimp')!==false && !planmyday_exists_mailchimp() )
			$not_installed .= '<br>' . esc_html__('Mail Chimp', 'planmyday');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'planmyday_mailchimp_importer_set_options' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_options',	'planmyday_mailchimp_importer_set_options' );
	function planmyday_mailchimp_importer_set_options($options=array()) {
		if ( in_array('mailchimp', planmyday_storage_get('required_plugins')) && planmyday_exists_mailchimp() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mc4wp_lite_checkbox';
			$options['additional_options'][] = 'mc4wp_lite_form';
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'planmyday_mailchimp_importer_show_params' ) ) {
	//Handler of add_action( 'planmyday_action_importer_params',	'planmyday_mailchimp_importer_show_params', 10, 1 );
	function planmyday_mailchimp_importer_show_params($importer) {
		if ( planmyday_exists_mailchimp() && in_array('mailchimp', planmyday_storage_get('required_plugins')) ) {
			$importer->show_importer_params(array(
				'slug' => 'mailchimp',
				'title' => esc_html__('Import MailChimp for WP', 'planmyday'),
				'part' => 1
			));
		}
	}
}

// Check if the row will be imported
if ( !function_exists( 'planmyday_mailchimp_importer_check_row' ) ) {
	//Handler of add_filter('planmyday_filter_importer_import_row', 'planmyday_mailchimp_importer_check_row', 9, 4);
	function planmyday_mailchimp_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'mailchimp')===false) return $flag;
		if ( planmyday_exists_mailchimp() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='mc4wp-form';
		}
		return $flag;
	}
}
?>