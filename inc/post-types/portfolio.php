<?php
/**
 * Register Portfolio post type and taxonomies.
 *
 * @package curixus
 */

add_action( 'init', 'curixus_project_register_portfolio_content' );

if ( ! function_exists( 'curixus_project_register_portfolio_content' ) ) :
	/**
	 * Register portfolio custom post type and related taxonomy.
	 */
	function curixus_project_register_portfolio_content() {
		$labels = array(
			'name'                  => __( 'Portfolio', 'curixus' ),
			'singular_name'         => __( 'Portfolio Item', 'curixus' ),
			'menu_name'             => __( 'Portfolio', 'curixus' ),
			'name_admin_bar'        => __( 'Portfolio Item', 'curixus' ),
			'add_new'               => __( 'Add New', 'curixus' ),
			'add_new_item'          => __( 'Add New Portfolio Item', 'curixus' ),
			'new_item'              => __( 'New Portfolio Item', 'curixus' ),
			'edit_item'             => __( 'Edit Portfolio Item', 'curixus' ),
			'view_item'             => __( 'View Portfolio Item', 'curixus' ),
			'all_items'             => __( 'All Portfolio Items', 'curixus' ),
			'search_items'          => __( 'Search Portfolio', 'curixus' ),
			'parent_item_colon'     => __( 'Parent Portfolio Item:', 'curixus' ),
			'not_found'             => __( 'No portfolio items found.', 'curixus' ),
			'not_found_in_trash'    => __( 'No portfolio items found in Trash.', 'curixus' ),
			'featured_image'        => __( 'Portfolio Cover Image', 'curixus' ),
			'set_featured_image'    => __( 'Set cover image', 'curixus' ),
			'remove_featured_image' => __( 'Remove cover image', 'curixus' ),
			'use_featured_image'    => __( 'Use as cover image', 'curixus' ),
			'archives'              => __( 'Portfolio archives', 'curixus' ),
			'insert_into_item'      => __( 'Insert into portfolio item', 'curixus' ),
			'uploaded_to_this_item' => __( 'Uploaded to this portfolio item', 'curixus' ),
			'filter_items_list'     => __( 'Filter portfolio list', 'curixus' ),
			'items_list_navigation' => __( 'Portfolio list navigation', 'curixus' ),
			'items_list'            => __( 'Portfolio list', 'curixus' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'portfolio' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 21,
			'menu_icon'          => 'dashicons-format-gallery',
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		);

		register_post_type( 'portfolio', $args );

		$taxonomy_labels = array(
			'name'              => __( 'Portfolio Categories', 'curixus' ),
			'singular_name'     => __( 'Portfolio Category', 'curixus' ),
			'search_items'      => __( 'Search Portfolio Categories', 'curixus' ),
			'all_items'         => __( 'All Portfolio Categories', 'curixus' ),
			'parent_item'       => __( 'Parent Portfolio Category', 'curixus' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:', 'curixus' ),
			'edit_item'         => __( 'Edit Portfolio Category', 'curixus' ),
			'update_item'       => __( 'Update Portfolio Category', 'curixus' ),
			'add_new_item'      => __( 'Add New Portfolio Category', 'curixus' ),
			'new_item_name'     => __( 'New Portfolio Category Name', 'curixus' ),
			'menu_name'         => __( 'Portfolio Categories', 'curixus' ),
		);

		$taxonomy_args = array(
			'hierarchical'      => true,
			'labels'            => $taxonomy_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'portfolio-category' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'portfolio_category', array( 'portfolio' ), $taxonomy_args );
	}
endif;
