<?php
/**
 * Dark header variant loader.
 *
 * @package curixus
 */

set_query_var( 'curixus_header_variant', 'dark' );
require get_template_directory() . '/header.php';
set_query_var( 'curixus_header_variant', null );
