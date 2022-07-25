// index file of document datatable js
$(document).ready(function () {
    $(":checkbox").uniform();
    $('#document_table').show();
    $('.pre_loader').hide();

    
    $('#category_table').DataTable({ 
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": true,
        ordering: false,
        aoColumnDefs: [],
        order: [],
        // "bInfo": false,
        fixedColumns: true
    });
    var serialno;
    var BU_table = $('#document_table').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        ordering: true,
        aoColumnDefs: [],
        columnDefs: [{
                targets: 0,
                searchable: true,
                render: function(data, type) {
                    return type === 'export'? serialno++ :  data;
                  }
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

            {
                targets: 10,
                orderable: false
            },
            {
                targets: 11,
                orderable: false
            },
            {
                targets: 12,
                orderable: false
            },
            // { targets: [0,1,2,6,8]}
        ],
        order: [],
        dom: 'Bfrtip',
        buttons: [{
                className: 'upload_doc btn sbold green',
                id: 'upload_doc',
                title: 'Upload',
                text: "Upload <i class='fa fa-upload' aria-hidden='true'>",
                action: function (e, dt, node, config) {
                    $('#upload_doc_modal').show();
                }
            },
            {
                className: 'btn sbold green',
                extend: 'csvHtml5',
                title: 'Document CSV',
                text: "Download CSV <i class='fa fa-file-excel-o' aria-hidden='true'>",
                //Columns to export
                exportOptions: {
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
                className: 'new_category btn sbold green',
                id: 'new_category',
                title: 'Create New Category',
                text: "Create New Category <i class='fa fa-plus-square' aria-hidden='true'>",
                action: function (e, dt, node, config) {
                    $('#new_category_modal').show();
                }
            }
        ],
        "bInfo": false,
        fixedColumns: true
    });

    BU_table.on( 'order.dt search.dt', function () {
        BU_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            // cell.innerHTML = i+1;
        } );
    } ).draw();


    $('#document_table_filter').css('display', 'none');
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
            if (BU_table.column(3).search() !== $(this).val()) {
                BU_table
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

        $('#filter_folder', this).on('change', function () {
            // alert($(this).val());
            if (BU_table.column(1).search() !== $(this).val()) {
                BU_table
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
            if (BU_table.column(5).search() !== $(this).val()) {
                BU_table
                    .column(5)
                    .search($(this).val())
                    .draw();
            }
        });

    });

    //close create category popup on cross button click
    $('.modal_close').click(function () {
        $('#new_cat_form').trigger("reset");
        $('#new_category_modal').hide();
        $('label.error').remove();
        
        $('#edit_cat_form').trigger("reset");
        $('#edit_category_modal').hide();

        $(".category").removeClass("error");
    })

    // on cancel button close create category popup
    $('.cancel_modal').click(function (event) {
        $('#new_cat_form').trigger("reset");
        $('#new_category_modal').hide();
        $('#edit_cat_form').trigger("reset");
        $('#edit_category_modal').hide();
        $('label.error').remove();
        $(".category").removeClass("error");
    });

    //close activity log popup on cross button click
    $('.activity_close').click(function () {
        $('#activity_log_modal').hide();
    })

    //add validation to create new category form
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 5MB');

    var validateTrue=0;
    var new_cat_form = $("#new_cat_form").validate({
        //   ignore: [],
        onkeyup: false, 
        rules: {
            
            name: {
                required: true,
                remote: {
                    url: "/admin/check/unique/category",
                    type: "post",
                    data: {
                        name: function () {
                            return $("#new_cat_form .category").val();
                        }
                    },
                    complete:function(data){
                        validateTrue=1;
                        if ($('#edit_cat_form').valid()) {
                            
                        }
                    }
                }
            },

           
        },
        messages: {
            
            name: {
                required: "Please enter Category Name.",
                remote : "Company name should be different"
            }
        },
    });

    $(".new-category-btn").click(function (event) {
        // console.log("new_cat_form", new_cat_form);
        // event.preventDefault();
        var self = $("#new_cat_form");
        console.log(new_cat_form.valid());
        if (new_cat_form.valid()) {
            if(validateTrue==1){
                $.ajax({
                    beforeSend: function () {
                        $(".pre_loader").show();
                        $(self).find("input[type='submit']").prop('disabled', true);
                    },
                    url: '/admin/create_category',
                    type: 'POST',
                    data: $(self).serialize(),
                    success: function (response) {
                    
                        $(".pre_loader").hide();
                        // alertSuccess(response.message, 'success');
                        $(self).find("input[type='submit']").prop('disabled', false);
                        $('#new_category_modal').hide();
                        location.reload();
                    },
                    error: function (error) {
                        $(".pre_loader").hide();
                        $(self).find("input[type='submit']").prop('disabled', false);
                        var response = error.responseJSON;
                        if (response.message == 'validation_error') {
                            var errors = response.validation_error;
                            for (var i = 0; i < errors.length; i++) {
                                var errorRow = errors[i];
                                var inputElement = $(self).find("[name='" + errorRow.element + "']");
                                var inputElementId = inputElement[0].id;
                                new_cat_form.invalid[inputElementId] = true;
                                new_cat_form.submitted[inputElementId] = errorRow.message;
                                new_cat_form.errorMap[inputElementId] = errorRow.message;
                                new_cat_form.errorList.push({
                                    message: errorRow.message,
                                    element: inputElement[0],
                                    method: 'unique'
                                });
                                new_cat_form.showErrors(errorRow.obj);
                            }
                        }
                    }
                });
            }
        }
        // if ($("#new_cat_form").valid()) {
        //     $(this).find("input[type='submit']").prop('disabled', true);
        // }
    });



    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 100MB');

    var errorLog = $("#create_document").validate({
        //   ignore: [],
        rules: {
            name: {
                required: true,
                remote: {
                    url: "/admin/check/document/name",
                    type: "post",
                    data: {
                        name: function () {
                            return $("#create_document .title").val();
                        }
                    }
                }
            },
            // expiry_date: {
            //     required: true
            // },
            category: {
                required: true
            },
            owner: {
                required: true
            },
            business_unit: {
                required: true
            },
            department :{
                required: true
            },
            // doc_data: {
            //     extension: "docx|rtf|doc|pdf|xls|xlsx|jpg|jpeg|gif|mp4|mp3||png",
            // },
            file: {
                // accept: "image/*,video/*,audio/*",
                extension: "docx|rtf|doc|pdf|xls|xlsx|jpg|jpeg|gif|mp4|mp3||png",
                filesize: 100000000
            },
            folder: {
                required: true
            },
            url:{
                url: true
            }

        },
        messages: {
            name: {
                required: "Please enter Title.",
                remote: "Document already exist."
            },
            // expiry_date: {
            //     required: "Please enter Exipry Date.",
            // },
            category: {
                required: "Please select Category.",
            },
            owner: {
                required: "Please select Owner.",
            },
            business_unit: {
                required: "Please select Business Unit.",
            },
            department :{
                required: "Please select Department."
            },
            doc_data: {
                accept: "Please upload valid file."

            },
            file: {
                // accept: "Please upload valid file.",
                extension: "Please upload valid file."

            },
            folder: {
                required: "Please select Folder.",
            },
            url:{
                url: "Please enter valid Url"
            }
        },
        //   submitHandler: function(form) {
        //     form.submit();
        //   },
    });

    //edit document form validation
    $("#edit_document").validate({
        //   ignore: [],
        rules: {
            name: {
                required: true,
                remote: {
                    url: "/admin/check/document/name",
                    type: "post",
                    data: {
                        name: function () {
                            return $("#edit_title").val();
                        },
                        id: function () {
                            return $("#doc_id").val();
                        }
                    }
                }
            },
            // expiry_date: {
            //     required: true
            // },
            category: {
                required: true
            },
            owner: {
                required: true
            },
            business_unit: {
                required: true
            },
            department :{
                required: true
            },
            // doc_data: {
            //     extension: "docx|rtf|doc|pdf|xls|xlsx|jpg|jpeg|gif|mp4|mp3||png",
            // },
            file: {
                // accept: "image/*,video/*,audio/*",
                extension: "docx|rtf|doc|pdf|xls|xlsx|jpg|jpeg|gif|mp4|mp3||png",
                filesize: 100000000
            },
            folder: {
                required: true
            },
            url:{
                url: true
            }

        },
        messages: {
            name: {
                required: "Please enter Title.",
                remote: "Document already exist."
            },
            // expiry_date: {
            //     required: "Please enter Exipry Date.",
            // },
            category: {
                required: "Please select Category.",
            },
            owner: {
                required: "Please select Owner.",
            },
            business_unit: {
                required: "Please select Business Unit.",
            },
            department :{
                required: "Please select Department."
            },
            doc_data: {
                accept: "Please upload valid file."
    
            },
            file: {
                // accept: "Please upload valid file.",
                extension: "Please upload valid file."
    
            },
            folder: {
                required: "Please select Folder.",
            },
            url:{
                url: "Please enter valid Url"
            }
    
        }
    });

    /**
     * Share Doc With Supplier
     */
    $("#document_table .share-suplier").on("click", function () {
        var id = $(this).data('id');
        var data = {};
        var self = this;
        var checkboxState = $(this).is(":checked");
        if (checkboxState) {
            data.share_with_supplier = 1;
        } else {
            data.share_with_supplier = 0;
        }
        if (id != '' && id != undefined && id != null) {
            $.ajax({
                beforeSend: function () {
                    $(".pre_loader").show();
                },
                url: '/admin/update/' + id,
                type: 'PUT',
                data: data,
                success: function (response) {
                    $(".pre_loader").hide();
                    alertSuccess(response.message, 'success');
                },
                error: function (error) {
                    $(".pre_loader").hide();
                    if (checkboxState) {
                        $(self).prop("checked", false);
                    } else {
                        $(self).prop("checked", true);
                    }
                    alertError(error.responseJSON.message, 'error');
                }
            });
        }
    });

    /**
     * Share Doc Mobile Device
     */
    $("#document_table .mobile-device").on("click", function () {
        var id = $(this).data('id');
        var data = {};
        var self = this;
        var checkboxState = $(this).is(":checked");
        if (checkboxState) {
            data.Use_in_mobile = 1;
        } else {
            data.Use_in_mobile = 0;
        }
        if (id != '' && id != undefined && id != null) {
            $.ajax({
                beforeSend: function () {
                    $(".pre_loader").show();
                },
                url: '/admin/update/' + id,
                type: 'PUT',
                data: data,
                success: function (response) {
                    $(".pre_loader").hide();
                    alertSuccess(response.message, 'success');
                },
                error: function (error) {
                    $(".pre_loader").hide();
                    if (checkboxState) {
                        $(self).prop("checked", false);
                    } else {
                        $(self).prop("checked", true);
                    }
                    alertError(error.responseJSON.message, 'error');
                }
            });
        }
    });

    //close upload model on click cross button
    $('.upload_close').click(function () {
        $('#create_document').trigger("reset");
        $('#upload_doc_modal').hide();
        $('label.error').remove();
        $(".upload-doc").removeClass("error");
        $('.filename').text('');
    });

    // on cancel button close upload document popup
    $('#upload_cancel').click(function (event) {
        $('#create_document').trigger("reset");
        $('#upload_doc_modal').hide();
        $('label.error').remove();
        $(".upload-doc").removeClass("error");
        $('.filename').text('');
    });

    // on cancel button close edit document popup
    $('#edit_cancel, .edit_close').click(function (event) {
        $('#edit_document').trigger("reset");
        $('#edit_doc_modal').hide();
        $('label.error').remove();
        $(".edit-doc").removeClass("error");
    });
});
$('body').on('click', '.reset', function () {
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('#searching').val('');
    $('.filter').trigger('change');
    $('.filter').trigger('keyup');

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
    $("#expiry_date, .expiry_date").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: 0,
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
function stop_submit() {
    event.preventDefault();
}

// archive document
function delete_doc(id) {
    // alert(id);
    // event.preventDefault();
    var id = id;
    var self = this;
    bootbox.confirm({
        message: "Are you sure you want to archive this document?",
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
                // let id = $(self).attr('data-id');
                $.ajax({
                    url: '/admin/archive-document',
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


// show activity log 
function activity_log(id, title) {
    $('.pre_loader').show();
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    // let id=  $(this).attr('data-id');
    $.ajax({
        url: '/admin/activity_log',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (data) {
            count=0;
            var created_date = data['doc_list'][0]['created_at'];
            $('.log_data').html("");
            if (created_date != null) {
                count++;
                created_date = created_date.substring(0, 10);
                created_date = new Date(created_date);
                    yr = created_date.getFullYear(),
                    month = created_date.getMonth()+ 1,
                    month = created_date.getMonth() < 9 ? '0' + month : month,
                    day = created_date.getDate() < 10 ? '0' + created_date.getDate() : created_date.getDate(),
                    newDate = month + '/' + day + '/' + yr;   
                $('.log_data').append('<tr><th scope="row">'+count+'</th><td>' + newDate + '</td><td>' + data['doc_list'][0]['owner']['vc_fname'] + '</td><td>Created</td></tr>');

            } else {
                created_date = '';
            }
            // console.log(data);
        


            var updated_at = data['doc_list'][0]['updated_at'];
            if (updated_at != null) {
                count++;
                updated_at = updated_at.substring(0, 10);
                updated_at = new Date(updated_at);
                    yr = updated_at.getFullYear(),
                    month = updated_at.getMonth()+ 1,
                    month = updated_at.getMonth() < 9 ? '0' + month : month,
                    day = updated_at.getDate() < 10 ? '0' + updated_at.getDate() : updated_at.getDate(),
                    newDate = month + '/' + day + '/' + yr;
                 $('.log_data').append('<tr><th scope="row">'+count+'</th><td>' + newDate + '</td><td>' + data['doc_list'][0]['owner']['vc_fname'] + '</td><td>Update</td></tr>');
            } else {
                updated_at = '';
            }


            var open_doc = data['open_doc'];
            if (open_doc != null) {
                count++;
                for(i=0; i<open_doc.length; i++){
                    updated_at = open_doc[i]['created_at'].substring(0, 10);
                    updated_at = new Date(updated_at);
                        yr = updated_at.getFullYear(),
                        month = updated_at.getMonth()+ 1,
                        month = updated_at.getMonth() < 9 ? '0' + month : month,
                        day = updated_at.getDate() < 10 ? '0' + updated_at.getDate() : updated_at.getDate(),
                        newDate = month + '/' + day + '/' + yr;
                    $('.log_data').append('<tr><th scope="row">'+count+'</th><td>' + newDate + '</td><td>' + data['doc_list'][0]['owner']['vc_fname'] + '</td><td>View</td></tr>');
                }
            }



            $('.document_title').text(title);
            $('.pre_loader').hide();
            $('#activity_log_modal').show();
        

        }
    });
}

var edit_owner_id="";
var edit_department_id="";
var edit_project_id="";
// edit document modal data
function edit_doc(id) {
    edit_owner_id=""; 
    edit_department_id="";
    edit_project_id="";
    $('.pre_loader').show();
    event.preventDefault();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/admin/edit_document',
        type: 'GET',
        data: {
            _token: CSRF_TOKEN,
            id: id
        },
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            $("#business_unit_edit").val(data['business_unit_id']).change();
           
            var date = new Date(data['expires_at']),
                yr = date.getFullYear(),
                month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
                day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
                month = parseInt(month) + 1;
            expiryDate = month + '/' + day + '/' + yr;
            if (data['expires_at'] != null) {
                $('#edit_expiry_date').val(expiryDate);
            } else {
                $('#edit_expiry_date').val('');
            }
            
            
            // file_name
            $("#edit_title").val(data['title']);
            $("#edit_category").val(data['category_id']);
            $('#doc_id').val(data['id']);
            $('#doc_folder').val(data['folder_id']);
            $('#doc_description').val(data['description']);
            
            edit_owner_id=data['owner_id']; 
            edit_department_id=data['department_id'];
            edit_project_id=data['project_id'];

            
            if(data['file_type']==6){
                $("#url_edit").val(data['file_name']);
            
            }else{
                $("#edit_filename").html(data['file_name']+'<span class="close-filename">&times;</span>');
                
            }
            
            // $("#owner_list option").length
             
            setTimeout(function(){
                
                $('#owner_list_edit').val(data['owner_id']).change();
                $("#department_edit").val(data['department_id']).change();
                $("#project_edit").val(data['project_id']).change();


            },
            3050);
            setTimeout(function(){
                
                $('.pre_loader').hide();
                $('#edit_doc_modal').show();
            },
           5000);

        }
    });
}

function set_edit_document_param(){
    $('#owner_list_edit').val(edit_owner_id).change();
    $("#department_edit").val(edit_department_id).change();
    $("#project_edit").val(edit_project_id).change();
    setTimeout(function(){
        $('#owner_list_edit').val(edit_owner_id).change();
        $("#department_edit").val(edit_department_id).change();
        $("#project_edit").val(edit_project_id).change();  
    },
    1050);
    setTimeout(function(){
        $('#owner_list_edit').val(edit_owner_id).change();
        $("#department_edit").val(edit_department_id).change();
        $("#project_edit").val(edit_project_id).change();  
    },
    2050);
    setTimeout(function(){
        $('#owner_list_edit').val(edit_owner_id).change();
        $("#department_edit").val(edit_department_id).change();
        $("#project_edit").val(edit_project_id).change();  
    },
    4050);
    setTimeout(function(){
        $('#owner_list_edit').val(edit_owner_id).change();
        $("#department_edit").val(edit_department_id).change();
        $("#project_edit").val(edit_project_id).change();  
    },
    5000);
    
}



function ValidateExtension(filesize) {
    // console.log(filesize);
    $(".file_error").remove();
    var allowedFiles = [".doc", ".docx", ".pdf", ".png", ".jpg", ".gif", ".xls", ".xlsx", ".mp4", ".mp3"];
    var fileUpload = $('#doc_data').val() != ''? $('#doc_data').val() : $('#doc_file').val();
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    // console.log(fileUpload);
    if(fileUpload != ''){
        if (!regex.test(fileUpload)) {
            $("#doc_file").after('<label class=" file_error">Please upload valid file.</label>');
            return false;
        } else if (filesize > 100000000) {
            $("#doc_file").after('<label class=" file_error">File size must be less than 100MB.</label>');
            return false;
        } else {
            $('.file_error').remove();
            return true;
        }
    }
}


// Drag and Drop functionality in upload document

$(document).ready(function () {
    let file = '';
    $('.uploadfilesec').on('dragover', function () {
        $(this).addClass('file_drag_div_over');
        return false;
    });

    $('.uploadfilesec').on('dragleave', function () {
        $(this).removeClass('file_drag_div_over');
        return false;
    });

    $('.uploadfilesec').on('drop', function (e) {
        e.preventDefault();
        $(this).removeClass('file_drag_div_over');
        file = e.originalEvent.dataTransfer.files;
        $('#doc_data').val('C:\\fakepath\\' + file[0]['name']);
        $('.filename').html(file[0]['name']+ ' <span class="close-filename">&times;</span>');
        $('#doc_file').val('');
    });

    $("body").on('click', '.close-filename', function (e) {
        $('#doc_data').val('');
        $('.filename').html('');
        $('#doc_file').val('');
    });

    $("body").on('click', '.save_document', function (e) {
        e.preventDefault();

        var formData = new FormData($('#create_document')[0]);
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        formData.append('_token', CSRF_TOKEN);
        var filesize = '';
        $('.file_error').remove();
        if (file != '' && $('#doc_file').val() == '') {
            filesize = file[0]['size'];
            formData.append('file', file[0]);
            // formData.append('filename', file[0]['name']);
            // formData.append('filetype', file[0]['type']);
        }
        

        if ($('.filename').text() != '' && $('.online-url').val() != '') {
            $("#doc_file").after('<label class=" file_error">Please only upload the file or enter the url.</label>');
        }else if($('.filename').text() != '' && $('.online-url').val() == ''){
            if (ValidateExtension(filesize) || $('#doc_file').val() != '') {
                if ($("#create_document").valid()) {
                    $('.file_error').remove();
                    upload_doc(e, formData);
                }
            }
        }else if($('.filename').text() == '' && $('.online-url').val() != ''){
            if ($("#create_document").valid()) {
                $('.file_error').remove();
                upload_doc(e, formData);
            }
        }else {
            $("#doc_file").after('<label class=" file_error">Please select file or enter url.</label>');
        }
    });

    $("body").on('click', '.edit_document', function (e) {
        e.preventDefault();

        console.log("filename"+$('.filename').text());
        console.log("file"+document.getElementById('edit_doc_file').files);
        console.log("url online"+$('#url_edit').val());


        var formElement = document.getElementById('edit_document');
        var formData = new FormData(formElement);
        $('.file_error').remove();
        console.log($('#edit_doc_file').val());
        console.log(document.getElementById('edit_doc_file').files);
        console.log(document.getElementById('edit_doc_file').files.length);
            if(document.getElementById('edit_doc_file').files.length !=0){
                formData.append('edit_doc_files', document.getElementById('edit_doc_file').files[0]);
                console.log(document.getElementById('edit_doc_file').files);
                console.log(document.getElementById('edit_doc_file').files[0]);
                $("#editremovefile").val(document.getElementById('edit_doc_file').files[0]);
                filesize = document.getElementById('edit_doc_file').files[0]['size'];

            }
        if ($('.filename').text() != '' && $('#url_edit').val() != '') {
            $(".doc_file_error").after('<label class=" file_error">Please only upload the file or enter the url.</label>');
        }else if(document.getElementById('edit_doc_file').files.length!= 0 && $('#url_edit').val() == ''){
            
            if (ValidateExtension(filesize) || $('#edit_doc_file').val() != '') {
                if ($("#edit_document").valid()) {
                    $('.file_error').remove();
                    // console.log($("#editremovefile").val());
                    // console.log(document.getElementById('editremovefile').files)
                    // console.log(formData);

                    $(".edit_document").attr('disabled', true);
                    $("#edit_cancel").attr('disabled', true);

                    $('#pre_loader').show();
                    $('#edit_document').submit();
                }
            }

        }else if(document.getElementById('edit_doc_file').files.length == 0 && $('#url_edit').val() != ''){
            if ($("#edit_document").valid()) {
                $('.file_error').remove();
                $(".edit_document").attr('disabled', true);
                $("#edit_cancel").attr('disabled', true);
                // $('.upload-loader').css('display', 'inline-block');
                $('#pre_loader').show();
                $('#edit_document').submit();
            }
        }else if($('.filename').text() == '' && $('#url_edit').val() == '') {
            console.log("both empty")
            $(".doc_file_error").after('<label class=" file_error">Please select file or enter url.</label>');
        }else{
            console.log("sd");
            if ($("#edit_document").valid()) {
                $('.file_error').remove();
                $(".edit_document").attr('disabled', true);
                $("#edit_cancel").attr('disabled', true);
                // $('.upload-loader').css('display', 'inline-block');
                $('#pre_loader').show();
                $('#edit_document').submit();
            }
        }

        return true;

        if ($("#edit_document").valid()) {






            $('#edit_document').submit();
        }
    });

});

// upload document  
function upload_doc(e, formData) {
    e.preventDefault();
    $(".save_document").attr('disabled', true);
    // $(".save_document").attr('type', 'hidden');
    $("#upload_cancel").attr('disabled', true);
    $('#pre_loader').show();
    // alert('here');
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: APP_URL+ '/admin/create_document',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            location.reload(true);
        },
        error: function (error) {
            $(".save_document").attr('disabled', false);
            $("#upload_cancel").attr('disabled', false);
            $('#pre_loader').hide();
            alertError('Something went wrong!');
            // $.each(error.responseJSON.errors, function (key, value) {
            //     $('input[name="' + key + '"]').parent().find('span.error').html(value).addClass('active').show();
            //     $('select[name="' + key + '"]').parent().find('span.error').html(value).addClass('active').show();
            // });
        }
    });
}

//show filename on selecting doc file

$('#doc_file,#edit_doc_file').on('change', function () {
    var doc_name = $(this).val().replace(/C:\\fakepath\\/i, '');
    $('.filename').html(doc_name+ ' <span class="close-filename">&times;</span>');
    $('#doc_data').val('');
})


/**
 * View Document
 */
$('body').on('click', '.view-document', function () {
    $('pre_view_loader').show();
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
            case 6:
                newlink=document.doc_link.replace("", APP_URL)
                // console.log(APP_URL);
                // console.log(newlink);
                window.open(document.doc_link, '_blank');
                $("#view_document").hide();
                break;
            default:
                doucHtml = `<h2>Something went wrong</h2>`;
                break;
        }
        $(".view_doc").html(doucHtml);
        var document_id = document.document_id;

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
       postdata= { _token: CSRF_TOKEN, 'document_id': document_id };
        $.ajax({
            url: APP_URL+ '/admin/view_document',
            type: 'POST',
            data: JSON.stringify(postdata),
            processData: false,
            // contentType: false,
            
    	contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response) {
                // location.reload(true);
            },
            error: function (error) {
            }
        });
        $('pre_view_loader').hide();
    }
});


//close view document model on click cross button
$('.close-view-document').click(function () {
    $('#view_document').hide();
});




// edit document modal data
function edit_category(id,name) {
    $('.pre_loader').show();
    event.preventDefault();
    $("#category_id").val(id);

   $("#category_name").val(name);
   $('#new_cat_form').trigger("reset");
   $('#new_category_modal').hide();
   $('label.error').remove();
   $(".category").removeClass("error"); 
   $('.pre_loader').hide();

   $("#edit_category_modal").show();
}

var edit_cat_form = $("#edit_cat_form").validate({
    //   ignore: [],
    rules: {
        name: {
            required: true,
            remote: {
                url: "/admin/check/unique/category",
                type: "post",
                data: {
                    name: function () {
                        return $("#edit_cat_form .category").val();
                    },
                    id: function () {
                        return $("#category_id").val();
                    }
                }
            }
        },
    },
    messages: {
        name: {
            required: "Please enter Category Name.",
            remote : "Company name should be different"
        }
    },
});

$("#edit_cat_form").submit(function (event) {
    // console.log("edit_cat_form", edit_cat_form);
    event.preventDefault();
    var self = this;
    if (edit_cat_form.valid()) {
        $.ajax({
            beforeSend: function () {
                $(".pre_loader").show();
                $(self).find("input[type='submit']").prop('disabled', true);
            },
            url: '/admin/edit_category',
            type: 'POST',
            data: $(self).serialize(),
            success: function (response) {
                $(".pre_loader").hide();
                // alertSuccess(response.message, 'success');
                $(self).find("input[type='submit']").prop('disabled', false);
                $('#edit_category_modal').hide();
                location.reload();
            },
            error: function (error) {
                $(".pre_loader").hide();
                $(self).find("input[type='submit']").prop('disabled', false);
                var response = error.responseJSON;
                if (response.message == 'validation_error') {
                    var errors = response.validation_error;
                    for (var i = 0; i < errors.length; i++) {
                        var errorRow = errors[i];
                        var inputElement = $(self).find("[name='" + errorRow.element + "']");
                        var inputElementId = inputElement[0].id;
                        edit_cat_form.invalid[inputElementId] = true;
                        edit_cat_form.submitted[inputElementId] = errorRow.message;
                        edit_cat_form.errorMap[inputElementId] = errorRow.message;
                        edit_cat_form.errorList.push({
                            message: errorRow.message,
                            element: inputElement[0],
                            method: 'unique'
                        });
                        edit_cat_form.showErrors(errorRow.obj);
                    }
                }
            }
        });
    }
    // if ($("#new_cat_form").valid()) {
    //     $(this).find("input[type='submit']").prop('disabled', true);
    // }
});



// archive document
function delete_category(id) {
    // alert(id);
    // event.preventDefault();
    var id = id;
    var self = this;
    bootbox.confirm({
        message: "Are you sure you want to delete this category?",
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
                // let id = $(self).attr('data-id');
                $.ajax({
                    url: '/admin/delete_category',
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        id: id
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        if(data.status="failed"){
                            alertError("Category can't be deleted, it is mapped to documents");
                            // alertSuccess();
                            return true;
                        }

                        location.reload(true);
                    }
                });
            }
        }
    });
}

$(".new_cat_form").hide();
$(".hideshow_create_category").click(function (){
    console.log("asd");
    $(".new_cat_form").toggle();
})