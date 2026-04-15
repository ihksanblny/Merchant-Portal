<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    product: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    sku: '',
    category: '',
    description: '',
    critical_stock: 5,
    expiry_days_threshold: 7,
    price: 0,
});

watch(
    () => props.show,
    (isOpen) => {
        if (isOpen) {
            form.clearErrors();
            if (props.product) {
                form.name = props.product.name;
                form.sku = props.product.sku;
                form.category = props.product.category;
                form.description = props.product.description;
                form.critical_stock = props.product.critical_stock;
                form.expiry_days_threshold = props.product.expiry_days_threshold;
                form.price = props.product.price;
            } else {
                form.reset();
            }
        }
    }
);

const close = () => {
    emit('close');
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    if (props.product) {
        form.put(route('products.update', props.product.id), {
            onSuccess: () => close(),
        });
    } else {
        form.post(route('products.store'), {
            onSuccess: () => close(),
        });
    }
};
</script>

<template>
    <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 backdrop-blur-none" enter-to-class="opacity-100 backdrop-blur-sm" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 backdrop-blur-sm" leave-to-class="opacity-0 backdrop-blur-none" mode="out-in">
        <div v-show="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Background Backdrop -->
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="close"></div>
            
            <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-12 scale-95" enter-to-class="opacity-100 translate-y-0 scale-100" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0 scale-100" leave-to-class="opacity-0 translate-y-12 scale-95" mode="out-in">
                <div v-show="show" class="relative bg-surface-lowest shadow-[0_40px_100px_rgba(70,69,85,0.15)] rounded-[2.5rem] w-full max-w-lg overflow-hidden border border-surface-low">
                    
                    <div class="px-8 py-7 border-b border-surface-low flex justify-between items-center bg-surface-lowest">
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ product ? 'Edit Produk' : 'Produk Baru' }}</h3>
                        <button @click="close" class="text-gray-400 hover:text-gray-800 transition-colors bg-surface-low/50 hover:bg-surface-low p-2 rounded-full">
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
                            <button type="button" @click="close" class="px-6 py-3 rounded-full text-sm font-bold text-gray-500 hover:bg-surface-low transition-colors">Batal</button>
                            <PrimaryButton :disabled="form.processing" class="px-8 py-3 shadow-[0_10px_20px_rgba(79,70,229,0.3)] hover:shadow-none translate-y-0 hover:translate-y-1">
                                {{ product ? 'Simpan Perubahan' : 'Simpan Produk' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </transition>
        </div>
    </transition>
</template>