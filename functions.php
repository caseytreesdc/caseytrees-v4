<?php
/*
===================================
Theme Supports
===================================
*/
function ct2021_theme_setup() {
    add_theme_support('custom-logo');
}

add_action('after_setup_theme', 'ct2021_theme_setup');

/*
===================================
Menu
===================================
*/
function ct2021_menus() {
    $locations = array(
        'topOfAllPagesMenu' => 'Top of All Pages for Now',
    );

    register_nav_menus($locations);
}

add_action('init', 'ct2021_menus');

// /*
//     ===================================
//     Register Resources Custom Post Type
//     ===================================
// */

function ct2021_resources_init() {
	$labels = array(
		'name'               => _x( 'Resources', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Resource', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Resources', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Resource', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => __( 'Add New', 'resource', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Resource', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Resource', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Resource', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Resource', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Resources', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Resources', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Resources:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No resources found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No resources found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'resources-list', 'with_front' => false),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-media-document',
		'supports'           => array('title', 'editor')
	);

	/******** Icon reference for $args['menu_position'] --> https://developer.wordpress.org/resource/dashicons/ *********/

	register_post_type( 'resources', $args );
}
add_action( 'init', 'ct2021_resources_init' );

register_taxonomy(
	'resources-categories',
	'resources',
	array(
		'label' => __( 'Resources Categories' ),
		'rewrite' => array( 'slug' => 'resources-categories' ),
		'hierarchical' => true,
        'show_in_nav_menus'  => true,
        'show_in_rest' => true
	)
);

register_taxonomy(
	'resources-tags',
	array('resources', 'page', 'post', 'trees'),
	array(
		'label' => __( 'Resources Tags' ),
		'rewrite' => array( 'slug' => 'resources-tags' ),
		'hierarchical' => true,
		'show_in_nav_menus'  => true,
	)
);

// /*
//     ===================================
//     Register Trees Custom Post Type NMC Code 
//     ===================================
// */

add_action( 'init', 'trees_init' ); //how come this isn't after declaring the function? 
function trees_init() { 
	$labels = array(
		'name'               => _x( 'Trees', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Tree', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Trees', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Tree', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'tree', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Tree', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Tree', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Tree', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Tree', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Trees', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Trees', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Trees:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No trees found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No trees found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'trees-list', 'with_front' => true),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-palmtree',
		'supports'           => array('title', 'editor')
	);

	/******** Icon reference for $args['menu_position'] --> https://developer.wordpress.org/resource/dashicons/ *********/

	register_post_type( 'trees', $args );
}

// /*
//     ===================================
//     Class Manipulation Filters
//     ===================================
// */
add_filter('get_custom_logo', 'ct2021_replace_logo_classes');

function ct2021_replace_logo_classes( $html ) {
	$html = str_replace( 'custom-logo-link', 'header__logo-anchor', $html);
	$html = str_replace( 'custom-logo', 'header__logo', $html);
	return $html;
}

add_filter('wp_nav_menu', 'ct2021_replace_header_menu_div_wrapper_class');

function ct2021_replace_header_menu_div_wrapper_class( $html ) {
	$html = str_replace('menu-main-navigation-container', 'Nav__container', $html);
	return $html;
}

// /*
//     ===================================
//     Register Styles
//     ===================================
// */
function ct2021_register_styles() {
    
    $styleVersion = wp_get_theme()->get( 'Version' );
	
	wp_enqueue_style('adobe-fonts', 'https://use.typekit.net/bix5hve.css', array(), 'version');
    wp_enqueue_style('ct2021-style', get_template_directory_uri() . '/style.css', array(), $styleVersion, 'all');
}

add_action('wp_enqueue_scripts', 'ct2021_register_styles');