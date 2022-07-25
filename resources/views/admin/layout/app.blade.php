<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(Auth::check())
    
    @if(Auth::user()->user_type!='employee')
    <meta name="pusher-id" content="{{ Auth::check()? Auth::user()->id :'' }}" title="sup">
    @else
    <meta name="pusher-id" content="{{ Auth::check()? Auth::user()->users_details->i_ref_role_id :'' }}" title="emp">
    @endif
@else
<meta name="pusher-id" content="" type="">
@endif


<title>{{ trans('label.form_builder') }}: @yield('title')</title>
@include('admin.layout.head')
</head>
<body class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid">
<!-- BEGIN HEADER -->
@include('admin.layout.topbar')
<div class="page-container">
	<div class="page-sidebar-wrapper">
        @include('admin.layout.sidebar')
    </div>
    @yield('content')
@include('admin.layout.footer')