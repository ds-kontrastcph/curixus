<?php
/**
 * Application Process block template.
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML.
 * @param bool       $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

defined( 'ABSPATH' ) || exit;

$id = 'application-process-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'application-process-section has-container';
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

$section_title = get_field( 'section_title' );
$cta_button    = get_field( 'cta_button' );
?>

<?php if ( $is_preview ) : ?>
<style type="text/css">
	<?php echo '#' . esc_attr( $id ); ?> a {
		pointer-events: none;
	}
</style>
<?php endif; ?>

<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="application-process">
			<div data-aos="fade-up" class="application-process__content">
				<?php if ( ! empty( $section_title ) ) : ?>
					<h2 class="application-process__title"><?php echo esc_html( $section_title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $cta_button['url'] ) ) : ?>
					<a
						class="application-process__btn btn btn--primary"
						href="<?php echo esc_url( $cta_button['url'] ); ?>"
						target="<?php echo esc_attr( $cta_button['target'] ?: '_self' ); ?>"
					>
						<span class="btn__text"><?php echo esc_html( $cta_button['title'] ); ?></span>
						<span class="btn__icon btn__icon--after" aria-hidden="true"><?php sprite_svg( 'icon-right', '14', '25' ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( have_rows( 'steps' ) ) : ?>
				<div class="application-process__steps">
					<?php
					$i = 0;
					while ( have_rows( 'steps' ) ) :
						the_row();

						$step_variant     = get_sub_field( 'step_variant' ) ?: 'number';
						$step_label       = get_sub_field( 'step_label' );
						$step_title       = get_sub_field( 'step_title' );
						$step_description = get_sub_field( 'step_description' );
						$step_icon_image  = get_sub_field( 'step_icon_image' );
						$step_icon_markup = '';

						if ( 'icon' === $step_variant && ! empty( $step_icon_image['ID'] ) ) {
							$step_icon_markup = wp_get_attachment_image(
								$step_icon_image['ID'],
								'full',
								false,
								array(
									'class' => 'application-process__badge-image',
									'alt'   => esc_attr( $step_icon_image['alt'] ?: $step_title ),
								)
							);
						}
						?>
						<div data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 100 ); ?>" class="application-process__step application-process__step--<?php echo esc_attr( $step_variant ); ?>">
							<div class="application-process__badge">
								<?php if ( 'icon' === $step_variant ) : ?>
									<span class="application-process__badge-icon" aria-hidden="true"><?php echo $step_icon_markup; ?></span>
								<?php else : ?>
									<span class="application-process__badge-label"><?php echo esc_html( $step_label ); ?></span>
								<?php endif; ?>
							</div>
							<div class="application-process__step-content">
								<?php if ( ! empty( $step_title ) ) : ?>
									<h3 class="application-process__step-title"><?php echo esc_html( $step_title ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $step_description ) ) : ?>
									<div class="application-process__step-description"><?php echo esc_html( $step_description ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php $i++; ?>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
