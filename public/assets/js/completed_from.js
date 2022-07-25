
$(document).ready(function () {

    Dropzone.autoDiscover = false;

    if ($('.dropdown_ans :selected').val() == '(C) Compliant') {
        $(".dropdown_ans").css("background-color", "#7CFC00");
        $(".dropdown_ans").css("color", "black");
    } else if ($('.dropdown_ans :selected').val() == '(NC) Non-Compliant') {
        $(".dropdown_ans").css("background-color", "#FF0000");
        $(".dropdown_ans").css("color", "black");
    } else if ($('.dropdown_ans :selected').val() == '(V) Variation') {
        $(".dropdown_ans").css("background-color", "#7B68EE");
        $(".dropdown_ans").css("color", "black");
    } else if ($('.dropdown_ans :selected').val() == '(ND) Not-Determined') {
        $(".dropdown_ans").css("background-color", "#FFD700");
        $(".dropdown_ans").css("color", "black");
    } else if ($('.dropdown_ans :selected').val() == 'End user requirement') {
        $(".dropdown_ans").css("background-color", "#00BFFF");
        $(".dropdown_ans").css("color", "black");
    } else if ($('.dropdown_ans :selected').val() == '(N/A) Not applicable') {
        $(".dropdown_ans").css("background-color", "#C0C0C0");
        $(".dropdown_ans").css("color", "black");
    }

    $('#completed_forms').show();

    $('.pre_loader').hide();
    var serialno;
    var BU_table = $('#completed_forms').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        ordering: false,
        "autoWidth": false,
        //searching: false,
        aoColumnDefs: [
            // {bSortable: false, aTargets: [8]},
            //{type: 'title-string', targets: 2 }
        ],
        columnDefs: [{
                orderable: false,
                targets: 8
            },
            {
                width: "15%",
                targets: 0,
                render: function(data, type) {
                    return type === 'export'? serialno++ : data;
                  }
            },
            {
                width: "10%",
                targets: 1
            },
            {
                width: "10%",
                targets: 2
            },
            {
                width: "25%",
                targets: 3
            },
            {
                width: "17%",
                targets: 4
            },
            {
                width: "15%",
                targets: 5
            }
        ],
        order: [],
        // scrollX : true,
        dom: 'Blfrtip',
        buttons: [{
                className: 'google_map',
                id: 'google_map',
                title: 'Display google Map',
                text: "Display google Map",
                action: function (e, dt, node, config) {
                    //This will send the page to the location specified
                    window.location.href = 'google_map';
                    // "window.location = '".route('admin.posts.create')."';"
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    // columns: [1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                    columns: [0, 1, 2, 3, 4, 5, 6, 7],
                    orthogonal: "export",
                    rows: function(idx, data, node) {
                        serialno = 1;
                        return true;
                    }
                    // columns: 'th:not(:first-child)'
                }
            },
        ],
    });

    // BU_table.on( 'order.dt search.dt', function () {
    //     BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     } );
    // } ).draw();


    $('#completed_forms_filter').css('display', 'none');

    $(".filterhead").each(function (i) {
        $('#filter_type', this).on('change', function () {
            if (BU_table.column(1).search() !== $(this).val()) {
                BU_table
                    .column(1)
                    .search(this.value)
                    .draw();
            }
        });

        $('#searching').unbind().on('keyup change', function () {
            var searchTerm = this.value.toLowerCase();
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                //search only in column 1 and 2
                if (~data[0].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[3].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[5].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[7].toLowerCase().indexOf(searchTerm)) return true;
                return false;
            })
            BU_table.draw();
            $.fn.dataTable.ext.search.pop();
        })

        $('#filter_department', this).on('change', function () {

            if (BU_table.column(6).search() !== $(this).val()) {
                BU_table
                    .column(6)
                    .search(this.value)
                    .draw();
            }
        });

        $('#filter_completed', this).on('change', function () {
            // alert($(this).val());
            if (BU_table.column(4).search() !== $(this).val()) {
                BU_table
                    .column(4)
                    .search(this.value)
                    .draw();
            }
        });

        $('.filter_date', this).on('change', function () {
            if (BU_table.column(2).search() !== $(this).val()) {
                BU_table
                    .column(2)
                    .search(this.value)
                    .draw();
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
    $(".date_picker").datepicker({
        dateFormat: "yy-mm-dd",
        endDate: "today",
        maxDate: today,
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
    });
});

/*Save as completed forms*/
$(document).ready(function () {
    $(".archive").click(function (event) {
        event.preventDefault();
        var self = this;
        bootbox.confirm({
            message: "Are you sure you want to archive this form ?",
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
                        url: '/admin/archive',
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


    //restore completed forms
    $(".restore").click(function (event) {
        event.preventDefault();
        var self = this;
        bootbox.confirm({
            message: "Are you sure you want to restore this form ?",
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
                        url: '/admin/restore',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            console.log(data);
                            location.reload(true);
                        }
                    });
                }
            }
        });
    });

    //edit completed forms
    $(".update_answer").click(function (event) {
        event.preventDefault();
        $('.pre_loader').show();
        // $(this).attr('disabled', true);
        let type = '';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        type = $(this).attr('data-type');
        let answer = $(this).parent().find(".answer").val();
        let completed_forms = $("#form_id").val();
        var arr = [];
        $(this).parent().find(".answer:checkbox:checked").each(function () {
            arr.push($(this).val());
        });
        //for multiple select
        if (type == 7) {
            $(this).parent().find(".answer :selected").each(function () {
                arr.push($(this).val());
            });
        }

        //for yes no 
        if (type == 5) {
            answer = $(this).parent().find(".answer:selected").val();
        }

        var formData = new FormData();
        formData.append('_token', CSRF_TOKEN)
        formData.append('id', id)
        formData.append('answer', answer)
        formData.append('arr', arr)

        $.ajax({
            url: '/admin/update_answer',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function (data) {
                // console.log(data);
                if (data == 0) {
                    $('.pre_loader').hide();
                    alertError('Failed to update answer!')
                } else {
                    $('.pre_loader').hide();
                }
            },
            error: function (error) {
                alertError('Something Went Wrong!')
            }
        });
    });
    //save admin comments
    $(".save_comments").click(function (event) {
        event.preventDefault();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        let question = $(this).attr('data-question');
        let answer = $(this).attr('data-answer');
        let section = $(this).attr('data-section');
        let template_id = $(this).attr('data-template');
        let comment = $(this).parentsUntil(".comment").find(".comment").val();
        let form_id = $("#form_id").val();
        $.ajax({
            url: '/admin/save_comments',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                type: type,
                comment: comment,
                question_id: question,
                answer_id: answer,
                form_id: form_id,
                template_id: template_id,
                section_id: section,
            },
            dataType: 'JSON',
            success: function (data) {
                console.log('there', data);
                
                if (data == 0) {
                    alertError('Something Went Wrong!')
                }else{
                    alertSuccess('Comment added successfully.')
                }
                // console.log(data);
                //  location.reload(true);
            },
            error: function (error) {
                console.log('here');
                alertError('Something Went Wrong!')
            }
        });
    });


    /**
     * Open evidences
     */
    var evidenceParams = {};
    $(document).on("click", ".model-evidence", function () {
        var answer = $(this).attr('data-answer-id');
        var section = $(this).attr('data-section-id');
        if(answer != '' && section != ""){
            evidenceParams = {
                section_id : section,
                answer_id : answer
            }
        } else {
            alertError('Something Went Wrong!');   
        }
    });
    /**
     * Use Dropzone
     */
    if($('div#DropzoneEvidence').length > 0){
        var myDropzone = new Dropzone("div#DropzoneEvidence", {
            url: "/admin/upload-evidence",
            addRemoveLinks: true,
            acceptedFiles:'image/*, .pdf, audio/*, video/*, .doc, .docx, .docm',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function () {
                var self = this;
                this.on("sending", function (file, xhr, formData) {
                    formData.append("section_id", evidenceParams.section_id);
                    formData.append("answer_id", evidenceParams.answer_id);
                });
                this.on("success", function (file, response) {
                    if(response.status == true){
                        $(".evidence-" + response.answer_id).html(response.evidences);
                        $("#model-evidence-content").modal('hide');
                        self.removeFile(file);
                    }
                });
            }
        });
    }
});

//show document name after selecting answer documnet
function show_doc(value, type, ans_id) {
    // console.log(value);
    var doc_name = value.replace(/C:\\fakepath\\/i, '');
    var doc_lenght = doc_name.length;
    var short_doc_name = '';
    if (doc_lenght > 10) {
        for (var i = 0; i <= 10; i++) {
            short_doc_name += doc_name[i];
        }
        short_doc_name += '..';
    } else {
        short_doc_name = doc_name;
    }
    type = $.trim(type);
    if (type == 'document') {
        if (short_doc_name != '') {
            $('.doc-name-' + ans_id).show().text(short_doc_name);
            $('.remove-doc-' + ans_id).show();
        }
    } else if (type == 'picture') {
        // var pic_name = $('#picture-'+ans_id).val().replace(/C:\\fakepath\\/i, '');
        if (short_doc_name != '') {
            $('.pic-name-' + ans_id).show().text(short_doc_name);
            $('.remove-pic-' + ans_id).show();
        }
    } else if (type == 'vedio') {
        // var vedio_name = $('#vedio-'+ans_id).val().replace(/C:\\fakepath\\/i, '');
        if (short_doc_name != '') {
            $('.vedio-name-' + ans_id).show().text(short_doc_name);
            $('.remove-vedio-' + ans_id).show();
        }
    } else if (type == 'audio') {
        // var audio_name = $('#audio-'+ans_id).val().replace(/C:\\fakepath\\/i, '');
        if (short_doc_name != '') {
            $('.audio-name-' + ans_id).show().text(short_doc_name);
            $('.remove-audio-' + ans_id).show();
        }
    }

}


// remove selected documents of answer
function removeDoc(type, ans_id) {
    type = $.trim(type);
    if (type == 'document') {
        $('.doc-name-' + ans_id).hide().text('');
        $('.remove-doc-' + ans_id).hide();
        $('#document-' + ans_id).val('');
    } else if (type == 'picture') {
        $('.pic-name-' + ans_id).hide().text('');
        $('.remove-pic-' + ans_id).hide();
        $('#picture-' + ans_id).val('');
    } else if (type == 'vedio') {
        $('.vedio-name-' + ans_id).hide().text('');
        $('.remove-vedio-' + ans_id).hide();
        $('#vedio-' + ans_id).val('');
    } else if (type == 'audio') {
        $('.audio-name-' + ans_id).hide().text('');
        $('.remove-audio-' + ans_id).hide();
        $('#audio-' + ans_id).val('');
    }

}

// remove evidence of answer
function remove_file(id) {
    event.preventDefault();
    var self = this;
    bootbox.confirm({
        message: "Are you sure you want to remove this file ?",
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
                $.ajax({
                    url: '/admin/delete-evidence',
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        id: id
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data == 1) {
                            $('.file-' + id).remove();
                        } else {
                            alertError('Something Went Wrong!')
                        }
                    },
                    error: function (error) {
                        alertError('Something Went Wrong!')
                    }
                });
            }
        }
    });
}

// on submit edit completed form
$('.edit_form').submit(function(){
    $('#business_unit').attr('disabled', false);
    $('#department').attr('disabled', false);
    $('#project').attr('disabled', false);
});
