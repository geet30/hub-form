// index file of document datatable js
$(document).ready(function () {

    $('#document_table').show();
    $('.pre_loader').hide();
    var BU_table = $('#document_table').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        ordering: false,
        aoColumnDefs: [],
        columnDefs: [{
                targets: 0,
                searchable: true
            },
            {
                targets: 1,
                searchable: true
            },
            {
                targets: 2,
                searchable: true
            },
            {
                targets: 6,
                searchable: true
            },
            {
                targets: 8,
                searchable: true
            },
            // { targets: [0,1,2,6,8]}
        ],
        order: [],
        dom: 'Bfrtip',
        buttons: [],
        "bInfo": false,
        fixedColumns: true
    });


    $('#document_table_filter').css('display', 'none');
    $(".filterhead").each(function (i) {
        var title = $(this).text();
        var dept_value = $('#dept_name').val();
        var cat_name = $('#cat_name').val();
        $(this).html("");
        if (i == 0) {
            $(this).html('<input class="filter form-control" style = "width:100px" id="searching" type="text"  placeholder="Search" />');
        } else if (i == 1) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_category"><option value="">Category</option>' + cat_name + '</select>');
        } else if (i == 2) {
            $(this).html('<form class="date-filter filter" style = "width:150px" id ="filter_date"><input class="filter form-control filter_date" id="fiscalYear" type="text"  placeholder="' + title + '" /><span class="glyphicon glyphicon-calendar"></span></form>');
        } else if (i == 3) {
            $(this).html('<select class="filter form-control dropdown-filter" style = "width:150px" id="filter_department"><option value="">Department</option>' + dept_value + '</select>');
        } else if (i == 4) {
            $(this).html('<button class="btn btn-success reset filter">Reset Filter</button>');
        }

        $('#filter_category', this).on('change', function () {
            if (BU_table.column(3).search() !== $(this).val()) {
                BU_table.column(3).search(this.value).draw();
            }
        });

        $('#searching').unbind().on('keyup', function () {
            var searchTerm = this.value.toLowerCase();
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                //search in column
                if (~data[0].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[2].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[4].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[6].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[8].toLowerCase().indexOf(searchTerm)) return true;
                return false;
            })
            BU_table.draw();
            $.fn.dataTable.ext.search.pop();
        })

        $('#filter_department', this).on('change', function () {

            if (BU_table.column(7).search() !== $(this).val()) {
                BU_table.column(7).search(this.value).draw();
            }
        });

        $('#filter_folder', this).on('change', function () {
            if (BU_table.column(1).search() !== $(this).val()) {
                BU_table.column(1).search(this.value).draw();
            }
        });

        $('.filter_date', this).on('change', function () {
            if (BU_table.column(5).search() !== $(this).val()) {
                BU_table.column(5).search(this.value).draw();
            }
        });
    });
});

$('body').on('click', '.reset', function () {
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('#searching').val('');
    $('.filter').trigger('change');
    $('.filter').trigger('keyup');
});

/**
 * View Document
 */
$('body').on('click', '.view-document', function () {
    $("#view_document").show();
    var document = $(this).data("document");
    if (document != undefined && document != null && document != '' && document.file_type != undefined) {
        var fileType = document.file_type;
        console.log("fileType", fileType);
        var doucHtml = '';
        switch (fileType) {
            case 1:
                doucHtml = `<img class="img-responsive center-block" src="${document.doc_link}" />`;
                break;
            case 2:
                doucHtml = `<audio controls> 
                <source src="${document.doc_link}" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>`
                break;
            case 3:
                window.open('https://docs.google.com/gview?url=' + document.doc_link, '_blank');
                $("#view_document").hide();
                break;
            case 4:
                doucHtml = `<video width="100%" controls style='width: 100%;height: auto;'>
                    <source src="${document.doc_link}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>`
                break;
            case 5:
                window.open('https://docs.google.com/gview?url=' + document.doc_link, '_blank');
                $("#view_document").hide();
                break;
            default:
                doucHtml = `<h2>Something went wrong</h2>`;
                break;
        }
        $(".view_doc").html(doucHtml);
    }
});
//close upload model on click cross button
$('.close-view-document').click(function () {
    $('#view_document').hide();
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
    $("#expiry_date").datepicker({
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
