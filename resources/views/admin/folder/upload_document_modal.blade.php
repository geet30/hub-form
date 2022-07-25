<!-- upload documemt Modal -->
<div id="upload_doc_modal" class="modal" style="height: fit-content;">

  <!-- Modal content -->
  <div class="modal-content" style="height: auto;">
    <div class="modal-header">
      <span class="upload_close">&times;</span>
      <div class="upload_title">Upload Document</div>
    </div>
    <div class="modal-body">
        <div class="pre_loader" id="pre_loader">
            <img src="{{ asset('assets/images/loading.gif') }}" alt="loader">
        </div>
        <div class="upload_div">
        <form id ="create_document">
        {{-- <!-- {{ Form::open(array('method' => 'post', 'id' => 'create_document', 'enctype' => 'multipart/form-data'))}} --> --}}
            <div class="label-input">
                <label class="upload_label"> Title <span style="color:red">*</span></label> <input type="text" name="name" class="form-control upload-doc title" maxlength="100"> <br>
            </div>
            <div class="label-input">
                <label class="upload_label"> Expiry Date</label>
                <div class="expirydiv"><input type="text" name="expiry_date" class="form-control expiry_date upload-doc" id="expiry_date"><span class="glyphicon glyphicon-calendar expiry-calender"></span></div>
            </div>
            <div class="label-input">
                <label class="upload_label"> Category <span style="color:red">*</span></label>
                <select name="category" class=" form-control upload-doc"> 
                    <option value="">-- Category --</option>
                @foreach($categories as $category)
                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                @endforeach
                </select><br>
            </div>
            <div class="label-input">
                <label class="upload_label"> Business Unit <span style="color:red">*</span></label>
                <select name="business_unit" class=" form-control" id="business_unit"> 
                    <option value="">-- Business Unit --</option>
                @foreach($business_unit as $bu)
                    <option value="{{$bu['id']}}">{{$bu['vc_short_name']}}</option>
                @endforeach
                </select><br>
            </div>
            <div class="label-input">
                <label class="upload_label"> Department <span style="color:red">*</span></label>
                <select name="department" class=" form-control" id="department"> 
                    <option value="">-- Department --</option>
                </select><br>
            </div>
            <div class="label-input">
                <label class="upload_label"> Project </label>
                <select name="project" class=" form-control" id="project"> 
                    <option value="">-- Project --</option>
                </select><br>
            </div>
            <div class="label-input">
                <label class="upload_label"> Owner <span style="color:red">*</span></label>
                {{-- <input type="text" name="owner" class="form-control upload-doc" maxlength="100" value="{{auth()->user()->vc_fname}} {{auth()->user()->vc_lname}}" disabled> --}}
                <select name="owner" class="form-control upload-doc owner_list" id="owner_list"> 
                    <option value="">-- Owner --</option>
                 
                </select>
                <br>            
            </div>
            <div class="label-input">
                <label class="upload_label"> Description </label>
                <textarea class="form-control" name="description" maxlength="200"></textarea>

                <input type="hidden" name="folder" class="form-control upload-doc" value="<?=!empty($id)?$id:""?>">
                <br>
            </div>
            <div class="label-input">
                <label class="upload_label"> URL </label>
                <input type="text" class="form-control online-url" name="url" maxlength="200"/>
                
            </div>
            <div class="uploadfilesec">
                <div class="uploadfileicon"><i class="fas fa-cloud-upload-alt"></i>

                </div>
                <div class="filename"></div>
                <input type="hidden" name="doc_data"  id="doc_data" >
                <label for="doc_file" class="uploadtext">Upload file</label>
                <input type="file" name="file" id="doc_file" style="display:none !important;">
                <div id="dropbox">
                <div class="browsefile">Drag and drop your file here or <span class="browsefileinput">Browse <input type="file"></span> </div>
                </div>
            </div>
            <div class="uploadfilebuttons">
                <input type="submit" name="Save" value="Save" class="btn btn-success save_document">
                <div class="upload-loader" style="display:none"></div>
                <input type="button" name="Cancel" value="Cancel" class="btn btn-success" id="upload_cancel">
            </div>
        </form>
        {{-- <!-- {{ Form::close() }} --> --}}
        </div>     
    </div>
  </div>
</div>