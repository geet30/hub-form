@extends('admin.layout.app')
@section('title') {{ trans('label.edit_supplier') }} @endsection
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
                    <span>{{ trans('label.edit_supplier') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.edit_supplier') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('suppliers.update', $supplier_data->id_encrypted) }}" id="updateSupplier"
                            method="POST" enctype='multipart/form-data'>
                            @csrf
                            {{ method_field('put') }}
                            <input type="hidden" name="user_detail_id" value="{{$supplier_data->users_details->id ?? ''}}">
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Business<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Full Legal Entity Name" name="bussiness_name" id="bussiness_name" value="{{$supplier_data->bussiness_name}}"></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Business Number" name="buss_no" id="bussiness_number" value="{{$supplier_data->buss_no}}"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @error('bussiness_name')
                                <label for="bussiness_name" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Primary Contact Person<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="First Name" name="vc_fname" id="vc_fname" value="{{$supplier_data->vc_fname}}"></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Last Name" name="vc_lname" id="vc_lname" value="{{$supplier_data->vc_lname}}"></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Short Name" name="vc_sname" id="vc_sname" value="{{$supplier_data->vc_sname}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Email<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="email" placeholder="Email" name="email" id="email" value="{{$supplier_data->email}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Image</label></div>
                                    <div class="col-sm-2"> <img src={{$supplier_data->image_url }} class="default-image" alt="" id="user_image"> </div>
                                    <div class="col-md-2"> 
                                        <label for="vc_image" class="upload-image"> <span  class="btn btn-primary" id="uploadCompanyImage" style="margin-top: 30px; ">Upload Image</span></label>
                                        <input id="vc_image" type="file" name="vc_image" value="" class="vc_image" style="display: none" accept="image/*" onchange="loadFile(event)" />    
                                    </div>

                                   

                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Descriptions Of Products and Services*</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter Descriptions Of Products and Services*..." name="vc_DOPAS" id="vc_DOPAS" style="height: 100px;" value="{{$supplier_data->vc_DOPAS}}"> {{$supplier_data->vc_DOPAS}}</textarea></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Password</label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Password" name="password" id="password" ></div>
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
                                    <div class="col-md-3"> <label>Permissions<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6">
                                        <input type="hidden" value="{{!empty($supplier_data->user_permissions) ? base64_encode(serialize($supplier_data->user_permissions)): ''}}" name="old_permission"> 
                                        <select class="permission_id" multiple="multiple" theme="bootstrap" name="permission_id[]">
                                            @foreach (App\Models\Users::getSupplierPermissionArray() as $key => $value)
                                                    <option value="{{ $key }}" @if(in_array($key, $supplier_data->user_permissions)) selected @endif >{{ $value }}</option>
                                            @endforeach
                                        </select>  
                                    </div>
                                    <div class="col-md-3"> <button type="button" class="btn btn-primary permissions-all">Select all</button></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3" style="margin-bottom: 30px"> <label> <b> Address</b> </label></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"> <label> Head Office </label></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Street" name="hd_office_street" id="hd_office_street" value="{{$supplier_data->users_details->hd_office_street}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="City" name="hd_office_city" id="hd_office_city" value="{{$supplier_data->users_details->hd_office_city}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="State" name="hd_office_state" id="hd_office_state" value="{{$supplier_data->users_details->hd_office_state}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Postcode" name="hd_office_postalcode" id="hd_office_postalcode" value="{{$supplier_data->users_details->hd_office_postalcode}}"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3" ></div>
                                    <div class="col-md-2" >
                                        <select name="hd_office_country" id="hd_office_country">
                                            <option value=""> Select Country </option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{$country->id == $supplier_data->users_details->hd_office_country ? 'selected' : ''}}> {{$country->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2"><input type="text" placeholder="Phone" name="hd_office_phone" id="hd_office_phone" value="{{$supplier_data->users_details->hd_office_phone}}"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-5" style="margin-bottom: 30px"> <label> <input type="checkbox"> Copy Head Address to Local Address</label></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"> <label> Local Office </label></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Street" name="lc_office_street" id="lc_office_street" value="{{$supplier_data->users_details->lc_office_street}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="City" name="lc_office_city" id="lc_office_city" value="{{$supplier_data->users_details->lc_office_city}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="State" name="lc_office_state" id="lc_office_state" value="{{$supplier_data->users_details->lc_office_state}}"></div>
                                    <div class="col-sm-2"><input type="text" placeholder="Postcode" name="lc_office_postalcode" id="lc_office_postalcode" value="{{$supplier_data->users_details->lc_office_postalcode}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3" ></div>
                                    <div class="col-md-2" >
                                        <select name="lc_office_country" id="lc_office_country">
                                            <option value=""> Select Country </option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{$country->id == $supplier_data->users_details->lc_office_country ? 'selected' : ''}}> {{$country->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2"><input type="text" placeholder="Phone" name="lc_office_phone" id="lc_office_phone" value="{{$supplier_data->users_details->lc_office_phone}}"></div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Account Email </label></div>
                                    <div class="col-sm-6"><input type="email" placeholder="Account Email" name="account_email" id="account_email" value="{{$supplier_data->users_details->account_email}}"></div>
                                </div>
                            </div>
                            {{-- <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label> <b> Financial </b> </label></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Bank Name</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank Name" name="bank_name" id="bank_name" value="{{$supplier_data->users_details->bank_name}}"></div>
                                    <div class="col-md-3"> <label>Bank Branch</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank Branch" name="bank_branch" id="bank_branch" value="{{$supplier_data->users_details->bank_branch}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Bank Address</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank Address" name="bank_address" id="bank_address" value="{{$supplier_data->users_details->bank_address}}"></div>
                                    <div class="col-md-3"> <label>Bank Code</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank Code" name="bank_code" id="bank_code" value="{{$supplier_data->users_details->bank_code}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Bank City</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank City" name="bank_city" id="bank_city" value="{{$supplier_data->users_details->bank_city}}"></div>
                                    <div class="col-md-3"> <label>Bank Country</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank Country" name="bank_country" id="bank_country" value="{{$supplier_data->users_details->bank_country}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Account Number</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Account Number" name="bank_account_no" id="bank_account_no" value="{{$supplier_data->users_details->bank_account_no}}"></div>
                                    <div class="col-md-3"> <label>Account Name</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Account Name" name="bank_account_name" id="bank_account_name" value="{{$supplier_data->users_details->bank_account_name}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>SWIFT Code <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="SWIFT Code " name="swift_code" id="swift_code" value="{{$supplier_data->swift_code}}"></div>
                                    <div class="col-md-3"> <label>Bank BSB Number <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Bank BSB Number" name="bank_BSB_number" id="bank_BSB_number" value="{{$supplier_data->bank_BSB_number}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Tax File Number <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Tax File Number" name="tax_File_number" id="tax_File_number" value="{{$supplier_data->tax_File_number}}"></div>
                                    <div class="col-md-3"> <label>Australian Business Number <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Australian Business Number" name="australlian_business_number" id="australlian_business_number" value="{{$supplier_data->australlian_business_number}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Company Business Number <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Company Business Number" name="company_business_number" id="company_business_number" value="{{$supplier_data->company_business_number}}"></div>
                                </div>
                            </div>
                            
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label> <b> Payment Detail </b> </label></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Payment Currency</label></div>
                                    <div class="col-sm-3"><input type="text" placeholder="Payment Currency" name="payment_currency" id="payment_currency" value="{{$supplier_data->users_details->payment_currency}}"></div>
                                    <div class="col-md-3"> <label>Payment Details for Beneficiary</label></div>
                                    <div class="col-sm-3"><textarea placeholder="Beneficiary Details..." name="beneficiary_details" id="beneficiary_details" style="height: 100px;" value="{{$supplier_data->users_details->beneficiary_details}}">{{$supplier_data->users_details->beneficiary_details}}</textarea></div>
                                </div>
                            </div> --}}
                            
                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary"  type="submit" id="update-supplier">Update</button>&nbsp;
                                        <a href="{{route('suppliers.index')}}" class="btn btn-default">Cancel</a>
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
<script src="{{asset('assets/js/suppliers.js')}}"></script>
<script>
    $(document).ready(function () {;
        $(".permission_id").select2({
            theme: "classic",
            placeholder: "Select Permissions",
            allowClear: true
        });
        /**
         * Select All business-units 
         */
        $(".permissions-all").click(function () {
            // if ($(".business-units").find('option:selected').length == 0) {
                $(".permission_id > option").prop("selected", "selected");
                $(".permission_id").trigger("change");
            // } else {
            //     $(".business-units > option").removeAttr("selected");
            //     $(".business-units").trigger("change");
            // }
        });

       
    });

    /**
    * remove class on save buttom
    * 
    */
    $('#update-supplier' ).click(function(){
        $('#updateSupplier input').removeClass('form_change');
        $('#updateSupplier select').removeClass('form_change');
        $('#updateSupplier textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#updateSupplier input, #updateSupplier select, #updateSupplier textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#updateSupplier input').hasClass('form_change') || $('#updateSupplier select').hasClass('form_change') 
        || $('#updateSupplier textarea').hasClass('form_change') ){
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
