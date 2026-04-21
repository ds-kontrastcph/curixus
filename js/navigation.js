/**
 * File navigation.js.
 *
 * Handles the sticky site header and mobile navigation state.
 */
( function() {
	'use strict';

	const MOBILE_BREAKPOINT = 991;
	const COMPACT_SCROLL_OFFSET = 24;
	const HEADER_MENU_OPEN_CLASS = 'site-header--menu-open';
	const BODY_MENU_OPEN_CLASS = 'has-open-header-menu';

	function initHeaderNavigation() {
		const siteHeader = document.querySelector( '[data-site-header]' );

		if ( ! siteHeader ) {
			return;
		}

		const menuToggle = siteHeader.querySelector( '[data-header-toggle]' );
		const menuPanel = siteHeader.querySelector( '[data-header-panel]' );
		const menuLinks = siteHeader.querySelectorAll( '.site-header__menu a' );
		let isScrollTicking = false;

		function isMobileViewport() {
			return window.innerWidth <= MOBILE_BREAKPOINT;
		}

		function isMenuOpen() {
			return siteHeader.classList.contains( HEADER_MENU_OPEN_CLASS );
		}

		function syncBodyScrollState() {
			document.body.classList.toggle( BODY_MENU_OPEN_CLASS, isMenuOpen() && isMobileViewport() );
		}

		function setMenuState( shouldOpen ) {
			if ( ! menuToggle || ! menuPanel ) {
				return;
			}

			siteHeader.classList.toggle( HEADER_MENU_OPEN_CLASS, shouldOpen );
			menuToggle.setAttribute( 'aria-expanded', shouldOpen ? 'true' : 'false' );
			syncBodyScrollState();
		}

		function closeMenu() {
			setMenuState( false );
		}

		function updateCompactState() {
			siteHeader.classList.toggle( 'site-header--compact', window.scrollY > COMPACT_SCROLL_OFFSET );
		}

		function handleScroll() {
			if ( isScrollTicking ) {
				return;
			}

			isScrollTicking = true;

			window.requestAnimationFrame(
				function() {
					updateCompactState();
					isScrollTicking = false;
				}
			);
		}

		function handleResize() {
			updateCompactState();

			if ( ! isMobileViewport() ) {
				closeMenu();
				return;
			}

			syncBodyScrollState();
		}

		if ( menuToggle ) {
			menuToggle.addEventListener(
				'click',
				function() {
					setMenuState( ! isMenuOpen() );
				}
			);
		}

		document.addEventListener(
			'click',
			function( event ) {
				if ( ! isMobileViewport() || ! isMenuOpen() ) {
					return;
				}

				if ( siteHeader.contains( event.target ) ) {
					return;
				}

				closeMenu();
			}
		);

		document.addEventListener(
			'keydown',
			function( event ) {
				if ( 'Escape' === event.key && isMenuOpen() ) {
					closeMenu();
				}
			}
		);

		menuLinks.forEach(
			function( menuLink ) {
				menuLink.addEventListener(
					'click',
					function() {
						if ( isMobileViewport() ) {
							closeMenu();
						}
					}
				);
			}
		);

		window.addEventListener( 'scroll', handleScroll, { passive: true } );
		window.addEventListener( 'resize', handleResize );

		updateCompactState();
		syncBodyScrollState();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initHeaderNavigation );
	} else {
		initHeaderNavigation();
	}
}() );
