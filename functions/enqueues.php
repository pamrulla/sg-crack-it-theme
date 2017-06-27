<?php

function sgcrackit_enqueues() {

	/* Styles */

	wp_register_style('bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, '4.0.0-alpha.6', null);
	wp_enqueue_style('bootstrap-css');

	wp_register_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', null);
	wp_enqueue_style('font-awesome-css');

  	wp_register_style('sgcrackit-css', get_template_directory_uri() . '/theme/css/sgcrackit.css', false, null);
	wp_enqueue_style('sgcrackit-css');

	/* Scripts */

	wp_enqueue_script( 'jquery' );
	
  	wp_register_script('modernizr',  'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', false, '2.8.3', true);
	wp_enqueue_script('modernizr');

	wp_register_script('tether',  'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js', false, '1.4.0', true);
	wp_enqueue_script('tether');

  	wp_register_script('bootstrap-js', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js', false, '4.0.0-alpha.6', true);
	wp_enqueue_script('bootstrap-js');

    wp_register_script('charts-js', 'https://www.gstatic.com/charts/loader.js', false, '', true);
	wp_enqueue_script('charts-js');

	wp_register_script('sgcrackit-js', get_template_directory_uri() . '/theme/js/sgcrackit.js', false, null, true);
	wp_enqueue_script('sgcrackit-js');

	/*if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}*/
}
add_action('wp_enqueue_scripts', 'sgcrackit_enqueues', 100);
