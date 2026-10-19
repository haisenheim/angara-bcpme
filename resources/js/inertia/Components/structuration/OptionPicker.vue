<script setup>
/**
 * Sélection multiple avec recherche (remplace les <select multiple> Blade) : liste de cases à cocher
 * filtrable, avec les éléments retenus affichés en tête sous forme d'étiquettes.
 */
import { computed, ref } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    options: { type: Array, default: () => [] }, // [{ value, label, hint? }]
    excluded: { type: Array, default: () => [] }, // valeurs non proposées (ex. produit principal)
    placeholder: { type: String, default: 'Rechercher…' },
});
const emit = defineEmits(['update:modelValue']);

const query = ref('');
const normalize = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

const available = computed(() => props.options.filter((o) => !props.excluded.includes(o.value)));
const filtered = computed(() => {
    const q = normalize(query.value);
    return q ? available.value.filter((o) => normalize(`${o.label} ${o.hint ?? ''}`).includes(q)) : available.value;
});
const selected = computed(() => available.value.filter((o) => props.modelValue.includes(o.value)));

const toggle = (value) => {
    emit('update:modelValue', props.modelValue.includes(value) ? props.modelValue.filter((v) => v !== value) : [...props.modelValue, value]);
};
</script>

<template>
    <div class="rounded-lg border border-surface-container-highest">
        <div v-if="selected.length" class="flex flex-wrap gap-1.5 border-b border-surface-container-highest p-2.5">
            <button
                v-for="o in selected" :key="o.value" type="button"
                class="inline-flex items-center gap-1 rounded border border-secondary/30 bg-secondary/10 px-2 py-0.5 text-xs font-semibold text-secondary hover:bg-secondary/20"
                :title="`Retirer ${o.label}`" @click="toggle(o.value)"
            >
                {{ o.label }} <Icon name="close" class="text-sm" />
            </button>
        </div>
        <div class="relative border-b border-surface-container-highest">
            <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
            <input v-model="query" type="search" :placeholder="placeholder" class="w-full border-0 bg-transparent py-2 pl-8 text-xs focus:ring-0">
        </div>
        <ul class="max-h-64 overflow-y-auto py-1 text-xs">
            <li v-for="o in filtered" :key="o.value">
                <label class="flex cursor-pointer items-center gap-2 px-3 py-1.5 hover:bg-surface-container-low">
                    <input type="checkbox" :checked="modelValue.includes(o.value)" class="rounded text-secondary focus:ring-secondary" @change="toggle(o.value)">
                    <span class="text-on-surface">{{ o.label }}</span>
                    <span v-if="o.hint" class="text-on-surface-variant">— {{ o.hint }}</span>
                </label>
            </li>
            <li v-if="!filtered.length" class="px-3 py-3 text-center text-on-surface-variant">Aucun résultat.</li>
        </ul>
    </div>
</template>
