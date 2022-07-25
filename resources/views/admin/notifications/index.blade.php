@extends('admin.layout.app')
@section('title') {{('Notifications')}} @endsection
@section('header_css')
<link href="{{ asset('assets/css/jquery.datatables.css') }}" rel="stylesheet">
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
                    <span> Notifications </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-bulb font-dark"></i>
                            <span class="caption-subject bold uppercase"> Notifications </span>
                        </div>
                    </div>
                    <!-- response messages -->
                    @include ('partials.messages')
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        
                        <table id="notifications" class="display-no-important">
                            <thead>
                                <tr>
                                    <th class="filterhead"></th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text" placeholder="Search Message" />
                                    </th>
                                    <th class="filterhead">
                                        <button class="btn btn-success reset">Reset Filter</button>
                                    </th>
                                </tr>
                                <tr>
                                    <th>S.no</th>
                                    <th>Message</th>
                                    <th>Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $key => $row)
                                    @if(isset($row->notification_type) && $row->notification_type == 30 && isset($row->notificationable->status) && $row->notificationable->status == 1 || isset($row->notificationable->action) && $row->notificationable->action->status == 1)

                                    @else
                                        <tr>
                                            <td>{{ ($key + 1) }} </td>
                                            <td> 
                                                <a @if (!$row->status) class="mark-as-read" data-id="{{ $row->id }}" onclick="return false" @endif href="{{ $row->url }}"> {{ $row->message }} </a>
                                            </td>
                                            <td>{{ $row->created->format(DATE_TIME_FORMAT) }}</td>
                                        </tr>
                                    @endif
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
<script src="{{asset('assets/js/notifications.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection
