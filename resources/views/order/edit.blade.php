@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Orders</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Order - {{ $data->id }}</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('order')
			<a href="{{ route('orders.index') }}" class="btn btn-primary">Orders List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<form class="form" method="post" action="{{ route('orders.update', $data->id) }}">
	@csrf
	@method('PUT')
	<div class="card h-100">
		<div class="card-body">
			<div class="basic-form">
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
								<label class="form-label">Customer <strong>*</strong></label>
								<select name="customer" class="form-control" disabled>
									<option value="" selected>{{ $data->get_customer->name }}</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Delivery Address <strong>*</strong></label>
								<select name="address" id="address-select" class="form-control" disabled>
									<option value="" selected>{{ $data->address }}</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-3">
								<label class="form-label">Order Number <strong>*</strong></label>
								<input type="text" value="{{ $data->id }}" class="form-control" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4">
			<div class="card h-100 mt-4">
				<div class="card-body">
					<h5 class="card-title mb-3">Sections</h5>
					<button id="backBtn" type="button" class="btn btn-sm btn-secondary mb-3 d-none">← Back</button>
					<div class="basic-form">
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="drill-wrapper">
										<div id="loader" class="text-center my-3 d-none">
											<div class="spinner-border text-primary" role="status">
												<span class="sr-only">Loading...</span>
											</div>
										</div>
										<div id="drilldownContent">
											<ul class="product-section">
												@foreach($products as $key => $product)
												<li><a class="btn btn-primary section-btn" href="javascript:;" data-section="{{ $product->product_section }}">{{ $product->product_section }}</a></li>
												@endforeach
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="card h-100 mt-4">
				<div class="card-body">
					<div class="basic-form">
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
								<div class="col-md-12">
									<table id="orderTable" class="table table-stripped responsive nowrap" data-order="[[ 1, &quot;desc&quot; ]]">
										<thead>
											<tr>
												<th>Code</th>
												<th>Description</th>
												<th>Price</th>
												<th>QTY</th>
												<th>Total Price</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data->items as $item)
											<tr data-code="{{ $item->product_code }}">
												<td class="align-middle">{{ $item->product_code }}</td>
												<td class="align-middle">{{ $item->description }}</td>
												<td class="align-middle">{{ number_format($item->price, 2) }}</td>
												<td class="align-middle item-qty">{{ $item->quantity }}</td>
												<td class="align-middle item-total">{{ number_format($item->total, 2) }}</td>
												<td class="align-middle">
													<button type="button" class="btn btn-danger shadow btn-xs sharp remove-item" data-id="{{ $item->id }}">
														<i class="glyph-icon simple-icon-trash"></i>
													</button>
												</td>
											</tr>
											@endforeach
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4" class="text-right font-weight-bold">Grand Total:</td>
												<td id="grandTotal" class="font-weight-bold">
													{{ number_format($data->items->sum('total'), 2) }}
												</td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@push('scripts')
<script>
	$(function(){
		let historyStack = [];

		function showLoader() {
			$('#loader').removeClass('d-none');
		}
		function hideLoader() {
			$('#loader').addClass('d-none');
		}

		$(document).on('click', '.section-btn', function(){
			let section = $(this).data('section');
			historyStack.push($('#drilldownContent').html()); // save current view
			$('#backBtn').removeClass('d-none');
			showLoader();
			$.get('/products/types/' + section, function(data){
				let html = '<h6>Production Types in <b>'+section+'</b></h6><ul class="product-section">';
				data.forEach(function(type){
					html += '<li><a class="btn btn-success type-btn" href="javascript:;" data-section="'+section+'" data-type="'+type+'">'+type+'</a></li>';
				});
				html += '</ul>';
				$('#drilldownContent').html(html);
			}).always(function(){
				hideLoader();
			});
		});

		$(document).on('click', '.type-btn', function(){
			let section = $(this).data('section');
			let type = $(this).data('type');
			historyStack.push($('#drilldownContent').html());
			showLoader();
			$.get('/products/ranges/' + section + '/' + type, function(data){
				let html = '<h6>Ranges in <b>'+type+'</b></h6><ul class="product-section">';
				data.forEach(function(range){
					html += '<li><a class="btn btn-warning range-btn" href="javascript:;" data-section="'+section+'" data-type="'+type+'" data-range="'+range+'">'+range+'</a></li>';
				});
				html += '</ul>';
				$('#drilldownContent').html(html);
			}).always(function(){
				hideLoader();
			});
		});

		$(document).on('click', '.range-btn', function(){
			let section = $(this).data('section');
			let type = $(this).data('type');
			let range = $(this).data('range');
			historyStack.push($('#drilldownContent').html());
			showLoader();
			$.get('/products/list/' + section + '/' + type + '/' + range, function(data){
				let html = '<h6>Products in <b>'+range+'</b></h6><ul class="product-section product-list">';
				data.forEach(function(p){
					let codeHtml = p.product_code ? `<h6>${p.product_code}</h6>` : '';
					let descHtml = p.description ? `<p>${p.description}</p>` : '';
					let priceHtml = p.sale_price ? `<h5>${p.sale_price}</h5>` : '';
					html += `
					<li>
						<a href="javascript:;" class="btn btn-info product-btn">
							${codeHtml}
							${descHtml}
							${priceHtml}
							<div class="quantity-form d-none">
								<input type="number" class="form-control form-control-sm qty-input mb-2" value="1" min="1">
								<button type="button" class="btn btn-sm btn-success add-to-order"
										data-code="${p.product_code}"
										data-desc="${p.description}"
										data-price="${p.sale_price}">
									Add to Order
								</button>
							</div>
						</a>
					</li>`;
				});
				html += '</ul>';
				$('#drilldownContent').html(html);
			}).always(function(){
				hideLoader();
			});
		});

		$('#backBtn').on('click', function(){
			if(historyStack.length > 0){
				$('#drilldownContent').html(historyStack.pop());
			}
			if(historyStack.length === 0){
				$('#backBtn').addClass('d-none'); // hide if at top level
			}
		});

		$(document).on('click', '.product-btn', function(){
			$(this).find('.quantity-form').toggleClass('d-none');
		});

		$(document).on('click', '.add-to-order', function(){
			let code = $(this).data('code');
			let desc = $(this).data('desc');
			let price = parseFloat($(this).data('price'));
			let qty = parseInt($(this).closest('.quantity-form').find('.qty-input').val()) || 1;
			let orderId = "{{ $data->id }}";

			$.ajax({
				url: '/orders/' + orderId + '/items',
				method: 'POST',
				data: {
					_token: '{{ csrf_token() }}', // CSRF token
					code: code,
					description: desc,
					price: price,
					qty: qty
				},
				success: function(response) {
					if (response.success) {
						let item = response.item;
						let existingRow = $('#orderTable tbody tr[data-code="'+ item.product_code +'"]');
						if (existingRow.length > 0) {
							existingRow.find('.item-qty').text(item.quantity);
							existingRow.find('.item-total').text(parseFloat(item.total).toFixed(2));
							existingRow.addClass('table-warning');
							setTimeout(() => {
								existingRow.removeClass('table-warning');
							}, 800);
						} else {
							let row = `
								<tr data-code="${item.product_code}">
									<td class="align-middle">${item.product_code}</td>
									<td class="align-middle">${item.description}</td>
									<td class="align-middle">${parseFloat(item.price).toFixed(2)}</td>
									<td class="align-middle item-qty">${item.quantity}</td>
									<td class="align-middle item-total">${item.total.toFixed(2)}</td>
									<td class="align-middle">
										<button type="button" class="btn btn-danger shadow btn-xs sharp remove-item" data-id="${item.id}"><i class="glyph-icon simple-icon-trash"></i></button>
									</td>
								</tr>
							`;
							$('#orderTable tbody').append(row);
							row.fadeIn(300);
						}
						updateGrandTotal();
					}
				}
			});
			$(this).closest('.quantity-form').addClass('d-none');
		});

		$(document).on('click', '.remove-item', function(){
			let row = $(this).closest('tr');
			let itemId = $(this).data('id');
			let orderId = "{{ $data->id }}";

			$.ajax({
				url: '/orders/' + orderId + '/items/' + itemId,
				method: 'DELETE',
				data: { _token: '{{ csrf_token() }}' },
				success: function() {
					row.fadeOut(300, function(){
						$(this).remove();
						updateGrandTotal();
					});
				}
			});
		});

		$(document).on('click', '.quantity-form', function(e){
			e.stopPropagation();
		});

	});

	function updateGrandTotal() {
		let total = 0;
		$('#orderTable tbody tr').each(function(){
			let rowTotal = parseFloat($(this).find('.item-total').text()) || 0;
			total += rowTotal;
		});
		$('#grandTotal').text(total.toFixed(2));
	}
</script>
@endpush