<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_404_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_404_theme_setup', 1 );
	function planmyday_template_404_theme_setup() {
		planmyday_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_404_output' ) ) {
	function planmyday_template_404_output() {
		?>
		<div class="page404_bg margin_bottom_medium">
			<div class="page404_bg_overlay">
				<article class="post_item post_item_404">
					<div class="post_content">
						<h1 class="page_title"><?php esc_html_e( '404', 'planmyday' ); ?></h1>
						<h2 class="page_subtitle"><?php esc_html_e('The requested page cannot be found', 'planmyday'); ?></h2>
						<p class="page_description"><?php echo wp_kses_data( sprintf( __('TAKE A MOMENT AND DO A SEARCH BELOW OR START FROM <a href="%s">OUR HOMEPAGE</a>.', 'planmyday'), esc_url(home_url('/')) ) ); ?></p>
						<div class="page_search"><?php if (function_exists('planmyday_sc_search')) planmyday_show_layout(planmyday_sc_search(array('state'=>'fixed', 'title'=>__('Enter Keyword', 'planmyday')))); ?></div>
					</div>
				</article>
			</div>
		</div>
		<?php
	}
}
?>