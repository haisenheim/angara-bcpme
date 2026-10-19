<script setup>
defineProps({
    title: { type: String, default: null },
    padded: { type: Boolean, default: true },
});
</script>

<template>
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs" :class="padded ? 'p-5' : ''">
        <header v-if="title || $slots.actions" class="mb-4 flex items-center justify-between gap-3" :class="padded ? '' : 'px-5 pt-5'">
            <h2 v-if="title" class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant">{{ title }}</h2>
            <slot name="actions" />
        </header>
        <slot />
    </section>
</template>
