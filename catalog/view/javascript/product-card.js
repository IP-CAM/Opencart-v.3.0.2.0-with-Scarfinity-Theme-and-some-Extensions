var pcTimeout = null;

var productCardController = {
    // Common
    productCard: null,
    productId: null,
    productModel: null,

    // Price
    productQuantityContainer: null,
    price: {
        base: 0,
        origin: 0,
        special: 0
    },

    // Images
    productCardMainImageContainerSwiper: null,
    productCardImageContainerSwiper: null,
    '__init': function () {
        console.log('init product card...');
        var controller = this;
        this.productCard = $('#product');
        this.productId = $(this.productCard).attr('data-product-id');
        this.productModel = $(this.productCard).attr('data-product-model');

        // Quantity and price
        this.productQuantityContainer = $('#cart-quantity');
        var priceContainer = $('#product-price-data');
        this.price.base = $(priceContainer).attr('data-price-base');
        this.price.origin = $(priceContainer).attr('data-price-origin');
        this.price.special = $(priceContainer).attr('data-price-special');

        sCart.addEventListener('afterload', function () {
            controller.fillProductModelQuantity();
            controller.updateCurrentPrice();
            $('#product .product-card-core').removeClass('process');
        });

        // Swiper
        // Main image swiper
        var mainImageSwiper = $(this.productCard).find('#carousel-product-card-main-image');
        this.productCardMainImageContainerSwiper = new Swiper(mainImageSwiper, {
            mode: 'horizontal',
            slidesPerView: 1,
            pagination: false,
            paginationClickable: false,
            spaceBetween: 0,
            autoplay: false,
            autoplayDisableOnInteraction: true,
            loop: false,
            autoHeight: true,
            on: {
                init: function () {
                    $('#p_card-i_gallery-swiper a').eq(0).addClass('pc_active');
                },
                slideChange: function () {
                    $('#p_card-i_gallery-swiper a').removeClass('pc_active');
                    $('#p_card-i_gallery-swiper a').eq(this.activeIndex).addClass('pc_active');
                    controller.productCardImageContainerSwiper.slideTo(this.activeIndex);
                }
            }
        });

        // Images swiper
        this.productCardImageContainerSwiper = new Swiper('#p_card-i_gallery-swiper', {
            mode: 'horizontal',
            slidesPerView: 5,
            pagination: false,
            paginationClickable: false,
            spaceBetween: 0,
            slidesOffsetBefore: 0,
            slidesOffsetAfter: 0,
            autoplay: false,
            autoplayDisableOnInteraction: true,
            loop: false,
            autoHeight: false,
            on: {
                init: function () {
                    $('.p_card-i_gallery-swiper').removeClass('init');
                    controller.productCardMainImageContainerSwiper.slideTo(0, 0);
                },
                tap: function (e) {
                    controller.productCardMainImageContainerSwiper.slideTo(this.clickedIndex, 0);
                },
                click: function (e) {
                    controller.productCardMainImageContainerSwiper.slideTo(this.clickedIndex, 0);
                }
            },
            breakpoints: {
                1024: {
                    slidesPerView: 5,
                },
                768: {
                    slidesPerView: 5,
                },
                640: {
                    slidesPerView: 4,
                },
                350: {
                    slidesPerView: 4,
                },
                280: {
                    slidesPerView: 4,
                }
            }
        });

        $('#button-cart').on('click', function(e) {
            var input = $(this).parent().find('input[name="quantity"]');
            $(input).val(parseInt($(input).val()) + 1).trigger('change');
        });

        $(document).delegate('.ctr-utt button', 'click', function(e) {
            var name = e.target.name;
            var parent = $(this).parent().parent();
            var input = parent.find('input[name="quantity"]');
            input.val(Math.max(0, parseInt(input.val()) + (name == 'inc' ? 1 : -1))).trigger('change');
        });

        $(document).delegate('.ctr-utt input[name$=quantity]', 'change', function(e) {
            var input = $(this);
            var parent = input.parent();
            var product_id = parent.find('input[type="hidden"]').val();
            var newValue = parseInt(input.val());
            var oldValue = parseInt(input.data('tmp-value'));

            if (oldValue !== newValue) {
                input.val(newValue);
                clearTimeout(pcTimeout);
                pcTimeout = setTimeout(function () {
                    clearTimeout(pcTimeout);
                    $('#product .product-card-core').addClass('process');
                    sCart.set(product_id, newValue);
                }, 650);
            }
        });

        controller.fillProductModelQuantity();
        controller.updateCurrentPrice();

        $('#product .product-card-core').removeClass('process');

        $(document).ready(function () {

            controller.__initReview();
            controller.__initModel();

            $('#carousel-product-card-main-image').magnificPopup({
                type: 'image',
                delegate: 'a',
                gallery: {
                    enabled: true
                },
                mainClass: 'mfp-with-zoom', // this class is for CSS animation below
                zoom: {
                    enabled: false, // By default it's false, so don't forget to enable it
                    duration: 300, // duration of the effect, in milliseconds
                    easing: 'ease-in-out', // CSS transition easing function
                    opener: function (openerElement) {
                        // openerElement is the element on which popup was initialized, in this case its <a> tag
                        // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }
            });

            // $('.product-card-models').magnificPopup({
            //     type: 'image',
            //     delegate: 'a',
            //     mainClass: 'mfp-with-zoom'
            // });
        });
    },
    'fillProductModelQuantity': function() {
        var controller = this;

        $(this.productCard).find('.product-model').each(function(index, model) {
            var productId = $(model).find('input[name="product_id"]').val();
            quantity = sCart.getProductQuantity(productId);
            $(model).find('input[name="quantity"]').val(quantity);
        });
    },
    'fillProductOptions': function () {
        var controller = this;

        var options = $(this.productCard).find('.ctr-utt');
        _.forEach(options, function (option) {
            var optionInfo = $(option).find('input[type="hidden"]');
            var qInput = $(option).find('input[name$="quantity"]');
            var quantity = sCart.getProductQuantity(controller.productId, [{
                product_option_id: optionInfo.attr('name'),
                product_option_value_id: optionInfo.val()
            }]);

            qInput.data('tmp-value', quantity);
            qInput.val(quantity).trigger("change");
        });

        this.productQuantityContainer.text(sCart.getProductQuantity(controller.productId));
    },
    'updateCurrentPrice': function () {
        var controller = this;

        var currency = $('#product .utility__currency');
        var price = sCart.getProductPriceByModel(controller.productModel);
        var value = currency.find('.utility__currency__value');

        console.log(controller.productId, price);

        if (price != '') {
            value.text(price);
        } else {
            if (sCart.getSubTotalOrigin() >= 5000) {
                value.text(this.price['origin']);
            } else {
                value.text(this.price['base']);
            }
        }
    },
    'setProductQuantity': function () {
        var controller = this;

        var options = $('#product .ctr-utt');
        _.forEach(options, function (option) {
            var optionInfo = $(option).find('input[type="hidden"]');
            var qInput = $(option).find('input[name$="quantity"]');
            var oldValue = qInput.data('tmp-value');
            var newValue = parseInt(qInput.val());
            qInput.data('tmp-value', newValue);

            if (oldValue != newValue) {
                sCart.set(controller.productId, qInput.val(), [{
                    product_option_id: optionInfo.attr('name'),
                    product_option_value_id: optionInfo.val()
                }]);
            }
        });
    },
    '__initModel': function() {
        var controller = this;

        this.loadModel();
    },
    '__initReview': function() {
        var controller = this;

        $('#review').delegate('.pagination a', 'click', function(e) {
            e.preventDefault();
        
            $('#review').fadeOut('slow');
        
            $('#review').load(this.href);
        
            $('#review').fadeIn('slow');
        });

        this.loadReview();

        $('#button-review').on('click', function() {
            $.ajax({
                url: 'index.php?route=product/product/write&product_id=' + controller.productId,
                type: 'post',
                dataType: 'json',
                data: $("#form-review").serialize(),
                beforeSend: function() {
                    $('#button-review').button('loading');
                },
                complete: function() {
                    $('#button-review').button('reset');
                },
                success: function(json) {
                    $('.alert-dismissible').remove();
        
                    if (json['error']) {
                        $('#leave-conatiner-leave-message').html('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    }
        
                    if (json['success']) {
                        $('#leave-conatiner-leave-message').html('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
        
                        $('input[name=\'name\']').val('');
                        $('textarea[name=\'text\']').val('');
                        $('input[name=\'rating\']:checked').prop('checked', false);
                    }
                }
            });
        });
    },
    'loadModel': function() {
        var controller = this;

        $('#model').load('index.php?route=product/product/model&product_id=' + this.productId, function() {
            controller.fillProductModelQuantity();
        });
    },
    'loadReview': function() {
        $('#review').load('index.php?route=product/product/review&product_id=' + this.productId);
    }
}

productCardController.__init();