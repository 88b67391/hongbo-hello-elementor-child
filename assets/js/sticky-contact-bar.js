/**
 * Coarse pointer + side rail: first tap expands label, second follows link.
 * Mobile bottom bar: single tap navigates (no two-step).
 */
(function () {
	'use strict';

	function isMobileBottomBar(root) {
		return (
			root.classList.contains('heb-sticky-contact--mobile-bottom') &&
			window.matchMedia('(max-width: 767px)').matches
		);
	}

	function init(root) {
		var items = root.querySelectorAll('.heb-sticky-contact__item[data-heb-contact-item]');
		if (!items.length) {
			return;
		}

		var mq = window.matchMedia('(hover: none), (pointer: coarse)');

		function isCoarse() {
			return mq.matches;
		}

		items.forEach(function (item) {
			item.addEventListener(
				'click',
				function (e) {
					if (isMobileBottomBar(root)) {
						return;
					}
					if (!isCoarse()) {
						return;
					}
					var href = item.getAttribute('href');
					var expanded = item.classList.contains('is-touch-open');
					if (!expanded) {
						e.preventDefault();
						items.forEach(function (other) {
							if (other !== item) {
								other.classList.remove('is-touch-open');
							}
						});
						item.classList.add('is-touch-open');
						return;
					}
					if (href === '#' || href === '') {
						e.preventDefault();
					}
				},
				true
			);
		});

		document.addEventListener('click', function (e) {
			if (isMobileBottomBar(root)) {
				return;
			}
			if (!isCoarse()) {
				return;
			}
			if (!root.contains(e.target)) {
				items.forEach(function (item) {
					item.classList.remove('is-touch-open');
				});
			}
		});
	}

	document.querySelectorAll('[data-heb-sticky-contact]').forEach(init);
})();
