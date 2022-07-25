<?php //pr($detail);die('====='); ?>
@extends('admin.layout.app')
@section('title') {{ trans('label.roles_detail') }} @endsection
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
                    <a href="{{ route('roles.index') }}">{{ trans('label.roles') }}</a>
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
                        <h1>{{ trans('label.roles_detail') }}</h1>
                        <div class="uploaddocadin">
                            <a href="{{route('roles.index')}}"><button class="cancel-btn">Close</button></a>
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
                                                        <div class="userimg"><img src= {{!empty($roleData->user_detail) && !empty($roleData->user_detail->user) ? $roleData->user_detail->user->image_url : asset('assets/edit_form/images/defaultpic.jpeg')}}></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Name</th>
                                                        <td>{{$roleData->vc_name ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Company</th>
                                                        <td>{{$roleData->company->vc_company_name ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Assigned User</th>
                                                        <td>{{!empty($roleData->user_detail) && !empty($roleData->user_detail->user) ? $roleData->user_detail->user->vc_fname. ' '.$roleData->user_detail->user->vc_mname. ' '.$roleData->user_detail->user->vc_lname : 'No User Asigned!'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Level</th>
                                                        <td>{{$roleData->level->vc_name ?? '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Authorization Limit</th>
                                                        <td>{{'$'.$roleData->level->i_start_limit .'-$'. $roleData->level->i_end_limit }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{ $roleData->i_status == 1?'Activated':'Deactivated' }}</td>
                                                    </tr>
                                                    @php
                                                        $created_date_timestamp = !empty($roleData->created_at) ? strtotime($roleData->created_at) : '';
                                                        $created_date = !empty($created_date_timestamp) ? date('d M Y H:i A', $created_date_timestamp) : '-'; 
                                                        $update_date_timestamp = !empty($roleData->modified_at) ? strtotime($roleData->modified_at) : '';
                                                        $update_date = !empty($update_date_timestamp) ? date('d M Y H:i A', $update_date_timestamp) : '-'; 
                                                    @endphp
                                                    <tr>
                                                        <th>Created date</th>
                                                        <td>{{ $created_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last modified date</th>
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
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseTwo">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="" contenteditable="false">List of Permissions assigned to this Role</span>
                                                      </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseThree" class="collapse business-unit" aria-labelledby="collapseThree" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    @if(count($roleData['role_permission']) > 0)
                                                        <tbody>
                                                            @php $i=1; @endphp
                                                            @foreach ($roleData['role_permission'] as $role_permission)
                                                                <tr>
                                                                    <td>{{$i}}</td>
                                                                    <td>{{$role_permission->permission->vc_name}} </td>
                                                                    <td>{{!empty($role_permission->permission->vc_description) ? Str::limit($role_permission->permission->vc_description, 20) : '-'}} </td>
                                                                </tr>
                                                                @php $i++; @endphp
                                                            @endforeach
                                                        </tbody>
                                                    @else
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="6" class="text-center">
                                                                    <span class="form_nodata"> No permission assigned to this Role. </span>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    @endif
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
                                                        List of Form Permissions assigned to this Role
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
                                                            </tr>
                                                        </thead>
                                                        @if(count($roleData['role_form_permission']) > 0)
                                                            <tbody>
                                                                @php $j=1; @endphp
                                                                @foreach ($roleData['role_form_permission'] as $role_form_permission)
                                                                    <tr>
                                                                        <td>{{$j}}</td>
                                                                        <td>{{$role_form_permission->form_permission->vc_name}} </td>
                                                                        <td>{{!empty($role_form_permission->form_permission->vc_description) ? Str::limit($role_form_permission->form_permission->vc_description, 20) : '-'}} </td>
                                                                    </tr>
                                                                    @php $j++; @endphp
                                                                @endforeach
                                                            </tbody>
                                                        @else
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="6" class="text-center">
                                                                        <span class="form_nodata"> No Form Permission assigned to this Role. </span>
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


                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
                                                      <i class="fas fa-caret-down"></i>
                                                      <i class="fas fa-caret-right"></i>
                                                        List of Child Roles of this Role
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
                                                                <th>Parent</th>
                                                            </tr>
                                                        </thead>
                                                        @if(count($roleData['child_roles']) > 0)
                                                            <tbody>
                                                                @php $j=1; @endphp
                                                                @foreach ($roleData['child_roles'] as $child_roles)
                                                                    <tr>
                                                                        <td>{{$j}}</td>
                                                                        <td>{{$child_roles->vc_name}} </td>
                                                                        <td>{{$child_roles->parent_role->vc_name}} </td>
                                                                    </tr>
                                                                    @php $j++; @endphp
                                                                @endforeach
                                                            </tbody>
                                                        @else
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="6" class="text-center">
                                                                        <span class="form_nodata"> No Child roles of this Role. </span>
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
