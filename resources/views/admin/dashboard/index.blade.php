@extends('admin.layout.app')
@section('title'){{ trans('label.dashboard') }}@endsection
@section('header_css')
<style>
    .pre_loader {
        left: 50%;
        top: 20%;
        z-index: 100000;
        position: fixed;
        display: none;
    }
</style>
@endsection
@section('content')
<?php
$formdata[] = array();
$formDays  = 0;
// pr($template_forms);die;
foreach ($template_forms as $key => $template_form) {
    $formDays  = $formDays + $template_form['completed_forms_days_count'];
    $forms = $template_form['completed_forms_days_count'];
    $formdata[$key] = ["y" => $forms, "label" => $template_form['template_prefix']];
}

// print_r($dataPoints);
// // die;
$actiondata = [];
if ($incompleted_actions > 0 || $completed_actions > 0 || $overdue_actions > 0) {
    $actiondata = array(
        array("y" => $incompleted_actions, "label" => "In Progress", 'color' => "orange"),
        array("y" => $completed_actions, "label" => "Completed", 'color' => "green"),
        array("y" => $overdue_actions, "label" => "Overdue", 'color' => "red")

    );

}
// print_r($data);die;
?>
<div class="pre_loader">
    <img src="{{ asset('assets/images/loading.gif') }}" alt="loader">
</div>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                </li>
            </ul>
        </div>
        <?php //print_r($_COOKIE['user_timezone']);die; 
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    @if(auth()->user()->user_type == 'employee' && !auth()->user()->userHasFormPermission("Complete Form") && !auth()->user()->userHasFormPermission("Manage Actions") && !auth()->user()->userHasFormPermission("Document Library"))
                    <div class="empty_data"> You are not able to see any data. Please contact to administrator! </div>
                    @else
                    <div class="row col-lg-12">
                        <div class="view_default">
                            View Data For :-
                            <select class="default_view">
                                <option value="30">Last 30 Days </option>
                                <option value="6">Last 6 month</option>
                                <option value="1">Last 1 year</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" value="{{route("actions", ["status"=>"Overdue"])}}" id="overdue_route">
                    <input type="hidden" value="{{route("actions", ["status"=>"In-Progress"])}}" id="inprogress_route">
                    <input type="hidden" value="{{route("actions", ["status"=>"Completed"])}}" id="completed_route">
                    <input type="hidden" value="{{route("actions")}}" id="action_route">
                    <input type="hidden" value="{{(auth()->user()->user_type == 'company') ? 1 : 0}}" id="user_type">
                    <div class="row col-lg-12">

                        {{-- <div class="col-md-1">
                        </div> --}}
                        @if ( auth()->user()->userHasFormPermission("Manage Actions"))
                        <div class="col-md-5 actionchart">
                        <input type="hidden" value='<?php echo json_encode($actiondata, JSON_NUMERIC_CHECK); ?>' id="actiondata">
                           
                            <div id="actionchartContainer" ></div>
                            @empty(!$actiondata)
                            <a href="{{route('actions')}}" class="view_more">View more </a>
                            @endempty
                        </div>
                        @endif

                        @if (auth()->user()->user_type == 'company')
                        @if ( auth()->user()->userHasFormPermission("Complete Form"))
                        <div class="col-md-5 formchart">
                            <input type="hidden" value='<?php echo json_encode($formdata, JSON_NUMERIC_CHECK); ?>' id="">
                            <input type="hidden" value='{{$formDays}}' id="form_value">
                            <div id="formchartContainer" style=" width: 100%;"></div>
                            <a href="{{route('completed_forms')}}" class="view_more view_more_form">View more </a>
                        </div>
                        @endif
                        @endif
                    </div>

                    <div class="row">
                        @if ( auth()->user()->userHasFormPermission("Manage Actions"))
                        <div class="action_grid col-md-6">
                            @if (auth()->check() && auth()->user()->user_type == 'company')
                            <div class="action_title">All Action(s)</div>
                            @else
                            <div class="action_title">My Action(s)</div>
                            @endif
                            <table class="table table-bordered action_table_grid action_body">
                                @include('partials.dashboard-action_listings')
                            </table>
                        </div>
                        @endif
                        @if ( auth()->user()->userHasFormPermission("Complete Form"))
                        <div class="form_grid col-md-6">
                            @if (auth()->check() && auth()->user()->user_type == 'company')
                            <div class="form_title">All Form(s)</div>
                            @else
                            <div class="form_title">My Form(s)</div>
                            @endif
                            <table class="table table-bordered form_table_grid form_body">
                                @include('partials.dashboard-form_listings')
                            </table>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        @if ( auth()->user()->userHasFormPermission("Document Library") || auth()->user()->user_type != 'employee')
                        <div class="doc_grid col-md-6">
                            @if (auth()->user()->user_type == 'company')
                            <div class="doc_title">All Document(s)</div>
                            @else
                            <div class="doc_title">My Document(s)</div>
                            @endif
                            <table class="table table-bordered doc_table_grid doc_body">
                                @include('partials.dashboard-doc_listings')
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- view documemt Modal --}}
<div id="view_document" class="modal">
    {{-- Modal content --}}
    <div class="modal-content">
        <div class="modal-header">
            <span class="upload_close close-view-document">&times;</span>
            <div class="upload_title">Document View</div>
        </div>
        <div class="modal-body">
            <div class="view_doc">
                <div class="pre_loader" id="pre_view_loader">
                    <img src="{{asset('assets/images/loading.gif')}}" alt="">
                </div>


            </div>
        </div>
    </div>
</div>

@endsection
@section('footer_scripts')
<script src="{{asset('assets/js/document.js')}}"></script>
<script>
    window.onload = function() {

        // form graph
        var formdata;
        if ($('#form_value').val() == '' || $('#form_value').val() == 0) {
            $('.view_more_form').hide();
            formdata = [{
                "label": 'No data available'
            }];
        } else {
            formdata = JSON.parse('<?php echo json_encode($formdata, JSON_NUMERIC_CHECK); ?>');
            $('.view_more_form').show();

        }


        if($('#actiondata').val()=="[]" && ($('#form_value').val() == '' || $('#form_value').val() == 0)){
            console.log("asd");
            $('#actionchartContainer').css("height", "389px");
            $('#formchartContainer').css("height", "389px");
        }else if($('#actiondata').val()!="[]" && ($('#form_value').val() == '' || $('#form_value').val() == 0) ){
            $('#formchartContainer').css("height", "408px");
            $('#actionchartContainer').css("height", "389px");
            // $('#formchartContainer').css("height", "389px");
      
        }else if(($('#form_value').val() != '' || $('#form_value').val() != 0) && $('#actiondata').val()=="[]" ){
            $('#formchartContainer').css("height", "389px");
            $('#actionchartContainer').css("height", "408px");
         
        }else{
            $('#formchartContainer').css("height", "389px");
            $('#actionchartContainer').css("height", "389px");
         
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
                axisX: {
                    labelFontWeight: "bold"
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,##0",
                    dataPoints: formdata
                }]
            });
            formchart.render();
        }

        // action chart 
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
                    click: function(e) {
                        if (e.dataPoint.label == "In Progress") {
                            window.open('{{route("actions", ["status"=>"In-Progress"])}}', "_blank");
                        } else if (e.dataPoint.label == "Completed") {
                            window.open('{{route("actions", ["status"=>"Completed"])}}', "_blank");
                        } else if (e.dataPoint.label == "Overdue") {
                            window.open('{{route("actions", ["status"=>"Overdue"])}}', "_blank");
                        } else {
                            window.open('{{route("actions")}}', "_blank");
                        }
                    },
                    indexLabelFontWeight: "bold",
                    type: "doughnut",
                    // indexLabel: "{symbol} - {y}",
                    yValueFormatString: "#,##0.##",
                    showInLegend: true,
                    innerRadius: "50%",
                    legendText: "{label} : {y}",
                    dataPoints: JSON.parse('<?php echo json_encode($actiondata, JSON_NUMERIC_CHECK); ?>')
                }]
            });
            showDefaultText(actionchart, "No Data available");
            actionchart.render();
        }
    }

    function showDefaultText(chart, text) {

        var isEmpty = !(chart.options.data[0].dataPoints && chart.options.data[0].dataPoints.length > 0);

        if (!chart.options.subtitles)
            (chart.options.subtitles = []);

        if (isEmpty)
            chart.options.subtitles.push({
                text: text,
                fontSize: 15,
                verticalAlign: 'center',
            });
        else
            (chart.options.subtitles = []);
    }
</script>

<script src="{{asset('assets/js/jquery.canvasjs.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/dashboard.js')}}" type="text/javascript"></script>
@endsection