$(document).ready(function () {
    
    $(":checkbox").uniform();
    $(":radio").uniform();
    $('#suppliers_table').show();
    $('.pre_loader').hide();
    var serialno;
    var BU_table = $('#suppliers_table').DataTable({
        deferLoading:true,
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
                targets: 1
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
                className: 'create_supplier',
                id: 'create_supplier',
                title: 'Create Supplier',
                text: "Create Supplier",
                action: function (e, dt, node, config) {
                    //This will send the page to the location specified
                    window.location.href = APP_URL+'/admin/cms/suppliers/create';
                }
            },
            {
                extend: 'excelHtml5',
                title: 'Excel',
                text: 'Export to Excel',
                //Columns to export
                exportOptions: {
                    columns: [0, 2, 3, 6, 7],
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

    $('#suppliers_table_filter').css('display', 'none');
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

    //Archive suppliers
    $(".archive_supplier").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to archive this Supplier ?",
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
                        url: APP_URL+'/admin/cms/suppliers/'+id,
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

    //restore supplier
    $(".restore_supplier").click(function (event) {
        event.preventDefault();
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to restore this Supplier?",
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
                        url: APP_URL+'/admin/cms/suppliers/restore',
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


    //toggle status of suppliers
    $(".toggle_status").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to change the status of this Supplier ?",
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
                        url: APP_URL+'/admin/cms/suppliers/toggle_status',
                        type: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            status: status
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            // console.log(data);
                            if(data == 1 && status == 1){
                                // alert('here');
                                $(self).removeClass('deactivated');
                                $(self).addClass('activated');
                                $(self).attr('title', 'Activated');
                                $(self).attr('data-toggle', '0');
                            }else if(data == 1 && status == 0){
                                // alert('there');
                                $(self).removeClass('activated');
                                $(self).addClass('deactivated');
                                $(self).attr('title', 'Deactivated');
                                $(self).attr('data-toggle', '1');
                            }else{
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


    var validateTrue=0;
     /**
         * Form create validation
         */
      $("#createSupplier").validate({
        rules: {
            onkeyup: true,  
            bussiness_name: {
                required: true,
                maxlength: 32,
                minlength:2
            },
            vc_fname:{
                required: true,
                maxlength: 32,
                minlength:2
            },
            vc_lname:{
                required: true,
                maxlength: 32,
                minlength:2
            },
            vc_image:{
                accept: "jpg|jpeg|png"
            },
            email:{
                required: true,
                remote: {
                    url: "/admin/cms/users/emailcheck",
                    async:false,
                    type: "post",
                    data: {
                        email: function () {
                            return $("#createSupplier #email").val();
                        }
                    },
                    complete:function(data){
                        validateTrue=1;
                        if ($('#createSupplier').valid()) {
                            
                            $('form#createSupplier')[0].submit();
                        }
                    }
                }
            },
            'permission_id[]':{
                required: true,
            },
            vc_DOPAS:{
                required: true,
            },
            // swift_code:{
            //     required: true,
            // },
            // bank_BSB_number:{
            //     required: true,
            // },
            // tax_File_number:{
            //     required: true,
            // },
            // australlian_business_number:{
            //     required: true,
            // },
            // company_business_number:{
            //     required: true,
            // },
            // password:{
            //     minlength: 6,
            //     maxlength: 10
            // },
            // rpassword:{
            //     minlength: 6,
            //     maxlength: 10,
            //     equalTo: "#password"
            // }
        },
        messages:{
            bussiness_name: {
                required: "Please enter Business Name",
            },
            vc_fname: {
                required: "Please enter First name",
            },
            vc_lname: {
                required: "Please enter Last name",
            },
            vc_image:{
                accept: "Image should be jpg , png or jpeg"
            },
            email: {
                required: "Please enter Email",
                remote: "Email already exist"
            },
            'permission_id[]':{
                required: "Please select Permissions",
            },
            vc_DOPAS: {
                required: "Please enter Descriptions Of Products and Services",
            },
            // swift_code: {
            //     required: "Please enter SWIFT Code",
            // },
            // bank_BSB_number: {
            //     required: "Please enter Bank BSB Number",
            // },
            // tax_File_number: {
            //     required: "Please enter Tax File Number",
            // },
            // australlian_business_number: {
            //     required: "Please enter Australlian Business Number ",
            // },
            // company_business_number: {
            //     required: "Please enter Company Business Number",
            // },
            // rpassword:{
            //     equalTo: "Confirm Password in not same as Password"
            // }
        }
    });



    /**
         * Update Form validation
         */
     $("#updateSupplier").validate({
        rules: {
            bussiness_name: {
                required: true,
                maxlength: 32,
                minlength:2
            },
            vc_fname:{
                required: true,
                maxlength: 32,
                minlength:2
            },
            vc_lname:{
                required: true,
                maxlength: 32,
                minlength:2
            },
            vc_image:{
                accept: "jpg|jpeg|png"
            },
            email:{
                required: true,
            },
            'permission_id[]':{
                required: true,
            },
            vc_DOPAS:{
                required: true,
            },
            // swift_code:{
            //     required: true,
            // },
            // bank_BSB_number:{
            //     required: true,
            // },
            // tax_File_number:{
            //     required: true,
            // },
            // australlian_business_number:{
            //     required: true,
            // },
            // company_business_number:{
            //     required: true,
            // },
            password:{
                minlength: 6,
                maxlength: 10
            },
            rpassword:{
                minlength: 6,
                maxlength: 10,
                equalTo: "#password"
            }
        },
        messages:{
            bussiness_name: {
                required: "Please enter Business Name",
            },
            vc_fname: {
                required: "Please enter First name",
            },
            vc_lname: {
                required: "Please enter Last name",
            },
            vc_image:{
                accept: "Image should be jpg , png or jpeg"
            },
            email: {
                required: "Please enter Email"
            },
            'permission_id[]':{
                required: "Please select Permissions",
            },
            vc_DOPAS: {
                required: "Please enter Descriptions Of Products and Services",
            },
            swift_code: {
                required: "Please enter SWIFT Code",
            },
            bank_BSB_number: {
                required: "Please enter Bank BSB Number",
            },
            tax_File_number: {
                required: "Please enter Tax File Number",
            },
            australlian_business_number: {
                required: "Please enter Australlian Business Number ",
            },
            company_business_number: {
                required: "Please enter Company Business Number",
            },
            rpassword:{
                equalTo: "Confirm Password in not same as Password"
            }
        }
    });


    $('#save-supplier').on('click', function () {
        if ($('#createSupplier').valid()) {
            if(validateTrue==1){
                console.log("ddddddd");
                if ($('#createSupplier').valid()) {
                    $('#createSupplier').submit();
                }
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
var loadFile = function(event) {
	var image = document.getElementById('user_image');
	image.src = URL.createObjectURL(event.target.files[0]);
};

// force to enter numbers only

function onlyNumberKey(evt) {
          
    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}



    //Archive suppliers
    $(".supplier_approve_alert").click(function (event) {
        var self = this;
        event.preventDefault();
        bootbox.confirm({
            message: "Are you sure you want to send notification to approve this Supplier ?",
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
                        url: APP_URL+'/admin/cms/suppliers/supplier_approve_alert',
                        type: 'get',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            alertError('Alert Send Successfully!');
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


