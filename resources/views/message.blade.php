@extends('layouts.layout')
@section('title', 'Message')
@section('content')

        <div class="main-container">    <!-- START: Main Container -->
            
            <div class="page-header">
                <ol class="breadcrumb">
                    <li><a href="">Home</a></li>
                    <li class="active">Message</li>
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
                            <h4 class="panel-title">{{ $message->subject }}  - {{ date_create($message->created_at)->format("j M, g:i a")}}</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <p>
                                    {{ $original_msg }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- end panel -->

                        </div>
                        
                        <!--**********DONORS*****************-->
                            
                    </div>
                    
                    
                </div>
                
            </div>  <!--END: Content Wrap-->
            
        </div>  <!-- END: Main Container -->


@endsection