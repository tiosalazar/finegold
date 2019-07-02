<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

planmyday_storage_set('settings', array(
	
	'less_compiler'		=> 'no',								// no|lessc|less|external - Compiler for the .less
																// lessc	- fast & low memory required, but .less-map, shadows & gradients not supprted
																// less		- slow, but support all features
																// external	- used if you have external .less compiler (like WinLess or Koala)
																// no		- don't use .less, all styles stored in the theme.styles.php
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_split'		=> false,								// If true - load each file into memory, split it (see below) and compile separate.
																// Else - compile each file without loading to memory
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',								// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

    'allow_profiler'    => false,                                // Allow to show theme profiler when 'debug mode' is on
    'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true 								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)
	)
);



// Default Theme Options
if ( !function_exists( 'planmyday_options_settings_theme_setup' ) ) {
	add_action( 'planmyday_action_before_init_theme', 'planmyday_options_settings_theme_setup', 2 );	// Priority 1 for add planmyday_filter handlers
	function planmyday_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'planmyday_options_reset');

		// Settings 
		$socials_type = planmyday_get_theme_setting('socials_type');
				
		// Prepare arrays 
		planmyday_storage_set('options_params', apply_filters('planmyday_filter_theme_options_params', array(
			'list_fonts'				=> array('$planmyday_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$planmyday_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$planmyday_get_list_socials' => ''),
			'list_icons' 				=> array('$planmyday_get_list_icons(true)' => ''),
			'list_posts_types' 			=> array('$planmyday_get_list_posts_types' => ''),
			'list_categories' 			=> array('$planmyday_get_list_categories' => ''),
			'list_menus'				=> array('$planmyday_get_list_menus(true)' => ''),
			'list_sidebars'				=> array('$planmyday_get_list_sidebars' => ''),
			'list_positions' 			=> array('$planmyday_get_list_sidebars_positions' => ''),
			'list_color_schemes'		=> array('$planmyday_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$planmyday_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$planmyday_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$planmyday_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$planmyday_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$planmyday_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$planmyday_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$planmyday_get_list_blog_counters' => ''),
			'list_menu_hovers' 			=> array('$planmyday_get_list_menu_hovers' => ''),
			'list_button_hovers'		=> array('$planmyday_get_list_button_hovers' => ''),
			'list_input_hovers'			=> array('$planmyday_get_list_input_hovers' => ''),
			'list_search_styles'		=> array('$planmyday_get_list_search_styles' => ''),
			'list_animations_in' 		=> array('$planmyday_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$planmyday_get_list_animations_out' => ''),
			'list_filters'				=> array('$planmyday_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$planmyday_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$planmyday_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$planmyday_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$planmyday_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$planmyday_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$planmyday_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$planmyday_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$planmyday_get_list_yesno' => ''),
			'list_on_off' 				=> array('$planmyday_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$planmyday_get_list_showhide' => ''),
			'list_sorting' 				=> array('$planmyday_get_list_sortings' => ''),
			'list_ordering' 			=> array('$planmyday_get_list_orderings' => ''),
			'list_locations' 			=> array('$planmyday_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		planmyday_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'planmyday'),
					"start" => "partitions",
					"override" => "category,services_group,post,page,custom",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'planmyday'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Select body style and color scheme for entire site. You can override this parameters on any page, post or category', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'planmyday'),
					"desc" => wp_kses_data( __('Select body style:', 'planmyday') )
								// . ' <br>' 
								// . wp_kses_data( __('<b>boxed</b> - if you want use background color and/or image', 'planmyday') )
								. ',<br>'
								. wp_kses_data( __('<b>wide</b> - page fill whole window with centered content', 'planmyday') )
								. (planmyday_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'planmyday') )
									: '')
								. (planmyday_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses_data( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'planmyday') )
									: ''),
					"info" => true,
					"override" => "category,services_group,post,page,custom",
					"std" => "wide",
					"options" => planmyday_get_options_param('list_body_styles'),
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'planmyday'),
					"desc" => wp_kses_data( __('Add paddings above and below the page content', 'planmyday') ),
					"override" => "post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the entire page', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'planmyday'),
					"desc" => wp_kses_data( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'planmyday'),
					"desc" => wp_kses_data( __('Color and image for the site background', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'planmyday'),
					"desc" => wp_kses_data( __("Use custom color and/or image as the site background", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'planmyday'),
					"desc" => wp_kses_data( __('Body background color',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'planmyday'),
					"desc" => wp_kses_data( __('Select theme background pattern (first case - without pattern)',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => planmyday_get_file_url('images/spacer.png'),
						1 => planmyday_get_file_url('images/bg/pattern_1.jpg'),
						2 => planmyday_get_file_url('images/bg/pattern_2.jpg'),
						3 => planmyday_get_file_url('images/bg/pattern_3.jpg'),
						4 => planmyday_get_file_url('images/bg/pattern_4.jpg'),
						5 => planmyday_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'planmyday'),
					"desc" => wp_kses_data( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'planmyday'),
					"desc" => wp_kses_data( __('Select theme background image (first case - without image)',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => planmyday_get_file_url('images/spacer.png'),
						1 => planmyday_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => planmyday_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => planmyday_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'planmyday'),
					"desc" => wp_kses_data( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'planmyday'),
					"desc" => wp_kses_data( __('Select custom image position',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'planmyday'),
					"desc" => wp_kses_data( __('Always load background images or only for boxed body style', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'planmyday'),
						'always' => esc_html__('Always', 'planmyday')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'planmyday'),
					"desc" => wp_kses_data( __('Parameters of the video, used as site background', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'planmyday'),
					"desc" => wp_kses_data( __("Show video as the site background", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'planmyday'),
					"desc" => wp_kses_data( __("Youtube code of video", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'planmyday'),
					"desc" => wp_kses_data( __("URL to video-file (uploaded on your site)", 'planmyday') ),
					"readonly" =>false,
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'planmyday'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'planmyday'),
															'update' => esc_html__( 'Select Video', 'planmyday')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'planmyday'),
					"desc" => wp_kses_data( __('Use overlay texture for the video background', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'planmyday'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'planmyday'),
					"desc" => wp_kses_data( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'planmyday'),
					"desc" => wp_kses_data( __('Select desired style of the page header', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "header_1",
					"options" => planmyday_get_options_param('list_header_styles'),
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'planmyday'),
					"desc" => wp_kses_data( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_1','header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'planmyday'),
					"desc" => wp_kses_data( __('Select position for the top panel with logo and main menu', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'planmyday'),
						'above' => esc_html__('Above slider', 'planmyday'),
						'below' => esc_html__('Below slider', 'planmyday'),
						'over'  => esc_html__('Over slider', 'planmyday')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the top panel', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'planmyday'),
					"desc" => wp_kses_data( __('Show post/page/category title', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'planmyday'),
					"desc" => wp_kses_data( __('Show path to current category (post, page)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'planmyday'),
					"desc" => wp_kses_data( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'planmyday') ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'planmyday'),
					"desc" => wp_kses_data( __('Select the Main menu style and position', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'planmyday'),
					"desc" => wp_kses_data( __('Select main menu for the current page',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => planmyday_get_options_param('list_menus'),
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'planmyday'),
					"desc" => wp_kses_data( __('Attach main menu to top of window then page scroll down', 'planmyday') ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'planmyday'), 
						"none"=>esc_html__("Don't fix menu position", 'planmyday')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_hover" => array( 
					"title" => esc_html__('Main menu hover effect', 'planmyday'),
					"desc" => wp_kses_data( __('Select hover effect for the main menu items', 'planmyday') ),
					"std" => "fade",
					"type" => "select",
					"options" => planmyday_get_options_param('list_menu_hovers')),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'planmyday'),
					"desc" => wp_kses_data( __('Select animation to show submenu ', 'planmyday') ),
					"std" => "fadeIn",
					"type" => "select",
					"options" => planmyday_get_options_param('list_animations_in')),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'planmyday'),
					"desc" => wp_kses_data( __('Select animation to hide submenu ', 'planmyday') ),
					"std" => "fadeOut",
					"type" => "select",
					"options" => planmyday_get_options_param('list_animations_out')),
		
		"menu_mobile" => array( 
					"title" => esc_html__('Main menu responsive', 'planmyday'),
					"desc" => wp_kses_data( __('Allow responsive version for the main menu if window width less then this value', 'planmyday') ),
					"std" => 1024,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'planmyday'),
					"desc" => wp_kses_data( __('Width for dropdown menus in main menu', 'planmyday') ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'planmyday'),
					"desc" => wp_kses_data( __("Select parts for the user's menu area", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'planmyday'),
					"desc" => wp_kses_data( __('Show user menu area on top of page', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'planmyday'),
					"desc" => wp_kses_data( __('Select user menu for the current page',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => planmyday_get_options_param('list_menus'),
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'planmyday'),
					"desc" => wp_kses_data( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'planmyday') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'planmyday'),
					"desc" => wp_kses_data( __('Show Login and Logout buttons in the user menu area', 'planmyday') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'planmyday'),
					"desc" => wp_kses_data( __('Show bookmarks selector in the user menu', 'planmyday') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'planmyday'),
					"desc" => wp_kses_data( __('Show Social icons in the user menu area', 'planmyday') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'planmyday'),
					"desc" => wp_kses_data( __("Select or upload logos for the site's header and select it position", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'planmyday'),
					"desc" => wp_kses_data( __('Main logo image', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'planmyday'),
					"desc" => wp_kses_data( __('Main logo image used on Retina display', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'planmyday'),
					"desc" => wp_kses_data( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'planmyday'),
					"desc" => wp_kses_data( __('Logo text - display it after logo image', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'planmyday'),
					"desc" => wp_kses_data( __('Height for the logo in the header area', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'planmyday'),
					"desc" => wp_kses_data( __('Top offset for the logo in the header area', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'planmyday'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Select parameters for main slider (you can override it in each category and page)', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'planmyday'),
					"desc" => wp_kses_data( __('Do you want to show slider on each page (post)', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'planmyday'),
					"desc" => wp_kses_data( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'planmyday'),
						"fullwide"=>esc_html__("Fullwide", 'planmyday'),
						"fullscreen"=>esc_html__("Fullscreen", 'planmyday')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'planmyday'),
					"desc" => wp_kses_data( __("Slider height (in pixels) - only if slider display with fixed height.", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'planmyday'),
					"desc" => wp_kses_data( __('What engine use to show slider?', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => planmyday_get_options_param('list_sliders'),
					"type" => "radio"),

		"slider_over_content" => array(
					"title" => esc_html__('Put content over slider',  'planmyday'),
					"desc" => wp_kses_data( __('Put content below on fixed layer over this slider',  'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "editor"),

		"slider_over_scheme" => array(
					"title" => esc_html__('Color scheme for content above', 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the content over the slider', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "dark",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'planmyday'),
					"desc" => wp_kses_data( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => planmyday_array_merge(array(0 => esc_html__('- Select category -', 'planmyday')), planmyday_get_options_param('list_categories')),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'planmyday'),
					"desc" => wp_kses_data( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'planmyday'),
					"desc" => wp_kses_data( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => planmyday_get_options_param('list_sorting'),
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'planmyday'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => planmyday_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'planmyday'),
					"desc" => wp_kses_data( __("Interval (in ms) for slides change in slider", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'planmyday'),
					"desc" => wp_kses_data( __("Choose pagination style for the slider", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'planmyday'),
						'yes'  => esc_html__('Dots', 'planmyday'), 
						'over' => esc_html__('Titles', 'planmyday')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'planmyday'),
					"desc" => wp_kses_data( __("Do you want to show post's title, reviews rating and description on slides in slider", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'planmyday'),
						'slide' => esc_html__('Slide', 'planmyday'), 
						'fixed' => esc_html__('Fixed', 'planmyday')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'planmyday'),
					"desc" => wp_kses_data( __("Do you want to show post's category on slides in slider", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'planmyday'),
					"desc" => wp_kses_data( __("Do you want to show post's reviews rating on slides in slider", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'planmyday'),
					"desc" => wp_kses_data( __("How many characters show in the post's description in slider. 0 - no descriptions", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'planmyday'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'planmyday'),
					"desc" => wp_kses_data( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'planmyday') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'planmyday'),
					"desc" => wp_kses_data( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'planmyday') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'planmyday'),
					"desc" => wp_kses_data( __('Show / Hide and select main sidebar', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'planmyday'),
					"desc" => wp_kses_data( __('Select position for the main sidebar or hide it',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "right",
					"options" => planmyday_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the main sidebar', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'planmyday'),
					"desc" => wp_kses_data( __('Select main sidebar content',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => planmyday_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"menu_side" => array(
					"title" => esc_html__('Select menu',  'planmyday'),
					"desc" => wp_kses_data( __('Select menu for the outer sidebar',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right'),
						'sidebar_outer_show_menu' => array('yes')
					),
					"std" => "default",
					"options" => planmyday_get_options_param('list_menus'),
					"type" => "select"),
		
		"sidebar_outer_show_widgets" => array( 
					"title" => esc_html__('Show Widgets', 'planmyday'),
					"desc" => wp_kses_data( __('Show Widgets in the outer sidebar', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_outer" => array( 
					"title" => esc_html__('Select outer sidebar',  'planmyday'),
					"desc" => wp_kses_data( __('Select outer sidebar content',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'sidebar_outer_show_widgets' => array('yes'),
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "sidebar_outer",
					"options" => planmyday_get_options_param('list_sidebars'),
					"type" => "select"),
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'planmyday'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'planmyday'),
					"desc" => wp_kses_data( __("Select components of the footer, set style and put the content for the user's footer area", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'planmyday'),
					"desc" => wp_kses_data( __('Select style for the footer sidebar or hide it', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the footer', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'planmyday'),
					"desc" => wp_kses_data( __('Select footer sidebar for the blog page',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => planmyday_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'planmyday'),
					"desc" => wp_kses_data( __('Select columns number for the footer sidebar',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'planmyday'),
					"desc" => wp_kses_data( __('Select parameters for Testimonials in the Footer', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'planmyday'),
					"desc" => wp_kses_data( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the testimonials area', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'planmyday'),
					"desc" => wp_kses_data( __('Number testimonials to show', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'planmyday'),
					"desc" => wp_kses_data( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'planmyday'),
					"desc" => wp_kses_data( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the twitter area', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'planmyday'),
					"desc" => wp_kses_data( __('Number twitter to show', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Select parameters for Google map (you can override it in each category and page)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'planmyday'),
					"desc" => wp_kses_data( __('Do you want to show Google map on each page (post)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'planmyday'),
					"desc" => wp_kses_data( __("Map height (default - in pixels, allows any CSS units of measure)", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'planmyday'),
					"desc" => wp_kses_data( __("Enter address to show on map center", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'planmyday'),
					"desc" => wp_kses_data( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'planmyday'),
					"desc" => wp_kses_data( __("Enter title to show on map center", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'planmyday'),
					"desc" => wp_kses_data( __("Enter description to show on map center", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'planmyday'),
					"desc" => wp_kses_data( __("Enter desired initial zoom for Google map", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'planmyday'),
					"desc" => wp_kses_data( __("Select style to show Google map", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => planmyday_get_options_param('list_gmap_styles'),
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'planmyday'),
					"desc" => wp_kses_data( __("Select or upload png-image with Google map marker", 'planmyday') ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'planmyday'),
					"desc" => wp_kses_data( __("Show/Hide contacts area in the footer", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'planmyday'),
					"desc" => wp_kses_data( __('Show contact information area in footer: site logo, contact info and large social icons', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the contacts area', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'planmyday'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

		'logo_footer_retina' => array(
					"title" => esc_html__('Logo image for footer for Retina', 'planmyday'),
					"desc" => wp_kses_data( __('Logo image in the footer (in the contacts area) used on Retina display', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'planmyday'),
					"desc" => wp_kses_data( __('Height for the logo in the footer area (in the contacts area)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'planmyday'),
					"desc" => wp_kses_data( __("Show/Hide copyright area in the footer", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'planmyday'),
					"desc" => wp_kses_data( __('Show area with copyright information, footer menu and small social icons in footer', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'planmyday'),
						'text' => esc_html__('Text', 'planmyday'),
						'menu' => esc_html__('Text and menu', 'planmyday'),
						'socials' => esc_html__('Text and Social icons', 'planmyday')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'planmyday'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the copyright area', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => planmyday_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'planmyday'),
					"desc" => wp_kses_data( __('Select footer menu for the current page',  'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => planmyday_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'planmyday'),
					"desc" => wp_kses_data( __("Copyright text to show in footer area (bottom of site)", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "Axiom &copy; 2018 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'planmyday'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Animation parameters and responsive layouts for the small screens', 'planmyday') ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'planmyday'),
					"desc" => wp_kses_data( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'planmyday') ),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'planmyday'),
					"desc" => wp_kses_data( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'planmyday') ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'planmyday'),
					"desc" => wp_kses_data( __('Do you want use extended animations effects on your site?', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'animation_on_mobile' => array(
					"title" => esc_html__('Allow CSS animations on mobile', 'planmyday'),
					"desc" => wp_kses_data( __('Do you allow extended animations effects on mobile devices?', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"button_hover" => array( 
					"title" => esc_html__("Buttons hover", 'planmyday'),
					"desc" => wp_kses_data( __("Select hover effect for all theme's buttons (and buttons from the thirdparty plugins if possible)", 'planmyday') ),
					"std" => "fade",
					"type" => "select",
					"options" => planmyday_get_options_param('list_button_hovers')),

		"input_hover" => array( 
					"title" => esc_html__("Input fileds style", 'planmyday'),
					"desc" => wp_kses_data( __("Select style for all theme's input fields (and fields from the thirdparty plugins if possible)", 'planmyday') ),
					"std" => "default",
					"type" => "select",
					"options" => planmyday_get_options_param('list_input_hovers')),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'planmyday'),
					"desc" => wp_kses_data( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'planmyday') ),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'planmyday'),
					"desc" => wp_kses_data( __('Do you want use responsive layouts on small screen or still use main layout?', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"page_preloader" => array( 
					"title" => esc_html__("Show page preloader", 'planmyday'),
					"desc" => wp_kses_data( __("Select one of predefined styles for the page preloader or upload preloader image", 'planmyday') ),
					"std" => "none",
					"type" => "select",
					"options" => array(
						'none'   => esc_html__('Hide preloader', 'planmyday'),
						'circle' => esc_html__('Circle', 'planmyday'),
						'square' => esc_html__('Square', 'planmyday'),
						'custom' => esc_html__('Custom', 'planmyday'),
					)),
        'privacy_text' => array(
                    "title" => esc_html__("Text with Privacy Policy link", 'planmyday'),
                    "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'planmyday') ),
                    "std"   => wp_kses_post( __( 'I agree that my submitted data is being collected and stored.', 'planmyday') ),
                    "type"  => "text"
                     ),


            'page_preloader_image' => array(
					"title" => esc_html__('Upload preloader image',  'planmyday'),
					"desc" => wp_kses_data( __('Upload animated GIF to use it as page preloader',  'planmyday') ),
					"dependency" => array(
						'page_preloader' => array('custom')
					),
					"std" => "",
					"type" => "media"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Specify additional parameters, used to load Google fonts', 'planmyday') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'planmyday'),
					"desc" => wp_kses_data( __('Select subset, included into used Google fonts', 'planmyday') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'planmyday'),
						'latin-ext' => esc_html__('Latin Extended', 'planmyday'),
						'greek' => esc_html__('Greek', 'planmyday'),
						'greek-ext' => esc_html__('Greek Extended', 'planmyday'),
						'cyrillic' => esc_html__('Cyrillic', 'planmyday'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'planmyday'),
						'vietnamese' => esc_html__('Vietnamese', 'planmyday')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'planmyday'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'planmyday'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Select desired blog streampage parameters (you can override it in each category)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'planmyday'),
					"desc" => wp_kses_data( __('Select desired blog style', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "excerpt",
					"options" => planmyday_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'planmyday'),
					"desc" => wp_kses_data( __('Select desired hover style (only for Blog style = Portfolio)', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => planmyday_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'planmyday'),
					"desc" => wp_kses_data( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => planmyday_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'planmyday'),
					"desc" => wp_kses_data( __('Select article display method: boxed or stretch', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "stretch",
					"options" => planmyday_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'planmyday'),
					"desc" => wp_kses_data( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => planmyday_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'planmyday'),
					"desc" => wp_kses_data( __('What taxonomy use for filter buttons', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => planmyday_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'planmyday'),
					"desc" => wp_kses_data( __('Select the desired sorting method for posts', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "date",
					"options" => planmyday_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'planmyday'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "desc",
					"options" => planmyday_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'planmyday'),
					"desc" => wp_kses_data( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'planmyday'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'planmyday'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'planmyday'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'planmyday'),
					"desc" => wp_kses_data( __('Select desired style for single page', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "single-standard",
					"options" => planmyday_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'planmyday'),
					"desc" => wp_kses_data( __('Select icon for output before post/category name in some layouts', 'planmyday') ),
					"override" => "services_group,post,page,custom",
					"std" => "",
					"options" => planmyday_get_options_param('list_icons'),
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'planmyday'),
					"override" => "page,post",
					"desc" => wp_kses_data( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'planmyday') ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => planmyday_get_options_param('list_alter_sizes')
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'planmyday'),
					"desc" => wp_kses_data( __("Show featured image (if selected) before post content on single pages", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'planmyday'),
					"desc" => wp_kses_data( __('Show area with post title on single pages', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'planmyday'),
					"desc" => wp_kses_data( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'planmyday'),
					"desc" => wp_kses_data( __('Show area with post info on single pages', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'planmyday'),
					"desc" => wp_kses_data( __('Show text before "Read more" tag on single pages', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'planmyday'),
					"desc" => wp_kses_data( __("Show post author information block on single post page", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'planmyday'),
					"desc" => wp_kses_data( __("Show tags block on single post page", 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
        "show_post_related" => array(
                    "title" => esc_html__('Show related posts',  'planmyday'),
                    "desc" => wp_kses_data( __("Show related posts block on single post page", 'planmyday') ),
                    "override" => "category,services_group,post,custom",
                    "std" => "no",
                    "options" => planmyday_get_options_param('list_yes_no'),
                    "type" => "hidden"),    //switch
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'planmyday'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Select excluded categories, substitute parameters, etc.', 'planmyday') ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'planmyday'),
					"desc" => wp_kses_data( __('Select categories, which posts are exclude from blog page', 'planmyday') ),
					"std" => "",
					"options" => planmyday_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'planmyday'),
					"desc" => wp_kses_data( __('Select type of the pagination on blog streampages', 'planmyday') ),
					"std" => "pages",
					"override" => "category,services_group,page,custom",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'planmyday'),
						'slider'   => esc_html__('Slider with page numbers', 'planmyday'),
						'viewmore' => esc_html__('"View more" button', 'planmyday'),
						'infinite' => esc_html__('Infinite scroll', 'planmyday')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'planmyday'),
					"desc" => wp_kses_data( __('Select counters, displayed near the post title', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "views",
					"options" => planmyday_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'planmyday'),
					"desc" => wp_kses_data( __('What category display in announce block (over posts thumb) - original or nearest parental', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'planmyday'),
						'original' => esc_html__("Original post's category", 'planmyday')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'planmyday'),
					"desc" => wp_kses_data( __('Show post date after N days (before - show post age)', 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'planmyday'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'planmyday'),
					"desc" => wp_kses_data( __('Set up parameters to show images, galleries, audio and video posts', 'planmyday') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'planmyday'),
					"desc" => wp_kses_data( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'planmyday') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'planmyday'), 
						"2" => esc_html__("Retina", 'planmyday')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'planmyday'),
					"desc" => wp_kses_data( __('Quality (1-100) to save cropped images', 'planmyday') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'planmyday'),
					"desc" => wp_kses_data( __('Substitute standard Wordpress gallery with our slider on the single pages', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'planmyday'),
					"desc" => wp_kses_data( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'planmyday'),
					"desc" => wp_kses_data( __('Maximum images number from gallery into slider', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'planmyday'),
					"desc" => wp_kses_data( __('Select engine to show popup windows with images and galleries', 'planmyday') ),
					"std" => "magnific",
					"options" => planmyday_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'planmyday'),
					"desc" => wp_kses_data( __('Substitute audio tag with source from soundcloud to embed player', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'planmyday'),
					"desc" => wp_kses_data( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'planmyday') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'planmyday'),
					"desc" => wp_kses_data( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'planmyday'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page,custom",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'planmyday'),
					"desc" => wp_kses_data( __("Social networks list for site footer and Social widget", 'planmyday') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'planmyday'),
					"desc" => wp_kses_data( __('Select icon and write URL to your profile in desired social networks.',  'planmyday') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? planmyday_get_options_param('list_socials') : planmyday_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'planmyday'),
					"desc" => wp_kses_data( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'planmyday'),
					"desc" => wp_kses_data( __("Show social share buttons block", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'planmyday'),
						'vertical'	=> esc_html__('Vertical', 'planmyday'),
						'horizontal'=> esc_html__('Horizontal', 'planmyday')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'planmyday'),
					"desc" => wp_kses_data( __("Show share counters after social buttons", 'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'planmyday'),
					"desc" => wp_kses_data( __('Caption for the block with social share buttons',  'planmyday') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'planmyday'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'planmyday'),
					"desc" => wp_kses_data( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'planmyday') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? planmyday_get_options_param('list_socials') : planmyday_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'planmyday'),
					"desc" => wp_kses_data( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'planmyday') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'planmyday'),
					"desc" => wp_kses_data( __('Your login (username) in Twitter',  'planmyday') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'planmyday'),
					"desc" => wp_kses_data( __('Twitter API Consumer key',  'planmyday') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'planmyday'),
					"desc" => wp_kses_data( __('Twitter API Consumer secret',  'planmyday') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'planmyday'),
					"desc" => wp_kses_data( __('Twitter API Token key',  'planmyday') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'planmyday'),
					"desc" => wp_kses_data( __('Twitter API Token secret',  'planmyday') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"info_socials_4" => array(
					"title" => esc_html__('Google API Keys', 'planmyday'),
					"desc" => wp_kses_data( __('API Keys for some Web services', 'planmyday') ),
					"type" => "info"),
		'api_google' => array(
					"title" => esc_html__('Google API Key for browsers', 'planmyday'),
					"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'planmyday') ),
					"std" => "",
					"type" => "text"),
		
		"info_socials_5" => array(
					"title" => esc_html__('Login via Socials', 'planmyday'),
					"desc" => wp_kses_data( __('Settings for the Login via Social networks', 'planmyday') ),
					"type" => "info"),
		
		"social_login" => array(
					"title" => esc_html__('Shortcode or any HTML/JS code',  'planmyday'),
					"desc" => wp_kses_data( __('Specify shortcode from your Social Login Plugin or any HTML/JS code to make Social Login section',  'planmyday') ),
					"std" => "",
					"type" => "textarea"),
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'planmyday'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'planmyday'),
					"desc" => wp_kses_data( __('Company address, phones and e-mail', 'planmyday') ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'planmyday'),
					"desc" => wp_kses_data( __('String with contact info in the left side of the site header', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'planmyday'),
					"desc" => wp_kses_data( __('String with open hours in the site header', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'planmyday'),
					"desc" => wp_kses_data( __('E-mail for send contact form and user registration data', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'planmyday'),
					"desc" => wp_kses_data( __('Company country, post code and city', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'planmyday'),
					"desc" => wp_kses_data( __('Street and house number', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'planmyday'),
					"desc" => wp_kses_data( __('Phone number', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'planmyday'),
					"desc" => wp_kses_data( __('Fax number', 'planmyday') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'planmyday'),
					"desc" => wp_kses_data( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'planmyday') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'planmyday'),
					"desc" => wp_kses_data( __("Message's maxlength in the contact form shortcode", 'planmyday') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'planmyday'),
					"desc" => wp_kses_data( __("Message's maxlength in the comments form", 'planmyday') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'planmyday'),
					"desc" => wp_kses_data( __('What function use to send mail: the built-in Wordpress wp_mail or standard PHP mail function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'planmyday') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'planmyday'),
					"desc" => wp_kses_data( __("What function use to send mail? Attention! Only wp_mail support attachment in the mail!", 'planmyday') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'planmyday'),
						'mail' => esc_html__('PHP mail', 'planmyday')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'planmyday'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'planmyday'),
					"desc" => wp_kses_data( __('Enable/disable AJAX search and output settings for it', 'planmyday') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'planmyday'),
					"desc" => wp_kses_data( __('Show search field in the top area and side menus', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"search_style" => array( 
					"title" => esc_html__('Select search style', 'planmyday'),
					"desc" => wp_kses_data( __('Select style for the search field', 'planmyday') ),
					"std" => "default",
					"type" => "select",
					"options" => planmyday_get_options_param('list_search_styles')),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'planmyday'),
					"desc" => wp_kses_data( __('Use incremental AJAX search for the search field in top of page', 'planmyday') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand')
					),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'planmyday'),
					"desc" => wp_kses_data( __('The minimum length of the search string',  'planmyday') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'planmyday'),
					"desc" => wp_kses_data( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'planmyday') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'planmyday'),
					"desc" => wp_kses_data( __('Select post types, what will be include in search results. If not selected - use all types.', 'planmyday') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => planmyday_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'planmyday'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __('Number of the posts to show in search results',  'planmyday') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'planmyday'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's thumbnail in the search results", 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'planmyday'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's publish date in the search results", 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'planmyday'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's author in the search results", 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'planmyday'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's counters (views, comments, likes) in the search results", 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'planmyday'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'planmyday'),
					"desc" => wp_kses_data( __('Basic theme functionality settings', 'planmyday') ),
					"type" => "info"),

		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'planmyday'),
					"desc" => wp_kses_data( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'planmyday') ),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'planmyday'),
					"desc" => wp_kses_data( __("Allow authors to edit their posts in frontend area", 'planmyday') ),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'planmyday'),
					"desc" => wp_kses_data( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'planmyday') ),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'planmyday'),
					"desc" => wp_kses_data( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'planmyday'),
					"desc" => wp_kses_data( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'planmyday'),
					"desc" => wp_kses_data( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'planmyday') ),
					"std" => "yes",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'planmyday'),
					"desc" => wp_kses_data( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'planmyday') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'planmyday'),
					"desc" => wp_kses_data( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'planmyday') ),
					"std" => "no",
					"options" => planmyday_get_options_param('list_yes_no'),
					"type" => "switch")
		));

	}
}


// Update all temporary vars (start with $planmyday_) in the Theme Options with actual lists
if ( !function_exists( 'planmyday_options_settings_theme_setup2' ) ) {
	add_action( 'planmyday_action_after_init_theme', 'planmyday_options_settings_theme_setup2', 1 );
	function planmyday_options_settings_theme_setup2() {
		if (planmyday_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = planmyday_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$planmyday_';
				$prefix_len = planmyday_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (planmyday_substr($k1, 0, $prefix_len) == $prefix || planmyday_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = planmyday_substr(planmyday_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								$inherit = strpos($list_func, '(true)')!==false;
								$list_func = str_replace('(true)', '', $list_func);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = planmyday_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = planmyday_array_merge($tmp[$k]['options'], $list_func($inherit));
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'planmyday'), $list_func));
								}
							}
						}
					}
				}
				planmyday_storage_set('options', $tmp);
			}
		}
	}
}



// Reset old Theme Options while theme first run
if ( !function_exists( 'planmyday_options_reset' ) ) {
	//Handler of add_action('after_switch_theme', 'planmyday_options_reset');
	function planmyday_options_reset($clear=true) {
		$theme_slug = str_replace(' ', '_', trim(planmyday_strtolower(get_stylesheet())));
		$option_name = planmyday_storage_get('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query( $wpdb->prepare(
										"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
										planmyday_storage_get('options_prefix').'_%'
										)
							);
				// Add Templates Options
				$txt = planmyday_fgc(planmyday_storage_get('demo_data_url') . 'default/templates_options.txt');
				if (!empty($txt)) {
					$data = planmyday_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = planmyday_replace_uploads_url(planmyday_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>