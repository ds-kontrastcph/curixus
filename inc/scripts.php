<?php 
function curixus_scripts() {
	$theme_stylesheet_path = get_template_directory() . '/css/style.css';
	$theme_script_path     = get_template_directory() . '/js/navigation.js';

	wp_enqueue_style(
		'curixus-style',
		get_template_directory_uri() . '/css/style.css',
		array(),
		filemtime( $theme_stylesheet_path )
	);

	wp_enqueue_script(
		'curixus-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		filemtime( $theme_script_path ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'curixus_scripts' );



// Setup admin style
function admin_style() {
	wp_enqueue_style('admin-styles', get_template_directory_uri() . '/css/admin.css', array(), filemtime(get_template_directory() . '/css/admin.css'), false );
}
add_action('admin_enqueue_scripts', 'admin_style');