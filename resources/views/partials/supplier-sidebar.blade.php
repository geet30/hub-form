<li class="@if(isset($active) && $active == 'dashboard') active open @endif">
    <a href="{{route('dashboard')}}">
        <i class="fa fa-home"></i>
        <span class="title">Dashboard</span>
        <span class="selected"></span>
    </a>
</li>
@if (auth()->user()->userHasFormPermission("Complete Form"))
<li class="@if(isset($active) &&  $active == 'archive' || $active == 'completed_forms' ) active @endif">
    <a href="{{route('completed_forms')}}"> <i class="icon-bulb" aria-hidden="true"></i> Completed Forms </a>
</li>
@endif
@if (auth()->user()->userHasFormPermission("Manage Actions"))
<li
    class="@if(isset($active) && $active == 'action' || $active == 'create_action'|| $active == 'edit_action'|| $active == 'archive_action') active open @endif">
    <a href="javascript:;">
        <i class="fa fa-edit"></i>
        <span class="title">Action Management</span>
        <span class="selected"></span>
        <span
            class="arrow @if(isset($active) && $active == 'action' || $active == 'create_action'|| $active == 'edit_action'|| $active == 'archive_action') open @endif"></span>
    </a>
    <ul class="sub-menu">
        <li
            class="@if(isset($active) && $active == 'action' || $active == 'create_action'|| $active == 'edit_action' ) active @endif">
            <a href="{{ route('actions') }}">
                <i class="fa fa-edit"></i> Actions
            </a>
        </li>
        <li class="@if(isset($active) && $active == 'archive_action') active @endif">
            <a href="{{ route('archive_actions') }}">
                <i class="icon-graph"></i> Archive
            </a>
        </li>
    </ul>
</li>
@endif
@if (auth()->user()->userHasFormPermission("Manage Document"))
<li class="@if(isset($active) && $active == 'documents' || $active == 'manage_folder'|| $active == 'archive_doc') active @endif">
    <a href="{{ route('supplier.document') }}">
        <i class="fa fa-file-text-o"></i> Document Library
    </a>
</li>
<li class="@if(isset($active) && $active == 'notification') active open @endif">
    <a href="{{ route('notifications') }}">
        <i class="icon-bell"></i>
        <span class="title">Notifications</span>
        <span class="selected"></span>
    </a>
</li>
@endif