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
            <tr class="route-row">
                <td class="align-middle">
                    @if($route->orders && $route->orders->count() > 0)
                        <a href="javascript:;" class="toggle-orders" data-id="{{ $route->id }}">
                            <i class="glyph-icon simple-icon-arrow-down"></i>
                        </a>
                    @endif
                </td>
                <td class="align-middle">{{ $route->name }}</td>
                <td class="align-middle">{{ $route->start_date }} - {{ $route->start_time->format('h:i A') }}</td>
                <td class="align-middle">({{ $route->start_location }} → {{ $route->end_location }})</td>
                <td class="align-middle">
                    <div class="d-flex justify-content-end">
                        <a href="javascript:;" data-id="{{ $route->id }}" data-name="{{ $route->name }}" class="btn btn-info shadow btn-xs sharp mr-2 open-route-order" title="Add Order"><i class="glyph-icon iconsminds-add"></i></a>
                        @can('edit routes')
                        <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-primary shadow btn-xs sharp me-1 mr-2"><i class="glyph-icon iconsminds-file-edit"></i></a>
                        @endcan
                        @can('delete routes')
                        <form id="deleteRouteForm-{{ $route->id }}" action="{{ route('routes.destroy', $route->id) }}" method="post" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger shadow btn-xs sharp confirm-delete-route" data-id="{{ $route->id }}">
                                <i class="glyph-icon simple-icon-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @if($route->orders && $route->orders->count() > 0)
            <tr class="orders-row orders-{{ $route->id }}">
                <td colspan="5" class="p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="border: 0;"></th>
                                <th class="pl-0">Order ID</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($route->orders as $orders)
                            <tr>
                                <td style="border: 0;"></td>
                                <td class="pl-0">{{ $orders->id }}</td>
                                <td>{{ $orders->get_customer->name }}</td>
                                <td>{{ $orders->address }}</td>
                                <td class="text-right">
                                    <button type="button"
                                            class="btn btn-danger shadow btn-xs sharp confirm-delete-btn"
                                            data-id="{{ $orders->id }}">
                                        <i class="glyph-icon simple-icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background-color: #e5e5e5;">
                            <tr>
                                <th colspan="5"></th>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
            @endif
            @endforeach
        </table>
        @endif
    </div>
</div>
@endforeach
<div class="modal fade bd-example-modal-lg" id="routeOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Order to Route</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignOrderForm">
                    <input type="hidden" name="route_id" id="route_id">

                    <div class="form-group">
                        <label for="order_id">Select Order</label>
                        <select class="form-control select2-single" name="order_id" id="order_id" required>
                            <option value="">Loading orders...</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Assign</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this order from the route?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Remove</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmRouteDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Route Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this route?  
                <br><small class="text-danger">All assigned orders will be unassigned.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteRouteBtn">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(document).on('click', '.open-route-order', function () {
        let routeId = $(this).data('id');
        let routeName = $(this).data('name');

        $('#route_id').val(routeId);
        $('#routeOrderModal .modal-title').text("Assign Order to " + routeName);

        // Reset dropdown
        $('#order_id').html('<option value="">Loading orders...</option>');

        // Fetch orders not assigned yet
        $.get("{{ route('routes.unassignedOrders') }}", { route_id: routeId }, function (res) {
            if (res.success) {
                let options = '<option value="">-- Select Order --</option>';
                res.orders.forEach(function (order) {
                    options += `<option value="${order.id}">${order.id} - ${order.get_customer.name} - ${order.address} - ${order.grand_total}</option>`;
                });
                $('#order_id').html(options);
            } else {
                $('#order_id').html('<option value="">No available orders</option>');
            }
        });

        $('#routeOrderModal').modal('show');
    });

    $(document).on('submit', '#assignOrderForm', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('routes.assignOrder') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    let order = res.order;
                    let $ordersRow = $(".orders-row.orders-" + order.route_id);
                    let newRow = `
                        <tr>
                            <td style="border: 0;"></td>
                            <td class="pl-0">${order.id}</td>
                            <td>${order.get_customer.name ?? ''}</td>
                            <td>${order.address ?? ''}</td>
                            <td class="text-right">
                                <button type="button"
                                        class="btn btn-danger shadow btn-xs sharp confirm-delete-btn"
                                        data-id="${order.id}">
                                    <i class="glyph-icon simple-icon-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    if ($ordersRow.length) {
                        $ordersRow.find("tbody").append(newRow).show();
                    } else {
                        let ordersTable = `
                            <tr class="orders-row orders-${order.route_id}" style="display:none;">
                                <td colspan="5" class="p-0">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th style="border: 0;"></th>
                                                <th class="pl-0">Order ID</th>
                                                <th>Customer</th>
                                                <th>Address</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${newRow}
                                        </tbody>
                                        <tfoot style="background-color: #e5e5e5;">
                                            <tr><th colspan="5"></th></tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        `;

                        let $routeRow = $(`[data-id="${order.route_id}"]`).closest("tr");
                        $routeRow.after(ordersTable);
                        if ($routeRow.find(".toggle-orders").length === 0) {
                            $routeRow.find("td:first").html(`
                                <a href="javascript:;" class="toggle-orders" data-id="${order.route_id}">
                                    <i class="glyph-icon simple-icon-arrow-down"></i>
                                </a>
                            `);
                        }
                        $(".orders-row.orders-" + order.route_id).fadeIn();
                    }

                    $('#routeOrderModal').modal('hide');
                } else {

                }
            }
        });
    });

    
    $(document).on('click', '.toggle-orders', function () {
        let routeId = $(this).data('id');
        let $icon = $(this).find('i');
        let $row = $('.orders-' + routeId);
        let $parentRow = $(this).closest('.route-row');

        $row.toggle();

        if ($row.is(':visible')) {
            $icon.removeClass('simple-icon-arrow-down').addClass('simple-icon-arrow-up');
            $parentRow.addClass('active-row');
        } else {
            $icon.removeClass('simple-icon-arrow-up').addClass('simple-icon-arrow-down');
            $parentRow.removeClass('active-row');
        }
    });

    let orderIdToDelete = null;
    let $rowToDelete = null;

    $(document).on('click', '.confirm-delete-btn', function () {
        orderIdToDelete = $(this).data('id');
        $rowToDelete = $(this).closest('tr');
        $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function () {
        if (orderIdToDelete) {
            $.ajax({
                url: "{{ route('routes.removeOrder') }}", // backend route
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    order_id: orderIdToDelete
                },
                success: function (res) {
                    if (res.success) {
                        let $tbody = $rowToDelete.closest('tbody');
                        let $table = $rowToDelete.closest('table');
                        $rowToDelete.fadeOut(300, function () {
                            $(this).remove();
                            if ($tbody.find('tr').length === 0) {
                                $table.find('thead').remove();
                                $table.closest('.orders-row').remove();
                                let routeId = $table.closest('.orders-row').attr('class').match(/orders-(\d+)/)[1];
                                $(`.toggle-orders[data-id="${routeId}"]`).remove();
                            }
                        });
                        $('#confirmDeleteModal').modal('hide');
                    } else {
                        
                    }
                }
            });
        }
    });

    let routeIdToDelete = null;

    $(document).on('click', '.confirm-delete-route', function () {
        routeIdToDelete = $(this).data('id');
        $('#confirmRouteDeleteModal').modal('show');
    });

    $('#confirmDeleteRouteBtn').on('click', function () {
        if (routeIdToDelete) {
            $('#deleteRouteForm-' + routeIdToDelete).submit();
        }
    });



</script>
@endpush