<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Reset Password | {{env('SITE_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="">
    <meta name="description" content="">

    <!--[if IE]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/lib/modernizr.js') }}"></script>
    <link rel="icon" href="{{ asset('assets-admin/images/favicon.png') }}" type="image/gif">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-admin/plugins/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-admin/plugins/animate-it/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-admin/css/lib/cmp-bs-checkbox.css') }}">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-admin/css/lib/page-login.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-admin/css/style-default.css') }}">
</head>

<body>

    <div class="container">
        
        <div class="animatedParent">
            <div class="row">
                
                <div class="col-md-4 col-sm-4 hidden-xs">
                    
                </div>
                
                <div class="col-xs-12 col-md-4 col-sm-6 col-lg-4">
                    
                    <div class="blue-line sm normal"></div>
                    
                    <div class="signup-box">
                        <div class="logo"><h4><a href="{{url('/')}}">{{env('SITE_NAME')}}</a></h4></div>
                        
                        <div class="panel-heading">
                        @include('common.errors')

                        @if (Session::has('flash_message'))
                        <div align="center" class="alert alert-danger alert-dismissable mw800 center-block">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" color="blue">x</button>
                            <strong>{{Session::get('flash_message')}}</strong>
                        </div>
                        @endif

                        @if (Session::has('flash_message_success'))
                        <div align="center" class="alert alert-success alert-dismissable mw800 center-block">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" color="blue">x</button>
                            <strong>{{Session::get('flash_message_success')}}</strong>
                        </div>
                        @endif
                        </div>
                        
                        <form action="{{url('reset-update')}}" method="POST">
                            
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="New password" required="required" />
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </div>
                            <div class="form-group">
                                <input type="password" name="repeat" class="form-control" placeholder="Enter new password again" required="required" />
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </div>

                            <input type="hidden" name="token" value="{{$token}}" />

                            <div class="form-group">
                                {{--!! app('captcha')->display(); !!--}}
                            </div>
                            
                            
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </form>
                    </div>
                    
                    <br/>
                    <div class="signup-box">
                        <p class="signac animated flipInX">Don't have an account? <a href="{{url('register')}}">Sign up</a></p>
                    </div>
                    
                    <div class="blue-line lg normal"></div>
                </div>
            </div>
        </div>
    </div>  <!-- Container End --> 
    
    
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/lib/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/lib/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/animate-it/animate-it.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-admin/plugins/animate-it/arrow-line.js') }}"></script>
</body>
</html>