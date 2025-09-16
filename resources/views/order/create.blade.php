@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Orders</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Order</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('order')
			<a href="{{ route('orders.index') }}" class="btn btn-primary">Orders List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<form class="form" method="post" action="{{ route('orders.store') }}">
	@csrf
	<div class="card h-100">
		<div class="card-body">
			<h5 class="card-title">Add Order Form</h5>
			<div class="basic-form">
				<div class="box-body">
					@if($errors->any())
						{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
					@endif
					@if(session()->has('success'))
						<div class="alert alert-success">
							{{ session()->get('success') }}
						</div>
					@endif
					<div class="row">
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="form-label">Customer <strong>*</strong></label>
								<select name="customer" id="customer-select" class="form-control" required></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="form-label">Delivery Address <strong>*</strong></label>
								<select name="address" id="address-select" class="form-control" required>
									
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card h-100 mt-4">
		<div class="card-body">
			<h5 class="card-title">Order Detail</h5>
			<div class="basic-form">
				<div class="box-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Order Date <strong>*</strong></label>
								<input type="date" class="form-control" name="order_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Customer Order Reference</label>
								<input type="text" class="form-control" name="order_reference">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Order Type</label>
								<select name="order_type" id="order_type" class="form-control">
									<option value="order">Order</option>
									<option value="Quote">Quote</option>
									<option value="Credit">Credit</option>
									<option value="Fulfilment">Fulfilment</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Required Date</label>
								<input type="date" class="form-control" name="required_date">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Salesperson 1</label>
								<select name="salesperson_one" id="salesperson_one" class="form-control select2-single">
									<option value="">Choose Salesperson</option>
									@foreach($salesPersons as $key => $value)
									<option value="{{ $value->id }}">{{ $value->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Salesperson 2</label>
								<select name="salesperson_two" id="salesperson_two" class="form-control select2-single">
									<option value="">Choose Salesperson</option>
									@foreach($salesPersons as $key => $value)
									<option value="{{ $value->id }}">{{ $value->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Customer Contact</label>
								<select name="customer_contact" id="customer_contact" class="form-control">
									<option value="">Choose Contact</option>
									<option value="Accounts">Accounts</option>
									<option value="Nasir">Nasir</option>
									<option value="Saarah">Saarah</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer text-right">
					<button type="submit" class="btn btn-primary">Save Order</button>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@push('scripts')
<script>
	$(document).ready(function() {
		$('#customer-select').select2({
			placeholder: 'Search for a Customer',
			ajax: {
				url: "{{ route('customer.search') }}",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					};
				},
				processResults: function(data) {
					return {
						results: data
					};
				},
				cache: true
			},
			minimumInputLength: 1,
			templateResult: function(customer) {
				if (!customer.id) return customer.text;
				return $('<div><strong>' + customer.text + '</strong></div>');
			},
			templateSelection: function(customer) {
				return customer.text || customer.id;
			}
		});

		$('#customer-select').on('select2:select', function (e) {
			var data = e.params.data;
			$('#address-select').empty();
			$('#address-select').append(new Option("Choose Delivery Address", "", true, true));
			if (data.address) {
				var optionValue = data.address + ' | ' + data.city + ' | ' + data.country;
				var optionText = data.address + ' | ' + data.city + ' | ' + data.country;
				$('#address-select').append(new Option(optionText, optionValue, false, false));
			}
			$('#address-select').append(new Option("Collection", "collection", false, false));
			$('#address-select').trigger('change');
		});



	});
</script>
@endpush