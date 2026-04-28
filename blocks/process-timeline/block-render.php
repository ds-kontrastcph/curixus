<?php
/**
 * Process Timeline block template.
 *
 * @param array      $block      The block settings and attributes.
 * @param string     $content    The block inner HTML.
 * @param bool       $is_preview True during AJAX preview.
 * @param int|string $post_id    The post ID this block is saved to.
 */

defined( 'ABSPATH' ) || exit;

$id = 'process-timeline-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'process-timeline-section has-container';
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

$section_title       = get_field( 'section_title' );
$section_description = get_field( 'section_description' );
?>

<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="process-timeline">
			<?php if ( ! empty( $section_title ) || ! empty( $section_description ) ) : ?>
				<header class="process-timeline__header">
					<?php if ( ! empty( $section_title ) ) : ?>
						<h2 class="process-timeline__title"><?php echo esc_html( $section_title ); ?></h2>
					<?php endif; ?>

					<?php if ( ! empty( $section_description ) ) : ?>
						<p class="process-timeline__subtitle"><?php echo esc_html( $section_description ); ?></p>
					<?php endif; ?>
				</header>
			<?php endif; ?>

			<?php if ( have_rows( 'steps' ) ) : ?>
				<ol class="process-timeline__steps" data-step-count="<?php echo esc_attr( count( get_field( 'steps' ) ?: array() ) ); ?>">
					<?php
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
									'class' => 'process-timeline__badge-image',
									'alt'   => esc_attr( $step_icon_image['alt'] ?: $step_title ),
								)
							);
						}
						?>
						<li class="process-timeline__step process-timeline__step--<?php echo esc_attr( $step_variant ); ?>">
							<div class="process-timeline__badge">
								<?php if ( 'icon' === $step_variant ) : ?>
									<span class="process-timeline__badge-icon" aria-hidden="true"><?php echo $step_icon_markup; ?></span>
								<?php else : ?>
									<span class="process-timeline__badge-label"><?php echo esc_html( $step_label ); ?></span>
								<?php endif; ?>
							</div>

							<div class="process-timeline__step-content">
								<?php if ( ! empty( $step_title ) ) : ?>
									<h3 class="process-timeline__step-title"><?php echo esc_html( $step_title ); ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $step_description ) ) : ?>
									<p class="process-timeline__step-description"><?php echo esc_html( $step_description ); ?></p>
								<?php endif; ?>
							</div>
						</li>
					<?php endwhile; ?>
				</ol>
			<?php endif; ?>
		</div>
	</div>
</section>
