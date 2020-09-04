<?php
/**
 * The Header for our theme.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php
		// Add class 'scheme_xxx' into <html> because it used as context for the body classes!
		$body_scheme = planmyday_get_custom_option('body_scheme');
		if (empty($body_scheme) || planmyday_is_inherit_option($body_scheme)) $body_scheme = 'original';
		echo 'scheme_' . esc_attr($body_scheme); 
		?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class();?>>

	<?php do_action( 'before' ); ?>

	<?php if ( !planmyday_param_is_off(planmyday_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php get_template_part(planmyday_get_file_slug('sidebar_outer.php')); ?>

	<?php
		$body_style  = planmyday_get_custom_option('body_style');
		$class = $style = '';
		if (planmyday_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || planmyday_get_custom_option('bg_image_load')=='always')) {
			if (($img = planmyday_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', planmyday_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
			else if (($img = planmyday_get_custom_option('bg_pattern_custom')) != '')
				$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
			else if (($img = planmyday_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = planmyday_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.($img);
			if (($img = planmyday_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.($img).';';
		}
	?>

	<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>

		<?php
		$video_bg_show = planmyday_get_custom_option('show_video_bg')=='yes';
		$youtube = planmyday_get_custom_option('video_bg_youtube_code');
		$video   = planmyday_get_custom_option('video_bg_url');
		$overlay = planmyday_get_custom_option('video_bg_overlay')=='yes';
		if ($video_bg_show && (!empty($youtube) || !empty($video))) {
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$ext = planmyday_get_file_ext($video);
				if (empty($ext)) $ext = 'src';
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			$top_panel_style = planmyday_get_custom_option('top_panel_style');
			$top_panel_position = planmyday_get_custom_option('top_panel_position');
			$top_panel_scheme = planmyday_get_custom_option('top_panel_scheme');
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				planmyday_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				require get_template_directory().'/templates/headers/_parts/header-mobile.php';
			}

			// Slider
			require get_template_directory().'/templates/headers/_parts/slider.php';
			
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				planmyday_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				require get_template_directory().'/templates/headers/_parts/header-mobile.php';
			}

			// Top of page section: page title and breadcrumbs
			$show_title = planmyday_get_custom_option('show_page_title')=='yes';
			$show_navi = apply_filters('planmyday_filter_show_post_navi', false);
			$show_breadcrumbs = planmyday_get_custom_option('show_breadcrumbs')=='yes';
			if ($show_title || $show_breadcrumbs) {
				?>
				<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present'.  ($show_navi ? ' navi_present' : '') : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present_inner' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present_inner' : ''); ?>">
						<div class="content_wrap">
							<?php
							if ($show_title) {
								if ($show_navi) {
									?><div class="post_navi"><?php 
										previous_post_link( '<span class="post_navi_item post_navi_prev">%link</span>', '%title', true, '', 'product_cat' );
										next_post_link( '<span class="post_navi_item post_navi_next">%link</span>', '%title', true, '', 'product_cat' );
									?></div><?php
								} else {
									?><h1 class="page_title"><?php echo strip_tags(planmyday_get_blog_title()); ?></h1><?php
								}
							}
							if ($show_breadcrumbs) {
								?><div class="breadcrumbs"><?php if (!is_404()) planmyday_show_breadcrumbs(); ?></div><?php
							}
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(planmyday_get_custom_option('body_paddings')); ?>">

				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') planmyday_open_wrapper('<div class="content_wrap">');
				
				// Main content wrapper
				planmyday_open_wrapper('<div class="content">');
				?>