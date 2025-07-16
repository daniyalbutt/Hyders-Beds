@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Edit Role - {{ $data->name }}</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Roles</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Role - {{ $data->name }}</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('role')
			<a href="{{ route('roles.index') }}" class="btn btn-primary">Role List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>


<div class="card h-100">
	<div class="card-body">
		<h4 class="card-title">Edit Role Form - {{ $data->name }}</h4>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('roles.update', $data->id) }}">
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
							<div class="form-group">
								<label class="form-label">Name</label>
								<input type="text" class="form-control" name="name" value="{{ old('name', $data->name) }}" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<ul class="role-wrapper">
							@foreach($permission as $key => $value)
								<li>
									<input name="permission[]" value="{{ $value->name }}" type="checkbox" id="basic_checkbox_{{$key}}" {{ in_array($value->name, $rolePermissions) ? 'checked' : '' }} />
									<label for="basic_checkbox_{{$key}}">{{ $value->name }}</label>
								</li>
							@endforeach
							</ul>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
@endpush