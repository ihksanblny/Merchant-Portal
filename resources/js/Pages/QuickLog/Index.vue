<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CustomSelect from '@/Components/CustomSelect.vue';

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
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Quick Log Transaksi</h2>
            <p class="text-gray-500 mt-2 text-sm max-w-xl">
                Catat masuk-keluarnya barang dengan cepat. Sistem akan otomatis memonitor/memotong batch yang paling dekat dengan tanggal kedaluwarsa.
            </p>
        </template>

        <div class="max-w-3xl mt-4">
                <div class="bg-surface-lowest shadow-ambient rounded-[2rem] overflow-hidden mb-12">
                    
                    <!-- Alert Success -->
                    <div v-if="$page.props.flash?.success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-6 mt-6 rounded text-sm font-medium">
                        {{ $page.props.flash.success }}
                    </div>

                    <!-- Alert Error -->
                    <div v-if="$page.props.errors?.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-6 mt-6 rounded text-sm font-medium">
                        {{ $page.props.errors.error }}
                    </div>

                    <!-- Tabs Header -->
                    <div class="px-8 mt-8">
                        <nav class="flex space-x-2 p-1 bg-surface-low rounded-2xl" aria-label="Tabs">
                            <button @click="activeTab = 'RESTOCK'" type="button"
                                :class="[activeTab === 'RESTOCK' ? 'bg-white text-brand-container shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-white/50', 'flex-1 py-3 px-4 text-center rounded-xl font-bold text-sm transition-all duration-300']">
                                Restock (Masuk)
                            </button>
                            <button @click="activeTab = 'SALES'" type="button"
                                :class="[activeTab === 'SALES' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-white/50', 'flex-1 py-3 px-4 text-center rounded-xl font-bold text-sm transition-all duration-300']">
                                Sales (Keluar)
                            </button>
                            <button @click="activeTab = 'WASTE'" type="button"
                                :class="[activeTab === 'WASTE' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-white/50', 'flex-1 py-3 px-4 text-center rounded-xl font-bold text-sm transition-all duration-300']">
                                Waste (Dibuang)
                            </button>
                        </nav>
                    </div>

                    <div class="p-8 text-gray-900 border-t border-surface-low/50 mt-4">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Pilih Produk -->
                            <div>
                                <InputLabel for="product_id" value="Pilih Produk" />
                                <CustomSelect 
                                    v-model="form.product_id" 
                                    :options="products" 
                                    placeholder="Cari Produk Gudang" 
                                />
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
                            <div v-show="activeTab === 'RESTOCK'" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-brand-container/5 rounded-2xl border border-brand-container/10">
                                <div>
                                    <InputLabel for="batch_code" value="Kode Batch (Opsional)" />
                                    <TextInput id="batch_code" type="text" class="mt-1 block w-full text-sm" v-model="form.batch_code" placeholder="Misal: B-001" :required="activeTab === 'RESTOCK'" />
                                    <InputError class="mt-2" :message="form.errors.batch_code" />
                                </div>
                                <div>
                                    <InputLabel for="expiry_date" value="Tanggal Kedaluwarsa *" />
                                    <TextInput id="expiry_date" type="date" class="mt-1 block w-full text-sm z-50 relative" v-model="form.expiry_date" :required="activeTab === 'RESTOCK'" />
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
                                <PrimaryButton :class="{'opacity-25': form.processing, 'bg-brand hover:bg-brand-container border-0': activeTab === 'RESTOCK', 'bg-emerald-600 hover:bg-emerald-700 border-0': activeTab === 'SALES', 'bg-red-600 hover:bg-red-700 border-0': activeTab === 'WASTE', 'rounded-full px-8 py-3 text-sm font-bold tracking-wide transition-all': true}" :disabled="form.processing">
                                    Simpan Transaksi {{ activeTab }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </AuthenticatedLayout>
</template>
