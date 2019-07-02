<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('planmyday_gutenberg_theme_setup')) {
    add_action( 'planmyday_action_before_init_theme', 'planmyday_gutenberg_theme_setup', 1 );
    function planmyday_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'planmyday_filter_required_plugins', 'planmyday_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'planmyday_exists_gutenberg' ) ) {
    function planmyday_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'planmyday_gutenberg_required_plugins' ) ) {
    //add_filter('planmyday_filter_required_plugins',    'planmyday_gutenberg_required_plugins');
    function planmyday_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)planmyday_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'planmyday'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}