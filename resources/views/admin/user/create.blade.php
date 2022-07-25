@extends('admin.layout.app')
@section('title') {{ trans('label.create_user') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
<style>
    .form-new {

        float: left;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
        padding: 20 px;
        font-size: 13px;
        border-bottom: 1 px solid #d3d7db;
        background-color: #fcfcfc;
    }

    .form-new label {
        /* color: #4a535e;
        text-align: left; */
        float: left;
        width: 100%;
        margin: 0;
        padding: 0;
        padding-top: 7px;
    }

    .form-new .asterisk {
        color: #D9534F;
    }

    .form-new input {
        width: 100% !important;
        border-radius: 3px !important;
        padding: 10px !important;
        border: 1px solid #ccc !important;
        background: #fff !important;
        font-size: 13px !important;
    }

    .form-new textarea {
        width: 100%;
        border-radius: 3px;
        padding: 10px;
        border: 1px solid #ccc;
        background: #fff;
        font-size: 13px;
        resize: none;
        height: 100px;
    }

    label.role_user_lable {
        display: inline;
        padding: 0;
        margin: 0 0 -21px 28px;
        position: relative;
        bottom: 21px;
    }
</style>
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
                    <span>{{ trans('label.create_user') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.create_user') }}</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('users.store') }}" id="createUser" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-2">
                                        <select name="vc_title" class="vc_title">
                                            <option value=""> Select </option>
                                            @foreach (App\Models\Users::getTitleArray() as $title)
                                            <option value="{{$title}}"> {{$title}} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="col-sm-2"><input type="text" placeholder="First Name" name="vc_fname" id="vc_fname"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Middle Name" name="vc_mname" id="vc_mname"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Last Name" name="vc_lname" id="vc_lname"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Image</label></div>
                                    <div class="col-sm-2"> <img src="/assets/edit_form/images/defaultpic.jpeg" class="default-image user_image" alt="" id="user_image"> </div>
                                    <div class="col-sm-6">
                                        <label for="vc_image" class="upload-image"> <span class="btn btn-primary" id="uploadCompanyImage" style="margin-top: 30px; ">Upload Image</span></label>
                                        <input id="vc_image" type="file" name="vc_image" value="" class="vc_image" style="display: none" accept="image/*" onchange="loadFile(event)" />
                                    </div>
                                    <div class="col-sm-4">
                                        Image Dimension should be within 1000X800 and Size should be less than 2 mb
                                    </div>


                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Email<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="email" placeholder="Email" name="email" id="email"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Alternate Email</label></div>
                                    <div class="col-sm-6"><input type="email" placeholder="Alternate Email" name="email_corr_2" id="email_corr_2"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Address<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Address" name="address" id="address"></div>
                                    <div class="col-sm-3"><input type="text" placeholder="City" name="vc_city" id="vc_city"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-sm-3">
                                        <select name="i_ref_country_id" id="country_id">
                                            <option value=""> Select Country </option>
                                            @foreach ($countries as $country)
                                            <option value="{{$country->id}}"> {{$country->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="i_ref_state_id" id="state_id">
                                            <option value=""> Select State </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3"><input type="text" placeholder="Zip Code" name="vc_zip_code" id="vc_zip_code"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Contact No.</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Contact No." name="vc_phone" id="vc_phone"></div>
                                    <div class="col-md-3"> <label>Alternate Contact No.</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Alternate Contact No." name="vc_phone_corr_2" id="vc_phone_corr_2"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-sm-2"><label> Business Unit<span class="asterisk">*</span> </label></div>
                                    <div class="col-sm-2">
                                        <select name="i_ref_bu_id" id="business_unit" class="business_unit">
                                            <option value=""> -- Business Unit -- </option>
                                            @foreach ($business_units as $key => $bu)
                                            <option value="{{$bu->id}}"> {{$bu->vc_short_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2"><label> Department<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-2">
                                        <select name="i_ref_dep_id" id="department">
                                            <option value=""> -- Department -- </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2"><label> Role<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-2">
                                        <select name="i_ref_role_id" id="role">
                                            <option value=""> -- Role -- </option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-2"> <button type="button" class="btn btn-primary ">Add More</button></div> --}}
                                </div>
                            </div>

                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary" type="submit" id="save-user">Save</button>&nbsp;
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



<!-- role has user  Modal -->
@include('admin.user.role_has_user_modal')







@endsection
@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('assets/js/users.js')}}"></script>
<script src="{{asset('assets/js/business_unit.js')}}"></script>
<script>
    /**
     * remove class on save buttom
     * 
     */
    $('#save-user').click(function() {
        $('#createUser input').removeClass('form_change');
        $('#createUser select').removeClass('form_change');
        $('#createUser textarea').removeClass('form_change');
    });

    /**
     * add class if there is change in any
     * input
     */
    $('#createUser input, #createUser select, #createUser textarea').on('keyup change', function() {
        $(this).addClass('form_change');
    });

    /**
     * alert before leaving window 
     */
    $(window).on('beforeunload', function() {
        if ($('#createUser input').hasClass('form_change') || $('#createUser select').hasClass('form_change') ||
            $('#createUser textarea').hasClass('form_change')) {
            var c = confirm();
            if (c) {
                return true;
            } else
                return false;
        }
    });


    $(document).ready(function() {
        $("input[name$='Relive']").click(function() {
            var test = $(this).val();
            if (test == "new_user") {
                $(".create_new_role").show();
            } else {
                $(".create_new_role").hide();
            }

            if (test == "relive_user") {
                // $(".relive_user").show();                
            } else {
                // $(".relive_user" ).hide();   
            }

        });
    });
    
    $(document).ready(function() {


        if($(".form_permissions").length==1){
                $(".form_permissions").select2({
                theme: "classic",
                placeholder: "Select a Form Permissions",
                allowClear: true
            });
        }

        if($(".permissions").length==1){
        
            $(".permissions").select2({
                theme: "classic",
                placeholder: "Select a Permissions",
                allowClear: true
            });
        }
    });
</script>
@endsection