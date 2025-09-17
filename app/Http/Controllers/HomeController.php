<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_customer = DB::table('customers')->where('status', 0)->count();
        $total_product = DB::table('products')->where('status', 0)->count();
        $total_order = DB::table('orders')->where('status', 0)->count();
        return view('home', compact('total_customer', 'total_product', 'total_order'));
    }
}
