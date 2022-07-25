@extends('admin.layout.app')
@section('title'){{'Archive Document'}}@endsection
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
                    <span>Archive </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase">Archive</span>
                        </div>
                        <!-- <div class="btn-group pull-right">
                            <a id="sample_editable_1_new" class="btn sbold green">Create New Category <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                        </div> -->
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        @if($departments)
                        <?php 
                        $dept_name = '';
                        foreach ($departments as $department) {
                          $dept_name .= '<option value="'.$department['vc_name'].'">'.$department['vc_name'].'</option>';
                        }
                      ?>
                        @endif
                        @if($categories)
                        <?php 
                        $cat_name = '';
                        foreach ($categories as $category) {
                          $cat_name .= '<option value="'.$category['name'].'">'.$category['name'].'</option>';
                        }
                      ?>
                        @endif
                        @if($folders)
                        <?php 
                        $folder_name = '';
                        foreach ($folders as $folder) {
                          $folder_name .= '<option value="'.$folder['name'].'">'.$folder['name'].'</option>';
                        }
                      ?>
                        @endif
                        <input type="hidden" name="archive_dept_name" id="archive_dept_name" value='<?= $dept_name; ?>'>
                        <input type="hidden" name="archive_cat_name" id="archive_cat_name" value='<?= $cat_name; ?>'>
                        <input type="hidden" name="folder_name" id="folder_name" value='<?= $folder_name; ?>'>
                        <table id="archive_document_table" style="display:none;" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead">ID, Owner, Title, Business unit, Project</th>
                                    <th class="filterhead">Category</th>
                                    <th class="filterhead">Expiry Date</th>
                                    <th class="filterhead">Department</th>
                                    <th class="filterhead">Folder</th>
                                    <th class="filterhead"> </th>
                                </tr>
                                <tr class="top-heading">

                                    <th>ID</th>
                                    <th>Folder</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Owner</th>
                                    <th>Expiry Date</th>
                                    <th>Business Unit</th>
                                    <th>Department</th>
                                    <th>Project</th>
                                    <th>Description</th>
                                    {{-- <th>Share with Suplier</th>
                                  <th>Use in mobile Device</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $preDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );?>
                                @if(!empty($doc_listing))
                                @foreach($doc_listing as $doc_key => $document)
                                <?php 
                                    $short_description = '';
                                    if(strlen($document->description) > 15){
                                        $short_description = substr($document->description, 0, 15).'...';
                                    }else{
                                        $short_description = $document->description;
                                    }
                                ?>
                                <tr>
                                    <td>D-00{{$document->id ?? ''}}</td>
                                    <td> {{ $document->folder ? $document->folder->name : '-'}} </td>
                                    <td>{{!empty($document->title) ? Str::limit($document->title, 15) : '-'}}</td>
                                    <td>{{($document->category !="")?($document->category->name !="")?$document->category->name:"-":"-" }}
                                    </td>
                                    <td>{{ $document->owner ? $document->owner->full_name : ''}}</td>
                                    <td>{{ ($document->expires_at != null) ? $document->expires_at->format(DATE_FORMAT) : '-' }}
                                    </td>
                                    <td>{{($document->business_unit !="")?($document->business_unit->vc_short_name !="")?$document->business_unit->vc_short_name:"-":"-" }}
                                    </td>
                                    <td>{{($document->department !="")?($document->department->vc_name !="")?$document->department->vc_name:"-":"-" }}
                                    </td>
                                    <td>{{($document->project !="")?($document->project->vc_name !="")?$document->project->vc_name:"-":"-" }}
                                    </td>
                                    <td>{{ !empty($document->description)? Str::limit($document->description, 10):'-'}}</td>
                                    {{-- <td><input type="checkbox" class="form-control" ></td>
                                    <td><input type="checkbox" class="form-control" ></td> --}}
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            @if(auth()->check() && auth()->user()->user_type == 'company' ||
                                            $document->owner_id == auth()->user()->id)
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                <a href="#" class="restore_document" data-id="{{ $document->id }}"><i
                                                        class="fa fa-archive fa-1x"></i> Restore </a>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                No Document archived!
                                @endif
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
<script src="{{asset('assets/js/archive_document.js')}}"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection
