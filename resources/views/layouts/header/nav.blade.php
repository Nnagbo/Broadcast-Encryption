
        
        <header class="header-top navbar fixed-top">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle side-nav-toggle">
                    <i class="ti-align-left"></i>
                </button>

                <a class="navbar-brand" href="{{url('/')}}">
                    <span>{{env('SITE_NAME')}}</span>
                </a>

                <ul class="nav navbar-nav-xs">  <!-- START: Responsive Top Right tool bar -->
                    <li>
                        <a href="javascript:;" class="collapse" data-toggle="collapse" data-target="#headerNavbarCollapse">
                            <i class="sli-user"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="search-toggle">
                            <i class="sli-magnifier"></i>
                        </a>
                    </li>
                    
                </ul>   <!-- END: Responsive Top Right tool bar -->
                
            </div>
            
            <div class="collapse navbar-collapse" id="headerNavbarCollapse">
                
                <ul class="nav navbar-nav">
                    
                    <li class="hidden-xs">
                        <a href="javascript:;" class="sidenav-size-toggle">
                            <i class="ti-align-left"></i>
                        </a>
                    </li>

                </ul>
                
                <ul class="nav navbar-nav navbar-right">
                                        
                    <li class="user-profile dropdown">
                        <a href="javascript:;" class="clearfix dropdown-toggle" data-toggle="dropdown">
                            <div class="user-name">{{ env('username') }} <small class="fa fa-angle-down"></small></div>
                        </a>
                        <ul class="dropdown-menu dropdown-animated pop-effect" role="menu">
                            <li><a href="{{url('logout')}}"><i class="sli-logout"></i> Logout</a></li>
                        </ul>
                    </li>
                    
                </ul>
                
            </div><!-- END: Navbar-collapse -->
            
        </header>

       <!-- END: Header -->        
                <aside class="side-navigation-wrap sidebar-fixed">  <!-- START: Side Navigation -->
            <div class="sidenav-inner">
                
                <ul class="side-nav magic-nav">
                    
                    <!-- <li class="side-nav-header">Main</li> -->
                    
                    <li>
                        <a href="{{url('dashboard')}}">
                            <i class="sli-dashboard"></i> 
                            <span class="nav-text">My Messages</span>
                        </a>
                    </li>
                    @if(Session::get('priority') > 3)
                    <li>
                        <a href="{{url('console')}}">
                            <i class="sli-settings"></i> 
                            <span class="nav-text">Console</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('bad-keys')}}">
                            <i class="sli-settings"></i> 
                            <span class="nav-text">Failed</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{url('logout')}}">
                            <i class="sli-logout"></i> 
                            <span class="nav-text">Logout</span>
                        </a>
                    </li>
                    
                    
                    
                </ul>
                
            </div><!-- END: sidebar-inner -->
            
        </aside>    <!-- END: Side Navigation -->  