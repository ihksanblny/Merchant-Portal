<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class QuickLogController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'name')->get();
        return Inertia::render('QuickLog/Index', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:RESTOCK,SALES,WASTE',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
            'batch_code' => 'required_if:transaction_type,RESTOCK|nullable|string',
            'expiry_date' => 'required_if:transaction_type,RESTOCK|nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $type = $request->transaction_type;
            $qtyNeeded = $request->quantity;

            if ($type === 'RESTOCK') {
                $batch = Batch::create([
                    'product_id' => $request->product_id,
                    'batch_code' => $request->batch_code,
                    'expiry_date' => $request->expiry_date,
                    'initial_qty' => $qtyNeeded,
                    'current_qty' => $qtyNeeded,
                ]);

                Transaction::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $request->product_id,
                    'batch_id' => $batch->id,
                    'quantity' => $qtyNeeded,
                    'type' => $type,
                    'note' => $request->note,
                ]);

            } else {
                $totalStock = Batch::where('product_id', $request->product_id)
                                    ->sum('current_qty');

                if ($totalStock < $qtyNeeded) {
                    throw new \Exception("Stok tidak mencukupi! Tersisa: {$totalStock}");
                }

                $batches = Batch::where('product_id', $request->product_id)
                                ->where('current_qty', '>', 0)
                                ->orderBy('expiry_date', 'asc')
                                ->lockForUpdate()
                                ->get();

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) break;

                    $takeQty = min($batch->current_qty, $qtyNeeded);

                    $qtyNeeded -= $takeQty;

                    $batch->current_qty -= $takeQty;
                    $batch->save();

                    Transaction::create([
                        'user_id' => $request->user()->id,
                        'product_id' => $request->product_id,
                        'batch_id' => $batch->id,
                        'quantity' => $takeQty,
                        'type' => $type,
                        'note' => $request->note,
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}