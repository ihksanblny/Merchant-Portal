<x-mail::message>
# Peringatan Stok Hampir Kedaluwarsa

Halo,

Ini adalah notifikasi dari sistem **SisaLezat / Merchant Portal**.
Berdasarkan pengecekan jadwal (Health Check) kami, terdapat beberapa batch produk di gudang Anda yang **segera mendekati batas kedaluwarsa** dan harus segera diurus (diskon/dijual/dibuang).

### 📦 Daftar Stok Berisiko:
@foreach ($atRiskBatches as $batch)
**{{ $batch['product_name'] }}** (Kode: {{ $batch['batch_code'] }})
- Sisa Stok: **{{ $batch['current_qty'] }} item**
- Tanggal Kedaluwarsa: **{{ \Carbon\Carbon::parse($batch['expiry_date'])->format('d M Y') }}**
- Sisa Waktu: {{ $batch['days_left'] }} hari lagi

@endforeach

Silakan segera masuk ke Dashboard Anda untuk mengambil tindakan.

<x-mail::button :url="config('app.url') . '/quick-log'">
Buka Quick Log
</x-mail::button>

Terima kasih,<br>
Sistem Early Warning - {{ config('app.name') }}
</x-mail::message>
