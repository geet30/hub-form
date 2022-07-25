$(document).ready(function () {
    
    $(":checkbox").uniform();
    $(":radio").uniform();
    $('#project_table').show();
    $('.pre_loader').hide();
    var serialno;
    var BU_table = $('#project_table').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        // ordering: false,
        aoColumnDefs: [],
        columnDefs: [{
                orderable: false,
                targets: 0,
                render: function(data, type) {
                    return type === 'export'? serialno++ : data;
                  }
            },
            {
                orderable: false,
                targets: 5,
                width : '10%',
            },
            {
                orderable: false,
                targets: 4
            },
            {
                width : '25%',
                targets: 1
            },
            {
                width : '15%',
                targets: 3
            },
            {
                width : '25%',
                targets: 2
            }
        ],
        order: [],
        dom: 'Blfrtip',
        buttons: [
            {
                className: 'create_project',
                id: 'create_project',
                title: 'Create Project',
                text: "Create Project",
                action: function (e, dt, node, config) {
                    //This will send the page to the location specified
                    window.location.href = APP_URL+'/admin/cms/projects/create';
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    columns: [0, 1, 2, 3],
                    orthogonal: "export",
                    rows: function(idx, data, node) {
                        serialno = 1;
                        return true;
                    }
                }
            },
        ],
    });

    BU_table.on( 'order.dt search.dt', function () {
        BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#project_table_filter').css('display', 'none');
    $(".filterhead").each(function (i) {
        $('.filter', this).on('keyup change', function () {
            if (BU_table.column(i).search() !== this.value) {
                BU_table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });

    //Archive Project
    $(".archive_project").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to archive this Project ?",
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
                        url: APP_URL+'/admin/cms/projects/'+id,
                        type: 'DELETE',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            location.reload(true);
                        },
                        error: function (error) {
                            alertError('Something Went Wrong!');
                        }
                    });
                }
            }
        });
    });

    //restore project
    $(".restore_project").click(function (event) {
        event.preventDefault();
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to restore this Project?",
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
                        url: APP_URL+'/admin/cms/projects/restore',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            location.reload(true);
                        },
                        error: function (error) {
                            alertError('Something Went Wrong!');
                        }
                    });
                }
            }
        });
    });

    //close and open project
    $(".project-close-open").click(function (event) {
        event.preventDefault();
        var self = this;
        var status = $(self).attr('target');

        if(status == 0){
            var message = "Are you sure you want to open this Project?"
        }else{
            var message = "Are you sure you want to close this Project?"
        }

        event.preventDefault();
        bootbox.confirm({
            message: message,
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
                        url: APP_URL+'/admin/cms/projects/open_close_project',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            status : status
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            location.reload(true);
                        },
                        error: function (error) {
                            alertError('Something Went Wrong!');
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
    $('.filter').trigger('keyup');
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




