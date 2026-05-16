/**
 * Production base gallery — Swiper + line pagination (no arrows).
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

		var el = root.querySelector('.heb-prod-base__swiper');
		var pag = root.querySelector('.heb-prod-base__pagination');
		if (!el || !pag) {
			return;
		}

		if (root._hebSwiper && typeof root._hebSwiper.destroy === 'function') {
			root._hebSwiper.destroy(true, true);
			root._hebSwiper = null;
		}

		var base = parseOpts(root);
		var opts = Object.assign({}, base, {
			pagination: {
				el: pag,
				clickable: true,
			},
		});

		root._hebSwiper = new window.Swiper(el, opts);
	}

	function initAll() {
		document.querySelectorAll('.heb-prod-base').forEach(mount);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}

	if (typeof window.elementorFrontend !== 'undefined' && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/heb_production_base_carousel.default',
			function ($scope) {
				var root =
					$scope && $scope[0]
						? $scope[0].querySelector('.heb-prod-base')
						: null;
				if (root) {
					mount(root);
				}
			}
		);
	}
})();
