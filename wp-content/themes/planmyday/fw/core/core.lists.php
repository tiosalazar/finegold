<?php
/**
 * Planmyday Framework: return lists
 *
 * @package planmyday
 * @since planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'planmyday_get_list_styles' ) ) {
	function planmyday_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'planmyday'), $i);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'planmyday_get_list_margins' ) ) {
	function planmyday_get_list_margins($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'planmyday'),
				'tiny'		=> esc_html__('Tiny',		'planmyday'),
				'small'		=> esc_html__('Small',		'planmyday'),
				'medium'	=> esc_html__('Medium',		'planmyday'),
				'large'		=> esc_html__('Large',		'planmyday'),
				'huge'		=> esc_html__('Huge',		'planmyday'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'planmyday'),
				'small-'	=> esc_html__('Small (negative)',	'planmyday'),
				'medium-'	=> esc_html__('Medium (negative)',	'planmyday'),
				'large-'	=> esc_html__('Large (negative)',	'planmyday'),
				'huge-'		=> esc_html__('Huge (negative)',	'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_margins', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'planmyday_get_list_line_styles' ) ) {
	function planmyday_get_list_line_styles($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'planmyday'),
				'dashed'=> esc_html__('Dashed', 'planmyday'),
				'dotted'=> esc_html__('Dotted', 'planmyday'),
				'double'=> esc_html__('Double', 'planmyday'),
				'image'	=> esc_html__('Image', 'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_line_styles', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'planmyday_get_list_animations' ) ) {
	function planmyday_get_list_animations($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'planmyday'),
				'bounce'		=> esc_html__('Bounce',		'planmyday'),
				'elastic'		=> esc_html__('Elastic',	'planmyday'),
				'flash'			=> esc_html__('Flash',		'planmyday'),
				'flip'			=> esc_html__('Flip',		'planmyday'),
				'pulse'			=> esc_html__('Pulse',		'planmyday'),
				'rubberBand'	=> esc_html__('Rubber Band','planmyday'),
				'shake'			=> esc_html__('Shake',		'planmyday'),
				'swing'			=> esc_html__('Swing',		'planmyday'),
				'tada'			=> esc_html__('Tada',		'planmyday'),
				'wobble'		=> esc_html__('Wobble',		'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_animations', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'planmyday_get_list_animations_in' ) ) {
	function planmyday_get_list_animations_in($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'planmyday'),
				'fadeIn'			=> esc_html__('Fade In',			'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_animations_in', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'planmyday_get_list_animations_out' ) ) {
	function planmyday_get_list_animations_out($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'planmyday'),
				'fadeOut'			=> esc_html__('Fade Out',			'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_animations_out', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('planmyday_get_animation_classes')) {
	function planmyday_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return planmyday_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!planmyday_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'planmyday_get_list_menu_hovers' ) ) {
	function planmyday_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'planmyday'),
				);
			$list = apply_filters('planmyday_filter_list_menu_hovers', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the button's hover effects
if ( !function_exists( 'planmyday_get_list_button_hovers' ) ) {
	function planmyday_get_list_button_hovers($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_button_hovers'))=='') {
			$list = array(
				'default'		=> esc_html__('Default',			'planmyday'),
				'fade'			=> esc_html__('Fade',				'planmyday'),
				'slide_left'	=> esc_html__('Slide from Left',	'planmyday'),
				'slide_top'		=> esc_html__('Slide from Top',		'planmyday'),
				'arrow'			=> esc_html__('Arrow',				'planmyday'),
				);
			$list = apply_filters('planmyday_filter_list_button_hovers', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_button_hovers', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the input field's hover effects
if ( !function_exists( 'planmyday_get_list_input_hovers' ) ) {
	function planmyday_get_list_input_hovers($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'planmyday'),
				'accent'	=> esc_html__('Accented',	'planmyday'),
				'path'		=> esc_html__('Path',		'planmyday'),
				'jump'		=> esc_html__('Jump',		'planmyday'),
				'underline'	=> esc_html__('Underline',	'planmyday'),
				'iconed'	=> esc_html__('Iconed',		'planmyday'),
				);
			$list = apply_filters('planmyday_filter_list_input_hovers', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'planmyday_get_list_search_styles' ) ) {
	function planmyday_get_list_search_styles($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'planmyday'),
				'fullscreen'=> esc_html__('Fullscreen',	'planmyday'),
				'slide'		=> esc_html__('Slide',		'planmyday'),
				'expand'	=> esc_html__('Expand',		'planmyday'),
				);
			$list = apply_filters('planmyday_filter_list_search_styles', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'planmyday_get_list_categories' ) ) {
	function planmyday_get_list_categories($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'planmyday_get_list_terms' ) ) {
	function planmyday_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = planmyday_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = planmyday_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'planmyday_get_list_posts_types' ) ) {
	function planmyday_get_list_posts_types($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('planmyday_filter_list_post_types', array());
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'planmyday_get_list_posts' ) ) {
	function planmyday_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = planmyday_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'planmyday');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set($hash, $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'planmyday_get_list_pages' ) ) {
	function planmyday_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return planmyday_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'planmyday_get_list_users' ) ) {
	function planmyday_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = planmyday_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'planmyday');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_users', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'planmyday_get_list_sliders' ) ) {
	function planmyday_get_list_sliders($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'planmyday')
			);
			$list = apply_filters('planmyday_filter_list_sliders', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'planmyday_get_list_slider_controls' ) ) {
	function planmyday_get_list_slider_controls($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'planmyday'),
				'side'		=> esc_html__('Side', 'planmyday'),
				'bottom'	=> esc_html__('Bottom', 'planmyday'),
				'pagination'=> esc_html__('Pagination', 'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_slider_controls', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'planmyday_get_slider_controls_classes' ) ) {
	function planmyday_get_slider_controls_classes($controls) {
		if (planmyday_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'planmyday_get_list_popup_engines' ) ) {
	function planmyday_get_list_popup_engines($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'planmyday'),
				"magnific"	=> esc_html__("Magnific popup", 'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_popup_engines', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'planmyday_get_list_menus' ) ) {
	function planmyday_get_list_menus($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'planmyday');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'planmyday_get_list_sidebars' ) ) {
	function planmyday_get_list_sidebars($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_sidebars'))=='') {
			if (($list = planmyday_storage_get('registered_sidebars'))=='') $list = array();
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'planmyday_get_list_sidebars_positions' ) ) {
	function planmyday_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'planmyday'),
				'left'  => esc_html__('Left',  'planmyday'),
				'right' => esc_html__('Right', 'planmyday')
				);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'planmyday_get_sidebar_class' ) ) {
	function planmyday_get_sidebar_class() {
		$sb_main = planmyday_get_custom_option('show_sidebar_main');
		$sb_outer = planmyday_get_custom_option('show_sidebar_outer');
		return (planmyday_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (planmyday_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_body_styles' ) ) {
	function planmyday_get_list_body_styles($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_body_styles'))=='') {
			$list = array(
				// 'boxed'	=> esc_html__('Boxed',		'planmyday'),
				'wide'	=> esc_html__('Wide',		'planmyday')
				);
			if (planmyday_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'planmyday');
				$list['fullscreen']	= esc_html__('Fullscreen',	'planmyday');
			}
			$list = apply_filters('planmyday_filter_list_body_styles', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'planmyday_get_list_templates' ) ) {
	function planmyday_get_list_templates($mode='') {
		if (($list = planmyday_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = planmyday_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: planmyday_strtoproper($v['layout'])
										);
				}
			}
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_templates_blog' ) ) {
	function planmyday_get_list_templates_blog($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_templates_blog'))=='') {
			$list = planmyday_get_list_templates('blog');
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_templates_blogger' ) ) {
	function planmyday_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_templates_blogger'))=='') {
			$list = planmyday_array_merge(planmyday_get_list_templates('blogger'), planmyday_get_list_templates('blog'));
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_templates_single' ) ) {
	function planmyday_get_list_templates_single($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_templates_single'))=='') {
			$list = planmyday_get_list_templates('single');
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_templates_header' ) ) {
	function planmyday_get_list_templates_header($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_templates_header'))=='') {
			$list = planmyday_get_list_templates('header');
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_templates_forms' ) ) {
	function planmyday_get_list_templates_forms($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_templates_forms'))=='') {
			$list = planmyday_get_list_templates('forms');
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_article_styles' ) ) {
	function planmyday_get_list_article_styles($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'planmyday'),
				"stretch" => esc_html__('Stretch', 'planmyday')
				);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'planmyday_get_list_post_formats_filters' ) ) {
	function planmyday_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'planmyday'),
				"thumbs"  => esc_html__('With thumbs', 'planmyday'),
				"reviews" => esc_html__('With reviews', 'planmyday'),
				"video"   => esc_html__('With videos', 'planmyday'),
				"audio"   => esc_html__('With audios', 'planmyday'),
				"gallery" => esc_html__('With galleries', 'planmyday')
				);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'planmyday_get_list_portfolio_filters' ) ) {
	function planmyday_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'planmyday'),
				"tags"		=> esc_html__('Tags', 'planmyday'),
				"categories"=> esc_html__('Categories', 'planmyday')
				);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_hovers' ) ) {
	function planmyday_get_list_hovers($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'planmyday');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'planmyday');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'planmyday');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'planmyday');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'planmyday');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'planmyday');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'planmyday');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'planmyday');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'planmyday');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'planmyday');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'planmyday');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'planmyday');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'planmyday');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'planmyday');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'planmyday');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'planmyday');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'planmyday');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'planmyday');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'planmyday');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'planmyday');
			$list['square effect1']  = esc_html__('Square Effect 1',  'planmyday');
			$list['square effect2']  = esc_html__('Square Effect 2',  'planmyday');
			$list['square effect3']  = esc_html__('Square Effect 3',  'planmyday');
			$list['square effect5']  = esc_html__('Square Effect 5',  'planmyday');
			$list['square effect6']  = esc_html__('Square Effect 6',  'planmyday');
			$list['square effect7']  = esc_html__('Square Effect 7',  'planmyday');
			$list['square effect8']  = esc_html__('Square Effect 8',  'planmyday');
			$list['square effect9']  = esc_html__('Square Effect 9',  'planmyday');
			$list['square effect10'] = esc_html__('Square Effect 10',  'planmyday');
			$list['square effect11'] = esc_html__('Square Effect 11',  'planmyday');
			$list['square effect12'] = esc_html__('Square Effect 12',  'planmyday');
			$list['square effect13'] = esc_html__('Square Effect 13',  'planmyday');
			$list['square effect14'] = esc_html__('Square Effect 14',  'planmyday');
			$list['square effect15'] = esc_html__('Square Effect 15',  'planmyday');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'planmyday');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'planmyday');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'planmyday');
			$list['square effect_more']  = esc_html__('Square Effect More',  'planmyday');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'planmyday');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'planmyday');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'planmyday');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'planmyday');
			$list = apply_filters('planmyday_filter_portfolio_hovers', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'planmyday_get_list_blog_counters' ) ) {
	function planmyday_get_list_blog_counters($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'planmyday'),
				'likes'		=> esc_html__('Likes', 'planmyday'),
				'rating'	=> esc_html__('Rating', 'planmyday'),
				'comments'	=> esc_html__('Comments', 'planmyday')
				);
			$list = apply_filters('planmyday_filter_list_blog_counters', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'planmyday_get_list_alter_sizes' ) ) {
	function planmyday_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'planmyday'),
					'1_2' => esc_html__('1x2', 'planmyday'),
					'2_1' => esc_html__('2x1', 'planmyday'),
					'2_2' => esc_html__('2x2', 'planmyday'),
					'1_3' => esc_html__('1x3', 'planmyday'),
					'2_3' => esc_html__('2x3', 'planmyday'),
					'3_1' => esc_html__('3x1', 'planmyday'),
					'3_2' => esc_html__('3x2', 'planmyday'),
					'3_3' => esc_html__('3x3', 'planmyday')
					);
			$list = apply_filters('planmyday_filter_portfolio_alter_sizes', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'planmyday_get_list_hovers_directions' ) ) {
	function planmyday_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'planmyday'),
				'right_to_left' => esc_html__('Right to Left',  'planmyday'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'planmyday'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'planmyday'),
				'scale_up'      => esc_html__('Scale Up',  'planmyday'),
				'scale_down'    => esc_html__('Scale Down',  'planmyday'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'planmyday'),
				'from_left_and_right' => esc_html__('From Left and Right',  'planmyday'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'planmyday')
			);
			$list = apply_filters('planmyday_filter_portfolio_hovers_directions', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'planmyday_get_list_label_positions' ) ) {
	function planmyday_get_list_label_positions($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'planmyday'),
				'bottom'	=> esc_html__('Bottom',		'planmyday'),
				'left'		=> esc_html__('Left',		'planmyday'),
				'over'		=> esc_html__('Over',		'planmyday')
			);
			$list = apply_filters('planmyday_filter_label_positions', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'planmyday_get_list_bg_image_positions' ) ) {
	function planmyday_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'planmyday'),
				'center top'   => esc_html__("Center Top", 'planmyday'),
				'right top'    => esc_html__("Right Top", 'planmyday'),
				'left center'  => esc_html__("Left Center", 'planmyday'),
				'center center'=> esc_html__("Center Center", 'planmyday'),
				'right center' => esc_html__("Right Center", 'planmyday'),
				'left bottom'  => esc_html__("Left Bottom", 'planmyday'),
				'center bottom'=> esc_html__("Center Bottom", 'planmyday'),
				'right bottom' => esc_html__("Right Bottom", 'planmyday')
			);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'planmyday_get_list_bg_image_repeats' ) ) {
	function planmyday_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'planmyday'),
				'repeat-x'	=> esc_html__('Repeat X', 'planmyday'),
				'repeat-y'	=> esc_html__('Repeat Y', 'planmyday'),
				'no-repeat'	=> esc_html__('No Repeat', 'planmyday')
			);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'planmyday_get_list_bg_image_attachments' ) ) {
	function planmyday_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'planmyday'),
				'fixed'		=> esc_html__('Fixed', 'planmyday'),
				'local'		=> esc_html__('Local', 'planmyday')
			);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'planmyday_get_list_bg_tints' ) ) {
	function planmyday_get_list_bg_tints($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'planmyday'),
				'light'	=> esc_html__('Light', 'planmyday'),
				'dark'	=> esc_html__('Dark', 'planmyday')
			);
			$list = apply_filters('planmyday_filter_bg_tints', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'planmyday_get_list_field_types' ) ) {
	function planmyday_get_list_field_types($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'planmyday'),
				'textarea' => esc_html__('Text Area','planmyday'),
				'password' => esc_html__('Password',  'planmyday'),
				'radio'    => esc_html__('Radio',  'planmyday'),
				'checkbox' => esc_html__('Checkbox',  'planmyday'),
				'select'   => esc_html__('Select',  'planmyday'),
				'date'     => esc_html__('Date','planmyday'),
				'time'     => esc_html__('Time','planmyday'),
				'button'   => esc_html__('Button','planmyday')
			);
			$list = apply_filters('planmyday_filter_field_types', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'planmyday_get_list_googlemap_styles' ) ) {
	function planmyday_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'planmyday')
			);
			$list = apply_filters('planmyday_filter_googlemap_styles', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return images list
if (!function_exists('planmyday_get_list_images')) {	
	function planmyday_get_list_images($folder, $ext='', $only_names=false) {
		return function_exists('trx_utils_get_folder_list') ? trx_utils_get_folder_list($folder, $ext, $only_names) : array();
	}
}

// Return iconed classes list
if ( !function_exists( 'planmyday_get_list_icons' ) ) {
	function planmyday_get_list_icons($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_icons'))=='') {
			$list = planmyday_parse_icons_classes(planmyday_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'planmyday_get_list_socials' ) ) {
	function planmyday_get_list_socials($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_socials'))=='') {
			$list = planmyday_get_list_images("images/socials", "png");
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'planmyday_get_list_yesno' ) ) {
	function planmyday_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'planmyday'),
			'no'  => esc_html__("No", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'planmyday_get_list_onoff' ) ) {
	function planmyday_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'planmyday'),
			"off" => esc_html__("Off", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'planmyday_get_list_showhide' ) ) {
	function planmyday_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'planmyday'),
			"hide" => esc_html__("Hide", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'planmyday_get_list_orderings' ) ) {
	function planmyday_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'planmyday'),
			"desc" => esc_html__("Descending", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'planmyday_get_list_directions' ) ) {
	function planmyday_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'planmyday'),
			"vertical" => esc_html__("Vertical", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'planmyday_get_list_shapes' ) ) {
	function planmyday_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'planmyday'),
			"square" => esc_html__("Square", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'planmyday_get_list_sizes' ) ) {
	function planmyday_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'planmyday'),
			"small"  => esc_html__("Small", 'planmyday'),
			"medium" => esc_html__("Medium", 'planmyday'),
			"large"  => esc_html__("Large", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'planmyday_get_list_controls' ) ) {
	function planmyday_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'planmyday'),
			"side" => esc_html__("Side", 'planmyday'),
			"bottom" => esc_html__("Bottom", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'planmyday_get_list_floats' ) ) {
	function planmyday_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'planmyday'),
			"left" => esc_html__("Float Left", 'planmyday'),
			"right" => esc_html__("Float Right", 'planmyday')
		);
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'planmyday_get_list_alignments' ) ) {
	function planmyday_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'planmyday'),
			"left" => esc_html__("Left", 'planmyday'),
			"center" => esc_html__("Center", 'planmyday'),
			"right" => esc_html__("Right", 'planmyday')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'planmyday');
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'planmyday_get_list_hpos' ) ) {
	function planmyday_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'planmyday');
		if ($center) $list['center'] = esc_html__("Center", 'planmyday');
		$list['right'] = esc_html__("Right", 'planmyday');
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'planmyday_get_list_vpos' ) ) {
	function planmyday_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'planmyday');
		if ($center) $list['center'] = esc_html__("Center", 'planmyday');
		$list['bottom'] = esc_html__("Bottom", 'planmyday');
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'planmyday_get_list_sortings' ) ) {
	function planmyday_get_list_sortings($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'planmyday'),
				"title" => esc_html__("Alphabetically", 'planmyday'),
				"views" => esc_html__("Popular (views count)", 'planmyday'),
				"comments" => esc_html__("Most commented (comments count)", 'planmyday'),
				"author_rating" => esc_html__("Author rating", 'planmyday'),
				"users_rating" => esc_html__("Visitors (users) rating", 'planmyday'),
				"random" => esc_html__("Random", 'planmyday')
			);
			$list = apply_filters('planmyday_filter_list_sortings', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'planmyday_get_list_columns' ) ) {
	function planmyday_get_list_columns($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'planmyday'),
				"1_1" => esc_html__("100%", 'planmyday'),
				"1_2" => esc_html__("1/2", 'planmyday'),
				"1_3" => esc_html__("1/3", 'planmyday'),
				"2_3" => esc_html__("2/3", 'planmyday'),
				"1_4" => esc_html__("1/4", 'planmyday'),
				"3_4" => esc_html__("3/4", 'planmyday'),
				"1_5" => esc_html__("1/5", 'planmyday'),
				"2_5" => esc_html__("2/5", 'planmyday'),
				"3_5" => esc_html__("3/5", 'planmyday'),
				"4_5" => esc_html__("4/5", 'planmyday'),
				"1_6" => esc_html__("1/6", 'planmyday'),
				"5_6" => esc_html__("5/6", 'planmyday'),
				"1_7" => esc_html__("1/7", 'planmyday'),
				"2_7" => esc_html__("2/7", 'planmyday'),
				"3_7" => esc_html__("3/7", 'planmyday'),
				"4_7" => esc_html__("4/7", 'planmyday'),
				"5_7" => esc_html__("5/7", 'planmyday'),
				"6_7" => esc_html__("6/7", 'planmyday'),
				"1_8" => esc_html__("1/8", 'planmyday'),
				"3_8" => esc_html__("3/8", 'planmyday'),
				"5_8" => esc_html__("5/8", 'planmyday'),
				"7_8" => esc_html__("7/8", 'planmyday'),
				"1_9" => esc_html__("1/9", 'planmyday'),
				"2_9" => esc_html__("2/9", 'planmyday'),
				"4_9" => esc_html__("4/9", 'planmyday'),
				"5_9" => esc_html__("5/9", 'planmyday'),
				"7_9" => esc_html__("7/9", 'planmyday'),
				"8_9" => esc_html__("8/9", 'planmyday'),
				"1_10"=> esc_html__("1/10", 'planmyday'),
				"3_10"=> esc_html__("3/10", 'planmyday'),
				"7_10"=> esc_html__("7/10", 'planmyday'),
				"9_10"=> esc_html__("9/10", 'planmyday'),
				"1_11"=> esc_html__("1/11", 'planmyday'),
				"2_11"=> esc_html__("2/11", 'planmyday'),
				"3_11"=> esc_html__("3/11", 'planmyday'),
				"4_11"=> esc_html__("4/11", 'planmyday'),
				"5_11"=> esc_html__("5/11", 'planmyday'),
				"6_11"=> esc_html__("6/11", 'planmyday'),
				"7_11"=> esc_html__("7/11", 'planmyday'),
				"8_11"=> esc_html__("8/11", 'planmyday'),
				"9_11"=> esc_html__("9/11", 'planmyday'),
				"10_11"=> esc_html__("10/11", 'planmyday'),
				"1_12"=> esc_html__("1/12", 'planmyday'),
				"5_12"=> esc_html__("5/12", 'planmyday'),
				"7_12"=> esc_html__("7/12", 'planmyday'),
				"10_12"=> esc_html__("10/12", 'planmyday'),
				"11_12"=> esc_html__("11/12", 'planmyday')
			);
			$list = apply_filters('planmyday_filter_list_columns', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'planmyday_get_list_dedicated_locations' ) ) {
	function planmyday_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'planmyday'),
				"center"  => esc_html__('Above the text of the post', 'planmyday'),
				"left"    => esc_html__('To the left the text of the post', 'planmyday'),
				"right"   => esc_html__('To the right the text of the post', 'planmyday'),
				"alter"   => esc_html__('Alternates for each post', 'planmyday')
			);
			$list = apply_filters('planmyday_filter_list_dedicated_locations', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'planmyday_get_post_format_name' ) ) {
	function planmyday_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'planmyday') : esc_html__('galleries', 'planmyday');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'planmyday') : esc_html__('videos', 'planmyday');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'planmyday') : esc_html__('audios', 'planmyday');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'planmyday') : esc_html__('images', 'planmyday');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'planmyday') : esc_html__('quotes', 'planmyday');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'planmyday') : esc_html__('links', 'planmyday');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'planmyday') : esc_html__('statuses', 'planmyday');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'planmyday') : esc_html__('asides', 'planmyday');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'planmyday') : esc_html__('chats', 'planmyday');
		else						$name = $single ? esc_html__('standard', 'planmyday') : esc_html__('standards', 'planmyday');
		return apply_filters('planmyday_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'planmyday_get_post_format_icon' ) ) {
	function planmyday_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('planmyday_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'planmyday_get_list_fonts_styles' ) ) {
	function planmyday_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','planmyday'),
				'u' => esc_html__('U', 'planmyday')
			);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'planmyday_get_list_fonts' ) ) {
	function planmyday_get_list_fonts($prepend_inherit=false) {
		if (($list = planmyday_storage_get('list_fonts'))=='') {
			$list = array();
			$list = planmyday_array_merge($list, planmyday_get_list_font_faces());
			$list = planmyday_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('planmyday_filter_list_fonts', $list);
			if (planmyday_get_theme_setting('use_list_cache')) planmyday_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? planmyday_array_merge(array('inherit' => esc_html__("Inherit", 'planmyday')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'planmyday_get_list_font_faces' ) ) {
	function planmyday_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$fonts = planmyday_storage_get('required_custom_fonts');
		$list = array();
		if (is_array($fonts)) {
			foreach ($fonts as $font) {
				if (($url = planmyday_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
					$list[sprintf(esc_html__('%s (uploaded font)', 'planmyday'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>