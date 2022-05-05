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
                        
                        <div class="">
                            
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="table-basic-7">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                            </div>
                            <h4 class="panel-title">My Messages</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Encrypted Message</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($messages) < 1)
                                        <tr>
                                            <td colspan="4" align="center">No message yet.</td>
                                        </tr>
                                    @else
                                    @foreach($messages as $key=>$message)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td><a > {{ $message->subject }}</a>
                                            </td>
                                            <td><a href="#show-enc" data-toggle="modal" data-target="#showEnc{{ $key }}" title="click here to view"> {{ substr($message->encrypted_message, 0, 30) }} &hellip;</a>
                                                @include('modals.show')
                                            </td>
                                            <td>{{ date_create($message->created_at)->format("j M, g:i a") }}</td>
                                            <td>
                                                <form method="post" action="{{ url('view-message/'.$message->id) }}">
                                                    {{ csrf_field() }}
                                                    <input type="text" name="encryption_key" required="required">
                                                    <button type="submit" class="btn btn-info btn-xs">Decrypt</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- end panel -->

                        </div>
                        
                        <!--**********DONORS*****************-->
                            
                    </div>

                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="panel panel-default shadow-two">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Subscription</h4>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal form-bordered" action="{{ url('subscribe') }}" method="post" >
                                        {{csrf_field()}}
                                        <input type="hidden" name="ssh" value="{{ $profile->subscription }}" />
                                        <div class="form-group">
                                        <label class="control-label col-md-3"></label>
                                            <div class="col-md-6">
                                                @if($profile->subscription == 0)
                                                <button class="btn btn-success">Subscribe</button>
                                                @else
                                                <button class="btn btn-warning">Unsubscribe</button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                
            </div>  <!--END: Content Wrap-->
            
        </div>  <!-- END: Main Container -->


@endsection