<?php
/**
 * Template part for displaying text page layouts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package curixus
 */

$text_page_classes = array( 'text-page' );

if ( has_post_thumbnail() ) {
	$text_page_classes[] = 'text-page--has-featured-image';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $text_page_classes ); ?>>
	<header class="text-page__hero">
		<div class="container text-page__hero-container">
			<h1 class="text-page__title"><?php echo esc_html( get_the_title() ); ?></h1>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="text-page__featured-image-wrap">
			<div class="container text-page__featured-image-container">
				<figure class="text-page__featured-image">
					<?php
					the_post_thumbnail(
						'full',
						array(
							'class' => 'text-page__featured-image-el',
						)
					);
					?>
				</figure>
			</div>
		</div>
	<?php endif; ?>

	<div class="text-page__body">
		<div class="container text-page__body-container">
			<div class="entry-content text-page__content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'curixus' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</div>
	</div>
</article>
