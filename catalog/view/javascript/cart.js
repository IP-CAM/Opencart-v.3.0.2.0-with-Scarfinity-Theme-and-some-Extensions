var sCart = {
    loading: false,
    products: null,
    totals: null,
    vouchers: null,
    cart_info: null,
    listeners: [],
    load: function () {
        var cart = this;
        if (!cart.loading) {
            $.ajax({
                url: 'index.php?route=common/cart/get',
                type: 'post',
                success: function (json) {
                    cart.products = json['products'];
                    cart.totals = json['totals'];
                    cart.vouchers = json['vouchers'];
                    cart.cart_info = json['cart_info'];
                    _.forEach(_.filter(cart.listeners, { event: 'afterload' }), function (listener) {
                        if (listener.callback) {
                            listener.callback();
                        }
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    },
    add: function (product_id, quantity = 1, option = []) {
        var cart = this;
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: {
                product_id: product_id,
                quantity: quantity,
                option: _.reduce(option, function (obj, param) {
                    obj[param.product_option_id] = param.product_option_value_id;
                    return obj;
                }, {})
            },
            dataType: 'json',
            success: function (json) {
                if (json['redirect']) {
                    console.log(json);
                    // location = json['redirect'];
                } else if (json['error']) {
                    console.warn('Adding errros...');
                } else {
                    console.log('suucess!!');
                    cart.load();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    set: function (product_id, quantity = 1, option = []) {
        var cart = this;
        var productQuantity = cart.getProductQuantity(product_id, option);
        cart.add(product_id, Math.max(quantity, 0) - productQuantity, option);
    },
    remove: function (product_id, option = []) {
        var cart = this;
        cart.set(product_id, 0, option);
    },
    removeBy: function (cart_id) {
        var cart = this;
        var product = cart.getProductBy(cart_id);
        if (product) {
            cart.remove(product.product_id, product.option);
        }
    },
    getSubTotalOrigin: function () {
        var cart = this;
        var sub_total_origin = _.find(cart.totals, { 'code': 'handling' });

        if (sub_total_origin) {
            return sub_total_origin['value'] || 0;
        } else {
            return 0;
        }
    },
    getProduct: function (product_id, option = []) {
        var cart = this;
        return _.find(cart.products, function (product) {
            if (product.product_id == product_id) {
                if (_.differenceWith(
                    _.map(product.option, function (option) {
                        return option.product_option_id + ':' + option.product_option_value_id
                    }),
                    _.map(option, function (option) {
                        return option.product_option_id + ':' + option.product_option_value_id
                    })
                ).length == 0) {
                    return true;
                }
            }

            return false;
        });
    },
    getProductBy: function (cart_id) {
        var cart = this;
        return _.find(cart.products, function (product) {
            return product.cart_id == cart_id;
        });
    },
    getProductQuantity: function (product_id, option = null) {
        var cart = this;

        if(option == null) {
            return _.reduce(_.filter(cart.products, ['product_id', product_id.toString()]), function (sum, product) {
                return sum + parseInt(product.quantity);
            }, 0);
        }

        var product = cart.getProduct(product_id, option);
        
        return product ? product.quantity : 0;
    },
    getProductPrice: function (product_id) {
        var cart = this;
        var product = _.find(cart.products, function (product) {
            return product.product_id == product_id;
        });

        if (product) {
            return product.price;
        }

        return '';
    },
    addEventListener: function (event, callback) {
        this.listeners.push({ event, callback });
    }
}

function updateCart() {
    // $('html, body').animate({ scrollTop: 0 }, 'slow');
    $('#cart, #mobile-cart').load('index.php?route=common/cart/info');
}

sCart.addEventListener('afterload', updateCart);

sCart.load();