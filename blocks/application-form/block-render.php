<?php
/**
 * Application Form block template.
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML.
 * @param bool       $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

defined( 'ABSPATH' ) || exit;

$id = 'application-form-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'application-form-section has-container';
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
$contact_form        = get_field( 'contact_form' );
$contact_form_id     = 0;
$has_contact_form_7  = shortcode_exists( 'contact-form-7' );

if ( is_numeric( $contact_form ) ) {
	$contact_form_id = absint( $contact_form );
} elseif ( is_object( $contact_form ) && isset( $contact_form->ID ) ) {
	$contact_form_id = absint( $contact_form->ID );
}
?>

<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="application-form">
			<div class="application-form__intro">
				<?php if ( ! empty( $section_title ) ) : ?>
					<h2 class="application-form__title"><?php echo esc_html( $section_title ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $section_description ) ) : ?>
					<div class="application-form__description"><?php echo wp_kses_post( wpautop( $section_description ) ); ?></div>
				<?php endif; ?>
			</div>

			<div class="application-form__form">
				<?php if ( $has_contact_form_7 && $contact_form_id > 0 ) : ?>
					<?php echo do_shortcode( sprintf( '[contact-form-7 id="%d"]', $contact_form_id ) ); ?>
				<?php elseif ( ! $has_contact_form_7 && $is_preview ) : ?>
					<p class="application-form__placeholder">Contact Form 7 must be active to render this block.</p>
				<?php elseif ( $is_preview ) : ?>
					<p class="application-form__placeholder">Select a Contact Form 7 form in the block settings.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
