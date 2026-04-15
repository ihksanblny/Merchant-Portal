<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center py-12 sm:pt-0 bg-slate-50 overflow-hidden">
        <Head title="Register" />

        <Link href="/" class="absolute top-6 left-6 md:top-8 md:left-8 flex items-center justify-center p-3 md:px-5 md:py-2.5 bg-white/50 hover:bg-white backdrop-blur-xl shadow-[0_10px_30px_rgba(8,112,184,0.1)] rounded-full text-slate-600 font-bold transition-all duration-300 hover:scale-105 hover:text-indigo-600 border border-white/50 z-50">
            <svg class="w-5 h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="hidden md:inline">Beranda</span>
        </Link>

        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

        <div class="relative w-full sm:max-w-md px-8 py-10 bg-white/80 backdrop-blur-xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] sm:rounded-3xl border border-white/20">
            
            <div class="flex flex-col items-center justify-center mb-8">
                <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Buat Akun Baru</h1>
                <p class="text-slate-500 text-sm mt-1 text-center">Bergabunglah dengan ekosistem MerchantPortal kami.</p>
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Nama Lengkap" class="text-slate-600 font-medium ml-1" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.name"
                        required
                        autofocus
                        placeholder="Masukkan Nama Anda"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="username" value="Username" class="text-slate-600 font-medium ml-1" />
                    <TextInput
                        id="username"
                        type="text"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.username"
                        required
                        placeholder="Masukkan Username Anda"
                    />
                    <InputError class="mt-2" :message="form.errors.username" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" class="text-slate-600 font-medium ml-1" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.email"
                        required
                        placeholder="nama@email.com"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" class="text-slate-600 font-medium ml-1" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.password"
                        required
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Konfirmasi Password" class="text-slate-600 font-medium ml-1" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full border-gray-200 bg-white/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-all duration-200"
                        v-model="form.password_confirmation"
                        required
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="mt-8">
                    <PrimaryButton
                        class="w-full justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] focus:ring-indigo-500 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-100 font-bold"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        Daftar Akun
                    </PrimaryButton>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500">
                        Sudah punya akun? 
                        <Link :href="route('login')" class="text-indigo-600 font-bold hover:underline ml-1">
                            Masuk di sini
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
/* Animasi blob agar sinkron dengan login */
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