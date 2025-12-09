@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Task Name</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Task Name</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Task Name</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('task_names')
			<a href="{{ route('task-names.index') }}" class="btn btn-primary">Task Name List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>

<div class="card h-100">
	<div class="card-body">
		<h5 class="card-title">Add Task Name Form</h5>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('task-names.store') }}">
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
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Name</label>
								<input type="text" class="form-control" name="name" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Allow Next Step</label>
								<select name="allow_next_step" id="allow_next_step" class="form-control">
									<option value="1">YES</option>
									<option value="0">NO</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Order</label>
								<input type="number" class="form-control" name="order" value="{{ $nextOrder }}" required>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Save Task Name</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
@endpush