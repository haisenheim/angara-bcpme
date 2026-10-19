<script setup>
/**
 * Sélection multiple avec recherche (remplace les comboTree jQuery des formulaires Blade).
 * Options : [{ value, label, depth?, hint? }] ; depth sert à indenter une arborescence aplatie.
 */
import { computed, ref } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    options: { type: Array, default: () => [] },
    modelValue: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Rechercher…' },
    emptyLabel: { type: String, default: 'Aucune sélection.' },
});
const emit = defineEmits(['update:modelValue']);

const query = ref('');
const normalize = (s) => (s ?? '').toString().toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');

const selectedSet = computed(() => new Set(props.modelValue.map(String)));
const selected = computed(() => props.options.filter((o) => selectedSet.value.has(String(o.value))));
const filtered = computed(() => {
    const q = normalize(query.value.trim());
    if (!q) return props.options;
    return props.options.filter((o) => normalize(`${o.label} ${o.hint ?? ''}`).includes(q));
});

const toggle = (value) => {
    const key = String(value);
    const next = selectedSet.value.has(key)
        ? props.modelValue.filter((v) => String(v) !== key)
        : [...props.modelValue, value];
    emit('update:modelValue', next);
};
</script>

<template>
    <div class="space-y-3">
        <div class="relative">
            <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
            <input v-model="query" type="search" :placeholder="placeholder" class="w-full rounded-lg border-surface-container-highest bg-surface-container-low pl-8 text-xs focus:border-secondary focus:ring-secondary">
        </div>
        <ul class="max-h-60 overflow-y-auto rounded-lg border border-surface-container-highest text-xs">
            <li v-for="opt in filtered" :key="opt.value">
                <label class="flex cursor-pointer items-center gap-2 px-3 py-1.5 hover:bg-surface-container-low" :style="{ paddingLeft: `${0.75 + (query ? 0 : (opt.depth ?? 0)) * 1.1}rem` }">
                    <input type="checkbox" :checked="selectedSet.has(String(opt.value))" class="rounded text-secondary focus:ring-secondary" @change="toggle(opt.value)">
                    <span :class="(opt.depth ?? 0) === 0 ? 'font-semibold text-on-surface' : 'text-on-surface'">{{ opt.label }}</span>
                    <span v-if="opt.hint" class="text-[11px] text-on-surface-variant">({{ opt.hint }})</span>
                </label>
            </li>
            <li v-if="!filtered.length" class="px-3 py-4 text-center text-on-surface-variant">Aucun résultat.</li>
        </ul>
        <div>
            <div class="mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Sélection ({{ selected.length }})</div>
            <div v-if="selected.length" class="flex flex-wrap gap-1.5">
                <span v-for="opt in selected" :key="opt.value" class="inline-flex items-center gap-1 rounded-full border border-secondary/30 bg-brand-subtle px-2.5 py-1 text-[11px] font-medium text-on-surface">
                    {{ opt.label }}
                    <button type="button" class="rounded-full text-on-surface-variant hover:text-error" :aria-label="`Retirer ${opt.label}`" @click="toggle(opt.value)">
                        <Icon name="close" class="text-sm" />
                    </button>
                </span>
            </div>
            <p v-else class="text-xs text-on-surface-variant">{{ emptyLabel }}</p>
        </div>
    </div>
</template>
