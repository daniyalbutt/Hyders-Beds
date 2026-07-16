@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Order #{{ $order->id }}</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                @can('order')
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                @endcan
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
            </ol>
        </nav>
    </div>
    <div class="col-lg-6">
        <div class="text-right d-flex align-items-center gap-2 justify-content-end">
            @can('edit order')
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
                <i class="glyph-icon iconsminds-file-edit mr-1"></i> Edit Order
            </a>
            @endcan
            @can('order')
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary ml-2">Orders List</a>
            @endcan
        </div>
    </div>
    <div class="col-md-12">
        <div class="separator mb-5"></div>
    </div>
</div>


<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-0">
                    <h5 class="card-title mb-0">Order Details</h5>
                    <div>
                        {!! $order->draft == 0
                            ? '<span class="badge badge-light">Drafted</span>'
                            : '<span class="badge badge-success">Published</span>' !!}
                        @if($order->send_to_production)
                            <span class="badge badge-success ml-1">In Production</span>
                        @else
                            <span class="badge badge-danger ml-1">Not In Production</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Customer
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ $order->get_customer->name }}</p>
                </div>
            </div>
        </div>
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Delivery Address
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ $order->address }}</p>
                </div>
            </div>
        </div>
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Order Number
                    </a>
                    <p class="mb-0 text-muted text-small w-60">#{{ $order->id }}</p>
                </div>
            </div>
        </div>
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Order Date
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ $order->order_date }}</p>
                </div>
            </div>
        </div>
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Required Date
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ $order->required_date ?? '—' }}</p>
                </div>
            </div>
        </div>
        @if($order->order_reference)
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Order Reference
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ $order->order_reference }}</p>
                </div>
            </div>
        </div>
        @endif
        @if($order->order_type)
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Order Type
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ ucfirst($order->order_type) }}</p>
                </div>
            </div>
        </div>
        @endif
        @if($order->salesperson_one)
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Salesperson 1
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ optional($order->salesperson1)->name ?? '—' }}</p>
                </div>
            </div>
        </div>
        @endif
        @if($order->salesperson_two)
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Salesperson 2
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ optional($order->salesperson2)->name ?? '—' }}</p>
                </div>
            </div>
        </div>
        @endif
        @if($order->customer_contact)
        <div class="card d-flex flex-row mb-3">
            <div class="d-flex flex-grow-1 min-width-zero">
                <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                    <a class="list-item-heading mb-0 truncate w-40 w-xs-100" href="javascript:;">
                    Customer Contact
                    </a>
                    <p class="mb-0 text-muted text-small w-60">{{ $order->customer_contact }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Order Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:30px;"></th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>QTY</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                            <tr class="{{ $item->fabric_name || $item->drawer_name ? 'table-light' : '' }}">
                                <td class="align-middle text-center">
                                    @if($item->fabric_name || $item->drawer_name)
                                        <i class="glyph-icon simple-icon-layers text-primary"></i>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $item->product_code }}</td>
                                <td class="align-middle">{{ $item->description }}</td>
                                <td class="align-middle">£{{ number_format($item->price, 2) }}</td>
                                <td class="align-middle">{{ $item->quantity }}</td>
                                <td class="align-middle text-right">£{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @if($item->fabric_name)
                            <tr class="bg-light">
                                <td></td>
                                <td colspan="2" class="align-middle pl-4 text-muted">
                                    <i class="glyph-icon simple-icon-tag mr-1"></i>
                                    <small>Fabric: <strong>{{ $item->fabric_name }}</strong></small>
                                </td>
                                <td class="align-middle text-muted"><small>£{{ number_format($item->fabric_price, 2) }}</small></td>
                                <td class="align-middle">—</td>
                                <td class="align-middle text-right">—</td>
                            </tr>
                            @endif
                            @if($item->drawer_name)
                            <tr class="bg-light">
                                <td></td>
                                <td colspan="2" class="align-middle pl-4 text-muted">
                                    <i class="glyph-icon simple-icon-drawer mr-1"></i>
                                    <small>Drawer: <strong>{{ $item->drawer_name }}</strong></small>
                                </td>
                                <td class="align-middle text-muted"><small>£{{ number_format($item->drawer_price, 2) }}</small></td>
                                <td class="align-middle">—</td>
                                <td class="align-middle text-right">—</td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No items on this order.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            {{-- Deposits --}}
                            @foreach($order->deposits as $deposit)
                            <tr>
                                <td colspan="4" class="text-right text-muted">
                                    Deposit
                                    <span class="badge badge-dark ml-1">{{ $deposit->id }}</span>
                                    @if($deposit->description)
                                        <small class="ml-2">{{ $deposit->description }}</small>
                                    @endif
                                </td>
                                <td colspan="2" class="text-right text-success font-weight-bold">
                                    -£{{ number_format($deposit->amount, 2) }}
                                </td>
                            </tr>
                            @endforeach

                            @php
                                $itemsTotal    = $order->items->sum('total');
                                $depositsTotal = $order->deposits->sum('amount');
                                $netTotal      = $itemsTotal - $depositsTotal;
                                $vat           = $netTotal * 0.20;
                                $grand         = $netTotal + $vat;
                            @endphp

                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total:</td>
                                <td colspan="2" class="text-right font-weight-bold">£{{ number_format($netTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">VAT (20%):</td>
                                <td colspan="2" class="text-right">£{{ number_format($vat, 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="4" class="text-right font-weight-bold">Grand Total:</td>
                                <td colspan="2" class="text-right font-weight-bold">£{{ number_format($grand, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
@endpush