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
			<div class="card mt-4">
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
									<ul class="btn-table">
										<li>
											<button type="button" class="btn btn-light btn-xs">Delivery Label</button>
										</li>
									</ul>
									<table id="orderTable" class="table table-stripped responsive nowrap" data-order="[[ 1, &quot;desc&quot; ]]">
										<thead>
											<tr>
												<th>Code</th>
												<th>Description</th>
												<th>Price</th>
												<th>QTY</th>
												<th>Total</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data->items as $item)
											<tr data-code="{{ $item->product_code }}" data-id="{{ $item->product_id }}">
												<td class="align-middle">{{ $item->product_code }}</td>
												<td class="align-middle">{{ $item->description }}</td>
												<td class="align-middle">{{ number_format($item->price, 2) }}</td>
												<td class="align-middle item-qty">
													<span class="qty-text">{{ $item->quantity }}</span>
													<button type="button" class="btn btn-xs btn-link text-primary edit-qty" 
															data-id="{{ $item->id }}" 
															data-product="{{ $item->product_id }}" 
															data-qty="{{ $item->quantity }}">
														<i class="glyph-icon simple-icon-pencil"></i>
													</button>
												</td>
												<td class="align-middle item-total">{{ number_format($item->total, 2) }}</td>
												<td class="align-middle">
													<div class="d-flex">
														<button type="button" class="btn btn-danger shadow btn-xs sharp remove-item" data-id="{{ $item->id }}">
															<i class="glyph-icon simple-icon-trash"></i>
														</button>
													</div>
												</td>
											</tr>
											@endforeach
										</tbody>
										<tfoot>
											@foreach($data->deposits as $deposit)
											<tr class="deposit-row">
												<td colspan="4" class="text-right font-weight-bold align-middle">
													<div class="d-flex justify-content-end align-items-center">
														Deposit <span class="badge badge-dark ml-2">{{$deposit->id}}</span> <input type="text" value="{{ $deposit->description }}" style="width: 200px;height: 30px;margin-left: 10px;">
													</div>
												</td>
												<td class="text-success font-weight-bold align-middle deposit-amount" data-amount="{{ number_format($deposit->amount, 2) }}">-{{ number_format($deposit->amount, 2) }}</td>
												<td class="align-middle">
													<button type="button" class="btn btn-danger shadow btn-xs sharp remove-deposit" data-id="{{ $deposit->id }}">
														<i class="glyph-icon simple-icon-trash"></i>
													</button>
												</td>
											</tr>
											@endforeach
											@php
												$itemsTotal    = $data->items->sum('total');
												$depositsTotal = $data->deposits->sum('amount');

												$netTotal = $itemsTotal - $depositsTotal;
												$vat      = $netTotal * 0.20;
												$grand    = $netTotal + $vat;
											@endphp
											<tr>
												<td colspan="4" class="text-right font-weight-bold align-middle">Total:</td>
												<td id="totalAmount" class="align-middle">{{ number_format($netTotal, 2) }}</td>
												<td class="align-middle">
													<button type="button" class="btn btn-primary btn-xs" id="depositBtn">Deposit</button>
												</td>
											</tr>
											<tr>
												<td colspan="4" class="text-right font-weight-bold">VAT (20%):</td>
												<td id="vatAmount">{{ number_format($vat, 2) }}</td>
												<td></td>
											</tr>
											<tr>
												<td colspan="4" class="text-right font-weight-bold align-middle">Grand Total:</td>
												<td id="grandTotal" class="align-middle">{{ number_format($grand, 2) }}</td>
												<td class="align-middle">
													<button type="button" class="btn btn-info btn-xs" id="paymentBtn">Payment</button>
												</td>
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

<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="depositForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Deposit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Deposit Amount</label>
                        <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Deposit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="paymentForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Make Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date <strong>*</strong></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Type <strong>*</strong></label>
						<select name="payment_type" class="form-control">
							<option value="">Payment Type</option>
							<option value="BACS">BACS</option>
							<option value="CASH">CASH</option>
							<option value="Credit Card">Credit Card</option>
							<option value="Replacement - Not Paid">Replacement - Not Paid</option>
						</select>
                    </div>
					<div class="form-group">
                        <label>Reference <strong>*</strong></label>
                        <input type="text" name="reference" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Payment</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="changeQtyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white p-2">
                <h6 class="modal-title">Change Quantity</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="changeQtyForm">
                    <input type="hidden" name="item_id" id="modalItemId">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <div class="form-group mb-2">
                        <label for="modalQty">Quantity</label>
                        <input type="number" class="form-control form-control-sm" id="modalQty" name="quantity" min="1">
                    </div>
                    <button type="submit" class="btn btn-info btn-sm btn-block">Change Quantity</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
										data-product="${p.id}"
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
			let product = $(this).data('product');
			let code = $(this).data('code');
			let desc = $(this).data('desc');
			let price = parseFloat($(this).data('price'));
			let qty = parseInt($(this).closest('.quantity-form').find('.qty-input').val()) || 1;
			let orderId = "{{ $data->id }}";

			$.ajax({
				url: '/orders/' + orderId + '/items',
				method: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					product: product,
					code: code,
					description: desc,
					price: price,
					qty: qty
				},
				success: function(response) {
					if (response.success) {
						let item = response.item;
						let existingRow = $('#orderTable tbody tr[data-id="'+ item.product_id +'"]');
						if (existingRow.length > 0) {
							existingRow.find('.item-qty').text(item.quantity);
							existingRow.find('.item-total').text(parseFloat(item.total).toFixed(2));
							existingRow.addClass('table-warning');
							setTimeout(() => {
								existingRow.removeClass('table-warning');
							}, 800);
						} else {
							let row = $(`
								<tr data-code="${item.product_code}" data-id="${item.product_id}">
									<td class="align-middle">${item.product_code}</td>
									<td class="align-middle">${item.description}</td>
									<td class="align-middle">${parseFloat(item.price).toFixed(2)}</td>
									<td class="align-middle item-qty">${item.quantity}</td>
									<td class="align-middle item-total">${item.total.toFixed(2)}</td>
									<td class="align-middle">
										<button type="button" class="btn btn-danger shadow btn-xs sharp remove-item" data-id="${item.id}"><i class="glyph-icon simple-icon-trash"></i></button>
									</td>
								</tr>
							`);
							$('#orderTable tbody').append(row);
							row.fadeIn(300);
						}
						updateTotals();
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
						updateTotals();
					});
				}
			});
		});

		$(document).on('click', '.quantity-form', function(e){
			e.stopPropagation();
		});

		$(document).on('click', '#depositBtn', function(){
			$('#depositModal').modal('show');
		});

		$('#depositForm').on('submit', function(e){
			e.preventDefault();
			let orderId = "{{ $data->id }}";

			$.ajax({
				url: '/orders/' + orderId + '/deposit',
				method: 'POST',
				data: $(this).serialize(),
				success: function(response){
					if(response.success){
						let deposit = response.deposit;
						let row = `
							<tr class="deposit-row" style="display:none;" data-id="${deposit.id}">
								<td colspan="4" class="text-right font-weight-bold align-middle">
									<div class="d-flex justify-content-end align-items-center">
										Deposit <span class="badge badge-dark ml-2">${deposit.id}</span>
										<input type="text" value="${deposit.description ?? ''}" 
											class="form-control form-control-sm ml-2 deposit-desc" 
											style="width:200px; height:30px;">
									</div>
								</td>
								<td class="text-success font-weight-bold align-middle deposit-amount" data-amount="${parseFloat(deposit.amount).toFixed(2)}">-${parseFloat(deposit.amount).toFixed(2)}</td>
								<td class="align-middle">
									<button type="button" class="btn btn-danger shadow btn-xs sharp remove-deposit" data-id="${deposit.id}">
										<i class="glyph-icon simple-icon-trash"></i>
									</button>
								</td>
							</tr>
						`;
						if ($('#orderTable tbody .deposit-row').length > 0) {
							$(row).insertAfter('#orderTable tfoot .deposit-row:last').hide().fadeIn();
						} else {
							$(row).insertBefore('#orderTable tfoot tr:first').hide().fadeIn();
						}
						updateTotals();
						$('#depositModal').modal('hide');
						$('#depositForm')[0].reset();
					}
				}
			});
		});

		$(document).on('click', '#paymentBtn', function(){
			$('#paymentModal').modal('show');
		});

		$(document).on('click', '.edit-qty', function() {
			let productId = $(this).data('product');
			let itemId = $(this).data('id');
			let qty = $(this).data('qty');
			$('#modalItemId').val(itemId);
			$('#modalProductId').val(productId);
			$('#modalQty').val(qty);
			$('#changeQtyModal').modal('show');
		});

		$('#changeQtyForm').on('submit', function(e) {
			e.preventDefault();
			let orderId = "{{ $data->id }}";
			let itemId = $('#modalItemId').val();
			let productId = $('#modalProductId').val();
			let newQty = parseInt($('#modalQty').val());
			$.ajax({
				url: '/orders/' + orderId + '/items/' + itemId + '/update-qty',
				method: 'POST',
				data: {
					_token: '{{ csrf_token() }}',
					qty: newQty
				},
				success: function(response) {
					if(response.success) {
						let updated = response.item;
						let row = $('#orderTable tbody tr[data-id="'+ productId +'"]');
						row.find('.qty-text').text(updated.quantity);
						row.find('.edit-qty').data('qty', updated.quantity);
						row.find('.item-total').text(
							parseFloat(updated.total).toLocaleString('en-US', {
								minimumFractionDigits: 2, maximumFractionDigits: 2
							})
						);
						$('#changeQtyModal').modal('hide');
						updateTotals();
					}
				}
			});
		});

		$(document).on('click', '.btn-table .btn-light', function() {
			let $btn = $(this);
			$btn.toggleClass('active');

			if ($btn.hasClass('active')) {
				$('#orderTable tbody tr').each(function() {
					let $actionCell = $(this).find('td:last .d-flex');
					if ($actionCell.find('.delivery-checkbox').length === 0) {
						let checkbox = `
							<div class="input-group delivery-checkbox" style="max-width:50px;margin-left: 10px;">
								<div class="input-group-text" style="padding: 0 8px;">
									<input type="checkbox" class="delivery-toggle" aria-label="Delivery label toggle">
								</div>
							</div>`;
						$actionCell.append(checkbox);
					}
				});
				if ($('#createLabelRow').length === 0) {
					let createLabelRow = `
						<tr id="createLabelRow">
							<td colspan="6" class="text-right">
								<button type="button" class="btn btn-success btn-xs" id="createLabelBtn">
									Create Label
								</button>
							</td>
						</tr>`;
					$('#orderTable tfoot').append(createLabelRow);
				}
			} else {
				$('#orderTable tbody .delivery-checkbox').remove();
				$('#createLabelRow').remove();
			}
		});

		$(document).on('click', '#createLabelBtn', function() {
			let selectedItems = [];
			$('#orderTable tbody .delivery-toggle:checked').each(function() {
				let $row = $(this).closest('tr');
				let itemId = $row.data('id'); // assuming each row has data-id (product_id or order_item_id)
				let code = $row.data('code'); // product_code
				selectedItems.push({ id: itemId, code: code });
			});
			if (selectedItems.length === 0) {
				alert("Please select at least one item to create a label.");
				return;
			}
			$.ajax({
				url: "/orders/create-label",
				method: "POST",
				data: {
					_token: $('meta[name="csrf-token"]').attr('content'),
					items: selectedItems
				},
				xhrFields: {
					responseType: 'blob'
				},
				success: function(response) {
					let blob = new Blob([response], { type: 'application/pdf' });
					let url = window.URL.createObjectURL(blob);
					window.open(url, '_blank');
				},
				error: function(err) {
					console.error(err);
					alert("Something went wrong while generating the label.");
				}
			});
		});

		$(document).on('change', '.delivery-toggle', function() {
			let row = $(this).closest('tr');
			if ($(this).is(':checked')) {
				row.addClass('table-success');
			} else {
				row.removeClass('table-success');
			}
		});

	});

	$(document).on('click', '.remove-deposit', function(){
		let row = $(this).closest('tr');
		let depositId = $(this).data('id');
		let orderId = "{{ $data->id }}";

		if(!confirm("Are you sure you want to remove this deposit?")) return;

		$.ajax({
			url: '/orders/' + orderId + '/deposit/' + depositId,
			method: 'DELETE',
			data: { _token: '{{ csrf_token() }}' },
			success: function(response){
				if(response.success){
					row.fadeOut(300, function() {
						$(this).remove();
						updateTotals();
					});
				}
			}
		});
	});

	function updateTotals() {
		let subtotal = 0;
		$('#orderTable tbody tr[data-code]').each(function() {
			subtotal += parseFloat($(this).find('.item-total').text()) || 0;
		});
		let deposits = 0;
		$('#orderTable tfoot tr.deposit-row').each(function() {
			deposits += parseFloat($(this).find('.deposit-amount').data('amount')) || 0;
		});
		let netTotal = subtotal - deposits;
		let vat = netTotal * 0.20;
		let grandTotal = netTotal + vat;
		$('#totalAmount').text(formatCurrency(netTotal.toFixed(2)));
		$('#vatAmount').text(formatCurrency(vat.toFixed(2)));
		$('#grandTotal').text(formatCurrency(grandTotal.toFixed(2)));
	}


	function formatCurrency(value) {
		return parseFloat(value).toLocaleString('en-US', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	}

</script>
@endpush