<?php
/**
 * Template Name: Text Page
 * Template Post Type: page
 *
 * @package curixus
 */

get_header( 'dark' );
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'text-page' );

		endwhile;
		?>

	</main><!-- #main -->

<?php
get_footer();
