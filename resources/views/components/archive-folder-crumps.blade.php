<?php //print_r($parent_data);die; ?>
<div class="col-md-12 folder-crumps">
@foreach (array_reverse($parent_data) as $parentData)
    @php
        $encrypt_parentId = encrypt_decrypt('encrypt', $parentData['parent_folder_id'])   
    @endphp
    <a class="crump-name" href="{{ route('archive_folders', $encrypt_parentId) }}" >{{$parentData['name']}}</a> / 

@endforeach
</div>