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
        <div class="row product-row">
            <div class="col-lg-2">
                <div class="nav flex-column nav-pills" id="section-tab" role="tablist">
                    @php $firstSection = true; @endphp
                    @foreach($data as $section => $ranges)
                        <a class="nav-link {{ $firstSection ? 'active' : '' }}"
                        id="section-{{ Str::slug($section) }}-tab"
                        data-toggle="pill"
                        href="#section-{{ Str::slug($section) }}"
                        role="tab">
                        {{ $section }}
                        </a>
                        @php $firstSection = false; @endphp
                    @endforeach
                </div>
            </div>

            <div class="col-lg-10">
                <div class="tab-content" id="section-tabContent">
                    @php $firstSection = true; @endphp
                    @foreach($data as $section => $ranges)
                        <div class="tab-pane fade {{ $firstSection ? 'show active' : '' }}"
                            id="section-{{ Str::slug($section) }}"
                            role="tabpanel">

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="nav flex-column nav-pills" id="range-tab-{{ Str::slug($section) }}" role="tablist">
                                        @php $firstRange = true; @endphp
                                        @foreach($ranges as $range => $products)
                                            <a class="nav-link {{ $firstRange ? 'active' : '' }}"
                                            id="range-{{ Str::slug($section.'-'.$range) }}-tab"
                                            data-toggle="pill"
                                            href="#range-{{ Str::slug($section.'-'.$range) }}"
                                            role="tab">
                                            {{ $range }}
                                            </a>
                                            @php $firstRange = false; @endphp
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-lg-9">
                                    <div class="tab-content" id="range-tabContent-{{ Str::slug($section) }}">
                                        @php $firstRange = true; @endphp
                                        @foreach($ranges as $range => $products)
                                            <div class="tab-pane fade {{ $firstRange ? 'show active' : '' }}"
                                                id="range-{{ Str::slug($section.'-'.$range) }}"
                                                role="tabpanel">

                                                <table class="table table-stripped responsive nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Description</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($products as $product)
                                                            <tr>
                                                                <td>{{ $product->product_code }}</td>
                                                                <td>{{ $product->description }}</td>
                                                                <td>{{ $product->sale_price }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                            @php $firstRange = false; @endphp
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        @php $firstSection = false; @endphp
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')

@endpush