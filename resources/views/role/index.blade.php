@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Roles</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Roles</a></li>
                <li class="breadcrumb-item active" aria-current="page">Role List</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('create role')
			<a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>

<div class="card mb-4">
    <div class="card-body p-3">
        <form class="form-inline justify-content-end" method="get" action="{{ route('roles.index') }}">
            <label class="sr-only" for="inlineFormInputName2">Name</label>
			<input type="text" name="name" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Name" value="{{ Request::get('name') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
        </form>
    </div>
</div>

<div class="card h-100">
    <div class="card-body">
        <h5 class="card-title">Roles List</h5>
		@if($errors->any())
		{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
		@endif
		@if(session()->has('success'))
		<div class="alert alert-success">
			{{ session()->get('success') }}
		</div>
		@endif
		<div class="table-responsive">
			<table class="table table-responsive-md">
				<thead>
					<tr>
						<th><strong>SNO</strong></th>
						<th><strong>NAME</strong></th>
						<th><strong>CREATED AT</strong></th>
						<th><strong>Action</strong></th>
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key => $value)
					<tr>
						<td>{{ ++$key }}</td>
						<td>{{ $value->name }}</td>
						<td>{{ $value->created_at->format('d M, Y g:i A') }}</td>
						<td>
							<div class="d-flex mt-2">
								@can('edit role')
								<a href="{{ route('roles.edit', $value->id) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="glyph-icon iconsminds-file-edit"></i></a>
								@endcan
								@can('delete role')
								<form action="{{ route('roles.destroy', $value->id) }}" method="post">
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
		</div>
	</div>
</div>
@endsection

@push('scripts')
   <!-- <script type="text/javascript">-->
   <!-- 	$(function () {-->
   <!-- 		'use strict';-->
   <!-- 		$('#example1').DataTable({-->
		 <!-- 		'paging'      : true,-->
		 <!-- 		'lengthChange': false,-->
		 <!-- 		'searching'   : false,-->
		 <!-- 		'ordering'    : true,-->
		 <!-- 		'info'        : true,-->
		 <!-- 		'autoWidth'   : false-->
			<!--});-->
   <!-- 	});-->
   <!-- </script>-->
@endpush