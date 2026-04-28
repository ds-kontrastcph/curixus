<?php
$id = 'index-hero-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

$classes = 'index-hero has-container';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => $classes ] );

$eyebrow = get_field( 'eyebrow_text' );
$heading = get_field( 'heading' );
$image   = get_field( 'hero_image' );
?>

<?php if ( isset( $block['data']['preview_image_help'] ) ) : ?>
	<?php
	$file_url = str_replace( get_stylesheet_directory(), '', dirname( __FILE__ ) );
	echo '<img src="' . get_stylesheet_directory_uri() . $file_url . '/' . $block['data']['preview_image_help'] . '" style="width:100%; height:auto;">';
	?>
<?php else : ?>
<section id="<?php echo esc_attr( $id ); ?>" <?php echo $wrapper_attributes; ?>>

	<?php if ( $image ) : ?>
	<div class="index-hero__image-wrap" aria-hidden="true">
		<div class="index-hero__image-accent"></div>
		<div class="index-hero__image-photo">
			<img
				src="<?php echo esc_url( $image['url'] ); ?>"
				alt="<?php echo esc_attr( $image['alt'] ); ?>"
				class="index-hero__img"
				width="<?php echo esc_attr( $image['width'] ); ?>"
				height="<?php echo esc_attr( $image['height'] ); ?>"
			>
		</div>
	</div>
	<?php endif; ?>

	<div class="index-hero__inner container">
		<div class="index-hero__content">
			<?php if ( $eyebrow ) : ?>
				<p class="index-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h1 class="index-hero__heading"><?php echo esc_html( $heading ); ?></h1>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( have_rows( 'buttons' ) ) : ?>
	<div class="index-hero__cta container">
		<div class="index-hero__cta-inner">
			<?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
				<?php
				$link  = get_sub_field( 'button_link' );
				$style = get_sub_field( 'button_style' ) ?: 'primary';
				?>
				<?php if ( $link && ! empty( $link['url'] ) ) : ?>
					<a
						href="<?php echo esc_url( $link['url'] ); ?>"
						class="index-hero__btn btn btn--lg btn--<?php echo esc_attr( $style ); ?>"
						target="<?php echo esc_attr( $link['target'] ?: '_self' ); ?>"
					>
						<span class="btn__text"><?php echo esc_html( $link['title'] ); ?></span>
						<span class="btn__icon btn__icon--after" aria-hidden="true"><?php sprite_svg( 'icon-right', '14', '25' ); ?></span>
						
					</a>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</div>
	<?php endif; ?>

</section>
<?php endif; ?>
