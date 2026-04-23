<?php
/**
 * Portfolio Slider block template.
 *
 * @param array       $block The block settings and attributes.
 * @param string      $content The block inner HTML.
 * @param bool        $is_preview True during AJAX preview.
 * @param int|string  $post_id The post ID this block is saved to.
 */

$id = 'portfolio-slider-section-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'portfolio-slider-section has-container';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => $classes,
	)
);

$portfolio_query = new WP_Query(
	array(
		'post_type'           => 'portfolio',
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'orderby'             => 'menu_order date',
		'order'               => 'ASC',
		'ignore_sticky_posts' => true,
	)
);

$section_title       = get_field( 'section_title' );
$section_description = get_field( 'section_description' );
?>

<?php if ( $is_preview ) : ?>
<style type="text/css">
	<?php echo '#' . esc_attr( $id ); ?> .owl-nav button {
		pointer-events: none;
	}
	<?php echo '#' . esc_attr( $id ); ?> .portfolio-slider {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 24px;
	}
	<?php echo '#' . esc_attr( $id ); ?> .portfolio-card:nth-child(n+4) {
		display: none;
	}
	<?php echo '#' . esc_attr( $id ); ?> .portfolio-card__overlay {
		pointer-events: none;
	}
</style>
<?php endif; ?>

<?php if ( isset( $block['data']['preview_image_help'] ) ) : ?>
	<?php
	$file_url = str_replace( get_stylesheet_directory(), '', dirname( __FILE__ ) );
	echo '<img src="' . esc_url( get_stylesheet_directory_uri() . $file_url . '/' . $block['data']['preview_image_help'] ) . '" style="width:100%;height:auto;" alt="Block preview">';
	?>
<?php else : ?>
<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="portfolio-slider-section__head">
			<?php if ( ! empty( $section_title ) ) : ?>
				<h2 class="portfolio-slider-section__title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $section_description ) ) : ?>
				<div class="portfolio-slider-section__description">
					<?php echo wp_kses_post( $section_description ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $portfolio_query->have_posts() ) : ?>
			<div class="portfolio-slider js-portfolio-slider owl-carousel owl-theme">
				<?php
				while ( $portfolio_query->have_posts() ) :
					$portfolio_query->the_post();
					$featured_image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
					$featured_image_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
					$category_terms     = get_the_terms( get_the_ID(), 'portfolio_category' );
					$category_name      = '';

					if ( ! empty( $category_terms ) && ! is_wp_error( $category_terms ) ) {
						$first_category = reset( $category_terms );
						if ( $first_category instanceof WP_Term ) {
							$category_name = $first_category->name;
						}
					}
					?>
					<article class="portfolio-card">
						<?php if ( ! empty( $featured_image_url ) ) : ?>
							<img class="portfolio-card__image" src="<?php echo esc_url( $featured_image_url ); ?>" alt="<?php echo esc_attr( $featured_image_alt ? $featured_image_alt : get_the_title() ); ?>">
						<?php endif; ?>
						<a class="portfolio-card__overlay" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Read case study: %s', 'curixus' ), get_the_title() ) ); ?>">
							<div class="portfolio-card__meta">
								<?php if ( ! empty( $category_name ) ) : ?>
									<div class="portfolio-card__category"><?php echo esc_html( $category_name ); ?></div>
								<?php endif; ?>
								<h3 class="portfolio-card__title"><?php the_title(); ?></h3>
								<?php if ( has_excerpt() ) : ?>
									<div class="portfolio-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></div>
								<?php endif; ?>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="portfolio-slider-section__empty"><?php esc_html_e( 'Portfolio posts were not found yet.', 'curixus' ); ?></div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
