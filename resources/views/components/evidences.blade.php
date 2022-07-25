@foreach ($evidences as $keyEvidences =>  $evidence)
    {{-- check file is exists in dir --}}
    @if (File::exists(public_path(App\Models\Evidence::PUBLIC_EVIDENCE_PATH . DS . $evidence->file_name)))    
    
        @switch($evidence->file_type)
            @case(App\Models\Evidence::TYPE_IMAGE)
                <div class="col-md-3">
                    <a href="{{ $evidence->file_url }}" target="_blank" >
                        <img src="{{ $evidence->file_url }}" class="img-responsive evidence-img">
                    </a>
                </div>
                @break
            @case(App\Models\Evidence::TYPE_AUDIO)
                <div class="col-md-3">
                    <a href="{{ $evidence->file_url }}" target="_blank">
                        <img src="{{ asset('assets/images/file-audio-solid.png') }}" class="img-responsive evidence-icon" />
                    </a>
                    {{-- <audio controls> <source src="{{ $evidence->file_url }}" type="audio/mp3">
                        Your browser does not support the audio element.
                    </audio> --}}
                </div>
                @break
            @case(App\Models\Evidence::TYPE_VIDEO)
            <div class="col-md-3">
                <a href="{{ $evidence->file_url }}" target="_blank">
                    <img src="{{ asset('assets/images/video-slash-solid.png') }}" class="img-responsive evidence-icon" />
                </a>
                {{-- <video width="100%" controls>
                    <source src="{{ $evidence->file_url }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video> --}}
            </div>
                @break
            @case(App\Models\Evidence::TYPE_DOCUMENT)
            <div class="col-md-3">
                <a href="{{ $evidence->file_url }}" target="_blank">
                    <img src="{{ asset('assets/images/file-word-solid.png') }}" class="img-responsive evidence-icon" />
                </a>
                {{-- <video width="100%" controls>
                    <source src="{{ $evidence->file_url }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video> --}}
            </div>
                @break
            @case(App\Models\Evidence::TYPE_PDF)
            <div class="col-md-3">
                <a href="{{ $evidence->file_url }}" target="_blank">
                    <img src="{{ asset('assets/images/file-pdf-solid.png') }}" class="img-responsive evidence-icon" />
                </a>
                {{-- <video width="100%" controls>
                    <source src="{{ $evidence->file_url }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video> --}}
            </div>
                @break
            @default
            <div class="col-md-3">
                <a href="{{ $evidence->file_url }}" target="_blank"> view </a>
            </div>
        @endswitch
    @endif
@endforeach
