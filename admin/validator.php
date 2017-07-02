<?php 

function sgcrackit_register_validator_menu_page() {
    $count = 0;
    global $progressTable;
    
    global $wpdb;
    
    $sqlSelect = "SELECT COUNT(*) as c FROM $progressTable WHERE isCompleted = 1 ";
    $result = $wpdb->get_results($sqlSelect);
    
    $count = $result[0]->c;
    
    $question_page = add_menu_page(
        'Validator',
        sprintf( __( 'Validator %s' ), "<span class='update-plugins count-$count'><span class='update-count'>" . number_format_i18n($count) . "</span></span>" ),
        'manage_options',
        'validator',
        'sgcrackit_render_validator_page',
        '',
        6
    );
    
    add_action('admin_print_styles-' . $question_page, 'sgcrackit_load_admin_scripts_validator');
}
add_action( 'admin_menu', 'sgcrackit_register_validator_menu_page' );

function sgcrackit_load_admin_scripts_validator() {
    wp_register_style('bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, '4.0.0-alpha.6', null);
	wp_enqueue_style('bootstrap-css');
    
    wp_register_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', null);
	wp_enqueue_style('font-awesome-css');
    
    wp_register_script('sgcrackit-admin-js', get_template_directory_uri() . '/theme/js/admin.js', false, null, true);
	wp_enqueue_script('sgcrackit-admin-js');
}

function sgcrackit_render_validator_page() {
    echo '<a class="btn btn-primary">a</a>';
}