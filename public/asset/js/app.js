$(document).ready(function () {

    /* ============================================================
       MOBILE SIDEBAR TOGGLE
       - SB Admin 2 JS handle accordion & toggle di DESKTOP
       - Script ini handle hamburger & overlay di MOBILE
       Keduanya tidak konflik karena dibatasi cek lebar layar.
       ============================================================ */

    var $sidebar   = $('.sidebar');
    var $overlay   = $('#sidebarOverlay');
    var $hamburger = $('#hamburgerMenu');

    // Hamburger diklik → buka/tutup sidebar mobile
    $hamburger.on('click', function () {
        var isOpen = $sidebar.hasClass('active');
        $sidebar.toggleClass('active');
        $overlay.toggleClass('active');
        $(this).toggleClass('active').attr('aria-expanded', String(!isOpen));
    });

    // Klik overlay → tutup sidebar
    $overlay.on('click', function () {
        tutupSidebar();
    });

    // Klik nav-link (bukan accordion) di mobile → tutup sidebar
    $sidebar.find('.nav-link:not([data-toggle])').on('click', function () {
        if ($(window).width() < 768) {
            tutupSidebar();
        }
    });

    // Resize ke desktop → bersihkan state mobile
    $(window).on('resize', function () {
        if ($(this).width() >= 768) {
            tutupSidebar();
        }
    });

    function tutupSidebar() {
        $sidebar.removeClass('active');
        $overlay.removeClass('active');
        $hamburger.removeClass('active').attr('aria-expanded', 'false');
    }

    /* ============================================================
       SCROLL TO TOP
       ============================================================ */
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 100) {
            $('.scroll-to-top').fadeIn(200);
        } else {
            $('.scroll-to-top').fadeOut(200);
        }
    });

    $('.scroll-to-top').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 500, 'swing');
    });

});
