<thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">A_ID</th>
        @if (auth()->check() && auth()->user()->user_type == 'company')
        <th scope="col">Assignee</th>
        @else
        <th scope="col">Source</th>
        @endif
        <th scope="col">Title </th>
        <th scope="col">Status</th>
    </tr>
</thead>
<tbody>
    @if(count($action_listings) >0)
    @foreach($action_listings as $action_listingKey => $action_listing)
    @php 
    $id = encrypt_decrypt("encrypt", $action_listing->id); 
    $assigne=CheckUserType($action_listing->i_ref_assined_role_id,$action_listing->assined_user_id);

    @endphp
    <tr>
        <th scope="row">{{ ($action_listingKey +1 ) }}</th>
        <td class="data_id"><a href="{{ route('actions.view', $id )}}"> A00{{$action_listing->id ?? ''}} </a></td>
        @if (auth()->check() && auth()->user()->user_type == 'company')
        <td>{{ $assigne ? $assigne['full_name'] : '' }}
            @else
        <td>
            @if(!empty($action_listing->completedForm))
                <a href="{{ !empty($action_listing->completedForm->id_decrypted)? route('report', ['id' => $action_listing->completedForm->id_decrypted]):'' }}"
                > {{ $action_listing->completedForm ? $action_listing->completedForm->form_id ? $action_listing->completedForm->form_id: '-' : '-' }}</a>
                @else
                -
            @endif
        @endif
        </td>
        <td>{{ !empty($action_listing->title) ? Str::limit($action_listing->title,15) : '-'}}</td>
        <td>
            <span style="color:{{ $action_listing->status_color }}"
                class="status-button">{{ $action_listing->status_name }}</span>
        </td>
    </tr>
    @endforeach
    @endif
</tbody>
@if(count($action_listings) <= 0) 
<tfoot>
    <tr>
        <td colspan="5" class="text-center">
            <span class="form_nodata"> No Data Found! </span>
        </td>
    </tr>
</tfoot>
@endif
@if(count($action_listings) >= 5)
<tfoot>
    <tr>
        <td colspan="5" class="text-right">
            <a href="{{route('actions')}}" class="view_more action_view">
                View more
            </a>
        </td>
    </tr>
</tfoot>
@endif
