<?php
// Get template args
extract(planmyday_template_get_args('counters'));

$show_all_counters = !empty($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';

// Views
if ($show_all_counters || planmyday_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php planmyday_show_layout($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo esc_attr( sprintf(__('Views - %s', 'planmyday'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo($post_data['post_views']); ?></span><?php if (planmyday_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Views', 'planmyday'); ?></<?php planmyday_show_layout($counters_tag); ?>>
	<?php
}

 
// Rating
$rating = $post_data['post_reviews_'.(planmyday_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || planmyday_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php planmyday_show_layout($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'planmyday'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo($rating); ?></span></<?php planmyday_show_layout($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters || planmyday_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	planmyday_enqueue_messages();
	$likes = isset($_COOKIE['planmyday_likes']) ? $_COOKIE['planmyday_likes'] : '';
	$allow = planmyday_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart-empty <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'planmyday') : esc_attr__('Dislike', 'planmyday'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'planmyday'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'planmyday'); ?>"><span class="post_counters_number"><?php echo($post_data['post_likes']); ?></span><?php if (planmyday_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Likes', 'planmyday'); ?></a>
	<?php
}
// Comments
if ($show_all_counters || planmyday_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'planmyday'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo($post_data['post_comments']); ?></span><?php if (planmyday_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Comments', 'planmyday'); ?></a>
	<?php 
}

// Edit page link
if (planmyday_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'planmyday' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && planmyday_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(planmyday_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(planmyday_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>