<?php 
function curixus_scripts() {
	$theme_stylesheet_path = get_template_directory() . '/css/style.css';
	$theme_script_path     = get_template_directory() . '/js/navigation.js';
	$owl_style_path        = get_template_directory() . '/css/vendor/owl.carousel.min.css';
	$owl_theme_path        = get_template_directory() . '/css/vendor/owl.theme.default.min.css';
	$owl_script_path       = get_template_directory() . '/js/vendor/owl.carousel.min.js';
	$portfolio_script_path = get_template_directory() . '/js/portfolio-slider.js';

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

	wp_enqueue_style(
		'curixus-owl-carousel',
		get_template_directory_uri() . '/css/vendor/owl.carousel.min.css',
		array(),
		filemtime( $owl_style_path )
	);

	wp_enqueue_style(
		'curixus-owl-theme',
		get_template_directory_uri() . '/css/vendor/owl.theme.default.min.css',
		array( 'curixus-owl-carousel' ),
		filemtime( $owl_theme_path )
	);

	wp_enqueue_script(
		'curixus-owl-carousel',
		get_template_directory_uri() . '/js/vendor/owl.carousel.min.js',
		array( 'jquery' ),
		filemtime( $owl_script_path ),
		true
	);

	wp_enqueue_script(
		'curixus-portfolio-slider',
		get_template_directory_uri() . '/js/portfolio-slider.js',
		array( 'jquery', 'curixus-owl-carousel' ),
		filemtime( $portfolio_script_path ),
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

// Custom WordPress Login Logo
function login_css() {
	wp_enqueue_style( 'login_css', 'https://www.kontrastcph.dk/login/login.css' );
}
add_action('login_head', 'login_css');