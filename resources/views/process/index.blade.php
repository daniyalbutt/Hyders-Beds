@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Process</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Process</a></li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="card mb-4">
    <div class="card-body p-3">
        <form class="form-inline justify-content-end" method="get" action="{{ route('users.index') }}">
            <label class="sr-only" for="inlineFormInputName2">Name</label>
			<input type="text" name="name" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Name" value="{{ Request::get('name') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
        </form>
    </div>
</div>

@foreach($data as $route)
<div class="card mb-4 shadow-sm">
    <div class="card-body">

        <!-- Route Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-1">{{ $route->name }}</h5>
                <p class="text-muted mb-0">
                    <i class="simple-icon-clock"></i>
                    {{ $route->start_date }} {{ $route->start_time }}
                </p>
            </div>

            <a href="{{ route('process.show', $route->id) }}"
               class="btn btn-primary btn-sm">
                <i class="iconsminds-blinklist"></i>
            </a>
        </div>

        <!-- Orders -->
        @foreach($route->orders as $order)
        <span class="badge {{ $order->status === 'Completed' ? 'badge-success' : 'badge-warning' }}">
            {{ $order->status }}
        </span>

        <div class="card mb-2 border">
            <div class="card-header p-3 pl-4 pr-4">
                <a class="d-flex justify-content-between align-items-center text-decoration-none"
                   data-toggle="collapse"
                   href="#order-{{ $order->id }}">
                    <div>
                        <strong>Order #{{ $order->id }}</strong>
                        <span class="text-muted ml-2">
                            {{ $order->order_date }}
                        </span>
                    </div>
                    <span class="badge badge-outline-primary">
                        {{ $order->items->count() }} Items
                    </span>
                </a>
            </div>

            <!-- Order Items -->
            <div id="order-{{ $order->id }}" class="collapse">
                <div class="card-body p-2">

                    <div class="table-responsive">
                        <table class="table table-md table-striped mb-0">
                            <thead class="">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Fabric</th>
                                    <th class="text-right">Drawer</th>
                                    <th class="text-right">Completed Process</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                @php
                                    $task = $item->current_task;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $item->description }}</strong><br>
                                        <small class="text-muted">
                                            {{ $item->product_code ?? '' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-right">
                                        @if($item->fabric_name != null)
                                        {{ $item->fabric_name }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($item->drawer_name != null)
                                        {{ $item->drawer_name }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($task)
                                            <span class="badge badge-success">
                                                {{ $task->task->name }}
                                            </span>
                                        @else
                                            <span class="badge badge-outline-secondary">
                                                Not started
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endforeach

@endsection

@push('scripts')
   <!-- <script type="text/javascript">-->
   <!-- 	$(function () {-->
   <!-- 		'use strict';-->
   <!-- 		$('#example1').DataTable({-->
		 <!-- 		'paging'      : true,-->
		 <!-- 		'lengthChange': false,-->
		 <!-- 		'searching'   : false,-->
		 <!-- 		'ordering'    : true,-->
		 <!-- 		'info'        : true,-->
		 <!-- 		'autoWidth'   : false-->
			<!--});-->
   <!-- 	});-->
   <!-- </script>-->
@endpush