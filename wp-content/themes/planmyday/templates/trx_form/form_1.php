<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'planmyday_template_form_1_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_template_form_1_theme_setup', 1 );
	function planmyday_template_form_1_theme_setup() {
		planmyday_add_template(array(
			'layout' => 'form_1',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 1', 'planmyday')
			));
	}
}

// Template output
if ( !function_exists( 'planmyday_template_form_1_output' ) ) {
	function planmyday_template_form_1_output($post_options, $post_data) {

		$form_style = planmyday_get_theme_option('input_hover');
		$address_1 = planmyday_get_theme_option('contact_address_1');
		$address_2 = planmyday_get_theme_option('contact_address_2');
		$phone = planmyday_get_theme_option('contact_phone');
		$fax = planmyday_get_theme_option('contact_fax');
		$email = planmyday_get_theme_option('contact_email');
		$open_hours = planmyday_get_theme_option('contact_open_hours');
		
		?><div class="sc_columns"><?php

			// Form fields
			?><div class="sc_form_fields">
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> 
					class="sc_input_hover_<?php echo esc_attr($form_style); ?>"
					data-formtype="<?php echo esc_attr($post_options['layout']); ?>" 
					method="post" 
					action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php planmyday_sc_form_show_fields($post_options['fields']); ?>
					<div class="columns_wrap sc_columns columns_nofluid sc_columns_count_3">
							<div class="sc_form_item sc_form_field label_over column-1_2"><input id="sc_form_username" type="text" name="username"<?php if ($form_style=='default') echo ' placeholder="'.esc_attr__('Name *', 'planmyday').'"'; ?> aria-required="true"><?php
								if ($form_style!='default') { 
									?><label class="required" for="sc_form_username"><?php
										if ($form_style == 'path') {
											?><svg class="sc_form_graphic" preserveAspectRatio="none" viewBox="0 0 404 77" height="100%" width="100%"><path d="m0,0l404,0l0,77l-404,0l0,-77z"></svg><?php
										} else if ($form_style == 'iconed') {
											?><i class="sc_form_label_icon icon-user"></i><?php
										}
										?><span class="sc_form_label_content" data-content="<?php esc_html_e('Name', 'planmyday'); ?>"><?php esc_html_e('Name', 'planmyday'); ?></span><?php
									?></label><?php
								}
							?></div><div class="sc_form_item sc_form_field label_over column-1_2"><input id="sc_form_email" type="text" name="email"<?php if ($form_style=='default') echo ' placeholder="'.esc_attr__('E-mail *', 'planmyday').'"'; ?> aria-required="true"><?php
								if ($form_style!='default') { 
									?><label class="required" for="sc_form_email"><?php
										if ($form_style == 'path') {
											?><svg class="sc_form_graphic" preserveAspectRatio="none" viewBox="0 0 404 77" height="100%" width="100%"><path d="m0,0l404,0l0,77l-404,0l0,-77z"></svg><?php
										} else if ($form_style == 'iconed') {
											?><i class="sc_form_label_icon icon-mail-empty"></i><?php
										}
										?><span class="sc_form_label_content" data-content="<?php esc_html_e('E-mail', 'planmyday'); ?>"><?php esc_html_e('E-mail', 'planmyday'); ?></span><?php
									?></label><?php
								}
							?></div><div class="sc_form_item sc_form_field label_over column-1"><input id="sc_form_subj" type="text" name="subject"<?php if ($form_style=='default') echo ' placeholder="'.esc_attr__('Subject', 'planmyday').'"'; ?> aria-required="true"><?php
								if ($form_style!='default') { 
									?><label class="required" for="sc_form_subj"><?php
										if ($form_style == 'path') {
											?><svg class="sc_form_graphic" preserveAspectRatio="none" viewBox="0 0 404 77" height="100%" width="100%"><path d="m0,0l404,0l0,77l-404,0l0,-77z"></svg><?php
										} else if ($form_style == 'iconed') {
											?><i class="sc_form_label_icon icon-menu"></i><?php
										}
										?><span class="sc_form_label_content" data-content="<?php esc_html_e('Subject', 'planmyday'); ?>"><?php esc_html_e('Subject', 'planmyday'); ?></span><?php
									?></label><?php
								}
							?></div><div class="sc_form_item sc_form_message column-1"><textarea id="sc_form_message" name="message"<?php if ($form_style=='default') echo ' placeholder="'.esc_attr__('Message', 'planmyday').'"'; ?> aria-required="true"></textarea><?php
								if ($form_style!='default') { 
									?><label class="required" for="sc_form_message"><?php 
										if ($form_style == 'path') {
											?><svg class="sc_form_graphic" preserveAspectRatio="none" viewBox="0 0 404 77" height="100%" width="100%"><path d="m0,0l404,0l0,77l-404,0l0,-77z"></svg><?php
										} else if ($form_style == 'iconed') {
											?><i class="sc_form_label_icon icon-feather"></i><?php
										}
										?><span class="sc_form_label_content" data-content="<?php esc_html_e('Message', 'planmyday'); ?>"><?php esc_html_e('Message', 'planmyday'); ?></span><?php
									?></label><?php
								}
							?></div>

                        <?php
                        $privacy = trx_utils_get_privacy_text();
                        if (!empty($privacy)) {
                            ?><div class="sc_form_item sc_form_field_checkbox"><?php
                            ?><input type="checkbox" id="i_agree_privacy_policy_sc_form_1" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                            <label for="i_agree_privacy_policy_sc_form_1"><?php trx_utils_show_layout($privacy); ?></label>
                            </div><?php
                        }
                        ?><div class="sc_form_item sc_form_button"><?php
                            ?><button class="sc_button sc_button_round sc_button_style_border sc_button_size_large sc_button_iconed icon-right-small" <?php
                            if (!empty($privacy)) echo ' disabled="disabled"'
                            ?> ><?php
                                if (!empty($args['button_caption']))
                                    echo esc_html($args['button_caption']);
                                else
                                    esc_html_e('Send', 'planmyday');
                                ?></button>
                        </div>
                        <div class="result sc_infobox"></div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>