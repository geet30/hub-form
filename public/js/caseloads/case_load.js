
 $( document ).ready(function() {
     fadeOutAlertMessages();
     //initialize input masks fields
     handleInitialization();

     $(document).on('change', "input[type=file][name=image]", function() {
         $('.browsebutton').find('span.error').hide().removeClass('active');
         readURL(this);
         $('.patient_pro_pic_preview span.imgcross').show();
         $('.patient-info-pro-pic-upload').hide();
         $('input[type=hidden][name=upload_image_or_not]').val('yes');
     });

     function readURL(input) {
         if (input.files && input.files[0]) {
             var reader = new FileReader();
             reader.onload = function(e) {
                $('div.patient-caseload-edit-patient-modal-image-attr-holder .userimageicon').replaceWith('<img id="previewHolder" alt="" width="150">');
                 $('#previewHolder').attr('src', e.target.result);
             }

             reader.readAsDataURL(input.files[0]);
         }
     }

     $(document).on('change','input[type=file][name=uploaded_document]',function(e){
         $('.upload_document_error').hide().removeClass('active');
         $("#selected_doc_name").html(e.target.files[0].name);
         $("#delete_selected_doc").show();
     });

     $(document).on('click','#delete_selected_doc',function(e){
         $('.upload_document_error').hide().removeClass('active');
         $('#patient-careplan-document-form').find('input[type=file]').val("");
         $("#selected_doc_name").html('No file selected');
         $("#delete_selected_doc").hide();
     });
     $(document).on('click','.edit_assignment',function(e){
         jQuery(".assigned_cm").val($(this).attr('data-cm'));
         jQuery(".assigned_chw").val($(this).attr('data-chw'));
         jQuery(".assigned_md").val($(this).attr('data-md'));
         initCustomForms();
         $('#editAssignment').modal('show');
     });
     $(document).on('click','.editPatient',function(e){
         document.getElementById("patient-info-form").reset();

         $('.patient_pro_pic_preview').attr('data-src');
         if($('.patient_pro_pic_preview').attr('data-is_image') == '1'){
            $('input[type=hidden][name=upload_image_or_not]').val('no');
            $('.imgcross').show();
            $('.patient-info-pro-pic-upload').hide();
         }
         $('#previewHolder').removeAttr("src").attr("src", $('.patient-caseload-edit-patient-modal-image-attr-holder').attr('data-img'));
         initCustomForms();
         $('#editPatient').modal('show');
     });
 });

//Fill values in Assignment Modal before it fully opens
function edit_care_plan_team(){
  var formData = new FormData($('#edit_care_team_form')[0]);
  var assigned_chw = formData.get("assigned_chw");
  var assigned_cm = formData.get("assigned_cm");
  var assigned_md = formData.get("assigned_md");


 $.ajax({
      url:care_team_update_route,
      data:formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success:function(data){
          $('.model_box_save').removeAttr("disabled");
          $('input,textarea,select').removeClass('changed-input');
          if(data.status){
            $(".leftsectionpages").prepend('<div class="alert alert-success alert-dismissible" style="display: block;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>'+data.message+'</div>');
            $('.wellpop_team_section').html(data.html);
          }
          else{
            $(".leftsectionpages").prepend('<div class="alert alert-danger alert-dismissible" style="display: block;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>'+data.message+'</div>')
          }
          $('#editAssignment').modal('hide');
          $('.wellpop_team_section').parent().parent().find('.edit_assignment').attr('data-chw',assigned_chw).attr('data-cm',assigned_cm).attr('data-md',assigned_md);
          jQuery('html, body').animate({
                  scrollTop: jQuery('body').offset().top
              }, 500);
          fadeOutAlertMessages();
      },
      error:function(error){
          if(error.status == 500){
              $('.modal-body').prepend('<div class="alert alert-danger alert-dismissible" style="display: block;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>Internal Server Error</div>')
          }
          else{
              $.each(error.responseJSON.errors,function(key,value){
                  $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                  $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();

              });
          }

      }
  });
}


function saveform(button_pressed,formId,tab){
  $('.model_box_save').attr("disabled", "disabled");
  $('span.error').hide().removeClass('active');

  //unmask value of phone number fields
  $(".set_phone_format").inputmask('remove');

  $(".set_phone_format").inputmask('remove');
  var formData = new FormData($(formId)[0]);
 // formData.append( 'assignment_modal',1);

  $.ajax({
      url:patient_update_route,
      data:formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success:function(data){
          $('.model_box_save').removeAttr("disabled");
          $('input,textarea,select').removeClass('changed-input');
          if(data.status){
            $(".leftsectionpages").prepend('<div class="alert alert-success alert-dismissible" style="display: block;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>'+data.message+'</div>');
            $('.profile_section').html(data.profile_html);
            $('.edit_patient_modal').html(data.modal_html);
            $('.patient_header_section').html(data.patient_header);
            handleInitialization();
          }
          else{
            $(".leftsectionpages").prepend('<div class="alert alert-danger alert-dismissible" style="display: block;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>'+data.message+'</div>')
          }

          $('#editPatient').modal('hide');
          jQuery('html, body').animate({
                  scrollTop: jQuery('body').offset().top
              }, 500);
          fadeOutAlertMessages();
          //window.location.reload();
      },
      error:function(error){
          if(error.status == 403)
          {
              $('#editPatient').modal('hide');
              bootbox.alert("<div><p style='text-align:center'>You do not have permission to access this module/feature.<p><p style='text-align:center'>Please contact administrator for more information.</p></div>",function(){
                  location.reload(true);
              });
          }
          if(error.status == 500){
              $('.modal-body').prepend('<div class="alert alert-danger alert-dismissible" style="display: block;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>Internal Server Error</div>')
          }
          else{
              $('.model_box_save').removeAttr("disabled");
              $(".set_phone_format").inputmask("(999) 999-9999",{showMaskOnFocus : false, showMaskOnHover : false});
              $.each(error.responseJSON.errors,function(key,value){
                  if(key == 'image'){
                      $('#invalid_image_error').html(value).addClass('active').show();
                  }
                  $('input[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
                  $('select[name="'+key+'"]').parent().find('span.error').html(value).addClass('active').show();
              });
          }
      }
  });
}

function handleInitialization(){

   $(".set_phone_format").inputmask("(999) 999-9999",{showMaskOnFocus : false, showMaskOnHover : false});
     $(".ssn_format").inputmask("999-99-9999",{showMaskOnFocus : false, showMaskOnHover : false});

     $('.datePicker_dob').datepicker({
         autoclose: true,
         format: 'mm-dd-yyyy',
         endDate: '-10y',
         startDate: '-100y',
     });
}
