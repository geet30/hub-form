<div class="">
    <?php //pr($folders);die; ?>
    @if(count($folders)<=0  && count($documents)<=0)
        <div class="no-data">No data found!</div>
    @else
        @foreach ($folders as $folder)
        <div class="col-md-2 folders  folder-dragable {{$folder->encrypted_id}}    {{$folder->id}}" >
            <div class="folders-icon">
            <i class="fa fa-pencil-square-o" aria-hidden="true" onclick='rename_folder({{$folder->id}}, "{{$folder->name}}")'></i>
            <i class="fa fa-trash" aria-hidden="true" onclick="delete_folder(this, {{$folder->id}})"></i>
            </div>
            <a href="{{ route('folders', $folder->encrypted_id) }}" class="folder-link">
                <img src="{{ asset('assets/images/folder-solid.png') }}" width="80px" height="80px" class="folder-icon {{$folder->encrypted_id}}  {{$folder->id}} " />
                <div class="name-{{$folder->id}}">{{$folder->name}} </div>
            </a>
        </div>
        @endforeach
        <?php //pr($documents);die; ?>
        @foreach ($documents as $document)
        <?php //pr($document->doc_link);die; ?>
        @if (File::exists(public_path('documentLibrary/' . $document->file_name)))
        @php $doc_link = $document->doc_link; @endphp
        <div class="col-md-2 folders   {{ $document->id }}">
            <a class="view-document" data-document='<?php echo json_encode(["file_name" => $document->file_name, "file_type" => $document->file_type, "description" => $document->description, "doc_link" => $doc_link]); ?>'>
                @if($document->file_type == App\Models\Document::TYPE_IMAGE)
                <img src="{{$doc_link}}" width="80px" height="80px" class="folder-icon">
                @elseif($document->file_type == App\Models\Document::TYPE_PDF)
                <a href="{{$doc_link}}" target="_blank">
                    <img class="folder-icon" src="{{ asset('assets/action/images/pdf.png')}}" alt="pdf-media" class="mediaFile" width="80px" height="80px">
                </a>
                @elseif($document->file_type == App\Models\Document::TYPE_VIDEO)
                <a href="{{$doc_link}}" target="_blank">
                    <img class="folder-icon" src="{{ asset('assets/action/images/mp4.png')}}" alt="mp4-media" class="mediaFile" width="80px" height="80px">
                </a>
                @elseif($document->file_type == App\Models\Document::TYPE_AUDIO)
                <a href="{{$doc_link}}" target="_blank">
                    <img class="folder-icon" src="{{ asset('assets/action/images/mp3.png')}}" alt="mp3-media" class="mediaFile" width="80px" height="80px">
                </a>
                @elseif($document->file_type == App\Models\Document::TYPE_DOCUMENT)
                <a href="{{$doc_link}}" target="_blank">
                    <img class="folder-icon" src="{{ asset('assets/action/images/doc.png')}}" alt="doc-media" class="mediaFile" width="80px" height="80px">
                </a>
                @else
                <a href="{{$doc_link}}" target="_blank">
                    <img class="folder-icon" src="{{ asset('assets/action/images/file.png')}}" alt="file-media" class="mediaFile" width="80px" height="80px">

                </a>
                @endif
                <div>{{$document->title}} </div>
            </a>
        </div>
        @endif
        @if($document->file_type == App\Models\Document::TYPE_URL)
        <div class="col-md-2 folders">
            <a href="{{$document->file_name}}" target="_blank">
                <!-- <img class="folder-icon" src="{{ asset('assets/action/images/url.png')}}" alt="file-media" class="mediaFile" width="60px" height="80px"> -->
                <i  width="80px" height="80px" class="fa fa-external-link fa-5x mediaFile" aria-hidden="true" style="    margin-top: 46px !important
;"></i>

            </a>
            <div>{{$document->title}} </div>
        </div>
        @endif
        @endforeach
    @endif
</div>
