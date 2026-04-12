<?php

namespace App\Jobs;

use App\Models\Batch;
use App\Models\User;
use App\Mail\ExpiryWarningMail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EvaluateExpiryStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tries: Berapa kali job boleh gagal sebelum dibuang
     */
    public $tries = 3;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('EWS: Memulai pengecekan kedaluwarsa stok...');

        $batches = Batch::with('product')
            ->where('current_qty', '>', 0)
            ->whereNotNull('expiry_date')
            ->get();

        $atRiskBatches = [];

        foreach ($batches as $batch) {
            $daysToExpiry = (int) ceil(Carbon::now()->diffInDays(Carbon::parse($batch->expiry_date), false));
            $threshold = $batch->product->expiry_days_threshold ?? 7;

            // Jika hari menuju kadaluarsa kurang dari atau sama dengan threshold
            if ($daysToExpiry <= $threshold) {
                $atRiskBatches[] = [
                    'product_name' => $batch->product->name ?? 'Unknown',
                    'batch_code' => $batch->batch_code ?? '-',
                    'current_qty' => $batch->current_qty,
                    'expiry_date' => $batch->expiry_date,
                    'days_left' => $daysToExpiry
                ];
            }
        }

        // Jika ada barang yang berisiko, kirim Notifikasi!
        if (count($atRiskBatches) > 0) {
            Log::info('EWS: Ditemukan ' . count($atRiskBatches) . ' batch berisiko. Memulai pengiriman Email dan WA.');

            // 1. Eksekusi Kirim Email ke Pemilik (Ambil Admin pertama untuk demo)
            $owner = User::first(); 
            if ($owner) {
                Mail::to($owner->email)->send(new ExpiryWarningMail($atRiskBatches));
                Log::info('EWS: Email peringatan dikirim ke ' . $owner->email);
            }

            // 2. Eksekusi Integrasi API WhatsApp (Asinkron)
            $this->sendWhatsAppNotification($owner, count($atRiskBatches));
        } else {
            Log::info('EWS: Semua stok aman. Tidak ada peringatan dikirim.');
        }
    }

    /**
     * Kirim notifikasi WA menggunakan Guzzle/Http Client asinkron
     */
    private function sendWhatsAppNotification($user, $totalAtRisk)
    {
        // Ganti dengan API Endpoint dan Token Provider WA kamu (misal: Fonnte/Wablas/Watzap)
        $waApiUrl = env('WA_API_URL', 'https://api.fonnte.com/send');
        $waToken = env('WA_API_TOKEN', 'TOKEN_DUMMY');
        $targetPhone = env('WA_TARGET_PHONE', '081234567890'); // Ambil dari $user->phone jika ada

        $message = "🚨 *PERTANDA DARURAT STOK* 🚨\n\nHalo!\nTerdapat *$totalAtRisk Batch Produk* di gudang yang mendekati masa Basi/Kedaluwarsa.\n\nHarap segera cek Dashboard SisaLezat untuk menghabiskan stok tersebut atau membuangnya!";

        try {
            // Karena ini berjalan di dalam Queued Job, pengiriman ini sudah Asinkron dari sisi User.
            $response = Http::withHeaders([
                'Authorization' => $waToken
            ])->timeout(10)->post($waApiUrl, [
                'target' => $targetPhone,
                'message' => $message,
                'countryCode' => '62', // Opsional tergantung provider
            ]);

            if ($response->successful()) {
                Log::info('EWS: WhatsApp notifikasi berhasil dikirim via API.');
            } else {
                Log::warning('EWS: Pengiriman API WA gagal - ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('EWS: Server WA API tidak merespon. Error: ' . $e->getMessage());
        }
    }
}
