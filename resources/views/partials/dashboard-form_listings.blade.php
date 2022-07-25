<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">F_ID</th>
        @if (auth()->check() && auth()->user()->user_type == 'company')
        <th scope="col">Title</th>
        <th scope="col">Completed By</th>
        @else
        <th scope="col">Type</th>
        <th scope="col">Title</th>
        @endif
    </tr>
</thead>
<tbody>
    @if(count($form_listings) > 0)
    <?php $i=1; ?>
    @foreach($form_listings as $form_listing)
    <?php 
        $title = '-';
        if(!empty($form_listing->title)){
            if(strlen($form_listing->title) > 10){
                $title = substr($form_listing->title, 0, 10).'..';
            }else{
                $title = $form_listing->title;
            } 
        }
    ?>
    <tr>
        <th scope="row">{{$i}}</th>
        <td class="data_id">
            <a href="{{ route('show', ['id' => $form_listing->id_decrypted]) }}">
                {{ !empty($form_listing->form_id) ?Str::limit($form_listing->form_id, 7) : '-' }}
            </a>
        </td>
        @if (auth()->check() && auth()->user()->user_type == 'company')
        <td>{{ $title ?? '-' }}</td>
        <td>{{ $form_listing->completed_by ? $form_listing->completed_by->vc_fname?$form_listing->completed_by->vc_fname.' ' .$form_listing->completed_by->vc_mname.' '.$form_listing->completed_by->vc_lname:'-' : '-' }}
            @else
        <td>{{ $form_listing->template ? $form_listing->template->template_name ?$form_listing->template->template_name:'-' : '-' }}
        <td>{{ $title ?? '-' }}</td>
        @endif
        </td>
    </tr>
    <?php $i++; ?>
    @endforeach
    @endif
</tbody>
@if(count($form_listings) <= 0) 
<tfoot>
    <tr>
        <td colspan="4" class="text-center">
            <span class="form_nodata"> No Data Found! </span>
        </td>
    </tr>
</tfoot>
@endif
@if(count($form_listings) >= 5)
<tfoot>
    <tr>
        <td colspan="4" class="text-right">
            <a href="{{route('completed_forms')}}" class="view_more form_view">View more </a>
        </td>
    </tr>
</tfoot>
@endif
