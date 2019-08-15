var slideshowController = {
    '__init': function (speed = 5000) {
        var controller = this;

        $('.banner[data-init="false"]').each(function (index, banner) {
            var swiper = $(banner).find('.swiper-container');

            var swiper = new Swiper(swiper, {
                mode: 'horizontal',
                slidesPerView: 1,
                loop: true,
                autoplay: {
                    delay: speed
                },
                on: {
                    slideChangeTransitionEnd: function () {
                        controller.sliderGo(banner, this.realIndex, speed);
                    }
                }
            });

            $(banner).find('.prev').on('click', function () { swiper.slidePrev(); });
            $(banner).find('.next').on('click', function () { swiper.slideNext(); });
        });
    },
    'sliderGo': function (banner, step, speed = 5000) {
        var bar = $(banner).find('.banner-slide');
        var items = $(bar).find('.banner-slide-item');


        _.forEach(items, function (item, index) {
            var white = $(item).find('.banner-slide-item__white');

            if (index < step) {
                white.css({ 'transition': '', 'left': '100%' });
            } else if (index == step) {
                white.css({ 'transition': '', 'left': 0 });
                var timeout = setTimeout(function () {
                    white.css({ 'transition': 'left ' + speed + 'ms ease-in-out', 'left': '100%' });
                    clearTimeout(timeout);
                    timeout = undefined;
                }, 50);
            } else {
                white.css({ 'transition': '', 'left': 0 });
            }
        });
    }
}

$(document).ready(function () {
    slideshowController.__init();
});