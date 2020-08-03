$('input[type="radio"][name="input-limit"], #input-sort').on('change', function(e) {
    console.log('sdgdfg');
    location = e.target.value;
});

var catalog = {
    productList: null,
    __init: function() {
        console.log('CATALOG INIT');

        this.productList = $('#catalog-product-card-list');
        this.infiniteScrollInit();
    },
    infiniteScrollInit: function() {
        $(this.productList).infiniteScroll({
            path: '.pagination__next',
            append: '.ss-col-item',
            hideNav: '.pagination-p',
            prefill: false,
            button: '#fetch',
            scrollThreshold: true,
            status: '.page-load-status'
        })
        .on('append.infiniteScroll', function(a, b, c) {
            catalogProductCardController.__init();
        })
    }
}

catalog.__init();