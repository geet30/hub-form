    <li class="@if(isset($active) && $active == 'dashboard') active open @endif">
        <a href="{{route('dashboard')}}">
            <i class="fa fa-home"></i>
            <span class="title">Dashboard</span>
            <span class="selected"></span>
        </a>
    </li>



    @if (auth()->user()->empHasFormPermission("Manage Locations"))
<li class="@if(isset($active) && $active == 'location' || $active == 'archive_location') active open @endif">
    <a href="javascript:;">
        <i class="fa fa-map-marker"></i>
        <span class="title">Locations</span>
        <span class="selected"></span>
        <span
            class="arrow @if(isset($active) && $active == 'location' || $active == 'archive_location') open @endif"></span>
    </a>
    <ul class="sub-menu">
        <li class="@if(isset($active) && $active == 'location' ) active @endif">
            <a href="{{ route('location.index') }}">
                <i class="fa fa-map-marker"></i>
                Locations</a>
        </li>
        <li class="@if(isset($active) && $active == 'archive_location') active @endif">
            <a href="{{ route('location.archived') }}">
                <i class="icon-graph"></i>
                Archive</a>
        </li>
    </ul>
</li>
@endif


@if (auth()->user()->empHasFormPermission("Manage Business Unit"))
    <li class="@if(isset($active) && $active == 'business_unit' || $active == 'create_bu' || $active == 'archive_bu' || $active == 'edit_bu') active open @endif">
        <a href="javascript:;">
            <i class="fa fa-briefcase"></i>
            <span class="title">Business Units</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'business_unit' || $active == 'create_bu' || $active == 'archive_bu' || $active == 'edit_bu') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'business_unit' || $active == 'create_bu' || $active == 'edit_bu') active @endif">
                <a href="{{ route('business-units.index') }}">
                    <i class="fa fa-briefcase"></i> Business Units
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_bu') active @endif">
                <a href="{{ route('business-units.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif
@if (auth()->user()->empHasFormPermission("Manage Projects"))
    <li class="@if(isset($active) && $active == 'project' || $active == 'create_project' || $active == 'archive_project' || $active == 'edit_project') active open @endif">
        <a href="javascript:;">
            <i class="fa fa-tasks"></i>
            <span class="title">Projects</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'project' || $active == 'create_project' || $active == 'archive_project' || $active == 'edit_project') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'project' || $active == 'create_project' || $active == 'edit_project') active @endif">
                <a href="{{ route('projects.index') }}">
                    <i class="fa fa-tasks"></i> Projects
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_project') active @endif">
                <a href="{{ route('projects.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif

@if (auth()->user()->empHasFormPermission("Manage Departments"))
    <li class="@if(isset($active) && $active == 'department' || $active == 'create_dept' || $active == 'archive_dept' || $active == 'edit_dept') active open @endif">
        <a href="javascript:;">
            <i class="fa fa-building-o"></i>
            <span class="title">Departments</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'department' || $active == 'create_dept' || $active == 'archive_dept' || $active == 'edit_dept') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'department' || $active == 'create_dept' || $active == 'edit_dept') active @endif">
                <a href="{{ route('departments.index') }}">
                    <i class="fa fa-building-o"></i> Departments
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_dept') active @endif">
                <a href="{{ route('departments.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif

@if (auth()->user()->empHasFormPermission("Manage Groups"))
    <li class="@if(isset($active) && $active == 'groups' ||  $active == 'archive_groups' ) active open @endif">
        <a href="javascript:;">
            <i class="fa fa-group"></i>
            <span class="title">Groups</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'groups' ||  $active == 'archive_groups') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'groups') active @endif">
                <a href="{{ route('groups.index') }}">
                    <i class="fa fa-group"></i> Groups
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_groups') active @endif">
                <a href="{{ route('groups.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif

@if (auth()->user()->empHasFormPermission("Manage Roles"))
    <li class="@if(isset($active) && $active == 'roles' ||  $active == 'archive_role' ) active open @endif">
        <a href="javascript:;">
            <i class="fa fa-group"></i>
            <span class="title">Roles</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'roles' ||  $active == 'archive_role') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'roles') active @endif">
                <a href="{{ route('roles.index') }}">
                    <i class="fa fa-group"></i> Roles
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_role') active @endif">
                <a href="{{ route('roles.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif

@if (auth()->user()->empHasFormPermission("Manage User"))
    <li class="@if(isset($active) && $active == 'users' ||  $active == 'archive_user' ) active open @endif">
        <a href="javascript:;">
            <i class="fa fa-user"></i>
            <span class="title">Users</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'users' ||  $active == 'archive_user') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'users') active @endif">
                <a href="{{ route('users.index') }}">
                    <i class="fa fa-user"></i> Users
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_user') active @endif">
                <a href="{{ route('users.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif

@if (auth()->user()->empHasFormPermission("Manage User"))
    <li class="@if(isset($active) && $active == 'suppliers' ||  $active == 'archive_supplier' ) active open @endif">
        <a href="javascript:;">
            <i class="fa fa-chain"></i>
            <span class="title">Suppliers</span>
            <span class="selected"></span>
            <span class="arrow @if(isset($active) && $active == 'suppliers' ||  $active == 'archive_supplier') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'suppliers') active @endif">
                <a href="{{ route('suppliers.index') }}">
                    <i class="fa fa-chain"></i> Suppliers
                </a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_supplier') active @endif">
                <a href="{{ route('suppliers.archived') }}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif

@if (auth()->user()->empHasFormPermission("Manage Levels"))
<li class="@if(isset($active) && $active == 'level' ) active open @endif">
    <a href="{{ route('level.index') }}">
        <i class="fa fa-sitemap"></i>
        <span class="title">Authorisation Levels</span>
    </a>
</li>
@endif



@if (auth()->user()->userHasFormPermission("Create Template"))
    <li class="@if(isset($active) && $active == 'template' || $active == 'create_template' || $active == 'archive_template' || $active == 'edit_template') active open @endif">
        <a href="javascript:;">
            <i class="fa fa-file"></i>
            <span class="title">Template Management</span>
            <span class="selected"></span>
            <span
                class="arrow @if(isset($active) && $active == 'template' || $active == 'create_template' || $active == 'archive_template' || $active == 'edit_template') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li
                class="@if(isset($active) && $active == 'template' || $active == 'create_template' || $active == 'edit_template') active @endif">
                <a href="{{route('templates')}}">
                    <i class="fa fa-file"></i>
                    Templates</a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_template') active @endif">
                <a href=" {{route('archive_template')}}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif
@if (auth()->user()->userHasFormPermission("Complete Form"))
    <li class="@if(isset($active) &&  $active == 'archive' || $active == 'completed_forms' ) active open @endif">
        <a href="javascript:;">
            <i class="icon-bulb"></i>
            <span class="title">Completed Form</span>
            <span class="selected"></span>
            <span
                class="arrow @if(isset($active) &&  $active == 'archive' || $active == 'completed_forms') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'completed_forms') active @endif">
                <a href="{{route('completed_forms')}}">
                    <i class="icon-bulb" aria-hidden="true"></i>
                    Completed Forms</a>
            </li>
            <li class="@if(isset($active) && $active == 'archive') active @endif">
                <a href=" {{route('archive')}}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif
@if (auth()->user()->userHasFormPermission("Manage Actions") )
    <li class="@if(isset($active) && $active == 'action' || $active == 'create_action'|| $active == 'edit_action'|| $active == 'archive_action') active open @endif">
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
                <a href="{{route('actions')}}">
                    <i class="fa fa-edit"></i>
                    Actions</a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_action') active @endif">
                <a href="{{route('archive_actions')}}">
                    <i class="icon-graph"></i>
                    Archive</a>
            </li>
        </ul>
    </li>
@endif
@if (auth()->user()->userHasFormPermission("Document Library"))
    <li
        class="@if(isset($active) && $active == 'documents' || $active == 'manage_folder'|| $active == 'archive_doc' ||  $active == 'archive_folders') active open @endif">
        <a href="javascript:;">
            <i class="fa fa-file-text-o"></i>
            <span class="title">Document Library</span>
            <span class="selected"></span>
            <span
                class="arrow @if(isset($active) && $active == 'documents' || $active == 'manage_folder'|| $active == 'archive_doc' || $active == 'archive_folders') open @endif"></span>
        </a>
        <ul class="sub-menu">
            <li class="@if(isset($active) && $active == 'documents' ) active @endif">
                <a href="{{route('documents')}}">
                    <i class="fa fa-file-text-o"></i>
                    Documents</a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_doc') active @endif">
                    <a href="{{route('archive_document_listing')}}">
                        <i class="icon-graph"></i>
                        Archive Documents</a>
                </li>
            <li class="@if(isset($active) && $active == 'manage_folder') active @endif">
                <a href="{{route('folders')}}">
                    <i class="fa fa-gear"></i>
                    Manage Folder</a>
            </li>
            <li class="@if(isset($active) && $active == 'archive_folders') active @endif">
                    <a href="{{route('archive_folders')}}">
                        <i class="icon-graph"></i>
                        Archive Folder</a>
            </li>
        </ul>
    </li>
@endif
    <li class="@if(isset($active) && $active == 'notification') active open @endif">
        <a href="{{ route('notifications') }}">
            <i class="icon-bell"></i>
            <span class="title">Notifications</span>
            <span class="selected"></span>
        </a>
    </li>
