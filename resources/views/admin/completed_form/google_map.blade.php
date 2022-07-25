@extends('admin.layout.app')
@section('title') {{trans('Google Map')}} @endsection

@section('header_css')
  <link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="{{ route('completed_forms') }}">{{ trans('label.completed_form') }}</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                
                  <span>{{ trans('label.google_map') }} </span>
                </li>
            </ul>
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-user font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.google_map') }}  </span>
                        </div>
                    </div>
                    <div id="map_canvas" style="height:500px; width:980px;px">{!! Mapper::render() !!}</div>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    //  google.maps.event.addDomListener(window, 'load', new function() {
    //     setTimeout(function () {
    //         google.maps.event.addListener(maps[0].map, 'click', function(event) {
    //            console.log("event", event);
    //         });
    //     }, 500);
    // });

</script>
{{-- <script src="{{asset('assets/template/js/fontawesome.min.js ')}}" type="text/javascript "></script>
<script src="{{asset('assets/template/js/jquery.main.js ')}}" type="text/javascript "></script>
<script src="{{asset('assets/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/js/template.js')}}"></script>
<script src="{{asset('assets/js/completed_from.js')}}"></script> --}}
@endsection