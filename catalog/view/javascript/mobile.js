console.log('Mobile.js');

$('.ss-product-card-count').on('click', function() {
    $('.ss-product-card-list')
        .removeClass('ss-col-one ss-col-two ss-col-three ss-col-four')
        .addClass($(this).data('value'));
});

$('.ss-product-card-list-card')
    .on('mouseenter', function() {
        var height = $(this).outerHeight();
        var actionHeight = $(this).find('.ss-action').outerHeight(true);

        $(this).css("height", height);

        $(this)
            .find('.card-body, .card-actions')
            .css("position", "relative")
            .css("top", -actionHeight + "px");
        
        $(this)
            .find('.ss-action')
            .css("position", "relative");
    })
    .on('mouseleave', function() {
        $(this)
            .css("height", "")
            .find('.card-body, .card-actions')
            .removeAttr("style");

        $(this)
            .find('.ss-action')
            .removeAttr("style");
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