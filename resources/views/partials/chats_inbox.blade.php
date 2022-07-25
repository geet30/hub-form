@foreach($messages as $index => $document)
<?php 
    $timeZone = $_COOKIE['user_timezone'];
    date_default_timezone_set($timeZone);
    $message = $document['message_text'];
    $mediaUrl = $document['media_url'];
    $url = strtok($mediaUrl, '?');
    $ext = pathinfo($url, PATHINFO_EXTENSION);
    str_replace('%7D','',$ext);
    $parsePath = parse_url($mediaUrl, PHP_URL_PATH);
    $prePath = basename($parsePath);
    $mediaFileName = str_replace('chat_media%2F','',$prePath);
    $mediaFileName = str_replace('%20',' ',$mediaFileName);
    $type = $document['type'];
    $mediaType = $document['media_type'];
    $sentAt = $document['sent_at'];
    $sentDateTime = date('d M, g:i A', $sentAt/1000);
    $senderInfo = $document['sender_info']; 
?>
@if($type != 3)
@php
$mediaClass = 'innerLeftChat';
$centerChatClass = 'rightChat';
@endphp
@else
@php
$mediaClass = 'innerChatAction';
$centerChatClass = 'centerChat';
@endphp
@endif
@if(auth()->user()->id == $senderInfo->id)
<div class="{{$centerChatClass}}">
    @if($type != 3)
    <div class="namechatperson">
        {{ isset($senderInfo->vc_fname) ? $senderInfo->vc_fname : '' }}
        {{ isset($senderInfo->vc_lname) ? $senderInfo->vc_lname : '' }}
    </div>
    @endif
    @else
    <div class="leftChat">
        <div class="namechatperson">
            {{ isset($senderInfo->vc_fname) ? $senderInfo->vc_fname : '' }}
            {{ isset($senderInfo->vc_lname) ? $senderInfo->vc_lname : '' }}
        </div>
        @endif
        <div class="{{$mediaClass}} @if($mediaUrl !='') mediaChatBg @endif">
            {{ isset($message) ? $message : '' }}
            @if($type == 2 && $mediaUrl !="")
            @if($ext == 'jpg' || $ext == 'jpg%7D' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $mediaType ==
            1)
                <div class="mediaSection">
                    <ul>
                        <li>
                            <img src="{{$mediaUrl}}" width="150" height="150" >
                        </li>
                    </ul>
                </div>
            @elseif($ext =='pdf')
            <div class="mediaSection">
                <ul>
                    <li>
                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/pdf.png')}}"
                                alt="pdf-media" class="mediaFile"></a>
                    </li>
                    <li>
                        <p class="mediaName">{{$mediaFileName}}</p>
                    </li>
                </ul>
            </div>
            @elseif($ext =='mp4' || $mediaType == 4)
            <div class="mediaSection">
                <ul>
                    <li>
                        <video width="150" height="150" controls>
                            <source src="{{$mediaUrl}}" type="video/mp4">
                          Your browser does not support the video tag.
                        </video>
                    </li>
                </ul>
            </div>
            @elseif($ext== 'mp3' || $mediaType == 2)
            <div class="mediaSection">
                <ul>
                    <li>
                        <audio controls>
                            <source src="{{$mediaUrl}}" type="audio/mpeg">
                          Your browser does not support the audio element.
                        </audio>
                    </li>
                </ul>
            </div>
            @elseif($ext =='doc' || $ext == 'docx' || $ext=='docm' || $ext =='csv' || $mediaType == 3)
            <div class="mediaSection">
                <ul>
                    <li>
                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/doc.png')}}"
                                alt="doc-media" class="mediaFile"></a>
                    </li>
                    <li>
                        <p class="mediaName">{{$mediaFileName}}</p>
                    </li>
                </ul>
            </div>
            @else
            <div class="mediaSection">
                <ul>
                    <li>
                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/file.png')}}"
                                alt="file-media" class="mediaFile"></a>
                    </li>
                    <li>
                        <p class="mediaName">{{$mediaFileName}}</p>
                    </li>
                </ul>
            </div>
            @endif
            @endif
            <span class="timingChat @if($mediaUrl !='') mediaTime @endif">
                {{ isset($sentDateTime) ? $sentDateTime : '' }}
            </span>
        </div>
    </div>
    @endforeach
