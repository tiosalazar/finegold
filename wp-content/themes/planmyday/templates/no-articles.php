<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_no_articles_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_no_articles_theme_setup', 1 );
	function planmyday_template_no_articles_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'planmyday')
		));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_no_articles_output' ) ) {
	function planmyday_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'planmyday'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'planmyday' ); ?></p>
				<p><?php echo wp_kses_data( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'planmyday'), esc_url(home_url('/')), get_bloginfo()) ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'planmyday'); ?></p>
				<?php planmyday_show_layout(planmyday_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>