$('.closeall').click(function () {
    $('.collapse.show').collapse('hide');
});
$('.openall').click(function () {
    $('.collapse:not(".show")').collapse('show');
});
$(window).scroll(function () {
    if ($(this).scrollTop() > 270) {
        $(".rightlistbar").addClass("fixMainSide");
    } else {
        $(".rightlistbar").removeClass("fixMainSide");
    }
});
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) { // 300px from top
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
$('#php-communication-start-time').datetimepicker({
    format: 'LT',
    ignoreReadonly: true,
    minDate: new Date().setHours(0, 0),
    icons: {
        up: 'fa fa-angle-up',
        down: ' fa fa-angle-down'
    }
});
$('#php-communication-end-time').datetimepicker({
    format: 'LT',
    ignoreReadonly: true,
    minDate: new Date().setHours(0, 0),
    icons: {
        up: 'fa fa-angle-up',
        down: ' fa fa-angle-down'
    }
});