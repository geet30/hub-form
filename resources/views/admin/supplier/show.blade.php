<?php //pr($detail);die('====='); ?>
@extends('admin.layout.app')
@section('title') {{ trans('label.supplier_detail') }} @endsection
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
                    <a href="{{ route('suppliers.index') }}">{{ trans('label.suppliers') }}</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                  <span> Details </span>
                </li>
            </ul>
        </div>
        <div class="row">
        <div class="wrapper" style="padding:12px;">
        <div class="">
            <div class="page-wrap edit_form_accordion">
                <div class="adinfull">
                    <div class="addinname">
                        <h1>{{ trans('label.supplier_detail') }}</h1>
                        <div class="uploaddocadin">
                            <a href="{{route('suppliers.index')}}"><button class="cancel-btn">Close</button></a>
                        </div>
                    </div>
                    <div class="row">
                    </div>
                   
                    <div class="datadocumentcollaspe">
                        <div class="collaspeinner">
                            <div id="accordion">
                                <div class="card">
                                    <div id="collapseOne" class="collapse in business-unit" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                        <div class="userimg"><img src= {{$supplier_data->image_url}}></div>
                                                </div>
                                            </div>
                                            @php
                                                $created_date_timestamp = !empty($supplier_data->created_at) ? strtotime($supplier_data->created_at) : '';
                                                $created_date = !empty($created_date_timestamp) ? date('d M Y H:i A', $created_date_timestamp) : '-'; 
                                                $update_date_timestamp = !empty($supplier_data->modified_at) ? strtotime($supplier_data->modified_at) : '';
                                                $update_date = !empty($update_date_timestamp) ? date('d M Y H:i A', $update_date_timestamp) : '-'; 
                                            @endphp
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Name</th>
                                                        <td>{{$supplier_data->bussiness_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email</th>
                                                        <td>{{$supplier_data->email ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Primary Contact Person</th>
                                                        <td>{{$supplier_data->vc_fname. ' '.$supplier_data->vc_lname}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <td>{{!empty($supplier_data->users_details) && !empty($supplier_data->users_details->bank_name) ? $supplier_data->users_details->bank_name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Account No.</th>
                                                        <td>{{!empty($supplier_data->users_details) && !empty($supplier_data->users_details->bank_account_no) ? $supplier_data->users_details->bank_account_no : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Email</th>
                                                        <td>{{!empty($supplier_data->users_details) && !empty($supplier_data->users_details->account_email) ? $supplier_data->users_details->account_email : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>SWIFT Code</th>
                                                        <td>{{$supplier_data->swift_code ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank BSB Number</th>
                                                        <td>{{$supplier_data->bank_BSB_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tax File number</th>
                                                        <td>{{$supplier_data->tax_File_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Australian Business Number (ABN)</th>
                                                        <td>{{$supplier_data->australlian_business_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Company Business Number</th>
                                                        <td>{{$supplier_data->company_business_number ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{$supplier_data->users_details->i_status == 1 ? 'Activated' : 'Deactivated' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created date</th>
                                                        <td>{{$created_date ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last modified date</th>
                                                        <td>{{$update_date ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="" contenteditable="false">Description of the Product and the Services</span>
                                                      </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseTwo" class="collapse business-unit" aria-labelledby="collapseTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="filterwidth ">
                                                    <div class="" id="">
                                                        @if(!empty($supplier_data->vc_DOPAS))
                                                            <textarea placeholder="Description..." disabled="true">{{$supplier_data->vc_DOPAS}}</textarea>
                                                        @else
                                                            <span class="no-data"> No data found ! </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseTwo">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="" contenteditable="false">Head Office</span>
                                                      </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseThree" class="collapse business-unit" aria-labelledby="collapseThree" data-parent="#accordion">
                                        <div class="card-body">                                            
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Head Office Street</th>
                                                        <td>{{$supplier_data->users_details->hd_office_street ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Head Office City</th>
                                                        <td>{{$supplier_data->users_details->hd_office_city ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Head Office State</th>
                                                        <td>{{$supplier_data->users_details->hd_office_state ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Head Office Postalcode </th>
                                                        <td>{{$supplier_data->users_details->hd_office_postalcode ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Head Office Country</th>
                                                        <td>{{$supplier_data->users_details->hd_office_country ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Head Office Phone</th>
                                                        <td>{{$supplier_data->users_details->hd_office_phone ?? '-'}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefour" aria-expanded="true" aria-controls="collapsefour">
                                                      <i class="fas fa-caret-down"></i>
                                                      <i class="fas fa-caret-right"></i>
                                                        Local Office
                                                    </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="collapsefour" class="collapse business-unit" aria-labelledby="collapsefour" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="filterwidth">
                                                    <table class="table">
                                                        <tr>
                                                            <th>Local Office Street</th>
                                                            <td>{{$supplier_data->users_details->lc_office_street ?? '-'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Local Office City</th>
                                                            <td>{{$supplier_data->users_details->lc_office_city ?? '-'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Local Office State</th>
                                                            <td>{{$supplier_data->users_details->lc_office_state ?? '-'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Local Office Postalcode </th>
                                                            <td>{{$supplier_data->users_details->lc_office_postalcode ?? '-'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Local Office Country</th>
                                                            <td>{{$supplier_data->users_details->lc_office_country ?? '-'}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Local Office Phone</th>
                                                            <td>{{$supplier_data->users_details->lc_office_phone ?? '-'}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          </div>
        </div>
</div>
@endsection

@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
