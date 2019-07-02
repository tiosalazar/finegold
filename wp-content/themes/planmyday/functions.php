<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'planmyday_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_theme_setup', 1 );
	function planmyday_theme_setup() {

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Enable support for Post Thumbnails
        add_theme_support( 'post-thumbnails' );

        // Custom header setup
        add_theme_support( 'custom-header', array('header-text'=>false));

        // Custom backgrounds setup
        add_theme_support( 'custom-background');

        // Supported posts formats
        add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

        // Autogenerate title tag
        add_theme_support('title-tag');

        // Add user menu
        add_theme_support('nav-menus');

        // WooCommerce Support
        add_theme_support( 'woocommerce' );

        // Add wide and full blocks support
        add_theme_support( 'align-wide' );

        // Register theme menus
		add_filter( 'planmyday_filter_add_theme_menus',		'planmyday_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'planmyday_filter_add_theme_sidebars',	'planmyday_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'planmyday_filter_importer_options',		'planmyday_set_importer_options' );

		// Add theme required plugins
		add_filter( 'planmyday_filter_required_plugins',		'planmyday_add_required_plugins' );
		
		// Add preloader styles
		add_filter('planmyday_filter_add_styles_inline',		'planmyday_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'planmyday_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'planmyday_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'planmyday_head_add_page_meta', 1);
		add_action('before',								'planmyday_body_add_gtm');
		// add_action('before',								'planmyday_body_add_toc');
		add_action('before',								'planmyday_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'planmyday_footer_add_views_counter', 1);
		add_action('wp_footer',								'planmyday_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'planmyday_footer_add_scroll_to_top', 1);
		add_action('wp_footer',								'planmyday_footer_add_custom_html', 1);
		add_action('wp_footer',								'planmyday_footer_add_gtm2', 1);

		// Set list of the theme required plugins
		planmyday_storage_set('required_plugins', array(
	            'essgrids',
	            'revslider',
	            'trx_utils',
	            'visual_composer',
	            'mailchimp',
	            'woocommerce',
	            'instagram_feed',
	            'instagram_widget',
                'wp_gdpr_compliance',

			)
		);

		// Set list of the theme required custom fonts from folder /css/font-faces
		// Attention! Font's folder must have name equal to the font's name
		planmyday_storage_set('required_custom_fonts', array(
			'gogoiadeco',  
			'gogoiaregular',  
			'khand',  
			'khandbold',  
			'khandlight',  
			'khandmedium',  
			'khandsemibold'
			)
		);

		//planmyday_storage_set('demo_data_url',  esc_url(planmyday_get_protocol() . '://demofiles.axiomthemes.com/planmyday/'));
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'planmyday_add_theme_menus' ) ) {
	//Handler of add_filter( 'planmyday_filter_add_theme_menus', 'planmyday_add_theme_menus' );
	function planmyday_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'planmyday');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'planmyday_add_theme_sidebars' ) ) {
	//Handler of add_filter( 'planmyday_filter_add_theme_sidebars',	'planmyday_add_theme_sidebars' );
	function planmyday_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'planmyday' ),
				// 'sidebar_outer'		=> esc_html__( 'Outer Sidebar', 'planmyday' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'planmyday' )
			);
			if (function_exists('planmyday_exists_woocommerce') && planmyday_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'planmyday' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'planmyday_add_required_plugins' ) ) {
	//Handler of add_filter( 'planmyday_filter_required_plugins',		'planmyday_add_required_plugins' );
	function planmyday_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('ThemeRex Utilities', 'planmyday'),
			'version'	=> '3.1',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> planmyday_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}

//------------------------------------------------------------------------ 
// One-click import support 
//------------------------------------------------------------------------ 

// Set theme specific importer options 
if ( ! function_exists( 'planmyday_importer_set_options' ) ) {
    add_filter( 'trx_utils_filter_importer_options', 'planmyday_importer_set_options', 9 );
    function planmyday_importer_set_options( $options=array() ) {
        if ( is_array( $options ) ) {
            // Save or not installer's messages to the log-file 
            $options['debug'] = false;
            // Prepare demo data 
            if ( is_dir( PLANMYDAY_THEME_PATH . 'demo/' ) ) {
                $options['demo_url'] = PLANMYDAY_THEME_PATH . 'demo/';
            } else {
                $options['demo_url'] = esc_url( planmyday_get_protocol().'://demofiles.axiomthemes.com/planmyday/' ); // Demo-site domain
            }

            // Required plugins 
            $options['required_plugins'] =  array(
                'essential-grid',
                'revslider',
                'trx_utils',
                'js_composer',
                'mailchimp-for-wp',
                'woocommerce',
                'instagram-feed',
                'instagram_widget'
            );

            $options['theme_slug'] = 'planmyday';

            // Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images) 
            // Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images) 
            $options['regenerate_thumbnails'] = 3;
            // Default demo 
            $options['files']['default']['title'] = esc_html__( 'Planmyday Demo', 'planmyday' );
            $options['files']['default']['domain_dev'] = esc_url(planmyday_get_protocol().'://planmyday.dv.axiomthemes.com'); // Developers domain
            $options['files']['default']['domain_demo']= esc_url(planmyday_get_protocol().'://planmyday.axiomthemes.com'); // Demo-site domain

        }
        return $options;
    }
}

// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('planmyday_body_classes') ) {
	//Handler of add_filter( 'body_class', 'planmyday_body_classes' );
	function planmyday_body_classes( $classes ) {

		$classes[] = 'planmyday_body';
		$classes[] = 'body_style_' . trim(planmyday_get_custom_option('body_style'));
		$classes[] = 'body_' . (planmyday_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(planmyday_get_custom_option('article_style'));
		
		$blog_style = planmyday_get_custom_option(is_singular() && !planmyday_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(planmyday_get_template_name($blog_style));
		
		$body_scheme = planmyday_get_custom_option('body_scheme');
		if (empty($body_scheme)  || planmyday_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = planmyday_get_custom_option('top_panel_position');
		if (!planmyday_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = planmyday_get_sidebar_class();

		if (planmyday_get_custom_option('show_video_bg')=='yes' && (planmyday_get_custom_option('video_bg_youtube_code')!='' || planmyday_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!planmyday_param_is_off(planmyday_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('planmyday_head_add_page_meta')) {
	//Handler of add_action('wp_head', 'planmyday_head_add_page_meta', 1);
	function planmyday_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (planmyday_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('planmyday_head_add_page_preloader_styles')) {
	//Handler of add_filter('planmyday_filter_add_styles_inline', 'planmyday_head_add_page_preloader_styles');
	function planmyday_head_add_page_preloader_styles($css) {
		if (($preloader=planmyday_get_theme_option('page_preloader'))!='none') {
			$image = planmyday_get_theme_option('page_preloader_image');
			$bg_clr = planmyday_get_scheme_color('bg_color');
			$link_clr = planmyday_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('planmyday_body_add_gtm')) {
	//Handler of add_action('before', 'planmyday_body_add_gtm');
	function planmyday_body_add_gtm() {
		echo (planmyday_get_custom_option('gtm_code'));
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('planmyday_body_add_page_preloader')) {
	//Handler of add_action('before', 'planmyday_body_add_page_preloader');
	function planmyday_body_add_page_preloader() {
		if ( ($preloader=planmyday_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=planmyday_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('planmyday_footer_add_views_counter')) {
	//Handler of add_action('wp_footer', 'planmyday_footer_add_views_counter');
	function planmyday_footer_add_views_counter() {
		// Post/Page views counter
		require get_template_directory().'/templates/_parts/views-counter.php';
	}
}

// Add theme customizer
if (!function_exists('planmyday_footer_add_theme_customizer')) {
	//Handler of add_action('wp_footer', 'planmyday_footer_add_theme_customizer');
	function planmyday_footer_add_theme_customizer() {
		// Front customizer
		if (planmyday_get_custom_option('show_theme_customizer')=='yes') {
			require_once PLANMYDAY_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('planmyday_footer_add_scroll_to_top')) {
	//Handler of add_action('wp_footer', 'planmyday_footer_add_scroll_to_top');
	function planmyday_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up-small" title="<?php esc_attr_e('Scroll to top', 'planmyday'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('planmyday_footer_add_custom_html')) {
	//Handler of add_action('wp_footer', 'planmyday_footer_add_custom_html');
	function planmyday_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			echo (planmyday_get_custom_option('custom_code'));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('planmyday_footer_add_gtm2')) {
	//Handler of add_action('wp_footer', 'planmyday_footer_add_gtm2');
	function planmyday_footer_add_gtm2() {
		echo (planmyday_get_custom_option('gtm_code2'));
	}
}

// Add theme required plugins
if ( !function_exists( 'planmyday_add_trx_utils' ) ) {
    add_filter( 'trx_utils_active', 'planmyday_add_trx_utils' );
    function planmyday_add_trx_utils($enable=true) {
        return true;
    }
}

// Return text for the Privacy Policy checkbox
if ( ! function_exists('planmyday_get_privacy_text' ) ) {
    function planmyday_get_privacy_text() {
        $page = get_option( 'wp_page_for_privacy_policy' );
        $privacy_text = planmyday_get_theme_option( 'privacy_text' );
        return apply_filters( 'planmyday_filter_privacy_text', wp_kses_post(
                $privacy_text
                . ( ! empty( $page ) && ! empty( $privacy_text )
                    // Translators: Add url to the Privacy Policy page
                    ? ' ' . sprintf( __( 'For further details on handling user data, see our %s', 'planmyday' ),
                        '<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
                        . __( 'Privacy Policy', 'planmyday' )
                        . '</a>' )
                    : ''
                )
            )
        );
    }
}


// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>
