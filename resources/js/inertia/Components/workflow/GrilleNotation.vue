<script setup>
/**
 * Grille de notation corporate (critères, sous-critères, finance, notation PME).
 * Modifiable seulement si le serveur fournit `grille.edit` (URL des choix et de l'enregistrement) :
 * les choix possibles sont lus sur l'endpoint JSON existant, l'enregistrement poste le formulaire historique.
 */
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Card from '@/Components/ui/Card.vue';
import Modal from '@/Components/ui/Modal.vue';
import Button from '@/Components/ui/Button.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';

const props = defineProps({
    grille: { type: Object, required: true },
});

const editing = ref(null);
const choices = ref([]);
const loading = ref(false);
const loadError = ref(null);
const form = useForm({ dossier_id: '', programme_id: '', critere_id: '', choice_id: '' });

const open = async (row) => {
    const edit = props.grille.edit;
    editing.value = row;
    choices.value = [];
    loadError.value = null;
    form.clearErrors();
    form.dossier_id = edit.dossierId;
    form.programme_id = edit.programmeId ?? '';
    form.critere_id = row.id;
    form.choice_id = '';
    loading.value = true;
    try {
        const sep = edit.choicesUrl.includes('?') ? '&' : '?';
        const res = await fetch(`${edit.choicesUrl}${sep}id=${encodeURIComponent(row.id)}`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        if (!res.ok) throw new Error(String(res.status));
        const data = await res.json();
        choices.value = Array.isArray(data) ? data : [];
    } catch (e) {
        loadError.value = 'Impossible de charger les valeurs possibles pour ce sous-critère.';
    } finally {
        loading.value = false;
    }
};

const submit = () => {
    form.transform((data) => (data.programme_id === '' ? { ...data, programme_id: undefined } : data))
        .post(props.grille.edit.reponseUrl, { preserveScroll: true, onSuccess: () => (editing.value = null) });
};

const fmt = (v) => (v === null || v === undefined || v === '' ? '—' : v);
const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Card title="Grille de notation">
        <div>
            <div v-if="grille.hasData" class="max-h-[80vh] overflow-auto">
                <table class="w-full text-left text-xs">
                    <thead class="sticky top-0 border-b border-surface-container-highest bg-surface-container-low text-[11px] uppercase tracking-wider text-on-surface-variant">
                        <tr>
                            <th class="px-2 py-2">N°</th>
                            <th class="px-2 py-2 text-right">%</th>
                            <th class="px-2 py-2">Sous-critère</th>
                            <th class="px-2 py-2">Valeur</th>
                            <th class="px-2 py-2 text-right">Note</th>
                            <th class="px-2 py-2 text-right">Pondérée</th>
                        </tr>
                    </thead>
                    <tbody v-for="group in grille.groups" :key="group.name" class="divide-y divide-surface-container-highest">
                        <tr class="bg-surface-container-low/60">
                            <th colspan="6" class="px-2 py-2 text-[11px] font-bold uppercase tracking-wider text-on-surface">{{ group.name }}</th>
                        </tr>
                        <tr v-for="row in group.rows" :id="row.id ? `grille-souscritere-${row.id}` : null" :key="`${group.name}-${row.sequence}-${row.name}`">
                            <td class="px-2 py-2 text-on-surface-variant">{{ fmt(row.sequence) }}</td>
                            <td class="px-2 py-2 text-right">{{ row.pourcentage }} %</td>
                            <td class="px-2 py-2 font-medium text-on-surface">{{ row.name }}</td>
                            <td class="px-2 py-2">
                                <span class="inline-flex items-center gap-1">
                                    {{ fmt(row.valeur) }}
                                    <button v-if="row.editable && grille.edit" type="button" class="rounded p-0.5 text-secondary hover:bg-secondary/10" title="Modifier" @click="open(row)">
                                        <Icon name="edit" class="text-sm" />
                                    </button>
                                </span>
                            </td>
                            <td class="px-2 py-2 text-right">{{ fmt(row.note) }}</td>
                            <td class="px-2 py-2 text-right">{{ fmt(row.pondere) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-2 py-2 text-right text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Note critère pondérée</td>
                            <td class="px-2 py-2 text-right font-bold text-on-surface">{{ group.note }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-primary-container text-white">
                            <td colspan="3" class="px-2 py-2.5 text-[11px] font-semibold uppercase tracking-wider">Note pondérée finale</td>
                            <td class="px-2 py-2.5 font-bold">{{ fmt(grille.noteFinale) }}</td>
                            <td class="px-2 py-2.5 text-right text-[11px] font-semibold uppercase tracking-wider">Notation PME</td>
                            <td class="px-2 py-2.5 text-right font-bold">{{ fmt(grille.sme?.name) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <div v-if="grille.sme?.mention || grille.sme?.description" class="mt-3 space-y-1.5 text-xs">
                    <div v-if="grille.sme.mention" class="flex items-center gap-2">
                        <span class="font-semibold text-on-surface-variant">Mention :</span>
                        <StatusBadge :variant="grille.sme.mentionVariant">{{ grille.sme.mention }}</StatusBadge>
                    </div>
                    <p v-if="grille.sme.description" class="text-on-surface"><span class="font-semibold text-on-surface-variant">Avis SME :</span> {{ grille.sme.description }}</p>
                </div>
            </div>
            <div v-else class="py-10 text-center text-xs text-on-surface-variant">
                <Icon name="query_stats" class="text-4xl" />
                <p class="mt-2 font-semibold text-on-surface">Aucune donnée DSF importée</p>
                <p class="mt-1">{{ grille.emptyText }}</p>
            </div>
        </div>

        <Modal :show="!!editing" title="Choix de la valeur" @close="editing = null">
            <form class="space-y-3" @submit.prevent="submit">
                <label for="grille-choice" class="block text-xs font-semibold text-on-surface">{{ editing?.name }}</label>
                <p v-if="loading" class="text-xs text-on-surface-variant">Chargement des valeurs…</p>
                <p v-else-if="loadError" class="text-xs text-error">{{ loadError }}</p>
                <select v-else id="grille-choice" v-model="form.choice_id" required :class="inputClass">
                    <option value="" disabled>Choisir…</option>
                    <option v-for="c in choices" :key="c.id" :value="c.id">{{ c.valeur }}</option>
                </select>
                <p v-for="(message, key) in form.errors" :key="key" class="text-xs text-error">{{ message }}</p>
            </form>
            <template #footer>
                <Button @click="editing = null">Annuler</Button>
                <Button variant="primary" :disabled="form.processing || !form.choice_id" @click="submit">Enregistrer</Button>
            </template>
        </Modal>
    </Card>
</template>
