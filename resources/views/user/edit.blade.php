@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Users</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit User - {{ $data->name }}</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('user')
			<a href="{{ route('users.index') }}" class="btn btn-primary">User List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>


<div class="card h-100">
	<div class="card-body">
		<h5 class="card-title">Edit User Form - {{ $data->name }}</h5>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('users.update', $data->id) }}">
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
						<div class="col-md-3">
							<div class="form-group">
								<label class="form-label">E-mail</label>
								<input type="email" class="form-control" name="email" value="{{ old('email', $data->email) }}" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="form-label">Password</label>
								<input type="text" class="form-control" name="password">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label class="form-label">Role</label>
								<select name="role" id="role" class="form-control" required>
									@foreach($roles as $key => $value)
									<option value="{{ $value->name }}" {{ $data->getRole() == $value->name ? 'selected' : '' }}>{{ $value->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-12" id="production-tasks" style="display: {{ $data->hasRole('production') ? 'block' : 'none' }};">
							<label class="form-label">Assign Tasks</label>
							<div class="form-group">
								@foreach($tasks as $task)
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="tasks[]" value="{{ $task->id }}" id="task_{{ $task->id }}"
											{{ in_array($task->id, $userTasks) ? 'checked' : '' }}>
										<label class="form-check-label" for="task_{{ $task->id }}">
											{{ ucwords(str_replace('_', ' ', $task->name)) }}
										</label>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer mt-3">
					<button type="submit" class="btn btn-primary">Update User</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#role').change(function() {
            if ($(this).val() === 'production') {
                $('#production-tasks').show();
            } else {
                $('#production-tasks').hide();
                $('#production-tasks input[type=checkbox]').prop('checked', false);
            }
        });
    });
</script>
@endpush
