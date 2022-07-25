@extends('admin.layout.app')
@section('title'){{ trans('label.action_listing') }}@endsection
@section('header_css')
@if (auth()->user()->user_type == 'supplier')
<style>
    .buttons-excel {
        display: none;
    }
    .create_action {
        display: none;
    }

</style>
@endif
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
                    <span>{{ trans('label.actions') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.actions') }} </span>
                        </div>
                        <input type="hidden" id="create_action_url" value="{{url('admin/create-action')}}">
                        <input type="hidden" id="action_status" value="{{$status}}">
                        {{-- <div class="btn-group pull-right">
                            <a href="{{url('admin/create-action')}}" id="sample_editable_1_new"
                                class="btn sbold green">{{ trans('label.create_actions') }}</a>
                        </div> --}}
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        @if($action_listings)
                        <?php $department = '';
                            $assignee = '';
                        foreach ($departments as $department_value) {
                           if(!empty($department_value['vc_name']) || $department_value['vc_name'] != ''){
                            $department .= '<option value="'.$department_value['vc_name'].'">'.$department_value['vc_name'].'</option>';
                           }
                        }
                        foreach($users as $assignee_user){
                            if(!empty($assignee_user)){
                                $assignee .= '<option value="'.$assignee_user['vc_fname'].' '.$assignee_user['vc_mname'].' '.$assignee_user['vc_lname'].'">'.$assignee_user['full_name'].'</option>';
                            }
                        }
                      ?>
                        @endif
                        <input type="hidden" name="department" id="dept_value" value='<?= $department; ?>'>
                        <input type="hidden" name="assignee_value" id="assignee_value" value='<?= $assignee; ?>'>
                        <table id="action_table" class="display-table table-responsive" style="display: none">
                            <thead>
                                <tr>
                                    <th class="filterhead">ID, Source, Title, Business unit, Project</th>
                                    <th class="filterhead">Status</th>
                                    <th class="filterhead">Due Date</th>
                                    <th class="filterhead">Department</th>
                                    @switch(auth()->user()->user_type)
                                    @case('supplier')
                                    <th class="filterhead">Assigned By</th>
                                    @break
                                    @default
                                    <th class="filterhead">Assignee</th>
                                    @endswitch
                                    <th class="filterhead">Action</th>
                                </tr>
                                <tr class="top-heading">
                                    <th>#</th>
                                    <th>Action ID</th>
                                    <th>Source</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    @switch(auth()->user()->user_type)
                                    @case('supplier')
                                    <th>Assigned By</th>
                                    @break
                                    @default
                                    <th>Assignee</th>
                                    @endswitch
                                    <th>Business Unit</th>
                                    <th>Department</th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $preDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) ); @endphp
                                @foreach($action_listings as $actions)
                                @php $id = encrypt_decrypt("encrypt", $actions->id); @endphp
                                @php $newDueDate = date("m/d/Y", strtotime($actions->due_date)); @endphp
                                <tr>
                                    <td>  {{ $actions->id }}</td>
                                    <td> <a href="{{ route('actions.view', $id )}}"> {{!empty($actions->action_id) ? $actions->action_id : 'A00'. $actions->id }}</a> </td>
                                    <td form_id="{{$actions->completed_form_id ?? ''}}">
                                        <?php if(!empty($actions->completed_form_id)){ ?>
                                        <a href="{{ !empty($actions->completedForm->id_decrypted)? route('report', ['id' => $actions->completedForm->id_decrypted]):'' }}"
                                            target="_blank">{{ $actions->completedForm->form_id ?? '-'}}
                                        </a><?php }else{ echo "-";} ?>
                                    </td>
                                    <td>{{ !empty($actions->title) ? Str::limit($actions->title,15) : '-'}}</td>
                                    <td>{{ !empty($actions->descriptions)? Str::limit($actions->descriptions, 10) : '-' }}</td>
                                    <td>{{ $newDueDate ?? '-'}}</td>
                                    @switch(auth()->user()->user_type)
                                        @case('supplier')
                                        <td>{{ $actions->user->full_name?? '-' }}</td>
                                        @break
                                        @default
                                        <td>{{ $actions->assignee_user ? $actions->assignee_user->full_name : '-' }}</td>
                                    @endswitch
                                    <td>{{ ($actions->business_unit !="")? $actions->business_unit->vc_short_name : "-" }}
                                    </td>
                                    <td>{{ ($actions->department !="")?$actions->department->vc_name:"-" }}
                                    </td>
                                    <td>{{ ($actions->project !="")?$actions->project->vc_name:"-" }}
                                    </td>
                                    <td>
                                        <span style="background-color:{{ $actions->status_color }}"
                                            class="label label-default action-status">{{ $actions->status_name }}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                               <a href="{{ route('actions.view', $id )}}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-eye"></i> {{trans('label.view')}}
                                                </a>
                                                @switch($actions->status)
                                                @case(3)
                                               
                                                <a class="dropdown-item archive_actions" data-id="{{ $id }}"
                                                    onclick='archive_actions("{{$id }}")'>
                                                    <i class="fa fa-archive"></i> {{trans('label.archive')}}
                                                </a>
                                                @break
                                                @case(4)
                                                
                                                <a class="dropdown-item archive_actions" data-id="{{$id}}"
                                                    onclick='archive_actions("{{ $id}}")'>
                                                    <i class="fa fa-archive"></i> {{trans('label.archive')}}
                                                </a>
                                                @break
                                                @case(5)
                                                <a href="{{url('admin/edit-action')}}/{{$id }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-pencil"></i> {{trans('label.edit')}}
                                                </a>
                                                <a class="dropdown-item archive_actions" data-id="{{ $id }}"
                                                    onclick='archive_actions("{{$id }}")'>
                                                    <i class="fa fa-archive"></i> {{trans('label.archive')}}
                                                </a>
                                                @break
                                                @default
                                                <a href="{{url('admin/edit-action')}}/{{$id }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-pencil"></i> {{trans('label.edit')}}
                                                </a>
                                                <a class="dropdown-item close_actions" data-id=""
                                                    onclick="close_action('{{$actions->id}}')">
                                                    <i class="fa fa-archive"></i> {{trans('label.complete')}}
                                                </a>
                                                @endswitch
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.complete-action-modal')

@endsection
@section('footer_scripts')
<script src="{{asset('assets/js/action.js')}}"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection
