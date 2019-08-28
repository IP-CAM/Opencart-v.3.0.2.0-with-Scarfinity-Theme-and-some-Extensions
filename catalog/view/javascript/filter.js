$('#mobile-filters-container').append('<div class="mobile-filters-container__container"></div><div class="mobile-filters-container__button"></div>');

var filter = {
    places: {
        768: 'mobile-filters-container .mobile-filters-container__container',
    },
    place: 'layout-filters',
    target: 'filters',
    init: function () {
        var filter = this;

        filter['_places'] = _.orderBy(_.map(_.toPairs(filter.places), function (o) {
            return { breakpoint: o[0], target: o[1] }
        }), 'breakpoint').reverse();

        $(window).resize(function () {
            filter.changePlace();
        });
        filter.changePlace();
    },
    changePlace: function () {
        var filter = this;

        var width = $(window).width();
        var place = filter.place;

        _.forEach(filter['_places'], function (p) {
            if (width <= p.breakpoint) {
                place = p.target;
            }
        });

        if (place != '') {
            $('#' + place).html($('#' + filter.target));
        }
    }
};

$(document).ready(function () {
    filter.init();
});

var mobileFilters = {
    isOpen: false,
    container: document.getElementById('mobile-filters-container'),
    open: function () {
        var filters = this;

        if (!filters.isOpen) {
            filters.isOpen = true;

            $('html').addClass('mobile-filters-container-open');
            $(filters.container).addClass('mobile-filters-container--open');
        }
    },
    close: function () {
        var filters = this;

        if (filters.isOpen) {
            filters.isOpen = false;

            $('html').removeClass('mobile-filters-container-open');
            $(filters.container).removeClass('mobile-filters-container--open');
        }
    },
    toggle: function () {
        var filters = this;

        if (filters.isOpen) {
            filters.close();
        } else {
            filters.open();
        }
    }
}

$('#mobile-filters-container .mobile-filters-container__button').on('click', function () {
    mobileFilters.toggle();
});

$(document).delegate('#button-filter', 'click', function () {
    filter = [];

    $('input[name^=\'filter\']:checked').each(function (element) {
        filter.push(this.value);
    });

    var prices = '';
    $('input[name^=\'pmin\'], input[name^=\'pmax\']').each(function () {
        if (this.value != '') {
            prices += '&' + this.name + '=' + this.value;
        }
    });

    location = '{{ action }}&filter=' + filter.join(',') + prices;
});

if (!range) {
    var range = {};
}

var id = 1;

if (range[id] == null) {
    range[id] = {
        base: null,
        nouislider: null,
        min: null,
        max: null,
        pmin: parseInt('{{props.pmin}}') || 10,
        pmax: parseInt('{{props.pmax}}') || 3000,
        minValue: 10,
        maxValue: 3000,
        init: function () {
            var _ = this;

            _.base = document.getElementById('price-range-widget[' + id + ']');
            
            if(_.base) {
                _.nouislider = document.getElementById('price-range[' + id + ']');
                _.min = $(_.base).find('input[name$="min"]')[0];
                _.max = $(_.base).find('input[name$="max"]')[0];

                _.min.addEventListener('change', function () {
                    if (_.nouislider) {
                        _.nouislider.noUiSlider.set([$(this).val(), null]);
                    }
                });

                _.max.addEventListener('change', function () {
                    if (_.nouislider) {
                        _.nouislider.noUiSlider.set([null, $(this).val()]);
                    }
                });


                _.nouisliderInit();
            }
        },
        nouisliderInit: function () {
            var _ = this;

            if (typeof noUiSlider != 'undefined') {
                noUiSlider.create(_.nouislider, {
                    range: {
                        'min': _.minValue,
                        'max': _.maxValue
                    },
                    step: 50,
                    start: [_.pmin, _.pmax],
                    margin: 0,
                    limit: _.maxValue,
                    connect: true,
                    direction: 'ltr',
                    orientation: 'horizontal',
                    behaviour: 'tap-drag',
                    tooltips: false
                });

                _.nouislider.noUiSlider.on('update', function (values, handle) {
                    if (handle == 0) _.min.value = values[0] != _.minValue ? values[0] : '';
                    if (handle == 1) _.max.value = values[1] != _.maxValue ? values[1] : '';
                });
            }
        }
    };

    range[id].init();
}
