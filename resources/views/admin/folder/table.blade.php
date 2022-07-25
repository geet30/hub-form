<table class="tree" id="folder_table">
    <thead>
        <tr>
            <th class="filterheadfolder"></th>
            <th class="filterheadfolder">Folder Name, Sub folder Name </th>
            <th class="filterheadfolder">No of documents</th>
            <th class="filterheadfolder">Reset</th>
        </tr>
        <tr class="top-heading">
            <th></th>
            <th>Folder Name</th>
            <th>Number of Documents</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- <tr class="treegrid-1">
            <td>Root node 1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-2 treegrid-parent-1">
            <td>Node 1-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-3 treegrid-parent-1">
            <td>Node 1-2</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-4 treegrid-parent-3">
            <td>Node 1-2-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-8 treegrid-parent-7">
            <td>Node 2-2-1</td><td>Additional info</td>
        </tr> 
        <tr class="treegrid-5">
            <td>Root node 2</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-6 treegrid-parent-5">
            <td>Node 2-1</td><td>Additional info</td>
        </tr>
        <tr class="treegrid-7 treegrid-parent-5">
            <td>Node 2-2</td><td>Additional info</td>
        </tr>
        --}}
        <tr>
            <td></td>
            <td><i class='fas fa-chevron-circle-down' id="expand_tree"></i></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($folders as $folder)
        <?php if(!empty($folder['documents'])){
        $document = count($folder['documents']);
        } else{
            $document = '0';
        } ?>
        <tr class="treegrid-{{ $folder->id }} @if(!empty($folder->parent_folder_id)) treegrid-parent-{{$folder->parent_folder_id}} @endif">
            <td><input type="checkbox" class="open-popup-sub-main" data-id="{{ $folder->id }}"></td>
            <td>{{ $folder->name }}</td>
            <td>{{ $document }}</td>
            <td>
                <div class="dropdown more-btn">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span>...</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <a class="dropdown-item" onclick="rename_folder('{{$folder->id}}', '{{$folder->name}}')">
                            <i class="fa fa-pencil"></i> {{trans('label.rename_folder')}} 
                        </a>
                        <a class="dropdown-item" data-id="" onclick="delete_folder('{{$folder->id}}')">
                            <i class="fa fa-archive"></i> {{trans('label.delete_folder')}} 
                        </a>
                    </div>
                </div>
            </td>
        </tr>


        {{-- <tr class="treegrid-{{$folder->id}}  @if(!empty($folder->parent_folder_id)) treegrid-parent-{{$folder->parent_folder_id}} @endif">
            <td><input type="checkbox" class="open-popup-sub-main" data-id="{{$folder->id ?? ''}}"></td>
            <td>{{$folder->name ?? '-'}}</td>
            <td>{{$document}}</td>
            <td>
                <div class="dropdown more-btn">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span>...</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <a class="dropdown-item" onclick="rename_folder('{{$folder->id}}', '{{$folder->name}}')">
                            <i class="fa fa-pencil"></i> {{trans('label.rename_folder')}} 
                        </a>
                        <a class="dropdown-item" data-id="" onclick="delete_folder('{{$folder->id}}')">
                            <i class="fa fa-archive"></i> {{trans('label.delete_folder')}} 
                        </a>
                    </div>
                </div>
            </td>
        </tr> --}}
        @endforeach

    </tbody>
</table>
