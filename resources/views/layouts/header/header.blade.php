
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <div id="mobile-menu">
                    <div class="left-nav-toggle">
                        <a href="#">
                            <i class="stroke-hamburgermenu"></i>
                        </a>
                    </div>
                </div>
                <a class="navbar-brand" id="home" >
                POS {{Session::get('username')}}
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <div class="left-nav-toggle">
                    <a >
                        <i class="stroke-hamburgermenu"></i>
                    </a>
                </div>
                <form class="navbar-form navbar-left">
                    <!-- <input type="text" class="form-control" placeholder="Search Messages & Contacts" style="width: 175px"> -->
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <!-- <a  ><i class="material-icons">notifications_active</i>
                            <span class="label label-warning pull-right" id="notif-total"></span>
                        </a> -->
                    </li>
                    <li class=" profil-link">
                        <a data-toggle="dropdown" class="">
                            <span class="profile-address"></span>
                            <img src="../images/profile.jpg" class="img-circle" alt="">
                        </a>
                        <ul class="dropdown-menu">
                            <!-- <li><div class="icon-example-name bal" href="#">Balance <span id="balance" class="dash-foot-exec"> </span>
                                    <span><button class="btn btn-sm btn-danger" href="#recharge">Top Up</button></span>
                                </div>
                            </li> -->
                            <li><a class="icon-person" >{{Session::get('username')}}</a></li>
                            <li class="divider"></li>
                            <li><a class="icon-settings" href="#">Settings</a></li>
                            <li class="divider"></li>
                            <li><a class="icon-exit_to_app" id="logout" href="{{url('/logout')}}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                
            </div>
        </div>
    </nav>