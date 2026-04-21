<?php 
/**
 * Save acf-json to the stylesheet directory.
 *
 * @since 1.0.0
 * @link https://support.advancedcustomfields.com/forums/topic/acf-json-fields-not-loading-from-parent-theme/
 * @return string
 */
function em_acf_save_json_path() {
	return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'em_acf_save_json_path' );


/**
 * Load acf-json from parent theme and child theme, if available.
 *
 * @since 1.0.0
 * @link https://support.advancedcustomfields.com/forums/topic/acf-json-fields-not-loading-from-parent-theme/
 * @param array $paths Array of acf-json paths.
 * @return array
 */
function em_acf_load_json_paths( $paths ) {
	$paths = array( get_template_directory() . '/acf-json' );

	if ( is_child_theme() ) {
		$paths[] = get_stylesheet_directory() . '/acf-json';
	}

	return $paths;
}
add_filter( 'acf/settings/load_json', 'em_acf_load_json_paths' );

// Setup acf gutenberg blocks
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_my_blocks');
}

function register_my_blocks() {
	$blocks = glob( STYLESHEETPATH . '/blocks/*');
	if ( false === $blocks ) {
		return;
	}
	
	foreach ($blocks as $block){
		if ( is_dir( $block ) ) {
			// Wrap in try-catch to prevent fatal errors during block registration
			try {
				register_block_type( $block );
			} catch ( Exception $e ) {
				// Log error but don't crash
				if ( function_exists( 'error_log' ) ) {
					error_log( 'Block registration failed for ' . $block . ': ' . $e->getMessage() );
				}
			}
		}
	}
}

// register new Gutenberg blocks category
function add_custom_block_categories( $categories, $post ) {
	$custom_category_one = array(
		'slug' => 'curixus',
		'title' => __( 'Custom Blocks', 'curixus' ),
		'icon'  => 'welcome-widgets-menus',
	);
	array_unshift( $categories, $custom_category_one);

	$custom_category_two = array(
		'slug' => 'curixus_blocks',
		'title' => __( 'Landing Page', 'curixus' ),
		'icon'  => 'admin-generic',
	);
	$categories[] = $custom_category_two;

	return $categories;
}
add_filter( 'block_categories_all', 'add_custom_block_categories', 10, 2 );

//Theme General Settings
add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {
	// Check function exists.
	if( function_exists('acf_add_options_page') ) {
		// Add parent.
		$parent = acf_add_options_page(array(
			'page_title'  => __('Site Settings'),
			'menu_title'  => __('Site Settings'),
			'post_id' => 'options',
			'redirect'    => false,
		));
	}
}

