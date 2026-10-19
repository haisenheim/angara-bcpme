<script setup>
/**
 * Champs de formulaire décrits par le serveur (même contrat que ActionDialog), pour les formulaires en page.
 * Extensions : type `password`, `readonly`, `help` (texte d'aide), `width` (`full` | `half` | `third`),
 * `showIf` ({ field, value }) pour afficher un champ selon la valeur d'un autre.
 */
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    form: { type: Object, required: true },
    fields: { type: Array, required: true },
});

const visible = (field) => !field.showIf || String(props.form[field.showIf.field]) === String(field.showIf.value);
const widthClass = (field) => ({ full: 'md:col-span-6', half: 'md:col-span-3', third: 'md:col-span-2' }[field.width ?? 'full'] ?? 'md:col-span-6');
const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-6">
        <template v-for="field in fields" :key="field.name">
            <div v-if="field.type === 'section'" class="md:col-span-6 border-b border-surface-container-highest pb-1 pt-2 text-xs font-semibold uppercase tracking-wider text-on-surface-variant">
                {{ field.label }}
            </div>
            <div v-else-if="visible(field)" class="space-y-1.5" :class="widthClass(field)">
                <label v-if="field.type !== 'checkbox'" :for="`ff-${field.name}`" class="block text-xs font-semibold text-on-surface">
                    {{ field.label }}<span v-if="field.required" class="text-error"> *</span>
                </label>

                <select v-if="field.type === 'select'" :id="`ff-${field.name}`" v-model="form[field.name]" :required="field.required" :class="inputClass">
                    <option v-if="field.placeholder !== false" value="" :disabled="field.required">{{ field.placeholder ?? 'Sélectionner…' }}</option>
                    <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>

                <div v-else-if="field.type === 'radio'" class="flex flex-wrap gap-2">
                    <label
                        v-for="opt in field.options"
                        :key="opt.value"
                        class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                        :class="String(form[field.name]) === String(opt.value) ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'"
                    >
                        <input v-model="form[field.name]" type="radio" :name="field.name" :value="opt.value" class="text-secondary focus:ring-secondary">
                        {{ opt.label }}
                    </label>
                </div>

                <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2 pt-5 text-xs font-semibold text-on-surface">
                    <input :id="`ff-${field.name}`" v-model="form[field.name]" type="checkbox" :true-value="1" :false-value="0" class="rounded text-secondary focus:ring-secondary">
                    {{ field.label }}
                </label>

                <textarea v-else-if="field.type === 'textarea'" :id="`ff-${field.name}`" v-model="form[field.name]" rows="4" :required="field.required" :class="inputClass" />

                <input
                    v-else-if="field.type === 'file'"
                    :id="`ff-${field.name}`" type="file" :accept="field.accept ?? null" :required="field.required"
                    class="block w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-surface-container file:px-3 file:py-2 file:text-xs file:font-semibold"
                    @input="form[field.name] = $event.target.files[0]"
                >

                <input
                    v-else-if="field.readonly"
                    :id="`ff-${field.name}`" :value="field.value" type="text" disabled
                    class="w-full rounded-lg border-surface-container-highest bg-surface-container-low text-sm text-on-surface-variant"
                >

                <input
                    v-else
                    :id="`ff-${field.name}`" v-model="form[field.name]" :type="field.type" :required="field.required"
                    :min="field.min ?? null" :max="field.max ?? null" :maxlength="field.maxlength ?? null"
                    :step="field.type === 'number' ? (field.step ?? 'any') : null"
                    :autocomplete="field.type === 'password' ? 'new-password' : null"
                    :class="inputClass"
                >

                <p v-if="field.help" class="flex items-start gap-1 text-[11px] text-on-surface-variant"><Icon name="info" class="text-sm" />{{ field.help }}</p>
                <p v-if="form.errors[field.name]" class="text-xs text-error">{{ form.errors[field.name] }}</p>
            </div>
        </template>
    </div>
</template>
