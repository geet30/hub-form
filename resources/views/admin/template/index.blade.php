@extends('admin.layout.app')
@section('title'){{ trans('label.template_listing') }}@endsection
@section('header_css')
<link href="{{asset('assets/css/jquery-ui.css')}}" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
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
                    <span>{{ trans('label.templates') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-file font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.templates') }} </span>
                        </div>
                        <div class="btn-group pull-right">
                            <a href="{{url('admin/create-template')}}" id="sample_editable_1_new"
                                class="btn sbold green">Create Template</a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        <input type="hidden" id="auth_user" value="{{auth()->user()->id}}">
                        <table id="example" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="searching" type="text"
                                            placeholder="Prefix" />
                                    </th>
                                    <th class="filterhead">
                                        <select class="filter form-control dropdown-filter">
                                            <option value="">Name</option>
                                            @if(count($temp_listings) > 0)
                                            @foreach ($temp_listings as $key => $value)
                                            <option value="{{$value['template_name']}}">{{$value['template_name']}}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </th>
                                    <th class="filterhead">
                                        <input class="filter form-control" id="" type="text"
                                            placeholder="Description" />
                                    </th>
                                    <th class="filterhead">
                                        <form class="date-filter"><input class="filter form-control" id="fiscalYear"
                                                type="text" placeholder="Created Date" /><span
                                                class="glyphicon glyphicon-calendar"></span></form>
                                    </th>
                                    {{-- <th class="filterhead">
                                        <select class="filter form-control" name="" id="status_search">
                                            <option value=""> --Status-- </option>
                                            <option value="publish">Published</option>
                                            <option value="unpublish">Unpublished</option>

                                        </select>
                                    </th> --}}
                                    <th class="filterhead">
                                        <button class="btn btn-success reset">Reset Filter</button>
                                    </th>
                                </tr>
                                <tr class="top-heading">
                                    <th>Prefix</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($temp_listings) >0)
                                @foreach($temp_listings as $temp_listing)
                                <tr>
                                    <td>{{$temp_listing->template_prefix ?? '-'}}</td>
                                    <td>{{ !empty($temp_listing->template_name)? $temp_listing->template_name : '-' }}</td>
                                    <td>{{!empty($temp_listing->scope->snm_data	) ? Str::limit($temp_listing->scope->snm_data, 30) : '-' }}</td>
                                    <td>{{ $temp_listing->created ?? '-' }}</td>
                                    <td class="temp_status"><?php if($temp_listing->published == 1){  ?><span class="publish temp_status" title="Published" data-id="publish" ></span> <?php }else{ ?> <span class="unpublish temp_status" title="Unpublished" data-id="unpublish"></span> <?php }?></td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <a href="{{ route('edit-template', ['id' => $temp_listing->id_decrypted]) }}"
                                                    class="dropdown-item">
                                                    <i class="fa fa-pencil"></i> {{trans('label.edit')}}
                                                </a>
                                                <a class="dropdown-item archive_template"
                                                    data-id="{{$temp_listing->id_decrypted}}">
                                                    <i class="fa fa-archive"></i> {{trans('label.archive')}}
                                                </a>
                                                <?php if($temp_listing->published == 1){  ?>
                                                    <a onclick="share_template('{{$temp_listing->id_decrypted}}');"
                                                        class="dropdown-item">
                                                        <i class="fa fa-share-alt"></i> {{trans('label.share')}}
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.share-template-model')
@endsection
@section('footer_scripts')
<script src="{{asset('assets/js/template.js')}}"></script>
@endsection
