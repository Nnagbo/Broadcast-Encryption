@extends('layouts.layout')
@section('title', 'Console')
@section('content')

        <div class="main-container">    <!-- START: Main Container -->
            
            <div class="page-header">
                <ol class="breadcrumb">
                    <li><a href="">Home</a></li>
                    <li class="active">Dashboard</li>
                </ol>
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
                        <div class="row">

                            <!-- users table -->
                            <div class="col-md-9">
                                <!-- begin panel -->
                                <div class="panel panel-inverse" data-sortable-id="table-basic-7">
                                    <div class="panel-heading">
                                        <div class="panel-heading-btn">
                                        </div>
                                        <h4 class="panel-title">All Users</h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Username</th>
                                                        <th>Fullname</th>
                                                        <th>Mobile</th>
                                                        <th>Dormant</th>
                                                        <th>Action</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($users) < 1)
                                                    <tr>
                                                        <td colspan="4" align="center">Nothing to display.</td>
                                                    </tr>
                                                @else
                                                @foreach($users as $key=>$user)
                                                    <tr>
                                                        <td>{{$key + 1}}</td>
                                                        <td>{{ $user->username }}
                                                        </td>
                                                        <td>{{ $user->firstname.' '.$user->lastname }}
                                                        </td>
                                                        <td>{{ $user->mobile }}
                                                        </td>
                                                        <td>
                                                        @if($user->dormant)
                                                        <span class="text-warning">Dormant</span>
                                                        @else
                                                        <span class="text-primary">Active</span>
                                                        @endif
                                                        </td>
                                                        <td>
                                                        @if($user->deleted)
                                                            <button class="btn btn-inverse btn-xs" disabled="disabled">Deleted</button>
                                                        @else
                                                            <a class="btn btn-xs btn-danger" href="#delete-user" data-toggle="modal" data-target="#deleteUserModal{{ $key }}">Delete</a>
                                                            @include('modals.delete-user')
                                                        @endif
                                                        </td>
                                                        <td>{{ date_create($user->created_at)->format("j M, g:i a") }}</td>
                                                    </tr>
                                                @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        {{ $users->links() }}
                                    </div>
                                </div>
                                <!-- end panel -->
                            </div>
                            <!-- end users table -->

                            <div class="col-md-9">
                                
                                <div class="">
                                    <div class="panel panel-default shadow-two">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Top Up</h3>
                                            <div class="tools">
                                                                                    
                                            </div>
                                        </div>
                                        <div class="panel-body" data-height="200px">

                                            <form action="{{url('top-up')}}" method="post">
                                            {{csrf_field()}}
                                                    
                                                <div class="form-group form-group-lg">
                                                    <input class="input-lg" type="text" name="username" id="username" placeholder="Username" required="required" />
                                                </div>
                                                <div class=" form-inline">

                                                    <div class="form-group form-group-lg">
                                                        <input class="input-lg" type="text" name="amount" id="amount" placeholder="Amount" required="required" />
                                                    </div>

                                                    <div class="form-group form-group-lg">
                                                        <input class="input-lg" type="text" name="repeat" id="repeat" placeholder="Confirm Amount" required="required" />
                                                    </div>
                                                </div>
                                                <div class="clear"></div><br /><br />


                                                    <div class="form-group form-group-lg">
                                                        <button class="btn btn-info btn-rounded btn-block" id="add-to-cart" type="submit">Top Up</button>
                                                    </div>

                                            </form>
                                        </div>
                                    </div>

                                </div>
                                
                                <!--**********DONORS*****************-->
                                    
                            </div>
                        </div>
                    </div>
                    <!-- end content section (left) -->
                    
                    <div class="col-md-3 col-sm-6 col-lg-3">
                        <div class="panel panel-default shadow-two">
                            <div class="panel-heading">
                                <!-- <i class="sli-credit-card text-primary"></i> -->
                                <span class="panel-title">Total Amount GH</span>
                            </div>
                            <div class="panel-body text-center">
                                <h2>&#8358;{{ $stats->total_amount_gh }} </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-lg-3">
                        <div class="panel panel-default shadow-two">
                            <div class="panel-heading">
                                <!-- <i class="sli-credit-card text-primary"></i> -->
                                <span class="panel-title">Total Amount PH</span>
                            </div>
                            <div class="panel-body text-center">
                                <h2>&#8358;{{ $stats->total_amount_ph }} </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-lg-3">
                        <div class="panel panel-default shadow-two">
                            <div class="panel-heading">
                                <!-- <i class="sli-credit-card text-primary"></i> -->
                                <span class="panel-title">Total Tokens</span>
                            </div>
                            <div class="panel-body text-center">
                                <h2>&#8358;{{ $stats->total_tokens }} </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-lg-3">
                        <div class="panel panel-default shadow-two">
                            <div class="panel-heading">
                                <!-- <i class="sli-credit-card text-primary"></i> -->
                                <span class="panel-title">Total GH</span>
                            </div>
                            <div class="panel-body text-center">
                                <h2>{{ $stats->total_gh }} </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-lg-3">
                        <div class="panel panel-default shadow-two">
                            <div class="panel-heading">
                                <!-- <i class="sli-credit-card text-primary"></i> -->
                                <span class="panel-title">Total Users</span>
                            </div>
                            <div class="panel-body text-center">
                                <h2>{{ $stats->total_users }} </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-lg-3">
                        <div class="panel panel-default shadow-two">
                            <div class="panel-heading">
                                <!-- <i class="sli-credit-card text-primary"></i> -->
                                <span class="panel-title">Deleted Users</span>
                            </div>
                            <div class="panel-body text-center">
                                <h2>{{ $stats->total_suspended }} </h2>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>  <!--END: Content Wrap-->
            
        </div>  <!-- END: Main Container -->


@endsection