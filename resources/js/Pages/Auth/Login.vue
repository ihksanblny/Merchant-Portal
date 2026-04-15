<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 overflow-hidden">
        <Head title="Log in" />

        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

        <div class="relative w-full sm:max-w-md px-8 py-10 bg-white/80 backdrop-blur-xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] sm:rounded-3xl border border-white/20">
            
            <div class="flex flex-col items-center justify-center mb-10">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200 mb-4 transition-transform hover:scale-110 duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">MerchantPortal.</h1>
                <p class="text-slate-500 text-sm mt-1">Silakan masuk ke akun Anda</p>
            </div>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-100">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email or Username" class="text-slate-600 font-medium ml-1" />

                    <TextInput
                        id="email"
                        type="text"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan email anda"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-5">
                    <div class="flex justify-between items-center mb-1">
                        <InputLabel for="password" value="Password" class="text-slate-600 font-medium ml-1" />
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition-colors"
                        >
                            Lupa sandi?
                        </Link>
                    </div>

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-5 block">
                    <label class="flex items-center group cursor-pointer w-fit">
                        <Checkbox name="remember" v-model:checked="form.remember" class="text-indigo-600 border-gray-300 rounded-md focus:ring-indigo-500" />
                        <span class="ms-2 text-sm text-slate-500 group-hover:text-slate-700 transition-colors">Ingat saya</span>
                    </label>
                </div>

                <div class="mt-8">
                    <PrimaryButton
                        class="w-full justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] focus:ring-indigo-500 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-100 font-bold"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        Masuk Sekarang
                    </PrimaryButton>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500">
                        Belum punya akun? 
                        <Link :href="route('register')" class="text-indigo-600 font-bold hover:underline">Daftar gratis</Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
/* Animasi opsional untuk bola background agar bergerak pelan */
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