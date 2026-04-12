<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Existing Data
        $batches = Batch::with('product')->orderBy('expiry_date', 'asc')->get();
        $transactions = Transaction::with(['product', 'batch', 'user'])->orderBy('created_at', 'desc')->take(20)->get();

        // 2. Health Score Kalkulasi
        // Total stok aktual vs stok berisiko (mendekati kedaluwarsa sesuai threshold)
        $totalItems = $batches->sum('current_qty');
        $atRiskItems = 0;

        foreach ($batches as $batch) {
            $daysToExpiry = Carbon::now()->diffInDays(Carbon::parse($batch->expiry_date), false);
            // Default 7 hari jika produk tidak memiliki spesifik threshold (asumsi)
            $threshold = $batch->product->expiry_days_threshold ?? 7; 
            
            if ($daysToExpiry <= $threshold) {
                $atRiskItems += $batch->current_qty;
            }
        }

        $healthScore = $totalItems > 0 ? round((($totalItems - $atRiskItems) / $totalItems) * 100) : 100;

        // 3. Burn Rate Calculation (Rata-rata penjualan per hari dalam 7 hari terakhir)
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $totalSalesLast7Days = Transaction::where('type', 'SALES')
            ->where('created_at', '>=', $sevenDaysAgo)
            ->sum('quantity');
        
        $averageBurnRate = round($totalSalesLast7Days / 7, 2);

        // 4. Fast & Slow Moving Items (Berdasarkan jumlah terjual 30 hari terakhir)
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $salesByProduct = Transaction::where('type', 'SALES')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->select('product_id', DB::raw('SUM(quantity) as total_sales'))
            ->groupBy('product_id')
            ->get();

        $products = Product::all();
        $itemVelocity = [];

        foreach ($products as $product) {
            $sale = $salesByProduct->where('product_id', $product->id)->first();
            $itemVelocity[] = [
                'id' => $product->id,
                'name' => $product->name,
                'sales_30d' => $sale ? (int) $sale->total_sales : 0
            ];
        }

        // Urutkan berdasarkan penjualan (DESC)
        usort($itemVelocity, fn($a, $b) => $b['sales_30d'] <=> $a['sales_30d']);
        
        // Ambil top 3 terbanyak (Fast) daf bottom 3 tersedikit (Slow)
        $fastMoving = array_slice($itemVelocity, 0, 3);
        $slowMoving = array_slice(array_reverse($itemVelocity), 0, 3);

        return Inertia::render('Dashboard', [
            'batches' => $batches,
            'transactions' => $transactions,
            'metrics' => [
                'health_score' => $healthScore,
                'at_risk_qty' => $atRiskItems,
                'total_qty' => $totalItems,
                'burn_rate' => $averageBurnRate,
                'fast_moving' => $fastMoving,
                'slow_moving' => $slowMoving,
            ]
        ]);
    }
}
