<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome to Merchant Portal" />
    
    <!-- FIX DOUBLE SCROLL: Removed extra min-h-screen and conflicting flex wrappers -->
    <div class="relative bg-surface font-sans text-gray-900 overflow-x-clip selection:bg-brand-container selection:text-white pb-20">
        
        <!-- Elegant CSS Grid Background -->
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(rgba(79, 70, 229, 0.12) 1px, transparent 1px); background-size: 24px 24px; opacity: 0.6; mask-image: linear-gradient(to bottom, black 20%, transparent 80%); -webkit-mask-image: linear-gradient(to bottom, black 20%, transparent 80%);"></div>

        <!-- Ambient Glow Elements -->
        <div class="absolute top-[-15%] left-[-10%] w-[60%] h-[60%] bg-brand-container/15 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute top-[20%] right-[-10%] w-[50%] h-[50%] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[60%] h-[50%] bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-6 lg:px-12 z-10">
            <!-- Navigation -->
            <header class="flex items-center justify-between py-8 border-b border-surface-low/50 backdrop-blur-md">
                <div class="text-[1.35rem] font-black tracking-tighter text-brand-container flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-xl bg-brand-container flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <span>
                        Merchant<span class="text-brand">Portal.</span>
                    </span>
                </div>
                <nav v-if="canLogin" class="flex items-center space-x-2 md:space-x-4 bg-white/50 backdrop-blur-md px-2 py-2 rounded-full border border-white shadow-sm">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-bold bg-brand-container text-white px-6 py-2.5 rounded-full shadow-ambient hover:bg-brand hover:scale-105 transition-all duration-300"
                    >
                        Masuk Dashboard &rarr;
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-bold text-gray-600 hover:text-brand-container transition-colors px-5 py-2.5 rounded-full hover:bg-white"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="text-sm font-bold bg-gray-900 text-white px-6 py-2.5 rounded-full shadow-md hover:bg-black hover:scale-105 transition-all duration-300 ml-1"
                        >
                            Daftar Sekarang
                        </Link>
                    </template>
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="flex flex-col items-center text-center mt-20 md:mt-28 relative">
                
                <div class="inline-flex items-center rounded-full px-5 py-2 bg-white border border-gray-200 shadow-sm text-gray-800 font-bold text-xs tracking-wider mb-8 uppercase">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                    Sistem Inventori Generasi Baru
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-[5.5rem] font-black text-gray-900 tracking-tighter leading-[1.05] max-w-5xl">
                    Sistem Kelola Stok yang 
                    <span class="relative whitespace-nowrap">
                        <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-brand to-brand-container">Benar-Benar Cerdas.</span>
                        <svg class="absolute w-full h-8 -bottom-2 left-0 text-brand-container/20 -z-10" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0,10 Q50,20 100,10" stroke="currentColor" stroke-width="4" fill="none"/></svg>
                    </span>
                </h1>
                
                <p class="mt-8 text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed font-medium">
                    Hilangkan *waste* dan catat log gudang dalam hitungan detik. MerchantPortal menyatukan prediksi Burn Rate dan Peringatan Dini (EWS) dalam satu antarmuka super mewah.
                </p>

                <div class="mt-12 flex items-center justify-center space-x-6">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="text-base font-bold bg-brand-container text-white px-8 py-4 rounded-[1.5rem] shadow-ambient hover:bg-brand hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(79,70,229,0.3)] transition-all duration-300 flex items-center"
                    >
                        Mulai Gratis Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </Link>
                    <a href="#features" class="text-base font-bold text-gray-600 hover:text-brand-container px-6 py-4 rounded-[1.5rem] hover:bg-white transition-all duration-300 flex items-center">
                        Pelajari Fitur
                    </a>
                </div>

                <!-- Abstract App Preview Card -->
                <div class="mt-20 w-full max-w-5xl mx-auto perspective-1000">
                    <div class="relative rounded-[2.5rem] bg-white/60 backdrop-blur-2xl border border-white shadow-[0_40px_100px_rgba(70,69,85,0.08)] p-4 transform transition-transform duration-700 hover:scale-[1.02]">
                        <div class="absolute inset-0 rounded-[2.5rem] bg-gradient-to-tr from-brand-container/5 to-transparent pointer-events-none"></div>
                        <div class="rounded-[2rem] bg-surface-lowest shadow-sm border border-surface-low overflow-hidden flex flex-col h-[400px]">
                            <!-- Fake Window Header -->
                            <div class="h-12 border-b border-surface-low flex items-center px-6 space-x-2 bg-surface">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            </div>
                            <!-- Fake Content Area (Code Snippet) -->
                            <div class="flex-1 overflow-hidden bg-[#0d1117] p-8 font-mono text-sm leading-relaxed text-gray-300 relative text-left">
                                <div class="absolute top-0 left-0 w-full h-8 bg-gradient-to-b from-[#0d1117] to-transparent z-10"></div>
                                <div class="absolute bottom-0 left-0 w-full h-8 bg-gradient-to-t from-[#0d1117] to-transparent z-10"></div>
                                
                                <div class="code-scroll-animation flex flex-col space-y-1.5 opacity-90">
                                    <div class="flex"><span class="text-pink-400 mr-2">const</span> <span class="text-blue-400">checkExpiry</span> <span class="text-gray-400">=</span> <span class="text-yellow-300">async</span> () <span class="text-blue-400">=></span> {</div>
                                    <div class="flex pl-6"><span class="text-pink-400 mr-2">const</span> <span class="text-white">items</span> <span class="text-gray-400">=</span> <span class="text-yellow-300">await</span> <span class="text-green-300">Inventory</span>.<span class="text-blue-300">get</span>();</div>
                                    <div class="flex pl-6"><span class="text-pink-400">return</span> <span class="text-white">items</span>.<span class="text-blue-300">filter</span>(<span class="text-orange-300">i</span> <span class="text-blue-400">=></span> <span class="text-orange-300">i</span>.<span class="text-white">daysLeft</span> <span class="text-gray-400"><</span> <span class="text-purple-400">7</span>);</div>
                                    <div class="flex">};</div>
                                    <div class="h-2"></div>
                                    <div class="flex"><span class="text-pink-400 mr-2">import</span> { <span class="text-white">notify</span> } <span class="text-pink-400 mr-2">from</span> <span class="text-green-400">'@/utils/alert'</span>;</div>
                                    <div class="h-2"></div>
                                    <div class="flex"><span class="text-yellow-300">setInterval</span>(() <span class="text-blue-400">=></span> {</div>
                                    <div class="flex pl-6"><span class="text-pink-400 mr-2">const</span> <span class="text-white">criticalItems</span> <span class="text-gray-400">=</span> <span class="text-blue-300">checkExpiry</span>();</div>
                                    <div class="flex pl-6"><span class="text-pink-400">if</span> (<span class="text-white">criticalItems</span>.<span class="text-blue-300">length</span> <span class="text-gray-400">></span> <span class="text-purple-400">0</span>) {</div>
                                    <div class="flex pl-12"><span class="text-blue-300">notify</span>(<span class="text-green-400">'Awas! Ada stok yang hampir kedaluwarsa!'</span>);</div>
                                    <div class="flex pl-6">}</div>
                                    <div class="flex">}, <span class="text-purple-400">1000 * 60 * 60</span>);</div>
                                    <div class="h-2"></div>
                                    <div class="flex text-gray-500 italic">// Mengkalkulasi Burn Rate untuk memprediksi stok...</div>
                                    <div class="flex items-center mt-2"><span class="text-green-400 font-bold mr-2">merchant-portal ~ $ </span> <span class="w-2.5 h-4 bg-gray-300 animate-pulse"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-32 w-full text-left">
                    <div class="bg-white/80 backdrop-blur-xl shadow-ambient p-10 rounded-[2.5rem] border border-white hover:-translate-y-2 hover:shadow-[0_40px_80px_rgba(70,69,85,0.06)] transition-all duration-300">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 flex items-center justify-center mb-8 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight text-gray-900 mb-4">Live Burn Rate</h3>
                        <p class="text-base text-gray-500 leading-relaxed font-medium">Algoritma analitik meracik data transaksi harian untuk memberitahu Anda seberapa efisien barang terjual secara *real-time*.</p>
                    </div>

                    <div class="bg-white/80 backdrop-blur-xl shadow-ambient p-10 rounded-[2.5rem] border border-white hover:-translate-y-2 hover:shadow-[0_40px_80px_rgba(70,69,85,0.06)] transition-all duration-300">
                        <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-orange-100 to-orange-50 text-orange-600 flex items-center justify-center mb-8 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight text-gray-900 mb-4">Expiry Warning</h3>
                        <p class="text-base text-gray-500 leading-relaxed font-medium">Sistem pintar secara otonom melacak stok dan mengabari Anda jika barang mendekati batas kedaluwarsa sebelum menjadi rugi.</p>
                    </div>

                    <div class="bg-brand-container text-white shadow-ambient shadow-brand-container/30 p-10 rounded-[2.5rem] hover:-translate-y-2 hover:shadow-[0_40px_80px_rgba(79,70,229,0.4)] transition-all duration-300 relative overflow-hidden group">
                        <!-- Decorative glow inside the dark card -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 blur-[50px] group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="relative z-10 w-16 h-16 rounded-[1.5rem] bg-white/10 text-white flex items-center justify-center mb-8 shadow-sm border border-white/20 backdrop-blur-md">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="relative z-10 text-2xl font-black tracking-tight text-white mb-4">Quick Logging</h3>
                        <p class="relative z-10 text-base text-brand-50 leading-relaxed font-medium">Tekan tombol, catat, selesai. Form input dioptimasi dengan *keyboard navigation* dan minim klik agar tim gudang kerja lebih kilat.</p>
                    </div>
                </div>
            </main>

            <!-- Action Banner -->
            <div class="mt-32 mb-16 relative w-full bg-gray-900 rounded-[3rem] p-12 lg:p-20 text-center overflow-hidden">
                <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-brand-container rounded-full blur-[100px] opacity-40"></div>
                
                <h2 class="relative z-10 text-4xl lg:text-5xl font-black text-white tracking-tight mb-8">Siap Memangkas <br/>Waste Makanan Anda?</h2>
                <p class="relative z-10 text-gray-400 text-lg max-w-xl mx-auto mb-10">Bergabung dengan MerchantPortal dan temukan kemudahan manajemen inventori cerdas FEFO pertama di kelasnya.</p>
                <div class="relative z-10 flex justify-center">
                    <Link
                        :href="route('register')"
                        class="text-gray-900 font-bold bg-white px-8 py-4 rounded-full hover:bg-gray-100 hover:scale-105 transition-all duration-300 shadow-xl flex items-center"
                    >
                        Buat Akun Gratis
                    </Link>
                </div>
            </div>

            <!-- Footer -->
            <footer class="py-8 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500 font-semibold uppercase tracking-wide gap-4 border-t border-surface-low">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span>&copy; 2026 MerchantPortal.</span>
                </div>
                <div class="flex space-x-8">
                    <span class="hover:text-gray-900 transition-colors cursor-pointer">Privacy</span>
                    <span class="hover:text-gray-900 transition-colors cursor-pointer">Terms</span>
                </div>
            </footer>
        </div>
    </div>
</template>

<style>
.bg-grid-pattern {
    background-image: radial-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px);
    background-size: 30px 30px;
}
.perspective-1000 {
    perspective: 1000px;
}
.code-scroll-animation {
    animation: slideUpCode 12s infinite alternate ease-in-out;
}
@keyframes slideUpCode {
    0%, 10% { transform: translateY(10px); }
    90%, 100% { transform: translateY(-30px); }
}
</style>
