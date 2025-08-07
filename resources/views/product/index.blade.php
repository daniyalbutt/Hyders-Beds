@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Products</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Product List</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('create product')
            <a href="{{ route('product.import') }}" class="btn btn-outline-primary">Import Product</a>
			<a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="card mb-4">
    <div class="card-body p-3">
        <form class="form-inline justify-content-end" method="get" action="{{ route('products.index') }}">
            <label class="sr-only" for="inlineFormInputName2">Name</label>
			<input type="text" name="product_code" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Product Code" value="{{ Request::get('product_code') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
        </form>
    </div>
</div>

<div class="card h-100">
    <div class="card-body">
        <h5 class="card-title">Product List</h5>
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
                    <th>Product Code</th>
                    <th>Description</th>
                    <th>Product Range</th>
                    <th>Product Section</th>
                    <th>Production Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                <tr>
                    <td>
                        <p class="list-item-heading">{{ $value->product_code }}</p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->description }}</p>
                    </td>
                    <td>
                        <p class="text-muted"><span class="badge badge-info badge-sm">{{ $value->product_range }}</span></p>
                    </td>
                    <td>
                        <p class="text-muted">{{ $value->product_section }}</p>
                    </td>
                    <td>
                        <p class="text-muted"><span class="badge badge-secondary badge-sm">{{ $value->production_type }}</span></p>
                    </td>
                    <td>
                        <div class="d-flex">
                            @can('edit product')
                            <a href="{{ route('products.edit', $value->id) }}" class="btn btn-primary shadow btn-xs sharp me-1"><i class="glyph-icon iconsminds-file-edit"></i></a>
                            @endcan
                            @can('delete product')
                            <form action="{{ route('products.destroy', $value->id) }}" method="post">
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

@endpush