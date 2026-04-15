<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products')->where(fn ($query) => $query->where('user_id', auth()->id()))
            ],
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'critical_stock' => 'required|integer|min:0',
            'expiry_days_threshold' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        Product::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products')->ignore($product->id)->where(fn ($query) => $query->where('user_id', auth()->id()))
            ],
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
        abort_if($product->user_id !== auth()->id(), 403);

        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
