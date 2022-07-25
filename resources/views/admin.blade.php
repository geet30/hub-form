<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<title>{{ trans('label.form_builder') }}: @yield('title')</title>
@include('head')
</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid">
<!-- BEGIN HEADER -->
@include('topbar')
<div class="page-container">
	<div class="page-sidebar-wrapper">
        @include('mysidebar')
    </div>
    @yield('content')
	</div>
@include('pagefooter')
