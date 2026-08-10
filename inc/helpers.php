<?php 

function sprite_svg( $spriteName, $svgWidth = '24', $svgHeight = '24', $return = '' ) {
	$svg = get_stylesheet_directory_uri() . '/images/icons.svg?ver='. filemtime(get_template_directory() . '/images/icons.svg') .'#' . $spriteName;
	$elWidth = '';
	$elHeight = '';
	if (isset($svgWidth)) {
		$elWidth = 'width="' . $svgWidth . '"';
	}
	if (isset($svgHeight)) {
		$elHeight = 'height="' . $svgHeight . '"';
	}
	$iconHtml = '<svg class="svg-icon '. $spriteName .'" '.$elWidth.' '.$elHeight.' ><use xlink:href="' . $svg . '"></use></svg>';
	if ($return) {
		return $iconHtml;
	} else {
		echo $iconHtml;
	}
}

/**
 * Output SVG icon from socials sprite file
 *
 * @param string $spriteName Icon ID from socials.svg
 * @param string $svgWidth Icon width
 * @param string $svgHeight Icon height
 * @param string $return Return or echo
 * @return string|void
 */
function sprite_svg_social( $spriteName, $svgWidth = '22', $svgHeight = '22', $return = '' ) {
	$sprite_name = is_string( $spriteName ) ? $spriteName : '';
	$svg = get_stylesheet_directory_uri() . '/images/socials.svg?ver=' . filemtime( get_template_directory() . '/images/socials.svg' ) . '#' . $sprite_name;
	$elWidth = '';
	$elHeight = '';
	if (isset($svgWidth)) {
		$elWidth = 'width="' . $svgWidth . '"';
	}
	if (isset($svgHeight)) {
		$elHeight = 'height="' . $svgHeight . '"';
	}
	$iconHtml = '<svg class="svg-icon ' . esc_attr( $sprite_name ) . '" ' . $elWidth . ' ' . $elHeight . ' ><use xlink:href="' . esc_url( $svg ) . '"></use></svg>';
	if ($return) {
		return $iconHtml;
	} else {
		echo $iconHtml;
	}
}


// Plugin ACF Svg icon field
add_filter( 'acf/fields/svg_icon/file_path', 'tc_acf_svg_icon_file_path' );
function tc_acf_svg_icon_file_path( $file_path ) {
	// Respect the file path provided by the field configuration (e.g. socials.svg).
	// Fallback to theme icons.svg if the provided path is missing/invalid.
	if ( is_string( $file_path ) && '' !== $file_path ) {
		$resolved_path = $file_path;

		// If plugin passes a relative path, resolve it against the theme directory.
		if ( 0 !== strpos( $resolved_path, '/' ) && false === strpos( $resolved_path, ':' ) ) {
			$resolved_path = get_theme_file_path( '/' . ltrim( $resolved_path, '/' ) );
		}

		if ( file_exists( $resolved_path ) ) {
			return $resolved_path;
		}
	}

	return get_theme_file_path( '/images/icons.svg' );
}


// Allow svg to upload
add_filter( 'upload_mimes', 'allow_mimes' );
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

// Allow svg to display
add_filter( 'wp_kses_allowed_html', 'kses_allowed_svg' );


/**
 * Allow SVG.
 *
 * @param $types
 *
 * @return array
 */
function allow_mimes( $types ): array {
	$new_types         = [];
	$new_types['svg']  = 'image/svg+xml';
	$new_types['svgz'] = 'image/svg+xml';
	$new_types['json'] = 'application/json';

	return array_merge( $types, $new_types );
}

/**
 * Fix SVG mime type
 *
 * @param $data
 * @param $file
 * @param $filename
 * @param $mimes
 * @param string $real_mime
 *
 * @return mixed
 */
function fix_svg_mime_type( $data, $file, $filename, $mimes, string $real_mime = '' ) {
	// WP 5.1 +
	if ( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ) {
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ], true );
	} else {
		$dosvg = ( '.svg' === strtolower( substr( $filename, - 4 ) ) );
	}
	if ( $dosvg ) {
		if ( current_user_can( 'manage_options' ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		} else {
			$data['ext']  = false;
			$data['type'] = false;
		}
	}

	return $data;
}

add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

    if ( $ext === 'json' ) {
        $data = [
            'ext'  => 'json',
            'type' => 'application/json',
            'proper_filename' => $filename,
        ];
    }

    return $data;
}, 10, 4 );

/**
 * Allow SVG attributes.
 *
 * @param $tags
 *
 * @return mixed
 */
function kses_allowed_svg( $tags ) {
	$tags['svg']  = [
		'width'       => [],
		'height'      => [],
		'xmlns'       => [],
		'fill'        => [],
		'viewbox'     => [],
		'role'        => [],
		'aria-hidden' => [],
		'focusable'   => [],
		'class'       => [],
	];
	$tags['path'] = [
		'd'    => [],
		'fill' => [],
	];
	$tags['use']  = [
		'xlink:href' => [],
	];
	$tags['mask'] = [];
	$tags['g']    = [];

	return $tags;
}

function get_excerpt_trim($num_words='20', $more='...', $post_id = ''){
	$excerpt = get_the_excerpt($post_id);
	$excerpt = wp_trim_words( $excerpt, $num_words , $more );
	return $excerpt;
}

/**
 * Get the public link URL for a portfolio item.
 *
 * Uses the optional ACF resource_url when set, otherwise falls back to the post permalink.
 *
 * @param int $post_id Portfolio post ID.
 * @return string URL or empty string when post ID is invalid.
 */
function curixus_project_get_portfolio_item_url( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	$resource_url = get_field( 'resource_url', $post_id );

	if ( is_string( $resource_url ) && '' !== trim( $resource_url ) ) {
		return $resource_url;
	}

	return get_permalink( $post_id );
}

/**
 * Get link target for a portfolio item card.
 *
 * Custom resource URLs open in a new tab; internal permalinks stay in the same tab.
 *
 * @param int $post_id Portfolio post ID.
 * @return string Link target attribute value.
 */
function curixus_project_get_portfolio_item_link_target( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return '_self';
	}

	$resource_url = get_field( 'resource_url', $post_id );

	if ( is_string( $resource_url ) && '' !== trim( $resource_url ) ) {
		return '_blank';
	}

	return '_self';
}

// remove <br> and <p> from CF7
add_filter( 'wpcf7_autop_or_not', '__return_false' );