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
$id = 'approach-section-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
	$id = $block['anchor'];
}


// Create class attribute allowing for custom "className" and "align" values.
$classes = 'approach-section has-container';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}

$wrapper_attributes = get_block_wrapper_attributes([
	'class' => $classes
]);


?>

<?php /* style for Preview Only */ ?>
<?php if ($is_preview): ?>
<style type="text/css">
	<?php echo '#' . $id; ?> .container {
		position: relative;
		padding-block: 120px;
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
	<div class="container">
		<div class="approach-section__head">
			<?php if (get_field( 'section_title' )): ?>
				<h2 class="approach-section__title"><?php the_field( 'section_title' ); ?></h2>
			<?php endif ?>
			<?php if (get_field( 'section_description' )): ?>
				<div class="approach-section__description"><?php the_field( 'section_description' ); ?></div>
			<?php endif ?>
		</div>
		<?php if ( have_rows( 'items' ) ) : ?>
			<ul class="approach-list">
				<?php while ( have_rows( 'items' ) ) : the_row(); ?>
					<?php $icon = get_sub_field( 'icon' ); ?>
					<li class="approach-list__item">
						<div class="approach-list__icon">
							<?php if ( $icon ) : ?>
								<img src="<?php echo esc_url( $icon['url'] ); ?>" alt="<?php echo esc_attr( $icon['alt'] ); ?>" />
							<?php endif; ?>
						</div>
						<div class="approach-list__frame">
							<?php if (get_sub_field( 'title' )): ?>
								<div class="h4 approach-list__title"><?php the_sub_field( 'title' ); ?></div>
							<?php endif ?>
							<?php if (get_sub_field( 'description' )): ?>
								<div class="approach-list__description"><?php the_sub_field( 'description' ); ?></div>
							<?php endif ?>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>