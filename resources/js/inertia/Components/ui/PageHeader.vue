<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: null },
});
</script>

<template>
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-on-surface">{{ title }}</h1>
            <p v-if="subtitle" class="mt-1 text-xs text-on-surface-variant">{{ subtitle }}</p>
        </div>
        <div v-if="$slots.actions" class="flex shrink-0 flex-wrap items-center gap-2.5">
            <slot name="actions" />
        </div>
    </div>
</template>
