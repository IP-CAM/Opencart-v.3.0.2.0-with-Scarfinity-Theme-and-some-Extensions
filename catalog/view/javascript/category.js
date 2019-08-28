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
            append: '.product-card-catalog-container',
            hideNav: '.pagination-p',
            // prefill: true,
            button: '#fetch',
            scrollThreshold: false,
            status: '.page-load-status'
        })
        .on('append.infiniteScroll', function() {
            catalogProductCardController.__init();
        })
    }
}

catalog.__init();