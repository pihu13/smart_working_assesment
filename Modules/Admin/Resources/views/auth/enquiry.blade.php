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
                    <h1>Enquiry Form</h1>
                  
                  

                    
                            @if(count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul style="list-style: none;padding-left: 0;">
                                    @foreach ($errors->all() as $error)
                                    <li><strong>Error!</strong> {{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            @endif
                    

                    <form class="mt-4" method="POST" id="login-form" action="{{ route('admin.enquiry.post') }}" enctype="multipart/form-data">
                        @csrf


                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            
                                
                                <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>
                                <div class="col-md-6">
                                    <input type="tel" class="form-control" id="phone_number" minlength="4" maxlength="14" autocomplete="off" name="phone_number" placeholder="Phone Number" value="{{ old('phone_number' )}}">
                                    <label for="phone_number" style="color: red;display:none;" class="error_code">Phone number is invalid.</label>
                                     @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('State') }}</label>

                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" required autocomplete="state" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required autocomplete="city" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Zipcode') }}</label>

                            <div class="col-md-6">
                                <input id="zipcode" type="text" class="form-control @error('zipcode') is-invalid @enderror" name="zipcode" value="{{ old('zipcode') }}" required autocomplete="zipcode" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-6">
                            <textarea name="address" class="form-control" minlength="3" maxlength="160" placeholder="Please enter address">{{ old("address") }}</textarea>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Cancer Type') }}</label>
                    <div class="col-md-6">
                    <select name="cancer_type">
                        @foreach($cancers as $cancer)
                    <option value="{{ @$cancer->title }}">{{ @$cancer->title }}</option>
                       @endforeach
                    </select>
                    </div>


                    <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Images') }}</label>
                    <div class="col-md-6">
                        <input type="file" name="enquiry_image[]" accept=".png, .jpg, .jpeg, .mp3, .mp4" id="cat_image_full" value="" class="form-control cat_image_full" multiple>
                      
                    </div>
                    </div>
                       
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            
                        </div>
                    </form>
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