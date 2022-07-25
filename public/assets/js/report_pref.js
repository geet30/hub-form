$(document).ready(function() {

    $(".rep_checkbox").attr("disabled", true);
    // $(".rep_checkbox").attr("checked", true);
    $(".sbmit").attr("disabled", true);

    $(".edit_pref").click(function(event){
      event.preventDefault();
      $(".rep_checkbox").attr("disabled", false);
      $(".sbmit").attr("disabled", false);
      $(".checker").removeClass("disabled");
    });

    // Setting the filters for report
    var report_filter_string = $('.report_filter_string').val();
    var res = report_filter_string.split(" ");
    $.each(res, function( index, value ) {
      $('.'+value).prop('checked', true);
      $('.'+value).parent().addClass('checked');
    });

    $(".report_pdf").click(function(event){
    });
});
