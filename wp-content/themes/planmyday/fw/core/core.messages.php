<?php
/**
 * Planmyday Framework: messages subsystem
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('planmyday_messages_theme_setup')) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_messages_theme_setup' );
	function planmyday_messages_theme_setup() {
		// Core messages strings
		add_filter('planmyday_filter_localize_script', 'planmyday_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('planmyday_get_error_msg')) {
	function planmyday_get_error_msg() {
		return planmyday_storage_get('error_msg');
	}
}

if (!function_exists('planmyday_set_error_msg')) {
	function planmyday_set_error_msg($msg) {
		$msg2 = planmyday_get_error_msg();
		planmyday_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('planmyday_get_success_msg')) {
	function planmyday_get_success_msg() {
		return planmyday_storage_get('success_msg');
	}
}

if (!function_exists('planmyday_set_success_msg')) {
	function planmyday_set_success_msg($msg) {
		$msg2 = planmyday_get_success_msg();
		planmyday_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('planmyday_get_notice_msg')) {
	function planmyday_get_notice_msg() {
		return planmyday_storage_get('notice_msg');
	}
}

if (!function_exists('planmyday_set_notice_msg')) {
	function planmyday_set_notice_msg($msg) {
		$msg2 = planmyday_get_notice_msg();
		planmyday_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('planmyday_set_system_message')) {
	function planmyday_set_system_message($msg, $status='info', $hdr='') {
		update_option(planmyday_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('planmyday_get_system_message')) {
	function planmyday_get_system_message($del=false) {
		$msg = get_option(planmyday_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			planmyday_del_system_message();
		return $msg;
	}
}

if (!function_exists('planmyday_del_system_message')) {
	function planmyday_del_system_message() {
		delete_option(planmyday_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('planmyday_messages_localize_script')) {
	//Handler of add_filter('planmyday_filter_localize_script', 'planmyday_messages_localize_script');
	function planmyday_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'planmyday'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'planmyday'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'planmyday'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'planmyday'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'planmyday'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'planmyday'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'planmyday'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'planmyday'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'planmyday'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'planmyday'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'planmyday'),
			'error_global'		=> esc_html__('Global error text', 'planmyday'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'planmyday'),
			'name_long'			=> esc_html__('Too long name', 'planmyday'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'planmyday'),
			'email_long'		=> esc_html__('Too long email address', 'planmyday'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'planmyday'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'planmyday'),
			'subject_long'		=> esc_html__('Too long subject', 'planmyday'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'planmyday'),
			'text_long'			=> esc_html__('Too long message text', 'planmyday'),
			'send_complete'		=> esc_html__("Send message complete!", 'planmyday'),
			'send_error'		=> esc_html__('Transmit failed!', 'planmyday'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'planmyday'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'planmyday'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'planmyday'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'planmyday'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'planmyday'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'planmyday'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'planmyday'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'planmyday'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'planmyday'),
			'editor_caption_close'	=> esc_html__('Close', 'planmyday')
			);
		return $vars;
	}
}
?>