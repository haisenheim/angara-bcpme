<script setup>
/**
 * Formulaire générique d'action de workflow (affectation, avis, transmission, rejet, clôture).
 * La description de l'action (champs, boutons, URL) vient du serveur : aucune règle métier ici.
 */
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/ui/Modal.vue';
import Button from '@/Components/ui/Button.vue';
import RichTextEditor from '@/Components/ui/RichTextEditor.vue';

// Le parent remonte le composant pour chaque action (:key) : le formulaire repart de zéro.
const props = defineProps({
    action: { type: Object, required: true },
});
const emit = defineEmits(['close']);

const form = useForm(Object.fromEntries(props.action.fields.map((f) => [f.name, f.value ?? ''])));

const errorList = computed(() => Object.values(form.errors));

const hasFile = props.action.fields.some((f) => f.type === 'file');

const submit = (target) => {
    if (target.confirm && !window.confirm(target.confirm)) return;
    const method = (target.method ?? 'post').toLowerCase();
    // PUT / PATCH / DELETE simulés (_method) : compatibles avec les envois multipart.
    form.transform((data) => (method === 'post' ? data : { ...data, _method: method })).post(target.url, {
        preserveScroll: true,
        forceFormData: hasFile,
        onSuccess: () => emit('close'),
    });
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Modal show :title="action.title" :wide="action.wide" @close="emit('close')">
        <form class="space-y-4" @submit.prevent>
            <p v-if="action.description" class="text-xs leading-relaxed text-on-surface-variant">{{ action.description }}</p>

            <div v-if="errorList.length" class="rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">
                <p v-for="(message, i) in errorList" :key="i">{{ message }}</p>
            </div>

            <div v-for="field in action.fields" :key="field.name" class="space-y-1.5">
                <label :for="`f-${field.name}`" class="block text-xs font-semibold text-on-surface">
                    {{ field.label }}<span v-if="field.required" class="text-error"> *</span>
                </label>

                <select v-if="field.type === 'select'" :id="`f-${field.name}`" v-model="form[field.name]" :required="field.required" :class="inputClass">
                    <option value="" disabled>Sélectionner…</option>
                    <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>

                <div v-else-if="field.type === 'radio'" class="flex gap-2">
                    <label
                        v-for="opt in field.options"
                        :key="opt.value"
                        class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                        :class="form[field.name] === opt.value ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'"
                    >
                        <input v-model="form[field.name]" type="radio" :name="field.name" :value="opt.value" class="text-secondary focus:ring-secondary">
                        {{ opt.label }}
                    </label>
                </div>

                <RichTextEditor v-else-if="field.type === 'richtext'" v-model="form[field.name]" :min-height="action.wide ? '12rem' : '10rem'" />

                <input
                    v-else-if="['text', 'number', 'date', 'email', 'tel'].includes(field.type)"
                    :id="`f-${field.name}`" v-model="form[field.name]" :type="field.type" :required="field.required"
                    :step="field.type === 'number' ? 'any' : null" :class="inputClass"
                >

                <input
                    v-else-if="field.type === 'file'"
                    :id="`f-${field.name}`" type="file" :accept="field.accept ?? null" :required="field.required"
                    class="block w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-surface-container file:px-3 file:py-2 file:text-xs file:font-semibold"
                    @input="form[field.name] = $event.target.files[0]"
                >

                <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2 text-xs text-on-surface">
                    <input :id="`f-${field.name}`" v-model="form[field.name]" type="checkbox" :true-value="1" :false-value="0" class="rounded text-secondary focus:ring-secondary">
                    {{ field.hint ?? 'Oui' }}
                </label>

                <textarea v-else :id="`f-${field.name}`" v-model="form[field.name]" rows="4" :required="field.required" :class="inputClass" />

                <p v-if="form.errors[field.name]" class="text-xs text-error">{{ form.errors[field.name] }}</p>
            </div>
        </form>

        <template #footer>
            <Button @click="emit('close')">Annuler</Button>
            <Button
                v-for="target in action.submits"
                :key="target.url"
                :variant="target.variant"
                :disabled="form.processing"
                @click="submit(target)"
            >
                {{ target.label }}
            </Button>
        </template>
    </Modal>
</template>
