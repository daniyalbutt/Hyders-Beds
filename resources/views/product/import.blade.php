@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Import Products</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Import Product</li>
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
		<h5 class="card-title">Add Product Form</h5>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
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
						<div class="col-md-12">
							<div class="form-group mb-3">
								<label class="form-label">File <strong>*</strong></label>
								<input type="file" class="form-control" name="file" required>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Upload Product</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
@endpush