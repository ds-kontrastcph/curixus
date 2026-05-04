<?php
/**
 * Impact Metrics block template.
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML.
 * @param bool       $is_preview True during AJAX preview.
 * @param int|string $post_id The post ID this block is saved to.
 */

defined( 'ABSPATH' ) || exit;

$id = 'impact-metrics-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'impact-metrics-section has-container';
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

$metrics = array();

if ( have_rows( 'metrics' ) ) {
	while ( have_rows( 'metrics' ) ) {
		the_row();

		$metrics[] = array(
			'value'       => get_sub_field( 'metric_value' ),
			'description' => get_sub_field( 'metric_description' ),
		);
	}
}

if ( empty( $metrics ) ) {
	$metrics = array(
		array(
			'value'       => '15+',
			'description' => "Projects supported in\nbiotech and life sciences",
		),
		array(
			'value'       => '45M+',
			'description' => "Funds invested in\nearly-stage companies",
		),
		array(
			'value'       => '1M+',
			'description' => "Potential patients\naffected through our portfolio",
		),
	);
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
		<div class="impact-metrics">
			<?php $i = 0; ?>
			<?php foreach ( $metrics as $metric ) : ?>
				<div data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 100 ); ?>" class="impact-metrics__item">
					<?php if ( ! empty( $metric['value'] ) ) : ?>
						<p class="impact-metrics__value"><?php echo esc_html( $metric['value'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $metric['description'] ) ) : ?>
						<div class="impact-metrics__description"><?php echo wp_kses_post( nl2br( esc_html( $metric['description'] ) ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php $i++; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
