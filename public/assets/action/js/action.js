$(document).ready(function () {
    $('.dynamicAppendChat').empty();
    if ($("#mainChatSection").length != 0) {
        var messageBody = document.querySelector('#mainChatSection');
        messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
    }

});


$(document).ready(function () {

    $(":checkbox").uniform();
    // min start and end date today's date
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
    var yyyy = today.getFullYear();
    mm = ('0' + (mm)).slice(-2);
    dd = ('0' + (dd)).slice(-2);
    today = yyyy + '-' + mm + '-' + dd;
    $("#start_recur").attr("min", today);
    $("#end_recur").attr("min", today);

    // show data accroding to recurring type in edit action 
    var type = $('input[name=recurring_type]:checked').val();;
    if (type == 1) {
        $('.daily_data').show();
        $('.weekly_data').hide();
        $('.monthly_data').hide();
        $('.yearly_data').hide();
    } else if (type == 2) {
        $('.weekly_data').show();
        $('.monthly_data').hide();
        $('.yearly_data').hide();
        $('.daily_data').hide();
    } else if (type == 3) {
        $('.monthly_data').show();
        $('.weekly_data').hide();
        $('.yearly_data').hide();
        $('.daily_data').hide();
    } else if (type == 4) {
        $('.yearly_data').show();
        $('.weekly_data').hide();
        $('.monthly_data').hide();
        $('.daily_data').hide();
    } else {
        $('.weekly_data').hide();
        $('.monthly_data').hide();
        $('.yearly_data').hide();
        $('.daily_data').hide();
    }

    //validate save action form
    $("#createAction, #updateAction").validate({
        ignore: ".ignore",
        rules: {
            action_title: {
                required: true,
                maxlength: '30'
            },
            action_desc: {
                required: true
            },
            //   location: {
            //       required: true,
            //   },
            business_unit: {
                required: true,
            },
            department: {
                required: true,
            },
            //   project: {
            //     required: true,
            //   },
            assignee: {
                required: true
            },
            due_date: {
                required: true
            }
        },
        messages: {
            action_title: {
                required: "Please enter action title.",
                maxlength: "Please enter less then 30 characters."
            },
            action_desc: {
                required: "Please enter description of action."
            },
            business_unit: {
                required: "Please select business unit",
            },
            department: {
                required: "Please select department ",
            },
            //   project: {
            //     required: "Please select project",
            //   },
            assignee: {
                required: "Please select assignee"
            },
            due_date: {
                required: "Please enter due date."
            }
        },
        submitHandler: function (form) {
            form.submit();
        },
    });



});

$(".addquestionbutton ").click(function () {
    $(".addquestion-area ").slideToggle();
});

$("#action_button").click(function () {
    $('#recurring_modal').hide();
});

$(function () {
    $("#datepicker").datepicker({
        startDate: new Date(),
        // dateFormat: "dd-mm-yy",
        // altFormat: "dd-mm-yy",
        // dateFormat: 'dd/mm/yyyy',
        // format: 'dd/mm/yyyy'

    });
});
var actionDataTitle = $('.titleText').val();
var descData = $('.descText').val();
if (actionDataTitle == "") {
    $('#actiontTitle').text($('.titleText').attr('placeholder'));
}
if (descData == "") {
    $('#actionDescription').text($('.descText').attr('placeholder'));
}

$("#titleEdit").click(function () {
    var oldHtml = $("#actiontTitle").html();
    if (oldHtml == "Enter Title") {
        $("#actiontTitle").empty();
    }
    $("#actiontTitle").prop("contenteditable", true);
    $('#actiontTitle').focus();
});

$("#actiontTitle").focusout(function () {
    var html = $(this).html();
    if (html == '') {
        $(this).html("Enter Title");
    }
});




$("#editDescription").click(function () {
    var oldHtml = $("#actionDescription").html();
    if (oldHtml == "Enter Description") {
        $("#actionDescription").empty();
    }
    $("#actionDescription").prop("contenteditable", true);
    $('#actionDescription').focus();
});

$("#actionDescription").focusout(function () {
    var html = $(this).html();
    if (html == '') {
        $(this).html("Enter Description");
    }
});


$('body').on('click', function (event) {
    if (!$(event.target).is('#actionDescription') && !$(event.target).is('#editDescription')) {
        $('#actionDescription').prop('contenteditable', false);
    }
    if (!$(event.target).is('#actiontTitle') && !$(event.target).is('#titleEdit')) {
        $('#actiontTitle').prop('contenteditable', false);
    }
});


$('#actiontTitle').keyup(function () {
    $('.titleText').val($('#actiontTitle').text());
});

$('#actionDescription').keyup(function () {
    $('.descText').val($('#actionDescription').text());
});

$("#enableEditChat").click(function () {
    var chatText = $('textarea#messageTextarea').val();
    var filePath = $('#file_name').val();
    var actionId = $('#userActionId').val();
    var currentTimeStamp = new Date().getTime();
    var sentExtType = $('.mediaExt').val();
    if (chatText != "" || filePath != "" && actionId != "") {
        $('#loading').css('display', 'block');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '/admin/edit_action_chat',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                action_id: actionId,
                chat_message: chatText,
                file_name: filePath,
                current_time: currentTimeStamp,
                file_ext: sentExtType
            },
            dataType: 'JSON',
            success: function (data) {
                console.log("data");
                console.log(data);
                if (data.type == 'success') {
                    var message = data.message;
                    $('textarea#messageTextarea').val('');
                    
                    // $('.dynamicAppendChat').html(data.document);
                    var d = $("#mainChatSection");
                    d.scrollTop(d[0].scrollHeight);
                    $('#preview_image').css('display', 'none');
                    $('.removePreviewDynamic').css('display', 'none');
                    $('#file_name').val('');
                    $('#file').val('');
                    $('.mediaExt').val('');
                    // window.show_msg_notification();
                    // window.real_time_chat_get();
                } else {
                    var message = data.message;
                    // alert(message); 
                    $('#loading').css('display', 'none');
                }
                
                // $('#loading').css('display', 'none'); 
                setTimeout(function(){

                     $('#loading').css('display', 'none'); 
                     
                    },3000);
                
            }
        });
        console.log("eee");
    } else {
        alert("Please Type Message.");
    }
});

$('.clipImage').click(function () {
    $('#preview_image').css('display', 'none');
    $('.removePreviewDynamic').css('display', 'none');
    $('#file_name').val('');
    $('#file').click();
});

$('#file').change(function () {
    if ($(this).val() != '') {
        upload(this);
    }
});

function upload(img) {
    var siteUrl = $('#site_url').val();
    $('#loading').css('display', 'block');
    var image = img.files[0];
    var imageName = image.name;
    var extension = imageName.substring(imageName.lastIndexOf('.') + 1);
    var storageRef = firebase.storage().ref('chat_media/' + imageName);
    var uploadTask = storageRef.put(image);
    uploadTask.on('state_changed', function (snapshot) {
        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        console.log("upload is " + progress + " done");
    }, function (error) {
        console.log(error.message);
        $('#loading').css('display', 'none');
    }, function () {
        uploadTask.snapshot.ref.getDownloadURL().then(function (downlaodURL) {
            console.log(downlaodURL);
            $('#preview_image').css('display', 'block');
            $('.removePreviewDynamic').css('display', 'block');
            $('#file_name').val(downlaodURL);
            $('.mediaExt').val(extension);
            if (extension == 'jpg' || extension == 'jpeg' || extension == 'png' || extension == 'gif')
                $('#preview_image').attr('src', downlaodURL);
            else if (extension == 'pdf')
                $('#preview_image').attr('src', siteUrl + '/pdf.png');
            else if (extension == 'mp4')
                $('#preview_image').attr('src', siteUrl + '/mp4.png');
            else if (extension == 'mp3')
                $('#preview_image').attr('src', siteUrl + '/mp3.png');
            else if (extension == 'doc' || extension == 'docx' || extension == 'docm' || extension == 'csv')
                $('#preview_image').attr('src', siteUrl + '/doc.png');
            else
                $('#preview_image').attr('src', siteUrl + '/file.png');
            $('#loading').css('display', 'none');
        });
    });
}

$('#actionComplete').click(function () {
    var actionId = $('#userActionId').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var statusId = 3;
    var form_data = new FormData();
    var currentTimeStamp = new Date().getTime();
    form_data.append('action_id', actionId);
    form_data.append('_token', CSRF_TOKEN);
    form_data.append('status_id', statusId);
    form_data.append('current_date_time', currentTimeStamp);
    //   $('#completeLoader').css('display', 'block');
    $.ajax({
        beforeSend: function () {
            $(".pre_loader").show();
        },
        url: '/admin/update_action_status',
        data: form_data,
        type: 'POST',
        contentType: false,
        processData: false,
        complete: function () {
            $(".pre_loader").hide();
        },
        success: function (data) {

            if (data.type == 'success') {
                alertSuccess(data.message, 'success');
                location.reload();
            } else {
                alertSuccess(data.message, 'error');
            }
        },
        error: function (xhr, status, error) {

            alertError("Something went wrong. Pleasee try again.", 'error');
        }
    });
});

function removeMediaPreview() {
    var downloadUrl = $('#file_name').val();
    if (downloadUrl != "") {
        var storageRef = firebase.storage().refFromURL(downloadUrl);
        storageRef.delete().then(function () {
            console.log('deleted');
            $('#file_name').val('');
            $('#file').val('');
            $('.mediaExt').val('');
            $('#preview_image').css('display', 'none');
            $('#preview_image').attr('src', '');
            $('.removePreviewDynamic').css('display', 'none');
            // File deleted successfully
        }).catch(function (error) {
            console.log('not deleted');
        });
    }
}

// recurring of action
function recurring() {
    $('#recurring_modal').show();
}


function set_end() {
    var start_date = $('#start_recur').val();
    var start_datee = $('#start_recur').val();

    $("#end_recur").attr("min", start_date);
    start_date = new Date(start_date);
    var year = start_date.getFullYear() + 2;
    var month = start_date.getMonth() + 1;
    var day = start_date.getDate();
    month = ('0' + (month)).slice(-2);
    day = ('0' + (day)).slice(-2);
    var end_date = year + '-' + month + '-' + day;
    $("#end_recur").attr("max", end_date);
    // $('#start_recur').val(start_datee);
    // $('#start_recur').attr("value",start_datee);
}

// function set_start(){
//    var end_date =  $('#end_recur').val();
//    start_date = new Date(start_date);
//    var year = start_date.getFullYear() + 2;
//    var month = start_date.getMonth();
//    var day = start_date.getDate();
//    var start_date = year+'-'+month+'-'+day;
//    $("#end_recur").attr("max", end_date);
// }
// show data according to type of recurring
function show_data(type) {
    var type = $.trim(type);
    if (type == 'daily') {
        $('.daily_data').show();
        $('.weekly_data').hide();
        $('.monthly_data').hide();
        $('.yearly_data').hide();
    } else if (type == 'weekly') {
        $('.weekly_data').show();
        $('.monthly_data').hide();
        $('.yearly_data').hide();
        $('.daily_data').hide();
    } else if (type == 'monthly') {
        $('.monthly_data').show();
        $('.weekly_data').hide();
        $('.yearly_data').hide();
        $('.daily_data').hide();
    } else if (type == 'yearly') {
        $('.yearly_data').show();
        $('.weekly_data').hide();
        $('.monthly_data').hide();
        $('.daily_data').hide();
    } else {
        $('.weekly_data').hide();
        $('.monthly_data').hide();
        $('.yearly_data').hide();
        $('.daily_data').hide();
    }
}

// on click ok button modal close with data 
function close_modal(type) {
    var type = $.trim(type);
    if ($('.recurring_type').is(':checked')) {
        $("#recurring_action").parent().addClass('checked');
        $("#recurring_action").attr("checked", true);
        // var date=$("#end_recur").val().split("-");
        // $("#datepicker").val(date[1]+"/"+date[0]+"/"+date[2].trim());
        $("#datepicker").css("background","lightgray");
        var date=$("#end_recur").val();
        // console.log(date[0]+"/"+date[1]+"/"+date[2].trim());
        // $("#datepicker").attr("value",date[0]+"/"+date[1]+"/"+date[2].trim());
        $('#datepicker').val( date );
        $('#datepicker').attr("disabled",true);
    }else{      
        $('#datepicker').attr("disabled",false);
          $('#datepicker').attr("readonly",false);
        $("#datepicker").val("");
        $("#datepicker").css("background","white");
    }
    if (type == 'ok') {
        var no_error = '';
        if ($('.recurring_type').is(':checked')) {
            var name = $('input[name=recurring_type]:checked').val();
            name = $.trim(name);
            if (name == 1) {
                if ($('.daily_day').is(":visible") && $('.daily_day').val() == '') {
                    $('.recur_error').text('Please select value for recurrence.');
                    no_error = '0';
                    // return false;
                } else {
                    $('.recur_error').text('');
                    no_error = '1';
                    // return true;
                }
            } else if (name == 2) {
                if ($('.weekly_week').is(":visible") && $('.weekly_week').val() == '') {
                    $('.recur_error').text('Please select week for recurrence.');
                    no_error = '0';
                    // return false;
                } else if ($('.weekly_day').is(":visible") && $('input[name="weekly_day[]"]:checked').length <= 0) {
                    $('.recur_error').text('Please select days for recurrence.');
                    no_error = '0';
                    // return false;
                } else if ($('.weekly_week').is(":visible") && $('input[name="weekly_day[]"]:checked').length > $('.weekly_week').val()) {
                    $('.recur_error').text('Please select weekdays according to selected days for recurrence.');
                    no_error = '0';
                } else {
                    $('.recur_error').text('');
                    no_error = '1';
                    // return true;
                }
            } else if (name == 3) {
                if ($('.monthly_pattern').is(':checked')) {
                    var monthly = $('input[name=monthly_pattern]:checked').val();
                    monthly = $.trim(monthly);
                    if (monthly == '1') {
                        if ($('.month_day').is(":visible") && $('.month_day').val() == '') {
                            $('.recur_error').text('Please select day for monthly recurrence.');
                            no_error = '0';
                            // return false;
                        } else if ($('.month_week').is(":visible") && $('.month_week').val() == '') {
                            $('.recur_error').text('Please select week for monthly recurrence.');
                            no_error = '0';
                            // return false;
                        } else if ($('.month_month').is(":visible") && $('.month_month').val() == '') {
                            $('.recur_error').text('Please select month for monthly recurrence.');
                            no_error = '0';
                            return false;
                        } else {
                            $('.recur_error').text('');
                            no_error = '1';
                            // return true;
                        }
                    } else if (monthly == '2') {
                        if ($('.month_day_sec').is(":visible") && $('.month_day_sec').val() == '') {
                            $('.recur_error').text('Please select day for monthly recurrence.');
                            no_error = '0';
                            // return false;
                        } else if ($('.month_month_sec').is(":visible") && $('.month_month_sec').val() == '') {
                            $('.recur_error').text('Please select month for monthly recurrence.');
                            no_error = '0';
                            // return false;
                        } else {
                            $('.recur_error').text('');
                            no_error = '1';
                            // return true;
                        }
                    }
                } else {
                    $('.recur_error').text('Please select pattern of monthly recurrence.');
                    no_error = '0';
                    // return false;
                }

            } else if (name == 4) {
                if ($('.year_day').is(":visible") && $('.year_day').val() == '') {
                    $('.recur_error').text('Please select day for monthly recurrence.');
                    no_error = '0';
                    // return false;
                } else if ($('.year_week').is(":visible") && $('.year_week').val() == '') {
                    $('.recur_error').text('Please select week for monthly recurrence.');
                    no_error = '0';
                    // return false;
                } else if ($('.year_month').is(":visible") && $('.year_month').val() == '') {
                    $('.recur_error').text('Please select month for monthly recurrence.');
                    no_error = '0';
                    // return false;
                } else {
                    $('.recur_error').text('');
                    no_error = '1';
                    // return true;
                }
            } else {
                $('.recur_error').text('');
                no_error = '1';
                // return true;
            }
        } else {
            $('.recur_error').text('Please select type of recurrence.');
            no_error = '0';
            // return false;
        }

        var start = new Date($('#start_recur').val());
        var end = new Date($('#end_recur').val());
        var start_year = start.getFullYear();
        var end_date = end.getFullYear();
        var diff = end_date - start_year;

        if ($('#start_recur').is(":visible") && $('#start_recur').val() == '') {
            $('.date_error').text('Please enter start date for recurrence.');
            no_error = '0';
            // return false;
        } else if ($('#end_recur').is(":visible") && $('#end_recur').val() == '') {
            $('.date_error').text('Please enter end date for recurrence.');
            no_error = '0';
            // return false;
        } else if ($('#start_recur').val() != '' && $('#end_recur').val() < $('#start_recur').val()) {
            $('.date_error').text('Please enter valid end date for recurrence.');
            no_error = '0';
            // return false;
        } else if (diff > 2 || diff < 0) {
            $('.date_error').text('Please enter valid range of dates maximum upto 2 years.');
            no_error = '0';
        } else if (no_error == '0') {
            $('.date_error').text('');
            no_error = '0';
        } else {
            $('.date_error').text('');
            no_error = '1';
            // return true;
        }

        if (no_error == '1') {
            //   $("#recurring_action").parent().removeClass('checked');
            $("#recurring_action").prop("checked", true);
            $("#recurring_action").val(1);
            $('#recurring_modal').hide();
            $('#cancel_edit').val('');
        }
        // $('#recurring_modal').hide(); 


    } else if (type == 'cancel') {
        $("#recurring_action").parent().removeClass('checked');
        $("#recurring_action").prop("checked", false);
        $('.date_error').text('');
        $('.recur_error').text('');
        $('#recurring_modal').hide();
    } else if (type == 'cancel_edit') {
        $('.date_error').text('');
        $('.recur_error').text('');
        $('#recurring_modal').hide();
        $('#cancel_edit').val('1');
    }
    if ($('.recurring_type').is(':checked')) {
        $("#recurring_action").parent().addClass('checked');
        $("#recurring_action").attr("checked", true);
    }

};


// remove recurring on click button 

function remove_recurring(id, action_id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var recur_id = id;
    $.ajax({
        url: '/admin/remove_recurring',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            id: recur_id,
            action_id: action_id
        },
        // dataType: 'json',
        success: function () {
            console.log('here');
            $('#recurring_modal').hide();
            location.reload(true);
        },
        error: function (error) {
            alertError('Something Went Wrong!')
        }
    });
}

$('#updateAction').submit(function () {
    $('#actionLocation').attr('disabled', false);
    $('#actionAsigne').attr('disabled', false);
    $('#actionPriority').attr('disabled', false);
    $('.due_date').attr('disabled', false);
    $('#recurring_action').attr('disabled', false);
});


function set_end_recur(){
    console.log("asd");
    
}
