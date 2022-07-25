$(document).ready(function() {
        $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        bSort: false,
        language: {
            searchPlaceholder: "Search by ID",
            emptyTable:"No matching records found"
        },
        ajax: {
            url: '{{ route('template-listing') }}',
            data: function ( d ) {
                d.category = $('.custom_filter_class select').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'company_name', name: 'company_name',orderable: false,searchable: true},
            {data: 'type', name: 'type',orderable: false},
            {data: 'date', name: 'date',orderable: false,searchable: false},
            {data: 'action', name: 'action',orderable: false, searchable: false},
        ],
        "fnDrawCallback": function(settings, json) {
          //  alert( 'DataTables has finished its initialisation.' );
          applpyEllipses('loader_div', 5, 'no');
        }
    });
        });