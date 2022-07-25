@extends('admin.layout.app')
@section('title') {{ trans('label.create_location') }} @endsection
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
                    <span>{{ trans('label.create_location') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.create_location') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('location.store') }}" id="createLocation"
                            method="POST" enctype='multipart/form-data'>
                            @csrf
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Location Name<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Location Name" name="vc_name" id="vc_name" minlength="2" maxlength="32"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Description</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter description here..." maxlength="300" name="vc_description" id="vc_description"></textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Address</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Address Line 1" maxlength="60" name="vc_address" id="vc_address"></div>
                                    <div class="col-md-3"> <label>Address2</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Address Line 2" maxlength="60" name="vc_address2" id="vc_address2"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Contact Number</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Contact Number" name="contact_number" id="contact_number"></div>
                                    <div class="col-md-3"> <label>Other Contact Number</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Other Contact Number" name="other_contact_number" id="other_contact_number"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3" ><label>Country</label></div>
                                    <div class="col-sm-3" >
                                        <select name="i_ref_country_id" id="country_id">
                                            <option value=""> Select Country </option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}"> {{$country->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3" ><label>State</label></div>
                                    <div class="col-sm-3" >
                                        <select name="i_ref_state_id" id="state_id">
                                            <option value=""> Select State </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>City</label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="City" name="vc_city" id="vc_city" maxlength="50"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Postal Code</label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Postal Code" name="vc_postal_code" id="vc_postal_code"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Status</label></div>
                                    <div class="col-sm-5">
                                        <select name="i_status" id="i_status">
                                            @foreach ($status as $row => $value)
                                            <option value="{{ $row }}"> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary"  type="submit" id="save-location">Save</button>&nbsp;
                                        <a href="{{route('location.index')}}" class="btn btn-default">Cancel</a>
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
<script src="{{asset('assets/js/location.js')}}"></script>
<script src="{{asset('assets/js/business_unit.js')}}"></script>
<script>

    /**
    * remove class on save buttom
    * 
    */
    $('#save-location' ).click(function(){
        $('#createLocation input').removeClass('form_change');
        $('#createLocation select').removeClass('form_change');
        $('#createLocation textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#createLocation input, #createLocation select, #createLocation textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#createLocation input').hasClass('form_change') || $('#createLocation select').hasClass('form_change') 
        || $('#createLocation textarea').hasClass('form_change') ){
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
