<?php //pr($detail);die('====='); ?>
@extends('admin.layout.app')
@section('title') {{ trans('label.user_detail') }} @endsection
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
                    <a href="{{ route('users.index') }}">{{ trans('label.users') }}</a>
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
                        <h1>{{ trans('label.user_detail') }}</h1>
                        <div class="uploaddocadin">
                            <a href="{{route('users.index')}}"><button class="cancel-btn">Close</button></a>
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
                                                        <div class="userimg"><img src= {{$userdata->image_url}}></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Name</th>
                                                        <td>{{$userdata->vc_fname. ' '.$userdata->vc_mname. ' '.$userdata->vc_lname}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email</th>
                                                        <td>{{$userdata->email ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{$userdata->address ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact</th>
                                                        <td>{{$userdata->vc_phone ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Business Unit</th>
                                                        <td>{{!empty($userdata->users_details) && !empty($userdata->users_details->business_unit) ? $userdata->users_details->business_unit->vc_short_name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department Assigned</th>
                                                        <td>{{!empty($userdata->users_details) && !empty($userdata->users_details->department) ? $userdata->users_details->department->vc_name : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Role Assigned</th>
                                                        <td>{{!empty($userdata->users_details) && !empty($userdata->users_details->roles) ? $userdata->users_details->roles->vc_name : '-' }}</td>
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
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseTwo">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="" contenteditable="false">Companies in which you registered</span>
                                                      </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseThree" class="collapse business-unit" aria-labelledby="collapseThree" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                        <div class="userimg"><img src= {{$userdata->users_details->company->image_url}}></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Name</th>
                                                        <td>{{$userdata->users_details->company->vc_company_name ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{$userdata->users_details->company->country->name. ', '.$userdata->users_details->company->state->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Description</th>
                                                        <td>{{$userdata->users_details->company->vc_description ?? '-'}}</td>
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
                                                        List of Projects assigned to this User
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
                                                                <th>Description</th>
                                                                <th>Created Date</th>
                                                                <th>Modified Date</th>
                                                            </tr>
                                                        </thead>
                                                        @if(count($userdata['user_project']) > 0)
                                                            <tbody>
                                                                @php $i=1; @endphp
                                                                @foreach ($userdata['user_project'] as $user_project)
                                                                @php
                                                                    $created_date_timestamp = !empty($user_project->project->created) ? strtotime($user_project->project->created) : '';
                                                                    $created_date = !empty($created_date_timestamp) ? date('d M Y H:i A', $created_date_timestamp) : '-'; 
                                                                    $update_date_timestamp = !empty($user_project->project->modified) ? strtotime($user_project->project->modified) : '';
                                                                    $update_date = !empty($update_date_timestamp) ? date('d M Y H:i A', $update_date_timestamp) : '-'; 
                                                                @endphp
                                                                    <tr>
                                                                        <td>{{$i}}</td>
                                                                        <td>{{$user_project->project->vc_name}} </td>
                                                                        <td>{{!empty($user_project->project->vc_description) ? Str::limit($user_project->project->vc_description, 20) : '-'}} </td>
                                                                        <td>{{$created_date }} </td>
                                                                        <td>{{$update_date}} </td>
                                                                    </tr>
                                                                    @php $i++; @endphp
                                                                @endforeach
                                                            </tbody>
                                                        @else
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="6" class="text-center">
                                                                        <span class="form_nodata"> No project assigned to this business unit. </span>
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
