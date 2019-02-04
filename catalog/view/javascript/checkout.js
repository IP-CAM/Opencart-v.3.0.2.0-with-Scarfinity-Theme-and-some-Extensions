var checkout = {
    customer: {
        firstname: '',
        lastname: '',
        telephone: '',
        email: '',
        get: function() {
            return {
                firstname: this.firstname,
                lastname: this.lastname,
                telephone: this.telephone,
                email: this.email == '' ? this.telephone + '@scarfinity.ru' : this.email
            };
        }
    },
    login: {
        email: '',
        password: '',
    },
    shipping_address: {
        country_id: '176',
        city: '',
        address_1: '',
        address_2: '',
        postcode: '',
        zone_id: '1',
        firstname: '',
        lastname: '',
        company: '',
    },
    shipping_method: {
        shipping_method: ''
    },
    payment_method: {
        payment_method: ''
    },
    terms: {
        agree: false
    },
    additional: {
        comment: ''
    },
    __error: function(action) {
        return function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

            if(action) action();
        }
    },
    customerLogin: function(actions) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/login/save',
            type: 'post',
            data: checkout.login,
            dataType: 'json',
            beforeSend: function() {
                $('#button-login').button('loading');
            },
            complete: function() {
                $('#button-login').button('reset');
            },
            success: function(json) {
                console.log(json);
                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if(actions['error']) actions['error'](json['error']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    customerSave: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/customerRegister',
            data: checkout.customer.get(),
            method: 'post',
            dataType: 'json',
            beforeSend: function() {},
            success: function(json) {
                if(json['redirect']) {
                    location = json['redirect'];
                } else if(json['error']) {
                    if(actions['error']) actions['error'](json['error']);
                } else {
                    if(actions['success']) actions['success'](json);
                }
            },
            error: checkout.__error(actions['error'])
        });
    },
    shippingAddressGet: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/shippingAddressGet',
            dataType: 'html',
            beforeSend: actions['beforeSend'],
            success: function(html) {
                console.log('Sipping loaded!');
                if(actions['success']) actions['success'](html);
            },
            error: checkout.__error(actions['error'])
        });
    },
    shippingAddressSave: function(onSuccesed) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/shippingAddressSave',
            method: 'post',
            dataType: 'json',
            data: checkout.shipping_address,
            beforeSend: function() {},
            success: function(json) {
                if(json['redirect']) {
                    location = json['redirect'];
                } else if(json['error']) {
                    console.warn('Shipping address save errors!', json['error']);
                } else {
                    console.log('Shipping address save succesed!');
                    if(onSuccesed) onSuccesed(json);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    shippingMethodsGet: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/shippingMethodsGet',
            data: checkout.shipping_address,
            dataType: 'html',
            beforeSend: actions['beforeSend'],
            success: function(html) {
                console.log('Shipping methods load end!');
                if(actions['success']) actions['success'](html);
            },
            error: checkout.__error(actions['error'])
        });
    },
    paymentMethodsGet: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/paymentMethodsGet',
            dataType: 'html',
            beforeSend: actions['beforeSend'],
            success: function(html) {
                console.log('Payments loaded!');
                if(actions['success']) actions['success'](html);
            },
            error: checkout.__error(actions['error'])
        });
    },
    shippingAndPaymentsSave: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/shippingAndPaymentsSave',
            method: 'post',
            data: _.merge(checkout.shipping_address, checkout.shipping_method, checkout.payment_method, checkout.terms, checkout.additional),
            dataType: 'json',
            beforeSend: actions['beforeSend'],
            beforeSend: function() {
                if(actions['beforeSend']) actions['beforeSend']();
            },
            success: function(json) {
                if(json['redirect']) {
                    location = json['redirect'];
                } else if(json['error']){
                    if(actions['error']) actions['error'](json['error']);
                } else {
                    if(actions['success']) actions['success'](json);
                }
            },
            error: checkout.__error(actions['error']),
            complete: function() {
                if(actions['complete']) actions['complete']();
            }
        });
    },
    confirm: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=checkout/simple/confirm',
            method: 'post',
            dataType: 'json',
            success: function(json) {
                if(json['redirect']) {
                    location = json['redirect'];
                } else {
                    if(actions['success']) actions['success'](json);
                }
            },
            error: checkout.__error(actions['error'])
        });
    },
    paymentConfirm: function(actions = {}) {
        var checkout = this;

        $.ajax({
            url: 'index.php?route=extension/payment/cod/confirm',
            dataType: 'json',
            success: function(json) {
                location = json['redirect'];
            },
            error: checkout.__error(actions['error'])
        });
    }
};