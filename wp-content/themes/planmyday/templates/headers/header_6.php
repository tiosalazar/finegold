<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_header_6_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_header_6_theme_setup', 1 );
	function planmyday_template_header_6_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'header_6',
			'mode'   => 'header',
			'title'  => esc_html__('Header 6', 'planmyday'),
			'icon'   => planmyday_get_file_url('templates/headers/images/6.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_header_6_output' ) ) {
	function planmyday_template_header_6_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_6 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_6 top_panel_position_<?php echo esc_attr(planmyday_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle" <?php planmyday_show_layout($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php planmyday_show_logo(true, true); ?>
					</div>
					<div class="menu_main_wrap">
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
						if (planmyday_get_custom_option('show_search')=='yes') 
							planmyday_show_layout(planmyday_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed", "style"=>planmyday_get_theme_option('search_style'))));
						?>
					</div>
				</div>
			</div>

			</div>
		</header>

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