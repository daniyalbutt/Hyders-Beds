@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Routes</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Routes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Routes</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('routes')
			<a href="{{ route('routes.index') }}" class="btn btn-primary">Orders List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<form class="form" method="post" action="{{ route('routes.store') }}">
	@csrf
	<div class="card h-100 mt-4">
		<div class="card-body">
			<h5 class="card-title">Routes Detail</h5>
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
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Name <strong>*</strong></label>
								<input type="text" class="form-control" name="name" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Start Date <strong>*</strong></label>
								<input type="date" class="form-control" name="start_date" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Start Time <strong>*</strong></label>
								<input type="time" class="form-control" name="start_time" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="form-label">Start Location <strong>*</strong></label>
								<input type="text" class="form-control" name="start_location" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="form-label">End Location <strong>*</strong></label>
								<input type="text" class="form-control" name="end_location" required>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer text-right">
					<button type="submit" class="btn btn-primary">Save Route</button>
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