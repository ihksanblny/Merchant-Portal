<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProductFormModal from './Partials/ProductFormModal.vue';

const props = defineProps({
    products: Object,
});

// Modal state
const isModalOpen = ref(false);
const editingProduct = ref(null);

const openCreateModal = () => {
    editingProduct.value = null;
    isModalOpen.value = true;
};

const openEditModal = (product) => {
    editingProduct.value = product;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const deleteProduct = (product) => {
    if (confirm('Yakin ingin menghapus produk ' + product.name + '?')) {
        router.delete(route('products.destroy', product.id));
    }
};
</script>

<template>
    <Head title="Manajemen Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Katalog Produk</h2>
                    <p class="text-gray-400 mt-1 font-medium">Kelola barang, harga, dan parameter EWS.</p>
                </div>
                <PrimaryButton @click="openCreateModal" class="px-6 py-3 flex items-center shadow-[0_10px_20px_rgba(79,70,229,0.3)]">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produk
                </PrimaryButton>
            </div>
        </template>

        <!-- Product Table / Grid -->
        <div class="bg-surface-lowest shadow-ambient rounded-[2rem] p-6 border border-surface-low transform transition duration-500 hover:shadow-2xl">
            <div class="overflow-x-auto px-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-widest border-b border-surface-low">
                            <th class="py-4 px-4 font-bold">Nama & SKU</th>
                            <th class="py-4 px-4 font-bold">Kategori</th>
                            <th class="py-4 px-4 font-bold text-center">Batas Kritis</th>
                            <th class="py-4 px-4 font-bold text-center pl-6">EWS (Hari)</th>
                            <th class="py-4 px-4 font-bold text-right">Harga Satuan</th>
                            <th class="py-4 px-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products.data" :key="product.id" class="group hover:bg-surface-low transition-colors duration-200">
                            <td class="py-4 px-4 rounded-l-[1.5rem]">
                                <div class="font-bold text-gray-900 text-[15px]">{{ product.name }}</div>
                                <div class="text-[11px] text-gray-400 font-mono tracking-wider">{{ product.sku || 'TANPA SKU' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide uppercase">{{ product.category || 'LAINNYA' }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="bg-orange-50 text-orange-500 px-4 py-1.5 rounded-full text-xs font-bold">{{ product.critical_stock }}</span>
                            </td>
                            <td class="py-4 px-4 text-center pl-6">
                                <span class="bg-rose-50 text-rose-500 px-4 py-1.5 rounded-full text-xs font-bold">{{ product.expiry_days_threshold }}</span>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-emerald-600 text-[15px]">
                                Rp {{ Number(product.price).toLocaleString('id-ID') }}
                            </td>
                            <td class="py-4 px-4 text-right rounded-r-[1.5rem] opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEditModal(product)" class="text-brand-container font-black hover:text-brand mx-3 text-sm transition-colors">Edit</button>
                                <button @click="deleteProduct(product)" class="text-red-400 font-black hover:text-red-600 ml-1 mr-2 text-sm transition-colors">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="products.data.length === 0" class="text-center py-12 text-gray-400">
                    <div class="mb-4 text-gray-300 flex justify-center">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="font-bold text-gray-500">Belum ada data produk.</p>
                    <p class="text-sm mt-1">Silakan tambahkan produk pertama Anda di katalog.</p>
                </div>
            </div>
            
            <!-- Pagination Widget -->
            <div v-if="products.links && products.links.length > 3" class="px-6 py-4 border-t border-surface-low flex items-center justify-between bg-surface-lowest rounded-b-[2rem] mt-2">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">
                            Menampilkan <span class="font-bold text-gray-900">{{ products.from }}</span> hingga <span class="font-bold text-gray-900">{{ products.to }}</span> dari <span class="font-bold text-gray-900">{{ products.total }}</span> produk
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-full shadow-sm overflow-hidden border border-surface-low" aria-label="Pagination">
                            <Link v-for="(link, k) in products.links" :key="k"
                                :href="link.url || '#'"
                                :class="[
                                    link.active ? 'bg-brand-container text-white font-black' : 'bg-surface hover:bg-surface-low text-gray-500 font-bold', 
                                    'relative inline-flex items-center px-4 py-2 text-sm transition-colors border-r border-surface-low last:border-r-0', 
                                    !link.url ? 'opacity-40 cursor-not-allowed' : ''
                                ]"
                                v-html="link.label"
                                :preserve-scroll="true"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extracted ProductFormModal -->
        <ProductFormModal 
            :show="isModalOpen" 
            :product="editingProduct" 
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>