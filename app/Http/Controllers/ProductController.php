<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'critical_stock' => 'required|integer|min:0',
            'expiry_days_threshold' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'critical_stock' => 'required|integer|min:0',
            'expiry_days_threshold' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
