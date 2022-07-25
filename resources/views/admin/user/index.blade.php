@extends('admin.layout.app')
@section('title') {{ trans('label.users') }} @endsection
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
                    <span>{{ trans('label.users') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-user font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.users') }} </span>
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
                                    <th>Assign/Revoke Projects</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($user_data as $key => $userdata)
                                    @php 
                                        $user=$userdata->user;
                                        if(!empty($user)):
                                        $projects = [];
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
                                            <img src="{{$user->image_url}}" alt="{{$user->vc_fname ?? '-'}}" width="40px" height="40px">
                                        </td>
                                        <td>@if(!empty($user->vc_fname)) <a href="{{ route('users.show', $user->id_encrypted) }}">{{ $user->vc_fname.' '.$user->vc_mname.' '.$user->vc_lname}} </a> @else - @endif </td>
                                        <td>  {{ !empty($user->email)? $user->email : '-'}} </td>
                                        <td>{{ !empty($user->vc_phone) ? $user->vc_phone : '-'}}</td>
                                        <td>{{ !empty($user->country) ? $user->country->name : '-'}}</td>
                                        <td>{{ !empty($user['users_details']) ? !empty($user['users_details']['business_unit'])? $user['users_details']['business_unit']['vc_short_name'] : '-' : '-' }}</td>
                                        <td>{{ !empty($projects)? $projects : '-'}}</td>
                                        <td><div style="display:none;">{{$user['users_details']['i_status']}}</div>
                                         @if($user['users_details']['i_status'] == 0 ) 
                                         <span class="deactivated project_status toggle_status" title="Deactivated" data-id="{{$user->id}}" data-toggle='1'></span>
                                          @else  <span class="activated project_status toggle_status" title="Activated" data-id="{{$user->id}}" data-toggle='0' ></span>  @endif</td>
                                        <td>
                                            <div class="dropdown more-btn">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenu_{{$key}}"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span>...</span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu_{{$key}}">
                                                    <a href="{{ route('users.edit', $user->id_encrypted) }}"
                                                        class="dropdown-item">
                                                        <i class="fa fa-pencil fa-1x"></i> Edit
                                                    </a>
                                                    <a href="{{ route('users.show',  $user->id_encrypted) }}"
                                                        class="dropdown-item">
                                                        <i class="fa fa-eye fa-1x"></i> View
                                                    </a>
                                                    <a href="javascript:void(0);" class="dropdown-item archive_user" data-id="{{$user->id_encrypted}}">
                                                        <i class="fa fa-archive fa-1x"></i> Archive
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.assignRevokeProject', $user->id_encrypted) }}" class="asign-revoke"><i class="glyphicon glyphicon-plus"></i>/<i class="glyphicon glyphicon-minus"></i></a>    
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
@endsection

@section('footer_scripts')
    <script src="{{asset('assets/js/users.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection

