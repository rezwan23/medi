<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->isAjax == 1){
            $products = Product::with('variantPrices', 'variantPrices.variantOne', 'variantPrices.variantTwo', 'variantPrices.variantThree');
            
            if($request->filled('title')){
                $products->where('title', 'like', '%'.$request->title.'%');
            }

            if($request->filled('priceFrom')){
                $products->whereHas('variantPrices', function($query) use ($request){
                    $query->where('price', '>=', $request->priceFrom);
                });
            }

            if($request->filled('priceTo')){
                $products->whereHas('variantPrices', function($query) use ($request){
                    $query->where('price', '<=', $request->priceTo);
                });
            }

            if($request->filled('variant')){
                $productIds = ProductVariant::where('variant', $request->variant)->pluck('product_id');
                
                $products->whereIn('id', $productIds);
            }
            if($request->filled('date')){
                
                $products->whereDate('created_at', $request->date);
            }

            return [
                'total' => $products->count(),
                'products' => $products->paginate(2), 
            ];
        }
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }


    public function getVariants()
    {
        return Variant::with('variantValue')->select(['id', 'title'])->get();
    }
}
