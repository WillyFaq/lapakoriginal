(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    toogle_sidebar();
  });

  $(".sidebar_overlay_box").on('click', function(){
    toogle_sidebar();
  });
  if ($(window).width() < 768) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    
    $('.topbar').toggleClass("topbar-hide");
    $('#content-wrapper').toggleClass("content-hide");
/*
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
      //$('.topbar').addClass("topbar-hide");
      //$('#content-wrapper').css("margin-left", "0 !important");
    }else{
      //$('.topbar').removeClass("topbar-hide");
      $('#content-wrapper').removeClass("content-hide");

    };*/
  };
  // Close any open menu accordions when window is resized below 768px
  /*$(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });*/

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

  $(document).ready(function(){
    var url = window.location.href;
    //console.log(url);
    $(".nav-item .nav-link").each(function(){
      //console.log(this.href);
      if(this.href == url){
        $(this).parent().addClass("active");
      }else{
        var a = this.href;
        var b = url;
        a = a.split("/");
        b = b.split("/");
        //console.log(a[4]+"=="+b[4]);
        if(a[4]!='' && a[4]==b[4]){
          $(this).parent().addClass("active");
        }else{
          $(this).parent().removeClass("active");
        }
        //console.log(b.split("/"));
        /*console.log(this.href);
        console.log("  = "+url);*/
      }
    });
  });

})(jQuery); // End of use strict


function toogle_sidebar() {
  $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    
    $('.topbar').toggleClass("topbar-hide");
    $('#content-wrapper').toggleClass("content-hide");

    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
      console.log("close");
      // close sidebar
      $(".sidebar_overlay_box").hide();
      //$('.topbar').addClass("topbar-hide");
      //$('#content-wrapper').css("margin-left", "0 !important");
    }else{
      //$('.topbar').removeClass("topbar-hide");
      console.log("open");
      // open sidebar
      $(".sidebar_overlay_box").show();
      $('#content-wrapper').removeClass("content-hide");

    };
}
