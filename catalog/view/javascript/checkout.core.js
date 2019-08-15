var cartHeaderProductsCount = $('#cart-header-products-count');
var productsCount = $(cartHeaderProductsCount).attr('data-products-count');
$(cartHeaderProductsCount).text('У вас ' + productsCount + ' ' + crr_products(productsCount) + ' в коризине');

