$(document).ready(function () {

	$(":checkbox").uniform();
	$(":radio").uniform();

	$('#createRole, #updateUser').validate({
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
		}
	});


});


function getrole(tree_page, exp_row_id) {
	// prepare the data

	tree_page = $.trim(tree_page);

	var source =
	{
		dataType: "json",
		dataFields: [
			{ name: "id", type: "number" },
			{ name: "vc_name", type: "string" },
			//{name: "icon", type: "string" },
			{ name: "parent_id", type: "number" },
			{ name: "business_unit", type: "string" },
			{ name: "level_name", type: "string" },
			{ name: "status", type: "number" },
			{ name: "i_ref_role_id", type: "number" },
			{ name: "is_parent_itself", type: "string" },
			{ name: "i_ref_company_id", type: "number" },
			{ name: "account_payable", type: "number" },
			{ name: "supplier_approver", type: "number" },
			{ name: "alternative_supplier_approver", type: "number" },
			{ name: "system_administrator", type: "number" },


		],
		hierarchy:
		{
			keyDataField: { name: "id" },
			parentDataField: { name: "parent_id" }
		},
		id: "id",
		//localData: roles
		url: APP_URL + '/admin/cms/roles/getrole?tree_page=' + tree_page
	};

	var dataAdapter = new $.jqx.dataAdapter(source);
	// create Tree Grid
	$("#roleGrid").jqxTreeGrid(
		{
			width: "100%",
			source: dataAdapter,
			sortable: true,
			pageable: true,
			pageSize: 33,
			filterable: true,
			filterMode: "advanced",
			showToolbar: true,
			altRows: true,
			icons: true,
			exportSettings: { columnsHeader: true, hiddenColumns: true, serverURL: null, characterSet: null, collapsedRecords: true, recordsInView: true, fileName: null },
			ready: function () {
				$("#roleGrid").jqxTreeGrid("expandRow", parseInt(exp_row_id));
			},
			renderToolbar: function (toolBar) {
				$("#expandAllButton").click(function () {
					$.each(dataAdapter.loadedData, function (index, value) {
						$("#roleGrid").jqxTreeGrid("expandRow", value.id);
					});

					//traverseTreeGrid("treeGrid","expand");
				});
				$("#collapseAllButton").click(function () {
					$.each(dataAdapter.loadedData, function (index, value) {
						$("#roleGrid").jqxTreeGrid("collapseRow", value.id);
					});
					//traverseTreeGrid("treeGrid","collapse");
				});
			},
			columns: [
				{ text: "Name", dataField: "vc_name", width: "27%" },
				{ text: "Business Unit", dataField: "business_unit", width: "20%" },
				{ text: "Level", dataField: "level_name", width: "20%" },
				{
					text: "Status", dataField: "status", width: "10%", align: "center", cellsAlign: "center", cellsRenderer: function (rowKey, dataField, value, data) {
						var row = $("#roleGrid").jqxTreeGrid("getRow", rowKey);
						if (value == 1) {
							return '<span class="activated project_status" title="Activated" data-id="activated" ></span>';
						} else {
							return '<span class="deactivated project_status" title="Deactivated" data-id="deactivated" ></span>';
						}
					}
				},



				{
					text: "Account Payable", dataField: "account_payable", width: "10%", align: "center",
					cellsAlign: "center",
					cellsRenderer: function (rowKey, dataField, value, data) {
						// console.log(rowKey, dataField, value, data);
						//    console.log(data.i_ref_company_id)
						//    return false;
						var checked = (value == 1 ? "checked" : "");

						return "<div class='form-check'><input class='form-check-input archive_role account_payable'   type='checkbox' data-id =" + data.id + "  " + checked + " id ='account_payable' onclick='is_account_payable_exists(" + data.id + "," + data.i_ref_company_id + ")'></div>";

					}
				},


				{
					text: "Supplier Approver", dataField: "supplier_approver", width: "10%", align: "center",
					cellsAlign: "center",
					cellsRenderer: function (rowKey, dataField, value, data) {
						// console.log(rowKey, dataField, value, data);return false;
						var supplier_approver_checked = (value == 1 ? "checked" : "");
						return "<div class='form-check'><input class='form-check-input archive_role supplier_approver' type='checkbox'  data-id =" + data.id + "  " + supplier_approver_checked + " id ='supplier_approver' onclick='is_supplier_approver_exists(" + data.id + "," + data.i_ref_company_id + ")'>  </div>";

					}
				},


				{
					text: "Alternative Supplier Approver", dataField: "alternative_supplier_approver", width: "17%", align: "center",
					cellsAlign: "center",
					cellsRenderer: function (rowKey, dataField, value, data) {
						// console.log(rowKey, dataField, value, data);
						// return false;
						var alternative_supplier_approver_checked = (value == 1 ? "checked" : "");
						return "<div class='form-check'><input class='form-check-input archive_role alternative_supplier_approver' type='checkbox'  data-id =" + data.id + "  " + alternative_supplier_approver_checked + " id ='alternative_supplier_approver' onclick='is_alternative_supplier_approver_exist(" + data.id + "," + data.i_ref_company_id + ")'></div>";

					}
				},

				{
					text: "System Administrator", dataField: "system_administrator", width: "12%", align: "center",
					cellsAlign: "center",
					cellsRenderer: function (rowKey, dataField, value, data) {
						// console.log(rowKey, dataField, value, data);return false;
						var checked = (value == 1 ? "checked" : "");
						return "<div class='form-check'><input class='form-check-input archive_role system_administrator'  type='checkbox' data-id =" + data.id + " " + checked + " id ='system_administrator' onclick='is_system_administrator_exists(" + data.id + "," + data.i_ref_company_id + ")'></div>";
					}
				},



				{
					text: "Actions", dataField: "id", width: "25%", align: "center",
					cellsAlign: "center",
					cellsRenderer: function (rowKey, dataField, value, data) {
						// console.log(rowKey, dataField, value, data);return false;
						if (tree_page == "archived") {
							return '<a class="restore_role" onclick="restore_role(' + value + ')"><i class="fa fa-archive fa-1x"></i> </a>'
						} else {
							// if (data.is_parent_itself == "yes") {
							return "<a href= " + APP_URL + "/admin/cms/roles/" + value + "><i class=\"\glyphicon glyphicon-folder-open\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=" + APP_URL + "/admin/cms/roles/" + value + "/edit><i class=\"\glyphicon glyphicon-edit\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id =" + value + " id =trashRole  class='archive_role' onclick='archive_role(" + value + ")'><i class=\"\glyphicon glyphicon-inbox\"></i></a>";
							// }
							// else {
							// 	return "<a href= "+APP_URL+"/admin/cms/roles/"+value+"><i class=\"\glyphicon glyphicon-folder-open\"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="+APP_URL+"/admin/cms/roles/"+value+"/edit><i class=\"\glyphicon glyphicon-edit\"></i></a>";
							// }
						}
					}
				}
			]
		});
}


/*****************Hierachical*************/
var dragging = false;
$("#contentroleGrid table#tableroleGrid tbody tr").live("hover", function () {

	var selected_row = $(this).data("key");
	$("#tableroleGrid tbody").sortable({

		start: dragging = function (e, ui) {
			ui.item.data("start_pos", ui.item.index());
			var row = $("#roleGrid").jqxTreeGrid("getRow", selected_row);
			ui.item.data("start_level", row.i_ref_role_id);
			if (row.level == 0) {
				ui.helper.css("position", "relative");
				return dragging = false;
			}

		},

		stop: function (e, ui) {

			var start_pos = ui.item.data("start_pos");
			if (start_pos == ui.item.index()) {
				return false;
			}

			get_all_sort = [];
			$(".ui-sortable .ui-sortable-handle").each(function (i, val) {
				get_all_sort[i] = $(this).data("key");
			});

			var get_shifted_tree = [];
			$(get_all_sort).each(function (i, val) {
				get_shifted_tree.push(val);
				if (val == selected_row) {
					return false;
				}
			});

			var shifted_tree = get_shifted_tree[get_shifted_tree.length - 2];
			if (typeof shifted_tree == "undefined") {
				shifted_tree = get_all_sort[$.inArray(selected_row, get_all_sort) + 1];
			}
			var moved_row = $("#roleGrid").jqxTreeGrid("getRow", get_all_sort[$.inArray(selected_row, get_all_sort) + 1]);
			var row = $("#roleGrid").jqxTreeGrid("getRow", selected_row);

			seen = [];
			json = JSON.stringify(row, function (key, val) {
				if (val != null && typeof val == "object") {
					if (seen.indexOf(val) >= 0) {
						return;
					}
					seen.push(val);
				}
				return val;
			});

			if (typeof shifted_tree !== "undefined" && shifted_tree != "") {
				$(".jqx-datatable-load").css("display", "block");
				$(".jqx-datatable-load").css("visibility", "visible");
				$.ajax({
					url: APP_URL + "/admin/cms/roles/getUpgradeDowngradeRole",
					contentType: "application/json",
					data: JSON.stringify({ "selected": selected_row, "shifted": shifted_tree, "movedTo": get_all_sort[$.inArray(selected_row, get_all_sort) + 1], "moved_role_id": moved_row.i_ref_role_id }),
					type: "POST",
					success: function (msg) {
						$(".jqx-datatable-load").css("display", "none");
						$(".jqx-datatable-load").css("visibility", "hidden");
						msg = JSON.parse(msg);

						if (msg.exception_message) {
							$("#tableroleGrid tbody").sortable("cancel");

							bootbox.dialog({
								closeButton: false,
								message: msg.exception_message,
								title: "Alert",
								buttons: {
									main: {
										label: "Ok",
										className: "btn-danger",
										callback: function () {
										}
									}
								}
							});
						} else {
							if (get_all_sort[$.inArray(selected_row, get_all_sort) + 1] == moved_row.i_ref_role_id) {
								$("#setUpdateRoleText").html("Are you sure " + msg.data.selectedRole + " Role set as Top Role in this " + msg.data.selectedRoleBU);
								$("#setTopRoleModal").modal();
								$("#cancelSetTopRole").off("click").on("click", function () {
									$("#tableroleGrid tbody").sortable("cancel");
									$("#setTopRoleModal").modal("hide");
								});
								$("#setTopRole").off("click").on("click", function () {
									$("#setTopRoleModal").modal("hide");
									UpdateRol();
								});
							} else {
								UpdateRol();
							}

							function UpdateRol() {
								ui.item.data("flagChange", 0);
								$("#parentChild").hide();
								$("#ifChild").hide();
								$("#transferRole").modal();
								$("#transferRole").off("shown.bs.modal").on("shown.bs.modal", function () {
									$("#levelChange").find("option").remove();
									$.each(msg.data.levels, function (k, v) {
										$("#levelChange").append("<option value=" + v.Level.id + ">" + v.Level.vc_name + "(" + v.Level.i_start_limit + " - " + v.Level.i_end_limit + ")</option>");
									});
									$("#levelChange").val(msg.data.selectedRoleLevel);

									if (msg.data.childSelectedRole.length !== 0) {
										ui.item.data("flagChange", 1);
										$("#ifChild").show();
										$("#ifChild h3").html(msg.data.selectedRole + " Role has multiple childs");
										$("input[type=radio][name=roleup]").change(function () {
											if ($(this).val() == 1) {
												$("#parentChild").show();
												$("#updateRole").find("option").remove();
												$.each(msg.data.roles, function (k, v) {
													$("#updateRole").append("<option value=" + k + ">" + v + "</option>");
												});
											} else {
												$("#parentChild").hide();
												$("#updateRole").val("");
											}
										});
									}
								});
								$("#transferRole").off("hidden.bs.modal").on("hidden.bs.modal", function () {
									$("#updateRole").val("");
									$("#parentChild, #ifChild").hide();
									$("#ifChild h3").html("");
									$("input[type=radio][name=roleup][value=0]").prop("checked", true);
									msg.data.childSelectedRole = [];
									//if(ui.item.data("flagChange") == 0) {
									$("#tableroleGrid tbody").sortable("cancel");
									//}
								});
							}

						}
					}
				});
				$("#updateRoleData").off("click").on("click", function () {
					$(".jqx-datatable-load").css("display", "block");
					$(".jqx-datatable-load").css("visibility", "visible");
					$.ajax({
						url: APP_URL + "roles/change_child_role",
						contentType: "application/json",
						data: JSON.stringify({ "selected": selected_row, "shifted": shifted_tree, "movedTo": get_all_sort[$.inArray(selected_row, get_all_sort) + 1], "ChangeLevel": $("#levelChange").val(), UpdateChildParent: $("#updateRole").val(), "moved_role_id": moved_row.i_ref_role_id }),
						type: "POST",
						success: function (msg) {
							$(".jqx-datatable-load").css("display", "none");
							$(".jqx-datatable-load").css("visibility", "hidden");

							if (msg.exception_message) {
								$("#tableroleGrid tbody").sortable("cancel");

								bootbox.dialog({
									closeButton: false,
									message: msg.exception_message,
									title: "Alert",
									buttons: {
										main: {
											label: "Ok",
											className: "btn-danger",
											callback: function () {
											}
										}
									}
								});
							} else {
								$("#transferRole").modal("hide");

								getrole();
							}
						}
					});
				});


			} else {
				$("#tableroleGrid tbody").sortable("cancel");
				bootbox.dialog({
					closeButton: false,
					message: "You cannot change parent tree",
					title: "Alert",
					buttons: {
						main: {
							label: "Ok",
							className: "btn-danger",
							callback: function () {
							}
						}
					}
				});
			}
		},
	});
});

/********** Print role  ******************/
$("#printrole").click(function () {
	var gridContent = $("#roleGrid").jqxTreeGrid("exportData", "html");
	// console.log(gridContent);
	var table = "<table cellspacing=0 cellpadding=2>";
	table += "<thead>";
	table += $(gridContent).find("thead tr").each(function () {
		$(this).find("th:nth-last-child(1)").remove();
	}).html();
	table += "</thead>";
	table += "<tbody>";
	$(gridContent).find("tbody tr").each(function (k, v) {
		table += "<tr>";

		$(this).find("td:nth-last-child(1)").remove();
		var roleStatus = "In-Active";
		if (parseInt($(this).find("td:nth-last-child(1)").text()) == 1) {
			roleStatus = "Active";
		}
		$(this).find("td:nth-last-child(1)").text(roleStatus);

		table += $(this).html();

		table += "</tr>";
	});
	table += "</tbody>";
	table += "</table>";
	gridContent = table;

	var newWindow = window.open("", "", "width=800, height=500"),
		document = newWindow.document.open(),
		pageContent =
			"<!DOCTYPE html>" +
			"<html>" +
			"<head>" +
			"<meta charset=\"utf-8\" />" +
			"<title>Autrum Coal WBS</title>" +
			"</head>" +
			"<body>" + gridContent + "</body></html>";
	document.write(pageContent);
	newWindow.document.close();
	newWindow.print();
});


function archive_role(id) {
	var self = this;
	event.preventDefault();
	bootbox.confirm({
		message: "Are you sure you want to archive this Role ?",
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
					url: APP_URL + '/admin/cms/roles/' + id,
					type: 'DELETE',
					data: {
						_token: CSRF_TOKEN,
						id: id
					},
					dataType: 'JSON',
					success: function (data) {
						location.reload(true);
						// $('#users_table').dataTable().api().row('.selected').remove().draw();
					},
					error: function (error) {
						alertError('Something Went Wrong!');
					}
				});
			}
		}
	});
}



function restore_role(id) {
	event.preventDefault();
	var self = this;
	event.preventDefault();
	bootbox.confirm({
		message: "Are you sure you want to restore this Role?",
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
				// let id = id;
				$.ajax({
					url: APP_URL + '/admin/cms/roles/restore',
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
}








function is_account_payable_exists(id, company_id) {
	var self = this;
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	// $(".account_payable").toggle();
	console.log($(".account_payable").val());
	if ($(".account_payable").val() == 0) {
		$($('.account_payable')[0].offsetParent.children[0]).addClass('checked');
		$(".account_payable").prop("checked", true);

	}
	if ($(".account_payable").val() == 0) {
		// console.log($(".supplier_approver").is(':checked'));
		if($(".account_payable").is(':checked')){
			console.log("Asd");
			$($('.account_payable')[0].offsetParent.children[0]).removeClass('checked');
			$(".account_payable").prop("checked", false);
		}else{
			console.log("ewer");
			$($('.account_payable')[0].offsetParent.children[0]).addClass('checked');
			$(".account_payable").prop("checked", true);
			$(".account_payable").val(1);
		}
	}

	console.log($(".account_payable").is(':checked'));
	event.preventDefault();
	if ($(".account_payable").is(':checked')) {
		$(".account_payable").attr("disabled",true);
		$('.pre_loader').show();
		$.ajax({
			url: APP_URL + '/admin/cms/roles/is_account_payable_exists',
			type: 'get',
			data: {
				_token: CSRF_TOKEN,
				'id': id,
				'company_id': company_id
			},
			dataType: 'JSON',
			success: function (data) {
				$('.pre_loader').hide();
				if (data != null || data == 1) {
					bootbox.confirm({
						message: "Company has already a Account payable person. Do you want to change ?",
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
								$(".account_payable").val("on");
								// let id = $(self).attr('data-id');
								$.ajax({
									url: APP_URL + '/admin/cms/roles/update_account_payable',
									type: 'get',
									data: {
										_token: CSRF_TOKEN,
										'id': id,
										'company_id': company_id
									},
									dataType: 'JSON',
									success: function (data) {
										$(".account_payable").attr("disabled",false);
										$(".account_payable").attr("checked",true);
										// console.log(data);
										// location.reload(true);
										if ($(".account_payable_edit").val()) {
											$('.pre_loader').hide();
										} else {
											location.reload(true);
										}
										// $('#users_table').dataTable().api().row('.selected').remove().draw();
									},
									error: function (error) {
										alertError('Something Went Wrong!');
									}
								});
							} else {
								$($('.account_payable')[0].offsetParent.children[0]).removeClass('checked');
								$(".account_payable").prop("checked", false);
								$(".account_payable").attr("disabled",false);
							}
						}
					});
				} else {
					console.log($(".account_payable_edit").val());
					if ($(".account_payable_edit").val()) {
						$(".account_payable").attr("disabled",false);
						$(".account_payable").attr("checked",true);
					} else {
						$('.pre_loader').show();
						$.ajax({
							url: APP_URL + '/admin/cms/roles/update_account_payable',
							type: 'get',
							data: {
								_token: CSRF_TOKEN,
								'id': id,
								'company_id': company_id
							},
							dataType: 'JSON',
							success: function (data) {
								$(".account_payable").attr("disabled",false);
								location.reload(true);
								$('.pre_loader').hide();
								// $('#users_table').dataTable().api().row('.selected').remove().draw();
							},
							error: function (error) {
								alertError('Something Went Wrong!');
							}
						});
					}


				}
			},
			error: function (error) {
				alertError('Something Went Wrong!');
			}
		});
	} else {

		$(".account_payable").prop("checked", false);
		$(".account_payable").removeAttr("checked");

		$(".account_payable").val(0);
		$(".account_payable").attr("disabled",false);
		
		$(".account_payable").removeAttr("style");

	}
}



function is_supplier_approver_exists(id, company_id) {
	var self = this;
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$(".supplier_approver").removeAttr("style");
	console.log($(".supplier_approver").is(':checked'));
	if ($(".supplier_approver").val() == 0) {
		// console.log($(".supplier_approver").is(':checked'));
		if($(".supplier_approver").is(':checked')){
			console.log("Asd");
			$($('.supplier_approver')[0].offsetParent.children[0]).removeClass('checked');
			$(".supplier_approver").prop("checked", false);
		}else{
			console.log("ewer");
			$($('.supplier_approver')[0].offsetParent.children[0]).addClass('checked');
			$(".supplier_approver").prop("checked", true);
			$(".supplier_approver").val(1);
		}
	}


	event.preventDefault();
	if ($(".supplier_approver").is(':checked')) {
		$(".supplier_approver").attr("disabled",true);
		$('.pre_loader').show();
		$.ajax({
			url: APP_URL + '/admin/cms/roles/is_supplier_approver_exists',
			type: 'get',
			data: {
				_token: CSRF_TOKEN,
				'id': id,
				'company_id': company_id
			},
			dataType: 'JSON',
			success: function (data) {
				$('.pre_loader').hide();
				if (data != null || data == 1) {
					bootbox.confirm({
						message: "Company has already a Supplier Approver person. Do you want to change ?",
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
								// let id = $(self).attr('data-id');
								$.ajax({
									url: APP_URL + '/admin/cms/roles/update_supplier_approver',
									type: 'get',
									data: {
										_token: CSRF_TOKEN,
										'id': id,
										'company_id': company_id
									},
									dataType: 'JSON',
									success: function (data) {
										$(".supplier_approver").attr("disabled",false);
										$(".supplier_approver").attr("checked",true);
										
										// console.log(data);
										// location.reload(true);
										if ($(".supplier_approver_edit").val()) {
											$('.pre_loader').hide();
										} else {
											location.reload(true);
										}

										// $('#users_table').dataTable().api().row('.selected').remove().draw();
									},
									error: function (error) {
										alertError('Something Went Wrong!');
									}
								});
							} else {
								$(".supplier_approver").attr("disabled",false);
								$($('.supplier_approver')[0].offsetParent.children[0]).removeClass('checked');
								$(".supplier_approver").prop("checked", false);
							}
						}
					});
				} else {
					if ($(".supplier_approver_edit").val()) {
						$(".supplier_approver").attr("disabled",false);
						$(".supplier_approver").attr("checked",true);
					} else {
						$.ajax({
							url: APP_URL + '/admin/cms/roles/update_supplier_approver',
							type: 'get',
							data: {
								_token: CSRF_TOKEN,
								'id': id,
								'company_id': company_id
							},
							dataType: 'JSON',
							success: function (data) {
								$(".supplier_approver").attr("disabled",false);
								location.reload(true);
								// $('#users_table').dataTable().api().row('.selected').remove().draw();
							},
							error: function (error) {
								alertError('Something Went Wrong!');
							}
						});
					}
				}
				// location.reload(true);
				// $('#users_table').dataTable().api().row('.selected').remove().draw();
			},
			error: function (error) {
				alertError('Something Went Wrong!');
			}
		});
	} else {
		$(".supplier_approver").prop("checked", false);
		$(".supplier_approver").removeAttr("checked");
		$(".supplier_approver").attr("disabled",false);
		$(".supplier_approver").removeAttr("style");
		$(".supplier_approver").val(0);

	}

}



function is_system_administrator_exists(id, company_id) {
	var self = this;
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	event.preventDefault();
	if ($(".system_administrator").val() == 0) {
		// console.log($(".supplier_approver").is(':checked'));
		if($(".system_administrator").is(':checked')){
			console.log("Asd");
			$($('.system_administrator')[0].offsetParent.children[0]).removeClass('checked');
			$(".system_administrator").prop("checked", false);
		}else{
			console.log("ewer");
			$($('.system_administrator')[0].offsetParent.children[0]).addClass('checked');
			$(".system_administrator").prop("checked", true);
			$(".system_administrator").val(1);
		}
	}

	if ($(".system_administrator").is(':checked')) {
		$(".system_administrator").attr("disabled",true);
		$('.pre_loader').show();
		$.ajax({
			url: APP_URL + '/admin/cms/roles/is_system_administrator_exists',
			type: 'get',
			data: {
				_token: CSRF_TOKEN,
				'id': id,
				'company_id': company_id
			},
			dataType: 'JSON',
			success: function (data) {
				$('.pre_loader').hide();
				if (data != null || data == 1) {
					bootbox.confirm({
						message: "Company has already a System Administrator person. Do you want to change ?",
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
								// let id = $(self).attr('data-id');
								$.ajax({
									url: APP_URL + '/admin/cms/roles/update_system_administrator',
									type: 'get',
									data: {
										_token: CSRF_TOKEN,
										'id': id,
										'company_id': company_id
									},
									dataType: 'JSON',
									success: function (data) {
										$(".system_administrator").attr("disabled",false);
										$(".system_administrator").attr("checked",true);
										// console.log(data);
										
										if ($(".system_administrator_edit").val()) {
											$('.pre_loader').hide();
											return true;
										} else {
											location.reload(true);
										}

										// $('#users_table').dataTable().api().row('.selected').remove().draw();
									},
									error: function (error) {
										alertError('Something Went Wrong!');
									}
								});
							} else {
								$(".system_administrator").attr("disabled",false);
								$($('.system_administrator')[0].offsetParent.children[0]).removeClass('checked');
								$(".system_administrator").prop("checked", false);
							}
						}
					});
				} else {
					if ($(".system_administrator_edit").val()) {
						$(".system_administrator").attr("disabled",false);
						$(".system_administrator").attr("checked",true);
					} else {
						$.ajax({
							url: APP_URL + '/admin/cms/roles/update_system_administrator',
							type: 'get',
							data: {
								_token: CSRF_TOKEN,
								'id': id,
								'company_id': company_id
							},
							dataType: 'JSON',
							success: function (data) {
								$(".system_administrator").attr("disabled",false);
								location.reload(true);
								// $('#users_table').dataTable().api().row('.selected').remove().draw();
							},
							error: function (error) {
								alertError('Something Went Wrong!');
							}
						});
					}
				}
				// location.reload(true);
				// $('#users_table').dataTable().api().row('.selected').remove().draw();
			},
			error: function (error) {
				alertError('Something Went Wrong!');
			}
		});
	} else {
		$(".system_administrator").prop("checked", false);
		$(".system_administrator").removeAttr("checked");
		$(".system_administrator").attr("disabled",false);
		$(".system_administrator").val(0);
		$(".system_administrator").removeAttr("style");
	}
}




function is_alternative_supplier_approver_exist(id, company_id) {
	var self = this;
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	event.preventDefault();
	if ($(".alternative_supplier_approver").val() == 0) {
		// console.log($(".supplier_approver").is(':checked'));
		if($(".alternative_supplier_approver").is(':checked')){
			console.log("Asd");
			$($('.alternative_supplier_approver')[0].offsetParent.children[0]).removeClass('checked');
			$(".system_administrator").prop("checked", false);
		}else{
			console.log("ewer");
			$($('.alternative_supplier_approver')[0].offsetParent.children[0]).addClass('checked');
			$(".alternative_supplier_approver").prop("checked", true);
			$(".alternative_supplier_approver").val(1);
		}
	}
	if ($(".alternative_supplier_approver").is(':checked')) {
		$(".alternative_supplier_approver").attr("disabled",true);
		$('.pre_loader').show();
		console.log("workagian");
		$.ajax({
			url: APP_URL + '/admin/cms/roles/is_alternative_supplier_approver_exist',
			type: 'get',
			data: {
				_token: CSRF_TOKEN,
				'id': id,
				'company_id': company_id
			},
			dataType: 'JSON',
			success: function (data) {
				$('.pre_loader').hide();
				if (data != null || data == 1) {
					bootbox.confirm({
						message: "Company has already a Alternative Supplier Approver person. Do you want to change ?",
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
								// let id = $(self).attr('data-id');
								$.ajax({
									url: APP_URL + '/admin/cms/roles/update_alternative_supplier_approver',
									type: 'get',
									data: {
										_token: CSRF_TOKEN,
										'id': id,
										'company_id': company_id
									},
									dataType: 'JSON',
									success: function (data) {
										$(".alternative_supplier_approver").attr("disabled",false);
										$(".alternative_supplier_approver").attr("checked",true);
										// console.log(data);
										if ($(".alternative_supplier_approver_edit").val()) {
											$('.pre_loader').hide();
										} else {
											location.reload(true);
										}

										// $('#users_table').dataTable().api().row('.selected').remove().draw();
									},
									error: function (error) {
										alertError('Something Went Wrong!');
									}
								});
							} else {
								$(".alternative_supplier_approver").attr("disabled",false);
								$($('.alternative_supplier_approver')[0].offsetParent.children[0]).removeClass('checked');
								$(".alternative_supplier_approver").prop("checked", false);

							}
						}
					});
				} else {
					if ($(".alternative_supplier_approver_edit").val()) {
						$(".alternative_supplier_approver").attr("disabled",false);
						$(".alternative_supplier_approver").attr("checked",true);
					} else {
						$.ajax({
							url: APP_URL + '/admin/cms/roles/update_alternative_supplier_approver',
							type: 'get',
							data: {
								_token: CSRF_TOKEN,
								'id': id,
								'company_id': company_id
							},
							dataType: 'JSON',
							success: function (data) {
								$(".alternative_supplier_approver").attr("disabled",false);
								location.reload(true);
								// $('#users_table').dataTable().api().row('.selected').remove().draw();
							},
							error: function (error) {
								alertError('Something Went Wrong!');
							}
						});
					}
				}
				// location.reload(true);
				// $('#users_table').dataTable().api().row('.selected').remove().draw();
			},
			error: function (error) {
				alertError('Something Went Wrong!');
			}
		});

	} else {
		$(".alternative_supplier_approver").attr("disabled",false);
		$(".alternative_supplier_approver").prop("checked", false);
		$(".alternative_supplier_approver").removeAttr("checked");
		$(".alternative_supplier_approver").val(0);
		$(".alternative_supplier_approver").removeAttr("style");
	}
}



