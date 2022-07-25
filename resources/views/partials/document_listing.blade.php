@php
    use App\Models\Document;
@endphp
@if(!empty($doc_listings) && count($doc_listings) > 0)
@foreach($doc_listings as $key =>$doc_listing)
    <div class="col-md-3 doc-list">
        <input name="" id="document" class="document document_0_0"  type="checkbox" value="{{$doc_listing['id']}}" target="{{$doc_listing['title']}}" @if(!empty($doc_listing['id']) &&  !empty($document_id) && isset($document_id)){{ in_array($doc_listing['id'], $document_id)? 
        "checked" : '' }}@endif />
        <div class="doc-library">
            @switch($doc_listing['file_type'])
                @case( Document::TYPE_IMAGE)
                    <img src="{{asset('documentLibrary/'. $doc_listing['file_name'])}}" width="100%" height="100%">
                    @break
                @case(Document::TYPE_AUDIO)
                    <img src="{{ asset('assets/images/file-audio-solid.png') }}" width="100%" height="100%"/>
                    @break
                @case(Document::TYPE_VIDEO)
                    <img src="{{ asset('assets/images/video-slash-solid.png') }}" width="100%" height="100%"/>
                    @break
                @case(Document::TYPE_PDF)
                    <img src="{{ asset('assets/images/file-pdf-solid.png') }}" width="100%" height="100%"/>
                    @break
                @case(Document::TYPE_DOCUMENT)
                    <img src="{{ asset('assets/images/file-word-solid.png') }}" width="100%" height="100%"/>
                    @break
                @default
                    <img src="{{ asset('assets/images/file-word-solid.png') }}" width="100%" height="100%"/>
            @endswitch
        </div>
        <label for="document"  style="word-wrap:break-word"> {{$doc_listing['title']}} </label>
    </div>
@endforeach
@else
<div class="no_doc"> No Document Found! </div>
@endif