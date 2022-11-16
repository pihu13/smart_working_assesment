<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1, user-scalable=0" />
        @yield('meta')
        <title>Dashboard</title>
        @yield('links')
        <link rel="icon" type="image/png" href="{{ asset('/admin/images/favicon-32x32.png') }}" sizes="32x32" />
        <link href="{{ asset('/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/admin/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css">
        <link  rel="stylesheet" href="{{ asset('/admin/css/style.css' ) }}" type="text/css">
        <style type="text/css">
            .alert-success{
                height: 55px ;
            }
        </style>
    </head>
    <body>
        <div class="login-page">
            <div class="login-box">
                <div class="contentBox">
                    <div class="logo d-flex flex-wrap w-100">
                        <img src="{{ asset('/admin/images/logo.svg')}}" alt="logo">
                    </div>
                    <h1>Reset Password</h1>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul style="list-style: none;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="POST" action="{{ route('admin.forgotupdate.post') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Please Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary w-100">Send Password Reset Link</button>
                        </div>
                    </form>
                </div>
                <div class="imgBox d-none d-md-block">
                    <img src="{{ asset('/admin/images/login.jpg') }}" alt="image">
                </div>
            </div>
        </div>

        <link href="{{ asset('/admin/css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{ asset('/admin/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/admin/js/bootstrap.bundle.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/admin/js/script.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/admin/js/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/admin/js/daterangepicker.min.js') }}"></script>
        <script src="{{ asset('/admin/ckeditor/ckeditor.js') }}"></script>
    </body>
</html>

