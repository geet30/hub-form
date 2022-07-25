
$(document).ready(function() {
    var BU_table = $('#example').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "lengthMenu": [10, 25, 50, 75, 100],
        //searching: false,
        aoColumnDefs: [
            {bSortable: false, aTargets: [0, 1, 2, 3]},
            { width: "20%", targets: 3 },
            { width: "14%", targets: 4 },
            //{type: 'title-string', targets: 2 }
        ],
        columnDefs: [
           { orderable: false, targets: 0 }
        ],
        order:[],
        // scrollX : true,
    });
    BU_table.on( 'order.dt search.dt', function () {
        BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
         cell.innerHTML = i+1;
     } );
    }).draw();

    $('#tableUsers_filter').css('display', 'none');
    $(".filterhead").each( function ( i ) {
        var title = $(this).text();
        $(this).html("");
        if(i == 1) {
            $(this).html( '<input class="filter form-control" id="searching" type="text"  placeholder="Search '+title+'" />');
        } else if(i == 2){
            $(this).html( '<input class="filter form-control" id="searching" type="text"  placeholder="Search '+title+'" />');
        } else if(i == 3) {
            $(this).html( '<input class="filter form-control" id="fiscalYear" type="text"  placeholder="Search '+title+'" />');
        }  else if(i == 4) {
            $(this).html('<select class="filter form-control dropdown-filter"><option value="">Type</option><option value="1">MDG-15</option><option value="0">WRK</option></select>');
        }

        $( '.filter', this ).on( 'keyup change', function () {
            if ( BU_table.column(i).search() !== this.value ) {
                BU_table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    });
});
$('body').on('click','.reset', function(){
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('.filter').trigger('change');
});
$(function () {
    $("#fiscalYear").datepicker({
        dateFormat: "yy-mm-dd",
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        //gotoCurrent: true,
    });
});