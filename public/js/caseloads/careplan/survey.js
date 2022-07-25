$(document).on('click', '#add-survey', function(e) {
 getsurveyList(surveyListRoute, false);
  $("#careplan-survey-sidebar").addClass("show-overlay");
  $('.history-body').find('form input,form textarea,form select').prop("disabled", false);
  $('#careplan-frequency-select').val('');
  $('#search_bar_survey').val('');
  $('span.error').text('').hide();
  $("body").addClass("hideout");
});
applpyEllipses('survey-table', 2, 'no');
$(document).on('keyup', '#search_bar_survey', function (e) {
    if(e.which >= 38 &&  e.which <= 40) {
        return false;
    }
    var query = $(this).val();
    if(query !== '') {
        var url = surveyListRoute+"?data="+query;
        getsurveyList(url,false);
    }

    if(query === '') {
        getsurveyList(surveyListRoute, false);
    }
});

function getsurveyList(url, appendData = null) {
  $.ajax({
      url: url,
      type: 'get',
      data: {
        'patient_id': patient_id,
         'careplan_id': careplan_id,
      },
      success: function(data){
        if(appendData) {
            $('#survey_table').append(data.html);
        } else {
            $('#survey_table').html(data.html);
        }
       $('.careplan-survey-sidebar-table-container table').attr('data-next-page', data.next_page);
        initCustomForms();
        $('.dable-survey-eye').prop("disabled", true ).css('color', 'lightgray');
        $('.dable-survey-radio').prop("disabled", true ).parents('span').css('background-color', 'lightgray');
      }
  });
}

$('.careplan-survey-slider').scroll(function(){
    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
        var page = $('.careplan-survey-sidebar-table-container table').attr('data-next-page') ? $('.careplan-survey-sidebar-table-container table').attr('data-next-page') : null;        
        if(page){
            getsurveyList(page, true);
        }
        
    }
});
$(document).on('click', '#save-careplan-survey-btn', function(e){
    e.preventDefault();
    $.ajax({
        url: surveySaveRoute,
        type: 'post',
        data: $('#careplan-survey-form').serialize(),
        success: function(response){
          fadeOutAlertMessages();
          $('#careplan-survey-main-table').html(response.html);
          if(response.status == "danger")
          {
            $('.survey-error').removeClass('hide alert-success').addClass('alert-danger').show();
            $(".survey-error .alert-message").html('');
            $(".survey-error .alert-message").html(response.message);
          }
          else{
            $('.survey-error').removeClass('hide alert-danger').addClass('alert-success').show();
            $(".survey-error .alert-message").html('');
            $(".survey-error .alert-message").html(response.message);
          }
            $(".inner-slide").addClass("slideOutRight");
            setTimeout(function () {
                $("#careplan-survey-sidebar").removeClass("show-overlay");
                $("body").removeClass("hideout");
                $(".inner-slide").removeClass("slideOutRight");
            }, 1000);
            applpyEllipses('careplan-survey-table', 4, 'no');
            table_load();
        },
        error:function(error){
            $.each(error.responseJSON.errors,function(key,value) {

                if( key === 'careplan-survey-radiobutton' || key === 'frequency' ) {
                    $('#'+key+'').html(value).addClass('active').show();
                } else {
                $('input[name="'+key+'"]',$(formId)).parent().find('span.error').html(value).addClass('active').show();
                $('textarea[name="'+key+'"]',$(formId)).parent().find('span.error').html(value).addClass('active').show();
                $('select[name="'+key+'"]',$(formId)).parent().find('span.error').html(value).addClass('active').show();
                }

            });
        }
    });
});

$(document).on('click', '#careplan-survey-main-table .pagination a', function(e) {
   e.preventDefault();
   let page = getURLParameter($(this).attr('href'), 'page');
   let url = careplanSurveyPaginationRoute + page;
   surveyPagination(page,url);

});

function surveyPagination(page,url){
 $.ajax({
     url: url,
     type: 'get',
     data: {
       'patient_id': patient_id,
         'careplan_id': careplan_id,
     },
     success: function(response){
         $('#careplan-survey-main-table').html(response);
         applpyEllipses('careplan-survey-table', 4, 'no');
         initCustomForms();
         table_load();
     }
 });
}
$(document).on('click', '.careplan-survey-delete', function(e){
       e.preventDefault();
       let careplanSurveyId = $(this).attr('data-id');
       var message = "Are you sure you want to delete this Survey?";
       bootbox.confirm(message, function(result) {
           if(result)
           {
               $.ajax({
                   url: surveyDeleteRoute,
                   data: {
                       'careplan_id': careplan_id,
                       'patient_id': patient_id,
                       'careplanSurveyId': careplanSurveyId,
                   },
                   success: function(response){
                       $('#careplan-survey-main-table').html(response.html);
                       if(response.status == "danger")
                       {
                         $('.survey-error').removeClass('hide alert-success').addClass('alert-danger').show();
                         $(".survey-error .alert-message").html('');
                         $(".survey-error .alert-message").html(response.message);
                       }
                       else{
                         $('.survey-error').removeClass('hide alert-danger').addClass('alert-success').show();
                         $(".survey-error .alert-message").html('');
                         $(".survey-error .alert-message").html(response.message);
                       }
                       applpyEllipses('careplan-survey-table', 3, 'no');
                       fadeOutAlertMessages();
                       table_load();
                   }
               });

           }

       });
   });

   $(document).on('click', '.careplan-survey-sidebar-eye, .careplan-survey-main-table-eye', function(e){
       e.preventDefault();
       let sourceId = $(this).attr('data-id');
       $.ajax({
           url: surveyDetails,
           type: 'get',
           data: {
               'id': sourceId,
               'careplan_id': careplan_id,
               'patient_id': patient_id,
           },
           success: function(response){
               if(response.html)
               {
                 $('#survey-form-data').html(response.html);
                 applpyEllipses('care-plan-goals-table', 4, 'no');
                 table_load();
               }
               $('#view-careplan-survey').modal('show');
           },
           error: function(xhr, status, error){
               checkHttpStatus(xhr);
           }
       });
   });
