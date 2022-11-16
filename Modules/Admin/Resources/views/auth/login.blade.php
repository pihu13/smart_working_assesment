<!doctype html>
<html>
    @include('admin::includes.login-css')
    <body>
        <div class="login-page">
            <div class="login-box">
                <div class="contentBox">
                    <div class="logo d-flex flex-wrap w-100">
                        <img src="{{ asset('/storage/uploads/sitelogo//Ph7zlwn5m5jDGRk8BTjBxGbtfvVpT5xbK33nDLPe.jpg')}}" alt="Logo">
                    </div>
                    <h1>Welcome to Online Cancer Treatment Forum!</h1>
                    <p>Enter your email address and password to access admin panel-----.</p>
                    
                    @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul style="list-style: none;padding-left: 0;">
                            @foreach ($errors->all() as $error)
                            <li><strong>Error!</strong> {{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    @endif


                    @if ($message = Session::get('success'))    
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success</strong> {{ $message }}
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            @endif

                    <form class="mt-4" method="POST" id="login-form" action="{{ route('admin.login.post') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email Address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fal fa-envelope"></i></span>
                                </div>
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" autocomplete="off" placeholder="Email Address">
                                @if ($errors->has('email'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password @if (Route::has('forgot-password'))</label>
                            @endif
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fal fa-lock"></i></span>
                                </div>
                                <input id="password" type="password" placeholder="Password" class="form-control" name="password" autocomplete="off">
                                <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>
                                @if ($errors->has('password'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>

                       
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Login</button>
                            <a class="btn btn-link" href="{{ route('admin.forgot.password') }}">Forgot your password?</a>
                        </div>
                    </form>
                </div>
                <div class="imgBox d-none d-md-block">
                    <div><div class="sss"><a href="{{ route('admin.enquiry') }}" class="btn btn-primary">Online Enquiry</a></div></div>
                    
                </div>
            </div>
        </div>
        @include('admin::includes.login-js')
        <style type="text/css">

         .sss{
            padding:255px 0 0 169px; position:absolute
         }


            .field-icon {
                float: right;
                margin-right: 8px;
                margin-top: -23px;
                position: relative;
                z-index: 2;
                cursor:pointer;
            }

            .container{
                padding-top:50px;
                margin: auto;
            }

            #login-form span.toggle-password{
                right: 15px !important;
                left: auto !important;
                top: 15px !important;
                position: absolute;
                z-index: 11;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(document).on('click', '.toggle-password', function () {
                    jQuery(this).toggleClass("fa-eye fa-eye-slash");
                    var input = jQuery("#password");
                    input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
                });
            });
        </script>
    </body>
</html>