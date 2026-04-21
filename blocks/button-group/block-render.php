<?php 
/**
 * Block template file: block-render.php
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$id = 'btn-group-' . $block['id'];
if ( ! empty($block['anchor'] ) ) {
	$id = $block['anchor'];
}

global $modal_storage;
$modal_storage = $modal_storage ?? [];

$classes = 'btn-group-block';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}


$wrapper_attributes = get_block_wrapper_attributes([
	'class' => $classes
]);


?>

<?php /* style for Preview Only */ ?>
<?php if ($is_preview): ?>
<style type="text/css">
	.acf-block-preview .btn-group a {
		pointer-events: none;
	}
	.modal-item {
		display: none;
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
	<div class="btn-group btn-group--<?php the_field( 'direction' ); ?> btn-group--<?php the_field( 'alignment' ); ?>" style="--gap:<?php the_field( 'gap' ); ?>px;">
		<?php if ( have_rows( 'buttons' ) ) : ?>
			<?php $counter = 0; ?>
			<?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
				<?php
				$button_classes = array(
					'btn',
					'btn--' . get_sub_field( 'button_style' ),
					'btn--' . get_sub_field( 'button_size' ),
				);
				$ga_tracking_class = get_sub_field( 'ga_tracking_class' );
				if ( ! empty( $ga_tracking_class ) ) {
					foreach ( preg_split( '/\s+/', trim( $ga_tracking_class ) ) as $ga_class_part ) {
						$sanitized_ga_class = sanitize_html_class( $ga_class_part );
						if ( '' !== $sanitized_ga_class ) {
							$button_classes[] = $sanitized_ga_class;
						}
					}
				}
				$button_class_attr = implode( ' ', $button_classes );
				?>
				<div class="btn-group__item">
					<?php $button = get_sub_field( 'button' ); ?>
					<?php $button_icon = get_sub_field( 'button_icon' ); ?>
					<?php $select_modal = get_sub_field( 'select_modal' ); ?>
					<?php if ( get_sub_field( 'has_modal' ) == 1 ) : ?>
						<button data-fancybox data-src="#modal-<?php echo esc_attr($select_modal); ?>" class="<?php echo esc_attr( $button_class_attr ); ?>" >
							<span class="btn__text"><?php the_sub_field( 'button_text' ); ?></span>
							<?php $button_icon = get_sub_field( 'button_icon' ); ?>
							<?php if ( $button_icon ) : ?>
								<span class="btn__icon btn__icon--<?php the_sub_field( 'icon_position' ); ?>"><?php sprite_svg($button_icon['ID'], '24', '24') ?></span>
							<?php endif; ?>
						</button>
						<?php 
						if ( $select_modal ) {
							// Check if modal already exists in storage
							$modal_exists = false;
							foreach ( $modal_storage as $existing_modal ) {
								if ( $existing_modal['modal_id'] == $select_modal ) {
									$modal_exists = true;
									break;
								}
							}
							
							// Add modal to storage only if it doesn't exist
							if ( ! $modal_exists ) {
								$modal_storage[] = [
									'id'       => 'modal-' . $select_modal,
									'modal_id' => $select_modal 
								];
							}
						}
						?>
					<?php else : ?>
						<?php if ( $button ) : ?>
							<a class="<?php echo esc_attr( $button_class_attr ); ?>" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>">
								<span class="btn__text"><?php echo esc_html( $button['title'] ); ?></span>
								<?php $button_icon = get_sub_field( 'button_icon' ); ?>
								<?php if ( $button_icon ) : ?>
									<span class="btn__icon btn__icon--<?php the_sub_field( 'icon_position' ); ?>"><?php sprite_svg($button_icon['ID'], '24', '24') ?></span>
								<?php endif; ?>
							</a>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<?php $counter++; ?>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>