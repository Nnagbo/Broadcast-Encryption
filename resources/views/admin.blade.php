@extends('layouts.layout')
@section('title', 'Dashboard')
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
                                                <th>Firstname</th>
                                                <th>Mobile</th>
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
                                                <td>{{ $user->firstname }}
                                                </td>
                                                <td>{{ $user->mobile }}
                                                </td>
                                                <td>
                                                    <a class="btn btn-xs btn-primary" href="#message-user" data-toggle="modal" data-target="#messageUserModal{{ $key }}">Send message</a>
                                                    @include('modals.message-user')
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


                    <div class="col-md-9">
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="table-basic-7">
                            <div class="panel-heading">
                                <div class="panel-heading-btn">
                                </div>
                                <h4 class="panel-title">Subscribers</h4>
                                <div class="tools">
                                    @if(count($subscribers) < 1)
                                    <a class="btn btn-xs btn-warning">No subscriber yet!</a>
                                    @else
                                    <a class="btn btn-xs btn-primary" href="#message-user" data-toggle="modal" data-target="#messageBroadcastModal">Broadcast Message</a>
                                    @include('modals.message-broadcast')
                                    @endif
                                
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Username</th>
                                                <th>Mobile</th>
                                                <!-- <th>Action</th> -->
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($subscribers) < 1)
                                            <tr>
                                                <td colspan="4" align="center">Nothing to display.</td>
                                            </tr>
                                        @else
                                        @foreach($subscribers as $key=>$subscriber)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{ $subscriber->username }}
                                                </td>
                                                <td>{{ $subscriber->mobile }}
                                                </td>
                                                <td>{{ date_create($user->created_at)->format("j M, g:i a") }}</td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{ $subscribers->links() }}
                            </div>
                        </div>
                        <!-- end panel -->
                    </div>
                    
                    
                </div>
                
            </div>  <!--END: Content Wrap-->
            
        </div>  <!-- END: Main Container -->


@endsection