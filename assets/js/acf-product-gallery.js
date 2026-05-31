/**
 * Product gallery with thumbnail swapping and a navigable lightbox.
 */
(function () {
	'use strict';

	let activeGallery = null;
	let activeTrigger = null;
	let lightboxLoadId = 0;
	let modal = null;

	function toArray(list) {
		return Array.from(list);
	}

	function wrapIndex(index, length) {
		return (index + length) % length;
	}

	function getSlideFromButton(btn) {
		const thumbImage = btn.querySelector('img');
		const mainSrc = btn.getAttribute('data-main-src') || '';
		const fullSrc = btn.getAttribute('data-full-src') || mainSrc;

		if (!mainSrc) {
			return null;
		}

		return {
			button: btn,
			mainSrc: mainSrc,
			mainSrcset: btn.getAttribute('data-main-srcset') || '',
			mainSizes: btn.getAttribute('data-main-sizes') || '',
			fullSrc: fullSrc,
			fullSrcset: btn.getAttribute('data-full-srcset') || '',
			fullSizes: btn.getAttribute('data-full-sizes') || '',
			alt: btn.getAttribute('data-main-alt') || '',
			thumbSrc: thumbImage ? thumbImage.currentSrc || thumbImage.src : fullSrc
		};
	}

	function ensureModal() {
		if (modal) {
			return modal;
		}

		modal = document.createElement('div');
		modal.className = 'heb-acf-gallery-lightbox';
		modal.setAttribute('role', 'dialog');
		modal.setAttribute('aria-modal', 'true');
		modal.setAttribute('aria-label', 'Product image preview');
		modal.setAttribute('hidden', '');
		modal.innerHTML = [
			'<button type="button" class="heb-acf-gallery-lightbox__close" aria-label="Close image preview">',
			'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" /></svg>',
			'</button>',
			'<button type="button" class="heb-acf-gallery-lightbox__nav heb-acf-gallery-lightbox__nav--prev" aria-label="Previous image">',
			'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7" /></svg>',
			'</button>',
			'<div class="heb-acf-gallery-lightbox__frame">',
			'<img class="heb-acf-gallery-lightbox__image" alt="" />',
			'<span class="heb-acf-gallery-lightbox__loader" aria-hidden="true"></span>',
			'</div>',
			'<button type="button" class="heb-acf-gallery-lightbox__nav heb-acf-gallery-lightbox__nav--next" aria-label="Next image">',
			'<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7" /></svg>',
			'</button>',
			'<div class="heb-acf-gallery-lightbox__counter" aria-live="polite"></div>',
			'<div class="heb-acf-gallery-lightbox__thumbs" role="tablist" aria-label="Product image thumbnails"></div>'
		].join('');

		modal.addEventListener('click', handleModalClick);
		document.addEventListener('keydown', handleModalKeydown);

		document.body.appendChild(modal);
		return modal;
	}

	function closestTarget(event, selector) {
		return event.target && event.target.closest ? event.target.closest(selector) : null;
	}

	function isModalOpen() {
		return modal && !modal.hasAttribute('hidden');
	}

	function preloadSlide(slide) {
		if (!slide || !slide.fullSrc) {
			return;
		}

		const image = new Image();
		if (slide.fullSrcset) {
			image.srcset = slide.fullSrcset;
			image.sizes = slide.fullSizes || '100vw';
		}
		image.src = slide.fullSrc;
	}

	function preloadAdjacentSlides() {
		if (!activeGallery || activeGallery.slides.length < 2) {
			return;
		}

		const length = activeGallery.slides.length;
		preloadSlide(activeGallery.slides[wrapIndex(activeGallery.index + 1, length)]);
		preloadSlide(activeGallery.slides[wrapIndex(activeGallery.index - 1, length)]);
	}

	function buildLightboxThumbs(gallery) {
		const dialog = ensureModal();
		const rail = dialog.querySelector('.heb-acf-gallery-lightbox__thumbs');

		if (!rail) {
			return;
		}

		rail.innerHTML = gallery.slides.map((slide, index) => {
			return [
				'<button type="button" class="heb-acf-gallery-lightbox__thumb" role="tab" aria-selected="false" data-lightbox-index="',
				index,
				'">',
				'<img src="',
				slide.thumbSrc,
				'" alt="" loading="lazy" decoding="async" />',
				'</button>'
			].join('');
		}).join('');
	}

	function renderLightboxSlide() {
		const dialog = ensureModal();
		const preview = dialog.querySelector('.heb-acf-gallery-lightbox__image');
		const counter = dialog.querySelector('.heb-acf-gallery-lightbox__counter');
		const thumbs = dialog.querySelectorAll('.heb-acf-gallery-lightbox__thumb');

		if (!activeGallery || !activeGallery.slides.length || !preview) {
			return;
		}

		const slide = activeGallery.slides[activeGallery.index];
		const requestId = ++lightboxLoadId;
		dialog.classList.add('is-loading');
		preview.onload = () => {
			if (requestId !== lightboxLoadId) {
				return;
			}
			dialog.classList.remove('is-loading');
		};
		preview.onerror = () => {
			if (requestId !== lightboxLoadId) {
				return;
			}
			dialog.classList.remove('is-loading');
		};

		if (slide.fullSrcset) {
			preview.srcset = slide.fullSrcset;
			preview.sizes = slide.fullSizes || '100vw';
		} else {
			preview.removeAttribute('srcset');
			preview.removeAttribute('sizes');
		}
		preview.src = slide.fullSrc;
		preview.alt = slide.alt;

		window.setTimeout(() => {
			if (requestId === lightboxLoadId && preview.complete && preview.naturalWidth > 0) {
				dialog.classList.remove('is-loading');
			}
		}, 0);

		if (counter) {
			counter.textContent = (activeGallery.index + 1) + ' / ' + activeGallery.slides.length;
		}

		toArray(thumbs).forEach((thumb, index) => {
			const on = index === activeGallery.index;
			thumb.classList.toggle('is-active', on);
			thumb.setAttribute('aria-selected', on ? 'true' : 'false');
		});

		dialog.classList.toggle('is-single', activeGallery.slides.length < 2);
		preloadAdjacentSlides();
	}

	function showLightboxSlide(index) {
		if (!activeGallery || !activeGallery.slides.length) {
			return;
		}

		activeGallery.activate(wrapIndex(index, activeGallery.slides.length), {
			skipLightbox: true
		});

		renderLightboxSlide();
	}

	function openModal(gallery, trigger) {
		const dialog = ensureModal();
		const close = dialog.querySelector('.heb-acf-gallery-lightbox__close');

		activeGallery = gallery;
		activeTrigger = trigger;

		buildLightboxThumbs(gallery);
		renderLightboxSlide();

		dialog.removeAttribute('hidden');
		document.documentElement.classList.add('heb-acf-gallery-lightbox-open');
		if (close) {
			close.focus();
		}
	}

	function closeModal() {
		if (!modal) {
			return;
		}

		const preview = modal.querySelector('.heb-acf-gallery-lightbox__image');
		modal.setAttribute('hidden', '');
		document.documentElement.classList.remove('heb-acf-gallery-lightbox-open');
		if (preview) {
			preview.removeAttribute('src');
			preview.removeAttribute('srcset');
			preview.removeAttribute('sizes');
		}
		if (activeTrigger) {
			activeTrigger.focus();
		}

		activeGallery = null;
		activeTrigger = null;
	}

	function handleModalClick(event) {
		const close = closestTarget(event, '.heb-acf-gallery-lightbox__close');
		const next = closestTarget(event, '.heb-acf-gallery-lightbox__nav--next');
		const prev = closestTarget(event, '.heb-acf-gallery-lightbox__nav--prev');
		const thumb = closestTarget(event, '.heb-acf-gallery-lightbox__thumb');

		if (event.target === modal || close) {
			closeModal();
			return;
		}

		if (!activeGallery || activeGallery.slides.length < 2) {
			return;
		}

		if (prev) {
			showLightboxSlide(activeGallery.index - 1);
			return;
		}

		if (next) {
			showLightboxSlide(activeGallery.index + 1);
			return;
		}

		if (thumb) {
			showLightboxSlide(parseInt(thumb.getAttribute('data-lightbox-index'), 10) || 0);
		}
	}

	function handleModalKeydown(event) {
		if (!isModalOpen()) {
			return;
		}

		if (event.key === 'Escape') {
			closeModal();
			return;
		}

		if (!activeGallery || activeGallery.slides.length < 2) {
			return;
		}

		if (event.key === 'ArrowLeft') {
			event.preventDefault();
			showLightboxSlide(activeGallery.index - 1);
		}

		if (event.key === 'ArrowRight') {
			event.preventDefault();
			showLightboxSlide(activeGallery.index + 1);
		}
	}

	function initGallery(root) {
		const main = root.querySelector('.heb-acf-gallery__main');
		const trigger = root.querySelector('[data-heb-gallery-open]');
		const thumbs = toArray(root.querySelectorAll('.heb-acf-gallery__thumb'));

		if (!main || !thumbs.length) {
			return;
		}

		const gallery = {
			root: root,
			main: main,
			trigger: trigger,
			thumbs: thumbs,
			slides: thumbs.map(getSlideFromButton).filter(Boolean),
			index: 0,
			activate: null
		};

		if (!gallery.slides.length) {
			return;
		}

		gallery.activate = (index, options) => {
			const slide = gallery.slides[index];

			if (!slide) {
				return;
			}

			gallery.index = index;
			main.src = slide.mainSrc;
			if (slide.mainSrcset) {
				main.srcset = slide.mainSrcset;
				main.sizes = slide.mainSizes;
			} else {
				main.removeAttribute('srcset');
				main.removeAttribute('sizes');
			}
			main.alt = slide.alt;
			main.setAttribute('data-full-src', slide.fullSrc);
			if (slide.fullSrcset) {
				main.setAttribute('data-full-srcset', slide.fullSrcset);
				main.setAttribute('data-full-sizes', slide.fullSizes);
			} else {
				main.removeAttribute('data-full-srcset');
				main.removeAttribute('data-full-sizes');
			}

			gallery.thumbs.forEach((btn, thumbIndex) => {
				const on = thumbIndex === index;
				btn.classList.toggle('is-active', on);
				btn.setAttribute('aria-selected', on ? 'true' : 'false');
			});

			if (activeGallery === gallery && isModalOpen() && !(options && options.skipLightbox)) {
				renderLightboxSlide();
			}
		};

		gallery.thumbs.forEach((btn, index) => {
			btn.addEventListener('click', () => {
				gallery.activate(index);
			});
		});

		if (trigger) {
			trigger.addEventListener('click', () => {
				openModal(gallery, trigger);
			});
		}
	}

	document.querySelectorAll('[data-heb-acf-gallery]').forEach(initGallery);
})();
