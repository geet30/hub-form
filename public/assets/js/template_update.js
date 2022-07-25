$(document).ready(function () {

    $(":checkbox").uniform();
    $(":radio").uniform();
    $('#example').show();
    $('.pre_loader').hide();
    var BU_table = $('#example').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        // ordering: false,
        aoColumnDefs: [],
        columnDefs: [
            {
                searchable: false,
                orderable: false,
                targets: 0
            },
            {
                orderable: false,
                targets: 4
            },
            {
                orderable: false,
                targets: 5
            },
            {
                width: "10%",
                targets: 0
            },
            {
                width: "20%",
                targets: 1
            },
            {
                width: "30%",
                targets: 2
            },
            // {
            //     width: "25%",
            //     targets: 3
            // },
            // {
            //     width: "25%",
            //     targets: 3
            // },
        ],
        order: [],
    });

    // BU_table.on( 'order.dt search.dt', function () {
    //     BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     } );
    // } ).draw();


    $('#example_filter').css('display', 'none');
    $(".filterhead").each(function (i) {
        $('.filter', this).on('keyup change', function () {
            if (BU_table.column(i).search() !== this.value) {
                BU_table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });

        // $('#status_search', this).on('change', function(){
        //     var col = $('.unpublish').attr('data-id');
        //     // console.log(col);
        //     // console.log(BU_table.column(4).search());
        //     if (BU_table.column(4).search() !== this.value) {
        //         BU_table
        //             .column(4)
        //             .search(this.value)
        //             .draw();
        //     }
        // });

    });

    //Archive template
    $(".archive_template").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to archive this template ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let id = $(self).attr('data-id');
                    $.ajax({
                        url: APP_URL + '/admin/archive_template',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            location.reload(true);
                        }
                    });
                }
            }
        });
    });

    //restore template
    $(".restore").click(function (event) {
        event.preventDefault();
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to restore this template ?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let id = $(self).attr('data-id');
                    $.ajax({
                        url: APP_URL + '/admin/restore_template',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            location.reload(true);
                        }
                    });
                }
            }
        });
    });
});
$('body').on('click', '.reset', function () {
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('.filter').trigger('change');
});
$(function () {
    var today = new Date();
    $("#fiscalYear").datepicker({
        dateFormat: "yy-mm-dd",
        endDate: "today",
        maxDate: today,
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        // gotoCurrent: true,
    });
});



/** Create Template JS */

$(document).ready(function () {

    // asign text of scope and methodology to input value
    var scope_text = $('.scope_methodology').text();
    $('#scope_methodology_input').val(scope_text);

    // assign text of section to input value
    var section_text = $('#section_title_1').text();
    $('#section_1').val(section_text);

    // Add new element in scope and methodology
    $("#add_more_scope").click(function () {
        // Finding total number of elements added
        var total_element = $(".scope").length;

        // last <div> with element class id
        var lastid = $(".scope:last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[1]) + 1;

        var max = 20;
        // Check total number elements
        if (total_element < max) {
            // Adding new div container after last occurance of element class
            $(".scope:last").after("<div class='scope' id='scope_" + nextindex + "'></div>");

            // Adding element to <div>
            $("#scope_" + nextindex).append("<a id='remove_scope_" + nextindex + "' class='remove_scope'><span class='glyphicon glyphicon-remove pull-right' alt='Remove'>" +
                "</span></a>&nbsp;<textarea placeholder='Type your text here' id='scopetxt_" + nextindex + "' name='snm_data[]' maxlength='200' class='scopetxt'></textarea>");

        }

    });

    // Remove element in scope and methodology
    $('.scope_container').on('click', '.remove_scope', function () {

        var id = this.id;
        if (id !== '') {
            var split_id = id.split("_");
            var deleteindex = split_id[2];
            // Remove <div> with id
            $("#scope_" + deleteindex).remove();
        }

    });

    // Remove element in section
    $('.section_contain').on('click', '.deletelist', function () {
        var id = this.id;
        if (id !== '') {
            var split_id = id.split("_");
            var deleteindex = split_id[1];
            // Remove <div> with id
            $("#section_container_" + deleteindex).remove();

            // if ($("#saved_data").val() == "") {
            //     // console.log("saved_data");
            //     saved_data = {};
            // } else {
            //     saved_data = JSON.parse(deleteindex);
            // }
            // // console.log(saved_data);
            // saved_data[deleteindex] = deleteindex;

            // $("#deletesection").val(JSON.stringify(saved_data));
         
        }



    });

    // Show question options
    $('.section_contain').on('click', '.addquestionbutton', function () {
        var id = this.id;
        var split_id = id.split("_");
        var addindex = split_id[1];

        $("#addquestion-area-" + addindex).slideToggle();

    });

    // Edit title of Scope and methodology
    $('#edit_scope').click(function () {
        $('.scope_methodology').prop('contenteditable', true);
        $('.scope_methodology').focus();
        var text = $('.scope_methodology').text();
        $('#scope_methodology_input').val(text);
        var value = $('#scope_methodology_input').val();
    });


    $('body').on('click', function (event) {
        if (!$(event.target).is('.edit_section') && !$(event.target).is('.section')) {
            $('.section').prop('contenteditable', false);
        }
        if (!$(event.target).is('#edit_scope') && !$(event.target).is('.scope_methodology')) {
            $('.scope_methodology').prop('contenteditable', false);
            var text = $('.scope_methodology').text();
            $('#scope_methodology_input').val(text);
        }
    });

    // asign value of scope and methodology to input
    $('.scope_methodology').keypress(function () {
        var scope_text = $('.scope_methodology').text();
        $('#scope_methodology_input').val(scope_text);
    });

    // validations of create templae form

    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 100MB');


    $("#create_template, #edit_template").validate({
        //   ignore: [],
        rules: {
            template_name: {
                required: true,
                maxlength: '30'
            },
            template_prefix: {
                required: true,
                maxlength: '30'
            },
            color_pin: {
                required: true,
            },
            // scope_methodology: {
            //     required: true,
            //     maxlength: '30'
            // },
            // "snm_data[]": {
            //     required: true,
            //     maxlength: '200'
            // },
            // "section[]": {
            //     required: true,
            //     maxlength: '30'
            // },
        },
        messages: {
            template_name: {
                required: "Please enter template name.",
                maxlength: "Please enter less then 30 characters."
            },
            template_prefix: {
                required: "Please enter template prefix.",
                maxlength: "Please enter less then 30 characters."
            },
            color_pin: {
                required: "Please select color pin.",
            },
            scope_methodology: {
                required: "Please enter the name for Scope & Methodology.",
                maxlength: "Please enter less then 30 characters."
            },
            "snm_data[]": {
                required: "Please enter the description.",
                maxlength: "Please enter less then 200 characters."
            },
            "section[]": {
                required: "Please enter the name of section.",
                maxlength: "Please enter less then 30 characters."
            },
        },
        //   submitHandler: function(form) {
        //     form.submit();
        //   },
        // focusInvalid: false,
        // invalidHandler: function (form, validator) {

        //     if (!validator.numberOfInvalids())
        //         return;

        //     $('html, body').animate({
        //         scrollTop: $(validator.errorList[0].element).offset().top
        //     }, 2000);

        // }

        // errorPlacement: function(error, element) {
        //     if (element.attr("class") == "scopetxt" )
        //         error.insertAfter(".errorTxt");
        //     // else if  (element.attr("class") == "phone" )
        //     //     error.insertAfter(".some-other-class");
        //     else
        //         error.insertAfter(element);
        // }
    });

    $('.save_template').on('click', function () {
        // console.log("this", this);
        // console.log($('#create_template').valid());
        if ($('#create_template, #edit_template').valid()) {
            $('form#create_template, form#edit_template')[0].submit();
            // $('form#create_template, form#edit_template').submit();

        }
    });



    $('.publish_template').on('click', function () {
        $('.scope_methodology').each(function () {
            if (this.value == '') {
                $(this).rules("add", {
                    required: true,
                    maxlength: '30',
                    messages: {
                        required: "Please enter the name for Scope & Methodology.",
                        maxlength: "Please enter less then 30 characters."
                    }
                });
            }
        });

        $('.scopetxt').each(function () {
            if (this.value == '') {
                $(this).rules("add", {
                    required: true,
                    maxlength: '200',
                    messages: {
                        required: "Please enter the description.",
                        maxlength: "Please enter less then 200 characters."
                    }
                });
            }
        });

        $('.section_name').each(function () {
            if (this.value == '') {
                $(this).rules("add", {
                    required: true,
                    maxlength: '30',
                    messages: {
                        required: "Please enter the name of section.",
                        maxlength: "Please enter less then 30 characters."
                    }
                });
            }
        });

        $('.question').each(function () {
            if (this.value == '') {
                $(this).rules("add", {
                    required: true,
                    // maxlength: '200',
                    messages: {
                        required: "Please enter the question.",
                        // maxlength: "Please enter less then 200 characters."
                    }
                });
            }
        });
        $('.question_type').each(function () {
            if ($(this).is(":visible") && this.value == '') {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please select the question type.",
                    }
                });
            }
        });
        $('.options_ques').each(function () {
            if ($(this).is(":visible") && this.value == '') {
                $(this).rules("add", {
                    // ignore: "#hidden",
                    required: true,
                    maxlength: '50',
                    messages: {
                        required: "Please enter the option.",
                        maxlength: "Please enter less then 50 characters."
                    }
                });
            }
        });

        $('.dropdown_type').each(function () {
            if ($(this).is(":visible") && this.value == '') {
                $(this).rules("add", {
                    // ignore: "#hidden",
                    required: true,
                    maxlength: '50',
                    messages: {
                        required: "Please enter the type.",
                        maxlength: "Please enter less then 50 characters."
                    }
                });
            }
        });
        $('.guide-input').each(function () {
            $(this).rules("add", {
                filesize: 100000000,
            });
        });

        if ($('#create_template, #edit_template').valid()) {
            $("#publish_value").val('Publish');
            $('.pre_loader').show();
        }
    });

    //onclick close button in share template
    $('.share_close').click(function () {
        $('#share_temp').hide();
        $('#shareInput').val('');
        $("#shareInput").attr("data-id", '');
        $('#shareDropdown').hide();
        $('#shareDropdown a').show();
        $('.share_error').hide().text('');
    });
});

//stop form submit on other buttons click
function stop_submit() {
    event.preventDefault();
}

// edit Section title 
function edit_section_title(section) {
    $('#section_title_' + section).prop('contenteditable', true);
    $('#section_title_' + section).focus();
    var text = $('#section_title_' + section).text();
    $('#section_' + section).val(text);
}

//assign value of section to input field
function section_value(section) {
    var text = $('#section_title_' + section).text();
    $('#section_' + section).val(text);
}

// add section
function add_section() {
    // Finding total number of elements added
    var total_element = $(".section_container").length;

    // last <div> with element class id
    var lastid = $(".section_container:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[2]) + 1;
    var nextcontainer = Number(split_id[2]) + 3;
    var nextindex_array = parseInt(nextindex) - 1;


    var max = 20;
    var doc_listing = '';
    if ($('#doc_listing').val() != '') {
        doc_listing = $('#doc_listing').val();
    } else {
        doc_listing = '<div class="no_doc"> No Document Found! </div>';
    }
    // Check total number elements
    if (total_element < max) {
        // Adding new div container after last occurance of element class
        $(".section_container:last").after("<div class='section_container' id='section_container_" + nextindex + "'></div>");

        // Adding element to <div>
        $("#section_container_" + nextindex).append('<div class="card">' +
            '<div class="card-header" id="headingOne"><div class="row"><div class="col-lg-12">' +
            '<h5 class="mb-0"><button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse' + nextindex + '" aria-expanded="true" aria-controls="collapse' + nextindex + '" onclick="stop_submit()">' +
            '<i class="fas fa-caret-down"></i><i class="fas fa-caret-right"></i></button><span class="section"  contenteditable="false" id="section_title_' + nextindex + '" onkeypress="section_value(\'' + nextindex + '\')" onkeyup="section_value(\'' + nextindex + '\')"> Section ' + nextindex + '</span><input type="text" name="section[]" value="" id="section_' + nextindex + '"class="section_name" style="display:none">' +
            '<span><i class="fas fa-pencil-alt edit_section" onclick="edit_section_title(\'' + nextindex + '\')"></i></span><div class="dropdown more-btn" style="position: static;">' +
            '<button class="btn dropdown-toggle" type="button" id="dropdownMenu' + nextindex + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> More</button>' +
            '<div class="dropdown-menu " aria-labelledby="dropdownMenu' + nextindex + '"><a class="dropdown-item deletelist" id="deletelist_' + nextindex + '">Delete</a>' +
            '<a class="dropdown-item add_section" onclick="add_section()">Add Section</a></div></div><div class="dropdown more-btn">' +
            '<button class="btn btn-secondary dropdown-toggle scrorebtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
            'Score</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><a class="dropdown-item"> <input type="checkbox" name="score[]" id="score' + nextindex + '" value="1"> Score</a>' +
            '</div></div></h5></div></div></div><div id="collapse' + nextindex + '" class="collapse" aria-labelledby="collapse' + nextindex + '" data-parent="#accordion">' +
            '<div class="card-body"><div class="question_container" id="question_container_' + nextindex + '"><div class="question_section_' + nextindex + '" id="question_section_' + nextindex + '_1"  data-questionId="question_section_' + nextindex + '_1"><div class="row question-main-sec">' +
            '<div class="col-md-1 question-action"><span class="question-serial">1</span>' +
            '<button class="action-edit" onclick="stop_submit()"><i class="fas fa-pencil-alt"></i>Edit</button><button class="action-edit delete_ques" id="delelte_quest_' + nextindex + '_1" onclick="delete_question( \'' + nextindex + '\', \'1\')"><i class="fas fa-trash"></i>' +
            'Delete</button>' +
            '</div><div class="col-md-10 question-data"><div class="row"><div class="col-md-6"><div class="namerinterviewfield">' +
            '<input type="text" placeholder="Enter Question" class="question" value="" name="question[' + nextindex_array + '][]" id="question_' + nextindex + '_1"></div></div></div><div class="variable-type">' +
            '<div class="row"><div class="col-md-12"><label>Type</label><div class="yesnobutton"><div class="row"><div class="col-md-3">' +
            '<select name="type[' + nextindex_array + '][0]" id="question_type_' + nextindex + '_1" class="question_type" onchange="showoptions(\'' + nextindex + '\',\'1\', this)"><option value="1" selected>Text</option>' +
            '<option value="2">Dropdown</option><option value="3">Date</option><option value="4">Number</option><option value="5"> Yes/No</option><option value="6">Multiple Choice</option>' +
            '<option value="7">Checkbox(Multiselect)</option><option value="8">Google Map Locator</option><option value="9">Signature</option></select>' +
            '</div><div class="col-md-3"><div class="customcheckboxmain"><label class="customcheckbox"><input type="checkbox" checked="checked" class="required_input" name="required[' + nextindex_array + '][0]" value="1">Required</label></div>' +
            '<a onclick="add_option(\'' + nextindex + '\', \'1\');" class="add_option_' + nextindex + '_1    more-options" style="display:none">+Add More Option</a></div></div></div></div><div class="template-dropdown" id="template-dropdown-' + nextindex + '-1"> </div><div class="option_section_' + nextindex + '_1 option_section multiepleoption" style="display:none">' +
            '<div class="option_' + nextindex + '_1  multieoption" id="option_' + nextindex + '_1_1"><div class="col-md-3"><div class="option-box"><input type="text"  placeholder="Write your options" name="options[' + nextindex_array + '][0][0]" id="options_ques_' + nextindex + '_1_1" class="options_ques  multieoption_input ques_option_first"></div>' +
            '</div><div class="col-md-2 failed-item  failed-item-' + nextindex + '-1" >' +
            '<input type="radio" name="failed_item[' + nextindex + '][1]" class="failed_item failed_item_input failed_item_' + nextindex + '_1_1" value="1" >Failed Item</div></div></div></div></div><div class="questionul">' +
            '<ul><li><a  class="add_guide" onclick="add_guide(\'' + nextindex + '\', \'1\')"><i class="fas fa-lightbulb"></i>Guide</a></li></ul><div class="guide_container" style="display:none" id="guide_container_' + nextindex + '_1">' +
            '<div class="guide-upload" id="guide-upload-' + nextindex + '-1">' +

            '<div class="files_'+nextindex +'_1   dropzone">'+
            '<p class="">Attachment from Device</p><label class=""><i class="fas fa-file" aria-hidden="true"></i>Document</label>'+
            '<label class=""><i class="fas fa-picture-o" aria-hidden="true"></i>Image</label><label class="">'+
            '<i class="fas fa-play-circle" aria-hidden="true"></i>Video</label><label class="">'+
            '<i class="fa fa-volume-up" aria-hidden="true"></i>Audio</label>'+
            '</div>'+



            '</div><div class="thumbnail-images thumbnail-images-' + nextindex + '-1 "></div><div class="text-center">OR</div><div class="guide-library">' +
            '<label class="guide_title">Attachment from Document Library <i class="fa fa-paperclip" aria-hidden="true" onclick="document_library(\'' + nextindex_array + '\',\'0\',\'0\')"></i>' +
            '</label><span class="library-name library-name-' + nextindex + '-1"> </span></div><div class="guide-note">' +
            '<input type="text" name="notes[' + nextindex_array + '][]" class="guide_text" placeholder="Write note here" id="guide_text_' + nextindex + '_1" maxlenght = "300"></div></div></div></div></div></div></div></div><div class="addquestion">' +
            '<button type="button" class="save_question" id="save_questions_' + nextindex + '" onclick="save_question(\'question_section_' + nextindex + '_1\')">' +
            '<i class="fas fa-save"></i>Save & Next</button>' +

            '<button class="addquestionbutton" id="addquestionbutton_' + nextindex + '" onclick="stop_submit()">Add Question <i class="fa fa-caret-down"></i></button>' +
            '<div class="addquestion-area" id="addquestion-area-' + nextindex + '">' +
            '<div class="addquestion-sub addquestion_text" id="addquestion_text_' + nextindex + '" onclick="add_question(\'text\', \'' + nextindex + '\')">Text</div>' +
            '<div class="addquestion-sub addquestion_dropdown" id="addquestion_dropdown_' + nextindex + '" onclick="add_question(\'dropdown\', \'' + nextindex + '\')">Dropdown</div>' +
            '<div class="addquestion-sub addquestion_date" id="addquestion_date_' + nextindex + '" onclick="add_question(\'date\', \'' + nextindex + '\')">Date</div>' +
            '<div class="addquestion-sub addquestion_number" id="addquestion_number_' + nextindex + '" onclick="add_question(\'number\', \'' + nextindex + '\')">Number</div>' +
            '<div class="addquestion-sub addquestion_yesno" id="addquestion_yesno_' + nextindex + '" onclick="add_question(\'yesno\', \'' + nextindex + '\')">Yes/No</div>' +
            '<div class="addquestion-sub addquestion_multiple" id="addquestion_multiple_' + nextindex + '" onclick="add_question(\'multiple\', \'' + nextindex + '\')">Multiple Choice</div>' +
            '<div class="addquestion-sub addquestion_checkbox" id="addquestion_checkbox_' + nextindex + '" onclick="add_question(\'checkbox\', \'' + nextindex + '\')">Checkbox(Multiselect)</div>' +
            '<div class="addquestion-sub addquestion_location" id="addquestion_location_' + nextindex + '" onclick="add_question(\'location\', \'' + nextindex + '\')">Google Map Locator</div>' +
            '<div class="addquestion-sub addquestion_signature" id="addquestion_signature_' + nextindex + '" onclick="add_question(\'signature\', \'' + nextindex + '\')">Signature</div></div></div></div>' +
            '</div></div><div id="doc_library_' + nextindex_array + '_0" class="modal doc_library">' +
            '<div class="modal-content"> <div class="modal-header"> <span class="doc_close" onclick="cancel_doc(\'' + nextindex_array + '\',\'0\')">&times;</span>' +
            '<div class="doc_library_title">Document Library</div></div><div class="modal-body">' +
            '<input type="text" class="search-doc search-doc-' + nextindex_array + '-0 form-control" placeholder="Search Document" onkeyup="search_document(\'' + nextindex_array + '\', \'0\' , this)">' +
            '<div style="height:auto;" class="col-md-12 doc_listing doc_listing_' + nextindex_array + '_0">' + doc_listing + '</div><div class="doclibrarybuttons">' +
            '<input type="button" name="Save" value="Save" class="btn btn-success document_save" onclick="save_doc(\'' + nextindex_array + '\',\'0\')">' +
            '<div class="upload-loader" style="display:none"></div><input type="button" name="Cancel" value="Cancel" class="btn btn-success" id="doc_cancel" onclick="cancel_doc(\'' + nextindex_array + '\',\'0\')">' +
            '</div></div></div></div>');
            appendfileinput(nextindex_array,"0");
            createdrozone(nextindex,"1");
            $("#save_question_"+nextindex).show();
            $("#addquestionbutton_" + nextindex).hide();
            $("#addquestion-area-" + nextindex).hide();
        
        $(":checkbox").uniform();
        $(":radio").uniform();
        if ($('#doc_listing').val() != '') {
            $('.doclibrarybuttons').show();
        } else {
            $('.doclibrarybuttons').hide();
        }

        if ($('#doc_listing').val() != '') {
            $('.search-doc-' + nextindex_array + '-0').show();
            $('.doc_listing_' + nextindex_array + '_0').addClass('col-md-12');
        } else {
            $('.search-doc-' + nextindex_array + '-0').hide();
            $('.doc_listing_' + nextindex_array + '_0').removeClass('col-md-12');
        }

        var section_text = $('#section_title_' + nextindex).text();
        $('#section_' + nextindex).val(section_text);
        selector = $($('#section_' + nextindex_array).attr('scroll'));
        $('html body').animate({
            scrollTop: (selector.offset().top)
        },
            1000
        );
    }

}

$(".save_question").hide();

// Add question in section
function add_question(type, section) {

    var type = type;
    var section_array = parseInt(section) - 1;

    // Finding total number of elements added
    var total_element = $(".question_section_" + section).length;


    $("#save_questions_"+section).show();
    $("#addquestionbutton_" + section).hide();
    $("#addquestion-area-" + section).hide();

    if (total_element == 0) {
        var questionid = 1;
        var nextindex = 1;
    } else {
        // last <div> with element class id
        var ques_class = ".question_section_" + section;
        var lastid = $(ques_class + ":last").attr("id");
        var split_id = lastid.split("_");
        var questionid = split_id[2];
        var nextindex = Number(split_id[3]) + 1;

    }
    var next_array = parseInt(nextindex) - 1;

    $("#save_questions_" + section).attr("onclick", 'save_question(\'question_section_' + section + '_' + nextindex + '\')');

    var max = 20;
    // Check total number elements
    if (total_element < max) {
        // Adding new div container after last occurance of element class
        if (total_element == 0) {
            var conatiner_id = "#question_container_" + section;
            $(conatiner_id).after("<div class='question_section_" + section + "' id='question_section_" + section + "_" + nextindex + "'  data-questionId='question_section_" + section + "_" + nextindex + "'></div>");
        } else {
            $(ques_class + ":last").after("<div class='question_section_" + section + "' id='question_section_" + section + "_" + nextindex + "' data-questionId='question_section_" + section + "_" + nextindex + "'></div>");
        }

        var doc_listing = '';
        if ($('#doc_listing').val() != '') {
            doc_listing = $('#doc_listing').val();
        } else {
            doc_listing = '<div class="no_doc"> No Document Found! </div>';
        }

        // $('.document').addClass('document_0_0');
        // Adding element to <div>
        $("#question_section_" + section + "_" + nextindex).append('<div class="row question-main-sec">' +
            '<div class="col-md-1 question-action"><span class="question-serial">' + nextindex + '</span>' +
            '<button class="action-edit" onclick="stop_submit()"><i class="fas fa-pencil-alt"></i>Edit</button>' +
            '<button class="action-edit delete_ques" id="delelte_quest_' + questionid + '_' + nextindex + '" onclick="delete_question(\'' + questionid + '\' , \'' + nextindex + '\')"><i class="fas fa-trash"></i>' +
            'Delete</button>' +

            '</div><div class="col-md-10 question-data"><div class="row"><div class="col-md-6"><div class="namerinterviewfield">' +
            '<input type="text" placeholder="Enter Question" class="question" value="" name="question[' + section_array + '][]" id="question_' + section + '_' + nextindex + '"></div></div></div><div class="variable-type">' +
            '<div class="row"><div class="col-md-12"><label>Type</label><div class="yesnobutton"><div class="row"><div class="col-md-3">' +
            '<select name="type[' + section_array + '][' + next_array + ']" id="question_type_' + section + '_' + nextindex + '" class="question_type" onchange="showoptions(\'' + section + '\',\'' + nextindex + '\', this)"><option value="1" selected>Text</option>' +
            '<option value="2">Dropdown</option><option value="3">Date</option>' +
            '<option value="4">Number</option><option value="5"> Yes/No</option><option value="6">Multiple Choice</option>' +
            '<option value="7">Checkbox(Multiselect)</option><option value="8">Google Map Locator</option><option value="9">Signature</option></select>' +
            '</div><div class="col-md-3"><div class="customcheckboxmain"><label class="customcheckbox">' +
            '<input type="checkbox" checked="checked"  class="required_input" name="required[' + section_array + '][' + next_array + ']" value="1">Required</label></div><a onclick="add_option(\'' + section + '\', \'' + nextindex + '\');" class="add_option_' + section + '_' + nextindex + '   more-options" style="display:none;">+Add More Option</a>' +
            '</div></div></div></div><div class="template-dropdown" id="template-dropdown-' + section + '-' + nextindex + '"> </div><div class="option_section_' + section + '_' + nextindex + ' option_section multiepleoption" style="display:none">' +
            '<div class="option_' + section + '_' + nextindex + '  multieoption" id="option_' + section + '_' + nextindex + '_1"><div class="col-md-3">' +
            '<div class="option-box"><input type="text"  placeholder="Write your options" name="options[' + section_array + '][' + next_array + '][0]" id="options_ques_' + section + '_' + nextindex + '_1" class="options_ques multieoption_input ques_option_first"></div></div>' +
            '</div></div></div></div><div class="questionul">' +
            '<ul><li><a class="add_guide" onclick="add_guide(\'' + section + '\', \'' + nextindex + '\')"><i class="fas fa-lightbulb"></i>Guide</a></li></ul><div class="guide_container" style="display:none" id="guide_container_' + section + '_' + nextindex + '">' +
            '<div class="guide-upload" id="guide-upload-' + section + '-' + nextindex + '">' +

                    '<div class="files_'+section + '_' + nextindex +' dropzone">'+
                    '<p class="">Attachment from Device</p><label class=""><i class="fas fa-file" aria-hidden="true"></i>Document</label>'+
                    '<label class=""><i class="fas fa-picture-o" aria-hidden="true"></i>Image</label><label class="">'+
                    '<i class="fas fa-play-circle" aria-hidden="true"></i>Video</label><label class="">'+
                    '<i class="fa fa-volume-up" aria-hidden="true"></i>Audio</label>'+
                    '</div>'+


            '</div><div class="thumbnail-images thumbnail-images-' + section + '-' + nextindex + ' "></div><div class="text-center">OR</div><div class="guide-library"><label class="guide_title">Attachment from Document Library ' +
            '<i class="fa fa-paperclip" aria-hidden="true" onclick="document_library(\'' + section_array + '\', \'' + next_array + '\', \'0\')"></i></label><span class="library-name library-name-' + section + '-' + nextindex + '"> </span>' +
            '</div><div class="guide-note"><input type="text" name="notes[' + section_array + '][]" class="guide_text" placeholder="Write note here" id="guide_text_' + section + '_' + nextindex + '" maxlenght = "300">' +
            '</div></div></div></div></div></div> <div id="doc_library_' + section_array + '_' + next_array + '" class="modal doc_library">' +
            '<div class="modal-content"> <div class="modal-header"> <span class="doc_close" onclick="cancel_doc(\'' + section_array + '\', \'' + next_array + '\')">&times;</span>' +
            '<div class="doc_library_title">Document Library</div></div><div class="modal-body">' +
            '<input type="text" class="search-doc search-doc-' + section_array + '-' + next_array + ' form-control" placeholder="Search Document" onkeyup="search_document(\'' + section_array + '\', \'' + next_array + '\', this)">' +
            '<div style="height:auto;" class="col-md-12 doc_listing doc_listing_' + section_array + '_' + next_array + '">' + doc_listing + '</div><div class="doclibrarybuttons">' +
            '<input type="button" name="Save" value="Save" class="btn btn-success document_save" onclick="save_doc(\'' + section_array + '\', \'' + next_array + '\')">' +
            '<div class="upload-loader" style="display:none"></div><input type="button" name="Cancel" value="Cancel" class="btn btn-success" id="doc_cancel" onclick="cancel_doc(\'' + section_array + '\', \'' + next_array + '\')">' +
            '</div></div></div></div>');
        var option_id = "#question_type_" + section + "_" + nextindex;
        appendfileinput(section_array,next_array);
            //1-2
        createdrozone(section,nextindex);
        if ($('#doc_listing').val() != '') {
            $('.search-doc-' + section_array + '-' + next_array).show();
            $('.doc_listing_' + section_array + '_' + next_array).addClass('col-md-12');
        } else {
            $('.search-doc-' + section_array + '-' + next_array).hide();
            $('.doc_listing_' + section_array + '_' + next_array).removeClass('col-md-12');
        }

        if (type == 'text') {
            $(option_id).val('1').attr("selected", "selected");
        } else if (type == 'dropdown') {
            $(option_id).val('2').attr("selected", "selected");
            // console.log(section);
            // console.log(nextindex);
            $('.pre_loader').show();

            getdropdownoptions(section,nextindex);       

            $("#options_ques_" + section + "_" + nextindex + "_1").hide();
            $("#drop_opt_" + section + "_" + nextindex).show();
        } else if (type == 'date') {
            $(option_id).val('3').attr("selected", "selected");
        } else if (type == 'number') {
            $(option_id).val('4').attr("selected", "selected");
        } else if (type == 'yesno') {
            $(option_id).val('5').attr("selected", "selected");
        } else if (type == 'multiple') {
            $('.add_option_' + section + '_' + nextindex).show();
            $('.option_section_' + section + '_' + nextindex).show();
            $(option_id).val('6').attr("selected", "selected");
        } else if (type == 'checkbox') {
            $('.add_option_' + section + '_' + nextindex).show();
            $('.option_section_' + section + '_' + nextindex).show();
            $(option_id).val('7').attr("selected", "selected");
        } else if (type == 'location') {
            $(option_id).val('8').attr("selected", "selected");
        } else if (type == 'signature') {
            $(option_id).val('9').attr("selected", "selected");
        } else {
            $(option_id).val('1').attr("selected", "selected");
        }
    }
    if ($('#doc_listing').val() != '') {
        $('.doclibrarybuttons').show();
    } else {
        $('.doclibrarybuttons').hide();
    }

    reorderdQuestion(section,nextindex);



    $(":checkbox").uniform();
    $(":radio").uniform();
}


function getdropdownoptions(section,nextindex){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: APP_URL + '/admin/dropdown_options',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            'section': section,
            'question': nextindex
        },
        dataType: "json",
        success: function (response) {
            $('#template-dropdown-' + section + '-' + nextindex).show().html(response.dropdown);
            $(":checkbox").uniform();
            $(":radio").uniform();
            // console.log("#drop_opt_" + section + "_" + nextindex);
        },
        complete: function () {
            $('.pre_loader').hide();
        },
        error: function (error) {
            errorHandler(error);
        }
    });
}


function reorderdQuestion(section,nextindex){
    var section=section;
    var nextindex=nextindex;
    $(".question_container").sortable({
        placeholder: 'myPlaceholder',
        tolerance: 'pointer',
        revert: 'invalid',
        forceHelperSize: true,
        cursor: "move",
        update: function (event, ui) {
            //     var lastid = $(ques_class + ":last").attr("id");
            //     var split_id = lastid.split("_");
            //     var questionid = split_id[2];
            //     var nextindex = Number(split_id[3]) + 1;
            // var next_array = parseInt(nextindex) - 1;  
            var nextindex = 1;
            var containerid = $(this).attr("id");
            var section_id = containerid.split("_");
            // console.log(section_id);
            var className = "question_section_" + section_id[2] + "_";
            var section_id = section_id[2];
            var index = 1;
            // console.log(section);
            // console.log(section_id);
            // console.log(nextindex);

            for (i = 0; i < $(this).children().length; i++) {
                // console.log($(this).children()[i]);
                $($(this).children()[i]).find('.question-serial').text(index);
                $($(this).children()[i]).find('.delete_ques').attr("id", 'delelte_quest_' + section + '_' + nextindex);
                $($(this).children()[i]).find('.delete_ques').attr("onclick", 'delete_question( \'' + section_id + '\' , \'' + nextindex + '\')');

                $($(this).children()[i]).find('.question').attr("id", 'question_' + section + '_' + nextindex);


                $($(this).children()[i]).find('.question_type').attr("name", 'type[' + (section_id - 1) + '][' + i + ']');
                $($(this).children()[i]).find('.question_type').attr("id", 'question_type_' + section + '_' + nextindex);
                $($(this).children()[i]).find('.question_type').attr("onchange", 'showoptions(\'' + section_id + '\',\'' + nextindex + '\', this)');


                $($(this).children()[i]).find('.required_input').attr("name", 'required[' + (section_id - 1) + '][' + i + ']');

                // if question type is dropdown and Multiselect and multicheckbox
                $($(this).children()[i]).find('.more-options').attr("onclick", 'add_option(\'' + section_id + '\',\'' + nextindex + '\')');

                $($(this).children()[i]).find('.more-options').attr("class", 'add_option_' + section + '_' + nextindex + '   more-options');

                // if question type is dropdown
                $($(this).children()[i]).find('.template-dropdown').attr("id", 'template-dropdown-' + section + '-' + nextindex);
                // drop down div
                $($(this).children()[i]).find('.drop_opt').attr("id", 'drop_opt_' + section + '_' + nextindex);
                // type-order select 
                $($(this).children()[i]).find('.type-order').attr("name", 'type_order[' + (section_id - 1) + '][' + i + ']');
                $($(this).children()[i]).find('.type-order').attr("class", 'type-order type-order-' + section_id + '-' + nextindex + '');
                $($(this).children()[i]).find('.type-order').attr("onchange", 'change_type(\'' + section_id + '\',\'' + nextindex + '\', this)');


                $($(this).children()[i]).find('.more_type').attr("class", 'col-md-3 more_type more_type_' + section + '_' + nextindex + '');

                $($(this).children()[i]).find('.dropdown_type').attr("name", 'dropdown_type[' + (section_id - 1) + '][' + i + ']');

                // add more type
                $($(this).children()[i]).find('.add_type').attr("class", 'add_type add_type_' + section + '_' + nextindex + '');

                $($(this).children()[i]).find('.add_type_button').attr("onclick", 'add_type(\'' + section_id + '\', \'' + nextindex + '\')');


                // $($(this).children()[i]).find('.add_type').attr("class", 'add_type  add_type_' + section + '_' + nextindex + '');


                $($(this).children()[i]).find('.dropdown-options').attr("class", 'col-md-3 dropdown-options dropdown-options-' + section + '-' + nextindex + '');

                $($(this).children()[i]).find('.add_option_button').attr("onclick", 'add_option(\'' + section_id + '\',\'' + nextindex + '\')');

                $($(this).children()[i]).find('.audit-option').attr("class", 'audit-option  audit-option-' + section + '-' + nextindex + '');

                $($(this).children()[i]).find('.compliant').attr("name", 'option[' + (section_id - 1) + '][' + i + '][0]');

                $($(this).children()[i]).find('.non_compliant').attr("name", 'option[' + (section_id - 1) + '][' + i + '][1]');

                $($(this).children()[i]).find('.variation').attr("name", 'option[' + (section_id - 1) + '][' + i + '][2]');

                $($(this).children()[i]).find('.not_determined').attr("name", 'option[' + (section_id - 1) + '][' + i + '][3]');

                $($(this).children()[i]).find('.end_user').attr("name", 'option[' + (section_id - 1) + '][' + i + '][4]');

                $($(this).children()[i]).find('.not_applicable').attr("name", 'option[' + (section_id - 1) + '][' + i + '][5]');

                var drop_option = 1;

                for (var inner = 0; inner < $($(this).children()[i]).find('.drop_opt_sec').children().length; inner++) {

                    console.log($($(this).children()[i]).find('.failed_item_input'));
                    $($(this).children()[i]).find('.drop_option').attr("class", 'drop_option col-md-12  drop_option_' + section + '_' + nextindex + '');
                    $($(this).children()[i]).find('.drop_option').attr("id", 'drop_option_' + section + '_' + nextindex + '_' + drop_option);

                    $($($(this).children()[i]).find('.options_ques_input')[inner]).attr("name", 'new_options[' + (section_id - 1) + '][' + (nextindex - 1) + '][' + (inner) + ']');
                    $($($(this).children()[i]).find('.options_ques_input')[inner]).attr("id", 'options_ques_' + section + '_' + nextindex + '_1');

                    $($(this).children()[i]).find('.failed_item_input').attr("name", 'failed_item[' + (section - 1) + '][' + inner + ']');
                    $($(this).children()[i]).find('.failed_item_input').attr("class", 'failed_item_input  failed-item  failed_item_' + section + '_' + nextindex + '_' + inner);

                    $($($(this).children()[i]).find('.color-options')[inner]).attr("class", 'color-options  color_code_' + section + '_' + nextindex + '_' + nextindex);

                    $($($(this).children()[i]).find('.color-options')[inner]).attr("name", 'color_code[' + (section_id - 1) + '][' + (i) + '][' + (inner) + ']');

                    drop_option++;
                }
                // console.log($($(this).children()[i]).find('.multiepleoption').children());
                // console.log($($(this).children()[i]).find('.multiepleoption').children().length);

                var options = 1;
                for (var inner = 0; inner < $($(this).children()[i]).find('.multiepleoption').children().length; inner++) {

                    // console.log($($($(this).children()[i]).find('.multieoption')[inner]));
                    // console.log($($(this).children()[i]).find('.remove_option'));
                    // console.log($($($(this).children()[i]).find('.remove_option')[inner]));
                    $($($(this).children()[i]).find('.multieoption')[inner]).attr("class", 'option  multieoption    option_' + section + '_' + nextindex + '');
                    $($($(this).children()[i]).find('.multieoption')[inner]).attr("id", 'option_' + section + '_' + nextindex + '_' + options);

                    $($($(this).children()[i]).find('.multieoption_input')[inner]).attr("name", 'options[' + (section_id - 1) + '][' + (i) + '][' + (inner) + ']');

                    $($($(this).children()[i]).find('.multieoption_input')[inner]).attr("id", 'options_ques_' + section + '_' + nextindex + '_' + options);
                    if (options > 1) {
                        $($($(this).children()[i]).find('.remove_option')[inner - 1]).attr("onclick", 'remove_options(\'' + section + '\',\'' + (nextindex) + '\',\'' + options + '\')');
                        $($($(this).children()[i]).find('.remove_option')[inner - 1]).attr("id", 'remove_option_' + options);

                    }

                    options++;

                }



                $($(this).children()[i]).find('.files_ids').attr("name", 'files[' + (section_id - 1) + '][' + (i) + '][]');

                $($(this).children()[i]).find('.files_ids').attr("id", 'files_'+section_id +'_'+nextindex );

                $($(this).children()[i]).find('.drop_opt_sec').attr("class", 'option_section drop_opt_sec  drop_opt_sec_' + section + '_' + nextindex + '');

                $($(this).children()[i]).find('.drop_opt_sec').attr("id", 'drop_opt_sec_' + section + '_' + nextindex + '_1');

                $($(this).children()[i]).find('.failed-item').attr("class", 'col-md-2 failed-item   failed-item-' + section + '-' + nextindex + '');

                $($(this).children()[i]).find('.color-code').attr("class", 'col-md-2 color-code  color-code-' + section + '-' + nextindex + '');

                $($(this).children()[i]).find('.color-code').attr("id", 'color-code-' + section + '-' + nextindex + '');

                $($(this).children()[i]).find('.add_guide').attr("onclick", 'add_guide(\'' + section + '\', \'' + nextindex + '\')');

                $($(this).children()[i]).find('.guide_container').attr("id", 'guide_container_' + section + '_' + nextindex);

                $($(this).children()[i]).find('.guide-upload').attr("id", 'guide-upload-' + section + '-' + nextindex);

                $($(this).children()[i]).find('.guide_title').attr("for", 'guide-document-' + section + '-' + nextindex);

                $($(this).children()[i]).find('.document').attr("onclick", 'performClick(\'guide-document-' + section_id + '-' + nextindex + '\')');

                $($(this).children()[i]).find('.document').attr("class", 'doc_name  document documentdoc_name_' + section_id + '_' + nextindex);

                $($(this).children()[i]).find('.document_input').attr("onchange", 'showdoc(\'document\', \'' + section_id + '\', \'' + nextindex + '\')');
                $($(this).children()[i]).find('.document_input').attr("id", 'guide-document-' + section_id + '-' + nextindex);
                $($(this).children()[i]).find('.document_input').attr("name", 'document[' + (section_id - 1) + '][' + i + '][]');


                $($(this).children()[i]).find('.image').attr("onclick", 'performClick(\'guide-image-' + section_id + '-' + nextindex + '\')');
                $($(this).children()[i]).find('.image_input').attr("onchange", 'showdoc(\'image\', \'' + section_id + '\', \'' + nextindex + '\')');
                $($(this).children()[i]).find('.image_input').attr("id", 'guide-image-' + section_id + '-' + nextindex);
                $($(this).children()[i]).find('.image_input').attr("name", 'image[' + (section_id - 1) + '][' + i + '][]');


                $($(this).children()[i]).find('.audio').attr("onclick", 'performClick(\'guide-audio-' + section_id + '-' + nextindex + '\')');
                $($(this).children()[i]).find('.audio_input').attr("onchange", 'showdoc(\'audio\', \'' + section_id + '\', \'' + nextindex + '\')');
                $($(this).children()[i]).find('.audio_input').attr("id", 'guide-audio-' + section_id + '-' + nextindex);
                $($(this).children()[i]).find('.audio_input').attr("name", 'audio[' + (section_id - 1) + '][' + i + '][]');


                $($(this).children()[i]).find('.video').attr("onclick", 'performClick(\'guide-video-' + section_id + '-' + nextindex + '\')');
                $($(this).children()[i]).find('.video_input').attr("onchange", 'showdoc(\'video\', \'' + section_id + '\', \'' + nextindex + '\')');
                $($(this).children()[i]).find('.video_input').attr("id", 'guide-video-' + section_id + '-' + nextindex);
                $($(this).children()[i]).find('.video_input').attr("name", 'video[' + (section_id - 1) + '][' + i + '][]');

                $($(this).children()[i]).find('.thumbnail-images').attr("class", 'thumbnail-images  thumbnail-images-' + section + '-' + nextindex);


                $($(this).children()[i]).find('.library-name').attr("class", 'library-name  library-name-' + section + '-' + nextindex);

                $($(this).children()[i]).find('.fa fa-paperclip').attr("onclick", 'document_library(\'' + section + '\', \'' + nextindex + '\' \',0\')');

                $($(this).children()[i]).find('.guide_text').attr("name", 'note[' + section_id + '][' + i + ']');

                $($(this).children()[i]).find('.guide_text').attr("id", 'guide_text_' + section + '_' + nextindex);


                $("#save_questions_" + section).attr("onclick", 'save_question(\'question_section_' + section + '_' + nextindex + '\')');

                $($(this).children()[i]).attr("id", className + index);
                index++;
                nextindex++;
            }

        }
    });
}

// delete question in section
function delete_question(section, ques) {
    var ques = ques;
    var section = section;
    // $("#section_container_"+section).find('.question_container').children();
    // console.log($("#section_container_"+section).find('.question_container').children());
    // console.log($("#section_container_"+section).find('.question_container').children().length);
    // console.log($("#save_questions_"+section));
    // console.log($("#save_questions_"+section).attr("onclick"));
    var save_questions=$("#save_questions_"+section).attr("onclick");
    save_questions=save_questions.split("_");
    console.log("#question_section_" + save_questions[3] + "_" + save_questions[4][0]);
    if("question_section_"+ section + "_" + ques == "question_section_" + save_questions[3] + "_" + save_questions[4][0]){
        $("#save_questions_"+section).hide();
        $("#addquestionbutton_" + section).show();
        // $("#addquestion-area-" + section).show();
    }else{

    }

    $("#question_section_" + section + "_" + ques).remove();
}




// add options in dropdown question
function add_drop_option(section, question) {

    var section = section;
    var section_array = parseInt(section) - 1;
    var ques_array = parseInt(question) - 1;

    // Finding total number of elements added
    var total_element = $(".drop_option_" + section + "_" + question).length;
    // last <div> with element class id
    if ($('div').hasClass("drop_option_" + section + "_" + question)) {
        var option_class = ".drop_option_" + section + "_" + question;
        var lastid = $(option_class + ":last").attr("id");
        var split_id = lastid.split("_");
        var index = split_id[4];
        var nextindex = Number(split_id[4]) + 1;
    } else {
        var option_class = ".dropdown_options_" + section + "_" + question;
        var index = 0;
        var nextindex = 1;
    }

    // console.log(option_class);


    // console.log($('.color-options').html());

    var color_opt = $('.color-options').html();

    var max = 20;
    // Check total number elements
    if (total_element < max) {
        // Adding new div container after last occurance of element class
        $(option_class + ":last").after("<div class='drop_option_" + section + "_" + question + " col-md-12' id='drop_option_" + section + "_" + question + "_" + nextindex + "'></div>");

        // Adding element to <div>
        $("#drop_option_" + section + "_" + question + "_" + nextindex).append('<div class="row"><div class="col-md-3"><div class="option-box"><a id="remove_option_' + nextindex + '" class="remove_option" onclick="remove_options(\'' + section + '\',\'' + question + '\', \'' + nextindex + '\' )"><span class="glyphicon glyphicon-remove pull-right" alt="Remove"></span></a>&nbsp;<input type="text" placeholder="Write your options" name="new_options[' + section_array + '][' + ques_array + '][' + index + ']" class="options_ques options_ques_input ques_option"></div>' +
            '</div><div class="col-md-2 failed-item  failed-item-' + section + '-' + question + '" ><input type="radio" name="failed_item[' + section_array + '][' + ques_array + ']" class="failed_item failed_item_input failed_item_' + section + '_' + question + '_' + nextindex + '" value="' + parseInt(nextindex - 1) + '">Failed Item</div><div class="col-md-2 color-code color-code-' + section + '-' + question + '" id="color-code-' + section + '-' + question + '" >' +
            '<select name="color_code[' + section_array + '][' + ques_array + '][' + index + ']" class="color_code_' + section + '_' + question + '_' + nextindex + ' color-options">' + color_opt + '</select></div></div>');

    }
    $('.failed-item-' + section + "-" + question).show();
    $('.color-code-' + section + "-" + question).show();

    $(":checkbox").uniform();
    $(":radio").uniform();
}

// remove options
function remove_options(section, ques, option) {
    var ques = ques;
    var section = section;
    if ($('#question_type_' + section + "_" + ques).val() == 2 && $('.type-order-' + section + "-" + ques + ' option:selected').val() == '') {
        $("#drop_option_" + section + "_" + ques + "_" + option).remove();
    } else if ($('#question_type_' + section + "_" + ques).val() == 2 && $('.type-order-' + section + "-" + ques + ' option:selected').val() != '') {
        $("#drop_option_old_" + section + "_" + ques + "_" + option).remove();
    } else {
        $("#option_" + section + "_" + ques + "_" + option).remove();
    }

}

// show options in multiple choice question
function showoptions(section, question, selectObject) {
    var value = selectObject.value;
    if (value == '6' || value == '7') {
        $('.add_option_' + section + '_' + question).show();
        $('.option_section_' + section + '_' + question).show();
        $('.drop_opt_sec_' + section + '_' + question).hide();
        $("#options_ques_" + section + "_" + question + "_1").show();
        $('.failed-item-' + section + "-" + question).hide();
        $('.color-code-' + section + "-" + question).hide();
    } else {
        // $("#options_ques_" + section + "_" + question + "_1").hide();
        $('.add_option_' + section + '_' + question).hide();
        $('.option_section_' + section + '_' + question).hide();
        $('.failed-item-' + section + "-" + question).hide();
        $('.color-code-' + section + "-" + question).hide();
    }

    if (value == '2') {
        var next_section = parseInt(section) - 1;
        var next_ques = parseInt(question) - 1;

        $('.pre_loader').show();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: APP_URL + '/admin/dropdown_options',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                'section': section,
                'question': question
            },
            dataType: "json",
            success: function (response) {
                $('#template-dropdown-' + section + '-' + question).show().html(response.dropdown);
                $(":checkbox").uniform();
                $(":radio").uniform();
                // console.log("#drop_opt_" + section + "_" + question);
                $("#drop_opt_" + section + "_" + question).show();
                // $("#options_ques_" + section + "_" + question + "_1").hide();
                $('.color-code-' + section + "-" + question).show();
                $('.option_section_' + section + '_' + question).hide();
                // $("#drop_opt_" + section + "_" + question).show().append();

                if ($('.type-order-' + section + "-" + question).val() == 'audit') {
                    $('.failed-item-' + section + "-" + question).hide();
                    $('.drop_opt_sec_' + section + '_' + question).hide();
                } else {
                    $('.failed-item-' + section + "-" + question).show();
                    $('.drop_opt_sec_' + section + '_' + question).show();
                }
            },
            complete: function () {
                $('.pre_loader').hide();
            },
            error: function (error) {
                errorHandler(error);
            }
        });

    } else {
        $("#drop_opt_" + section + "_" + question).hide();
        $('.color-code' + section + "-" + question).hide();
        $('.drop_opt_sec_' + section + '_' + question).hide();
        $('.failed-item-' + section + "-" + question).hide();
    }
}

//add guide
function add_guide(section, question) {
    $('#guide_container_' + section + '_' + question).toggle();
}

//show document name in guide section
function showdoc(type, section, question) {
    // console.log($('#guide-input-' + section + '-' + question).val());
    console.log(document.getElementById('guide-' + type + '-' + section + '-' + question).files);
    // var doc_name = $('#guide-'+ type +'-' + section + '-' + question).val().replace(/C:\\fakepath\\/i, '');
    var file = document.getElementById('guide-' + type + '-' + section + '-' + question).files;
    for (i = 0; i < file.length; i++) {

        remove_file = "<span class='glyphicon glyphicon-remove pull-right' alt='Remove' onclick='remove_doc(\"" + type + "\", \"" + section + "\",\"" + question + "\",\"" + i + "\")'></span>";
        imageurl = URL.createObjectURL(file[i]);
        if (type == "image") {

            doc_name = "<span class='fileremoved  " + type + 'remove_file_' + section + '_' + question + '_' + i + " '><img class='default-image'  src=" + imageurl + ">  " + remove_file + "</span>";
        } else if (type == "video") {
            doc_name = "<span class='fileremoved  " + type + 'remove_file_' + section + '_' + question + '_' + i + "   '><video class='default-image' controls><source src=" + imageurl + " ></video>  " + remove_file + "</span>";

        } else if (type == "document") {
            imageurl = "";
            doc_name = "<span class='fileremoved  " + type + 'remove_file_' + section + '_' + question + '_' + i + " '><img class='default-image'  src=" + APP_URL + '/images/icon_document.png' + ">  " + remove_file + "</span>";

        } else {
            doc_name = "<span class='fileremoved  " + type + 'remove_file_' + section + '_' + question + '_' + i + " '><audio class='default-image' controls>  <source src=" + imageurl + " ></audio> " + remove_file + "</span>";
        }
        // $('.'+type+'doc_name_' + section + '_' + question).append(doc_name);
        $('.thumbnail-images-' + section + '-' + question).append(doc_name);


    }

}

var remove_image = [];
// remove chossen file for document
function remove_doc(type, section, question, id) {
    // console.log($('.'+type+'doc_name_' + section + '_' + question+'_'+id));
    // console.log($($('.'+type+'doc_name_' + section + '_' + question+'_'+id).children().children()[1]).attr('id'));
    // console.log($('.'+type+'remove_file_' + section + '_' + question+'_'+id)[0].children[1].id);
    console.log(type);
    element = 'guide-' + String(type) + '-' + String(section) + '-' + String(question);
    console.log(element);
    arr = document.getElementById('guide-' + String(type) + '-' + String(section) + '-' + String(question)).files;
    arr = [...arr].splice(id, 1);
    console.log(arr);
    // console.log($('.'+type+'doc_name_' + section + '_' + question+'_'+id).attr('id'));

    remove_image.push($('.' + type + 'doc_name_' + section + '_' + question + '_' + id).attr('id'));
    $("#remove_image").val([remove_image]);

    $('.' + type + 'remove_file_' + section + '_' + question + '_' + id).remove();

    $('.' + type + 'doc_name_' + section + '_' + question + '_' + id).remove();
    // $('#guide-' +type+ '-' + section + '-' + question).removeAttr('value');
    $('#doc-name-' + section + '-' + question + '_' + id).removeAttr('value');
    $('#doc-type-' + section + '-' + question + '_' + id).removeAttr('value');
    // $('.doc_name_' + section + '_' + question).text('');
    // $('.remove_file_'+ section + '_' + question).hide('');



}

/** edit Template JS */

// delete scope text in edit template
function delete_scope(text, id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var id = id;
    $.ajax({
        url: '/admin/delete_scope',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (data) {
            var data = data.response;
            data = $.trim(data);
            if (data == 'Deleted') {
                $("#scope_" + text).remove();
            }
        }
    });
}

// delete section in edit template
function delete_sectionId(section, id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var id = id;
    $.ajax({
        url: APP_URL + '/admin/delete_section',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (data) {
            var data = data.response;
            data = $.trim(data);
            if (data == 'Deleted') {
                $("#section_container_" + section).remove();
            }
        }
    });
}


// delete question in edit template
function delete_quesId(section, question, id) {
    $('.pre_loader').show();
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var id = id;
    $.ajax({
        url: APP_URL + '/admin/delete_question',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (response) {
            var resp = response.message;
            alertSuccess(resp);
            $("#question_section_" + section + "_" + question).remove();
        },
        complete: function () {
            $('.pre_loader').hide();
        },
        error: function (error) {
            errorHandler(error);
        }

    });
}

// open popup of share template
function share_template(id) {
    // $('.pre_loader').show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: APP_URL + '/admin/share_template',
        type: 'GET',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (data) {
            if (data != '') {
                $('#share_temp').show();
                var prefix = data.template_prefix.split('').slice(0, 2).join('');
                $('.id-circle').text(prefix);
                $('#temp_id').text(data.template_prefix);
                $('#shared_temp_id').val(data.id);
                var share_number = data.share_templates.length;
                var user_id = $('#auth_user').val();
                let i;
                if (share_number > 0) {
                    $('#share_users').html('');
                    for (i = 0; i < share_number; i++) {
                        $('#share_users').append('<div class="user_' + data.share_templates[i].id + '">' + data.share_templates[i].user.vc_fname + ' ' + data.share_templates[i].user.vc_mname + ' ' + data.share_templates[i].user.vc_lname + (user_id != data.share_templates[i].user_id ? '<i class="fa fa-close" style="font-size:20px;float:right;" onclick="unshare_user(\'' + data.share_templates[i].id + '\');"></i>' : '') + '</div><hr>');
                    }
                } else {
                    $('#share_users').html('');
                    $('#share_users').append('<div class="text-center">Not Shared Yet !</div><hr>');
                }
            } else {
                $('#share_temp').hide();
            }
            $('.pre_loader').hide();
        }
    });
}

//search name or email in share template
function myFunction() {
    // document.getElementById("shareDropdown").classList.toggle("show");
    $('#shareDropdown').toggle();
}

function filterFunction() {
    document.getElementById("shareDropdown").style.display = "block";
    var input, filter, ul, li, a, i;
    input = document.getElementById("shareInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("shareDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
            var id = $(a[i]).attr("target-id");
            $("#shareInput").attr("data-id", id);
            $('.share_error').hide().text('');
        } else {
            a[i].style.display = "none";
            $("#shareInput").attr("data-id", '');
            $('.share_error').show().text('User or group doesnot exist');
        }
    }
}

function input_text(data) {
    var id = $(data).attr("target-id");
    var form = $(data).attr("target-form");
    $('#shareInput').val(data.text);
    $("#shareInput").attr("data-id", id);
    $("#shareInput").attr("data-form", form);
    $('#shareDropdown').hide();
    $('.share_error').hide().text('');
}


// share template with user 
function share_template_with() {
    $('#share_temp').show();
    // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var user_id = $('#shareInput').attr("data-id");
    var temp_id = $('#shared_temp_id').val();
    var form = $('#shareInput').attr("data-form");
    if (user_id == '') {
        $('.share_error').show().text('Please enter valid name or group.');
        $('#shareDropdown').hide();
    } else {
        $('.modal_loader').show();
        $('.add_people').hide();
        $('.share_error').hide().text('');
        $.ajax({
            url: APP_URL + '/admin/share_template_with',
            type: 'POST',
            data: {
                // _token: CSRF_TOKEN,
                user_id: user_id,
                temp_id: temp_id,
                form: form
            },
            success: function (response) {
                var resp = response.message;
                alertSuccess(resp);
                $('#share_temp').hide();
                $('#shareInput').val('');
                $("#shareInput").attr("data-id", '');
                $('#shareDropdown').hide();
                $('#shareDropdown a').show();
                $('.share_error').hide().text('');
            },
            complete: function () {
                $('.modal_loader').hide();
                $('.add_people').show();
            },
            error: function (error) {
                errorHandler(error);
            }
        });
    }
}

//unshare the template from user
function unshare_user(id) {
    $('<div></div>').appendTo('body')
    bootbox.confirm({
        message: "Are you sure you want to remove user?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            $('.pre_loader').show();
            if (result) {
                // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: APP_URL + '/admin/unshare_template',
                    type: 'POST',
                    data: {
                        // _token: CSRF_TOKEN,
                        id: id
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        console.log('.user_' + id);
                        var resp = response.message;
                        $('.user_' + id).next('hr').remove();
                        $('.user_' + id).remove();
                        alertSuccess(resp);
                        // location.reload(true);
                    },
                    complete: function () {
                        $('.pre_loader').hide();
                        $('.modal_loader').hide();
                    },
                    error: function (error) {
                        errorHandler(error);
                    }
                });
            } else {
                $('.pre_loader').hide();
                $('.modal_loader').hide();
            }
        }
    });
}

// show document library modal 
function document_library(sec, ques, question_id) {
    $('.document').parent().parent().addClass('documentnew');
    $('.documentnew').css({
        "position": "absolute",
        "height": "100%",
        "width": "100%"
    });
    if ($('#has_permission').val() == 1) {
        var total_doc = $('.document').length;
        $('#doc_library_' + sec + '_' + ques).show();
        var next_element = $('.doc_listing_' + sec + '_' + ques).find('.document').attr('name', 'doc_library_id[' + sec + '][' + ques + '][]');
    } else {
        alertError("You doesn't have permission to access Document Library. Please contact admin!");
    }
}

// on click cross and cancel button close document library modal and reset the values
function cancel_doc(sec, ques, edit) {
    var next_sec = parseInt(sec) + 1;
    var next_ques = parseInt(ques) + 1;
    if (edit != '' && edit == 1) {
        $('#doc_library_' + sec + '_' + ques).hide();
    } else {
        $('#doc_library_' + sec + '_' + ques).hide();
        $('.doc_listing_' + sec + '_' + ques).find('.document').attr('checked', false);
        $('.doc_listing_' + sec + '_' + ques).find('.document').parent().removeClass('checked');
        $('.doc_library_error').remove();
        $('.remove_doc_' + sec + '_' + ques).hide();
        $('.library-name-' + next_sec + '-' + next_ques).text('');
    }
}

// on click save button close document library modal
function save_doc(sec, ques) {
    var next_sec = parseInt(sec) + 1;
    var next_ques = parseInt(ques) + 1;
    if ($('.doc_listing_' + sec + '_' + ques).find('.document:checked').length <= 0) {
        $('.doc_library_error').remove();
        $(".doclibrarybuttons").before("<div class='error doc_library_error'> Please select document. </div>");
        return false;
    } else {
        $('#doc_library_' + sec + '_' + ques).hide();
        $('.doc_library_error').remove();
        var total = $('.doc_listing_' + sec + '_' + ques).find('.document:checked').length;
        var all_doc = [];
        $('.doc_listing_' + sec + '_' + ques).find('.document:checked').each(function () {
            all_doc.push($(this).attr('target'));
        });
        var name_doc = all_doc.toString();
        $('.library-name-' + next_sec + '-' + next_ques).text(name_doc);
        // console.log('.library-name-'+next_sec+'-'+next_ques);
        return true;
    }

}

// add options in multiple chice question
function add_option(section, question) {

    var section = section;
    var section_array = parseInt(section) - 1;
    var ques_array = parseInt(question) - 1;

    // Finding total number of elements added
    if ($('#question_type_' + section + "_" + question).val() == 2 && $('.type-order-' + section + "-" + question).val() == '') {
        var total_element = $(".drop_option_" + section + "_" + question).length;
        // last <div> with element class id
        var option_class = ".drop_option_" + section + "_" + question;
        var lastid = $(option_class + ":last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[4]) + 1;
    } else if ($('#question_type_' + section + "_" + question).val() == 2 && $('.type-order-' + section + "-" + question).val() != '') {
        var total_element = $(".drop_option_old_" + section + "_" + question).length;
        // last <div> with element class id
        var option_class = ".drop_option_old_" + section + "_" + question;
        var lastid = $(option_class + ":last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[5]) + 1;
    } else {
        var total_element = $(".option_" + section + "_" + question).length;
        // last <div> with element class id
        var option_class = ".option_" + section + "_" + question;
        var lastid = $(option_class + ":last").attr("id");
        var split_id = lastid.split("_");
        var nextindex = Number(split_id[3]) + 1;
    }

    // console.log(option_class);


    // console.log($('.color-options').html());

    var color_opt = $('.color-options').html();

    var max = 20;

    // Check total number elements
    if (total_element < max) {
        // Adding new div container after last occurance of element class
        if ($('#question_type_' + section + "_" + question + ' option:selected').val() == 2 && $('.type-order-' + section + "-" + question + ' option:selected').val() == '') {
            $(option_class + ":last").after("<div class='drop_option_" + section + "_" + question + " col-md-12  drop_option' id='drop_option_" + section + "_" + question + "_" + nextindex + "'></div>");

            // Adding element to <div>
            $("#drop_option_" + section + "_" + question + "_" + nextindex).append('<div class="row"><div class="col-md-3"><div class="option-box"><a id="remove_option_' + nextindex + '" class="remove_option" onclick="remove_options(\'' + section + '\',\'' + question + '\', \'' + nextindex + '\' )"><span class="glyphicon glyphicon-remove pull-right" alt="Remove"></span></a>&nbsp;<input type="text" placeholder="Write your options" name="new_options[' + section_array + '][' + ques_array + '][' + split_id[4] + ']" class="options_ques options_ques_input ques_option"></div>' +
                '</div><div class="col-md-2 failed-item  failed-item-' + section + '-' + question + '" ><input type="radio" name="failed_item[' + section_array + '][' + ques_array + ']" class="failed_item failed_item_input failed_item_' + section + '_' + question + '_' + nextindex + '" value="' + parseInt(nextindex - 1) + '">Failed Item</div><div class="col-md-2 color-code color-code-' + section + '-' + question + '" id="color-code-' + section + '-' + question + '" >' +
                '<select name="color_code[' + section_array + '][' + ques_array + '][' + split_id[4] + ']" class="color_code_' + section + '_' + question + '_' + nextindex + ' color-options">' + color_opt + '</select></div></div>');

        } else if ($('#question_type_' + section + "_" + question + ' option:selected').val() == 2 && $('.type-order-' + section + "-" + question + ' option:selected').val() != '') {
            $(option_class + ":last").after("<div class='drop_option_old_" + section + "_" + question + " col-md-12' id='drop_option_old_" + section + "_" + question + "_" + nextindex + "'></div>");

            // Adding element to <div>
            $("#drop_option_old_" + section + "_" + question + "_" + nextindex).append('<div class="row"><div class="col-md-3"><div class="option-box"><a id="remove_option_' + nextindex + '" class="remove_option" onclick="remove_options(\'' + section + '\',\'' + question + '\', \'' + nextindex + '\' )"><span class="glyphicon glyphicon-remove pull-right" alt="Remove"></span></a>&nbsp;<input type="text" placeholder="Write your options" name="old_options[' + section_array + '][' + ques_array + '][' + split_id[5] + ']" class="options_ques  options_ques_input  ques_option"></div>' +
                '</div><div class="col-md-2 failed-item  failed-item-' + section + '-' + question + '" ><input type="radio" name="old_failed_item[' + section_array + '][' + ques_array + ']" class="failed_item failed_item_input  failed_item_' + section + '_' + question + '_' + nextindex + '" value="' + nextindex + '">Failed Item</div><div class="col-md-2 color-code color-code-' + section + '-' + question + '" id="color-code-' + section + '-' + question + '" >' +
                '<select name="old_color_code[' + section_array + '][' + ques_array + '][' + split_id[5] + ']" class="color_code_' + section + '_' + question + '_' + nextindex + ' color-options">' + color_opt + '</select></div></div>');
        } else {
            $(option_class + ":last").after("<div class='option_" + section + "_" + question + "  multieoption' id='option_" + section + "_" + question + "_" + nextindex + "'></div>");

            // Adding element to <div>
            $("#option_" + section + "_" + question + "_" + nextindex).append('<div class="col-md-3"><div class="option-box"><a id="remove_option_' + nextindex + '" class="remove_option" onclick="remove_options(\'' + section + '\',\'' + question + '\', \'' + nextindex + '\' )"><span class="glyphicon glyphicon-remove pull-right" alt="Remove"></span></a>&nbsp;<input type="text" placeholder="Write your options" name="options[' + section_array + '][' + ques_array + '][' + split_id[3] + ']" class="options_ques multieoption_input  ques_option ques_option_first temp_change valid  "></div>' +
                '</div>');
        }

    }

    if ($('#question_type_' + section + "_" + question).val() == 2) {
        $('.failed-item-' + section + "-" + question).show();
        $('.color-code-' + section + "-" + question).show();
    } else {
        $('.failed-item-' + section + "-" + question).hide();
        $('.color-code-' + section + "-" + question).hide();
    }

    $(":checkbox").uniform();
    $(":radio").uniform();
}


// Add dropdown type 
function add_type(sec, ques) {
    $('.more_type_' + sec + '_' + ques).show();
    // console.log(    $('.more_type_' + sec + '_' + ques +'  .dropdown_type'));
    // $('.more_type_' + sec + '_' + ques +'  .dropdown_type').attr('required',true);
    $('.add_type_' + sec + '_' + ques).hide();
    // $('.drop-option-'+sec+'-'+ques).hide();
    $('.dropdown-options-' + sec + '-' + ques).show();
    $('#options_ques_' + sec + '_' + ques + '_' + '1').show();
    // $('.add_option_'+sec+'_'+ques).show();
    $('.option_section_' + sec + '_' + ques).hide();
    $('.audit-option-' + sec + '-' + ques).hide();
    $('.type-order-' + sec + '-' + ques).val('');
    $('.failed-item-' + sec + "-" + ques).show();
    $('.drop_opt_sec_' + sec + '_' + ques).show();
    $("#drop_opt_" + sec + "_" + ques).show();
    $('.drop_opt_sec_old_' + sec + '_' + ques).hide();
}

// On change type order of dropdown
function change_type(sec, ques, ele) {
    var type = $.trim(ele.value);
    var data_name = $('option:selected', ele).data('name');
    var data_id = $('option:selected', ele).data('id');
    console.log(data_name);
    if (ele.value == '' && ele.value != 'audit') {
        $('.more_type_' + sec + '_' + ques).show();
        $('.dropdown-options-' + sec + '-' + ques).show();
        $('.add_type_' + sec + '_' + ques).hide();
        $('.option_section_' + sec + '_' + ques).hide();
        $('.audit-option-' + sec + '-' + ques).hide();
        $('.drop_opt_sec_' + sec + '_' + ques).show();
        $('.drop_opt_sec_old_' + sec + '_' + ques).hide();
        $('.drop_option_old_' + sec + '_' + ques).hide();
        // $('.dropdown-option-' + sec + '-' + ques).hide();
    } else if (ele.value == 'audit' || data_name == 'audit') {
        $('.more_type_' + sec + '_' + ques).hide();
        $('.dropdown-options-' + sec + '-' + ques).hide();
        $('.add_type_' + sec + '_' + ques).show();
        $('.option_section_' + sec + '_' + ques).hide();
        $('.audit-option-' + sec + '-' + ques).show();
        $('.drop_opt_sec_' + sec + '_' + ques).hide();
        $('.drop_opt_sec_old_' + sec + '_' + ques).hide();
        $('.drop_option_old_' + sec + '_' + ques).hide();
        // $('.dropdown-option-' + sec + '-' + ques).hide();
    } else if (data_name != '' && data_name != 'audit' && data_id != '') {
        $('.' + data_name + '-' + data_id).show();
        $('.more_type_' + sec + '_' + ques).hide();
        $('.audit-option-' + sec + '-' + ques).hide();
        $('.drop_opt_sec_' + sec + '_' + ques).hide();
        $('.dropdown-options-' + sec + '-' + ques).show();
        $('.drop_opt_sec_old_' + sec + '_' + ques).show();
        $('.drop_option_old_' + sec + '_' + ques).show();
    }
}

/* search document in create adn edit template 
in document library in guide section */
function search_document(sec, ques, self) {
    $('.pre_loader').show();
    // console.log(self.value);
    var search = self.value;
    $.ajax({
        url: APP_URL + '/admin/document_library',
        type: 'GET',
        data: {
            'search': search
        },
        dataType: "json",
        success: function (response) {
            $('.doc_listing_' + sec + '_' + ques).html(response.folderStructure);
            $(":checkbox").uniform();
            $(":radio").uniform();
            $('.document').parent().parent().addClass('documentnew');
            $('.documentnew').css({
                "position": "absolute",
                "height": "100%",
                "width": "100%"
            });
        },
        complete: function () {
            $('.pre_loader').hide();
        },
        error: function (error) {
            errorHandler(error);
        }
    });
}

// open attached file 
function performClick(elemId) {
    var elem = document.getElementById(elemId);
    if (elem && document.createEvent) {
        var evt = document.createEvent("MouseEvents");
        evt.initEvent("click", true, false);
        elem.dispatchEvent(evt);
    }
}


var saved_questions_array = [];
function save_question(data) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $("#edit_template").submit(function (e) {
        e.preventDefault();
    });

    if ($('#create_template, #edit_template').valid()) {

        // $("#create_template").submit(function (e) {
        //     e.preventDefault();
        // });
        // console.log($('#create_template').serialize());
        var formElement = document.getElementById('edit_template');
        // console.log(formElement);
        data = data.split("_");
        // console.log(data);
        section_id = data[2];
        question_id = data[3];
        var formdata = new FormData(formElement);
        formdata.append("section_id", section_id);
        formdata.append("question_id", question_id);
        // console.log(JSON.stringify(formdata));
        // console.log(JSON.stringify($('#create_template').serialize()));
        // console.log(JSON.stringify($('#create_template').serializeArray()));

        // $("#addquestionbutton_"+section_id).show();

        // if(!saved_questions_array.includes("section "+section_id+ " question "+question_id)){
        saved_questions_array.push("section " + section_id + " question " + question_id);

        $('.pre_loader').show();

        $.ajax({
            url: APP_URL + '/admin/saveQuestion',
            type: 'post',
            data: formdata,
            cache: false,
            processData: false,
            contentType: false,
            // contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response) {

                
            
                $("input[name=template_id]").val(response.template_id);

                // console.log(response);
                // console.log(JSON.stringify(response.saved_data));
                // $("input[name=saved_data[]]").val(JSON.stringify(response.saved_sections_id));

                $("#addquestionbutton_" + section_id).show();


                if ($("#saved_data").val() == "") {
                    // console.log("saved_data");
                    saved_data = {};
                } else {
                    saved_data = JSON.parse($("#saved_data").val());
                }
                // console.log(saved_data);
                saved_data[section_id] = response.saved_section_id;

                $("#saved_data").val(JSON.stringify(saved_data));
             

             
                

                // $(".save_template").attr("disabled",false);
                return true;

                // $("#saved_questions").val(JSON.stringify(saved_questions_array));

                // $("#new_questions").val(JSON.stringify(response.question));

            },
            complete: function () {
                $('.pre_loader').hide();
            },
            error: function (error) {
                // saved_questions_array.pop("section "+section_id+ " question "+question_id);
                errorHandler(error);
            }
        });

        // }
    }


}








// update question in edit template
function update_question(section, question, id) {
    $('.pre_loader').show();
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var id = id;
    var formElement = document.getElementById('edit_template');
    // console.log(formElement);

    var formdata = new FormData(formElement);
    formdata.append("id", id);
    formdata.append("section_question", section);
    formdata.append("section_question_id", question);

    $.ajax({
        url: APP_URL + '/admin/updateQuestion',
        type: 'POST',
        data: formdata,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            var resp = response.message;
            alertSuccess(resp);

        },
        complete: function () {
            $('.pre_loader').hide();
        },
        error: function (error) {
            errorHandler(error);
        }

    });
}


function appendfileinput(section,question){
    $(".fileinput_1_1").append('<input type="hidden" name="files['+section+']['+(question)+'][]" class="files_ids form-control">');
}

function append_edit_fileinput(section,question,value){
    // console.log(value);
    // console.log(value);
    // array=[];
    // $.each(value, function (index) {
    //     // console.log(value[index])
    //     array[index]=value[index];
    // });
    // console.log(array);
    $(".fileinput_1_1").append('<input type="hidden" name="files['+section+']['+(question)+'][]"   class="files_ids form-control">');
}

Dropzone.autoDiscover = false;

function createdrozone(section,question){
    console.log("callsed")
    // console.log(section);
    // console.log(question);
    new Dropzone('.files_'+section+'_'+question,{
        maxFilesize: 50,
        // acceptedFiles: 'image/*',
        url:APP_URL+'/admin/saveimage',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        paramName:"document",
        uploadMultiple:true,
        // autoProcessQueue:false,
        addRemoveLinks:true,
        // autoQueue: false,
        parallelUploads:1,
        previewTemplate: document.getElementById('template-preview').innerHTML,

        init:function(){


            
            
            var myDropzone = this;
          
            $.get( APP_URL+ '/admin/getThumnails', {
                temp_id: temp_id,
                section:section,
                question:question,
            }, function(result) {
                console.log(result);
                var a=1;
                $.each(result, function(key, value) {
                    // console.log(key);    
                                    
                    console.log(APP_URL+"/uploads/"+value.document_name);
                    // console.log(result);
                    // console.log("key"+key);
                    // console.log("value"+value);
                       
                    var ori='';
                    var mockFile = {
                        name: APP_URL+"/uploads/"+value.name,
                        size: value.size,
                        id: value.id
                    };
                //   var ori=value.orient;
                //  //alert(ori);
                //     var str =value.name;
                    linkk=value.link;
                    
                    myDropzone.options.addedfile.call(myDropzone, mockFile);

                    myDropzone.options.thumbnail.call(myDropzone, mockFile,linkk); //uploadsfolder 
                    $('.dz-preview').addClass("dz-complete");
                  $(mockFile.previewElement).prop('id', value.name);
                  $(mockFile.previewElement).find("input[name='files["+(section-1)+"]["+(question-1)+"][]']:first").val(JSON.stringify(value.guide));
           
                      $('.dz-image').last().find('img').attr({width: '100%', height: '100%'})
                 
                  a++;
                    
                });
            }, 'json');
        },


        renameFilename: function (filename) {
            // console.log(filename);
            return filename;
        },
        success: function (file, response) {
            // console.log(response);
            // console.log(file);
            // console.log(section);
            // console.log(question);
            // console.log("input[name='files["+section-1+"]["+question+"]']");

            $(file.previewElement).find("input[name='files["+(section-1)+"]["+(question-1)+"][]']:first").val(response);
            // return file.previewElement.classList.add("dz-success");
            // $(".pre_loader").hide();
        }
    });
}






