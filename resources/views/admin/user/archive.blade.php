@extends('admin.layout.app')
@section('title') {{ trans('label.archived_user') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .buttons-html5 {
        display: none;
    }
    .create_user {
        display: none !important;
    }

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

</style>
@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        @include('partials.messages')
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>{{ trans('label.archived_user') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-user font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.archived_user') }} </span>
                        </div>
                        {{-- <div class="btn-group pull-right">
                            <a href="{{ route('business-units.create') }}" class="btn sbold green">{{ trans('label.create_bu') }}</a>
                        </div> --}}
                    </div>
                    
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <table id="users_table" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead"></th>
                                    <th class="filterhead"></th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Name" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:200px; padding: 5px;" type="text" placeholder="Email" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Contact No." />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Country" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Business Unit" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:150px; padding: 5px;" type="text" placeholder="Project" />
                                    </th>
                                    <th class="filterhead">
                                        <select class="filter form-control" id="status" style="width:120px; padding: 5px;">
                                            <option value="">Select Status</option>
                                            <option value="1">Activated</option>
                                            <option value="0">Deactivated</option>
                                        </select>
                                    </th>                                    
                                    <th class="filterhead">
                                        <button class="btn btn-success reset">Reset Filter</button>
                                    </th>
                                </tr>
                                <tr class="top-heading">
                                    <th>S No</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact No.</th>
                                    <th>Country</th>
                                    <th>Business Unit</th>
                                    <th>Projects</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($user_data as $key => $userdata)
                                    @php 
                                        $user=$userdata->user;
                                        $projects = [];
                                        if(!empty($user)):
                                        if(!empty($user->user_project)){
                                            foreach ($user->user_project as $user_project){
                                                if (!empty($user_project->project) && !empty($user_project->project->vc_name)) {
                                                    $projects[] = $user_project->project->vc_name;
                                                }
                                            }
                                            $projects = implode (", ", $projects);
                                        }
                                    @endphp
                                    <tr>
                                        <td> {{$i}} </td>
                                        <td>
                                            <img src="{{$user->image_url}}" alt="{{$user->bussiness_name ?? '-'}}" width="40px" height="40px">
                                        </td>
                                        <td>@if(!empty($user->vc_fname)) {{ $user->vc_fname.' '.$user->vc_mname.' '.$user->vc_lname}} @else - @endif </td>
                                        <td>  {{ !empty($user->email)? $user->email : '-'}} </td>
                                        <td>{{ !empty($user->vc_phone) ? $user->vc_phone : '-'}}</td>
                                        <td>{{ !empty($user->country) ? $user->country->name : '-'}}</td>
                                        <td>{{ !empty($user['users_details']) ? !empty($user['users_details'][0]['business_unit'])? $user['users_details'][0]['business_unit']['vc_short_name'] : '-' : '-' }}</td>
                                        <td>{{ !empty($projects)? $projects : '-'}}</td>
                                        <td><div style="display:none;">{{$user->i_status}}</div> 
                                         <span class="deactivated project_status" title="Deactivated" data-id="deactivated" ></span> </td>
                                        <td>
                                            <div class="dropdown more-btn">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenu_{{$key}}"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span>...</span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <a href="#" class="dropdown-item restore_user" data-id="{{ $user->id_encrypted }}"><i
                                                        class="fa fa-archive fa-1x"></i> Restore </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++; 
                                endif;
                                    @endphp
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- role has user  Modal -->
@include('admin.user.restore_create_role')

@endsection

@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('assets/js/users.js')}}"></script>
    <script src="{{asset('assets/js/business_unit.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
    <script>
            $(document).ready(function() {

$(".form_permissions").select2({
    theme: "classic",
    placeholder: "Select a Form Permissions",
    allowClear: true
});

$(".permissions").select2({
    theme: "classic",
    placeholder: "Select a Permissions",
    allowClear: true
});
});
    </script>
@endsection

