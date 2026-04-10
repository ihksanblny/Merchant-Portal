<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    products: Array,
});

const activeTab = ref('RESTOCK');

const form = useForm({
    transaction_type: 'RESTOCK',
    product_id: '',
    quantity: 1,
    note: '',
    batch_code: '',
    expiry_date: '',
});

// Reset error dan ganti tipe transaksi ketika pindah tab
watch(activeTab, (newTab) => {
    form.transaction_type = newTab;
    form.clearErrors();
});

const submit = () => {
    form.post(route('quick-log.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('quantity', 'note', 'batch_code', 'expiry_date');
        },
    });
};
</script>

<template>
    <Head title="Quick Log" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quick Log Transaksi</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <!-- Alert Success -->
                    <div v-if="$page.props.flash?.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-6 rounded text-sm font-medium">
                        {{ $page.props.flash.success }}
                    </div>

                    <!-- Alert Error -->
                    <div v-if="$page.props.errors?.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-6 mt-6 rounded text-sm font-medium">
                        {{ $page.props.errors.error }}
                    </div>

                    <!-- Tabs Header -->
                    <div class="border-b border-gray-200 mt-4">
                        <nav class="-mb-px flex justify-between" aria-label="Tabs">
                            <button @click="activeTab = 'RESTOCK'" type="button"
                                :class="[activeTab === 'RESTOCK' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200']">
                                Restock (Masuk)
                            </button>
                            <button @click="activeTab = 'SALES'" type="button"
                                :class="[activeTab === 'SALES' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200']">
                                Sales (Keluar)
                            </button>
                            <button @click="activeTab = 'WASTE'" type="button"
                                :class="[activeTab === 'WASTE' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200']">
                                Waste (Dibuang)
                            </button>
                        </nav>
                    </div>

                    <div class="p-6 text-gray-900 border-t border-gray-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Pilih Produk -->
                            <div>
                                <InputLabel for="product_id" value="Pilih Produk" />
                                <div class="relative">
                                    <select id="product_id" v-model="form.product_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="" disabled>-- Pilih Produk yg Tersedia --</option>
                                        <option v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }}
                                        </option>
                                    </select>
                                </div>
                                <InputError class="mt-2" :message="form.errors.product_id" />
                            </div>

                            <!-- Jumlah Barang -->
                            <div>
                                <InputLabel for="quantity" value="Jumlah Barang (Qty)" />
                                <TextInput id="quantity" type="number" min="1" class="mt-1 block w-full bg-gray-50 font-bold" v-model="form.quantity" required />
                                <InputError class="mt-2" :message="form.errors.quantity" />
                                <p v-if="activeTab !== 'RESTOCK'" class="text-xs text-gray-500 mt-1">Sistem otomatis memotong stok dari batch dgn masa kedaluwarsa paling dekat (FEFO).</p>
                            </div>

                            <!-- RESTOCK Only Fields -->
                            <div v-show="activeTab === 'RESTOCK'" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-indigo-50/50 rounded-lg border border-indigo-100">
                                <div>
                                    <InputLabel for="batch_code" value="Kode Batch (Opsional)" />
                                    <TextInput id="batch_code" type="text" class="mt-1 block w-full text-sm" v-model="form.batch_code" placeholder="Misal: B-001" :required="activeTab === 'RESTOCK'" />
                                    <InputError class="mt-2" :message="form.errors.batch_code" />
                                </div>
                                <div>
                                    <InputLabel for="expiry_date" value="Tanggal Kedaluwarsa *" />
                                    <TextInput id="expiry_date" type="date" class="mt-1 block w-full text-sm" v-model="form.expiry_date" :required="activeTab === 'RESTOCK'" />
                                    <InputError class="mt-2" :message="form.errors.expiry_date" />
                                </div>
                            </div>

                            <!-- Catatan / Note -->
                            <div>
                                <InputLabel for="note" value="Catatan Transaksi (Opsional)" />
                                <TextInput id="note" type="text" class="mt-1 block w-full text-sm" v-model="form.note" placeholder="Alasan pembuangan, nama supplier, dll." />
                                <InputError class="mt-2" :message="form.errors.note" />
                            </div>

                            <!-- Submit Box -->
                            <div class="flex items-center justify-end pt-4">
                                <PrimaryButton :class="{'opacity-25': form.processing, 'bg-indigo-600': activeTab !== 'WASTE', 'bg-red-600 hover:bg-red-700': activeTab === 'WASTE'}" :disabled="form.processing">
                                    Simpan Transaksi {{ activeTab }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
