var checkout_payment_load = false;

function __load() {
    if(!checkout_payment_load) {
        checkout.paymentGet({
            success: function(html) {
                checkout_payment_load = true;
                $('#payment').html(html);
            }
        });
    }
}

if(sCart.loading) {
    sCart.addEventListener('afterload', __load);    
    console.log(1);
} else {
    __load();
    console.log(2);
}