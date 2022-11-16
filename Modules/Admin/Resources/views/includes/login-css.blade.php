<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1, user-scalable=0" />
    @yield('meta')

    <title>Online Cancer Treatment Form</title>

    @yield('links')
    <link rel="shortcut icon" href="{{ asset('front/assets/images/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/admin/css/fontawesome.min.css') }}" rel="stylesheet" type="text/css">
    <link  rel="stylesheet" href="{{ asset('/admin/css/style.css' ) }}" type="text/css">
</head>
<style>
    .alert-success{
        height: 55px ;
    }
</style>