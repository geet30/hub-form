@extends('admin.layout.app')
@section('title') {{ trans('label.projects') }} @endsection
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
                    <span>{{ trans('label.projects') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-briefcase font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.projects') }} </span>
                        </div>
                        {{-- <div class="btn-group pull-right">
                            <a href="{{ route('business-units.create') }}" class="btn sbold green">{{ trans('label.create_bu') }}</a>
                        </div> --}}
                    </div>
                    
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <table id="project_table" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead"></th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search Name" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search Business Unit" />
                                    </th>
                                    <th class="filterhead">
                                        <select class="filter form-control" id="status">
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
                                    <th>Name</th>
                                    <th>Business Unit</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($project_data as $key => $project)
                                <?php //pr($bu->business_dept);die; ?>
                                <tr>
                                    <td> {{$i}} </td>
                                    <td>
                                        <a href="{{ route('projects.show', $project->id_encrypted) }}">
                                            {{$project->vc_name ?? '-'}}
                                        </a>
                                    </td>
                                    <td>{{$project->business_unit->vc_short_name ?? '-'}}</td>
                                    <td><div style="display:none;">{{$project->i_status}}</div> @if($project->i_status == 0 ) <span class="deactivated project_status" title="Deactivated" data-id="deactivated" ></span> @else  <span class="activated project_status" title="Activated" data-id="activated" ></span>  @endif</td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu_{{$key}}"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu_{{$key}}">
                                                <a href="{{ route('projects.edit', $project->id_encrypted) }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-pencil fa-1x"></i> Edit
                                                </a>
                                                <a href="{{ route('projects.show',  $project->id_encrypted) }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-eye fa-1x"></i> View
                                                </a>
                                                <a href="#" class="dropdown-item archive_project" data-id="{{$project->id_encrypted}}">
                                                    <i class="fa fa-archive fa-1x"></i> Archive
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td> 
                                        @if($project->open_close_status == 1 )
                                            <a href="" class="btn sbold green project-close-open" data-id="{{$project->id_encrypted}}" target="{{$project->open_close_status}}">Project Open</a>
                                        @else
                                            <a href="" class="btn sbold red project-close-open" data-id="{{$project->id_encrypted}}" target="{{$project->open_close_status}}">Project Closed</a>
                                        @endif
                                    </td>
                                    </tr>
                                @php $i++; @endphp
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
    <script src="{{asset('assets/js/project.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection

