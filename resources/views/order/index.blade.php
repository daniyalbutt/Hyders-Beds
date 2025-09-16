@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Orders</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order List</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('create order')
			<a href="{{ route('orders.create') }}" class="btn btn-primary">Create Orders</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="card mb-4">
    <div class="card-body p-3">
        <form class="form-inline justify-content-end" method="get" action="{{ route('orders.index') }}">
            <label class="sr-only" for="inlineFormInputName2">Name</label>
			<input type="text" name="name" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Name" value="{{ Request::get('name') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
        </form>
    </div>
</div>

<div class="card h-100">
    <div class="card-body">
        <h5 class="card-title">Orders List</h5>
        @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
        @endif
        <table class="table table-stripped responsive nowrap" data-order="[[ 1, &quot;desc&quot; ]]">
            <thead>
                <tr>
                    <th>SNO.</th>
                    <th>Customer</th>
                    <th>Order ID</th>
                    <th>Order Reference</th>
                    <th>Order Date</th>
                    <th>Order Required Date</th>
                    <th>Delivery Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                <tr>
                    <td>
                        <p class="list-item-heading">{{ ++$key }}</p>
                    </td>
                    <td>
                        <p class="text-muted"><span class="badge badge-info badge-sm">{{ $value->get_customer->name }}</span></p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->id }}</p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->order_reference }}</p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->order_date }}</p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->required_date }}</p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->address }}</p>
                    </td>
                    <td>
                        <div class="d-flex">
                            @can('edit order')
                            <a href="{{ route('orders.edit', $value->id) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="glyph-icon iconsminds-file-edit"></i></a>
                            @endcan
                            @can('delete order')
                            <form action="{{ route('orders.destroy', $value->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="glyph-icon simple-icon-trash"></i></button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-box">
            {{ $data->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
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