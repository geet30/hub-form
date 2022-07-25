//3rd module


$(document).ready(function () {

  $('#sidebar a[href^="#"]').on('click', function (e) {
    e.preventDefault();
    $(document).off("scroll");
    $('#sidebar a').each(function () {
      $(this).removeClass('active');
    })
    $(this).addClass('active');
    var target = this.hash;
      var menu = target;
    $target = $(target);

      console.log($target.offset().top);

    $('html, body').stop().animate({
      'scrollTop': $target.offset().top + 2
    }, 600, 'swing', function () {
     // window.location.hash = target;
    });
  });

  $('.datePicker').datepicker({
    autoclose: true,
    format: 'mm-dd-yyyy',
    startDate: '-100y',
    endDate: '-0d',
  });

  $('.follow_up-datePicker').datepicker({
    autoclose: true,
    format: 'mm-dd-yyyy',
    startDate: new Date(),
  });

  $('.start_timepicker').datetimepicker({
    format: 'LT',
    ignoreReadonly: true,
    minDate: new Date().setHours(0,0),
    icons: {
        up: 'fa fa-angle-up',
        down: ' fa fa-angle-down'
    }
  });
  $('.end_timepicker').datetimepicker({
    format: 'LT',
    ignoreReadonly: true,
    minDate: new Date().setHours(0,0),
    icons: {
        up: 'fa fa-angle-up',
        down: ' fa fa-angle-down'
    }
  });
});

$(window).scroll(function () {
  var windscroll = $(window).scrollTop();
  if (windscroll >= 270) {
    $('nav').addClass('stickydiv');
    $('.wrapper section').each(function (i) {
      if ($(this).position().top <= windscroll - 200) {
        $('#sidebar ul li a.active').removeClass('active');
        $('#sidebar ul li a').eq(i).addClass('active');
      }
    });

  } else {

    $('nav').removeClass('stickydiv');
    $('#sidebar ul li a.active').removeClass('active');
    $('#sidebar ul li a:first').addClass('active');
  }

}).scroll();



//sliding

$(".show-detail").click(function () {
  $("#viewOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});

$(".edit-detail").click(function () {
  $("#editOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});


$(".close-side").click(function () {
  $(".inner-slide").addClass("slideOutRight");
  setTimeout(function () {
    $("#viewOverlay").removeClass("show-overlay");
    //    $(".sidebox-overlay").removeClass("show-overlay");
    $("body").removeClass("hideout");
    $(".inner-slide").removeClass("slideOutRight");

  }, 1000);
});

$(".close-edit-side").click(function () {
  $(".inner-slide").addClass("slideOutRight");
  setTimeout(function () {
    $("#editOverlay").removeClass("show-overlay");
    //    $(".sidebox-overlay").removeClass("show-overlay");
    $("body").removeClass("hideout");
    $(".inner-slide").removeClass("slideOutRight");

  }, 1000);
});


//history
$(".show-history").click(function () {
  $("#historyOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});

$(".close-side-his").click(function () {
  $(".inner-slide").addClass("slideOutRight");
  setTimeout(function () {
    $(".sidebox-overlay").removeClass("show-overlay");
    $("body").removeClass("hideout");
    $(".inner-slide").removeClass("slideOutRight");
  }, 1000);
});

//select-goal
$(".select-goal").click(function () {
  $("#goalOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});

$(".close-side-goal").click(function () {
  $(".inner-slide").addClass("slideOutRight");
  setTimeout(function () {
    $(".sidebox-overlay").removeClass("show-overlay");
    $("body").removeClass("hideout");
    $(".inner-slide").removeClass("slideOutRight");
  }, 1000);
});

//select-goal-detail
$(".goal-detail").click(function () {
  $("#viewOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});

//barrier-detail
$(".barrier-detail").click(function () {
  $("#barrierOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});

$(".close-side-barrier").click(function () {
  $(".inner-slide").addClass("slideOutRight");
  setTimeout(function () {
    $(".sidebox-overlay").removeClass("show-overlay");
    $("body").removeClass("hideout");
    $(".inner-slide").removeClass("slideOutRight");
  }, 1000);
});
//barrier-detail
$(".tool-detail").click(function () {
  $("#toolsOverlay").addClass("show-overlay");
  $("body").addClass("hideout");
});

$(".close-side-barrier").click(function () {
  $(".inner-slide").addClass("slideOutRight");
  setTimeout(function () {
    $(".sidebox-overlay").removeClass("show-overlay");
    $("body").removeClass("hideout");
    $(".inner-slide").removeClass("slideOutRight");
  }, 1000);
});



//fix sidebar

$(window).scroll(function () {
  if ($(this).scrollTop() > 270) {
    $(".rightlistbar").addClass("fixMainSide");
  } else {
    $(".rightlistbar").removeClass("fixMainSide");
  }
});

$(window).scroll(function () {
  if ($(this).scrollTop() > 300) { // 300px from top
    $('#goTop').fadeIn();
    $('.fix-info').fadeIn();
  } else {
    $('#goTop').fadeOut();
    $('.fix-info').fadeOut();
  }
});
$('#goTop').each(function () {
  $(this).click(function () {
    $('html,body').animate({
      scrollTop: 0
    }, 'slow');
    return false;
  });
});


$('.show-flag').click(function() {
  $('.select-flag').fadeToggle();
})



$('.closeall').click(function(){
  $('.collapse.show')
    .collapse('hide');
});
$('.openall').click(function(){
  $('.collapse:not(".show")')
    .collapse('show');
});
