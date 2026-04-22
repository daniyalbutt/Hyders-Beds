@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Import Orders</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Import</li>
            </ol>
        </nav>
    </div>
    <div class="col-lg-6">
        <div class="text-right">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary mr-2">Back to Orders</a>
            <a href="{{ route('orders.sample-csv') }}" class="btn btn-outline-success">
                <i class="simple-icon-cloud-download mr-1"></i> Download Sample CSV
            </a>
        </div>
    </div>
    <div class="col-md-12">
        <div class="separator mb-5"></div>
    </div>
</div>

{{-- ── Alerts ──────────────────────────────────────────────────────────── --}}
@if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session()->has('import_errors') && count(session('import_errors')))
    <div class="alert alert-warning">
        <strong>The following rows were skipped:</strong>
        <ul class="mb-0 mt-2">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif



{{-- ── How-it-works card ───────────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">How the import works</h5>

        <p class="mb-2">Upload a <strong>.csv</strong>, <strong>.xlsx</strong>, or <strong>.xls</strong> file. Each row in the file represents <strong>one order line (item)</strong>. Multiple rows sharing the same <code>order_reference</code> + <code>customer_email</code> are grouped into a single order automatically.</p>

        <h6 class="mt-3">Required columns</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Column name</th>
                        <th>Required?</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><code>customer_name</code></td>      <td>Yes</td>  <td>For reference only — not used for lookup.</td></tr>
                    <tr><td><code>customer_email</code></td>     <td>Yes</td>  <td>Matched against the customers table. If no match is found the entire order is <strong>skipped</strong> and reported in the errors list.</td></tr>
                    <tr><td><code>delivery_address</code></td>   <td>Yes</td>  <td>The address stored on the order (can differ from the customer's address).</td></tr>
                    <tr><td><code>order_date</code></td>         <td>Yes</td>  <td>Accepted formats: <code>YYYY-MM-DD</code>, <code>MM/DD/YYYY</code>, <code>DD/MM/YYYY</code>.</td></tr>
                    <tr><td><code>order_reference</code></td>    <td>Yes</td>  <td>Used together with <code>customer_email</code> to group rows into one order.</td></tr>
                    <tr><td><code>order_type</code></td>         <td>No</td>   <td>Defaults to <em>order</em>.</td></tr>
                    <tr><td><code>required_date</code></td>      <td>No</td>   <td>Same date formats as <code>order_date</code>. Leave blank if not known.</td></tr>
                    <tr><td><code>product_code</code></td>       <td>Yes</td>  <td>Matched against the <code>products</code> table. If no match is found the item is still saved with <code>product_id = NULL</code> — you can link it manually later.</td></tr>
                    <tr><td><code>description</code></td>        <td>No</td>   <td>Falls back to the product's description if left blank.</td></tr>
                    <tr><td><code>price</code></td>              <td>No</td>   <td>Falls back to the product's sale price if left blank.</td></tr>
                    <tr><td><code>quantity</code></td>           <td>No</td>   <td>Defaults to 1.</td></tr>
                    <tr><td><code>fabric_name</code></td>        <td>No</td>   <td>Name of the chosen fabric (e.g. <em>GENESIS CREAM</em>). Leave blank if not applicable.</td></tr>
                    <tr><td><code>fabric_price</code></td>       <td>No</td>   <td>Fabric surcharge price. Defaults to <em>0.00</em>.</td></tr>
                    <tr><td><code>drawer_name</code></td>        <td>No</td>   <td>Name of the chosen drawer (e.g. <em>Continental Drawer</em>). Leave blank if not applicable.</td></tr>
                    <tr><td><code>drawer_price</code></td>       <td>No</td>   <td>Drawer surcharge price. Defaults to <em>0.00</em>.</td></tr>
                </tbody>
            </table>
        </div>

        <div class="alert alert-warning mt-3 mb-0">
            <strong>Unknown customers:</strong> If a <code>customer_email</code> is not found in the system, that order is <strong>skipped entirely</strong> and listed in the errors summary after import. Make sure the customer exists in <a href="{{ route('customers.index') }}">Customers</a> before importing their orders.
        </div>
    </div>
</div>

{{-- ── Upload form ─────────────────────────────────────────────────────── --}}
<div class="card h-100">
    <div class="card-body">
        <h5 class="card-title">Upload File</h5>

        @if($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif

        <form method="POST" action="{{ route('orders.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Select CSV / Excel File <strong>*</strong></label>
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input"
                                   id="importFile"
                                   name="file"
                                   accept=".csv,.xlsx,.xls"
                                   required>
                            <label class="custom-file-label" for="importFile">Choose file…</label>
                        </div>
                        <small class="form-text text-muted">Accepted: .csv, .xlsx, .xls — max 5 MB</small>
                    </div>
                </div>
                <div class="col-md-6" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="simple-icon-cloud-upload mr-1"></i> Import Orders
                    </button>
                    <a href="{{ route('orders.sample-csv') }}" class="btn btn-outline-success ml-2">
                        Download Sample CSV
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show selected filename in the custom-file label
    document.getElementById('importFile').addEventListener('change', function () {
        var fileName = this.files[0] ? this.files[0].name : 'Choose file…';
        this.nextElementSibling.textContent = fileName;
    });
</script>
@endpush