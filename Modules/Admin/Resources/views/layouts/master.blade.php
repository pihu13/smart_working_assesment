<!doctype html>
<html>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1, user-scalable=0" />
    <title>{{config('app.name')}}</title>

    @include('admin::includes.css')
    <style type="text/css">
        .loading-box {
            position: fixed;
            z-index: 99999999;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.92);
        }
        .copyright {
            padding-left: 280px;
        }
        .red_require{
            color:red;
        }
        .fee_error{
            font-size: 14px;
            color: red;
            margin-top: 5px;
            display: block;
        }
    </style>
    <body>
        @include('admin::includes.header')
        @include('admin::includes.sidebar')

        @yield('admin::content')

        @include('admin::includes.footer')
        @include('admin::includes.js')
        @yield('js')
        @toastr_js
        @toastr_render
        <div class="loading-box" style="display:none;">
            <div class="loader">
                <div class="ball-grid-pulse">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                //jQuery(".googlemapshown").geocomplete();
                let autoDom = document.getElementsByClassName('googlemapshown');
                new google.maps.places.Autocomplete(autoDom[0]);
            });
        </script>
    </body>
</html>
