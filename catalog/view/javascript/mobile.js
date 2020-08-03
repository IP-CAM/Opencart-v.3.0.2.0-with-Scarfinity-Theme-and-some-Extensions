console.log('Mobile.js');

// $('.ss-product-card-count').on('click', function() {
//     $('.ss-product-card-list')
//         .removeClass('ss-col-one ss-col-two ss-col-three ss-col-four')
//         .addClass($(this).data('value'));
// });

$('input[type=radio][name=ss-col-sort]').on('change', function() {
    $('.ss-product-card-list')
        .removeClass('ss-col-one ss-col-two ss-col-three ss-col-four')
        .addClass(this.value);
});

$(document).delegate('.ss-product-card-list-card', 'mouseenter', function(e) {
    var height = $(this)[0].getBoundingClientRect().height;
    var actionHeight = $(this).find('.ss-action').outerHeight(true);

    $(this).css("height", height);
    $(this).addClass("lift-up");
});

$(document).delegate('.ss-product-card-list-card', 'mouseleave', function(e) {
    $(this)
        .css("height", "")
        .removeClass("lift-up");
});

$(document).ready(function() {
    'use strict';
  
    var c, currentScrollTop = 0,
        navbar = $('nav.ss-nav');
 
    $(window).scroll(function () {
       var a = $(window).scrollTop();
       var b = navbar.height();
      
       currentScrollTop = a;
      
       if (c < currentScrollTop && a > b + b) {
         navbar.addClass("scrollUp");
       } else if (c > currentScrollTop && !(a <= b)) {
         navbar.removeClass("scrollUp");
       }
       c = currentScrollTop;
    });
});