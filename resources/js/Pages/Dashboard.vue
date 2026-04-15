<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import VueApexCharts from 'vue3-apexcharts';
import { computed } from 'vue';

const props = defineProps({
    batches: Array,
    transactions: Array,
    metrics: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    });
};

const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('id-ID', {
        day: 'numeric', month: 'short', hour: '2-digit', minute:'2-digit'
    });
};

// Pengaturan Chart Kesehatan Stok
const healthScoreColor = computed(() => {
    if (props.metrics.health_score >= 80) return '#10b981'; // Hijau Aman
    if (props.metrics.health_score >= 40) return '#f59e0b'; // Kuning Waspada
    return '#ef4444'; // Merah Bahaya
});

const healthChartOptions = computed(() => ({
    chart: { type: 'radialBar', fontFamily: 'Inter, sans-serif' },
    plotOptions: {
        radialBar: {
            hollow: { size: '65%' },
            dataLabels: {
                name: { show: false },
                value: { show: true, fontSize: '28px', fontWeight: 'bold', color: '#1f2937', formatter: (val) => val + '%' }
            }
        }
    },
    colors: [healthScoreColor.value],
    stroke: { lineCap: 'round' },
}));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">
                Dashboard
            </h2>
        </template>

        <div class="space-y-8 mt-2">
                
                <!-- Section: Metrik Utama (Fase 4) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Metro 1: Health Score -->
                    <div class="bg-surface-lowest rounded-[2rem] shadow-ambient p-8 flex flex-col items-center justify-center relative">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest absolute top-6 left-6">Kesehatan Stok</h3>
                        <div class="mt-8 w-full flex justify-center">
                            <VueApexCharts type="radialBar" :options="healthChartOptions" :series="[metrics.health_score]" height="250" />
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">
                            {{ metrics.at_risk_qty }} dari {{ metrics.total_qty }} item berisiko kedaluwarsa.
                        </p>
                    </div>

                    <!-- Metro 2: Burn Rate -->
                    <div class="bg-surface-lowest rounded-[2rem] shadow-ambient p-8 flex flex-col relative justify-center">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Burn Rate (Avg Sales)</h3>
                                <p class="text-3xl font-black text-gray-900 mt-1">{{ metrics.burn_rate }} <span class="text-sm text-gray-500 font-normal">item/hari</span></p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                            Rata-rata penjualan harian dari 7 hari terakhir. Naikkan angka ini untuk meningkatkan kecepatan perputaran barang!
                        </p>
                    </div>

                    <!-- Metro 3: Fast & Slow Moving -->
                    <div class="bg-surface-lowest rounded-[2rem] shadow-ambient flex flex-col overflow-hidden">
                        <div class="flex-1 p-6 border-b border-surface-low bg-emerald-50/20">
                            <h3 class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest flex items-center mb-4">Fast-Moving Items</h3>
                            <ul class="text-sm space-y-3">
                                <li v-for="item in metrics.fast_moving" :key="'f-'+item.id" class="flex justify-between items-center text-gray-700">
                                    <span class="truncate pr-2 font-medium">{{ item.name }}</span>
                                    <strong class="text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full shrink-0">{{ item.sales_30d }}x</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="flex-1 p-6 bg-orange-50/20">
                            <h3 class="text-[10px] font-bold text-orange-700 uppercase tracking-widest flex items-center mb-4">Slow-Moving Items</h3>
                            <ul class="text-sm space-y-3">
                                <li v-for="item in metrics.slow_moving" :key="'s-'+item.id" class="flex justify-between items-center text-gray-700">
                                    <span class="truncate pr-2 font-medium">{{ item.name }}</span>
                                    <strong class="text-orange-600 bg-orange-100 px-2 py-0.5 rounded-full shrink-0">{{ item.sales_30d }}x</strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <!-- Section: Sisa Stok per Batch -->
                <div class="bg-surface-lowest shadow-ambient rounded-[2rem] overflow-hidden p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold tracking-tight text-gray-900 mb-1 flex items-center">Total Stok per Batch</h3>
                        <p class="text-sm text-gray-500">Status aktual sisa gudang (diurutkan dari yang terdekat kadaluwarsa).</p>
                    </div>
                        
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="uppercase border-b border-surface-low text-gray-400 tracking-wider text-xs font-bold">
                                    <tr>
                                        <th class="px-6 py-3">Nama Produk</th>
                                        <th class="px-6 py-3">Kode Batch</th>
                                        <th class="px-6 py-3 text-center">Kedaluwarsa</th>
                                        <th class="px-6 py-3 text-center">Stok Awal</th>
                                        <th class="px-6 py-3 text-center rounded-tr-lg">Sisa Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="batch in batches" :key="batch.id" class="border-b border-gray-100 hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ batch.product?.name }}</td>
                                        <td class="px-6 py-4 font-mono text-gray-600">{{ batch.batch_code || '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span :class="{'text-red-600 font-bold': new Date(batch.expiry_date) <= new Date()}">{{ formatDate(batch.expiry_date) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-gray-500">{{ batch.initial_qty }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span :class="{'bg-red-100 text-red-800': batch.current_qty <= 5, 'bg-emerald-100 text-emerald-800': batch.current_qty > 5, 'bg-gray-100 text-gray-400': batch.current_qty === 0}" class="px-3 py-1 rounded-full font-bold">
                                                {{ batch.current_qty }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="batches && batches.length === 0">
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada batch barang tersimpan. Lakukan Restock!</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <!-- Section: Riwayat Transaksi -->
                <div class="bg-surface-lowest shadow-ambient rounded-[2rem] overflow-hidden p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold tracking-tight text-gray-900 mb-1 flex items-center">Riwayat Aktivitas & Transaksi</h3>
                    </div>
                        
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm whitespace-nowrap">
                            <thead class="uppercase border-b border-surface-low text-gray-400 tracking-wider text-xs font-bold">
                                    <tr>
                                        <th class="px-6 py-3">Waktu</th>
                                        <th class="px-6 py-3">Aksi</th>
                                        <th class="px-6 py-3">Produk</th>
                                        <th class="px-6 py-3 text-center">Qty</th>
                                        <th class="px-6 py-3 text-center">Sasar Batch</th>
                                        <th class="px-6 py-3 rounded-tr-lg">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="trx in transactions" :key="trx.id" class="border-b border-gray-100 hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4 text-gray-500 text-xs">{{ formatDateTime(trx.created_at) }}</td>
                                        <td class="px-6 py-4">
                                            <span v-if="trx.type === 'RESTOCK'" class="text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded text-[10px] border border-indigo-200 tracking-wider">RESTOCK</span>
                                            <span v-else-if="trx.type === 'SALES'" class="text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded text-[10px] border border-emerald-200 tracking-wider">SALES</span>
                                            <span v-else class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded text-[10px] border border-red-200 tracking-wider">WASTE</span>
                                        </td>
                                        <td class="px-6 py-4 font-medium">{{ trx.product?.name }}</td>
                                        <td class="px-6 py-4 text-center font-bold text-gray-800">{{ (trx.type === 'RESTOCK' ? '+' : '-') + trx.quantity }}</td>
                                        <td class="px-6 py-4 text-center font-mono text-xs text-gray-500">{{ trx.batch?.batch_code || '-' }}</td>
                                        <td class="px-6 py-4 text-gray-500 text-xs italic">{{ trx.note || '-' }}</td>
                                    </tr>
                                    <tr v-if="transactions && transactions.length === 0">
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat transaksi yang terekam.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

            </div>
    </AuthenticatedLayout>
</template>