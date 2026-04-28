<?php
/**
 * Register custom post type modules.
 *
 * @package curixus
 */

$curixus_post_type_files = array(
	'/inc/post-types/portfolio.php',
);

foreach ( $curixus_post_type_files as $curixus_post_type_file ) {
	$curixus_file_path = get_template_directory() . $curixus_post_type_file;
	if ( file_exists( $curixus_file_path ) ) {
		require_once $curixus_file_path;
	}
}
