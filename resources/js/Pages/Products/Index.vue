<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    products: Object,
});

// Modal state
const isModalOpen = ref(false);
const editingProduct = ref(null);

const form = useForm({
    name: '',
    sku: '',
    category: '',
    description: '',
    critical_stock: 5,
    expiry_days_threshold: 7,
    price: 0,
});

const openCreateModal = () => {
    editingProduct.value = null;
    form.reset();
    isModalOpen.value = true;
};

const openEditModal = (product) => {
    editingProduct.value = product;
    form.name = product.name;
    form.sku = product.sku;
    form.category = product.category;
    form.description = product.description;
    form.critical_stock = product.critical_stock;
    form.expiry_days_threshold = product.expiry_days_threshold;
    form.price = product.price;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (editingProduct.value) {
        form.put(route('products.update', editingProduct.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('products.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteProduct = (product) => {
    if (confirm('Yakin ingin menghapus produk ' + product.name + '?')) {
        form.delete(route('products.destroy', product.id));
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

        <!-- Inline Modal Slide-over or Overlay for Create/Edit -->
        <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 backdrop-blur-none" enter-to-class="opacity-100 backdrop-blur-sm" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 backdrop-blur-sm" leave-to-class="opacity-0 backdrop-blur-none">
            <div v-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="closeModal"></div>
                
                <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-12 scale-95" enter-to-class="opacity-100 translate-y-0 scale-100" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0 scale-100" leave-to-class="opacity-0 translate-y-12 scale-95">
                    <div v-show="isModalOpen" class="relative bg-surface-lowest shadow-[0_40px_100px_rgba(70,69,85,0.15)] rounded-[2.5rem] w-full max-w-lg overflow-hidden border border-surface-low">
                        
                        <div class="px-8 py-7 border-b border-surface-low flex justify-between items-center bg-surface-lowest">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ editingProduct ? 'Edit Produk' : 'Produk Baru' }}</h3>
                            <button @click="closeModal" class="text-gray-400 hover:text-gray-800 transition-colors bg-surface-low/50 hover:bg-surface-low p-2 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <form @submit.prevent="submitForm" class="p-8 space-y-6">
                            <div>
                                <InputLabel value="Nama Produk" />
                                <TextInput v-model="form.name" type="text" class="mt-2 block w-full !text-lg !font-bold" required autofocus placeholder="Contoh: Susu Full Cream" />
                                <div v-if="form.errors.name" class="text-rose-500 text-xs mt-2 font-medium">{{ form.errors.name }}</div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <InputLabel value="SKU / Kode (Opsional)" />
                                    <TextInput v-model="form.sku" type="text" class="mt-2 block w-full text-sm font-mono tracking-wider" placeholder="SC-001" />
                                    <div v-if="form.errors.sku" class="text-rose-500 text-xs mt-2 font-medium">{{ form.errors.sku }}</div>
                                </div>
                                <div>
                                    <InputLabel value="Kategori (Opsional)" />
                                    <TextInput v-model="form.category" type="text" class="mt-2 block w-full" placeholder="Dairy" />
                                    <div v-if="form.errors.category" class="text-rose-500 text-xs mt-2 font-medium">{{ form.errors.category }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 bg-surface-low/30 p-5 rounded-[1.5rem] border border-surface-low">
                                <div>
                                    <InputLabel value="Stok Rawan / Kritis" />
                                    <div class="relative mt-2">
                                        <TextInput v-model="form.critical_stock" type="number" min="0" class="block w-full pr-12 font-mono text-center font-bold text-orange-600" required />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-xs font-bold text-gray-400">Pcs</div>
                                    </div>
                                    <div v-if="form.errors.critical_stock" class="text-rose-500 text-xs mt-2 font-medium">{{ form.errors.critical_stock }}</div>
                                </div>
                                <div>
                                    <InputLabel value="Batas Aman (EWS)" />
                                    <div class="relative mt-2">
                                        <TextInput v-model="form.expiry_days_threshold" type="number" min="1" class="block w-full pr-16 font-mono text-center font-bold text-rose-600" required />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-xs font-bold text-gray-400">Hari</div>
                                    </div>
                                    <div v-if="form.errors.expiry_days_threshold" class="text-rose-500 text-xs mt-2 font-medium">{{ form.errors.expiry_days_threshold }}</div>
                                </div>
                            </div>

                            <div>
                                <InputLabel value="Harga Satuan (Rp)" />
                                <div class="relative mt-2">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none font-bold text-gray-400">Rp</div>
                                    <TextInput v-model="form.price" type="number" min="0" class="block w-full pl-12 font-mono" required />
                                </div>
                                <div v-if="form.errors.price" class="text-rose-500 text-xs mt-2 font-medium">{{ form.errors.price }}</div>
                            </div>

                            <div class="pt-4 flex justify-end space-x-3 mt-4">
                                <button type="button" @click="closeModal" class="px-6 py-3 rounded-full text-sm font-bold text-gray-500 hover:bg-surface-low transition-colors">Batal</button>
                                <PrimaryButton :disabled="form.processing" class="px-8 py-3 shadow-[0_10px_20px_rgba(79,70,229,0.3)] hover:shadow-none translate-y-0 hover:translate-y-1">
                                    {{ editingProduct ? 'Simpan Perubahan' : 'Simpan Produk' }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </transition>
            </div>
        </transition>

    </AuthenticatedLayout>
</template>
