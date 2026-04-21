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

	$wp_customize->add_section(
		'curixus_footer_section',
		array(
			'title'       => __( 'Footer', 'curixus' ),
			'description' => __( 'Manage the footer content displayed across the site.', 'curixus' ),
			'priority'    => 160,
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
				'section'   => 'curixus_footer_section',
				'settings'  => 'footer_logo',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'footer_address',
		array(
			'default'           => __( 'Vesterbrogade 149, 1620 Copenhagen V', 'curixus' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_address',
		array(
			'label'   => __( 'Address', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_contact_label',
		array(
			'default'           => __( 'Contact us', 'curixus' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_contact_label',
		array(
			'label'   => __( 'Contact label', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_email',
		array(
			'default'           => get_option( 'admin_email' ),
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_email',
		array(
			'label'   => __( 'Footer email', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'email',
		)
	);

	$wp_customize->add_setting(
		'footer_copyright',
		array(
			'default'           => sprintf( '©%s', gmdate( 'Y' ) ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_copyright',
		array(
			'label'   => __( 'Copyright text', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_cvr',
		array(
			'default'           => __( 'CVR: 12345678', 'curixus' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_cvr',
		array(
			'label'   => __( 'CVR text', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_cookie_text',
		array(
			'default'           => __( 'Cookie policy', 'curixus' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_cookie_text',
		array(
			'label'   => __( 'Cookie policy label', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_cookie_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_cookie_url',
		array(
			'label'   => __( 'Cookie policy URL', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'footer_privacy_text',
		array(
			'default'           => __( 'Privacy policy', 'curixus' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_privacy_text',
		array(
			'label'   => __( 'Privacy policy label', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_privacy_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'footer_privacy_url',
		array(
			'label'   => __( 'Privacy policy URL', 'curixus' ),
			'section' => 'curixus_footer_section',
			'type'    => 'url',
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
