@extends('admin.layout.app')
@section('title') {{trans('Completed Form Listing')}} @endsection
@section('header_css')
<style>
    .google_map {
        display: none!important;
    }
</style>
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
                    <span>Archive </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-graph font-dark"></i>
                            <span class="caption-subject bold uppercase"> Archive </span>
                        </div>
                    </div>
                    <!-- response messages -->
                    @include ('partials.messages')
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <table id="completed_forms" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead" colspan="2">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search By Id" />
                                    </th>
                                    <th class="filterhead" id="type" colspan="2">
                                        <select class="filter form-control" id="filter_type" style ="padding: 0 4px;">
                                            <option value="">Type</option>
                                            @foreach ($temp_list as $item)
                                            <option value="{{ $item->template_name }}">{{ $item->template_name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="filterhead" colspan="2">
                                        <input class="filter form-control filter_date" id="fiscalYear" type="text"  placeholder="Date"/>
                                    </th>
                                    <th class="filterhead">
                                        <select class="filter form-control" style = "width: 118px; padding: 0 4px;" id="filter_completed">
                                            <option value="">Completed By</option>
                                            @foreach ($completedBy as $comp)
                                                <option value="{{ $comp->vc_fname }}">{{ $comp->vc_fname }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="filterhead">
                                        <select class="filter form-control" style = "width: 107px; padding: 0 4px;" id="filter_department">
                                            <option value="">Department</option>
                                            @foreach ($departments as $department)
                                            <option value="{{ $department->vc_name }}">{{ $department->vc_name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th class="filterhead">
                                        <button class="btn btn-success reset">Reset Filter</button>
                                    </th>
                                </tr>
                                <tr class="top-heading">
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Completed By</th>
                                    <th>Business Unit</th>
                                    <th>Department</th>
                                    <th>Project</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listing as $key => $listing)
                                <tr>
                                    <td>{{ $listing->form_id }}</td>
                                    <td>{{ $listing->template['template_name'] }}</td>
                                    <td>{{ date('m/d/Y', strtotime($listing->created_at)) }}</td>
                                    <td>{{ !empty($listing->title) ? Str::limit($listing->title,15) : '-'}}</td>
                                    <td>{{($listing->completed_by !='')?$listing->completed_by->vc_fname. ' ' .$listing->completed_by->vc_mname. ' '.$listing->completed_by->vc_lname : '-'}}
                                    </td>
                                    <td>{{ !is_null($listing->business)?$listing->business->vc_short_name != '' ? $listing->business->vc_short_name :'-': '-' }}</td>
                                    <td>{{ !is_null($listing->dept_data)?$listing->dept_data->vc_name != '' ? $listing->dept_data->vc_name :'-' :'-' }}</td>
                                    <td>{{ !is_null($listing->project_data)?$listing->project_data->vc_name != '' ? $listing->project_data->vc_name :'-' :'-' }}</td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                            <a href="#" class="restore" data-id="{{ $listing->id }}"><i class="fa fa-archive fa-1x"></i> Restore </a>
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
@endsection

@section('footer_scripts')
<script src="{{asset('assets/js/dropzone.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/js/completed_from.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection
