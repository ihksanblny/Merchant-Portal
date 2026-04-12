<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    batches: Array,
    transactions: Array,
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
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard Laporan Inventori
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
                
                <!-- Section: Sisa Stok per Batch -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 z-10 flex items-center">📦 Total Stok per Batch</h3>
                        <p class="text-sm text-gray-500 mb-4 -mt-3">Status actual sisa gudang (diurutkan dari yg mau basi).</p>
                        
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full text-left text-sm whitespace-nowrap">
                                <thead class="uppercase border-b border-gray-200 bg-gray-50 text-gray-500 text-xs font-semibold">
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
                </div>

                <!-- Section: Riwayat Transaksi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">📝 Riwayat Aktivitas & Transaksi</h3>
                        
                        <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full text-left text-sm whitespace-nowrap">
                                <thead class="uppercase border-b border-gray-200 bg-gray-50 text-gray-500 text-xs font-semibold">
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

            </div>
        </div>
    </AuthenticatedLayout>
</template>
