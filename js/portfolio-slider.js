(function ($) {
	'use strict';

	$(function () {
		var SLIDE_MARGIN = 8;
		var $portfolioSliders = $('.js-portfolio-slider');

		if (!$portfolioSliders.length || 'function' !== typeof $.fn.owlCarousel) {
			return;
		}

		$portfolioSliders.each(function () {
			var $slider = $(this);

			if ($slider.hasClass('owl-loaded')) {
				return;
			}

			$slider.owlCarousel({
				autoWidth: true,
				margin: SLIDE_MARGIN,
				nav: false,
				dots: false,
				loop: false,
				autoHeight: false,
				navText: ['<span aria-hidden="true">&#8592;</span>', '<span aria-hidden="true">&#8594;</span>']
			});
		});
	});
})(jQuery);
