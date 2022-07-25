$(document).ready(function () {

    $('#action_table').show();
    $('.pre_loader').hide();
    var serialno;
    var BU_table = $('#action_table').DataTable({
        deferLoading: true,
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        aoColumnDefs: [
        ],
        columnDefs: [
            { orderable: false, targets: 0, searchable: true,
            
                render: function(data, type) {
                    return type === 'export'? serialno++ : data;
                  }
             }, //id
            { orderable: false, targets: 1, searchable: true,}, //ac id
            { orderable: true, targets: 2, searchable: true },//source
            { orderable: true, targets: 3, searchable: true },//title
            { orderable: true, targets: 4, searchable: true },//desp
            { orderable: true, targets: 6, searchable: true },//due date
            { orderable: true, targets: 7, searchable: true },//assgn
            { orderable: true, targets: 8, searchable: true },//bus
            { orderable: true, targets: 9, searchable: true },//depart
            { orderable: true, targets: 10, searchable: true },// proje
            { orderable: false, targets: 11, searchable: true },// status
            // { width: "25px", targets: 6},
            // { width: "30px", targets: 2},
            // { width: "30px", targets: 3},
            {
                "width": "20%",
                "targets": 4
            },
            {
                "width": "15%",
                "targets": 3
            },
            // { targets: [0,1,2,6,8]}
        ],
        order: [],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    // columns: [1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    orthogonal: "export",
                    rows: function(idx, data, node) {
                        serialno = 1;
                        return true;
                    }
                    // columns: 'th:not(:first-child)'
                }
            },
            {
                className: 'btn sbold green create_action',
                id: 'sample_editable_1_new',
                title: 'Create Action',
                text: "Create Action",
                action: function () {
                    window.location.href = $('#create_action_url').val();
                }
            },
        ],
        "bInfo": false,
        fixedColumns: true
    });


    BU_table.on('order.dt search.dt', function () {
        BU_table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $('#action_table_filter').css('display', 'none');
    $(".filterhead").each(function (i) {
        var title = $(this).text();
        var dept_value = $('#dept_value').val();
        var assignee_value = $('#assignee_value').val();
        $(this).html("");
        if (i == 0) {
            $(this).html('<input class="filter form-control" style = "width:100px" id="searching" type="text"  placeholder="Search" />');
        } else if (i == 1) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:100px" id="filter_status"><option value="">' + title + '</option><option value="Pending">Pending</option><option value="In-Progress">In-Progress</option><option value="Completed">Completed</option><option value="Closed">Closed</option><option value="Rejected">Rejected</option><option value="Overdue">Overdue</option></select>');
        } else if (i == 2) {
            $(this).html('<form class="date-filter filter" style = "width:130px" id ="filter_date"><input class="filter form-control filter_date" id="fiscalYear" type="text"  placeholder="' + title + '" /><span class="glyphicon glyphicon-calendar"></span></form>');
        } else if (i == 3) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_department"><option value="">Department</option>' + dept_value + '</select>');
        } else if (i == 4) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_assignee"><option value="">' + title + '</option>' + assignee_value + '</select>');
        } else if (i == 5) {
            $(this).html('<button class="btn btn-success reset filter">Reset Filter</button>');
        }

        var status = $('#action_status').val();
        // console.log(status);
        status = $.trim(status);
        BU_table.columns(9).search(status);
        BU_table.draw();
        $('#filter_status').val(status);

        $('#filter_status', this).on('change', function () {
            if (BU_table.column(9).search() !== $(this).val()) {
                BU_table
                    .column(9)
                    .search(this.value)
                    .draw();
            }
        });

        $('#searching').unbind().on('keyup change', function () {
            var searchTerm = this.value.toLowerCase();
            console.log(searchTerm);
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                //search only in column 1 and 2
                console.log(data);
                if (~data[0].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[1].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[2].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[3].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[4].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[5].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[6].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[7].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[8].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[9].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[10].toLowerCase().indexOf(searchTerm)) return true;
                return false;
            })
            BU_table.draw();
            $.fn.dataTable.ext.search.pop();
        })

        $('#filter_department', this).on('change', function () {

            if (BU_table.column(7).search() !== $(this).val()) {
                BU_table
                    .column(7)
                    .search(this.value)
                    .draw();
            }
        });

        $('#filter_assignee', this).on('change', function () {
            // alert($(this).val());
            if (BU_table.column(5).search() !== $(this).val()) {
                BU_table
                    .column(5)
                    .search(this.value)
                    .draw();
            }
        });

        $('.filter_date', this).on('change', function () {
            if (BU_table.column(4).search() !== $(this).val()) {
                BU_table
                    .column(4)
                    .search(this.value)
                    .draw();
            }
        });

    });

    //close close action popup on cross button click
    $('.modal_close').click(function () {
        $('#close_action_form').trigger("reset");
        $('#close_action_modal').hide();
        $('label.error').remove();
        $(".action_close").removeClass("error");
    })
    //add validation to close action form
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 100MB');

    $("#close_action_form").validate({
        //   ignore: [],
        rules: {
            close_date: {
                required: true
            },
            close_by: {
                required: true,
                maxlength: '50'
            },
            comments: {
                required: true,
                maxlength: '300'
            },
            evidence: {
                // extension: "docx|rtf|doc|pdf|xls|xlsx|jpg|jpeg|gif|mp4|mp3||png",
                filesize: 100000000,
            },
        },
        messages: {
            close_date: {
                required: "Please enter choose close date.",
            },
            close_by: {
                required: "Please enter close by.",
                maxlength: "Please enter less then 50 characters."
            },
            comments: {
                required: "Please enter comments.",
                maxlength: "Please enter less then 300 characters."
            },
            evidence: {
                // extension: "Please upload valid file."
            }
        },
        //   submitHandler: function(form) {
        //     form.submit();
        //   },
    });

    //restore action
    $(".restore_action").click(function (event) {
        event.preventDefault();
        var self = this;
        bootbox.confirm({
            message: "Are you sure you want to restore this action ?",
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
                        url: '/admin/restore_action',
                        type: 'POST',
                        data: { _token: CSRF_TOKEN, id: id },
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
    window.history.replaceState({}, '', '?', '');
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('#searching').val('');
    $('.filter').trigger('change');
    $('.filter').trigger('keyup');
});

// on click close action done button 
$('body').on('click', '.action-close-btn', function (e) {
    e.preventDefault();
    if ($("#close_action_form").valid()) {
        $(".action-close-btn").attr('type', 'hidden');
        $('.upload-loader').css('display', 'inline-block');
        $('#close_action_form').submit();
    }
});

$(function () {
    var today = new Date();
    $("#fiscalYear").datepicker({
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        // gotoCurrent: true,
    });
});

$(function () {
    var today = new Date();
    $(".close_date").datepicker({
        dateFormat: "YY-mm-dd",
        minDate: 0,
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        // gotoCurrent: true,
    });
});

function close_action($id) {
    $('#close_action_modal').show();
    $('#action_id').val($id);
    // alert('here')
}

// archive action 
function archive_actions(id) {
    var self = this;
    event.preventDefault();
    bootbox.confirm({
        message: "Are you sure you want to archive this action ?",
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
                // let id = $(self).data('id');
                // console.log(self);
                $.ajax({
                    url: '/admin/destroy',
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success: function (data) {
                        // console.log(data);
                        location.reload(true);
                    }
                });
            }
        }
    });
}

//stop form submit on other buttons click
function stop_submit() {
    event.preventDefault();
}
