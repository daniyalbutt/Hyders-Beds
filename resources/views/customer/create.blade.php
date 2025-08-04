@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Customers</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Customers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Customer</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('customer')
			<a href="{{ route('customers.index') }}" class="btn btn-primary">Customer List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>

<div class="card h-100">
	<div class="card-body">
		<h5 class="card-title">Add Customer Form</h5>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('customers.store') }}">
				@csrf
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
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Name <strong>*</strong></label>
								<input type="text" class="form-control" name="name" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Email <strong>*</strong></label>
								<input type="email" class="form-control" name="email" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Telephone <strong>*</strong></label>
								<input type="text" class="form-control" name="telephone" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Address <strong>*</strong></label>
								<input type="text" class="form-control" name="address" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">City <strong>*</strong></label>
								<input type="text" class="form-control" name="city" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Country <strong>*</strong></label>
								<input type="text" class="form-control" name="country" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Postcode <strong>*</strong></label>
								<input type="text" class="form-control" name="postcode" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group mb-3">
								<label class="form-label">Description</label>
								<textarea name="description" id="description" class="form-control"></textarea>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Save Customer</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
@endpush