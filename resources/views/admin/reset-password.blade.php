<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HubEnterprise: Reset password</title>
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
                                    <img src="{{ asset('assets/images/logo.png')}}" alt="hubenterprise"
                                        style="height: 85px;">
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    @include('partials.messages')
                    <form action="{{ route('reset_password',  ['id' => $user->id_encrypted, 'token' => $user->vc_password_token]) }}" novalidate="novalidate" id="resetPassword"
                        method="POST" accept-charset="utf-8">
                        @csrf
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <h4 class="heading">Reset Password</h4>
                        <p class="login-text">Create new password for this Email <b> {{$user->email}} </b></p>
                        <div class="inputfield">
                            <input type="password" placeholder="Enter Password" name="password" class="form-control"
                                id="password">
                            <img src="{{ asset('assets/images/locked.png')}}" class="inputimg"></div>
                        <div class="inputfield">
                            <input type="password" placeholder="Confirm Password" name="cpassword" class="form-control"
                                id="cpassword">
                            <img src="{{ asset('assets/images/locked.png')}}" class="inputimg"></div>
                        <button value="Submit" class="btn submit-btn btn-block" type="submit">Submit</button>
                        <a href="{{ route('login') }}" class="forget-pass">Login</a>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="{{ asset('assets/js/login.js') }}" type="text/javascript"></script>
    @include('admin.layout.footer')
