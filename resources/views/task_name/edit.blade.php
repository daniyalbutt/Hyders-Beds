@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Task Name</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Task Name</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit Task Name - {{ $data->name }}</li>
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
			<form class="form" method="post" action="{{ route('task-names.update', $data->id) }}">
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
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Name</label>
								<input type="text" class="form-control" name="name" value="{{ $data->name }}" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Allow Next Step</label>
								<select name="allow_next_step" id="allow_next_step" class="form-control">
									<option value="1" {{ $data->allow_next_step == 1 ? 'selected' : '' }}>YES</option>
									<option value="0" {{ $data->allow_next_step == 0 ? 'selected' : '' }}>NO</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Order</label>
								<input type="number" class="form-control" name="order" value="{{ $data->order }}" required>
							</div>
						</div>
					</div>
					<hr>
					<h6>Production Type Order</h6>

					<div id="repeater">
						@forelse($data->productionTypes as $key => $row)
						    <div class="row repeater-item mb-2 {{ $key == 0 ? 'first-row' : '' }}">
								<div class="col-md-5">
									<select name="production[{{ $key }}][production_type]" class="form-control" required>
										<option value="">Select Production Type</option>
										@foreach($productionTypes as $type)
											<option value="{{ $type }}" {{ $row->production_type == $type ? 'selected' : '' }}>
												{{ $type }}
											</option>
										@endforeach
									</select>
								</div>

								<div class="col-md-4">
									<input type="number"
										name="production[{{ $key }}][order_number]"
										class="form-control"
										value="{{ $row->order_number }}"
										placeholder="Order Number"
										required>
								</div>

								<div class="col-md-3">
								@if($key != 0)
									<button type="button" class="btn btn-danger remove-row">Remove</button>
								@endif
								@if($key == 0)
									<button type="button" class="btn btn-success" id="add-row">+ Add More</button>
								@endif
								</div>
							</div>
						@empty
							<div class="row repeater-item mb-2">
								<div class="col-md-5">
									<select name="production[0][production_type]" class="form-control" required>
										<option value="">Select Production Type</option>
										@foreach($productionTypes as $type)
											<option value="{{ $type }}">{{ $type }}</option>
										@endforeach
									</select>
								</div>

								<div class="col-md-4">
									<input type="number"
										name="production[0][order_number]"
										class="form-control"
										placeholder="Order Number"
										required>
								</div>

								<div class="col-md-3">
									<button type="button" class="btn btn-success" id="add-row">+ Add More</button>
								</div>
							</div>
						@endforelse
					</div>

				</div>
				<!-- /.box-body -->
				<div class="box-footer mt-4">
					<button type="submit" class="btn btn-primary">Update Task Name</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
    let index = {{ $data->productionTypes->count() ?? 1 }};

    document.getElementById('add-row').addEventListener('click', function () {
        let html = `
        <div class="row repeater-item mb-2">
            <div class="col-md-5">
                <select name="production[${index}][production_type]" class="form-control" required>
                    <option value="">Select Production Type</option>
                    @foreach($productionTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <input type="number"
                       name="production[${index}][order_number]"
                       class="form-control"
                       placeholder="Order Number"
                       required>
            </div>

            <div class="col-md-3">
                <button type="button" class="btn btn-danger remove-row">Remove</button>
            </div>
        </div>
        `;
        document.getElementById('repeater').insertAdjacentHTML('beforeend', html);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.repeater-item').remove();
        }
    });
</script>
@endpush
