<ul>
@foreach ($evidencesRows as $keyEvidences =>  $evidence)
    {{-- check file is exists in dir --}}
    @if (File::exists(public_path('uploads/' . $evidence->file_name)))
        <?php if($evidence->file_type == App\Models\Evidence::TYPE_IMAGE) { ?>
            <li class=" form_evidence file-{{$evidence->id}}">
                @if(auth()->user()->user_type == 'supplier')
                <span class="glyphicon glyphicon-remove pull-right cross_file" alt="Remove" onclick="remove_file('{{$evidence->id}}')"></span>
                @endif
                {{-- <img src="{{url('/uploads/'.$evidence->file_name.'')}}" class="img-responsive"> --}}
                <a href="{{url('/uploads/'.$evidence->file_name.'')}}" target="_blank">
                    {{-- <img src="{{ asset('assets/images/image-solid.png') }}" width="100%" height="100%"/> --}}
                    <img src="{{url('/uploads/'.$evidence->file_name.'')}}" class="" width="100%" height="100%">
                </a>
            </li>
        <?php } if($evidence->file_type == App\Models\Evidence::TYPE_AUDIO) {?>
            <li class=" form_evidence file-{{$evidence->id}}">
                @if(auth()->user()->user_type == 'supplier')
                <span class="glyphicon glyphicon-remove pull-right" alt="Remove" onclick="remove_file('{{$evidence->id}}')"></span>
                @endif
                {{-- <audio controls style="width: 100%"> <source src="{{url('/uploads/'.$evidence->file_name.'')}}" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio> --}}
                <a href="{{url('/uploads/'.$evidence->file_name.'')}}" target="_blank">
                    <img src="{{ asset('assets/images/file-audio-solid.png') }}" width="100%" height="100%"/>
                </a>
            </li>
        <?php } if($evidence->file_type == App\Models\Evidence::TYPE_VIDEO) { ?>
            <li class=" form_evidence file-{{$evidence->id}}">
                @if(auth()->user()->user_type == 'supplier')
                <span class="glyphicon glyphicon-remove pull-right cross_file" alt="Remove" onclick="remove_file('{{$evidence->id}}')"></span>
                @endif
                {{-- <video width="100%" controls>
                    <source src="{{url('/uploads/'.$evidence->file_name.'')}}" type="video/mp4">
                    Your browser does not support the video tag.
                </video> --}}
                <a href="{{url('/uploads/'.$evidence->file_name.'')}}" target="_blank">
                    <img src="{{ asset('assets/images/video-slash-solid.png') }}" width="100%" height="100%"/>
                </a>
            </li>
        <?php } if($evidence->file_type != App\Models\Evidence::TYPE_VIDEO && $evidence->file_type == App\Models\Evidence::TYPE_PDF && $evidence->file_type == App\Models\Evidence::TYPE_IMAGE) {?>
            <li class=" form_evidence file-{{$evidence->id}}">
                @if(auth()->user()->user_type == 'supplier')
                <span class="glyphicon glyphicon-remove pull-right cross_file" alt="Remove" onclick="remove_file('{{$evidence->id}}')"></span>
                @endif
                <a href="{{url('/uploads/'.$evidence->file_name.'')}}" target="_blank">
                    <img src="{{ asset('assets/images/file-word-solid.png') }}" width="100%" height="100%"/>
                </a>
            </li>
        <?php } if($evidence->file_type == App\Models\Evidence::TYPE_DOCUMENT) {?>
            <li class=" form_evidence file-{{$evidence->id}}">
                @if(auth()->user()->user_type == 'supplier')
                <span class="glyphicon glyphicon-remove pull-right cross_file" alt="Remove" onclick="remove_file('{{$evidence->id}}')"></span>
                @endif
                <a href="https://docs.google.com/gview?url={{url('/uploads/'.$evidence->file_name.'')}}" target="_blank">
                    <img src="{{ asset('assets/images/file-word-solid.png') }}" width="100%" height="100%"/>
                </a>
            </li>
        <?php } if($evidence->file_type == App\Models\Evidence::TYPE_PDF) {?>
            <li class=" form_evidence file-{{$evidence->id}}">
                @if(auth()->user()->user_type == 'supplier')
                <span class="glyphicon glyphicon-remove pull-right cross_file" alt="Remove" onclick="remove_file('{{$evidence->id}}')"></span>
                @endif
                <a href="{{url('/uploads/'.$evidence->file_name.'')}}" target="_blank">
                    <img src="{{ asset('assets/images/file-pdf-solid.png') }}" width="100%" height="100%"/>
                </a>
            </li>
        <?php } ?>
    @endif
@endforeach
</ul>
