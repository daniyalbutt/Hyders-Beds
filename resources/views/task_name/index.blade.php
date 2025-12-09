@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Task Name</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Task Name</a></li>
                <li class="breadcrumb-item active" aria-current="page">Task Name List</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('create task_names')
			<a href="{{ route('task-names.create') }}" class="btn btn-primary">Create Task Name</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="card mb-4">
    <div class="card-body p-3">
        <form class="form-inline justify-content-end" method="get" action="{{ route('task-names.index') }}">
            <label class="sr-only" for="inlineFormInputName2">Name</label>
			<input type="text" name="name" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Name" value="{{ Request::get('name') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
        </form>
    </div>
</div>

<div class="card h-100">
    <div class="card-body">
        <h5 class="card-title">Task Name List</h5>
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
                    <th>Name</th>
                    <th>Allow Next Step</th>
                    <th>Step</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="task-list">
                @foreach($data as $key => $value)
                <tr data-id="{{ $value->id }}">
                    <td>
                        <p class="text-muted">{{ $value->name }}</p>
                    </td>
                    <td>
                        <p class="text-muted">
                            <span class="badge badge-sm {{ $value->allow_next_step == 0 ? 'badge-danger' : 'badge-info' }}">
                                {{ $value->allow_next_step == 0 ? 'NO' : 'YES' }}
                            </span>
                        </p>
                    </td>
                    <td>
                        <p class="text-muted"><span class="badge badge-secondary badge-sm">{{ $value->order }}</span></p>
                    </td>
                    <td>
                        <div class="d-flex">
                            @can('edit task_names')
                            <a href="{{ route('task-names.edit', $value->id) }}" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="glyph-icon iconsminds-file-edit"></i></a>
                            @endcan
                            @can('delete task_names')
                            <form action="{{ route('task-names.destroy', $value->id) }}" method="post">
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
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#task-list").sortable({
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            let order = [];
            $('#task-list tr').each(function(index, element) {
                order.push({
                    id: $(element).data('id'),
                    order: index + 1
                });
            });

            $.ajax({
                url: '{{ route("task-names.reorder") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: order
                },
                success: function(response) {
                    // Optional: show success message or update UI
                    location.reload(); // reload to show updated order badges
                }
            });
        }
    });
});
</script>
@endpush