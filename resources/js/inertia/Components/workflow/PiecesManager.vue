<script setup>
/**
 * Pièces jointes d'un dossier : liste, dépôt, remplacement et suppression.
 * Les droits (auteur uniquement, dossier non clos) sont calculés côté serveur : seules les URL
 * autorisées sont fournies.
 */
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Modal from '@/Components/ui/Modal.vue';

const props = defineProps({
    pieces: { type: Array, default: () => [] },
    types: { type: Array, default: () => [] },
    storeUrl: { type: String, default: null },
    // Dossier clôturé : dépôt autorisé à titre exceptionnel (audit interne, engagements).
    afterClosure: { type: Boolean, default: false },
});

const editing = ref(null); // null | { mode: 'create' } | { mode: 'update', piece }
const form = useForm({ type_id: '', fichier: null });

const open = (mode, piece = null) => {
    form.reset();
    form.clearErrors();
    form.type_id = piece?.typeId ?? '';
    editing.value = { mode, piece };
};

const submit = () => {
    const isUpdate = editing.value.mode === 'update';
    const url = isUpdate ? editing.value.piece.updateUrl : props.storeUrl;
    // PUT multipart non supporté par PHP : méthode simulée.
    form.transform((data) => (isUpdate ? { ...data, _method: 'put' } : data)).post(url, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => (editing.value = null),
    });
};

const destroy = (piece) => {
    if (!window.confirm(`Supprimer la pièce « ${piece.name} » ?`)) return;
    router.delete(piece.destroyUrl, { preserveScroll: true });
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs">
        <header class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-xs font-semibold uppercase tracking-wider text-on-surface-variant">Pièces justificatives</h2>
            <Button v-if="storeUrl && types.length" icon="upload_file" @click="open('create')">Déposer une pièce</Button>
        </header>

        <div v-if="afterClosure" class="mb-4 flex items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
            <Icon name="lock_clock" class="text-base text-amber-700" />
            <span>Dossier clôturé : vous pouvez encore <strong>ajouter</strong> des pièces (audit, engagements). Elles sont marquées « Ajoutée après clôture » ; les pièces existantes ne peuvent plus être remplacées ni supprimées.</span>
        </div>

        <ul class="divide-y divide-surface-container-highest text-xs">
            <li v-for="piece in pieces" :key="piece.id" class="flex items-center justify-between gap-3 py-2.5">
                <div class="flex min-w-0 items-center gap-2">
                    <Icon name="draft" class="text-on-surface-variant" />
                    <div class="min-w-0">
                        <a :href="piece.url" target="_blank" rel="noopener" class="block truncate font-semibold text-on-surface hover:text-secondary">{{ piece.name }}</a>
                        <span class="text-[11px] text-on-surface-variant">{{ piece.type ?? 'Type non précisé' }} • {{ piece.uploadedBy }} • {{ piece.uploadedAt }}</span>
                        <span v-if="piece.afterClosure" class="ml-1 rounded border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-[10px] font-semibold text-amber-800">Ajoutée après clôture</span>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-1">
                    <button v-if="piece.updateUrl" type="button" class="rounded-lg p-1.5 text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface" title="Remplacer" @click="open('update', piece)">
                        <Icon name="sync" class="text-base" />
                    </button>
                    <button v-if="piece.destroyUrl" type="button" class="rounded-lg p-1.5 text-error hover:bg-error-container/60" title="Supprimer" @click="destroy(piece)">
                        <Icon name="delete" class="text-base" />
                    </button>
                </div>
            </li>
            <li v-if="!pieces.length" class="py-6 text-center text-on-surface-variant">Aucune pièce déposée.</li>
        </ul>

        <Modal :show="!!editing" :title="editing?.mode === 'update' ? 'Remplacer la pièce' : 'Déposer une pièce'" @close="editing = null">
            <form class="space-y-4" @submit.prevent="submit">
                <div class="space-y-1.5">
                    <label for="piece-type" class="block text-xs font-semibold text-on-surface">Type de pièce <span class="text-error">*</span></label>
                    <select id="piece-type" v-model="form.type_id" required :class="inputClass">
                        <option value="" disabled>Sélectionner…</option>
                        <option v-for="t in types" :key="t.id" :value="t.id">{{ t.name }}</option>
                    </select>
                    <p v-if="form.errors.type_id" class="text-xs text-error">{{ form.errors.type_id }}</p>
                </div>
                <div class="space-y-1.5">
                    <label for="piece-file" class="block text-xs font-semibold text-on-surface">Fichier (PDF, JPG ou PNG, 10 Mo max.) <span class="text-error">*</span></label>
                    <input id="piece-file" type="file" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-surface-container file:px-3 file:py-2 file:text-xs file:font-semibold" @input="form.fichier = $event.target.files[0]">
                    <p v-if="form.errors.fichier" class="text-xs text-error">{{ form.errors.fichier }}</p>
                </div>
            </form>
            <template #footer>
                <Button @click="editing = null">Annuler</Button>
                <Button variant="primary" :disabled="form.processing || !form.fichier || !form.type_id" @click="submit">
                    {{ editing?.mode === 'update' ? 'Remplacer' : 'Déposer' }}
                </Button>
            </template>
        </Modal>
    </section>
</template>
