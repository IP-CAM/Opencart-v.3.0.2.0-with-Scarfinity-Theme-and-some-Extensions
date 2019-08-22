$(document).ready(function() {
    checkout.shippingMethodsGet({
        success: function(html) {
            $('#cart-checkout-requisites-shipping-method').html(html);
            $('#cart-button-next').prop('disabled', false);
        }
    });
});    

$('.sInputTextArea textarea[name$="comment"]').val(comment).trigger('input');
$('.select-box input[type="checkbox"][data-group$="terms"][name$="agree"]').prop('checked', agree ? true : false).change();

var ui = {
    shippingMethodChange: function(form, shippingMethodCode) {
        console.warn('ShippingMethodChanged!');

        $('#cart-checkout-payments-method').html('');
        
        if(checkout) {
            checkout.paymentMethodsGet({
                success: function(html) {
                    $('#cart-checkout-payments-method').html(html);
                }
            });
        }

        $('form[name^=shipping_method_fields]')
            .addClass('hidden');

        $('form[name=shipping_method_fields_' + form + ']')
            .removeClass('hidden');

        $('select[data-code^="shipping"], input[data-code^="shipping"]')
            .parents('.sInput-container')
            .addClass('hidden');
        
        $('select[data-code$="' + form + '"], input[data-code$="' + form + '"]')
            .removeClass('sInput--error')
            .trigger('input')
            .change()
            .parents('.sInput-container')
            .removeClass('hidden')
            .find('.sInput-error')
            .remove();
    },
    shippingAndPaymentsSave: function() {
        if(checkout) {
            checkout.shippingAndPaymentsSave({
                beforeSend: ui.beforeSend('cart-checkout-payments', function() {
                    $('#cart-button-next').prop('disabled', true);
                }),
                success: function(json) {
                    checkout.confirm({
                        beforeSend: ui.beforeSend('cart-checkout-payments', function() {
                            $('#cart-button-next').prop('disabled', true);
                        }),
                        success: function(html) {
                            $('#cart').html(html);
                        },
                        complete: function() {
                            $('#cart-button-next').prop('disabled', false);
                        }
                    });
                },
                error: ui.displayErrors(function(errors) {}),
                complete: function() {
                    $('#cart-button-next').prop('disabled', false);
                }
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

$(document).delegate('#checkout-requisites .sInput', 'input', function(e) {
    var target = e.target;
    var group = $(target).data('group');
    var name = target.name;
    var value = target.value;

    if(checkout[group]) {
        checkout[group][name] = value;
    }
});

$(document).delegate('#checkout-requisites .select-box input', 'change', function(e) {
    var target = $(this);
    var type = target.attr('type');
    var group = target.data('group');
    var form = target.data('form');
    var name = target.attr('name');
    var value = target.val();
    var checked = target.attr('checked');

    console.log(name, group, value);

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

$(document).delegate('#checkout-requisites .sDropdown select', 'change', function() {
    var target = $(this);
    var name = target.attr('name');
    var group = target.data('group');
    var value = target.val();

    console.log(name, group, value);

    if(checkout[group]) {
        checkout[group][name] = value;
    }
});

// Kladr city
$(document).delegate('#input-shipping-country', 'change', function() {
    var target = $(this);
    var value = target.val();

    console.log(value);

    if($.kladr) {
        if(value == 176) {
            $('.sInput[data-group="shipping_address"][name$="city"]').kladr({
                type: $.kladr.type.city,
                select: function(obj) {
                    console.log(obj);
                    $(this).trigger('input');
                }
            });
        } else {
            $('.sInput[data-group="shipping_address"][name$="city"]').kladr = undefined;
        }
    }
});

// Сохранение реквизитов доставки
$('#cart-button-next').on('click', function() {
    ui.shippingAndPaymentsSave();
});