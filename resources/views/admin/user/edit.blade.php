@extends('admin.layout.app')
@section('title') {{ trans('label.edit_user') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
<style>
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
                    <span>{{ trans('label.edit_user') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.edit_user') }}</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('users.update', $userdata->id_encrypted) }}" id="updateUser" method="POST" enctype='multipart/form-data'>
                            @csrf
                            {{ method_field('put') }}
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-2">
                                        <select name="vc_title" class="vc_title">
                                            <option value=""> Select </option>
                                            @foreach (App\Models\Users::getTitleArray() as $title)
                                            <option value="{{$title}}" {{$userdata->vc_title == $title ? 'selected' : ''}}> {{$title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2"><input type="text" placeholder="First Name" name="vc_fname" id="vc_fname" value="{{$userdata->vc_fname ?? ''}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Middle Name" name="vc_mname" id="vc_mname" value="{{$userdata->vc_mname ?? ''}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Last Name" name="vc_lname" id="vc_lname" value="{{$userdata->vc_lname ?? ''}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Image</label></div>
                                    <div class="col-sm-2"> <img src={{$userdata->image_url }} class="default-image user_image" alt="" id="user_image"> </div>
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
                                    <input type="hidden" name="old_email" id="old_email" value="{{$userdata->email ?? ''}}">
                                    <div class="col-sm-6"><input type="email" placeholder="Email" name="email" id="email" value="{{$userdata->email ?? ''}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Alternate Email</label></div>
                                    <div class="col-sm-6"><input type="email" placeholder="Alternate Email" name="email_corr_2" id="email_corr_2" value="{{$userdata->email_corr_2 ?? ''}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Password</label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Password" name="password" id="password"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Confirm Password</label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Confirm Password" name="rpassword" id="rpassword"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Address<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Address" name="address" id="address" value="{{$userdata->address ?? ''}}"></div>
                                    <div class="col-sm-3"><input type="text" placeholder="City" name="vc_city" id="vc_city" value="{{$userdata->vc_city ?? ''}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-sm-3">
                                        <select name="i_ref_country_id" id="country_id">
                                            <option value=""> Select Country </option>
                                            @foreach ($countries as $country)
                                            <option value="{{$country->id}}" {{$userdata->i_ref_country_id == $country->id ? 'selected' : ''}}> {{$country->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="i_ref_state_id" id="state_id">
                                            <option value=""> Select State </option>
                                            @if(!empty($userdata->country->states))
                                            @foreach ($userdata->country->states as $states)
                                            <option value="{{$states->id}}" {{$userdata->i_ref_state_id == $states->id ? 'selected' : ''}}> {{$states->name}} </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-3"><input type="text" placeholder="Zip Code" name="vc_zip_code" id="vc_zip_code" value="{{$userdata->vc_zip_code ?? ''}}"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Contact No.</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Contact No." name="vc_phone" id="vc_phone" value="{{$userdata->vc_phone ?? ''}}"></div>
                                    <div class="col-md-3"> <label>Alternate Contact No.</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Alternate Contact No." name="vc_phone_corr_2" id="vc_phone_corr_2" value="{{$userdata->vc_phone_corr_2 ?? ''}}"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-sm-2"><label> Business Unit<span class="asterisk">*</span> </label></div>
                                    <div class="col-sm-2">
                                        <input type="hidden" name="user_detail_id" value="{{$userdata->users_details->id ?? ''}}">
                                        <select name="i_ref_bu_id" id="business_unit"class="business_unit">
                                            <option value=""> -- Business Unit --</option>
                                            @foreach ($business_units as $key => $bu)
                                            <option value="{{$bu->id}}" {{$userdata->users_details->i_ref_bu_id == $bu->id ? 'selected' : ''}}> {{$bu->vc_short_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1"><label> Department<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-2">
                                        <select name="i_ref_dep_id" id="department">
                                            <option value=""> -- Department --</option>
                                            @foreach ($userdata->users_details->business_unit->business_dept as $key => $dept)
                                            @if($dept->dept_data)
                                            <option value="{{$dept->department_id}}" {{$userdata->users_details->i_ref_dep_id == $dept->department_id ? 'selected' : ''}}> {{$dept->dept_data->vc_name}} </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1"><label> Role<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-2">
                                        <select name="i_ref_role_id" id="role">
                                            <option value=""> -- Role --</option>
                                            @foreach ($userdata->users_details->business_unit->roles as $key => $role)
                                            <option value="{{$role->id}}" {{$userdata->users_details->i_ref_role_id == $role->id ? 'selected' : ''}}> {{$role->vc_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-2"> <button type="button" class="btn btn-primary ">Add More</button></div> --}}
                                </div>
                            </div>

                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary" type="submit" id="update-user">Update</button>&nbsp;
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
     * remove class on update buttom
     * 
     */
    $('#update-user').click(function(event) {
        if($("#updateUser").valid()){
        $('#updateUser input').removeClass('form_change');
        $('#updateUser select').removeClass('form_change');
        $('#updateUser textarea').removeClass('form_change');
        }else{
            event.preventDefault();
        }
    });

    /**
     * add class if there is change in any
     * input
     */
    $('#updateUser input, #updateUser select, #updateUser textarea').on('keyup change', function() {
        $(this).addClass('form_change');
    });

    /**
     * alert before leaving window 
     */
    $(window).on('beforeunload', function() {
        if ($('#updateUser input').hasClass('form_change') || $('#updateUser select').hasClass('form_change') ||
            $('#updateUser textarea').hasClass('form_change')) {
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
</script>
@endsection