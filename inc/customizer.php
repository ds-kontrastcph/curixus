<?php
/**
 * curixus Theme Customizer
 *
 * @package curixus
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function curixus_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'curixus_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'curixus_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_setting(
		'dark_header_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'dark_header_logo',
			array(
				'label'       => __( 'Dark header logo', 'curixus' ),
				'description' => __( 'Used when a template loads the dark header variant.', 'curixus' ),
				'section'     => 'title_tagline',
				'settings'    => 'dark_header_logo',
				'mime_type'   => 'image',
				'priority'    => 9,
			)
		)
	);

	$wp_customize->add_setting(
		'footer_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'footer_logo',
			array(
				'label'     => __( 'Footer logo', 'curixus' ),
				'section'   => 'title_tagline',
				'settings'  => 'footer_logo',
				'mime_type' => 'image',
				'priority'  => 10,
			)
		)
	);
}
add_action( 'customize_register', 'curixus_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function curixus_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function curixus_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function curixus_customize_preview_js() {
	wp_enqueue_script( 'curixus-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'curixus_customize_preview_js' );
