<?php //pr($detail);die('====='); ?>
@extends('admin.layout.app')
@section('title') {{ trans('label.location_detail') }} @endsection
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
                    <a href="{{ route('location.index') }}">{{ trans('label.locations') }}</a>
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
                        <h1>{{ trans('label.location_detail') }}</h1>
                        <div class="uploaddocadin">
                            <a href="{{route('location.index')}}"><button class="cancel-btn">Close</button></a>
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
                                                    @php $compnyProfilePic = $location_data['company']['vc_logo'] @endphp
                                                    @if(isset($compnyProfilePic) && !empty($compnyProfilePic))
                                                        @php  $name = P2B_BASE_URL.'/uploads/company_logos/'.$compnyProfilePic; @endphp
                                                        <div class="userimg"><img src= {{$name}}></div>
                                                    @else
                                                        <div class="userimg"><img src="{{ asset('assets/edit_form/images/defaultpic.jpeg')}}"></div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Name</th>
                                                        <td>{{ !empty($location_data->vc_name ) ? $location_data->vc_name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Company</th>
                                                        <td>{{ !empty($location_data->company) ? $location_data->company->vc_company_name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{ $location_data->i_status == 1?'Activated':'Deactivated' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created Date</th>
                                                        @php
                                                            $created_date_timestamp = !empty($location_data->created_at) ? strtotime($location_data->created_at) : '';
                                                            $created_date = !empty($created_date_timestamp) ? date('d M Y H:i A', $created_date_timestamp) : '-'; 
                                                            $update_date_timestamp = !empty($location_data->modified_at) ? strtotime($location_data->modified_at) : '';
                                                            $update_date = !empty($update_date_timestamp) ? date('d M Y H:i A', $update_date_timestamp) : '-'; 
                                                        @endphp
                                                        <td>{{ $created_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Modified Date</th>
                                                        <td>{{ $update_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address Line 1</th>
                                                        <td>{{ !empty($location_data->vc_address) ? $location_data->vc_address : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address Line 2</th>
                                                        <td>{{ !empty($location_data->vc_address2) ? $location_data->vc_address2 : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact Number</th>
                                                        <td>{{ !empty($location_data->contact_number) ? $location_data->contact_number : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Other Contact Number</th>
                                                        <td>{{ !empty($location_data->other_contact_number) ? $location_data->other_contact_number : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Country</th>
                                                        <td>{{ !empty($location_data->country)?$location_data->country->name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>State</th>
                                                        <td>{{ !empty($location_data->state) ? $location_data->state->name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Postal Code</th>
                                                        <td>{{ !empty($location_data->vc_postal_code) ? $location_data->vc_postal_code : '-' }}</td>
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
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
                                                      <i class="fas fa-caret-down"></i>
                                                      <i class="fas fa-caret-right"></i>
                                                        List of Business Unit associated with this Location
                                                    </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="collapsefive" class="collapse business-unit" aria-labelledby="collapsefive" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="filterwidth">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Name</th>
                                                                <th>Description</th>
                                                                <th>Created Date</th>
                                                                <th>Modified Date</th>
                                                            </tr>
                                                        </thead>
                                                        @if(count($location_data['locations_bu']) > 0)
                                                            <tbody>
                                                                @php $i=1; @endphp
                                                                @foreach ($location_data['locations_bu'] as $business_unit)
                                                                @php    
                                                                    $created_bu = strtotime($business_unit->created_at);
                                                                    $updated_bu = strtotime($business_unit->modified_at);
                                                                @endphp
                                                                    <tr>
                                                                        <td>{{$i}}</td>
                                                                        <td><a href="{{ route('business-units.show', $business_unit->id_encrypted) }}" target="_blank"> {{$business_unit->vc_short_name}} </a></td>
                                                                        <td>{{!empty($business_unit->vc_description) ? $business_unit->vc_description : '-'}}</td>
                                                                        <td>{{date('d M Y H:i A', $created_bu)}}</td>
                                                                        <td>{{date('d M Y H:i A', $updated_bu)}}</td>
                                                                    </tr>
                                                                    @php $i++; @endphp
                                                                @endforeach

                                                            </tbody>
                                                        @else
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="6" class="text-center">
                                                                        <span class="form_nodata"> No Data Found! </span>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        @endif
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
