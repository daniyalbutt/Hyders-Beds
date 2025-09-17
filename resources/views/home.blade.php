@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Dashboard</h1>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="row">
                @can('customer')
                <div class="col-6 col-lg-2 mb-4">
                    <div class="card dashboard-small-chart-analytics">
                        <div class="card-body">
                            <i class="iconsminds-add-user"></i>
                            <p class="lead color-theme-1 mb-1 value">Customers</p>
                            <p class="mb-0 label">{{ $total_customer }}</p>
                        </div>
                    </div>
                </div>
                @endcan
                @can('product')
                <div class="col-6 col-lg-2 mb-4">
                    <div class="card dashboard-small-chart-analytics">
                        <div class="card-body">
                            <i class="iconsminds-pantone"></i>
                            <p class="lead color-theme-1 mb-1 value">Products</p>
                            <p class="mb-0 label">{{ $total_product }}</p>
                        </div>
                    </div>
                </div>
                @endcan
                @can('order')
                <div class="col-6 col-lg-2 mb-4">
                    <div class="card dashboard-small-chart-analytics">
                        <div class="card-body">
                            <i class="iconsminds-shopping-bag"></i>
                            <p class="lead color-theme-1 mb-1 value">Orders</p>
                            <p class="mb-0 label">{{ $total_order }}</p>
                        </div>
                    </div>
                </div>
                @endcan

            </div>
        </div>
    </div>
</div>
@endsection
