<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{

    function __construct(){
        $this->middleware('permission:product|create product|edit product|delete product', ['only' => ['index','show']]);
        $this->middleware('permission:create product', ['only' => ['create','store','import']]);
        $this->middleware('permission:edit product', ['only' => ['edit','update']]);
        $this->middleware('permission:delete product', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::where('status', 0)->orderBy('id', 'desc')->paginate(20);
        return view('product.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|unique:products,product_code',
            'description' => 'required',
            'product_section' => 'required',
            'production_type' => 'required'
        ]);
        $data = new Product();
        $data->product_code = $request->product_code;
        $data->description = $request->description;
        $data->sale_price = $request->sale_price;
        $data->product_range = $request->product_range;
        $data->product_section = $request->product_section;
        $data->production_type = $request->production_type;
        $data->volume = $request->volume;
        $data->weight = $request->weight;
        $data->width = $request->width;
        $data->length = $request->length;
        $data->height = $request->height;
        $data->save();
        return redirect()->back()->with('success', 'Product Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $data = Product::find($id);
        return view('product.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_code' => 'required|unique:products,product_code,' . $id,
            'description' => 'required',
            'product_section' => 'required',
            'production_type' => 'required'
        ]);
        $data = Product::find($id);
        $data->product_code = $request->product_code;
        $data->description = $request->description;
        $data->sale_price = $request->sale_price;
        $data->product_range = $request->product_range;
        $data->product_section = $request->product_section;
        $data->production_type = $request->production_type;
        $data->volume = $request->volume;
        $data->weight = $request->weight;
        $data->width = $request->width;
        $data->length = $request->length;
        $data->height = $request->height;
        $data->save();
        return redirect()->back()->with('success', 'Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->back()->with('success', 'Product Deleted Successfully');
    }

    public function import(){
        return view('product.import');
    }

    public function importProduct(Request $request){
        try{
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Product Import Successfully');
        }catch(\Exception $ex){
            return redirect()->back()->with('success', 'Some error has occu ' . $ex->getMessage());
        }
    }
}
