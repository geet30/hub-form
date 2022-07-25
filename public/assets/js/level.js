$(document).ready(function () {
    
    $(":checkbox").uniform();
    $(":radio").uniform();
    $('#level_forms').show();
    $('.pre_loader').hide();
    var BU_table = $('#level_forms').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        // ordering: false,
        aoColumnDefs: [],
        columnDefs: [{
                orderable: false,
                targets: 0
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
                width : '40%',
                targets: 2
            },
            {
                orderable: false,
                targets: 4
            },
            {
                orderable: false,
                targets: 5
            },
        ],
        order: [],
        dom: 'Blfrtip',
        buttons: [
            {
                className: 'create_bu',
                id: 'create_bu',
                title: 'Create Level',
                text: "Create Level",
                action: function (e, dt, node, config) {
                    //This will send the page to the location specified
                    window.location.href = APP_URL+'/admin/cms/level/create';
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                }
            },
        ],
    });

    BU_table.on( 'order.dt search.dt', function () {
        BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#level_forms_filter').css('display', 'none');
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

    $('#i_start_limit , #i_end_limit').on('input keypress keyup paste', function(eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
          eve.preventDefault();
        }
      
        // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
        $('#i_start_limit, #i_end_limit').on('input keypress keyup paste',function(eve) {
          if ($(this).val().indexOf('.') == 0) {
            $(this).val($(this).val().substring(1));
          }
        });
    });


    //level Form validation
    jQuery('#createLevel, #editLevel').validate({
        rules :{
            vc_name: {
                required: true,
            },
            i_start_limit:{
                required: true,
                digits: true
            },
            i_end_limit:{
                required: true,
                digits: true
            },
        },
        messages :{
            vc_name: {
                required: "Please enter Name",
            },
            i_start_limit:{
                required: "Please enter Minimum Budget",
            },
            i_end_limit:{
                required: "Please aenter Maximum Budget",
            },
        }

    });

    //Archive Business unit
    $(".archive_level").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to archive this Level ?",
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
                        url: APP_URL+'/admin/cms/level/'+id,
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




