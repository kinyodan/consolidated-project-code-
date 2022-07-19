$(document).ready(function () {


  $('.notification_tigger').click(function () {
    $('body').toggleClass('notif_open');
  });

  $('.user_modal').click(function () {
    $('body').removeClass('notif_open');
  });//Notification module ends

  // Mobile dashboard menu starts
  $('.menu_toggle').click(function () {
    $('body').toggleClass('nav-open');
  });

  $('.menu_overlay').click(function () {
    $('body').removeClass('nav-open');
  });
  // Mobile dashboard menu ends

  // Toggle menu expand/collapse
  $('.menu_collapse').click(function () {
    $('.app_navigation').toggleClass('app_navigation--collapse');
  });


  $('.dashboard_banner_slider').bxSlider({
    controls: false,
    auto: true,
    pause: 6000
  });

  // Subject Choice
  // $('.course-categories .category').on('click', function (event) {
  //   event.preventDefault();
  //   let selected = $(this);
  //   selected.next('.course-subjects').removeClass('is-hidden').end().closest('.course-categories').addClass('move-out');
  // });

  // $('.go-back').on('click', function (event) {
  //   event.preventDefault();
  //   let selected = $(this);
  //   selected.closest('.course-subjects').addClass('is-hidden').closest('.course-categories').removeClass('move-out');
  // });

  // $('.course-categories__close').on('click', function (event) {
  //   event.preventDefault();
  //   $(this).closest('.course-categories').hide();
  // });


});//end doc .ready
