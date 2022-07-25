<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HubEnterprise: Login</title>
    <link href="{{ asset('assets_old/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets_old/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/css/custom_style.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            box-sizing: border-box;
        }

    </style>
</head>

<body class="signin">
    <div class="main">
        <div class="signinpanel">
            <div class="row signin-detail">
                <div class="col-12 col-lg-7">
                    <div class="signin-info">
                        <div class="logopanel">
                            <div class="logo">
                                <a href="http://hubsoftware.debutinfotech.com/" target="_blank">
                                    <img src="{{ asset('assets/images/logo.png')}}" alt="hubenterprise" style="height: 85px;">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    @include('partials.messages')
                    <form action="{{ route('login') }}" novalidate="novalidate" id="UserLoginForm" method="POST" accept-charset="utf-8">
                        @csrf
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <h4 class="heading">SignIn</h4>
                        <p class="login-text">Login to access your account.</p>
                        <div class="inputfield">
                            <input type="email" placeholder="Email" class="form-control" name="email" id="UserEmail">
                            <img src="{{ asset('assets/images/user.png')}}" class="inputimg"></div>
                        <div class="inputfield">
                            <input type="password" placeholder="Password" name="password" class="form-control" id="UserPassword">
                            <img src="{{ asset('assets/images/locked.png')}}" class="inputimg"></div>
                        <div class="inputfield">
                            <select class="form-control" name="user_type" id="UserType">
                                <option value="company">Company</option>
                                <option value="employee" selected="selected">Employee</option>
                            </select>
                            <img src="{{ asset('assets/images/user.png')}}" class="inputimg">
                        </div>
                        <div class="check-field">
                            <div class="form-check checkbox-data">
                                <input class="form-check-input accept" type="checkbox" value="1" name="remember_me" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Remember me
                                </label>
                            </div>
                            <div class="form-check term-checked">
                                <input class="form-check-input accept" type="checkbox" value="" id="defaultCheck2" name="terms_condition">
                                <label class="form-check-label" for="defaultCheck2">
                                    Accept terms of HUB ENTERPRISE
                                </label>
                            </div>
                        </div>
                        <a href="{{!empty($terms_conditions)? url('/uploads/'.$terms_conditions->file.'') : ''}} " target="_blank"> View Terms and Conditions </a>
                        <button value="Submit" class="btn submit-btn btn-block" type="submit">Submit</button>
                        <a href="{{ route('forgot_password') }}" class="forget-pass">Forgot Password</a>
                        <a href="{{ route('supplier.login') }}" class="login-supplier">Login as Supplier</a>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="{{ asset('assets/js/login.js') }}" type="text/javascript"></script>
    @include('admin.layout.footer')
