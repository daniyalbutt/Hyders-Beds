@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Routes</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Routes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Route List</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('create routes')
			<a href="{{ route('routes.create') }}" class="btn btn-primary">Create Routes</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="card mb-4">
    <div class="card-body p-3">
        <form class="form-inline justify-content-end" method="get" action="{{ route('routes.index') }}">
            <label class="sr-only" for="inlineFormInputName2">Name</label>
			<input type="text" name="name" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Name" value="{{ Request::get('name') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
        </form>
    </div>
</div>

@foreach($days as $key => $value)
<div class="card h-100 mb-2">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ $value['day'] }}</h5>
            <p class="mb-0 badge badge-info">{{ $value['date'] }}</p>
        </div>
        @if($value['routes']->count())
        <table class="table table-stripped responsive nowrap mt-3 mb-0 align-middle">
            @foreach($value['routes'] as $route)
            <tr>
                <td class="align-middle">{{ $route->name }}</td>
                <td class="align-middle">{{ $route->start_date }} - {{ $route->start_time->format('h:i A') }}</td>
                <td class="align-middle">({{ $route->start_location }} → {{ $route->end_location }})</td>
                <td class="pr-0 align-middle">
                    <div class="d-flex justify-content-end">
                        @can('edit routes')
                        <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="glyph-icon iconsminds-file-edit"></i></a>
                        @endcan
                        @can('delete routes')
                        <form action="{{ route('routes.destroy', $route->id) }}" method="post" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="glyph-icon simple-icon-trash"></i></button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>
</div>
@endforeach

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