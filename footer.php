<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package curixus
 */

$footer_context = curixus_project_get_footer_context();
?>

	<footer id="colophon" class="site-footer">
		<div class="site-footer__main">
			<div class="container">
				<div class="site-footer__top">
					<div class="site-footer__brand">
						<div class="site-footer__logo">
							<a class="site-footer__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
								<?php if ( ! empty( $footer_context['logo_markup'] ) ) : ?>
									<?php echo wp_kses_post( $footer_context['logo_markup'] ); ?>
								<?php else : ?>
									<span class="site-footer__logo-text"><?php bloginfo( 'name' ); ?></span>
								<?php endif; ?>
							</a>
						</div>
						<?php if ( ! empty( $footer_context['address'] ) ) : ?>
							<p class="site-footer__address"><?php echo esc_html( $footer_context['address'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $footer_context['contact_label'] ) || ! empty( $footer_context['email'] ) ) : ?>
						<div class="site-footer__contact">
							<?php if ( ! empty( $footer_context['contact_label'] ) ) : ?>
								<p class="site-footer__eyebrow"><?php echo esc_html( $footer_context['contact_label'] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $footer_context['email'] ) ) : ?>
								<a class="site-footer__email" href="mailto:<?php echo esc_attr( $footer_context['email_display'] ); ?>">
									<?php echo esc_html( $footer_context['email_display'] ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<?php if ( ! empty( $footer_context['legal_items'] ) ) : ?>
			<div class="site-footer__meta">
				<div class="container">
					<ul class="site-footer__meta-list">
						<?php foreach ( $footer_context['legal_items'] as $footer_legal_item ) : ?>
								<li class="site-footer__meta-item">
									<?php if ( ! empty( $footer_legal_item['url'] ) ) : ?>
										<a
											class="site-footer__meta-link"
											href="<?php echo esc_url( $footer_legal_item['url'] ); ?>"
											<?php if ( ! empty( $footer_legal_item['target'] ) ) : ?>
												target="<?php echo esc_attr( $footer_legal_item['target'] ); ?>"
												<?php if ( '_blank' === $footer_legal_item['target'] ) : ?>
													rel="noopener noreferrer"
												<?php endif; ?>
											<?php endif; ?>
										>
											<?php echo esc_html( $footer_legal_item['label'] ); ?>
										</a>
									<?php else : ?>
									<span class="site-footer__meta-text"><?php echo esc_html( $footer_legal_item['label'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
