<?php
/* WP GDPR Compliance support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('planmyday_wp_gdpr_compliance_theme_setup')) {
    add_action( 'planmyday_action_before_init_theme', 'planmyday_wp_gdpr_compliance_theme_setup', 1 );
    function planmyday_wp_gdpr_compliance_theme_setup() {
        if (is_admin()) {
            add_filter( 'planmyday_filter_required_plugins', 'planmyday_wp_gdpr_compliance_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'planmyday_exists_wp_gdpr_compliance' ) ) {
    function planmyday_exists_wp_gdpr_compliance() {
        return defined( 'WP_GDPR_Compliance_VERSION' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'planmyday_wp_gdpr_compliance_required_plugins' ) ) {
    //add_filter('planmyday_filter_required_plugins',    'planmyday_wp_gdpr_compliance_required_plugins');
    function planmyday_wp_gdpr_compliance_required_plugins($list=array()) {
        if (in_array('wp_gdpr_compliance', (array)planmyday_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('WP GDPR Compliance', 'planmyday'),
                'slug'         => 'wp-gdpr-compliance',
                'required'     => false
            );
        return $list;
    }
}
