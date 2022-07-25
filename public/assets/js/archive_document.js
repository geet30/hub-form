// archived dcoument datable js  
$(document).ready(function() {

    $('#archive_document_table').show();
    $('.pre_loader').hide();
    var archive_BU_table = $('#archive_document_table').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        ordering: true,
        aoColumnDefs: [
        ],
        columnDefs: [
            { targets: 0 , searchable: true },
            { targets: 1 , searchable: true },
            { targets: 2 , searchable: true },
            { targets: 6 , searchable: true },
            { targets: 8 , searchable: true },
            
            {
                targets: 10,
                orderable: false
            },
            // { targets: [0,1,2,6,8]}
        ],
        order:[],
        dom: 'Bfrtip',
        buttons: [           
            {
                className: 'btn sbold green',
                extend: 'csvHtml5',
                title: 'Document CSV',
                text:"Download CSV <i class='fa fa-file-excel-o' aria-hidden='true'>",
                //Columns to export
                exportOptions: {
                  // columns: [1, 2, 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                  columns: [0,1, 2,3,4,5,6,7,8,9],
                  // columns: 'th:not(:first-child)'
              }
            }
        ],
        "bInfo": false,
        fixedColumns: true
    });

    archive_BU_table.on( 'order.dt search.dt', function () {
        archive_BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            // cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#archive_document_table_filter').css('display', 'none');
    $(".filterhead").each(function (i) {
        var title = $(this).text();
        var dept_value = $('#dept_name').val();
        var cat_name = $('#cat_name').val();
        var folder_name = $('#folder_name').val();
        $(this).html("");
        if (i == 0) {
            $(this).html('<input class="filter form-control" style = "width:100px" id="searching" type="text"  placeholder="Search" />');
        } else if (i == 1) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_category"><option value="">Category</option>' + cat_name + '</select>');
        } else if (i == 2) {
            $(this).html('<form class="date-filter filter" style = "width:150px" id ="filter_date"><input class="filter form-control" id="fiscalYear" id="date_filter" type="text"  placeholder="' + title + '" /><span class="glyphicon glyphicon-calendar"></span></form>');
        } else if (i == 3) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_department"><option value="">Department</option>' + dept_value + '</select>');
        } else if (i == 4) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_folder"><option value="">Folder</option>' + folder_name + '</select>');
        } else if (i == 5) {
            $(this).html('<button class="btn btn-success reset">Reset Filter</button>');
        }

        $('#filter_category', this).on('change', function () {
            if (archive_BU_table.column(3).search() !== $(this).val()) {
                archive_BU_table
                    .column(3)
                    .search(this.value)
                    .draw();
            }
        });

        $('#searching').unbind().on('keyup change', function () {
            var searchTerm = this.value.toLowerCase();
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                //search in column
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
                return false;
            })
            archive_BU_table.draw();
            $.fn.dataTable.ext.search.pop();
        })

        $('#filter_department', this).on('change', function () {

            if (archive_BU_table.column(7).search() !== $(this).val()) {
                archive_BU_table
                    .column(7)
                    .search(this.value)
                    .draw();
            }
        });

        $('#filter_folder', this).on('change', function () {
            // alert($(this).val());
            if (archive_BU_table.column(1).search() !== $(this).val()) {
                archive_BU_table
                    .column(1)
                    .search(this.value)
                    .draw();
            }
        });

        $('#fiscalYear', this).on('change', function () {
            var date = new Date($(this).val());
            yr = date.getFullYear(),
                month = date.getMonth() < 10 ? '0' + date.getMonth() + 1 : date.getMonth() + 1,
                day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
                newDate = yr + '-' + month + '-' + day;
            // alert($(this).val());
            if (archive_BU_table.column(5).search() !== $(this).val()) {
                archive_BU_table
                    .column(5)
                    .search($(this).val())
                    .draw();
            }
        });

    });

    //restore document
    $(".restore_document").click(function(event){
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
                    let id =  $(self).attr('data-id');
                    $.ajax({
                        url: '/admin/restore_document',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id:id},
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
$('body').on('click', '.archive_reset', function(){
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('#archive_searching').val('');
    $('.filter').trigger('change');
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

//stop form submit on other buttons click
function stop_submit(){
    event.preventDefault();
}