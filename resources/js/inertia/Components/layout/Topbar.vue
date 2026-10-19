<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    auth: { type: Object, default: null },
    breadcrumb: { type: Array, default: () => [] },
});
defineEmits(['toggle-sidebar']);
</script>

<template>
    <header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-surface-container-highest bg-surface-container-lowest px-8 shadow-xs">
        <div class="flex min-w-0 items-center gap-4">
            <button type="button" class="rounded-full p-2 text-on-surface-variant hover:bg-surface-container-low" title="Réduire le menu" @click="$emit('toggle-sidebar')">
                <Icon name="menu" />
            </button>
            <nav class="flex min-w-0 items-center gap-2 text-xs font-medium text-on-surface-variant" aria-label="Fil d'Ariane">
                <template v-for="(crumb, i) in breadcrumb" :key="i">
                    <Icon v-if="i > 0" name="chevron_right" class="text-sm" />
                    <a v-if="crumb.href && i < breadcrumb.length - 1" :href="crumb.href" class="truncate text-on-surface-variant/70 hover:text-on-surface">{{ crumb.label }}</a>
                    <span v-else class="truncate" :class="i === breadcrumb.length - 1 ? 'font-semibold text-on-surface' : 'text-on-surface-variant/70'">{{ crumb.label }}</span>
                </template>
            </nav>
        </div>

        <div v-if="auth" class="flex items-center gap-4">
            <div class="h-6 w-px bg-surface-container-highest"></div>
            <div class="flex items-center gap-3 pl-1">
                <div class="text-right">
                    <div class="text-xs font-semibold leading-snug text-on-surface">{{ auth.user.name }}</div>
                    <div class="text-[11px] leading-none text-on-surface-variant">
                        {{ auth.profile.label }}<template v-if="auth.profile.agence"> — {{ auth.profile.agence }}</template>
                    </div>
                </div>
                <div class="flex h-9 w-9 items-center justify-center rounded-full border border-outline-variant/40 bg-surface-container-high text-xs font-bold text-on-surface shadow-xs">
                    {{ auth.user.initials }}
                </div>
            </div>
        </div>
    </header>
</template>
