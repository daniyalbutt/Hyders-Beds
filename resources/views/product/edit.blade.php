@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Edit Product - {{ $data->product_code }}</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Product - {{ $data->product_code }}</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('product')
			<a href="{{ route('products.index') }}" class="btn btn-primary">Product List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>


<div class="card h-100">
	<div class="card-body">
		<h5 class="card-title">Edit Product Form - {{ $data->product_code }}</h5>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('products.update', $data->id) }}">
				@csrf
				@method('PUT')
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
								<label class="form-label">Product Code</label>
								<input type="text" class="form-control" name="product_code" value="{{ old('product_code', $data->product_code) }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group mb-3">
								<label class="form-label">Product Description <strong>*</strong></label>
								<input type="text" class="form-control" name="description" value="{{ old('description', $data->description) }}" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-3">
								<label class="form-label">Sale Price</label>
								<input type="number" class="form-control" name="sale_price" step="0.01" value="{{ old('sale_price', $data->sale_price) }}" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Product Range</label>
								<select name="product_range" id="product_range" class="form-control select2-single">
									<option value="0">Select Product Range</option>
									@foreach(config('product.ranges') as $key => $value)
									<option value="{{ $value }}" {{ $value == $data->product_range ? 'selected' : '' }}>{{ $value }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Product Section <strong>*</strong></label>
								<select name="product_section" id="product_section" class="form-control select2-single" required>
									<option value="0">Select Product Section</option>
									@foreach(config('product.section') as $key => $value)
									<option value="{{ $value }}" {{ $value == $data->product_section ? 'selected' : '' }}>{{ $value }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Production Type <strong>*</strong></label>
								<select name="production_type" id="production_type" class="form-control select2-single" required>
									<option value="0">Select Product Type</option>
									@foreach(config('product.type') as $key => $value)
									<option value="{{ $value }}" {{ $value == $data->production_type ? 'selected' : '' }}>{{ $value }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-3">
								<label class="form-label">Volume <strong>*</strong></label>
								<input type="number" step="0.01" class="form-control" name="volume" value="{{ $data->volume }}" required>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-3">
								<label class="form-label">Weight</label>
								<input type="number" step="0.01" class="form-control" name="weight" value="{{ $data->weight }}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-3">
								<label class="form-label">Width</label>
								<input type="number" step="0.01" class="form-control" name="width" value="{{ $data->weight }}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-3">
								<label class="form-label">Length</label>
								<input type="number" step="0.01" class="form-control" name="length" value="{{ $data->length }}">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-3">
								<label class="form-label">Height</label>
								<input type="number" step="0.01" class="form-control" name="height" value="{{ $data->height }}">
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer mt-3">
					<button type="submit" class="btn btn-primary">Update Product</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
@endpush