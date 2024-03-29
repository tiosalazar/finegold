<?php
/**
 * Planmyday Framework: shortcodes manipulations
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('planmyday_sc_theme_setup')) {
	add_action( 'planmyday_action_init_theme', 'planmyday_sc_theme_setup', 1 );
	function planmyday_sc_theme_setup() {
		// Add sc stylesheets
		add_action('planmyday_action_add_styles', 'planmyday_sc_add_styles', 1);
	}
}

if (!function_exists('planmyday_sc_theme_setup2')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_sc_theme_setup2' );
	function planmyday_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'planmyday_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('planmyday_sc_prepare_content')) planmyday_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('planmyday_shortcode_output', 'planmyday_sc_add_scripts', 10, 4);

		if (function_exists('planmyday_sc_form_send')) {
			// AJAX: Send contact form data
			add_action('wp_ajax_send_form',			'planmyday_sc_form_send');
			add_action('wp_ajax_nopriv_send_form',	'planmyday_sc_form_send');
		}

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'planmyday_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'planmyday_sc_add_styles' ) ) {
	//add_action('planmyday_action_add_styles', 'planmyday_sc_add_styles', 1);
	function planmyday_sc_add_styles() {
		// Shortcodes
		wp_enqueue_style( 'planmyday-shortcodes-style',	trx_utils_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'planmyday_sc_add_scripts' ) ) {
	//add_filter('planmyday_shortcode_output', 'planmyday_sc_add_scripts', 10, 4);
	function planmyday_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (planmyday_storage_empty('shortcodes_scripts_added')) {
			planmyday_storage_set('shortcodes_scripts_added', true);
			wp_enqueue_script( 'planmyday-shortcodes-script', trx_utils_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('planmyday_sc_prepare_content')) {
	function planmyday_sc_prepare_content() {
		if (function_exists('planmyday_sc_clear_around')) {
			$filters = array(
				array('planmyday', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('planmyday_exists_woocommerce') && planmyday_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'planmyday_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('planmyday_sc_excerpt_shortcodes')) {
	//add_filter('the_excerpt', 'planmyday_sc_excerpt_shortcodes');
	function planmyday_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('planmyday_sc_clear_around')) {
	function planmyday_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Planmyday shortcodes load scripts
if (!function_exists('planmyday_sc_load_scripts')) {
	function planmyday_sc_load_scripts() {
		static $loaded = false;
		if (!$loaded) {
			wp_enqueue_script( 'planmyday-shortcodes_admin-script', trx_utils_get_file_url('shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
			wp_enqueue_script( 'planmyday-selection-script',  planmyday_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
			wp_localize_script( 'planmyday-shortcodes_admin-script', 'PLANMYDAY_SHORTCODES_DATA', planmyday_storage_get('shortcodes') );
			$loaded = true;
		}
	}
}

// Planmyday shortcodes prepare scripts
if (!function_exists('planmyday_sc_prepare_scripts')) {
	function planmyday_sc_prepare_scripts() {
		static $prepared = false;
		if (!$prepared) {
			planmyday_storage_set_array('js_vars', 'shortcodes_cp', is_admin() ? (!planmyday_storage_empty('to_colorpicker') ? planmyday_storage_get('to_colorpicker') : 'wp') : 'custom');	// wp | tiny | custom
			$prepared = true;
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('planmyday_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','planmyday_sc_selector_add_in_toolbar', 11);
	function planmyday_sc_selector_add_in_toolbar(){

		if ( !planmyday_options_is_used() ) return;

		planmyday_sc_load_scripts();
		planmyday_sc_prepare_scripts();

		$shortcodes = planmyday_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'planmyday').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		planmyday_show_layout($shortcodes_list);
	}
}

// Planmyday shortcodes builder settings
require_once trx_utils_get_file_dir ('shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once trx_utils_get_file_dir ('shortcodes/shortcodes_vc.php');
}

// Planmyday shortcodes implementation
// Using require_once trx_utils_get_file_dir(), because shortcodes can be replaced in the child theme
require_once trx_utils_get_file_dir('shortcodes/trx_basic/audio.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/blogger.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/call_to_action.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/columns.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/content.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/form.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/googlemap.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/image.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/infobox.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/line.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/list.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/price_block.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/promo.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/quote.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/section.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/skills.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/slider.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/socials.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/table.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/title.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/video.php');

require_once trx_utils_get_file_dir('shortcodes/trx_optional/accordion.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/button.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/dropcaps.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/highlight.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/icon.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/price.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/search.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/tabs.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/toggles.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/tooltip.php');
?>