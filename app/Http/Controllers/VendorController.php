<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    // List all vendors owned by the authenticated user
    public function index(Request $request)
    {
        return $request->user()->vendors()->get();
    }

    // Register a new vendor
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $vendor = $request->user()->vendors()->create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Vendor registered successfully.',
            'vendor' => $vendor
        ], 201);
    }
}
