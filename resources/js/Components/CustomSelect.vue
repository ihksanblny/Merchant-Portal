<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    options: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Pilih opsi...'
    }
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const selectRef = ref(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const selectOption = (optionValue) => {
    emit('update:modelValue', optionValue);
    isOpen.value = false;
};

const handleClickOutside = (event) => {
    if (selectRef.value && !selectRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative" ref="selectRef">
        <!-- Select Trigger -->
        <div 
            @click="toggleDropdown"
            class="mt-1 flex w-full items-center justify-between rounded-[1.25rem] border border-surface-low bg-surface px-5 py-3.5 cursor-pointer shadow-sm transition-all duration-300 hover:bg-white"
            :class="isOpen ? 'bg-white ring-4 ring-brand-container/20 border-brand-container' : ''"
        >
            <span :class="modelValue ? 'text-gray-900 font-semibold' : 'text-gray-400 font-medium'">
                {{ modelValue ? options.find(o => o.id === modelValue)?.name : placeholder }}
            </span>
            <!-- Custom Arrow -->
            <svg 
                class="h-5 w-5 text-gray-500 transition-transform duration-300"
                :class="isOpen ? 'rotate-180 text-brand-container' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0 pb-4"
            enter-to-class="transform scale-100 opacity-100 pb-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div 
                v-if="isOpen" 
                class="absolute z-50 mt-2 w-full rounded-[1.25rem] bg-white shadow-ambient border border-surface-low overflow-hidden"
            >
                <ul class="max-h-60 overflow-y-auto py-2">
                    <li 
                        v-for="option in options" 
                        :key="option.id"
                        @click="selectOption(option.id)"
                        class="cursor-pointer px-5 py-3 text-sm font-medium transition-colors hover:bg-brand-container/5 hover:text-brand-container"
                        :class="modelValue === option.id ? 'bg-brand-container/10 text-brand-container font-bold' : 'text-gray-700'"
                    >
                        {{ option.name }}
                    </li>
                    <li v-if="!options.length" class="px-5 py-3 text-sm text-gray-500 cursor-not-allowed">
                        Tidak ada pilihan
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>
