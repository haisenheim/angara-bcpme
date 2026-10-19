<script setup>
/**
 * Formulaire décrit par le serveur (étapes, champs, URL du POST existant). Aucune règle métier ici.
 *
 * schema = {
 *   action, method?: 'post'|'put', submitLabel?, cancelHref?, hidden?: { name: value },
 *   steps: [{ key, title, description?, fields: [field] }]
 * }
 * field = { name, label, type, value?, required?, placeholder?, help?, options?: [{ value, label, parent? }],
 *           dependsOn?: nom du champ parent (filtre options.parent), virtual?: true (non envoyé),
 *           serialize?: 'csv', width?: 'full'|'half'|'third', suggestions?: [..], min?, max?, step? }
 * types : text, email, tel, number, date, textarea, select, radio, choice (liste longue avec recherche),
 *         multichoice (sélection multiple avec recherche), checkbox.
 */
import { computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import ChoiceList from '@/Components/forms/ChoiceList.vue';

const props = defineProps({
    schema: { type: Object, required: true },
    // Champs supplémentaires fusionnés au moment de l'envoi (ex. mode de rattachement d'un tiers).
    extra: { type: Object, default: () => ({}) },
    // Étapes affichées en accordéon empilé plutôt qu'en assistant.
    stacked: { type: Boolean, default: false },
});

const allFields = computed(() => props.schema.steps.flatMap((s) => s.fields));
const initial = {};
props.schema.steps.forEach((s) => s.fields.forEach((f) => {
    initial[f.name] = f.value ?? (f.type === 'multichoice' ? [] : f.type === 'checkbox' ? 0 : '');
}));
const form = useForm(initial);

const current = ref(0);
const steps = computed(() => props.schema.steps);
const isLast = computed(() => current.value >= steps.value.length - 1);

// Listes en cascade : une valeur qui n'appartient plus au parent est effacée.
const optionsFor = (field) => {
    if (!field.dependsOn) return field.options ?? [];
    const parent = form[field.dependsOn];
    if (parent === '' || parent === null || parent === undefined) return field.options ?? [];
    return (field.options ?? []).filter((o) => String(o.parent) === String(parent));
};
allFields.value.filter((f) => f.dependsOn).forEach((f) => {
    watch(() => form[f.dependsOn], () => {
        if (form[f.name] !== '' && !optionsFor(f).some((o) => String(o.value) === String(form[f.name]))) form[f.name] = '';
    });
});

const errorList = computed(() => Object.values(form.errors));
const stepHasError = (step) => step.fields.some((f) => form.errors[f.name] || Object.keys(form.errors).some((k) => k.startsWith(`${f.name}.`)));

const isEmpty = (v) => v === '' || v === null || v === undefined || (Array.isArray(v) && !v.length);

const submit = () => {
    // Champs obligatoires contrôlés dans le navigateur (équivalent de l'attribut HTML required des écrans Blade).
    if (props.schema.enforceRequired) {
        form.clearErrors();
        const missing = allFields.value.filter((f) => f.required && isEmpty(form[f.name]));
        if (missing.length) {
            missing.forEach((f) => form.setError(f.name, `Le champ « ${f.label} » est obligatoire.`));
            const idx = steps.value.findIndex(stepHasError);
            if (idx >= 0) current.value = idx;
            return;
        }
    }
    const virtual = new Set(allFields.value.filter((f) => f.virtual).map((f) => f.name));
    const csv = new Set(allFields.value.filter((f) => f.serialize === 'csv').map((f) => f.name));
    const method = (props.schema.method ?? 'post').toLowerCase();
    form.transform((data) => {
        const out = { ...(props.schema.hidden ?? {}), ...props.extra };
        Object.entries(data).forEach(([k, v]) => {
            if (virtual.has(k)) return;
            out[k] = csv.has(k) && Array.isArray(v) ? v.join(',') : v;
        });
        if (method !== 'post') out._method = method;
        return out;
    }).post(props.schema.action, {
        preserveScroll: true,
        onError: () => {
            const idx = steps.value.findIndex(stepHasError);
            if (idx >= 0) current.value = idx;
        },
    });
};

const widthClass = (f) => ({ full: 'md:col-span-6', half: 'md:col-span-3', third: 'md:col-span-2' }[f.width ?? 'third'] ?? 'md:col-span-2');
const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
const visibleSteps = computed(() => (props.stacked ? steps.value.map((s, i) => i) : [current.value]));

defineExpose({ form, submit });
</script>

<template>
    <form class="space-y-5" novalidate @submit.prevent="submit">
        <div v-if="errorList.length" class="rounded-xl border border-error/30 bg-error-container p-4 text-xs text-on-error-container">
            <p class="mb-1 font-semibold">Le formulaire contient des erreurs :</p>
            <ul class="list-disc space-y-0.5 pl-5">
                <li v-for="(message, i) in errorList" :key="i">{{ message }}</li>
            </ul>
        </div>

        <!-- Étapes -->
        <nav v-if="!stacked && steps.length > 1" class="grid grid-cols-2 gap-2 md:grid-cols-3 xl:grid-cols-6">
            <button
                v-for="(s, i) in steps" :key="s.key" type="button"
                class="flex items-center gap-2.5 rounded-lg border px-3 py-2.5 text-left text-xs transition-colors"
                :class="i === current ? 'border-secondary bg-secondary/10 font-bold text-on-surface' : 'border-surface-container-highest bg-surface-container-lowest text-on-surface-variant hover:text-on-surface'"
                @click="current = i"
            >
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-bold" :class="stepHasError(s) ? 'bg-error text-white' : i === current ? 'bg-secondary text-white' : 'bg-surface-container-high text-on-surface-variant'">{{ i + 1 }}</span>
                <span class="truncate">{{ s.title }}</span>
            </button>
        </nav>

        <section
            v-for="i in visibleSteps" :key="steps[i].key"
            class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs"
        >
            <header class="mb-4">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant">{{ steps[i].title }}</h2>
                <p v-if="steps[i].description" class="mt-1 text-xs text-on-surface-variant">{{ steps[i].description }}</p>
            </header>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
                <div v-for="field in steps[i].fields" :key="field.name" class="space-y-1.5" :class="widthClass(field)">
                    <label v-if="field.type !== 'checkbox'" :for="`sf-${field.name}`" class="block text-xs font-semibold text-on-surface">
                        {{ field.label }}<span v-if="field.required" class="text-error"> *</span>
                    </label>

                    <select v-if="field.type === 'select'" :id="`sf-${field.name}`" v-model="form[field.name]" :class="inputClass">
                        <option value="">{{ field.placeholder ?? 'Non renseigné' }}</option>
                        <option v-for="opt in optionsFor(field)" :key="opt.value" :value="String(opt.value)">{{ opt.label }}</option>
                    </select>

                    <div v-else-if="field.type === 'radio'" class="flex flex-wrap gap-2">
                        <label
                            v-for="opt in field.options" :key="opt.value"
                            class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                            :class="String(form[field.name]) === String(opt.value) ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'"
                        >
                            <input v-model="form[field.name]" type="radio" :name="field.name" :value="String(opt.value)" class="text-secondary focus:ring-secondary">
                            {{ opt.label }}
                        </label>
                    </div>

                    <ChoiceList v-else-if="field.type === 'choice'" v-model="form[field.name]" :options="optionsFor(field)" :placeholder="field.placeholder ?? 'Rechercher…'" />
                    <ChoiceList v-else-if="field.type === 'multichoice'" v-model="form[field.name]" multiple :options="optionsFor(field)" :placeholder="field.placeholder ?? 'Rechercher…'" />

                    <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2 pt-5 text-xs font-semibold text-on-surface">
                        <input :id="`sf-${field.name}`" v-model="form[field.name]" type="checkbox" :true-value="1" :false-value="0" class="rounded text-secondary focus:ring-secondary">
                        {{ field.label }}
                    </label>

                    <textarea v-else-if="field.type === 'textarea'" :id="`sf-${field.name}`" v-model="form[field.name]" rows="3" :placeholder="field.placeholder" :class="inputClass" />

                    <template v-else>
                        <input
                            :id="`sf-${field.name}`" v-model="form[field.name]" :type="field.type ?? 'text'" :placeholder="field.placeholder"
                            :min="field.min" :max="field.max" :step="field.type === 'number' ? (field.step ?? 'any') : null"
                            :list="field.suggestions ? `sf-${field.name}-list` : null" :class="inputClass"
                        >
                        <datalist v-if="field.suggestions" :id="`sf-${field.name}-list`">
                            <option v-for="s in field.suggestions" :key="s" :value="s" />
                        </datalist>
                    </template>

                    <p v-if="field.help" class="text-[11px] text-on-surface-variant">{{ field.help }}</p>
                    <p v-if="form.errors[field.name]" class="text-xs text-error">{{ form.errors[field.name] }}</p>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <Button v-if="schema.cancelHref" :href="schema.cancelHref" icon="arrow_back">Annuler</Button>
            <span v-else></span>
            <div class="flex items-center gap-2">
                <template v-if="!stacked && steps.length > 1">
                    <Button :disabled="current === 0" icon="chevron_left" @click="current--">Précédent</Button>
                    <Button v-if="!isLast" icon-right="chevron_right" @click="current++">Suivant</Button>
                </template>
                <Button type="submit" variant="primary" icon="save" :disabled="form.processing">{{ schema.submitLabel ?? 'Enregistrer' }}</Button>
            </div>
        </div>
        <p v-if="form.processing" class="flex items-center gap-2 text-xs text-on-surface-variant"><Icon name="progress_activity" class="animate-spin text-base" /> Enregistrement…</p>
    </form>
</template>
