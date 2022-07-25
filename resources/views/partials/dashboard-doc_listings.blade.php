<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">D_ID</th>
        <th scope="col">Title</th>
        @if (auth()->check() && auth()->user()->user_type == 'company')
        <th scope="col">Owner</th>
        @else
        <th scope="col">Category</th>
        @endif
    </tr>
</thead>
<tbody>
    @if(count($doc_listings) >0)
    <?php $i=1;  ?>
    @foreach($doc_listings as $doc_listing)
    <?php

        $owner=CheckUserType($doc_listing->i_ref_owner_role_id,$doc_listing->owner_id);

        ?>
    <tr>
        <th scope="row">{{$i}}</th>
        <td class="data_id">
        <a  class=" view-document" 
        data-document='<?php echo json_encode(["document_id" => $doc_listing->id, 
        "file_name" => $doc_listing->file_name, "file_type" =>
         $doc_listing->file_type, "description" => $doc_listing->description, 
         "doc_link" => ($doc_listing->file_type==6?$doc_listing->file_name:$doc_listing->doc_link) ]); ?>'>
         D-00{{$doc_listing->id ?? ''}}</a>    
         </td>
        <td>{{$doc_listing->title ?? '-'}}</td>
        @if (auth()->check() && auth()->user()->user_type == 'company')
        <td>{{($owner !='') ? $owner['full_name'] : '' }}
        </td>
        @else
        <td>{{($doc_listing->category !='') ? $doc_listing->category->name ? $doc_listing->category->name : '-' : '-'}}
        </td>
        @endif
    </tr>
    <?php $i++; ?>
    @endforeach
    @endif
</tbody>

@if(count($doc_listings) <= 0) 
<tfoot>
    <tr>
        <td colspan="4" class="text-center">
            <span class="form_nodata"> No Data Found! </span>
        </td>
    </tr>
</tfoot>
@endif
@if(count($doc_listings) >= 5)
<tfoot>
    <tr>
        <td colspan="4" class="text-right">
            <a href="{{route('documents')}}" class="view_more doc_view">View more </a>
        </td>
    </tr>
</tfoot>
@endif
