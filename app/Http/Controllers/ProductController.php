<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List products for authenticated user's vendors
    public function index(Request $request)
    {
        $user = $request->user();
        $products = Product::whereIn('vendor_id', $user->vendors->pluck('id'))->get();

        return response()->json($products);
    }

    // Create product under a specific vendor
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $vendor = Vendor::where('id', $request->vendor_id)
                        ->where('user_id', $request->user()->id)
                        ->first();

        if (!$vendor) {
            return response()->json(['message' => 'Unauthorized vendor.'], 403);
        }

        $product = $vendor->products()->create($request->only(['name', 'description', 'price', 'stock']));

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product
        ], 201);
    }

    // Show single product (if owned by user)
    public function show($id, Request $request)
    {
        $product = Product::findOrFail($id);

        if ($product->vendor->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        return response()->json($product);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->vendor->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($request->only(['name', 'description', 'price', 'stock']));

        return response()->json(['message' => 'Product updated.', 'product' => $product]);
    }

    // Delete product
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->vendor->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }
}