<?php
/**
 * The template for displaying single portfolio items.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
