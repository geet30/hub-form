<?php //pr($detail);die('====='); ?>
@extends('admin.layout.app')
@section('title') {{ trans('label.project_detail') }} @endsection
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
                    <a href="{{ route('projects.index') }}">{{ trans('label.projects') }}</a>
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
                        <h1>{{ trans('label.project_detail') }}</h1>
                        <div class="uploaddocadin">
                            <a href="{{route('projects.index')}}"><button class="cancel-btn">Close</button></a>
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
                                                    @php $compnyProfilePic = $project_data['company']['vc_logo'] @endphp
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
                                                        <td>{{ $project_data->vc_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Company</th>
                                                        <td>{{ $project_data->company->vc_company_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Business Unit</th>
                                                        <td>{{ $project_data->business_unit->vc_short_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{ $project_data->i_status == 1?'Activated':'Deactivated' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created Date</th>
                                                        @php
                                                            $created_date_timestamp = !empty($project_data->created_at) ? strtotime($project_data->created_at) : '';
                                                            $created_date = !empty($created_date_timestamp) ? date('d M Y H:i A', $created_date_timestamp) : '-'; 
                                                            $update_date_timestamp = !empty($project_data->modified_at) ? strtotime($project_data->modified_at) : '';
                                                            $update_date = !empty($update_date_timestamp) ? date('d M Y H:i A', $update_date_timestamp) : '-'; 
                                                        @endphp
                                                        <td>{{ $created_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Modified Date</th>
                                                        <td>{{ $update_date }}</td>
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
                                                        <span class="" contenteditable="false">Description</span>
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
                                                        @if(!empty($project_data->vc_description))
                                                            <textarea placeholder="Description..." disabled="true">{{$project_data->vc_description}}</textarea>
                                                        @else
                                                            <span class="no-data"> No Description ! </span>
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
                                                        <span class="" contenteditable="false">Comments</span>
                                                      </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseThree" class="collapse business-unit" aria-labelledby="collapseThree" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="filterwidth">
                                                    <div class="" id="">
                                                        @if(!empty($project_data->vc_comment))
                                                            <textarea placeholder="Comments..." disabled="true">{{$project_data->vc_comment}}</textarea>
                                                        @else
                                                            <span class="no-data"> No Comment ! </span>
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
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefour" aria-expanded="true" aria-controls="collapsefour">
                                                      <i class="fas fa-caret-down"></i>
                                                      <i class="fas fa-caret-right"></i>
                                                        List of User assigned to this Project
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
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Name</th>
                                                            </tr>
                                                        </thead>
                                                        @if(count($project_data['project_users']) > 0)
                                                            <tbody>
                                                                @php $i=1; @endphp
                                                                @foreach ($project_data['project_users'] as $project_users)
                                                                    <tr>
                                                                        <td>{{$i}}</td>
                                                                        <td>{{$project_users->users->vc_fname. ' '. $project_users->users->vc_mname. ' '. $project_users->users->vc_lname}} </td>
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
