<script setup>
/**
 * Zones d'analyse (grille d'analyse critique, rubriques de l'analyste financier) en lecture,
 * avec saisie optionnelle zone par zone quand le serveur fournit `zone.edit` (URL + champs cachés).
 */
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Modal from '@/Components/ui/Modal.vue';
import Button from '@/Components/ui/Button.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import RichTextEditor from '@/Components/ui/RichTextEditor.vue';

defineProps({
    zones: { type: Array, default: () => [] },
    emptyLabel: { type: String, default: 'Non renseigné.' },
});

const editing = ref(null);
const form = useForm({ content: '' });

const open = (zone) => {
    form.clearErrors();
    form.content = zone.html ?? '';
    editing.value = zone;
};

const submit = () => {
    const zone = editing.value;
    form.transform((data) => ({ ...zone.edit.fields, content: data.content }))
        .post(zone.edit.url, { preserveScroll: true, onSuccess: () => (editing.value = null) });
};
</script>

<template>
    <div class="space-y-3">
        <article
            v-for="zone in zones"
            :key="zone.key"
            class="overflow-hidden rounded-lg border bg-surface-container-lowest"
            :class="zone.highlight ? 'border-secondary/40' : 'border-surface-container-highest'"
        >
            <header class="flex items-center justify-between gap-2 border-b border-surface-container-highest bg-surface-container-low/60 px-3 py-2">
                <h3 class="text-xs font-semibold text-on-surface">{{ zone.label }}</h3>
                <button v-if="zone.edit" type="button" class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11px] font-semibold text-secondary hover:bg-secondary/10" @click="open(zone)">
                    <Icon name="edit" class="text-sm" /> {{ zone.filled ? 'Modifier' : 'Rédiger' }}
                </button>
            </header>
            <div class="px-3 py-2.5 text-xs">
                <SafeHtml v-if="zone.filled" :html="zone.html" class="leading-relaxed text-on-surface" />
                <p v-else class="italic text-on-surface-variant">{{ emptyLabel }}</p>
            </div>
        </article>

        <Modal :show="!!editing" :title="editing?.edit?.title ?? ''" wide @close="editing = null">
            <div class="space-y-3">
                <p v-if="editing?.edit?.description" class="whitespace-pre-line text-xs leading-relaxed text-on-surface-variant">{{ editing.edit.description }}</p>
                <RichTextEditor v-model="form.content" min-height="14rem" />
                <p v-for="(message, key) in form.errors" :key="key" class="text-xs text-error">{{ message }}</p>
            </div>
            <template #footer>
                <Button @click="editing = null">Annuler</Button>
                <Button variant="primary" :disabled="form.processing" @click="submit">Enregistrer</Button>
            </template>
        </Modal>
    </div>
</template>
