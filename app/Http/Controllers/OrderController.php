<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{
    function __construct(){
        $this->middleware('permission:order|create order|edit order|delete order', ['only' => ['index','show']]);
        $this->middleware('permission:create order', ['only' => ['create','store']]);
        $this->middleware('permission:edit order', ['only' => ['edit','update']]);
        $this->middleware('permission:delete order', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Order::where('status', 0)->orderBy('id', 'desc')->paginate(20);
        return view('order.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salesPersons = User::role('Sales Person')->get();
        return view('order.create', compact('salesPersons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required',
            'address' => 'required',
            'order_date' => 'required'
        ]);
        $data = new Order();
        $data->customer = $request->customer;
        $data->address = $request->address;
        $data->order_date = $request->order_date;
        $data->order_reference = $request->order_reference;
        $data->order_type = $request->order_type;
        $data->required_date = $request->required_date;
        $data->salesperson_one = $request->salesperson_one;
        $data->salesperson_two = $request->salesperson_two;
        $data->customer_contact = $request->customer_contact;
        $data->added_by = Auth::user()->id;
        $data->save();
        return redirect()->route('orders.edit', $data->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Order::find($id);
        if($data->status == 1){
            return redirect()->route('orders.index');
        }
        $salesPersons = User::role('Sales Person')->get();
        $products = Product::where('status', 0)
            ->selectRaw('MIN(id) as id, product_section')
            ->groupBy('product_section')
            ->orderBy('product_section', 'asc') // or 'desc'
            ->get();
        return view('order.edit', compact('data', 'salesPersons', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $step = $request->step;
        if($step == 'details'){
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:customers,email,' . $id,
                'telephone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'country' => 'required',
                'postcode' => 'required',
                'organizational_structure' => 'required'
            ]);
            $data = Customer::find($id);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->telephone = $request->telephone;
            $data->address = $request->address;
            $data->city = $request->city;
            $data->country = $request->country;
            $data->postcode = $request->postcode;
            $data->organizational_structure = $request->organizational_structure;
            $data->description = $request->description;
            $data->save();
            $data->sales()->delete();
            foreach ($request->sales_person ?? [] as $sale_id) {
                $data->sales()->create(['sale_id' => $sale_id]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.'
            ]);
        }

        if($step == 'credit_request'){
            $request->validate([
                'estimated_monthly_business' => 'required',
                'credit_limit_requested' => 'required'
            ]);
            $data = Customer::find($id);
            $data->estimated_monthly_business = $request->estimated_monthly_business;
            $data->credit_limit_requested = $request->credit_limit_requested;
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Credit Request updated successfully.'
            ]);
        }

        if($step == 'new_trade'){
            $request->validate([
                'company_name' => 'required',
                'address' => 'required',
                'telephone' => 'required',
                'email' => 'required',
                'contact_no' => 'required'
            ]);
            $CustomerTrade = new CustomerTrade();
            $CustomerTrade->company_name = $request->company_name;
            $CustomerTrade->address = $request->address;
            $CustomerTrade->telephone = $request->telephone;
            $CustomerTrade->email = $request->email;
            $CustomerTrade->contact_no = $request->contact_no;
            $CustomerTrade->customer_id = $id;
            $CustomerTrade->save();
            return response()->json([
                'success' => true,
                'message' => 'Trade Reference added successfully.',
                'data' => $CustomerTrade
            ]);
        }

        if($step == 'update_trade'){
            $request->validate([
                'company_name' => 'required',
                'address' => 'required',
                'telephone' => 'required',
                'email' => 'required',
                'contact_no' => 'required'
            ]);
            $company_name = $request->company_name;
            $address = $request->address;
            $telephone = $request->telephone;
            $email = $request->email;
            $contact_no = $request->contact_no;

            foreach($company_name as $key => $value){
                $CustomerTrade = CustomerTrade::find($key);
                $CustomerTrade->company_name = $company_name[$key];
                $CustomerTrade->address = $address[$key];
                $CustomerTrade->telephone = $telephone[$key];
                $CustomerTrade->email = $email[$key];
                $CustomerTrade->contact_no = $contact_no[$key];
                $CustomerTrade->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Trade Reference updated successfully.'
            ]);
        }

        if($step == 'add_bank'){
            $request->validate([
                'bank' => 'required',
                'bank_address' => 'required',
                'name_of_account' => 'required',
                'account_number' => 'required',
                'sort_code' => 'required'
            ]);
            $get_data = CustomerBank::where('customer_id', $id)->first();
            if($get_data == null){
                $CustomerBank = new CustomerBank();
            }else{
                $CustomerBank = CustomerBank::find($get_data->id);
            }

            $CustomerBank->bank = $request->bank;
            $CustomerBank->bank_address = $request->bank_address;
            $CustomerBank->name_of_account = $request->name_of_account;
            $CustomerBank->account_number = $request->account_number;
            $CustomerBank->sort_code = $request->sort_code;
            $CustomerBank->customer_id = $id;
            $CustomerBank->save();

            return response()->json([
                'success' => true,
                'message' => 'Bank updated successfully.'
            ]);
        }

        if($step == 'limited_plc'){
            $request->validate([
                'director_official_name' => 'required',
                'position' => 'required',
                'telepcontact_name_for_paymenthone' => 'required',
                'account_phone_no' => 'required',
                'account_email' => 'required'
            ]);
            $get_data = CustomerLimited::where('customer_id', $id)->first();
            if($get_data == null){
                $CustomerLimited = new CustomerLimited();
            }else{
                $CustomerLimited = CustomerLimited::find($get_data->id);
            }
            $CustomerLimited->director_official_name = $request->director_official_name;
            $CustomerLimited->position = $request->position;
            $CustomerLimited->telepcontact_name_for_paymenthone = $request->telepcontact_name_for_paymenthone;
            $CustomerLimited->account_phone_no = $request->account_phone_no;
            $CustomerLimited->account_email = $request->account_email;
            $CustomerLimited->customer_id = $id;
            $CustomerLimited->save();

            return response()->json([
                'success' => true,
                'message' => 'Limited P.L.C updated successfully.'
            ]);
        }

        if($step == 'new_customer_sale'){
            $request->validate([
                'name' => 'required',
                'address' => 'required',
                'telephone' => 'required'
            ]);
            $CustomerPartnership = new CustomerPartnership();
            $CustomerPartnership->name = $request->name;
            $CustomerPartnership->address = $request->address;
            $CustomerPartnership->telephone = $request->telephone;
            $CustomerPartnership->customer_id = $id;
            $CustomerPartnership->save();
            return response()->json([
                'success' => true,
                'message' => 'Customer Sale / Trade Partnership added successfully.',
                'data' => $CustomerPartnership
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $table_name = $request->table_name;
        
        if($table_name == 'customer_trades'){
            CustomerTrade::find($id)->delete();
        }
        if($table_name == 'customer_partnerships'){
            CustomerPartnership::find($id)->delete();
        }

        if($table_name == null){
            $data = Customer::find($id);
            $data->status = 1;
            $data->save();
            return redirect()->back()->with('success', 'Customer Deleted Successfully');
        }

        // $data = Customer::find($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Customer Deleted Successfully'
        ]);
    }

    public function addItem(Request $request, $orderId){
        $order = Order::findOrFail($orderId);
        $validated = $request->validate([
            'code' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'qty' => 'required|integer|min:1',
            'product' => 'required'
        ]);
        $userId = Auth::id();
        $item = $order->items()
            ->where('product_code', $validated['code'])
            ->first();

        if ($item) {
            $item->quantity += $validated['qty'];
            $item->total = $item->quantity * $item->price;
            $item->save();
        } else {
            $item = $order->items()->create([
                'product_id'   => $request->product ?? null,
                'product_code' => $validated['code'],
                'description'  => $validated['description'],
                'price'        => $validated['price'],
                'quantity'     => $validated['qty'],
                'total'        => $validated['price'] * $validated['qty'],
                'user_id'      => $userId,
            ]);
        }
        $order->recalcTotals();
        return response()->json([
            'success' => true,
            'item' => $item,
            'order' => $order
        ]);
    }


    public function removeItem($orderId, $itemId){
        $order = Order::findOrFail($orderId);
        $item = $order->items()->findOrFail($itemId);
        $item->delete();
        $order->recalcTotals();
        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function addDeposit(Request $request, $orderId){
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        $get_order = Order::find($orderId);

        $deposit = Deposit::create([
            'order_id' => $orderId,
            'customer' => $get_order->customer,
            'user_id' => Auth::id(),
            'amount' => $validated['amount'],
            'description' => $validated['description'],
        ]);

        $get_order->recalcTotals();

        return response()->json([
            'success' => true,
            'deposit' => $deposit,
            'order' => $get_order
        ]);
    }

    public function removeDeposit($orderId, $depositId){
        $order = Order::findOrFail($orderId);
        $deposit = $order->deposits()->where('id', $depositId)->firstOrFail();
        $deposit->delete();
        $order->recalcTotals();
        return response()->json([
            'success' => true,
            'message' => 'Deposit removed successfully',
            'order' => $order
        ]);
    }

    public function updateQty(Request $request, Order $order, OrderItem $item)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        if ($item->order_id !== $order->id) {
            return response()->json(['success' => false, 'message' => 'Item not found in this order'], 404);
        }

        $item->quantity = $validated['qty'];
        $item->total = $item->price * $validated['qty'];
        $item->save();
        $order->recalcTotals();

        return response()->json([
            'success' => true,
            'item' => $item,
            'new_total' => $order->items()->sum('total'),
            'order' => $order
        ]);
    }


}
