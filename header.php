<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until the page content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package curixus
 */

$curixus_header_variant = (string) get_query_var( 'curixus_header_variant', 'light' );
$curixus_header_context = curixus_project_get_header_context( $curixus_header_variant );
$curixus_header_classes = array(
	'site-header',
	'site-header--' . $curixus_header_context['variant'],
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'curixus' ); ?></a>

	<header
		id="masthead"
		class="<?php echo esc_attr( implode( ' ', $curixus_header_classes ) ); ?>"
		data-site-header
	>
		<div class="site-header__container">
			<div class="site-header__brand">
				<a
					class="site-header__logo-link"
					href="<?php echo esc_url( $curixus_header_context['home_url'] ); ?>"
					rel="home"
					aria-label="<?php echo esc_attr( $curixus_header_context['site_name'] ); ?>"
				>
					<span class="site-header__logo-mark site-header__logo-mark--default">
						<?php if ( ! empty( $curixus_header_context['default_logo_markup'] ) ) : ?>
							<?php echo $curixus_header_context['default_logo_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<span class="site-header__logo-text"><?php echo esc_html( $curixus_header_context['site_name'] ); ?></span>
						<?php endif; ?>
					</span>

					<span class="site-header__logo-mark site-header__logo-mark--dark">
						<?php if ( ! empty( $curixus_header_context['dark_logo_markup'] ) ) : ?>
							<?php echo $curixus_header_context['dark_logo_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php elseif ( ! empty( $curixus_header_context['default_logo_markup'] ) ) : ?>
							<?php echo $curixus_header_context['default_logo_markup']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<span class="site-header__logo-text"><?php echo esc_html( $curixus_header_context['site_name'] ); ?></span>
						<?php endif; ?>
					</span>
				</a>
			</div>

			<?php if ( $curixus_header_context['has_menu'] ) : ?>
				<button
					class="site-header__toggle"
					type="button"
					aria-controls="primary-menu"
					aria-expanded="false"
					aria-label="<?php esc_attr_e( 'Toggle navigation', 'curixus' ); ?>"
					data-header-toggle
				>
					<span class="site-header__toggle-icon" aria-hidden="true"></span>
				</button>

				<div class="site-header__panel" data-header-panel>
					<nav class="site-header__navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'curixus' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'container'      => false,
								'fallback_cb'    => false,
								'menu_class'     => 'site-header__menu',
								'menu_id'        => 'primary-menu',
							)
						);
						?>
					</nav>
				</div>
			<?php endif; ?>
		</div>
	</header><!-- #masthead -->
