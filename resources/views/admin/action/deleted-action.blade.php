@extends('admin.layout.app')
@section('title'){{ trans('label.action_listing') }}@endsection
@section('header_css')
@if (auth()->user()->user_type == 'supplier')
<style>
    .buttons-excel {
        display: none;
    }
</style>
@endif
<style>
    .create_action {
        display: none!important;
    }
</style>
@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Archive Actions </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-graph font-dark"></i>
                            <span class="caption-subject bold uppercase">Archive Actions</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        @if($action_listings)
                        <?php $department = '';
                            $assignee = '';
                        foreach ($action_listings as $key => $value) {
                            if(isset($value['department']) &&  (!empty($value['department']['vc_name']) || $value['department']['vc_name'] != '')){
                                $department .= '<option value="'.$value['department']['vc_name'].'">'.$value['department']['vc_name'].'</option>';
                            }

                            switch (auth()->user()->user_type) {
                                case 'supplier':
                                    if(isset($value['user'])){
                                        $assignee .= '<option value="' . $value['user']['full_name'] . '">'. $value['user']['full_name'] .'</option>';
                                    }
                                    break;
                                default:
                                    if(isset($value['assignee_user']) && (!empty($value['assignee_user']['vc_fname']) || $value['assignee_user']['vc_fname'] != '')){
                                        $assignee .= '<option value="'.$value['assignee_user']['vc_fname'].' '.$value['assignee_user']['vc_mname'].' '.$value['assignee_user']['vc_lname'].'">'.$value['assignee_user']['vc_fname'].' '.$value['assignee_user']['vc_mname'].' '.$value['assignee_user']['vc_lname'].'</option>';
                                    }
                                    break;
                            }
                        }
                      ?>
                        @endif
                        <input type="hidden" name="department" id="dept_value" value='<?= $department; ?>'>
                        <input type="hidden" name="assignee_value" id="assignee_value" value='<?= $assignee; ?>'>
                        <table id="action_table" class="display-table table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead">ID, Source, Title, Business unit, Project</th>
                                    <th class="filterhead">Status</th>
                                    <th class="filterhead">Due Date</th>
                                    <th class="filterhead">Department</th>
                                    <th class="filterhead">Assignee</th>
                                    <th class="filterhead">Action</th>
                                </tr>
                                <tr class="top-heading">

                                    <th>ID</th>
                                    <th>Source</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    @switch(auth()->user()->user_type)
                                    @case('supplier')
                                    <th>Asigned By</th>
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
                                <?php $preDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) ); ?>
                                @foreach($action_listings as $actions)
                                <?php $newDueDate = date("m/d/Y", strtotime($actions->due_date));  ?>
                                <tr>
                                    <td>A00{{$actions->id ?? ''}}</td>
                                    <td form_id="{{$actions->completed_form_id ?? ''}}">
                                        <?php if(!empty($actions->completed_form_id)){ ?>
                                        <a href="{{ !empty($actions->completedForm->id_decrypted)? route('report', ['id' => $actions->completedForm->id_decrypted]):'' }}"
                                            target="_blank">{{ $actions->completedForm->form_id ?? '-'}}
                                        </a><?php }else{ echo "-";} ?>
                                    </td>
                                    <td>{{ !empty($actions->title) ? Str::limit($actions->title,15) : '-'}}</td>
                                    <td>{{ !empty($actions->descriptions)? Str::limit($actions->descriptions, 10) : '-' }}</td>
                                    <td>{{$newDueDate ?? '-'}}</td>
                                    @switch(auth()->user()->user_type)
                                    @case('supplier')
                                    <td>{{ $actions->user->full_name }}</td>
                                    @break
                                    @default
                                    <td>{{ $actions->assignee_user ? $actions->assignee_user->full_name : '-' }}</td>
                                    @endswitch
                                    <td>{{$actions->business_unit['vc_short_name'] ?? '-'}}</td>
                                    <td>{{$actions->department['vc_name'] ?? '-'}}</td>
                                    <td>{{$actions->project['vc_name'] ?? '-'}}</td>
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
                                                
                                                <a href="#" class="dropdown-item restore_action" data-id="{{ $actions->id }}"><i
                                                    class="fa fa-archive fa-1x"></i> Restore </a>
                                            </div>
                                        </div>
                                        {{-- <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <a href="#" class="restore_action" data-id="{{ $actions->id }}"><i
                                                        class="fa fa-archive fa-1x"></i> Restore </a>
                                            </div>
                                        </div> --}}
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

@endsection
@section('footer_scripts')
<script src="{{asset('assets/js/action.js')}}"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection
