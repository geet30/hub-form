$(document).ready(function () {
    // change data according to dropdown value of default view
    $(".default_view").change(function (event) {
        var view = $.trim(this.value);
        event.preventDefault();
        $.ajax({
            beforeSend: function () {
                $(".pre_loader").show();
            },
            url: '/admin/form_data',
            type: 'POST',
            data: {
                view: view
            },
            dataType: 'JSON',
            success: function (data) {
                $(".pre_loader").hide();
                // console.log(data);
                var formdata = [];
                var i;
                var form_length;
                var prefix;
                var days = 0;
                if (data['form'].length > 0) {
                    for (i = 0; i < data['form'].length; i++) {
                        // console.log(i);
                        if (view == 30) {
                            form_length = data['form'][i]['completed_forms_days_count'];
                            days = parseInt(days + data['form'][i]['completed_forms_days_count']);
                        } else if (view == 6) {
                            form_length = data['form'][i]['completed_forms_months_count'];
                            days = parseInt(days + data['form'][i]['completed_forms_months_count']);
                        } else if (view == 1) {
                            form_length = data['form'][i]['completed_forms_year_count'];
                            days = parseInt(days + data['form'][i]['completed_forms_year_count']);
                        }
                        
                        prefix = data['form'][i]['template_prefix'];
                        formdata[i] = {
                            "y": form_length,
                            "label": prefix
                        };

                    }
                    if(days == 0){
                        $('.view_more_form').hide();
                        formdata = [{
                            "label": 'No data available'
                        }];
                    }else{
                        formdata = formdata;
                        $('.view_more_form').show();
                    }

                    if ($('#formchartContainer').length > 0) {
                        var formchart = new CanvasJS.Chart("formchartContainer", {
                            animationEnabled: true,
                            theme: "light2",
                            title: {
                                text: "Completed Forms",
                                fontSize: 18,
                                horizontalAlign: "center",
                            },
                            axisX:{
                                labelFontWeight: "bold"
                            },
                            data: [{
                                type: "column",
                                yValueFormatString: "#,##0.##",
                                dataPoints: formdata
                            }]
                        });
                        formchart.render();
                    }
                }

                // action chart 
                var incompleted;
                var completed;
                var overdue;
                var action_data;
                if (data['incompleted'] > 0 || data['completed'] > 0 || data['overdue'] > 0) {
                    var incompleted = data['incompleted'];
                    var completed = data['completed'];
                    var overdue = data['overdue'];
                    action_data = [{
                        "y": incompleted,
                        "label": "In Progress",
                        'color': "orange"
                    },
                    {
                        "y": completed,
                        "label": "Completed",
                        'color': "green"
                    },
                    {
                        "y": overdue,
                        "label": "Overdue",
                        'color': "red"
                    }
                    ]
                }
                if ($('#actionchartContainer').length > 0) {
                    var actionchart = new CanvasJS.Chart("actionchartContainer", {
                        theme: "light2",
                        animationEnabled: true,
                        title: {
                            text: "Status of Action(s)",
                            fontSize: 18,
                            horizontalAlign: "center",
                        },
                        data: [{
                            click: function (e) {
                                if (e.dataPoint.label == "In Progress") {
                                    var inprogress = $('#inprogress_route').val();
                                    window.open(inprogress, "_blank");
                                } else if (e.dataPoint.label == "Completed") {
                                    var completed = $('#completed_route').val();
                                    window.open(completed, "_blank");
                                } else if (e.dataPoint.label == "Overdue") {
                                    var overdue = $('#overdue_route').val();
                                    window.open(overdue, "_blank");
                                } else {
                                    var action = $('#action_route').val();
                                    window.open(action, "_blank");
                                }
                            },
                            indexLabelFontWeight: "bold",
                            type: "doughnut",
                            // indexLabel: "{symbol} - {y}",
                            yValueFormatString: "#,##0.##",
                            showInLegend: true,
                            innerRadius: "50%",
                            legendText: "{label} : {y}",
                            dataPoints: action_data
                        }]
                    });
                    showDefaultText(actionchart, "No Data available");
                    actionchart.render();
                }

                // console.log("data", data['action_listing']);

                // actions table 
                if (data['action_listing']) {
                    var action_table = data['action_listing'];
                    $('.action_nodata').css('display', 'none');
                    $('.action_body').html(action_table);
                }

                // form table 
                if (data['form_listing']) {
                    $('.form_body').html(data['form_listing']);
                }
                // document data
                if (data['doc_listing']) {
                    $('.doc_body').html(data['doc_listing']);
                }
            },
            error: function (error) {
                $(".pre_loader").hide();
                alertError("Something went wrong.");
                console.error("error", error);
            }
        });
    });
});
