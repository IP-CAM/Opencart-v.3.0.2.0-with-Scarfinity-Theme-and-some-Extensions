$.ajax({
    url: 'index.php?route=checkout/login',
    dataType: 'html',
    beforeSend: function() {},
    success: function(html) {
        $('#cart-checkout-requisites-login').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    },
    complete: function() {
        $('#cart-button-next').prop('disabled', false);
    }
});

$(document).delegate('#checkout-checkout .sInput', 'input', function(e) {
    var target = e.target;
    var group = $(target).data('group');
    var name = target.name;
    var value = target.value;

    if(checkout[group]) {
        checkout[group][name] = value;
    }
});

$(document).delegate('#checkout-checkout .select-box input', 'change', function(e) {
    var target = $(this);
    var type = target.attr('type');
    var group = target.data('group');
    var form = target.data('form');
    var name = target.attr('name');
    var value = target.val();
    var checked = target.attr('checked');
    console.log('[select-box]', type, name, value, checked);

    if(checkout[group]) {
        if(type == 'radio') {
            checkout[group][name] = value; 

            if(group == 'shipping_method' && ui) {
                ui.shippingMethodChange(form, value);
            }
        }

        if(type == 'checkbox') {
            checkout[group][name] = target.prop('checked');
        }
    }
});

$(document).delegate('#checkout-checkout .sDropdown select', 'change', function() {
    var target = $(this);
    var name = target.attr('name');
    var group = target.data('group');
    var value = target.val();

    console.log(name, group, value);

    if(checkout[group]) {
        checkout[group][name] = value;
    }
});

$(document).delegate('#button-login', 'click', function() {
    if(typeof ui) {
        ui.customerLogin();
    }
});

var ui = {
    customerLogin: function() {
        var ui = this;

        if(checkout) {
            checkout.customerLogin({
                beforeSend: ui.beforeSend('cart-checkout-login-login', function() {}),
                success: function() {},
                error: ui.displayErrors(function(errors) {})
            });
        }
    },
    customerSave: function() {
        var ui = this;
        
        if(checkout) {
            checkout.customerSave({
                beforeSend: ui.beforeSend('cart-checkout-requisites', function() {
                    $('#cart-button-next').prop('disabled', true);
                }),
                success: function(json) {
                    location = json['continue'];
                },
                error: ui.displayErrors(function(errors) {
                    $('#cart-button-next').prop('disabled', false);
                })
            });
        }
    },
    beforeSend: function(containerId, callback) {
        var ui = this;

        ui.container = $('#' + containerId);
        $(ui.container).find('.sInput-error').remove();
        $(ui.container).find('.sInput--error').removeClass('sInput--success sInput--error sInput--validate');

        if(callback) callback();
    },
    displayErrors: function(callback) {
        var ui = this;

        return function(errors) {
            _.forIn(errors, function(error, name) {
                $(ui.container).find('input[type!="checkbox"][name$="' + name +'"], div[data-name$="' + name + '"]')
                    .addClass('sInput--error')
                    .after('<p class="sInput-error">' + error + '</p>');

                $(ui.container).find('input[type="checkbox"][name$="' + name + '"], select[name$="' + name + '"]')
                    .addClass('sInput--error')
                    .parents('.select-box, .sInput-container')
                    .append('<p class="sInput-error">' + error + '</p>');
            });

            if(callback) callback(errors);
        }
    }
};

// Сохранение информации о пользователе
$('#cart-button-next').on('click', function() {
    ui.customerSave();
});