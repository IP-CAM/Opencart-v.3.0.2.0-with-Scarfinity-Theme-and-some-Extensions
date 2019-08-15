var bannerController = {
    '__init': function () {
        $('.banner-container[data-init="false"]').each(function (index, banner) {
            var swiper = $(banner).find('.swiper-container');

            if (typeof Swiper) {
                new Swiper(swiper, {
                    mode: 'horizontal',
                    effect: 'fade',
                    autoplay: 2500,
                    slidesPerView: 1,
                    navigation: {
                        nextEl: '.product-card-catalog__image__button--next',
                        prevEl: '.product-card-catalog__image__button--prev',
                    },
                    pagination: {
                        //el: '#product-card-catalog-swiper-pagination-{{swiper_id}}',
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
                    autoHeight: false
                });
            }
        });
    }
}