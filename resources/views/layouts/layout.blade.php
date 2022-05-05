<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{env('SITE_NAME')}}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    

    
    <link href="{{ asset('assets-admin/plugins/bootstrap/bootstrap.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('assets-admin/css/main.css') }}" rel='stylesheet' type='text/css' />

    <link href="{{ asset('assets-admin/css/style.min.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('assets-admin/css/style-default.css') }}" rel='stylesheet' type='text/css' />
    <!-- <link href="" rel="stylesheet" id="theme" /> -->
    
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/lib/modernizr.js') }}"></script>
    <link rel="icon" href="{{ asset('assets-admin/images/favicon.ico') }}" type="image/gif">
    


    <!-- ================== END BASE JS ================== -->
</head>
<body>
    
    <div class="wrapper has-footer">

        
    @include('layouts.header.nav')
        <!-- end #sidebar -->
        
        <!-- begin #content -->
    @yield('content')
        <footer class="footer"> <!-- START: Footer -->
            &#169; {{ date('Y') }} <b>{{env('SITE_NAME')}}</b>
        </footer>   <!-- END: Footer -->
        
    </div>  <!-- END: wrapper -->

    <script src="{{ asset('assets-admin/plugins/lib/jquery-2.2.4.min.js') }}"> </script>
    <script src="{{ asset('assets-admin/plugins/lib/jquery-ui.min.js') }}"> </script>
    <script src="{{ asset('assets-admin/plugins/lib/plugins.js') }}"> </script>
    <script src="{{ asset('assets-admin/plugins/bootstrap/bootstrap.min.js') }}"> </script>
    
    <script src="{{ asset('assets-admin/js/app.base.js') }}"> </script>
</body>

</html>