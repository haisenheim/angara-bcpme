<script setup>
/**
 * Sélection dans une longue liste avec recherche (produits, appuis, communes…).
 * multiple = true : v-model est un tableau de valeurs ; sinon une valeur unique ('' = aucune).
 */
import { computed, ref } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    modelValue: { type: [Array, String, Number], default: () => [] },
    options: { type: Array, default: () => [] },
    multiple: { type: Boolean, default: false },
    placeholder: { type: String, default: 'Rechercher…' },
    emptyLabel: { type: String, default: 'Aucun élément.' },
});
const emit = defineEmits(['update:modelValue']);

const query = ref('');
const open = ref(false);
const norm = (v) => String(v ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

const selectedValues = computed(() => (props.multiple ? (props.modelValue ?? []).map(String) : props.modelValue === '' || props.modelValue === null ? [] : [String(props.modelValue)]));
const selectedOptions = computed(() => props.options.filter((o) => selectedValues.value.includes(String(o.value))));
const filtered = computed(() => {
    const q = norm(query.value);
    const list = q ? props.options.filter((o) => norm(o.label).includes(q)) : props.options;
    return list.slice(0, 200);
});

const toggle = (opt) => {
    const v = String(opt.value);
    if (props.multiple) {
        const current = (props.modelValue ?? []).map(String);
        emit('update:modelValue', current.includes(v) ? current.filter((x) => x !== v) : [...current, v]);
    } else {
        emit('update:modelValue', selectedValues.value.includes(v) ? '' : v);
        open.value = false;
        query.value = '';
    }
};
const remove = (opt) => toggle(opt);
</script>

<template>
    <div class="relative">
        <div v-if="selectedOptions.length" class="mb-2 flex flex-wrap gap-1.5">
            <span v-for="o in selectedOptions" :key="o.value" class="inline-flex items-center gap-1 rounded border border-surface-container-highest bg-surface-container-low px-2 py-0.5 text-xs text-on-surface">
                {{ o.label }}
                <button type="button" class="text-on-surface-variant hover:text-error" :aria-label="`Retirer ${o.label}`" @click="remove(o)"><Icon name="close" class="text-sm" /></button>
            </span>
        </div>
        <div class="relative">
            <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
            <input
                v-model="query" type="search" :placeholder="placeholder"
                class="w-full rounded-lg border-surface-container-highest pl-8 text-sm focus:border-secondary focus:ring-secondary"
                @focus="open = true"
            >
        </div>
        <div v-if="open" class="absolute z-30 mt-1 max-h-64 w-full overflow-y-auto rounded-lg border border-surface-container-highest bg-white py-1 shadow-lg" @mouseleave="open = false">
            <button
                v-for="o in filtered" :key="o.value" type="button"
                class="flex w-full items-center gap-2 px-3 py-1.5 text-left text-xs hover:bg-surface-container-low"
                :class="selectedValues.includes(String(o.value)) ? 'font-semibold text-secondary' : 'text-on-surface'"
                @click="toggle(o)"
            >
                <Icon :name="selectedValues.includes(String(o.value)) ? 'check_box' : (multiple ? 'check_box_outline_blank' : 'radio_button_unchecked')" class="text-base" />
                {{ o.label }}
            </button>
            <p v-if="!filtered.length" class="px-3 py-2 text-xs text-on-surface-variant">{{ emptyLabel }}</p>
        </div>
    </div>
</template>
