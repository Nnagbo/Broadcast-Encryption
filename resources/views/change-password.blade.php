@extends('layouts.layout')
@section('title', 'Change Password')
@section('content')

        <div class="main-container">    <!-- START: Main Container -->
            
            <div class="page-header">
                <ol class="breadcrumb">
                    <li><a href="">Home</a></li>
                    <li class="active">Change Password</li>
                </ol>
                <!-- <h1 class="page-header">Change Password </h1> -->
            </div>
            
            <div class="content-wrap">  <!--START: Content Wrap-->
                    
                <div class="row">
                    <div class="col-lg-12">

                        <!-- Display Validation Errors -->
                        @include('common.errors')

                        @if (Session::has('flash_message'))
                        <div align="center" class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" color="blue">&times;</button>
                            <strong>{{Session::get('flash_message')}}</strong>

                        </div>
                        @endif

                        @if (Session::has('flash_message_success'))
                        <div align="center" class="alert alert-success alert-dismissable ">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" color="blue">&times;</button>
                            <i class="fa fa-info-circle"></i><strong>{{Session::get('flash_message_success')}}</strong>

                        </div>
                        @endif

                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-9">
                        
                        <div class="">
                            
                            <div class="col-md-8">
                                <div class="panel panel-default shadow-two">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Change Password</h4>
                                    </div>
                                    <div class="panel-body">
                                        <form class="form-horizontal form-bordered" action="{{url('account/change-password')}}" method="post" >
                                            {{csrf_field()}}

                                            <div class="form-group ">
                                                <input class="form-control" type="password" name="old_password" id="old_password" placeholder="Old password" required="required" />
                                            </div><br />
                                            <div class=" form-inline">

                                                <div class="form-group ">
                                                    <input class="form-control" type="password" name="password" id="password" placeholder="New password" required="required" />
                                                </div>

                                                <div class="form-group ">
                                                    <input class="form-control" type="password" name="repeat" id="repeat" placeholder="Confirm Password" required="required" />
                                                </div>
                                            </div>
                                            <div class="clear"></div><br /><br />

                                            <div class="form-group">
                                            <label class="control-label col-md-3"></label>
                                                <div class="col-md-6">
                                                    <button class="btn btn-success">Update</button>
                                                </div>
                                            </div>


                                                <!-- <div class="form-group form-group-lg">
                                                    <button class="btn btn-info btn-rounded btn-block btn-lg" id="add-to-cart" type="submit">Update</button>
                                                </div> -->

                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <!--**********DONORS*****************-->
                            
                    </div>
                    
                    
                </div>
                
            </div>  <!--END: Content Wrap-->
            
        </div>  <!-- END: Main Container -->


@endsection