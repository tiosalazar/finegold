<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('planmyday_woocommerce_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_woocommerce_theme_setup', 1 );
	function planmyday_woocommerce_theme_setup() {

		if (planmyday_exists_woocommerce()) {
			add_action('planmyday_action_add_styles', 				'planmyday_woocommerce_frontend_scripts' );

            add_theme_support( 'woocommerce' );

            // Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
            add_theme_support( 'wc-product-gallery-slider' );

            // Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
            add_theme_support( 'wc-product-gallery-lightbox' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('planmyday_filter_get_blog_type',				'planmyday_woocommerce_get_blog_type', 9, 2);
			add_filter('planmyday_filter_get_blog_title',			'planmyday_woocommerce_get_blog_title', 9, 2);
			add_filter('planmyday_filter_get_current_taxonomy',		'planmyday_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('planmyday_filter_is_taxonomy',				'planmyday_woocommerce_is_taxonomy', 9, 2);
			add_filter('planmyday_filter_get_stream_page_title',		'planmyday_woocommerce_get_stream_page_title', 9, 2);
			add_filter('planmyday_filter_get_stream_page_link',		'planmyday_woocommerce_get_stream_page_link', 9, 2);
			add_filter('planmyday_filter_get_stream_page_id',		'planmyday_woocommerce_get_stream_page_id', 9, 2);
			add_filter('planmyday_filter_detect_inheritance_key',	'planmyday_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('planmyday_filter_detect_template_page_id',	'planmyday_woocommerce_detect_template_page_id', 9, 2);
			add_filter('planmyday_filter_orderby_need',				'planmyday_woocommerce_orderby_need', 9, 2);

			add_filter('planmyday_filter_show_post_navi', 			'planmyday_woocommerce_show_post_navi');
			add_filter('planmyday_filter_list_post_types', 			'planmyday_woocommerce_list_post_types');

			add_action('planmyday_action_shortcodes_list', 			'planmyday_woocommerce_reg_shortcodes', 20);
			if (function_exists('planmyday_exists_visual_composer') && planmyday_exists_visual_composer())
				add_action('planmyday_action_shortcodes_list_vc',	'planmyday_woocommerce_reg_shortcodes_vc', 20);

			if (is_admin()) {
				add_filter( 'planmyday_filter_importer_options',				'planmyday_woocommerce_importer_set_options' );
				add_action( 'planmyday_action_importer_after_import_posts',	'planmyday_woocommerce_importer_after_import_posts', 10, 1 );
				add_action( 'planmyday_action_importer_params',				'planmyday_woocommerce_importer_show_params', 10, 1 );
				add_action( 'planmyday_action_importer_import',				'planmyday_woocommerce_importer_import', 10, 2 );
				add_action( 'planmyday_action_importer_import_fields',		'planmyday_woocommerce_importer_import_fields', 10, 1 );
				add_action( 'planmyday_action_importer_export',				'planmyday_woocommerce_importer_export', 10, 1 );
				add_action( 'planmyday_action_importer_export_fields',		'planmyday_woocommerce_importer_export_fields', 10, 1 );
			}
		}

		if (is_admin()) {
			add_filter( 'planmyday_filter_importer_required_plugins',		'planmyday_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'planmyday_filter_required_plugins',					'planmyday_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'planmyday_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_woocommerce_settings_theme_setup2', 3 );
	function planmyday_woocommerce_settings_theme_setup2() {
		if (planmyday_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			planmyday_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => 'blog-woocommerce',		// This params must be empty
				'single_template' => 'single-woocommerce',		// They are specified to enable separate settings for blog and single wooc
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'custom'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			planmyday_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'planmyday'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'planmyday'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'planmyday'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'planmyday'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'planmyday'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'planmyday'),
						'list' => esc_html__('List', 'planmyday')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'planmyday'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'planmyday'),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'planmyday'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'planmyday'),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'planmyday'),
					"desc" => esc_html__('Show currency selector in the user menu', 'planmyday'),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'planmyday'),
					"desc" => esc_html__('Show cart button in the user menu', 'planmyday'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'planmyday'),
						'always' => esc_html__('Always', 'planmyday'),
						'shop'   => esc_html__('Only on shop pages', 'planmyday')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'planmyday'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'planmyday'),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('planmyday_woocommerce_theme_setup3')) {
	add_action( 'planmyday_action_after_init_theme', 'planmyday_woocommerce_theme_setup3' );
	function planmyday_woocommerce_theme_setup3() {

		if (planmyday_exists_woocommerce()) {

			add_action(    'woocommerce_before_subcategory_title',		'planmyday_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'planmyday_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'planmyday_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'planmyday_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'planmyday_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'planmyday_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'planmyday_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'planmyday_woocommerce_after_subcategory_title', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);

		}

		if (planmyday_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'planmyday_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'planmyday_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'planmyday_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'planmyday_woocommerce_show_product_title', 5 );

            remove_action(  'woocommerce_single_product_summary',       'woocommerce_template_single_excerpt', 20);
            add_action(    'woocommerce_single_product_summary',		'planmyday_template_single_excerpt', 20 );

			add_action(    'woocommerce_before_shop_loop', 				'planmyday_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'planmyday_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'planmyday_woocommerce_show_product_id', 10);

			if (planmyday_param_is_on(planmyday_get_custom_option('show_post_related'))) {
                add_filter('woocommerce_output_related_products_args', 'planmyday_woocommerce_output_related_products_args');
                add_filter('woocommerce_related_products_args', 'planmyday_woocommerce_related_products_args');
            } else {
                remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
            }

			add_filter(    'woocommerce_product_thumbnails_columns',	'planmyday_woocommerce_product_thumbnails_columns' );

			add_filter(    'get_product_search_form',					'planmyday_woocommerce_get_product_search_form' );

			add_filter(    'loop_shop_columns',							'planmyday_woocommerce_loop_shop_columns' );
			add_filter(    'post_class',								'planmyday_woocommerce_loop_shop_columns_class' );
			add_filter(    'product_cat_class',							'planmyday_woocommerce_loop_shop_columns_class', 10, 3 );

			add_action(    'the_title',									'planmyday_woocommerce_the_title');
			
			planmyday_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'planmyday_exists_woocommerce' ) ) {
	function planmyday_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'planmyday_is_woocommerce_page' ) ) {
	function planmyday_is_woocommerce_page() {
		$rez = false;
		if (planmyday_exists_woocommerce()) {
			if (!planmyday_storage_empty('pre_query')) {
				$id = planmyday_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = planmyday_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| planmyday_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| planmyday_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| planmyday_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'planmyday_woocommerce_detect_inheritance_key' ) ) {
	//Handler of add_filter('planmyday_filter_detect_inheritance_key',	'planmyday_woocommerce_detect_inheritance_key', 9, 1);
	function planmyday_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return planmyday_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'planmyday_woocommerce_detect_template_page_id' ) ) {
	//Handler of add_filter('planmyday_filter_detect_template_page_id',	'planmyday_woocommerce_detect_template_page_id', 9, 2);
	function planmyday_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'planmyday_woocommerce_get_blog_type' ) ) {
	//Handler of add_filter('planmyday_filter_get_blog_type',	'planmyday_woocommerce_get_blog_type', 9, 2);
	function planmyday_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'planmyday_woocommerce_get_blog_title' ) ) {
	//Handler of add_filter('planmyday_filter_get_blog_title',	'planmyday_woocommerce_get_blog_title', 9, 2);
	function planmyday_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( planmyday_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'planmyday') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'planmyday' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'planmyday' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'planmyday' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = planmyday_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = planmyday_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'planmyday' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'planmyday_woocommerce_get_stream_page_title' ) ) {
	//Handler of add_filter('planmyday_filter_get_stream_page_title',	'planmyday_woocommerce_get_stream_page_title', 9, 2);
	function planmyday_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (planmyday_strpos($page, 'woocommerce')!==false) {
			if (($page_id = planmyday_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = planmyday_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'planmyday');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'planmyday_woocommerce_get_stream_page_id' ) ) {
	//Handler of add_filter('planmyday_filter_get_stream_page_id',	'planmyday_woocommerce_get_stream_page_id', 9, 2);
	function planmyday_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (planmyday_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'planmyday_woocommerce_get_stream_page_link' ) ) {
	//Handler of add_filter('planmyday_filter_get_stream_page_link',	'planmyday_woocommerce_get_stream_page_link', 9, 2);
	function planmyday_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (planmyday_strpos($page, 'woocommerce')!==false) {
			$id = planmyday_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'planmyday_woocommerce_get_current_taxonomy' ) ) {
	//Handler of add_filter('planmyday_filter_get_current_taxonomy',	'planmyday_woocommerce_get_current_taxonomy', 9, 2);
	function planmyday_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( planmyday_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'planmyday_woocommerce_is_taxonomy' ) ) {
	//Handler of add_filter('planmyday_filter_is_taxonomy',	'planmyday_woocommerce_is_taxonomy', 9, 2);
	function planmyday_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query!==null && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'planmyday_woocommerce_orderby_need' ) ) {
	//Handler of add_filter('planmyday_filter_orderby_need',	'planmyday_woocommerce_orderby_need', 9, 1);
	function planmyday_woocommerce_orderby_need($need) {
		if ($need == false || planmyday_storage_empty('pre_query'))
			return $need;
		else {
			return planmyday_storage_call_obj_method('pre_query', 'get', 'post_type')!='product' 
					&& planmyday_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
					&& planmyday_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'planmyday_woocommerce_list_post_types' ) ) {
	//Handler of add_filter('planmyday_filter_list_post_types', 	'planmyday_woocommerce_list_post_types', 10, 1);
	function planmyday_woocommerce_list_post_types($list) {
		$list['product'] = esc_html__('Products', 'planmyday');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'planmyday_woocommerce_frontend_scripts' ) ) {
	//Handler of add_action( 'planmyday_action_add_styles', 'planmyday_woocommerce_frontend_scripts' );
	function planmyday_woocommerce_frontend_scripts() {
		if (planmyday_is_woocommerce_page() || planmyday_get_custom_option('show_cart')=='always')
			if (file_exists(planmyday_get_file_dir('css/plugin.woocommerce.css')))
				wp_enqueue_style( 'planmyday-plugin.woocommerce-style',  planmyday_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}

// Before main content
if ( !function_exists( 'planmyday_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//Handler of add_action('woocommerce_before_main_content', 'planmyday_woocommerce_wrapper_start', 10);
	function planmyday_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !planmyday_storage_empty('shop_mode') ? planmyday_storage_get('shop_mode') : 'thumbs'; ?>">
			<div class="sc_line_style_dot_line"></div>
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'planmyday_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//Handler of add_action('woocommerce_after_main_content', 'planmyday_woocommerce_wrapper_end', 10);
	function planmyday_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'planmyday_woocommerce_show_page_title' ) ) {
	//Handler of add_action('woocommerce_show_page_title', 'planmyday_woocommerce_show_page_title', 10);
	function planmyday_woocommerce_show_page_title($defa=true) {
		return planmyday_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'planmyday_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//Handler of add_action( 'woocommerce_single_product_summary', 'planmyday_woocommerce_show_product_title', 5 );
	function planmyday_woocommerce_show_product_title() {
		if (planmyday_get_custom_option('show_post_title')=='yes' || planmyday_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// New product excerpt with video shortcode
if ( !function_exists( 'planmyday_template_single_excerpt' ) ) {
    //remove_action(  'woocommerce_single_product_summary',       'woocommerce_template_single_excerpt', 20);
    //Handler of add_action(    'woocommerce_single_product_summary',		'planmyday_template_single_excerpt', 20 );
    function planmyday_template_single_excerpt() {
        if ( ! defined( 'ABSPATH' ) ) {
            exit; // Exit if accessed directly
        }
        global $post;
        if ( ! $post->post_excerpt ) {
            return;
        }
        ?>
        <div itemprop="description">
            <?php echo planmyday_substitute_all(apply_filters( 'woocommerce_short_description', $post->post_excerpt )); ?>
        </div>
    <?php
    }
}

// Add list mode buttons
if ( !function_exists( 'planmyday_woocommerce_before_shop_loop' ) ) {
	//Handler of add_action( 'woocommerce_before_shop_loop', 'planmyday_woocommerce_before_shop_loop', 10 );
	function planmyday_woocommerce_before_shop_loop() {
		if (planmyday_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(planmyday_get_current_url()) . '" method="post">'
				. '<input type="hidden" name="planmyday_shop_mode" value="'.esc_attr(planmyday_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'planmyday').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'planmyday').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'planmyday_woocommerce_open_thumb_wrapper' ) ) {
	//Handler of add_action( 'woocommerce_before_subcategory_title', 'planmyday_woocommerce_open_thumb_wrapper', 9 );
	//Handler of add_action( 'woocommerce_before_shop_loop_item_title', 'planmyday_woocommerce_open_thumb_wrapper', 9 );
	function planmyday_woocommerce_open_thumb_wrapper($cat='') {
		planmyday_storage_set('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'planmyday_woocommerce_open_item_wrapper' ) ) {
	//Handler of add_action( 'woocommerce_before_subcategory_title', 'planmyday_woocommerce_open_item_wrapper', 20 );
	//Handler of add_action( 'woocommerce_before_shop_loop_item_title', 'planmyday_woocommerce_open_item_wrapper', 20 );
	function planmyday_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'planmyday_woocommerce_close_item_wrapper' ) ) {
	//Handler of add_action( 'woocommerce_after_subcategory', 'planmyday_woocommerce_close_item_wrapper', 20 );
	//Handler of add_action( 'woocommerce_after_shop_loop_item', 'planmyday_woocommerce_close_item_wrapper', 20 );
	function planmyday_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		planmyday_storage_set('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'planmyday_woocommerce_after_shop_loop_item_title' ) ) {
	//Handler of add_action( 'woocommerce_after_shop_loop_item_title', 'planmyday_woocommerce_after_shop_loop_item_title', 7);
	function planmyday_woocommerce_after_shop_loop_item_title() {
		if (planmyday_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'planmyday_woocommerce_after_subcategory_title' ) ) {
	//Handler of add_action( 'woocommerce_after_subcategory_title', 'planmyday_woocommerce_after_subcategory_title', 10 );
	function planmyday_woocommerce_after_subcategory_title($category) {
		if (planmyday_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'planmyday_woocommerce_show_product_id' ) ) {
	//Handler of add_action( 'woocommerce_product_meta_end', 'planmyday_woocommerce_show_product_id', 10);
	function planmyday_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'planmyday') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'planmyday_woocommerce_output_related_products_args' ) ) {
	//Handler of add_filter( 'woocommerce_output_related_products_args', 'planmyday_woocommerce_output_related_products_args' );
	function planmyday_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (planmyday_param_is_on(planmyday_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(planmyday_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  planmyday_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = planmyday_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Redefine post_type if number of related products == 0
if ( !function_exists( 'planmyday_woocommerce_related_products_args' ) ) {
	//Handler of add_filter( 'woocommerce_related_products_args', 'planmyday_woocommerce_related_products_args' );
	function planmyday_woocommerce_related_products_args($args) {
		if ($args['posts_per_page'] == 0) {
			$args['post_type'] .= '_';
        }
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'planmyday_woocommerce_product_thumbnails_columns' ) ) {
	//Handler of add_filter( 'woocommerce_product_thumbnails_columns', 'planmyday_woocommerce_product_thumbnails_columns' );
	function planmyday_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'planmyday_woocommerce_loop_shop_columns_class' ) ) {
	//Handler of add_filter( 'post_class', 'planmyday_woocommerce_loop_shop_columns_class' );
	function planmyday_woocommerce_loop_shop_columns_class($class) {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns']))
			$class[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
		} else if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array(planmyday_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  planmyday_get_custom_option('shop_loop_columns');
			$ccc = $ccc > 0 ? $ccc : (planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$class[] = ' column-1_'.esc_attr($ccc);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'planmyday_woocommerce_loop_shop_columns' ) ) {
	//Handler of add_filter( 'loop_shop_columns', 'planmyday_woocommerce_loop_shop_columns' );
	function planmyday_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array(planmyday_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc =  planmyday_get_custom_option('shop_loop_columns');
		$ccc = $ccc > 0 ? $ccc : (planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
		return $ccc;
	}
}

// Search form
if ( !function_exists( 'planmyday_woocommerce_get_product_search_form' ) ) {
	//Handler of add_filter( 'get_product_search_form', 'planmyday_woocommerce_get_product_search_form' );
	function planmyday_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'planmyday') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'planmyday') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'planmyday_woocommerce_the_title' ) ) {
	//Handler of add_filter( 'the_title', 'planmyday_woocommerce_the_title' );
	function planmyday_woocommerce_the_title($title) {
		if (planmyday_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'planmyday_woocommerce_pagination' ) ) {
	//Handler of add_filter( 'woocommerce_after_shop_loop', 'planmyday_woocommerce_pagination', 10 );
	function planmyday_woocommerce_pagination() {
		$style = planmyday_get_custom_option('blog_pagination');
		planmyday_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'planmyday_woocommerce_required_plugins' ) ) {
	//Handler of add_filter('planmyday_filter_required_plugins',	'planmyday_woocommerce_required_plugins');
	function planmyday_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', planmyday_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}

// Show products navigation
if ( !function_exists( 'planmyday_woocommerce_show_post_navi' ) ) {
	//Handler of add_filter('planmyday_filter_show_post_navi', 'planmyday_woocommerce_show_post_navi');
	function planmyday_woocommerce_show_post_navi($show=false) {
		return $show || (planmyday_get_custom_option('show_page_title')=='yes' && is_single() && planmyday_is_woocommerce_page());
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'planmyday_woocommerce_importer_required_plugins' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_required_plugins',	'planmyday_woocommerce_importer_required_plugins', 10, 2 );
	function planmyday_woocommerce_importer_required_plugins($not_installed='', $list='') {
		if (planmyday_strpos($list, 'woocommerce')!==false && !planmyday_exists_woocommerce() )
			$not_installed .= '<br>' . esc_html__('WooCommerce', 'planmyday');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'planmyday_woocommerce_importer_set_options' ) ) {
	//Handler of add_filter( 'planmyday_filter_importer_options',	'planmyday_woocommerce_importer_set_options' );
	function planmyday_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', planmyday_storage_get('required_plugins')) && planmyday_exists_woocommerce() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_woocommerce'] = str_replace('name.ext', 'woocommerce.txt', $v['file_with_']);
				}
			}
			// Add slugs to export options for this plugin
			$options['additional_options'][]	= 'shop_%';
			$options['additional_options'][]	= 'woocommerce_%';
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'planmyday_woocommerce_importer_after_import_posts' ) ) {
	//Handler of add_action( 'planmyday_action_importer_after_import_posts',	'planmyday_woocommerce_importer_after_import_posts', 10, 1 );
	function planmyday_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'planmyday_woocommerce_importer_show_params' ) ) {
	//Handler of add_action( 'planmyday_action_importer_params',	'planmyday_woocommerce_importer_show_params', 10, 1 );
	function planmyday_woocommerce_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('Import WooCommerce', 'planmyday'),
			'part' => 0
			));
	}
}

// Import posts
if ( !function_exists( 'planmyday_woocommerce_importer_import' ) ) {
	//Handler of add_action( 'planmyday_action_importer_import',	'planmyday_woocommerce_importer_import', 10, 2 );
	function planmyday_woocommerce_importer_import($importer, $action) {
		if ( $action == 'import_woocommerce' ) {
			$importer->response['start_from_id'] = 0;
			$importer->import_dump('woocommerce', esc_html__('WooCommerce meta', 'planmyday'));
			delete_transient( 'wc_attribute_taxonomies' );
		}
	}
}

// Display import progress
if ( !function_exists( 'planmyday_woocommerce_importer_import_fields' ) ) {
	//Handler of add_action( 'planmyday_action_importer_import_fields',	'planmyday_woocommerce_importer_import_fields', 10, 1 );
	function planmyday_woocommerce_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('WooCommerce meta', 'planmyday')
			));
	}
}

// Export posts
if ( !function_exists( 'planmyday_woocommerce_importer_export' ) ) {
	//Handler of add_action( 'planmyday_action_importer_export',	'planmyday_woocommerce_importer_export', 10, 1 );
	function planmyday_woocommerce_importer_export($importer) {
		planmyday_fpc(planmyday_get_file_dir('core/core.importer/export/woocommerce.txt'), serialize( array(
			"woocommerce_attribute_taxonomies"				=> $importer->export_dump("woocommerce_attribute_taxonomies"),
			"woocommerce_downloadable_product_permissions"	=> $importer->export_dump("woocommerce_downloadable_product_permissions"),
            "woocommerce_order_itemmeta"					=> $importer->export_dump("woocommerce_order_itemmeta"),
            "woocommerce_order_items"						=> $importer->export_dump("woocommerce_order_items"),
            "woocommerce_termmeta"							=> $importer->export_dump("woocommerce_termmeta")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'planmyday_woocommerce_importer_export_fields' ) ) {
	//Handler of add_action( 'planmyday_action_importer_export_fields',	'planmyday_woocommerce_importer_export_fields', 10, 1 );
	function planmyday_woocommerce_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('WooCommerce', 'planmyday')
			));
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'planmyday_woocommerce_reg_shortcodes' ) ) {
	//Handler of add_action('planmyday_action_shortcodes_list', 'planmyday_woocommerce_reg_shortcodes', 20);
	function planmyday_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		planmyday_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		planmyday_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		planmyday_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		planmyday_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		planmyday_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		planmyday_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'planmyday'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'planmyday'),
					"desc" => wp_kses_data( __("ID of displayed product", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", 'planmyday'),
					"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'planmyday') ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", 'planmyday'),
					"desc" => wp_kses_data( __("Display posts only with this status", 'planmyday') ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'planmyday'),
						"protected" => esc_html__('Protected', 'planmyday'),
						"private" => esc_html__('Private', 'planmyday'),
						"pending" => esc_html__('Pending', 'planmyday'),
						"draft" => esc_html__('Draft', 'planmyday')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		planmyday_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'planmyday'),
					"desc" => wp_kses_data( __("SKU code of displayed product", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'planmyday'),
					"desc" => wp_kses_data( __("ID of displayed product", 'planmyday') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		planmyday_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);
		
		// WooCommerce - Recent Products
		planmyday_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Related Products
		planmyday_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
						)
					)
				)
			)
		);
		
		// WooCommerce - Featured Products
		planmyday_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Top Rated Products
		planmyday_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Sale Products
		planmyday_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		planmyday_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", 'planmyday'),
					"desc" => wp_kses_data( __("Comma separated category slugs", 'planmyday') ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", 'planmyday'),
					"desc" => wp_kses_data( __("Categories operator", 'planmyday') ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'planmyday'),
						"NOT IN" => esc_html__('NOT IN', 'planmyday'),
						"AND" => esc_html__('AND', 'planmyday')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		planmyday_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", 'planmyday'),
					"desc" => wp_kses_data( __("Comma separated SKU codes of products", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'planmyday'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		planmyday_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many products showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", 'planmyday'),
					"desc" => wp_kses_data( __("Attribute name", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", 'planmyday'),
					"desc" => wp_kses_data( __("Attribute value", 'planmyday') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		planmyday_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", 'planmyday'),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'planmyday') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", 'planmyday'),
					"desc" => wp_kses_data( __("How many categories showed", 'planmyday') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'planmyday'),
					"desc" => wp_kses_data( __("How many columns per row use for categories output", 'planmyday') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'planmyday'),
						"title" => esc_html__('Title', 'planmyday')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'planmyday'),
					"desc" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => planmyday_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", 'planmyday'),
					"desc" => wp_kses_data( __("Parent category slug", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'planmyday'),
					"desc" => wp_kses_data( __("Comma separated ID of products", 'planmyday') ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", 'planmyday'),
					"desc" => wp_kses_data( __("Hide empty categories", 'planmyday') ),
					"value" => "yes",
					"type" => "switch",
					"options" => planmyday_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'planmyday_woocommerce_reg_shortcodes_vc' ) ) {
	//Handler of add_action('planmyday_action_shortcodes_list_vc', 'planmyday_woocommerce_reg_shortcodes_vc');
	function planmyday_woocommerce_reg_shortcodes_vc() {
	
		if (false && function_exists('planmyday_exists_woocommerce') && planmyday_exists_woocommerce()) {
		
			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'planmyday'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'planmyday') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Cart extends PLANMYDAY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'planmyday'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'planmyday') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Checkout extends PLANMYDAY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'planmyday'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'planmyday') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_My_Account extends PLANMYDAY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'planmyday'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'planmyday') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Order_Tracking extends PLANMYDAY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'planmyday'),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", 'planmyday') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Shop_Messages extends PLANMYDAY_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'planmyday'),
						"description" => wp_kses_data( __("SKU code of displayed product", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'planmyday'),
						"description" => wp_kses_data( __("ID of displayed product", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'planmyday'),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", 'planmyday') ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", 'planmyday'),
						"description" => wp_kses_data( __("Display posts only with this status", 'planmyday') ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'planmyday') => 'publish',
							esc_html__('Protected', 'planmyday') => 'protected',
							esc_html__('Private', 'planmyday') => 'private',
							esc_html__('Pending', 'planmyday') => 'pending',
							esc_html__('Draft', 'planmyday') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Page extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'planmyday'),
						"description" => wp_kses_data( __("Product's SKU code", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'planmyday'),
						"description" => wp_kses_data( __("Product's ID", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Best_Selling_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Related_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Featured_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Top_Rated_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Sale_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", 'planmyday'),
						"description" => wp_kses_data( __("Comma separated category slugs", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", 'planmyday'),
						"description" => wp_kses_data( __("Categories operator", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'planmyday') => 'IN',
							esc_html__('NOT IN', 'planmyday') => 'NOT IN',
							esc_html__('AND', 'planmyday') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Category extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", 'planmyday'),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'planmyday'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Products extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
		
			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many products showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", 'planmyday'),
						"description" => wp_kses_data( __("Attribute name", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", 'planmyday'),
						"description" => wp_kses_data( __("Attribute value", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Attribute extends PLANMYDAY_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", 'planmyday'),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", 'planmyday') ),
				"category" => esc_html__('WooCommerce', 'planmyday'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", 'planmyday'),
						"description" => wp_kses_data( __("How many categories showed", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'planmyday'),
						"description" => wp_kses_data( __("How many columns per row use for categories output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'planmyday') => 'date',
							esc_html__('Title', 'planmyday') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'planmyday'),
						"description" => wp_kses_data( __("Sorting order for products output", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(planmyday_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", 'planmyday'),
						"description" => wp_kses_data( __("Parent category slug", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'planmyday'),
						"description" => wp_kses_data( __("Comma separated ID of products", 'planmyday') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", 'planmyday'),
						"description" => wp_kses_data( __("Hide empty categories", 'planmyday') ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );
			
			class WPBakeryShortCode_Products_Categories extends PLANMYDAY_VC_ShortCodeSingle {}
		
		}
	}
}
?>