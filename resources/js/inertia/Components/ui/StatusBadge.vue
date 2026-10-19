<script setup>
defineProps({
    variant: { type: String, default: 'neutral' }, // neutral | info | success | danger | warning
    pulse: { type: Boolean, default: false },
});

const styles = {
    neutral: ['bg-surface-container-low text-on-surface-variant border-surface-container-highest', 'bg-on-surface-variant'],
    info: ['bg-blue-50 text-blue-700 border-blue-200', 'bg-blue-600'],
    success: ['bg-emerald-50 text-emerald-800 border-emerald-200', 'bg-emerald-600'],
    danger: ['bg-error-container text-error border-error/30', 'bg-error'],
    warning: ['bg-amber-50 text-amber-800 border-amber-200', 'bg-amber-600'],
};
</script>

<template>
    <span class="inline-flex items-center gap-1.5 rounded border px-2.5 py-0.5 text-xs font-medium" :class="(styles[variant] ?? styles.neutral)[0]">
        <span v-if="pulse" class="h-1.5 w-1.5 animate-pulse rounded-full" :class="(styles[variant] ?? styles.neutral)[1]"></span>
        <slot />
    </span>
</template>
