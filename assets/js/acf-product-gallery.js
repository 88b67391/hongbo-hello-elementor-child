/**
 * Thumbnail clicks swap the main product image.
 */
(function () {
	'use strict';

	function initGallery(root) {
		var main = root.querySelector('.heb-acf-gallery__main');
		var thumbs = root.querySelectorAll('.heb-acf-gallery__thumb');
		if (!main || !thumbs.length) {
			return;
		}

		function activate(btn) {
			var src = btn.getAttribute('data-main-src');
			var srcset = btn.getAttribute('data-main-srcset') || '';
			var sizes = btn.getAttribute('data-main-sizes') || '';
			var alt = btn.getAttribute('data-main-alt') || '';

			if (!src) {
				return;
			}

			main.src = src;
			if (srcset) {
				main.srcset = srcset;
				main.sizes = sizes;
			} else {
				main.removeAttribute('srcset');
				main.removeAttribute('sizes');
			}
			main.alt = alt;

			thumbs.forEach(function (t) {
				var on = t === btn;
				t.classList.toggle('is-active', on);
				t.setAttribute('aria-selected', on ? 'true' : 'false');
			});
		}

		thumbs.forEach(function (btn) {
			btn.addEventListener('click', function () {
				activate(btn);
			});
		});
	}

	document.querySelectorAll('[data-heb-acf-gallery]').forEach(initGallery);
})();
