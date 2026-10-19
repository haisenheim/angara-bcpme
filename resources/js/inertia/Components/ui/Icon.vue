<script setup>
defineProps({
    name: { type: String, required: true },
    filled: { type: Boolean, default: false },
});
</script>

<template>
    <span class="material-symbols-outlined" :class="{ 'fill-1': filled }" aria-hidden="true">{{ name }}</span>
</template>
