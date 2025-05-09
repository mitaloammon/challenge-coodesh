<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return Product::paginate(20);
    }

    public function show($code) {
        return Product::where('code', $code)->firstOrFail();
    }

    public function update(Request $request, $code) {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update($request->only($product->getFillable()));
        return response()->json($product);
    }

    public function destroy($code) {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update(['status' => 'trash']);
        return response()->json(['message' => 'Status atualizado para trash']);
    }
}