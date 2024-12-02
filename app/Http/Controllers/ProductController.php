<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['create' , 'store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['edit' , 'update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sections = Section::select('id', 'section_name')->get();
        $products = Product::with('section')->get();
        return view('products.products', compact(['sections' , 'products']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'product_name' => 'required',
            'section_id' => 'required',
            'description' => 'required',
        ] , [
            'product_name.required' => 'حقل اسم المنتج فارغ',
            'section_id.required' => 'حقل اسم القسم فارغ',
            'description.required' => 'حقل وصف المنتج فارغ',
        ]);
        Product::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'section_id' => $request->section_id,
            ]);

            session()->flash('Add' , 'تم إضافة المنتج بنجاح');
            return redirect('/products');
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
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //

        $validated = $request->validate([
            'product_name' => 'required',
            'section_name' => 'required',
            'description' => 'required',
        ] , [
            'product_name.required' => 'حقل اسم المنتج فارغ',
            'section_name.required' => 'حقل اسم القسم فارغ',
            'description.required' => 'حقل وصف المنتج فارغ',
        ]);
        $section = Section::where('section_name', '=', $request->section_name)->first();
        $id = $request->id;
        $product = Product::find($id);
        // return $product;
        $product->update([
            'product_name' => $request->product_name,
            'section_id' => $section->id,
            'description' => $request->description,
        ]);

            session()->flash('edit' , 'تم تعديل المنتج بنجاح');
            return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->id;
        $product = Product::find($id);
        $product->delete();
        session()->flash('delete' , 'تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
