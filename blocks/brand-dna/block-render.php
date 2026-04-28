<?php
/**
 * Brand DNA block template.
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML.
 * @param bool       $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

defined( 'ABSPATH' ) || exit;

$id = 'brand-dna-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'brand-dna-section has-container';
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

$eyebrow = get_field( 'eyebrow' );
if ( empty( $eyebrow ) ) {
	$eyebrow = 'Our name reflects our DNA';
}

$description = get_field( 'description' );
if ( empty( $description ) ) {
	$description = 'Curixus acts as the hub where curiosity transforms into strategic connections. We delve deep into the core of technology to create the precise links to capital and networks needed to scale future solutions with care and insight.';
}

$first_word_primary = get_field( 'first_word_primary' );
if ( empty( $first_word_primary ) ) {
	$first_word_primary = 'Curi';
}

$first_word_muted = get_field( 'first_word_muted' );
if ( empty( $first_word_muted ) ) {
	$first_word_muted = 'ositas';
}

$first_caption = get_field( 'first_caption' );
if ( empty( $first_caption ) ) {
	$first_caption = 'Curiosity drives discovery';
}

$second_word_muted = get_field( 'second_word_muted' );
if ( empty( $second_word_muted ) ) {
	$second_word_muted = 'Ne';
}

$second_word_primary = get_field( 'second_word_primary' );
if ( empty( $second_word_primary ) ) {
	$second_word_primary = 'xus';
}

$second_caption = get_field( 'second_caption' );
if ( empty( $second_caption ) ) {
	$second_caption = 'Connections turn ideas into real-world impact';
}
?>

<?php if ( $is_preview ) : ?>
<style type="text/css">
	<?php echo '#' . esc_attr( $id ); ?> {
		padding-block: 0 !important;
	}
</style>
<?php endif; ?>

<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="brand-dna">
			<div class="brand-dna__intro">
				<p class="brand-dna__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<div class="brand-dna__description"><?php echo wp_kses_post( nl2br( esc_html( $description ) ) ); ?></div>
			</div>

			<div class="brand-dna__equation" aria-label="<?php esc_attr_e( 'Brand name explanation', 'curixus' ); ?>">
				<div class="brand-dna__term">
					<p class="brand-dna__word">
						<span class="brand-dna__word-main"><?php echo esc_html( $first_word_primary ); ?></span><span class="brand-dna__word-muted"><?php echo esc_html( $first_word_muted ); ?></span>
					</p>
					<p class="brand-dna__caption"><?php echo esc_html( $first_caption ); ?></p>
				</div>

				<span class="brand-dna__plus" aria-hidden="true"></span>

				<div class="brand-dna__term">
					<p class="brand-dna__word">
						<span class="brand-dna__word-muted"><?php echo esc_html( $second_word_muted ); ?></span><span class="brand-dna__word-main"><?php echo esc_html( $second_word_primary ); ?></span>
					</p>
					<p class="brand-dna__caption"><?php echo esc_html( $second_caption ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
