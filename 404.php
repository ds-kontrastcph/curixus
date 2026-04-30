<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package curixus
 */

get_header();
?>

	<main id="primary" class="site-main error-404-page">
		<section class="error-404 not-found" aria-labelledby="error-404-title">
			<div class="error-404__container container">
				<p class="error-404__code" aria-hidden="true"><?php esc_html_e( '404', 'curixus' ); ?></p>

				<div class="error-404__content">
					<header class="error-404__header">
						<h1 id="error-404-title" class="error-404__title"><?php esc_html_e( 'Page not found', 'curixus' ); ?></h1>
						<p class="error-404__text"><?php esc_html_e( 'It may have been moved or no longer exists. Try using the menu at the top to find what you\'re looking for.', 'curixus' ); ?></p>
					</header>

					<a class="error-404__button btn btn--lg btn--dark-transparent" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<span class="btn__text"><?php esc_html_e( 'Go to front page', 'curixus' ); ?></span>
						<span class="btn__icon btn__icon--after" aria-hidden="true"><?php sprite_svg( 'icon-right', '6', '12' ); ?></span>
					</a>
				</div>
			</div>
		</section>

	</main><!-- #main -->

<?php
get_footer();
