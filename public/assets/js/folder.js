// const { event } = require("jquery");

$(document).ready(function () {

    function updateTreeParent(id, row) {
        $('.pre_loader').show();
        setTimeout(function () {
            var parentRow = $(row).treegrid('getParent');
            var perentId = $(parentRow[0]).attr('data-id');
            if (perentId == undefined || perentId == null) {
                var perentId = null;
            }
            var data = {
                'parent_folder_id': perentId
            };
            if (id != undefined && id != null) {
                $.ajax({
                    url: "/admin/update/" + id,
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        $('.pre_loader').hide();
                    },
                    error: function (error) {
                        $('.pre_loader').hide();
                    }
                });
            }
        }, 100);
    }

    /**
     * Tree grid
     */
    // $('#folder_table').treegrid({
    //     treeColumn: 1,
    //     enableMove: true,
    //     initialState: 'collapsed',
    //     onMove: async function (item, helper, position) {
    //         var selfId = $(item[0]).attr('data-id');
    //         var res = updateTreeParent(selfId, item[0]);
    //         return true;
    //     }
    // });


    $('#folder_table').show();
    $('.pre_loader').hide();
    var BU_table = $('#folder_table').DataTable({
        sPaginationType: 'full_numbers',
        paging: false,
        "autoWidth": false,
        aoColumnDefs: [],
        columnDefs: [{
                orderable: false,
                targets: 0,
                searchable: true
            },
            {
                orderable: false,
                targets: 1,
                searchable: true
            },
            {
                orderable: false,
                targets: 2,
                searchable: true
            },
            {
                orderable: false,
                targets: 3
            },
        ],
        order: [],
        dom: 'Bfrtip',
        buttons: [],
        "bInfo": false,
        fixedColumns: true
    });


    $('#folder_table_filter').css('display', 'none');
    $(".filterheadfolder").each(function (i) {
        var title = $(this).text();
        var dept_value = $('#dept_value').val();
        var assignee_value = $('#assignee_value').val();
        $(this).html("");
        if (i == 1) {
            $(this).html('<input class="filter form-control" id="searching" type="text"  placeholder="Search by Folder Name, Sub Folder Name" />');
        } else if (i == 2) {
            $(this).html('<select class="filter form-control dropdown-filter" id="filter_doc_no"><option value="">' + title + '</option></select>');
        } else if (i == 3) {
            $(this).html('<button class="btn btn-success reset filter">Reset Filter</button>');
        }
    });

    $('#searching').unbind().on('keyup', function () {
        var searchTerm = this.value.toLowerCase();
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            //search in column
            if (~data[1].toLowerCase().indexOf(searchTerm)) return true;
            return false;
        })
        BU_table.draw();
        $.fn.dataTable.ext.search.pop();
    })

    $('#filter_doc_no', this).on('change', function () {
        // alert($(this).val());
        if (BU_table.column(2).search() !== $(this).val()) {
            BU_table
                .column(2)
                .search(this.value)
                .draw();
        }
    });

   var validateTrue=0;
    // validate create folder form
    $("#folder_form").validate({
        onkeyup: false, 
        rules: {
            name: {
                required: true,
                maxlength: 250,
                remote: {
                    url: "/admin/check/folder/name",
                    type: "post",
                    data: {
                        name : function () {
                            return $("#folder_form .folder_name").val();
                        }
                    },
                    complete:function(data){
                        validateTrue=1;
                        if ($('#createSupplier').valid()) {
                            
                        }
                    }
                }
            },
            // 'sub_folder_name': {
            //     remote: {
            //         url: "/admin/check/folder/name",
            //         type: "post",
            //         data: {
            //             name : function () {
            //                 return $("#folder_form input[name=sub_folder_name]").val();
            //             }
            //         }
            //     }
            // }
        },
        messages: {
            name: {
                required: "Please enter name of folder.",
                maxlength: "Name must be at least 250 characters",
                remote: "Folder already exists."
            },
            // sub_folder_name: {
            //     remote: "Folder is already exists."
            // },
        }
    });

    $("body").on('click', '#save_folder_form', function (e) {
        e.preventDefault();
        if ($("#folder_form").valid()) {
            if(validateTrue==1){
                if ($('#folder_form').valid()) {
                    var formData = new FormData($('#folder_form')[0]);
                    savefolderOrSubfolder(formData);
                }
            }

        }
    });

    // validate rename folder form 
    $("#rename_folder_form").validate({
        rules: {
            'folder_name': {
                required: true,
                maxlength: 250,
            }
        },
        messages: {
            folder_name: {
                required: "Please enter Folder name.",
                maxlength: "Name must be at least 250 characters",
            },
        }
    });

    // validate create sub folder form
    $("#sub_folder_form").validate({
        ignore:":hidden",
        rules: {
            'folder_name': {
                required: true,
                maxlength: 250,
                remote: {
                    url: "/admin/check/folder/name",
                    type: "post",
                    data: {
                        name : function () {
                            return $("#sub_folder_form input[name=folder_name]").val();
                        }
                    }
                }
            },
            'sub_folder_name': {
                required: true,
                maxlength: 250,
                remote: {
                    url: "/admin/check/folder/name",
                    type: "post",
                    data: {
                        name : function () {
                            return $("#sub_folder_form input[name=sub_folder_name]").val();
                        }
                    }
                }
            }
        },
        messages: {
            folder_name: {
                required: "Please enter Folder name.",
                maxlength: "Name must be at least 250 characters",
                remote: "Folder is already exists."
            },
            sub_folder_name: {
                required: "Please enter Sub Folder name.",
                maxlength: "Name must be at least 250 characters",
                remote: "Folder is already exists."
            },
        }
    });
    

    //cancel rename folder modal 
    $('#cancel_modal , .modal_close').click(function (event) {
        $('#rename_folder_form').trigger("reset");
        $('#rename_folder').hide();
        $('label.error').remove();
        $(".folder_name").removeClass("error");
    });
});

$(function () {
    for (i = 1; i <= 20; i++) {
        $("#filter_doc_no").append($('<option></option>').val(i).html(i));
    }
});

$('body').on('click', '.reset', function () {
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('#searching').val('');
    // $('.filter').trigger('change');
    $('.filter').trigger('change');
    $('.filter').trigger('keyup');
});

$("body").on('click', '.modal_close', function (e) {
    e.preventDefault();
    $("#create_subfolder_folder").hide();
    $('.folder_name').val('');
    $('.sub_folder_name').val('');
    $('.open-popup-sub-main').parent().removeClass("checked");
})

$("body").on('click', '#create-folder', function (e) {
    e.preventDefault();
    $("#create_folder_modal").show();
    $("#create_folder_modal input[name='folder_name']").val('');
    $("#create_folder_modal input[name='sub_folder_name']").val('');
})

$("body").on('click', '.open-popup-sub-main', function (e) {
    var folder_id = $(this).attr('data-id');
    var parent_folder_id = $(this).attr('data-parent');
    e.preventDefault();
    $("#create_subfolder_folder").show();
    $("#create_subfolder_folder input[name='folder_id']").val(folder_id);
    $("#create_subfolder_folder input[name='folder_name']").val('');
    $("#create_subfolder_folder input[name='sub_folder_name']").val('');
    $("#create_subfolder_folder input[name='parent_folder_id']").val(parent_folder_id);
})




/**
 * validation on create folder & subfolder model
 */



$("body").on('click', '.sub-folder-submit-btn', function (e) {
    e.preventDefault();
    if($("#sub_folder_form").valid()) {
        $('.folder_error').text('');
        var formData = new FormData($('#sub_folder_form')[0]);
        saveSubfolder(formData);
    }
});

$("body").on('click', '.sub-folder-btn', function (e) {
    $(".sub-folder-textfeild").show();
    $(".folder-textfeild").hide();
    $(".sub-folder-submit-btn").show();
    $('.folder_name').val('');
    $('.folder_error').text('');
    $('.error').text('');
    $('.folder_name').removeClass('error');
});
$("body").on('click', '.modal_close', function (e) {
    $("#create_folder_modal").hide();
    $('.folder_error').text('');
});


function savefolderOrSubfolder(formData) {
    $("#save_folder_form").prop('disabled', true);
    $.ajax({
        url: save_folder,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            $("#save_folder_form").prop('disabled', false);
            alertSuccess(response.message);
            $("#create_folder_modal").hide();
            $('.folder-structure').html(response.folderStructure);
        },
        complete: function(){
            $( ".folders").draggable({
                revert: "invalid",
                zIndex: 99,
                helper: "clone",
                cursor: "move"
                // start: function() {
                //     console.log("asd");
                // },
                // drag: function() {
                // console.log("asd");
                // },
                // stop: function() {
                // console.log("easda");
                // }
            
            });
        
        
            
            $( ".folder-icon").droppable({
           
                accept:".folders",
                drop: function( event, ui ) {
                    $('.pre_loader').show();
                    // console.log("rrrrrrrrr");
                    // console.log(event);
                    console.log(ui);
                    // console.log(ui.draggable[0].classList[3]);
                    // console.log(this);
                    // console.log($(this)[0].classList[2]);
                    id=ui.draggable[0].classList[4];
                    parent_folder_id=$(this)[0].classList[2];
                    return true;
                    $.ajax({
                            url: '/admin/update/'+ id,
                            type: 'post',
                            data: {parent_folder_id : parent_folder_id},
                            dataType: 'JSON',
                            success: function (response) {
                                var resp = response.message;
                                alertSuccess(resp);
                            },
                            complete: function(){
                                
                                $(ui.draggable[0]).fadeOut(function() {});
                    
                                $('.pre_loader').hide();
                            },
                            error: function (error) {
                                errorHandler(error);
                            }
                    });
        
                }
            });

            
            
            $('.pre_loader').hide();
        },
        error: function (error) {
            errorHandler(error);
        }
    });
}

//append

$("body").on('click', '.folder-btn', function (e) {
    e.preventDefault();
    $(".folder-textfeild").show();
    $(".sub-folder-textfeild").hide();
    $(".sub-folder-submit-btn").show();
    $('.sub_folder_name').val('');
    $('.folder_error').text('');
    $('.error').text('');
    $('.folder_name').removeClass('error');
})

// $("body").on('click', '.sub-folder-btn', function (e){
//   e.preventDefault();
//   $(".sub-folder-textfeild").show();
//   $(".folder-textfeild").hide();
//   $(".sub-folder-btn").show();
// })

// show rename folder
function rename_folder(id, name) {
    $('.pre_loader').show();
    $('#folder_id').val(id);
    $('.folder_name').val(name);
    $('.pre_loader').hide();
    $('#rename_folder').show();
    
}

// rename folder
function edit_folder(){
    // alert('here');
    $('.pre_loader').show();
    id = $('#folder_id').val();
    name = $('#folder_name').val();
    $.ajax({
        url: '/admin/rename_folder/'+id,
        type: 'PUT',
        data: {name : name},
        dataType: 'JSON',
        success: function (response) {
            $('#rename_folder').hide();
            var resp = response.message;
            alertSuccess(resp);
            $('.folder-structure').html(response.folderStructure);
        },
        complete: function(){
            $('.pre_loader').hide();
        },
        error: function (error) {
            errorHandler(error);
        }

    });
}

// Delete folder
function delete_folder(self ,id) {
    
    var test =  $(self).closest("div.folders");

    bootbox.confirm({
        message: "Are you sure you want to delete the folder?",
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
                $('.pre_loader').show();
                $.ajax({
                    url: '/admin/delete_folder/'+ id,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function (response) {
                        alertSuccess(response.message);
                        test.remove();
                    },
                    complete: function(){
                        $('.pre_loader').hide();
                    },
                    error: function (error) {
                        errorHandler(error);
                    }
                });
            }
        }
    });
}





// Delete folder
function restore_folder(self ,id) {
    
    var test =  $(self).closest("div.folders");

    bootbox.confirm({
        message: "Are you sure you want to restore the folder?",
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
                $('.pre_loader').show();
                $.ajax({
                    url: '/admin/restore_folder/'+ id,
                    type: 'post',
                    dataType: 'JSON',
                    success: function (response) {
                        alertSuccess(response.message);
                        test.remove();
                    },
                    complete: function(){
                        $('.pre_loader').hide();
                    },
                    error: function (error) {
                        errorHandler(error);
                    }
                });
            }
        }
    });
}



$("body").on('click', '#openupload_doc_modal', function (e) {
    e.preventDefault();
    $("#upload_doc_modal").show();

})