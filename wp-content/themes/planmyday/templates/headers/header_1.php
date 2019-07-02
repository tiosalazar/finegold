<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_header_1_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_header_1_theme_setup', 1 );
	function planmyday_template_header_1_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'header_1',
			'mode'   => 'header',
			'title'  => esc_html__('Header 1', 'planmyday'),
			'icon'   => planmyday_get_file_url('templates/headers/images/1.jpg'),
			'thumb_title'  => esc_html__('Original image', 'planmyday'),
			'w'		 => null,
			'h_crop' => null,
			'h'      => null
			));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_header_1_output' ) ) {
	function planmyday_template_header_1_output($post_options, $post_data) {

		// Get custom image (for blog) or featured image (for single)
		$header_css = '';
		if (is_singular()) {
			$post_id = get_the_ID();
			$post_format = get_post_format();
			$post_icon = planmyday_get_custom_option('icon', planmyday_get_post_format_icon($post_format));
			$header_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		}
		if (empty($header_image))
			$header_image = planmyday_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			// Uncomment next rows if you want crop image
			//$thumb_sizes = planmyday_get_thumb_sizes(array( 'layout' => $post_options['layout'] ));
			//$header_image = planmyday_get_resized_image_url($header_image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_1 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_1 top_panel_position_<?php echo esc_attr(planmyday_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="column-1_3 contact_logo">
						<?php planmyday_show_logo(true, true); ?>
					</div>
					<div class="column-2_3 menu_main_wrap">
						<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(planmyday_get_theme_option('menu_hover')); ?>">
							<?php
							$menu_main = planmyday_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = planmyday_get_nav_menu();
							planmyday_show_layout($menu_main);
							?>
						</nav>
						<?php
						if (function_exists('planmyday_exists_woocommerce') && planmyday_exists_woocommerce() && (planmyday_is_woocommerce_page() && planmyday_get_custom_option('show_cart')=='shop' || planmyday_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
							?>
							<div class="menu_main_cart top_panel_icon">
								<?php require get_template_directory().'/templates/headers/_parts/contact-info-cart.php'; ?>
							</div>
							<?php
						}
						if (planmyday_get_custom_option('show_search')=='yes' && function_exists('planmyday_sc_search'))
							planmyday_show_layout(planmyday_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed", "style"=>planmyday_get_theme_option('search_style'))));
						
						?>
					</div>
				</div>
			</div>
			

			</div>
		</header>

		<section class="top_panel_image" <?php planmyday_show_layout($header_css); ?>>
			<div class="top_panel_image_hover"></div>
			<div class="top_panel_image_header">
				<?php echo ( planmyday_get_blog_type() == 'single' ) ? '' : '<h1 class="top_panel_image_title entry-title">' . strip_tags(planmyday_get_blog_title()) . '</h1>'; ?>
				<div class="breadcrumbs">
					<?php if (!is_404()) planmyday_show_breadcrumbs(); ?>
				</div>
			</div>
		</section>
		<?php
		planmyday_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => true,
				 'search' => true
			)
		);
	}
}
?>