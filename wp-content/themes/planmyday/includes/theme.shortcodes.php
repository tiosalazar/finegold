<?php
if (!function_exists('planmyday_theme_shortcodes_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_theme_shortcodes_setup', 1 );
	function planmyday_theme_shortcodes_setup() {
		add_filter('planmyday_filter_googlemap_styles', 'planmyday_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'planmyday_theme_shortcodes_googlemap_styles' ) ) {
	function planmyday_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'planmyday');
		$list['greyscale']	= esc_html__('Greyscale', 'planmyday');
		$list['inverse']	= esc_html__('Inverse', 'planmyday');
		$list['apple']		= esc_html__('Apple', 'planmyday');
		$list['dark']		= esc_html__('Dark', 'planmyday');
		return $list;
	}
}
?>