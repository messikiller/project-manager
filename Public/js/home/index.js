$(document).ready(function() {
    var interval = 2000;
    var owl = $("#owl-demo");
    owl.owlCarousel({
      items : 10,
    //   itemsDesktop : [1000,5],
    //   itemsDesktopSmall : [900,3],
    //   itemsTablet: [600,2],
    //   itemsMobile : false
    });
    owl.trigger('owl.play', interval);
});
