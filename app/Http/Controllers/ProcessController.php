<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTaskProgress;
use App\Models\TaskName;
use Illuminate\Http\Request;

class ProcessController extends Controller
{

    function __construct(){
        $this->middleware('permission:process|create process|edit process|delete process', ['only' => ['index','show']]);
        $this->middleware('permission:create process', ['only' => ['create','store']]);
        $this->middleware('permission:edit process', ['only' => ['edit','update']]);
        $this->middleware('permission:delete process', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        $data = Route::whereHas('orders', function ($q) {
            $q->sendToProduction();
        })
        ->with([
            'orders' => function ($q) {
                $q->sendToProduction()
                ->orderBy('order_date', 'asc');
            },
            'orders.items.taskProgress.task'
        ])
        ->orderBy('start_date', 'asc')
        ->orderBy('start_time', 'asc')
        ->get();


        return view('process.index', compact('data'));
    }

    public function show($routeId)
    {
        $route = Route::findOrFail($routeId);

        $orders = Order::with([
            'get_customer',
            'items',
            'route',
            'items.taskProgress' // 👈 important
        ])
        ->where('route_id', $routeId)
        ->where('send_to_production', 1)
        ->orderBy('order_date', 'asc')
        ->get();

        $tasks = TaskName::with('productionTypes')
            ->orderBy('order')
            ->get();

        $userTaskIds = auth()->user()->hasRole('production')
        ? auth()->user()->tasks->pluck('id')->toArray()
        : [];


        return view('process.show', compact(
            'orders',
            'tasks',
            'route',
            'userTaskIds'
        ));
    }





    public function create()
    {
       
    }

    public function store(Request $request)
    {
        
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
        
    }

    public function completeTask(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'task_name_id' => 'required|exists:task_names,id',
        ]);

        $item = OrderItem::with('order.route')->findOrFail($request->order_item_id);

        OrderTaskProgress::updateOrCreate(
            [
                'order_item_id' => $item->id,
                'task_name_id' => $request->task_name_id,
            ],
            [
                'order_id' => $item->order_id,
                'user_id' => auth()->id(),
                'completed_at' => now(),
            ]
        );

        if ($request->task_name_id == TaskName::where('name', 'Loading')->value('id')) {
            // handled via progress logic
        }

        // 🔹 If order fully completed
        if ($item->order->isCompleted()) {
            $item->order->update(['production_completed' => true]);
        }

        // 🔹 If route fully completed
        if ($item->order->route->isCompleted()) {
            $item->order->route->update(['production_completed' => true]);
        }

        return response()->json([
            'success' => true
        ]);
    }


}
