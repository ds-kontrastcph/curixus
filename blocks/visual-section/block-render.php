<?php 
/**
 * Block template file: block-render.php
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'visual-section-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
	$id = $block['anchor'];
}


// Create class attribute allowing for custom "className" and "align" values.
$classes = 'visual-section';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}
if (get_field( 'has_overflow_image' ) == 1) {
	$classes .= ' visual-section--has-overflow';
}

$wrapper_attributes = get_block_wrapper_attributes([
	'class' => $classes
]);

$template = array(
	array( 'core/heading', array(
		'level' => 3,
		'placeholder' => 'Title'
	) ),
	array( 'core/paragraph', array(
		'placeholder' => 'description',
	) ),
);

$allowed = array(
	'core/heading',
	'core/paragraph',
	'core/list',
	'curixus/button-group',
);

?>

<?php /* style for Preview Only */ ?>
<?php if ($is_preview): ?>
<style type="text/css">
	<?php echo '#' . $id; ?> .container {
		position: relative;
		z-index: 3;
	}
	<?php echo '#' . $id; ?> a {
		pointer-events: none;
	}
</style>
<?php endif ?>


<?php if (isset( $block['data']['preview_image_help'] )  ): ?>
	<?php 
	$fileUrl = str_replace(get_stylesheet_directory(), '', dirname(__FILE__), );
	echo '<img src="' . get_stylesheet_directory_uri() . $fileUrl . '/' . $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';
	?>
<?php else: ?>
<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="visual-section__wrap visual-section__wrap--<?php the_field( 'content_size' ); ?> visual-section__wrap--<?php the_field( 'image_direction' ); ?>">
		<div class="visual-section__visual">
			
			<?php $image = get_field( 'image' ); ?>
			<?php if ( $image ) : ?>
				<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
			<?php endif; ?>

		</div>
		<div class="visual-section__content">
			<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
			
		</div>
	</div>
</section>
<?php endif; ?>