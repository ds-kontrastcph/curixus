<?php 
function curixus_scripts() {
	wp_enqueue_style( 'curixus-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'curixus-style', 'rtl', 'replace' );

	wp_enqueue_script( 'curixus-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'curixus_scripts' );