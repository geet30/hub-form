// index file of document datatable js
$(document).ready(function () {
    $('#faq_table').show();
    $('.pre_loader').hide();
    var BU_table = $('#faq_table').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        ordering: false,
        aoColumnDefs: [],
        columnDefs: [{
                width: "10%",
                targets: 0
            },
            {
                width: "30%",
                targets: 1,
                searchable: true
            },
            {
                targets: 2,
                searchable: true
            },
            {
                targets: 3,
                searchable: true
            },
            // { targets: [0,1,2,6,8]}
        ],
        order: [],
        dom: 'Bfrtip',
        // buttons: [
        //     {
        //         className: ' btn sbold green',
        //         id: '',
        //         title: 'Create FAQ',
        //         text: "Create <i class='fa fa-plus-square' aria-hidden='true'>",
        //         action: function (e, dt, node, config)
        //         {
        //             // $('#upload_doc_modal').show();
        //         }
        //     }
        // ],
        "bInfo": false,
        fixedColumns: true
    });
    $('#faq_table_filter').css('display', 'none');
    $(".filterhead").each(function (i) {
        var title = $(this).text();
        var dept_value = $('#dept_name').val();
        var cat_name = $('#cat_name').val();
        $(this).html("");
        if (i == 0) {
            $(this).html('<input class="filter form-control" id="searching" type="text"  placeholder="Search" />');
        } else if (i == 1) {
            $(this).html('<select class="filter form-control dropdown-filter" id="filter_status"><option value="">Status</option><option value="Active ">Active</option><option value="In Active">In Active</option></select>');
        } else if (i == 2) {
            $(this).html('<button class="btn btn-success reset filter">Reset Filter</button>');
        }

        $('#filter_status', this).on('change', function () {
            if (BU_table.column(3).search() !== $(this).val()) {
                BU_table
                    .column(3)
                    .search("^" + this.value, true, true, true)
                    .draw();
            }
        });

        $('#searching').unbind().on('keyup', function () {
            var searchTerm = this.value.toLowerCase();
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                //search in column
                if (~data[0].toLowerCase().indexOf(searchTerm)) return true;
                if (~data[1].toLowerCase().indexOf(searchTerm)) return true;
                return false;
            })
            BU_table.draw();
            $.fn.dataTable.ext.search.pop();
        });
    });

    // validate add faq and edit faq form
    $("#new_faq_form").validate({
        //   ignore: [],
        rules: {
            faqs: {
                required: true
            },
            answer: {
                required: true
            },
            status: {
                required: true
            },
        },
        messages: {
            faqs: {
                required: "Please enter Question.",
            },
            answer: {
                required: "Please enter Answer."
            },
            status: {
                required: "Please select Status."
            },
        },
    });

    $("#edit_faq_form").validate({
        //   ignore: [],
        rules: {
            faqs: {
                required: true
            },
            answer: {
                required: true
            },
            status: {
                required: true
            },
        },
        messages: {
            faqs: {
                required: "Please enter Question.",
            },
            answer: {
                required: "Please enter Answer."
            },
            status: {
                required: "Please select Status."
            },
        },
    });


    //validate terms and condition form 
    $("#add_term_condition").validate({
        //   ignore: [],
        rules: {
            term_condition: {
                required: true,
                accept: 'pdf'
            }
        },
        messages: {
            term_condition: {
                required: "Please upload file for terms and condition.",
                accept: "Only Pdf file is allowed."
            },
        },
    });

    //validate privacy policy form 
    $("#add_privacy_policy").validate({
        //   ignore: [],
        rules: {
            privacy_policy: {
                required: true,
                accept: 'pdf'
            }
        },
        messages: {
            privacy_policy: {
                required: "Please upload file for Privacy Policy.",
                accept: "Only Pdf file is allowed."
            },
        },
    });
});

$('body').on('click', '.reset', function () {
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('#searching').val('');
    $('.filter').trigger('change');
});

// on cancel button close add new faq modal
$('.cancel_modal , .add_modal_close').click(function (event) {
    $('#new_faq_form').trigger("reset");
    $('#new_faq_modal').hide();
    $('label.error').remove();
    $(".faq").removeClass("error");
});

// on cancel button close edit faq modal
$('.cancel_edit_modal , .edit_modal_close').click(function (event) {
    $('#edit_faq_form').trigger("reset");
    $('#edit_faq_modal').hide();
    $('label.error').remove();
    $(".faq").removeClass("error");
});

//add new faq
function add_faq() {
    $('#new_faq_modal').show();
}

//edit faq form
function edit_faq(id) {
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    // let faq_id=  $(this).attr('data-id');
    $.ajax({
        url: '/admin/edit_faq',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            if (data != '') {
                $('#edit_faq_modal').show();
                $('#edit_faqs').val(data['faqs']);
                $('#edit_ans').val(data['answer']);
                $('#faq_id').val(data['id']);
                if(data['status'] == 1){
                    $("#active").attr('checked', 'checked');
                    $("#inactive").removeAttr('checked');
                    $("#active").parent().addClass('checked');
                    $("#inactive").parent().removeClass('checked');
                }else{
                    $("#inactive").attr('checked', 'checked');
                    $("#active").removeAttr('checked', '');
                    $("#inactive").parent().addClass('checked');
                    $("#active").parent().removeClass('checked');
                }
            } else {
                location.reload(true);
            }

        }
    });
}

// delete faq 
function delete_faq(id) {
    // event.preventDefault();
    var self = this;

    bootbox.confirm({
        message: "Are you sure you want to delete this FAQ ?",
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
                    url: '/admin/delete_faq',
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
}
