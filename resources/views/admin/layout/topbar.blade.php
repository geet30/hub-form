<div class="page-header -i navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <!-- <img src="../../assets/admin/layout/img/logo.png" alt="logo" class="logo-default"/> -->
            <h3 class="logo-default">{{ trans('label.atrum_company') }}</h3>
            <div class="menu-toggler sidebar-toggler"><i class='fa fa-bars'></i></div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
            data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li>
                    <!-- <button class="btn btn-success green">
                        <span class="username username-hide-on-mobile"> Switch to HUB Enterprise </span>
                    </button> -->
                    <a href="{{ route('users.redirects') }}" class="btn btn-success green switch-button">
                        <span class="username username-hide-on-mobile switch_span"> Switch to HUB Enterprise </span>
                    </a> 
                </li>
                <!-- END TODO DROPDOWN -->
                <x-notifications/>
                <x-reject-comment/>
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="top-bar-user">
                    <div class="btn-group username-dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown">
                            <img alt="" class="img-circle topbar-user-image" src="{{ auth()->user()->image_url }}" />
                            <span class="username username-hide-on-mobile"> {{ auth()->user()->full_name }} </span>
                            <span class="caret down-arrow"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="{{route('logout')}}"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
                        </ul>
                    </div>
                    </div>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <!-- <li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="javascript:;" class="dropdown-toggle">
					<i class="icon-logout"></i>
					</a>
				</li> -->
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
