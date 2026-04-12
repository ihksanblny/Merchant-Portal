<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-[1.25rem] border-surface-low bg-surface hover:bg-white focus:bg-white focus:border-brand-container focus:ring-[3px] focus:ring-brand-container/20 transition-all duration-300 outline-none w-full shadow-sm px-4 py-3"
        v-model="model"
        ref="input"
    />
</template>
