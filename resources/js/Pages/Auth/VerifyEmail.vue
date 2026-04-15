<script setup>
import { computed } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 overflow-hidden">
        <Head title="Email Verification" />

        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

        <div class="relative w-full sm:max-w-md px-8 py-10 bg-white/80 backdrop-blur-xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] sm:rounded-3xl border border-white/20">
            
            <div class="flex flex-col items-center justify-center mb-8">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200 mb-4 transition-transform hover:scale-110 duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Verifikasi Email</h1>
                <p class="text-slate-500 text-sm mt-3 text-center leading-relaxed">
                    Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan menekan tautan yang baru saja kami kirimkan.
                </p>
            </div>

            <div
                class="mb-6 text-sm font-medium text-green-600 bg-green-50 p-4 rounded-xl border border-green-100"
                v-if="verificationLinkSent"
            >
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </div>

            <form @submit.prevent="submit">
                <div class="flex flex-col gap-4">
                    <PrimaryButton
                        class="w-full justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] focus:ring-indigo-500 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-100 font-bold"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        Kirim Ulang Email Verifikasi
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-sm text-slate-500 hover:text-slate-700 font-medium transition-colors"
                    >
                        Keluar (Logout)
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>

