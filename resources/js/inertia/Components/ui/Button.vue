<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    variant: { type: String, default: 'secondary' }, // primary | secondary | danger | success | ghost-danger
    icon: { type: String, default: null },
    iconRight: { type: String, default: null },
    block: { type: Boolean, default: false },
    type: { type: String, default: 'button' },
    href: { type: String, default: null },
    disabled: { type: Boolean, default: false },
});

const variants = {
    primary: 'bg-primary-container text-white shadow-xs hover:bg-black',
    secondary: 'border border-surface-container-highest bg-white text-on-surface shadow-2xs hover:bg-surface-container-low',
    danger: 'bg-error text-white shadow-2xs hover:bg-error-hover',
    success: 'bg-brand text-white shadow-2xs hover:bg-brand-hover',
    'ghost-danger': 'border border-error/30 bg-white text-error hover:bg-error-container/60',
};
</script>

<template>
    <component
        :is="href ? 'a' : 'button'"
        :href="href"
        :type="href ? null : type"
        :disabled="disabled"
        class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-xs font-semibold transition-colors disabled:cursor-not-allowed disabled:opacity-50"
        :class="[variants[variant] ?? variants.secondary, block ? 'w-full' : '']"
    >
        <Icon v-if="icon" :name="icon" class="text-base" />
        <span><slot /></span>
        <Icon v-if="iconRight" :name="iconRight" class="text-base" />
    </component>
</template>
