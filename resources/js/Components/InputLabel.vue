<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-[13px] font-bold text-gray-600 mb-1 ml-1 tracking-wide">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
