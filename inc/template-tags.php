<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package curixus
 */

if ( ! function_exists( 'curixus_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function curixus_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'curixus' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'curixus_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function curixus_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'curixus' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'curixus_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function curixus_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'curixus' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'curixus' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'curixus' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'curixus' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'curixus' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'curixus' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'curixus_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function curixus_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'curixus_project_get_footer_context' ) ) :
	/**
	 * Build footer content from Customizer settings.
	 *
	 * @return array<string, mixed>
	 */
	function curixus_project_get_footer_context() {
		$footer_logo_id = (int) get_theme_mod( 'footer_logo' );
		$footer_logo_markup = '';

		if ( $footer_logo_id ) {
			$footer_logo_markup = wp_get_attachment_image(
				$footer_logo_id,
				'full',
				false,
				array(
					'class'   => 'site-footer__logo-image',
					'loading' => false,
				)
			);
		}

		$footer_email = sanitize_email( (string) get_theme_mod( 'footer_email', '' ) );

		$footer_legal_items = array_values(
			array_filter(
				array(
					array(
						'label' => trim( (string) get_theme_mod( 'footer_copyright', sprintf( '©%s', gmdate( 'Y' ) ) ) ),
						'url'   => '',
					),
					array(
						'label' => trim( (string) get_theme_mod( 'footer_cvr', __( 'CVR: 12345678', 'curixus' ) ) ),
						'url'   => '',
					),
					array(
						'label' => trim( (string) get_theme_mod( 'footer_cookie_text', __( 'Cookie policy', 'curixus' ) ) ),
						'url'   => esc_url( (string) get_theme_mod( 'footer_cookie_url', '' ) ),
					),
					array(
						'label' => trim( (string) get_theme_mod( 'footer_privacy_text', __( 'Privacy policy', 'curixus' ) ) ),
						'url'   => esc_url( (string) get_theme_mod( 'footer_privacy_url', '' ) ),
					),
				),
				static function ( $item ) {
					return ! empty( $item['label'] );
				}
			)
		);

		return array(
			'logo_markup'   => $footer_logo_markup,
			'address'       => trim( (string) get_theme_mod( 'footer_address', __( 'Vesterbrogade 149, 1620 Copenhagen V', 'curixus' ) ) ),
			'contact_label' => trim( (string) get_theme_mod( 'footer_contact_label', __( 'Contact us', 'curixus' ) ) ),
			'email'         => $footer_email,
			'email_display' => $footer_email ? antispambot( $footer_email ) : '',
			'legal_items'   => $footer_legal_items,
		);
	}
endif;

if ( ! function_exists( 'curixus_project_get_header_context' ) ) :
	/**
	 * Build header content for the requested header variant.
	 *
	 * @param string $header_variant Header variant slug.
	 * @return array<string, mixed>
	 */
	function curixus_project_get_header_context( $header_variant = 'light' ) {
		$resolved_variant = 'dark' === $header_variant ? 'dark' : 'light';
		$default_logo_id  = (int) get_theme_mod( 'custom_logo' );
		$dark_logo_id     = (int) get_theme_mod( 'dark_header_logo' );
		$dark_logo_id     = $dark_logo_id ?: $default_logo_id;

		$default_logo_markup = '';
		$dark_logo_markup    = '';

		if ( $default_logo_id ) {
			$default_logo_markup = wp_get_attachment_image(
				$default_logo_id,
				'full',
				false,
				array(
					'class'   => 'site-header__logo-image',
					'loading' => false,
				)
			);
		}

		if ( $dark_logo_id ) {
			$dark_logo_markup = wp_get_attachment_image(
				$dark_logo_id,
				'full',
				false,
				array(
					'class'   => 'site-header__logo-image',
					'loading' => false,
				)
			);
		}

		return array(
			'variant'             => $resolved_variant,
			'default_logo_markup' => $default_logo_markup,
			'dark_logo_markup'    => $dark_logo_markup,
			'home_url'            => home_url( '/' ),
			'site_name'           => get_bloginfo( 'name' ),
			'has_menu'            => has_nav_menu( 'menu-1' ),
		);
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
