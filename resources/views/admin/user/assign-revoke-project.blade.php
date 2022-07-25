@extends('admin.layout.app')
@section('title') {{ trans('label.assign_revoke_project') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                </li>
                {{-- <li>
                    <i class="fa fa-briefcase"></i>
                    <a href="{{ route('business-units.index') }}">{{ trans('label.business_unit') }}</a>
                    <i class="fa fa-circle"></i>
                </li> --}}
                <li>
                    <i class="fa fa-circle"></i>
                    <span>{{ trans('label.assign_revoke_project') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-briefcase"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.assign_revoke_project') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('users.saveProject', $userdata->id_encrypted) }}" id="assign_revoke_project"
                            method="POST" enctype='multipart/form-data'>
                            @csrf
                            {{ method_field('put') }}
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Business Unit</label></div>
                                    <div class="col-sm-6" style="color: #1CAF9A; margin-top:10px"> {{isset($userdata->users_details->business_unit) && !empty($userdata->users_details->business_unit) ? $userdata->users_details->business_unit->vc_short_name : '-'}} </div>
                                    
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Projects</label></div>
                                    <div class="col-sm-6">
                                        <input type="hidden" value="{{!empty($userdata->user_project) ? base64_encode(serialize($userdata->user_project)): ''}}" name="old_project"> 
                                        <select class="projects" multiple="multiple" theme="bootstrap" name="i_ref_project_id[]">
                                            @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @if(!empty($userdata->user_project) && in_array($project->id, $userdata->user_project)) selected @endif> {{ $project->vc_name }} </option>
                                            @endforeach
                                        </select>   
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary"  type="submit" id="assign_revoke">Assign/Revoke</button>&nbsp;
                                        <a href="{{route('users.index')}}" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('assets/js/users.js')}}"></script>
<script src="{{asset('assets/js/business_unit.js')}}"></script>
<script>

$(".projects").select2({
    theme: "classic",
    placeholder: "Select Projects",
    allowClear: true
});

    /**
    * remove class on update buttom
    * 
    */
    $('#assign_revoke' ).click(function(){
        $('#assign_revoke_project input').removeClass('form_change');
        $('#assign_revoke_project select').removeClass('form_change');
        $('#assign_revoke_project textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#assign_revoke_project input, #assign_revoke_project select, #assign_revoke_project textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#assign_revoke_project input').hasClass('form_change') || $('#assign_revoke_project select').hasClass('form_change') 
        || $('#assign_revoke_project textarea').hasClass('form_change') ){
            var c=confirm();
            if(c){
            return true;
            }
            else
            return false;
        }
    });

</script>
@endsection
