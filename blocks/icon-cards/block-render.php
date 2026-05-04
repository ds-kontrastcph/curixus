<?php
$id = 'icon-cards-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'icon-cards';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $classes ] );
?>

<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>
	<div class="icon-cards__inner">
		<?php if ( have_rows( 'cards' ) ) : ?>
			<div class="icon-cards__grid">
				<?php $i = 0; ?>
				<?php while ( have_rows( 'cards' ) ) : the_row(); ?>
					<?php
					$icon        = get_sub_field( 'icon' );
					$title       = get_sub_field( 'title' );
					$description = get_sub_field( 'description' );
					?>
					<div data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 100 ); ?>" class="icon-cards__card">
						<?php if ( $icon ) : ?>
							<div class="icon-cards__icon" aria-hidden="true">
								<img
									src="<?php echo esc_url( $icon['url'] ); ?>"
									alt="<?php echo esc_attr( $icon['alt'] ); ?>"
									width="72"
									height="72"
									loading="lazy"
								>
							</div>
						<?php endif; ?>
						<div class="icon-cards__content">
							<?php if ( $title ) : ?>
								<p class="icon-cards__title"><?php echo esc_html( $title ); ?></p>
							<?php endif; ?>
							<?php if ( $description ) : ?>
								<p class="icon-cards__description"><?php echo esc_html( $description ); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<?php $i++; ?>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
