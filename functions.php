<?php

function sgcrackit_script_enqueue() {
    
    wp_enqueue_style('bootstrapstyle', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', array());
    wp_enqueue_style('customstyle', get_template_directory_uri() . '/css/sgcrackit.css', array(), '1.0.0', 'all');
    
    wp_enqueue_script('boostrapscript', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', array('jquery'), null, true);
    wp_enqueue_script('customjs', get_template_directory_uri() . '/js/sgcrackit.js', array(), '1.0.0', true);
    
}

add_action('wp_enqueue_scripts', 'sgcrackit_script_enqueue');

function sgcrackit_theme_setup() {
    
    add_theme_support('menus');
    
    register_nav_menu('primary', 'This is primary menu');
    register_nav_menu('secondary', 'This is footer menu');
}

add_action('init', 'sgcrackit_theme_setup');
