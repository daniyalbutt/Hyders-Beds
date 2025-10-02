<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use App\Models\Order;
use Carbon\Carbon;

class RouteController extends Controller
{

    function __construct(){
        $this->middleware('permission:routes|create routes|edit routes|delete routes', ['only' => ['index','show']]);
        $this->middleware('permission:create routes', ['only' => ['create','store','import']]);
        $this->middleware('permission:edit routes', ['only' => ['edit','update']]);
        $this->middleware('permission:delete routes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $days = [];
        for ($i = 0; $i < 8; $i++) {
            $days[] = [
                'day'  => $startOfWeek->copy()->addDays($i)->format('l'),      // Full name: Sunday, Monday, etc
                'date' => $startOfWeek->copy()->addDays($i)->format('Y-m-d'), // YYYY-MM-DD
            ];
        }

        $routes = Route::whereBetween('start_date', [
            $startOfWeek->copy()->format('Y-m-d'),
            $startOfWeek->copy()->addDays(7)->format('Y-m-d')
        ])
        ->orderBy('start_time')
        ->get();

        foreach ($days as &$day) {
            $day['routes'] = $routes->where('start_date', $day['date']);
        }
        
        return view('routes.index', compact('days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('routes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'start_date'     => 'required|date',
            'start_time'     => 'required',
            'start_location' => 'required|string|max:255',
            'end_location'   => 'required|string|max:255',
        ]);
        Route::create($request->all());
        return redirect()->back()->with('success', 'Route Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->back()->with('success', 'Route deleted Successfully');
    }

    public function unassignedOrders(Request $request)
    {
        $orders = Order::with('get_customer')->whereNull('route_id')->where('draft', 1)->get();
        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function assignOrder(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->update(['route_id' => $request->route_id]);

        return response()->json([
            'success' => true
        ]);
    }

}
