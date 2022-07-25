@php
    use App\Models\Document;
@endphp
<!-- Document library list Modal -->
<div id="doc_library_0_0" class="modal doc_library">
    <div class="modal-content">
        <div class="modal-header">
            <span class="doc_close" onclick="cancel_doc('0','0')">&times;</span>
            <div class="doc_library_title">Document Library</div>
        </div>
        <div class="modal-body">
            <div class="pre_loader">
                <img src="{{asset('assets/images/loading.gif')}}" alt="">
            </div>
            @if(!empty($doc_listings) && count($doc_listings) > 0)
                <input type="text" class="search-doc search-doc-0-0 form-control" placeholder="Search Document" onkeyup="search_document(0,0, this)">
                <div style="height:auto;" class="col-md-12 doc_listing doc_listing_0_0">
                    @include('partials.document_listing')
                </div>
                <div class="doclibrarybuttons">
                    <input type="button" name="Save" value="Save" class="btn btn-success document_save" onclick="save_doc('0','0')">
                    <div class="upload-loader" style="display:none"></div>
                    <input type="button" name="Cancel" value="Cancel" class="btn btn-success" id="doc_cancel" onclick="cancel_doc('0','0')">
                </div>
            @else
            <div class="no_doc"> No Document Found! </div>
            @endif
            </div>
    </div>
</div>

@php $document_list = ''; @endphp
@if(!empty($doc_listings) && count($doc_listings) > 0)
@foreach($doc_listings as $doc_listing)
@php $document_image = '';@endphp
@switch($doc_listing['file_type'])
    @case( Document::TYPE_IMAGE)
        @php $document_image = '<img src="'.asset("documentLibrary/". $doc_listing["file_name"]).'" width="100%" height="100%">'; @endphp
        @break
    @case(Document::TYPE_AUDIO)
        @php $document_image = '<img src="'. asset("assets/images/file-audio-solid.png") .'" width="100%" height="100%"/>'; @endphp
        @break
    @case(Document::TYPE_VIDEO)
        @php $document_image = '<img src="'.asset("assets/images/video-slash-solid.png").'" width="100%" height="100%"/>'; @endphp
        @break
    @case(Document::TYPE_PDF)
        @php $document_image = '<img src="'.asset("assets/images/file-pdf-solid.png") .'" width="100%" height="100%"/>'; @endphp
        @break
    @case(Document::TYPE_DOCUMENT)
        @php $document_image = '<img src="'. asset("assets/images/file-word-solid.png").'" width="100%" height="100%"/>'; @endphp
        @break
    @default
        @php $document_image = '<img src="'. asset("assets/images/file-word-solid.png") .'" width="100%" height="100%"/>'; @endphp
@endswitch



<?php $document_list .= '<div class="col-md-3 doc-list"> <input name="" id="document" class="document document_0_0"  type="checkbox" value="'.$doc_listing['id'].'" target="" />';
    $document_list .= '<div class="doc-library"> '.$document_image.'</div> <label for="document"  style="word-wrap:break-word"> '.$doc_listing['title'].' </label> </div>';
?>
@endforeach
@endif

<input type="hidden" name="doc_listing" id="doc_listing" value='<?= $document_list; ?>'>