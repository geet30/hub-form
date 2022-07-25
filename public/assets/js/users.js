$(document).ready(function () {

    $(":checkbox").uniform();
    $(":radio").uniform();
    $('#users_table').show();
    $('.pre_loader').hide();
    var serialno;
    var BU_table = $('#users_table').DataTable({
        deferLoading: true,
        sPaginationType: 'full_numbers',
        paging: true,
        "autoWidth": false,
        aoColumnDefs: [
        ],
        // ordering: false,
        aoColumnDefs: [],
        columnDefs: [{
            orderable: false,
            searchable: false,
            targets: 0,
            width: "25px",
            render: function (data, type) {
                return type === 'export' ? serialno++ : data;
            }

        },
        {
            orderable: false,
            targets: 1,
            width: "25px"
        },
        {
            orderable: false,
            targets: 7
        },
        {
            orderable: false,
            targets: 8
        },
        {
            orderable: false,
            targets: 9
        }
        ],
        order: [],
        dom: 'Blfrtip',
        buttons: [
            {
                className: 'create_user',
                id: 'create_user',
                title: 'Create User',
                text: "Create User",
                action: function (e, dt, node, config) {
                    //This will send the page to the location specified
                    window.location.href = APP_URL + '/admin/cms/users/create';
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    columns: [0, 2, 3, 4, 5, 6, 7, 8],
                    orthogonal: "export",
                    rows: function (idx, data, node) {
                        serialno = 1;
                        return true;
                    }
                }
            },
        ],
    });

    BU_table.on('order.dt search.dt', function () {
        BU_table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();


    $('#users_table_filter').css('display', 'none');
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

    //Archive user
    $(".archive_user").click(function (event) {
        var self = this;
        console.log($(this));
        console.log($(this));

        // console.log(gloable_role_id);
        // console.log(gloable_dapetment_id);
        if(typeof gloable_role_id !="undefined"){
            $("select[name=i_ref_dep_id]").val(gloable_dapetment_id).change();
            $("#role").val(gloable_role_id);
        }
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to archive this User ?",
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
                $('.pre_loader').hide();
                if (result) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let id = $(self).attr('data-id');
                    $('.pre_loader').show();
                    $.ajax({
                        url: APP_URL + '/admin/cms/users/' + id,
                        type: 'DELETE',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            $('.pre_loader').hide();
                            console.log($(self).attr('id'));
                            if ($(self).attr('id') != "inlineRadio1") {
                                location.reload(true);
                            } else {

                                $("select[name=i_ref_dep_id]").val(gloable_dapetment_id).change();
                                $("#role").val(gloable_role_id);
                                $("#role_has_user").hide();
                            }
                            // $('#users_table').dataTable().api().row('.selected').remove().draw();
                        },
                        error: function (error) {
                            alertError('Something Went Wrong!');
                        }
                    });
                } else {
                    if ($(this).attr('id') == "inlineRadio1") {
                        $(this).prop('checked', false);
                        $($("#uniform-inlineRadio1").children()).removeClass('checked');
                        $("select[name=i_ref_dep_id]").val(gloable_dapetment_id).change();
                        $("#role").val(gloable_role_id);
                    }
                }
            }
        });
    });

    //restore supplier
    $(".restore_user").click(function (event) {
        event.preventDefault();
        var self = this;
        event.preventDefault();

        bootbox.confirm({
            message: "Are you sure you want to restore this User?",
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
                $('.pre_loader').hide();
                if (result) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let id = $(self).attr('data-id');
                    $('.pre_loader').show();
                    $.ajax({
                        url: APP_URL + '/admin/cms/users/check_role_avliable',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (dataa) {
                            $('.pre_loader').hide();
                            if (dataa.status == "done") {
                                $('.pre_loader').show();
                                $.ajax({
                                    url: APP_URL+'/admin/cms/users/restore',
                                    type: 'POST',
                                    data: {
                                        _token: CSRF_TOKEN,
                                        id: id
                                    },
                                    dataType: 'JSON',
                                    success: function (data) {
                                        $('.pre_loader').hide();
                                        location.reload(true);
                                    },
                                    error: function (error) {
                                        alertError('Something Went Wrong!');
                                    }
                                });


                            } else {
                                
                                $('.pre_loader').show();

                                $.ajax({
                                    url: APP_URL+'/admin/cms/roles/getrole_by_business_department',
                                    type: 'POST',
                                    data: {
                                        _token: CSRF_TOKEN,
                                        business: dataa.data.users_details.i_ref_bu_id,
                                        department: dataa.data.users_details.i_ref_dep_id,
                                    },
                                    dataType: 'JSON',
                                    success: function (response) {
                                        console.log(response);
                                        console.log($("#role"));
                                        roles=response.roles;
                                        for (i = 0; i < roles.length; i++) {
                    
                                            if(roles[i].user_detail == null || roles[i].user_detail == ''){
                                                
                                                var role_name = roles[i]['vc_name'];
                                                console.log(role_name);
                                                var role_val = roles[i]['id'];
                                                $("#role").append($('<option></option>').val(role_val).html(role_name));
                                            }
                                        }
                                        if(roles.length==0){
                                            $("#role").after('<label for="vc_fname" generated="true" class="error">Role not Avliable, Pease first create New Role</label>');
                                        }
                                        
                                            $("#restore_user_id").val(id);
                                            $('.pre_loader').hide();
                                            $("#role_has_user").show();
                                            
                                    },
                                    error: function (error) {
                                        alertError('Something Went Wrong!');
                                    }
                                });

                            }
                            // location.reload(true);
                        },
                        error: function (error) {
                            alertError('Something Went Wrong!');
                        }
                    });


                }
            }
        });
    });

    //toggle status of suppliers
    $(".toggle_status").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to change the status of this User ?",
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
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let id = $(self).attr('data-id');
                    let status = $(self).attr('data-toggle');
                    $.ajax({
                        url: APP_URL + '/admin/cms/users/toggle_status',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            status: status
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            // console.log(data);
                            if (data == 1 && status == 1) {
                                // alert('here');
                                $(self).removeClass('deactivated');
                                $(self).addClass('activated');
                                $(self).attr('title', 'Activated');
                                $(self).attr('data-toggle', '0');
                            } else if (data == 1 && status == 0) {
                                // alert('there');
                                $(self).removeClass('activated');
                                $(self).addClass('deactivated');
                                $(self).attr('title', 'Deactivated');
                                $(self).attr('data-toggle', '1');
                            } else {
                                alertError('Unable to change the status!');
                            }
                            // location.reload(true);
                        },
                        complete: function () {
                            $('.pre_loader').hide();
                        },
                        error: function (error) {
                            alertError('Something Went Wrong!');
                        }
                    });
                }
            }
        });
    });

    $.validator.addMethod('minImageWidth', function (value, element, minWidth) {
        return ($(element).data('imageWidth') || 0) < minWidth;
    }, function (minWidth, element) {
        var imageWidth = $(element).data('imageWidth');
        return (imageWidth)
            ? ("Your image's width must be Less than " + minWidth + "px")
            : "Selected file is not an image.";
    });


    $('#createUser, #updateUser').validate({
        rules: {
            // vc_title: {
            //     required: true,
            // },
            vc_fname: {
                required: true,
                maxlength: 32,
                minlength: 2
            },
            vc_image: {
                accept: "jpg|jpeg|png"
            },
            vc_lname: {
                required: true,
                maxlength: 32,
                minlength: 2
            },
            email: {
                required: true,
                remote: {
                    url: "/admin/cms/users/emailcheck",
                    async: false,
                    type: "post",
                    data: {
                        email: function () {
                            return $("#createUser #email").val();
                        }
                    }
                }
            },
            address: {
                required: true,
            },
            i_ref_country_id: {
                required: true,
            },
            i_ref_state_id: {
                required: true,
            },
            vc_city: {
                required: true,
            },
            i_ref_bu_id: {
                required: true,
            },
            i_ref_dep_id: {
                required: true,
            },
            i_ref_role_id: {
                required: true,
            },
            vc_phone: {
                digits: true,
                maxlength: 12
            },
            vc_phone_corr_2: {
                digits: true,
                maxlength: 12
            },
            password: {
                minlength: 6,
                maxlength: 10
            },
            rpassword: {
                minlength: 6,
                maxlength: 10,
                equalTo: "#password"
            }
        },
        messages: {
            vc_title: {
                required: "Please select Title",
            },
            vc_fname: {
                required: "Please enter First Name",
            },
            vc_image: {
                accept: "Image should be jpg , png or jpeg"
            },
            vc_lname: {
                required: "Please enter Last Name",
            },
            email: {
                required: "Please enter Email",
                remote: "Email already exist"
            },
            address: {
                required: "Please enter Address",
            },
            i_ref_country_id: {
                required: "Please select Country",
            },
            i_ref_state_id: {
                required: "Please select State ",
            },
            vc_city: {
                required: "Please enter City ",
            },
            i_ref_bu_id: {
                required: "Please select Business Unit ",
            },
            i_ref_dep_id: {
                required: "Please select Department",
            },
            i_ref_role_id: {
                required: "Please select Role",
            },
            vc_phone: {
                digits: "Contact Number should contain digits",
                maxlength: "Contact Number should be less then 12 digits"
            },
            vc_phone_corr_2: {
                digits: "Contact Number should contain digits",
                maxlength: "Contact Number should be less then 12 digits"
            },
            rpassword: {
                equalTo: "Confirm Password in not same as Password"
            }
        }
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

// show image after selecting
var loadFile = function (event) {
    var image = document.getElementById('user_image');
    image.src = URL.createObjectURL(event.target.files[0]);
};


$('#createRole').validate({
    rules: {
        vc_name: {
            required: true,
            maxlength: 32,
            minlength: 2
        },
        i_ref_bu_id: {
            required: true,
        },
        i_ref_level_id: {
            required: true,
        },
        'permission_id[]': {
            required: true,
        },
        'form_permission_id[]': {
            required: true,
        }
    },
    messages: {
        vc_name: {
            required: "Please enter Name",
        },
        i_ref_bu_id: {
            required: "Please select Business Unit",
        },
        i_ref_level_id: {
            required: "Please select Level"
        },
        'permission_id[]': {
            required: "Please select Permissions",
        },
        'form_permission_id[]': {
            required: "Please select Form Permissions",
        }
    },
    onsubmit: false
});


$("#new_role").click(function (event) {
    event.preventDefault;

    if ($('#createRole').valid()) {
        var formElement = document.getElementById('createRole');
        var formData = new FormData(formElement);
        $('.pre_loader').show();
        $.ajax({
            url: APP_URL + "/admin/cms/roles/ajax_create_role",
            // contentType: "application/json",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            type: "POST",
            success: function (response) {
                $('.pre_loader').hide();
                if (response.done == "done") {
                    // var newOption = new Option(response.name, response.id, true, true);
                    // Append it to the select
                   var department= $("#i_ref_dep_id").val();
                    console.log('<option value=' + response.id + ' selected>' + response.name + '</option>');
                    $('#role').append('<option value=' + response.id + ' selected>' + response.name + '</option>');

                    $("#department").val(department);
                    $('#role').val(response.id);
                    $("select[name=i_ref_dep_id]").val(department);
                    $('#role').val(response.id);
                    $("#role_has_user").hide();
                    $('#createRole').trigger("reset");
                    $(".permissions").val([]);
                    $(".form_permissions").val([]);
                }
            }, error: function (error) {
                alertError('Something Went Wrong!');
            }

        });
    }


});




$("#assignnewrole").validate({
    rules: {
        i_ref_role_id: {
            required: true,
        },
    },
    onsubmit: false
});

$("#assign_new_role").click(function (event) {
    event.preventDefault;

    if ($('#assignnewrole').valid()) {
        $('.pre_loader').show();
        var formElement = document.getElementById('assignnewrole');
        var formData = new FormData(formElement);
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        id=$("#restore_user_id").val();
        $.ajax({
            url: APP_URL + "/admin/cms/roles/update_user_role",
            // contentType: "application/json",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            type: "POST",
            success: function (response) {
                
                $.ajax({
                    url: APP_URL + '/admin/cms/users/restore',
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        id: id
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('.pre_loader').hide();
                        location.reload(true);
                    },
                    error: function (error) {
                        alertError('Something Went Wrong!');
                    }
                });

            }
        });
    }

});


$(".upload_close").click(function(){
    $("#role_has_user").hide();
});
