@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Process</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Process</a></li>
                <li class="breadcrumb-item active" aria-current="page">Route {{ $route->name }} - {{ $route->start_date }} {{ $route->start_time }}</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">

	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="card mb-4">
    <div class="card-body p-3">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="production-task">
                @foreach($tasks as $task)
                    @role('production')
                        @if(in_array($task->id, $userTaskIds))
                        <span class="badge badge-info">
                            {{ $task->name }}
                        </span>
                        @endif
                    @endrole
                @endforeach
                </div>
            </div>
            <div class="col-lg-3">
                <form class="form-inline justify-content-end" method="get" action="{{ route('users.index') }}">
                    <label class="sr-only" for="inlineFormInputName2">Name</label>
                    <input type="text" name="name" class="form-control mb-0 mr-sm-2" id="inlineFormInputName2" placeholder="Name" value="{{ Request::get('name') }}">
                    <button type="submit" class="btn btn-sm btn-outline-primary mb-0">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($route->orders as $order)
<div class="row">
    @foreach($order->items as $product)
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="card-title">{{ $product->description }} -- {{ $product->product->production_type }}</h5>
                        <h5 class="font-weight-bold">{{ $product->quantity }}</h5>
                        <p class="text-muted">Reference:  {{ $order->order_reference }} | Order# {{ $order->id }}</p>
                        <h6 class="text-info">{{ $order->get_customer->name ?? '' }}</h6>
                    </div>
                    <div class="col-lg-4">
                        @if($product->fabric_name != null)
                        <h5>FABRIC: {{ $product->fabric_name }}</h5>
                        @endif
                        @if($product->drawer_name != null)
                        <h5>DRAWER: {{ $product->drawer_name }}</h5>
                        @endif

                    </div>
                </div>
                <table class="table table-bordered text-center mt-3">
                    <thead>
                        <tr class="bg-info text-white">
                            @php
                                $prodType = $product->product->production_type ?? null;
                                $productTasks = $tasks
                                    ->filter(function($task) use ($prodType) {
                                        return $task->productionTypes
                                            ->pluck('production_type')
                                            ->contains($prodType);
                                    })
                                    ->values();
                            @endphp
                            @foreach($productTasks as $task)
                                <th>{{ ucwords($task->name) }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                        @foreach($productTasks as $index => $task)
                        @php
                            $completed = $product->taskProgress
                                ->where('task_name_id', $task->id)
                                ->isNotEmpty();

                            // previous task completed?
                            $prevCompleted = $index === 0
                                ? true
                                : $product->taskProgress
                                    ->where('task_name_id', $productTasks[$index - 1]->id)
                                    ->isNotEmpty();

                            $disabled = ! $prevCompleted;
                        @endphp

                        <td class="{{ $completed ? 'alert alert-success' : '' }}" style="padding:15px;">
                            <input
                                type="checkbox"
                                class="task-checkbox"
                                data-task-id="{{ $task->id }}"
                                data-item-id="{{ $product->id }}"
                                {{ $completed ? 'checked data-locked=true disabled' : '' }}
                                {{ (!$completed && $disabled) ? 'disabled' : '' }}
                                style="width: 20px;height: 20px;"
                            >
                        </td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endforeach

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    $('.task-checkbox').each(function () {
        let row = $(this).closest('tr');
        let checkboxes = row.find('.task-checkbox');

        checkboxes.each(function (index) {
            if (index === 0) return;

            if (checkboxes.eq(index - 1).is(':checked')) {
                checkboxes.eq(index).prop('disabled', false);
            }
        });
    });


    $('.task-checkbox').on('change', function () {

        let current = $(this);
        let row = current.closest('tr');
        let checkboxes = row.find('.task-checkbox');

        checkboxes.each(function (index) {
            let prevChecked = true;

            if (index > 0) {
                prevChecked = checkboxes.eq(index - 1).is(':checked');
            }

            if (!prevChecked) {
                $(this).prop('checked', false);
                $(this).prop('disabled', true);
            } else {
                if (!$(this).data('locked')) {
                    $(this).prop('disabled', false);
                }
            }
        });

    });

    // Initial lock on load
    $('.task-checkbox').on('change', function () {
        let checkbox = $(this);
        if (!checkbox.is(':checked')) return;
        $.post("{{ route('process.task.complete') }}", {
            _token: "{{ csrf_token() }}",
            order_item_id: checkbox.data('item-id'),
            task_name_id: checkbox.data('task-id'),
        }).done(() => {
            let row = checkbox.closest('tr');
            let index = row.find('.task-checkbox').index(checkbox);
            row.find('.task-checkbox').eq(index + 1).prop('disabled', false);
        });
    });

});
</script>
@endpush
