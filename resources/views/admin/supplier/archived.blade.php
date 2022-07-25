@extends('admin.layout.app')
@section('title') {{ trans('label.archived_supplier') }} @endsection
@section('header_css')
<style>
    .buttons-html5 {
        display: none;
    }
    .create_supplier {
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
                    <span>{{ trans('label.archived_supplier') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-user font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.archived_supplier') }} </span>
                        </div>
                        {{-- <div class="btn-group pull-right">
                            <a href="{{ route('business-units.create') }}" class="btn sbold green">{{ trans('label.create_bu') }}</a>
                        </div> --}}
                    </div>
                    
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <table id="suppliers_table" class="table-responsive" style="display: none">
                            <thead>
                                <tr>
                                    <th class="filterhead"></th>
                                    <th class="filterhead"></th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Business Name" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:200px; padding: 5px;" type="text" placeholder="Description of Products" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Primary Contact Person" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:120px; padding: 5px;" type="text" placeholder="Phone" />
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" style="width:180px; padding: 5px;" type="text" placeholder="Email" />
                                    </th>
                                    <th class="filterhead">
                                        <select class="filter form-control" id="status" style="width:120px; padding: 5px;">
                                            <option value="">Select Status</option>
                                            <option value="Active">Activated</option>
                                            <option value="Deactivated">Deactivated</option>
                                        </select>
                                    </th> 
                                    <th class="filterhead">
                                        <select class="filter form-control" id="status" style="width:120px; padding: 5px;">
                                            <option value="">Supplier Status</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                    </th>                                    
                                    <th class="filterhead">
                                        <button class="btn btn-success reset">Reset Filter</button>
                                    </th>
                                </tr>
                                <tr class="top-heading">
                                    <th>S No</th>
                                    <th>Image</th>
                                    <th>Business Name</th>
                                    <th>Description of Products</th>
                                    <th>Primary Contact Person</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Supplier Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($supplier_data as $key => $supplierdata)
                                @php 
                                        $supplier=$supplierdata->user;
                                        if(!empty($supplier)):
                                @endphp
                                        <tr>
                                            <td> {{$i}} </td>
                                            <td>
                                                <img src="{{$supplier->image_url}}" alt="{{$supplier->bussiness_name ?? '-'}}" width="40px" height="40px">
                                            </td>
                                            <td>{{$supplier->bussiness_name ?? '-'}}</td>
                                            <td>{{ !empty($supplier->vc_DOPAS)? Str::limit($supplier->vc_DOPAS,25): '-'}}</td>
                                            <td>{{ !empty($supplier->vc_fname)? $supplier->vc_fname.' '.$supplier->vc_mname.' '.$supplier->vc_lname: '-'}}</td>
                                            <td>{{ !empty($supplier['users_details']) ? !empty($supplier['users_details']['hd_office_phone'])? $supplier['users_details']['hd_office_phone'] : '-' : '-' }}</td>
                                            <td>{{$supplier->email ?? '-'}}</td>
                                            <td><div style="display:none;" >{{($supplier['users_details']['i_status'] == 1) ? 'Active' : 'Deactivated'}}</div> 
                                            <span class="deactivated project_status " title="Deactivated" data-id="{{$supplier->id}}" data-toggle='1'></span> 
                                            </td>
                                            <td>{{ $supplier->i_status == 1? 'Approved' : 'Pending' }}</td>
                                            <td>
                                                <div class="dropdown more-btn">
                                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu_{{$key}}"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span>...</span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                        <a href="#" class="dropdown-item restore_supplier" data-id="{{ $supplier->id_encrypted }}"><i
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
@endsection

@section('footer_scripts')
    <script src="{{asset('assets/js/suppliers.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection

