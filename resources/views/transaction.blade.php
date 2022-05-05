@extends('layouts.layout')
@section('title', 'Transactions')
@section('content')

        <div class="main-container">    <!-- START: Main Container -->
            
            <div class="page-header">
                <ol class="breadcrumb">
                    <li><a href="">Home</a></li>
                    <li class="active">Transactions</li>
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
                            <h4 class="panel-title">My Transactions</h4>
                        </div>
                        <div class="panel-body">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Detail</th>
											<th>Date</th>
											<th>Amount</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
	                                @if(count($transactions) < 1)
	                                    <tr>
	                                        <td colspan="4" align="center">No transaction yet.</td>
	                                    </tr>
	                                @else
	                                @foreach($transactions as $key=>$transaction)
	                                    <tr>
	                                        <td>{{$key + 1}}</td>
	                                        @if($transaction->recipient == Session::get('uid'))
	                                        <td>Donation received
	                                        </td>
	                                        @elseif($transaction->donor == Session::get('uid'))
	                                        <td><span class="text-info">Donation made</span>
	                                        </td>
	                                        @endif
	                                        <td>{{ date_create($transaction->date)->format("j M, g:i a") }}</td>
	                                        <td><b>&#8358;{{ $transaction->amount }}</b></td>
	                                        <td><span class="text-success">Success</span></td>
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
                    
                    
                </div>
                
            </div>  <!--END: Content Wrap-->
            
        </div>  <!-- END: Main Container -->


@endsection