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
        'primary' => 'Header Menu',
    );

    register_nav_menus($locations);
}

add_action('init', 'ct2021_menus');