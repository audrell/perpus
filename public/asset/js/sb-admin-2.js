(function($) {
  "use strict";

  console.log('🚀 SB Admin 2 JS Loaded');

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    }
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    }

    if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
      $("body").addClass("sidebar-toggled");
      $(".sidebar").addClass("toggled");
      $('.sidebar .collapse').collapse('hide');
    }
  });

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

  // ⭐⭐⭐ AUTO EXPAND/COLLAPSE SIDEBAR ON HOVER ⭐⭐⭐
  $(document).ready(function() {
    console.log('🎯 Initializing sidebar hover...');
    console.log('Sidebar found:', $('#accordionSidebar').length);

    let hoverTimeout;

    // Only on desktop
    if ($(window).width() > 768) {

      $('#accordionSidebar').hover(
        // Mouse enter
        function() {
          console.log('🖱️ Mouse ENTER sidebar');
          clearTimeout(hoverTimeout);
          $(this).addClass('sidebar-expanded');
        },
        // Mouse leave
        function() {
          console.log('🖱️ Mouse LEAVE sidebar');
          var $sidebar = $(this);
          hoverTimeout = setTimeout(function() {
            $sidebar.removeClass('sidebar-expanded');
          }, 300);
        }
      );

      console.log('✅ Sidebar hover initialized');

    } else {
      console.log('📱 Mobile detected - hover disabled');
    }

    // Re-init on resize
    $(window).on('resize', function() {
      $('#accordionSidebar').off('mouseenter mouseleave');

      if ($(window).width() > 768) {
        $('#accordionSidebar').hover(
          function() {
            clearTimeout(hoverTimeout);
            $(this).addClass('sidebar-expanded');
          },
          function() {
            var $sidebar = $(this);
            hoverTimeout = setTimeout(function() {
              $sidebar.removeClass('sidebar-expanded');
            }, 300);
          }
        );
      } else {
        $('#accordionSidebar').removeClass('sidebar-expanded');
      }
    });

  });

})(jQuery);
