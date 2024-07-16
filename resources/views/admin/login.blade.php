<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('backend/assets/images/favicon-32x32.png') }}" type="image/png" />
        <link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('backend/assets/js/pace.min.js') }}"></script>
        <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">
        <title>Login</title>
    </head>
    <body class="bg-login">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="wrapper">
            <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
                <div class="container-fluid">
                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                        <div class="col mx-auto">
                            <div class="card loginCard">
                                <div class="card-body">
                                    <div class="mb-4 mt-3 text-center">
                                        <img src="{{ asset('backend/assets/images/logo2.png') }}" width="160" alt="" />
                                    </div>
                                    <div class="p-2 rounded">
                                      
                                            <div class="loginData">
                                                <b>Demo data</b>:
                                                <br /><br />
                                                <p>
                                                    Email: mario@rossi.it
                                                    <br />
                                                    Password: 12345678
                                                </p>
                                            </div>
                                      

                                        <div class="form-body">
                                            <form class="row g-3" method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="col-12">
                                                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                    <input type="email" name="email" class="form-control inputShadow" id="email" required autofocus placeholder="Email" value="mario@rossi.it">
                                                </div>
                                                <div class="col-12">
                                                    <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                                    <div class="input-group" id="show_hide_password">
                                                        <input type="password" name="password" class="form-control border-end-0 inputShadow" id="password" placeholder="Enter Password"> 
                                                        <a href="javascript:;" class="input-group-text bg-transparent inputShadow"><i class='bx bx-hide'></i></a>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input inputShadow" name="remember" type="checkbox" id="flexSwitchCheckChecked" checked>
                                                        <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 text-end"> 
                                                    <a href="{{ route('password.request') }}">
                                                        Forgot Password ?
                                                    </a>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary" style="background:#e1564e;border-color:#e1564e">
                                                            <i class="bx bxs-lock-open"></i>Sign in
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script>
    $(document).ready(function () {
        $("#show_hide_password a").on('click', function (event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
    </script>
    <script src="{{ asset('backend/assets/js/app.js') }}"></script>
    </body>
</html>
