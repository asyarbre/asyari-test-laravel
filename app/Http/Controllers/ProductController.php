<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'message' => 'Products Retrieved',
            'data' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'price' => 'integer|required',
            'description' => 'string|required',
            'stock' => 'integer|required',
        ]);

        $product = Product::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description, 
            'stock' => $request->stock,
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product,
        ]);
    }

    public function show(string $id)
    {
        $product = Product::where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found',
            ], 404);
        }

        if ($product->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'Product Not Found',
            ], 404);
        }

        return response()->json([
            'message' => 'Product Retrieved',
            'data' => $product,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $product = Product::where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found',
            ], 404);
        }

        if ($product->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'Product Not Found',
            ], 404);
        }

        $product->update($request->only([
            'name',
            'price',
            'description',
            'stock',
        ]));

        return response()->json([
            'message' => 'Product Updated',
            'data' => $product,
        ]);


    }

    public function destroy(string $id)
    {
        $product = Product::where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product Not Found',
            ], 404);
        }

        if ($product->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'Product Not Found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product Deleted',
        ]);
    }
}
