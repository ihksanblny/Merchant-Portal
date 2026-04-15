<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex h-screen bg-surface overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-72 bg-surface-lowest shadow-ambient flex-shrink-0 hidden md:flex flex-col z-20 rounded-r-[3rem] my-4 ml-4">
            <!-- Logo -->
            <div class="h-32 flex items-center px-10">
                <Link :href="route('dashboard')">
                    <div class="text-[1.75rem] font-black tracking-tighter text-brand-container leading-none">
                        Merchant
                        <br/><span class="text-brand">Portal.</span>
                    </div>
                </Link>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-6 space-y-3 mt-4">
                <Link
                    :href="route('dashboard')"
                    :class="[route().current('dashboard') ? 'bg-surface px-6 py-4 rounded-full font-bold text-brand-container shadow-sm' : 'px-6 py-4 rounded-full font-medium text-gray-500 hover:bg-surface-low hover:text-gray-900 transition-all duration-300', 'flex items-center space-x-4 w-full']"
                >
                    <!-- Dashboard Icon -->
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-sm">Dashboard</span>
                </Link>

                <Link
                    :href="route('quick-log.index')"
                    :class="[route().current('quick-log.*') ? 'bg-surface px-6 py-4 rounded-full font-bold text-brand-container shadow-sm' : 'px-6 py-4 rounded-full font-medium text-gray-500 hover:bg-surface-low hover:text-gray-900 transition-all duration-300', 'flex items-center space-x-4 w-full']"
                >
                    <!-- Quick Log Icon -->
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span class="text-sm">Quick Log</span>
                </Link>

                <Link
                    :href="route('products.index')"
                    :class="[route().current('products.*') ? 'bg-surface px-6 py-4 rounded-full font-bold text-brand-container shadow-sm' : 'px-6 py-4 rounded-full font-medium text-gray-500 hover:bg-surface-low hover:text-gray-900 transition-all duration-300', 'flex items-center space-x-4 w-full']"
                >
                    <!-- Products Icon -->
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="text-sm">Produk</span>
                </Link>
            </nav>

            <!-- User Profile & Settings -->
            <div class="p-6">
                <!-- Dropdown replacement: we just use a pill style container -->
                <div class="bg-surface rounded-[2rem] p-5 flex flex-col space-y-4 shadow-sm border border-surface-low">
                    <div class="flex items-center space-x-3 pb-4 border-b border-gray-200 border-dashed">
                        <div class="h-10 w-10 rounded-full bg-brand-container/10 flex items-center justify-center text-brand font-black text-lg shadow-inner">
                            {{ $page.props.auth.user.name.charAt(0) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                        </div>
                    </div>
                    
                    <Link :href="route('profile.edit')" class="text-sm font-semibold text-gray-500 hover:text-brand-container transition flex flex-row items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Kelola Akun
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="text-sm font-semibold text-red-400 hover:text-red-600 transition flex flex-row items-center w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-surface relative">
            
            <!-- Mobile Header (Visible only on md-) -->
            <div class="md:hidden bg-surface-lowest shadow-sm h-16 flex items-center justify-between px-6 z-20">
                <div class="text-xl font-black tracking-tighter text-brand-container">
                    Merchant<span class="text-brand">Portal.</span>
                </div>
                <!-- A simple settings link on mobile to logout/profile -->
                <Link :href="route('profile.edit')" class="text-brand font-bold p-2 bg-surface rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </Link>
            </div>

            <!-- Page Padding + Scroll Area -->
            <div class="flex-1 overflow-y-auto w-full pt-12 pb-32 px-6 lg:px-16 relative">
                
                <!-- Page Heading Custom Slot -->
                <header v-if="$slots.header" class="mb-12 w-full max-w-6xl mx-auto">
                    <slot name="header" />
                </header>

                <!-- Page Content -->
                <div class="w-full max-w-6xl mx-auto">
                    <slot />
                </div>
                
            </div>
        </main>
    </div>
</template>