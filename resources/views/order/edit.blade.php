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
					<div class="d-flex justify-content-between">
						<h5 class="card-title mb-3" id="dynamic-heading">Sections</h5>
						<input type="text" class="form-control search-global" placeholder="Search Sections">
					</div>
					<div id="breadcrumb" class="mb-2">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item">Home</li>
							</ol>
						</nav>
					</div>
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
												<th colspan="2">Code</th>
												<th>Description</th>
												<th>Price</th>
												<th>QTY</th>
												<th>Total</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach($data->items as $item)
											<tr data-code="{{ $item->product_code }}"
												data-id="{{ $item->product_id }}"
												class="@if($item->fabric_name || $item->drawer_name) has-children-row @endif">
												<td class="align-middle pr-0">
													@if($item->fabric_name || $item->drawer_name)
													<button type="button" class="btn btn-xs btn-link text-primary toggle-children p-0" data-id="{{ $item->id }}">
														<i class="glyph-icon simple-icon-arrow-down"></i>
													</button>
													@endif
												</td>
												<td class="align-middle">{{ $item->product_code }}</td>
												<td class="align-middle item-desc" contenteditable="true" data-id="{{ $item->id }}">
													{{ $item->description }}
												</td>
												<td class="align-middle item-price" contenteditable="true" data-id="{{ $item->id }}">
													{{ number_format($item->price, 2) }}
												</td>
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
											@if($item->fabric_name)
											<tr class="fabric-row child-row" data-parent="{{ $item->id }}">
												<td class="align-middle" colspan="2"></td>
												<td class="align-middle text-right">{{ $item->fabric_name }}</td>
												<td class="align-middle">
													{{ number_format($item->fabric_price, 2) }}
												</td>
												<td class="align-middle">—</td>
												<td class="align-middle">—</td>
												<td class="align-middle">
													<div class="d-flex">
														<button type="button" class="btn btn-danger shadow btn-xs sharp remove-fabric" data-id="{{ $item->id }}">
															<i class="glyph-icon simple-icon-trash"></i>
														</button>
													</div>
												</td>
											</tr>
											@endif
											@if($item->drawer_name)
											<tr class="drawer-row child-row" data-parent="{{ $item->id }}">
												<td class="align-middle" colspan="2"></td>
												<td class="align-middle text-right">{{ $item->drawer_name }}</td>
												<td class="align-middle">
													{{ number_format($item->drawer_price, 2) }}
												</td>
												<td class="align-middle">—</td>
												<td class="align-middle">—</td>
												<td class="align-middle">
													<div class="d-flex">
														<button type="button" class="btn btn-danger shadow btn-xs sharp remove-drawer" data-id="{{ $item->id }}">
															<i class="glyph-icon simple-icon-trash"></i>
														</button>
													</div>
												</td>
											</tr>
											@endif
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
			updateBreadcrumb([section]);
			let url = "{{ route('product.ranges', ':section') }}".replace(':section', section);
			historyStack.push({
				html: $('#drilldownContent').html(),
				heading: $('#dynamic-heading').text(),
				placeholder: $('.search-global').attr('Search Ranges')
			});
			$('#backBtn').removeClass('d-none');
			showLoader();
			$.get(url, function(data) {
				let html = '<h6>Ranges in <b>'+section+'</b></h6><ul class="product-section">';
				data.forEach(function(range){
					html += '<li><a class="btn btn-warning range-btn" href="javascript:;" data-section="'+section+'" data-range="'+encodeURIComponent(range)+'">'+range+'</a></li>';
				});
				html += '</ul>';
				$('#drilldownContent').html(html);
				$('#dynamic-heading').text('Ranges');
        		$('.search-global').attr('placeholder', 'Search Ranges');
				$('.search-global').val('');
			}).always(function(){
				hideLoader();
			});
		});

		$(document).on('click', '.type-btn', function(){
			let section = $(this).data('section');
			let type = $(this).data('type');
			historyStack.push($('#drilldownContent').html());
			showLoader();
			let url = "{{ route('product.ranges', [':section', ':type']) }}"
			.replace(':section', section)
			.replace(':type', type);
			
			$.get(url, function(data){
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
			let range   = decodeURIComponent($(this).data('range'));
			updateBreadcrumb([section, range]);
			historyStack.push({
				html: $('#drilldownContent').html(),
				heading: $('#dynamic-heading').text(),
				placeholder: $('.search-global').attr('placeholder')
			});
			showLoader();
			let url = "{{ route('product.list', [':section', ':range']) }}"
				.replace(':section', section)
				.replace(':range', range);
			$.get(url, function(data){
				let html = '<h6>Products in <b>'+range+'</b></h6><ul class="product-section product-list">';
				data.forEach(function(p){
					let codeHtml = p.product_code ? `<h6>${p.product_code}</h6>` : '';
					let descHtml = p.description ? `<p>${p.description}</p>` : '';
					let priceHtml = p.sale_price ? `<h5>${p.sale_price}</h5>` : '';
					html += `
					<li>
						<a href="javascript:;" class="btn btn-info product-btn" data-range="${p.product_range}" data-section="${p.product_section}" data-production="${p.production_type}">
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
				$('#dynamic-heading').text('Products');
        		$('.search-global').attr('placeholder', 'Search Products');
				$('.search-global').val('');
			}).always(function(){
				hideLoader();
			});
		});

		$('#backBtn').on('click', function(){
			$('.search-global').val('');
			if(historyStack.length > 0){
				let prev = historyStack.pop();
				$('#drilldownContent').html(prev.html);
				$('#dynamic-heading').text(prev.heading);
				$('.search-global').attr('placeholder', prev.placeholder);
			}
			if(historyStack.length === 0){
				$('#backBtn').addClass('d-none');
			}
			$(this).closest('.drill-wrapper').find('.selected-product').remove();
    		cachedProduct = null;
		});

		const fabricRanges = ["base", "bed frame", "headboard", "headboard design", "ottoman"];
		let cachedProduct = null;

		$(document).on('click', '.product-btn', function(){
			let section = $(this).data('section');
			let range   = decodeURIComponent($(this).data('range')).toLowerCase();
			let productId = $(this).find('.add-to-order').data('product');
			// let product = $(this).data('id');
			let production = ($(this).data('production') || "").toLowerCase();
			let desc = $(this).find('.add-to-order').data('desc');
    		let code = $(this).find('.add-to-order').data('code');
			let price = $(this).find('.add-to-order').data('price');
			updateBreadcrumb([section, range, desc]);
			let productTitle = `${code} - ${desc}`;
			let headerHtml = `
				<div class="selected-product">
					<div class="text-success alert alert-success heading"><b>${productTitle} - ${price}</b></div>
				</div>
			`;
			if (fabricRanges.includes(production)) {
				cachedProduct = {
					productId: productId,
					code: $(this).find('.add-to-order').data('code'),
					desc: $(this).find('.add-to-order').data('desc'),
					price: $(this).find('.add-to-order').data('price')
				};
				$('.drill-wrapper').prepend(headerHtml);
				loadFabrics(section, range, productId, production);
			} else {
				$(this).find('.quantity-form').removeClass('d-none');
			}
		});


		$(document).on('click', '.fabric-btn', function () {
			let $btn   = $(this);
			let fabric = decodeURIComponent($btn.data('fabric'));
			let price  = parseFloat($btn.data('price')) || 0;
			let fabricList = $btn.closest('.fabric-list');
			if ($btn.hasClass('active')) {
				$btn.removeClass('active');
				if (cachedProduct) {
					delete cachedProduct.fabric;
					delete cachedProduct.fabricPrice;
				}
				$('.selected-fabric-info').remove();
				let placeholder = `
					<div class="selected-fabric-info">
						<div class="alert alert-info text-info mb-0 heading">
							<b>Select Fabric</b>
						</div>
					</div>
				`;
				$('.selected-product .heading').after(placeholder);
				fabricList.show();
				$('.fabric-qty-form').remove();
				return;
			}
			$('.fabric-btn').removeClass('active');
			$btn.addClass('active');
			if (cachedProduct) {
				cachedProduct.fabric = fabric;
				cachedProduct.fabricPrice = price;
			}
			let fabricInfo = `
				<div class="selected-fabric-info alert alert-success">
					<div class="text-success mb-0 heading">
						<b>Selected Fabric<br>${fabric}<br>${price.toFixed(2)}</b>
					</div>
					<div class="fabric-search">
						<button type="button" class="btn btn-danger shadow btn-xs sharp remove-fabric-selection mb-1 ml-0 pl-1 pr-1 remove-icon">
							<i class="glyph-icon simple-icon-trash"></i>
						</button>
						<input type="text" class="form-control fabric-search-input" placeholder="Search fabrics...">
					</div>
				</div>
			`;
			$('.selected-fabric-info').remove();
			$('.selected-product .heading').after(fabricInfo);
			fabricList.hide();
			let qtyHtml = `
				<div class="fabric-qty-form mt-3">
					<input type="number" class="form-control form-control-sm fabric-qty mb-2" value="1" min="1">
					<button type="button" class="btn btn-sm btn-success add-fabric-to-order">
						Add to Order
					</button>
				</div>
			`;
			$('.fabric-qty-form').remove();
			$('#drilldownContent').after(qtyHtml);
		});

		$(document).on('click', '.remove-fabric-selection', function () {
			if (cachedProduct) {
				delete cachedProduct.fabric;
				delete cachedProduct.fabricPrice;
			}
			$('.fabric-btn').removeClass('active');
			$('.selected-fabric-info').remove();
			let placeholder = `
				<div class="selected-fabric-info alert alert-info text-info">
					<div class="mb-0 heading">
						<b>Select Fabric</b>
					</div>
					<div class="fabric-search">
						<input type="text" class="form-control fabric-search-input" placeholder="Search fabrics...">
					</div>
				</div>
			`;
			$('.selected-product .heading').after(placeholder);
			$('.fabric-list').show();
			$('.fabric-qty-form').remove();
		});

		$(document).on('click', '.remove-drawer-selection', function () {
			if (cachedProduct) {
				delete cachedProduct.drawer;
				delete cachedProduct.drawerPrice;
			}
			$('.drawer-btn').removeClass('active');
			$('.selected-drawer-info').remove();
			let placeholder = `
				<div class="selected-drawer-info alert alert-info text-info">
					<div class="mb-0 heading">
						<b>Select Drawers</b>
					</div>
					<div class="drawer-search">
						<input type="text" class="form-control drawer-search-input" placeholder="Search drawers...">
					</div>
				</div>
			`;
			$('.selected-product .heading').after(placeholder);
			$('.drawer-list').show();
		});


		$(document).on('click', '.drawer-btn', function () {
			let $btn   = $(this);
			let drawer = decodeURIComponent($btn.data('drawer'));
			let price  = parseFloat($btn.data('price')) || 0;
			let drawerList = $btn.closest('.drawer-list');

			if ($btn.hasClass('active')) {
				$btn.removeClass('active');
				if (cachedProduct) {
					delete cachedProduct.drawer;
					delete cachedProduct.drawerPrice;
				}
				$('.selected-drawer-info').remove();
				let placeholder = `
					<div class="selected-drawer-info alert alert-info text-info">
						<div class="mb-0 heading">
							<b>Select Drawers</b>
						</div>
						<div class="drawer-search">
							<input type="text" class="form-control drawer-search-input" placeholder="Search Drawers...">
						</div>
					</div>
				`;
				drawerList.before(placeholder);
				drawerList.show();
				return;
			}
			$('.drawer-btn').removeClass('active');
			$btn.addClass('active');
			if (cachedProduct) {
				cachedProduct.drawer = drawer;
				cachedProduct.drawerPrice = price;
			}
			let drawerInfo = `
				<div class="selected-drawer-info alert alert-success text-success">
					<div class="mb-0 heading">
						<b>Selected Drawer<br>${drawer}<br>${price.toFixed(2)}</b>
					</div>
					<div class="drawer-search">
						<button type="button" class="btn btn-danger shadow btn-xs sharp remove-drawer-selection ml-0 mb-1 pl-1 pr-1 remove-icon">
							<i class="glyph-icon simple-icon-trash"></i>
						</button>
						<input type="text" class="form-control drawer-search-input" placeholder="Search Drawers...">
					</div>
				</div>
			`;
			$('.selected-drawer-info').remove();
			drawerList.before(drawerInfo);
			drawerList.hide();
		});



		$(document).on('click', '.selected-fabric-info', function () {
			$('.fabric-list').toggle();
		});

		$(document).on('focus click', '.fabric-search-input', function () {
			$('.fabric-list').show();
		});

		$(document).on('focus click', '.drawer-search-input', function () {
			$('.drawer-list').show();
		});

		$(document).on('click', '.selected-drawer-info', function () {
			$('.drawer-list').toggle();
		});

		$(document).on('click', '.add-fabric-to-order', function () {
			let fabric = $(this).data('fabric');
			let qty = parseInt($(this).closest('.fabric-qty-form').find('.fabric-qty').val()) || 1;
			let orderId = "{{ $data->id }}";

			if (!cachedProduct) {
				alert("No product cached.");
				return;
			}

			let basePrice   = parseFloat(cachedProduct.price) || 0;
			let fabricPrice = parseFloat(cachedProduct.fabricPrice) || 0;
			let drawerPrice = parseFloat(cachedProduct.drawerPrice) || 0;
			let unitPrice   = basePrice + fabricPrice + drawerPrice;
			let totalPrice  = unitPrice * qty;

			$.ajax({
				url: "{{ route('orders.addItemFabric', ['order' => $data->id]) }}",
				method: "POST",
				data: {
					_token: $('meta[name="csrf-token"]').attr('content'),
					product_id: cachedProduct.productId,
					order_id: orderId,
					product_code: cachedProduct.code,
					description: cachedProduct.desc,
					price: basePrice,
					quantity: qty,
					total: totalPrice,
					fabric_name: cachedProduct.fabric || null,
					fabric_price: fabricPrice,
					drawer_name: cachedProduct.drawer || null,
					drawer_price: drawerPrice
				},
				success: function (res) {
					if (res.success) {
						let item = res.item;
						console.log(item);
						let hasChildren = item.fabric_name || item.drawer_name ? 'has-children-row' : '';
						let rowHtml = `
						<tr data-code="${item.product_code}"
							data-id="${item.product_id}"
							class="${hasChildren}">
							<td class="align-middle pr-0">
								${(item.fabric_name || item.drawer_name) ? `
									<button type="button" class="btn btn-xs btn-link text-primary toggle-children p-0" data-id="${item.id}">
										<i class="glyph-icon simple-icon-arrow-down"></i>
									</button>` : ``}
							</td>
							<td class="align-middle">${item.product_code}</td>
							<td class="align-middle item-desc" contenteditable="true" data-id="${item.id}">
								${item.description}
							</td>
							<td class="align-middle item-price" contenteditable="true" data-id="${item.id}">
								${parseFloat(item.price).toFixed(2)}
							</td>
							<td class="align-middle item-qty">
								<span class="qty-text">${item.quantity}</span>
								<button type="button" class="btn btn-xs btn-link text-primary edit-qty" 
										data-id="${item.id}" 
										data-product="${item.product_id}" 
										data-qty="${item.quantity}">
									<i class="glyph-icon simple-icon-pencil"></i>
								</button>
							</td>
							<td class="align-middle item-total">${parseFloat(item.total).toFixed(2)}</td>
							<td class="align-middle">
								<div class="d-flex">
									<button type="button" class="btn btn-danger shadow btn-xs sharp remove-item" data-id="${item.id}">
										<i class="glyph-icon simple-icon-trash"></i>
									</button>
								</div>
							</td>
						</tr>`;
						if (item.fabric_name) {
							rowHtml += `
							<tr class="fabric-row child-row" data-parent="${item.id}">
								<td class="align-middle" colspan="2"></td>
								<td class="align-middle text-right">${item.fabric_name}</td>
								<td class="align-middle">${parseFloat(item.fabric_price).toFixed(2)}</td>
								<td class="align-middle">—</td>
								<td class="align-middle">—</td>
								<td class="align-middle">
									<div class="d-flex">
										<button type="button" class="btn btn-danger shadow btn-xs sharp remove-fabric" data-id="${item.id}">
											<i class="glyph-icon simple-icon-trash"></i>
										</button>
									</div>
								</td>
							</tr>`;
						}
						if (item.drawer_name) {
							rowHtml += `
							<tr class="drawer-row child-row" data-parent="${item.id}">
								<td class="align-middle" colspan="2"></td>
								<td class="align-middle text-right">${item.drawer_name}</td>
								<td class="align-middle">${parseFloat(item.drawer_price).toFixed(2)}</td>
								<td class="align-middle">—</td>
								<td class="align-middle">—</td>
								<td class="align-middle">
									<div class="d-flex">
										<button type="button" class="btn btn-danger shadow btn-xs sharp remove-drawer" data-id="${item.id}">
											<i class="glyph-icon simple-icon-trash"></i>
										</button>
									</div>
								</td>
							</tr>`;
						}
						$("#orderTable tbody").append(rowHtml);
						cachedProduct = null;
					}
					updateTotals();
				}
			});
		});

		$(document).on('click', '.add-to-order', function(){
			let product = $(this).data('product');
			let code = $(this).data('code');
			let desc = $(this).data('desc');
			let price = parseFloat($(this).data('price'));
			let qty = parseInt($(this).closest('.quantity-form').find('.qty-input').val()) || 1;
			let orderId = "{{ $data->id }}";
			$.ajax({
				url: "{{ route('orders.addItem', ':order') }}".replace(':order', orderId),
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
										<div class="d-flex">
											<button type="button" class="btn btn-danger shadow btn-xs sharp remove-item" data-id="${item.id}"><i class="glyph-icon simple-icon-trash"></i></button>
										</div>
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
				url: "{{ route('orders.removeItem', [':order', ':item']) }}"
				.replace(':order', orderId)
				.replace(':item', itemId),
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
				url: "{{ route('orders.deposit', ':order') }}".replace(':order', orderId),
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
											class="form-control ml-2 deposit-desc" 
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
				url: "{{ route('orders.items.updateQty', [':order', ':item']) }}"
				.replace(':order', orderId)
				.replace(':item', itemId),
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
				url: "{{ route('orders.createLabel') }}",
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

		$(document).on('blur', '.item-desc', function () {
			let itemId = $(this).data('id');
			let newDesc = $(this).text().trim(); // get edited text
			let orderId = "{{ $data->id }}";

			$.ajax({
				url: "{{ route('orders.updateItemDesc', ['order' => $data->id, 'item' => 'ITEM_ID']) }}"
					.replace('ITEM_ID', itemId),
				method: 'PUT',
				data: {
					_token: '{{ csrf_token() }}',
					description: newDesc
				},
				success: function (response) {
					
				},
				error: function () {
					toastr.error("Error while updating description.");
				}
			});
		});

		$(document).on('blur', '.item-price', function () {
			let itemId = $(this).data('id');
			let newPrice = parseFloat($(this).text().trim()) || 0;
			let orderId = "{{ $data->id }}";
			let $row = $(this).closest('tr');

			$.ajax({
				url: "{{ route('orders.updateItemPrice', ['order' => $data->id, 'item' => 'ITEM_ID']) }}"
					.replace('ITEM_ID', itemId),
				method: 'PUT',
				data: {
					_token: '{{ csrf_token() }}',
					price: newPrice
				},
				success: function (response) {
					if (response.success) {
						let item = response.item;
						let price = parseFloat(item.price) || 0;
						let total = parseFloat(item.total) || 0;
						$row.find('.item-price').text(price.toFixed(2));
						$row.find('.item-total').text(total.toFixed(2));
						$row.addClass('table-warning');
						setTimeout(() => $row.removeClass('table-warning'), 800);
						updateTotals();
					}
				},
				error: function () {
					alert("Error while updating price.");
				}
			});
		});

		$(document).on('keypress', '.item-desc, .item-price', function (e) {
			if (e.which === 13) {
				e.preventDefault();
				$(this).blur();
			}
		});

		function loadFabrics(section, range, productId, production) {
			showLoader();
			let url = "{{ route('product.fabrics', [':section', ':range', ':product']) }}"
				.replace(':section', encodeURIComponent(section))
				.replace(':range', encodeURIComponent(range))
				.replace(':product', productId);

			$.get(url, function (data) {
				let html = `
					<div class="selected-fabric-info alert alert-info text-info">
						<div class="mb-0 heading">
							<b>Select Fabric</b>
						</div>
						<div class="fabric-search">
							<input type="text" class="form-control fabric-search-input" placeholder="Search fabrics...">
						</div>
					</div>
					<ul class="product-section fabric-list" style="display:none;">
				`;

				data.forEach(function (fabric) {
					let fabricLabel = $('<div>').text(fabric.description).html();
					let price = fabric.sale_price ? parseFloat(fabric.sale_price).toFixed(2) : '0.00';
					html += `
						<li>
							<div class="fabric-option">
								<a class="btn btn-info fabric-btn" href="javascript:;"
								data-section="${section}"
								data-range="${range}"
								data-product="${productId}"
								data-fabric="${encodeURIComponent(fabric.description)}"
								data-price="${price}"
								data-category="${fabric.product_section}">
									${fabricLabel} <br>
									<small>${fabric.product_section}</small>
									<span>${price}</span>
								</a>
							</div>
						</li>
					`;
				});

				html += '</ul>';
				if (production === "base") {
					loadDrawers(section, range, productId, html);
				} else {
					$('#drilldownContent').html(html);
				}

				$(document).off('input.fabricSearch').on('input.fabricSearch', '.fabric-search-input', function () {
					let query = $(this).val().toLowerCase();
					$(".fabric-list").show();
					$(".fabric-list li").each(function () {
						let text = $(this).text().toLowerCase();
						$(this).toggle(text.indexOf(query) !== -1);
					});
				});

				// $('#drilldownContent').html(html);
				// $('#dynamic-heading').text('Fabrics');
				// $('.search-global').attr('placeholder', 'Search Fabrics').val('');
			})
			.fail(function () {
				$('#drilldownContent').html('<p class="text-danger">Failed to load fabrics.</p>');
			})
			.always(function () {
				hideLoader();
			});
		}

		function loadDrawers(section, range, productId, existingHtml = '') {
			let url = "{{ route('product.drawers', [':section', ':range', ':product']) }}"
				.replace(':section', section)
				.replace(':range', range)
				.replace(':product', productId);

			$.get(url, function(drawers){
				let productTitle = cachedProduct ? `${cachedProduct.code} - ${cachedProduct.desc}` : '';
				let html = existingHtml;
				html += `
					<div class="selected-drawer-info alert alert-info text-info">
						<div class="mb-0 heading"><b>Select Drawers</b></div>
						<div class="drawer-search">
							<input type="text" class="form-control drawer-search-input" placeholder="Search Drawers...">
						</div>
					</div>
				<ul class="product-section drawer-list" style="display:none">`;
				drawers.forEach(function (drawer) {
					let price = drawer.sale_price ? parseFloat(drawer.sale_price).toFixed(2) : '0.00';
					html += `
						<li>
							<a class="btn btn-info drawer-btn" href="javascript:;"
							data-price="${price}"
							data-product="${drawer.id}"
							data-drawer="${encodeURIComponent(drawer.description)}">
							${drawer.description}<br>
							<small>${drawer.product_section}</small>
							<span>${price}</span>
							</a>
						</li>`;
				});
				html += '</ul>';

				$('#drilldownContent').html(html);
			});
		}


	});

	$(document).off('input.drawer-search-input').on('input.fabricSearch', '.fabric-search-input', function () {
		let query = $(this).val().toLowerCase();
		$(".fabric-list").show();
		$(".fabric-list li").each(function () {
			let text = $(this).text().toLowerCase();
			$(this).toggle(text.indexOf(query) !== -1);
		});
	});
	

	$(document).on('keyup', '.drawer-search-input', function () {
		let query = $(this).val().toLowerCase();
		$(".drawer-list").show();
		$(".drawer-list li").each(function () {
			let text = $(this).text().toLowerCase();
			$(this).toggle(text.indexOf(query) !== -1);
		});
	});

	$(document).on('click', '.remove-deposit', function(){
		let row = $(this).closest('tr');
		let depositId = $(this).data('id');
		let orderId = "{{ $data->id }}";

		if(!confirm("Are you sure you want to remove this deposit?")) return;

		$.ajax({
			url: "{{ route('orders.deposit.remove', ['order' => ':order', 'deposit' => ':deposit']) }}"
			.replace(':order', orderId)
			.replace(':deposit', depositId),
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

	$(document).on('keyup', '.search-global', function(){
		let value = $(this).val().toLowerCase();
		$('.product-section li').filter(function(){
			$(this).toggle(
				$(this).text().toLowerCase().indexOf(value) > -1
			);
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

	function updateBreadcrumb(parts) {
		let html = '';
		parts.forEach((part, i) => {
			if (i === parts.length - 1) {
				html += `<li class="breadcrumb-item active" aria-current="page">${part}</li>`;
			} else {
				html += `<li class="breadcrumb-item">${part}</li>`;
			}
		});
		$('#breadcrumb .breadcrumb').html(html);
	}

	$(document).on('click', '.toggle-children', function () {
		let parentId = $(this).data('id');
		let $icon = $(this).find('i');
		console.log(parentId);
		console.log($(`.child-row[data-parent="${parentId}"]`));
		$(`.child-row[data-parent="${parentId}"]`).slideToggle('fast');
		if ($icon.hasClass('simple-icon-arrow-down')) {
			$icon.removeClass('simple-icon-arrow-down').addClass('simple-icon-arrow-up');
		} else {
			$icon.removeClass('simple-icon-arrow-up').addClass('simple-icon-arrow-down');
		}
	});

</script>
@endpush