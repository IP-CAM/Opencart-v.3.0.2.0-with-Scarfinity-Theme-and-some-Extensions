var catalogProductCardController = {
	initialize: false,
	cards: [],
	'add': function(card) {
		var controller = this;
		card.imageLoaded = false;

		controller.cards.push(card);

		if(this.initialize) {
			this.initCard(this.cards.length - 1, true);
		}
	},
	'__init': function() {
		$('.product-card-catalog[data-init="false"]').each(function(index, card) {
			console.log('init product card...');
			var swiper = $(card).find('.product-card-catalog-swiper');
			var pagination = $(card).find('.swiper-pagination');

			new Swiper(swiper, {
				mode: 'horizontal',
				lazy: true,
				nested: true,
				grabCursor: true,
				preloadImages: false,
				slidesPerView: 1,
				navigation: {
					nextEl: '.product-card-catalog__image__button--next',
					prevEl: '.product-card-catalog__image__button--prev',
				},
				pagination: {
					el: pagination,
					dynamicBullets: true,
				},
				spaceBetween: 0,
				touchAngle: 30,
				resistanceRatio: 0.8,
				slidesOffsetBefore: 0,
				slidesOffsetAfter: 0,
				autoplay: false,
				autoplayDisableOnInteraction: true,
				loop: false,
				autoHeight: false,
				on: {
					slideChangeTransitionStart: function() {
						if($(card).attr('data-image-init') === 'false') {
							$(swiper).find('.swiper-lazy').each(function(index, img) {
								$(img).attr('src', $(img).attr('data-src'));
								$(img).attr('data-src', null);
							});

							$(card).attr('data-image-init', 'true');
						}
					}
				}
			});
			
			// Images
			var swiperLazy = $(card).find('.swiper-lazy-main');
			$(swiperLazy).each(function(index, img) {
				console.log($(img).attr('data-src'));
				$(img).attr('src', $(img).attr('data-src'));
				$(img).attr('data-src', null);
			});

			// Colors
			var colorContainer = $(card).find('.product-card-catalog-colors');
			$(colorContainer).magnificPopup({
				delegate: 'a',
				type: 'image',
				gallery: {
					enabled: true
				},
				mainClass: 'mfp-with-zoom'
			});

			$(card).attr('data-init', 'true');

			console.log('Card init done!');
		});
	},
	'initCard': function(cardIndex, colors = true) {
		var card = this.cards[cardIndex];
		var controller = this;

		if(card) {
			
			$('#product-card-catalog-swiper-' + card.swiperId + ' .swiper-lazy-main').each(function(index, img) {
				console.log($(img).attr('data-src'));
				$(img).attr('src', $(img).attr('data-src'));
				$(img).attr('data-src', null);
			});

			new Swiper('#product-card-catalog-swiper-' + card.swiperId, {
				mode: 'horizontal',
				lazy: true,
				nested: true,
				grabCursor: true,
				preloadImages: false,
				slidesPerView: 1,
				navigation: {
					nextEl: '.product-card-catalog__image__button--next',
					prevEl: '.product-card-catalog__image__button--prev',
				},
				pagination: {
					el: '#product-card-catalog-swiper-pagination-' + card.swiperId,
					dynamicBullets: true,
				},
				spaceBetween: 0,
				touchAngle: 30,
				resistanceRatio: 0.8,
				slidesOffsetBefore: 0,
				slidesOffsetAfter: 0,
				autoplay: false,
				autoplayDisableOnInteraction: true,
				loop: false,
				autoHeight: false,
				on: {
					slideChangeTransitionStart: function() {
						if(controller.cards[cardIndex].imageLoaded == false) {
							$('#product-card-catalog-swiper-' + card.swiperId + ' .swiper-lazy').each(function(index, img) {
								$(img).attr('src', $(img).attr('data-src'));
								$(img).attr('data-src', null);
								controller.cards[cardIndex].imageLoaded = true;
							});
						}
					}
				}
			});

			if(colors == true) {
				$('#product-card-catalog-colors-' + card.swiperId).magnificPopup({
					delegate: 'a',
					type: 'image',
					gallery: {
						enabled: true
					},
					mainClass: 'mfp-with-zoom'
				});
			}
		}
	}
}

$(document).ready(function() {
	catalogProductCardController.__init();
});