<?php 
function curixus_scripts() {
	wp_enqueue_style(
		'curixus-style',
		get_template_directory_uri() . '/css/style.css',
		array(),
		filemtime( get_template_directory() . '/css/style.css' )
	);

	wp_enqueue_script( 'curixus-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'curixus_scripts' );
