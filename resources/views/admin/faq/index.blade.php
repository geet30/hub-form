@extends('admin.layout.app')
@section('title'){{ 'FAQ' }}@endsection
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
                    <span>FAQ</span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase"> FAQ </span>
                        </div>
                        <div class="btn-group pull-right">
                            <a id="" class="btn sbold green" onclick="add_faq();">Add New FAQ <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="portlet-body">
                    <div class="pre_loader">
                      <img src="{{asset('assets/images/loading.gif')}}" alt="">
                    </div>
                       <table id="faq_table"  style="display:none;" class="table-responsive">
                          <thead>
                              <tr>
                                  <th></th>
                                  <th class="filterhead">Search</th>
                                  <th class="filterhead">Status</th>
                                  <th class="filterhead"></th>
                              </tr>
                              <tr class="top-heading">

                                  <th>S.NO</th>
                                  <th>Question</th>
                                  <th>Answer</th>
                                  <th>Status</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>

                            @if(!$faqs->isEmpty())
                            <?php //pr($faqs);die;?>
                                <?php ?>
                                @foreach($faqs as $key =>$faq)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{$faq->faqs ?? '-'}}</td>
                                        <td>{{$faq->answer?? '-'}}</td>
                                        <td>{{($faq->status == 1)?'Active': 'In Active'}}</td>
                                        <td>
                                            <div class="dropdown more-btn">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span>...</span>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <a href="" class="dropdown-item" data-id="{{$faq->id}}" onclick="edit_faq('{{$faq->id}}');"><i class="fa fa-pencil"></i> {{trans('label.edit')}} </a>
                                                    <a class="dropdown-item delete_doc" data-id="" onclick="delete_faq('{{$faq->id}}')"><i class="fa fa-archive"></i> {{trans('label.delete')}} </a>
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

<!-- Add New FAQ Modal -->
<div id="new_faq_modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="modal_close add_modal_close">&times;</span>
      <div class="faq_title">Add New FAQ</div>
    </div>
    <div class="modal-body">
    {{ Form::open(array('action' => 'FaqController@add_faq', 'method' => 'post', 'id' => 'new_faq_form', 'enctype' => 'multipart/form-data'))}}
        <div class="new_faq_div">
            <label class="faq_label"> Question </label> <input type="text" name="faqs" class="faq form-control" placeholder="Question" maxlength="100"><br>
            <label class="faq_label"> Answer </label> <input type="text" name="answer" class="faq form-control" placeholder="Answer" maxlength="100"><br>
            <label class="faq_label"> Status </label> 
            <div class="faq_input">
                <input type="radio" name="status" class="faq faq-status" value=1 checked> Active 
                <input type="radio" name="status" class="faq faq-status" value=0> In Active
            </div>
            <br>
            <div class="button_div"> 
                <input type="submit" name="save" value="Save" class="btn btn-success category-btn">
                <input type="button" class="btn btn-success cancel_modal" value="Cancel">
            </div>
        </div>
    {{ Form::close() }}
    </div>
  </div>

</div>

<!-- EDIT Faq Modal -->
<div id="edit_faq_modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="modal_close edit_modal_close">&times;</span>
      <div class="faq_title">Edit FAQ</div>
    </div>
    <div class="modal-body">
    {{ Form::open(array('action' => 'FaqController@update_faq', 'method' => 'post', 'id' => 'edit_faq_form', 'enctype' => 'multipart/form-data'))}}
        <div class="new_faq_div">
            <input type="hidden" name="faq_id" id="faq_id">
            <label class="faq_label"> Question </label> <input type="text" name="faqs" class="faq form-control" id="edit_faqs" placeholder="Question" maxlength="100"><br>
            <label class="faq_label"> Answer </label> <input type="text" name="answer" class="faq form-control" id ="edit_ans" placeholder="Answer" maxlength="400"><br>
            <label class="faq_label"> Status </label> 
            <div class="faq_input">
                <input type="radio" name="status" class="faq faq_status" id="active" value="1"> Active 
                <input type="radio" name="status" class="faq faq_status" id="inactive" value="0" > Inactive
            </div>
            <br>
            <div class="button_div"> 
                <input type="submit" name="edit" value="Update" class="btn btn-success category-btn">
                <input type="button" class="btn btn-success cancel_edit_modal" value="Cancel">
            </div>
        </div>
    {{ Form::close() }}
    </div>
  </div>

</div>

@endsection
@section('footer_scripts')
    <script src="{{asset('assets/js/faq.js')}}"></script>
@endsection
