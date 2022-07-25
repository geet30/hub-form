@extends('admin.layout.app')
@section('title') {{ trans('label.business_unit') }} @endsection
@section('header_css')
<style>
    .buttons-html5 {
        display: none;
    }
    .create_bu {
        display: none!important;
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
                    <span>{{ trans('label.archive_bu') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-briefcase font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.archive_bu') }} </span>
                        </div>
                    </div>
                    
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <table id="bu_forms" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead"></th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search Name" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search Location" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search Deparment" />
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
                                    <th>Locations</th>
                                    <th>Depatment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($bu_data as $key => $bu)
                                <tr>
                                    <td> {{$i}} </td>
                                    <td>{{$bu->vc_short_name ?? '-'}}</td>
                                    <td>{{$bu->locations->vc_name ?? '-'}}</td>
                                    <td>
                                        @php $department = []; @endphp
                                        @foreach ($bu->business_dept as $bu_dept)
                                            @php    
                                                if (isset($bu_dept->dept_data->vc_name)) {
                                                    $department[] = $bu_dept->dept_data->vc_name;
                                                }
                                            @endphp
                                        @endforeach  
                                        {{!empty($department) ? Str::limit(implode (", ", $department),  30) : '-'}}
                                    </td>
                                    <td><div style="display:none;">{{$bu->i_status}}</div>@if($bu->i_status == 1 )<span class="activated bu_status" title="Activated" data-id="activated" ></span> @else <span class="deactivated bu_status" title="Deactivated" data-id="deactivated" ></span> @endif</td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu_{{$key}}"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <a href="#" class="dropdown-item restore_bu" data-id="{{ $bu->id_encrypted }}"><i
                                                    class="fa fa-archive fa-1x"></i> Restore </a>
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
    <script src="{{asset('assets/js/business.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection

