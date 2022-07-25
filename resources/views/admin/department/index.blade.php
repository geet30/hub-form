@extends('admin.layout.app')
@section('title') {{ trans('label.deparments') }} @endsection
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
                    <span>{{ trans('label.deparments') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-briefcase font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.deparments') }} </span>
                        </div>
                        {{-- <div class="btn-group pull-right">
                            <a href="{{ route('business-units.create') }}" class="btn sbold green">{{ trans('label.create_bu') }}</a>
                        </div> --}}
                    </div>
                    
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <table id="dept_table" class="table-responsive">
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
                                    <th>Business Units</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($dept_data as $key => $dept)
                                <?php //pr($bu->business_dept);die; ?>
                                <tr>
                                    <td> {{$i}} </td>
                                    <td>
                                        <a href="{{ route('departments.show', $dept->id_encrypted) }}">
                                            {{$dept->vc_name ?? '-'}}
                                        </a>
                                    </td>
                                    <td>
                                        @php $business = []; @endphp
                                        @foreach ($dept->dept_bu as $dept_bu)
                                            @php    
                                                if (isset($dept_bu->bu_data->vc_short_name)) {
                                                    $business[] = $dept_bu->bu_data->vc_short_name;
                                                }
                                            @endphp
                                        @endforeach  
                                        {{!empty($business) ? Str::limit(implode (", ", $business),  30) : '-'}}
                                    </td>
                                    <td><div style="display:none;">{{$dept->i_status}}</div>@if($dept->i_status == 1 )<span class="activated bu_status" title="Activated" data-id="activated" ></span> @else <span class="deactivated bu_status" title="Deactivated" data-id="deactivated" ></span> @endif</td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu_{{$key}}"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu_{{$key}}">
                                                <a href="{{ route('departments.edit', $dept->id_encrypted) }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-pencil fa-1x"></i> Edit
                                                </a>
                                                <a href="{{ route('departments.show',  $dept->id_encrypted) }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-eye fa-1x"></i> View
                                                </a>
                                                <a href="#" class="dropdown-item archive_bu" data-id="{{$dept->id_encrypted}}">
                                                    <i class="fa fa-archive fa-1x"></i> Archive
                                                </a>
                                            </div>
                                        </div>
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
    <script src="{{asset('assets/js/department.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection

