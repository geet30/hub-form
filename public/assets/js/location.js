$(document).ready(function () {
    
    $(":checkbox").uniform();
    $(":radio").uniform();
    $('#location_forms').show();
    $('.pre_loader').hide();
    var serialno;
    var BU_table = $('#location_forms').DataTable({
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
                targets: 4
            },
            {
                orderable: false,
                targets: 5
            },
            {
                width : '20%',
                targets: 1
            },
            {
                width : '20%',
                targets: 2
            }
        ],
        order: [],
        dom: 'Blfrtip',
        buttons: [
            {
                className: 'create_location',
                id: 'create_location',
                title: 'Create Location',
                text: "Create Location",
                action: function (e, dt, node, config) {
                    //This will send the page to the location specified
                    window.location.href = APP_URL+'/admin/cms/location/create';
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
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

    $('#location_forms_filter').css('display', 'none');
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

    //location Form validation
    $('#createLocation , #editLocation').validate({
        rules :{
            vc_name: {
                required: true,
            },
            contact_number:{
                digits: true,
                maxlength: 11,
                minlength:6
            },
            other_contact_number:{
                digits: true,
                maxlength: 11,
                minlength:6
            },
            vc_postal_code:{
                digits: true,
                maxlength: 9,
                minlength:6
            },
        },
        messages :{
            vc_name: {
                required: "Please enter Location Name",
            }
        }

    });

    //Archive location
    $(".archive_location").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to archive this location ?",
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
                        url: APP_URL+'/admin/cms/location/'+id,
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

    //restore location
    $(".restore_location").click(function (event) {
        event.preventDefault();
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to restore this Location ?",
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
                        url: APP_URL+'/admin/cms/location/restore',
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




