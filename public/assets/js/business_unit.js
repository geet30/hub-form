// get departmnets and projects and roles according to business unit

var gloable_total_roles = [];
var gloable_total_roles_length = 0;

$('#business_unit, #business_unit_edit').on('change', function () {
    var id = $(this).val();
    $('#pre_loader').show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if (id != '') {
        $.ajax({
            url: APP_URL + '/admin/show_bu_data',
            type: 'POST',
            data: { _token: CSRF_TOKEN, id: id },
            dataType: "json",
            success: function (response) {
                // console.log(response['business_dept']);
                if (response) {
                    // console.log(typeof set_edit_document_param);
                    if (typeof set_edit_document_param !== 'undefined' && typeof set_edit_document_param === 'function') {  
                        set_edit_document_param();
                    }

                    var total_dept = response['business_dept'].length;
                    var total_project = response['projects'].length;
                    var total_users = response['roles'].length;
                    gloable_total_roles = response['roles'];
                    gloable_total_roles_length = response['roles'].length;;
                    var total_roles = response['roles'].length;
                    $("#department , #department_edit").html('<option value="">-- Department --</option>');
                    if (total_dept > 0) {
                        var i;
                        for (i = 0; i < total_dept; i++) {
                            if (response['business_dept'][i]['dept_data'] != null) {
                                var dept_name = response['business_dept'][i]['dept_data']['vc_name'];
                                var dept_val = response['business_dept'][i]['dept_data']['id'];
                                $("#department,#department_edit").append($('<option></option>').val(dept_val).html(dept_name));
                            }
                        }
                    }
                    if ($('#role').length > 0) {
                        $("#role, #role_edit").html('<option value="">-- Role --</option>');
                    }

                    $("#project, #project_edit").html('<option value="">-- Project --</option>');
                    if (total_project > 0) {
                        var j;
                        for (j = 0; j < total_project; j++) {
                            var project_name = response['projects'][j]['vc_name'];
                            var project_val = response['projects'][j]['id'];
                            $("#project,#project_edit").append($('<option></option>').val(project_val).html(project_name));
                        }
                    }

                    if ($('#owner_list , #owner_list_edit').length > 0) {
                        $("#owner_list, #owner_list_edit").html('<option value="">-- Owner --</option>');
                        if (total_users > 0) {
                            var k;
                            for (k = 0; k < total_users; k++) {
                                // console.log(response['roles'][k]);
                                // console.log(response['roles'][k]['user_detail'].user);
                                if (response['roles'][k]['user_detail'] != null) {
                                    if (response['roles'][k]['user_detail'].user != null) {
                                        var user_name = response['roles'][k]['vc_name'] + " " + '[' + response['roles'][k]['user_detail']['user']['vc_fname'] + ' ' + response['roles'][k]['user_detail']['user']['vc_lname'] + ']';
                                        var user_val = response['roles'][k]['user_detail']['user']['id'];
                                        $("#owner_list ,#owner_list_edit").append($('<option></option>').val(user_val).html(user_name));
                                    }
                                }

                            }

                        }
                    }


                    $('.self_parent').removeAttr("disabled");
                    $('.alreadyParentBU').hide();
                    if ($('#parent_role').length > 0) {
                        // alert('here');
                        $("#parent_role").html('<option value=""> Select Parent Role </option>');
                        if (total_roles > 0) {
                            var m;
                            console.log(response['roles']);
                            for (m = 0; m < total_roles; m++) {
                                if (response['roles'][m]['i_ref_role_id'] == '' || response['roles'][m]['i_ref_role_id'] == 0) {
                                    $('.self_parent').attr("disabled", true);
                                    if($('.self_parent').parent().hasClass('checked')){
                                        $('.self_parent').removeAttr("checked");
                                        $('.self_parent').parent().removeClass('checked');
                                    }
                                    $('.alreadyParentBU').show();
                                }
                                if (response['roles'][m]['i_status'] == 1) {
                                    var all_role_name = response['roles'][m]['vc_name'];
                                    var all_role_val = response['roles'][m]['id'];
                                    $("#parent_role").append($('<option></option>').val(all_role_val).html(all_role_name));
                                }
                            }
                        }
                    }

                    $('#pre_loader').hide();

                }
            }
        });
    } else {
        $("#department").html('<option value=""> --Department-- </option>');
        $("#project").html('<option value=""> --Project-- </option>');
        $('#pre_loader').hide();
    }
});

// get states according to country
$('#country_id').on('change', function () {
    var id = $(this).val();
    $('#pre_loader').show();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if (id != '') {
        $.ajax({
            url: APP_URL + '/admin/show_states',
            type: 'POST',
            data: { _token: CSRF_TOKEN, id: id },
            dataType: "json",
            success: function (response) {
                if (response) {
                    var total_states = response['states'].length;

                    $("#state_id").html('<option value=""> Select State </option>');
                    if (total_states > 0) {
                        var a;
                        for (a = 0; a < total_states; a++) {
                            var state_name = response['states'][a]['name'];
                            var state_val = response['states'][a]['id'];
                            $("#state_id").append($('<option></option>').val(state_val).html(state_name));
                        }
                    }

                    $('#pre_loader').hide();
                }
            }
        });
    } else {
        $("#department").html('<option value=""> --Department-- </option>');
        $("#project").html('<option value=""> --Project-- </option>');
        $('#pre_loader').hide();
    }
});


var gloable_role_id="";
var gloable_dapetment_id="";
$("#role").on('change', function () {

    var id = $(this).val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    if (id != '') {
        $('.pre_loader').show();
        $.ajax({
            url: APP_URL + '/admin/users/check_role_has_user',
            type: 'POST',
            data: { _token: CSRF_TOKEN, id: id },
            dataType: "json",
            success: function (response) {
                $('.pre_loader').hide();
                console.log(response);
                console.log(response.user_detail.user);
                    if(response.user_detail.user!=null){
                        $(".role_has_user").text("This role is already assigned to  " + response.user_detail.user.vc_fname + " user")
                        $("input[name=i_ref_bu_id]").val($(".business_unit").val());
                        var department= $("select[name=i_ref_dep_id]").val();
                        gloable_dapetment_id=department;
                        gloable_role_id= $("#role").val();
                        $("#i_ref_dep_id").val(department);
                        $("select[name=business_unit]").val($(".business_unit").val()).change();
                        $("#role_has_user").show();
                        console.log(response.user_detail.user);
                        $("#inlineRadio1").attr('data-id',response.user_detail.user.id_encrypted);
                    }
            }
        });

    } else {
        $("#role").html('<option value=""> --role-- </option>');
    }

});
$("#department").on('change', function () {
    if ($('#role').length > 0) {
        $("#role").html('<option value="">-- Role --</option>');
        if (gloable_total_roles_length > 0) {
            var l;
            for (l = 0; l < gloable_total_roles_length; l++) {
                // if(response['roles'][l]['user_detail'] == null || response['roles'][l]['user_detail'] == ''){

                var role_name = gloable_total_roles[l]['vc_name'];
                var role_val = gloable_total_roles[l]['id'];
                $("#role").append($('<option></option>').val(role_val).html(role_name));
               
                // }
            }
        }
    }
 
});
$(".upload_close").click(function(){
    $("#view_business_modal").hide();
});
