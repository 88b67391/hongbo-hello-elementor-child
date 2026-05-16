/**
 * Qualification carousel — Swiper 11 (bundle provides global Swiper).
 */
(function () {
	'use strict';

	function parseOpts(root) {
		var raw = root.getAttribute('data-heb-swiper');
		if (!raw) {
			return {};
		}
		try {
			return JSON.parse(raw);
		} catch (e) {
			return {};
		}
	}

	function mount(root) {
		if (!root || typeof window.Swiper === 'undefined') {
			return;
		}

		var el = root.querySelector('.heb-qual-carousel__swiper');
		var prev = root.querySelector('.swiper-button-prev');
		var next = root.querySelector('.swiper-button-next');
		if (!el || !prev || !next) {
			return;
		}

		if (root._hebSwiper && typeof root._hebSwiper.destroy === 'function') {
			root._hebSwiper.destroy(true, true);
			root._hebSwiper = null;
		}

		var base = parseOpts(root);
		var opts = Object.assign({}, base, {
			navigation: {
				prevEl: prev,
				nextEl: next,
			},
		});

		root._hebSwiper = new window.Swiper(el, opts);
	}

	function initAll() {
		document.querySelectorAll('.heb-qual-carousel').forEach(mount);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}

	if (typeof window.elementorFrontend !== 'undefined' && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/heb_qualification_carousel.default',
			function ($scope) {
				var root =
					$scope && $scope[0]
						? $scope[0].querySelector('.heb-qual-carousel')
						: null;
				if (root) {
					mount(root);
				}
			}
		);
	}
})();
