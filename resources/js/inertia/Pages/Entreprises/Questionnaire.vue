<script setup>
/**
 * Questionnaire de mise en relation. Envoi JSON vers l'endpoint historique saveQuestionnaire
 * (choices[] : critere_id, sous_critere_id, question_id, choice_id, entreprise_id, value).
 */
import { computed, reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    entreprise: { type: Object, required: true },
    groups: { type: Array, default: () => [] },
    answers: { type: Object, default: () => ({}) },
    saveUrl: { type: String, required: true },
    returnUrl: { type: String, required: true },
});

const values = reactive({});
props.groups.forEach((g) => g.sections.forEach((s) => s.questions.forEach((q) => {
    values[q.id] = props.answers[q.id] && q.choices.some((c) => c.id === props.answers[q.id]) ? props.answers[q.id] : 0;
})));

const sections = computed(() => props.groups.flatMap((g, gi) => g.sections.map((s) => ({ ...s, group: g.title, gi }))));
const active = ref(0);
const current = computed(() => sections.value[active.value]);

const count = (list) => ({ total: list.length, answered: list.filter((q) => Number(values[q.id]) !== 0).length });
const totals = computed(() => count(sections.value.flatMap((s) => s.questions)));
const percent = computed(() => (totals.value.total ? Math.round((totals.value.answered / totals.value.total) * 100) : 0));

const saving = ref(false);
const error = ref(null);
const saved = ref(false);

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const save = async () => {
    error.value = null;
    saving.value = true;
    const choices = [];
    sections.value.forEach((s) => s.questions.forEach((q) => {
        const choice = q.choices.find((c) => c.id === Number(values[q.id]));
        if (choice) {
            choices.push({
                critere_id: s.critereId, sous_critere_id: s.id, question_id: q.id,
                choice_id: choice.id, entreprise_id: props.entreprise.id, value: choice.value,
            });
        }
    }));
    try {
        const response = await fetch(props.saveUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({ choices }),
        });
        if (!response.ok) throw new Error();
        saved.value = true;
        router.visit(props.returnUrl);
    } catch {
        error.value = 'Une erreur est survenue lors de l\'enregistrement des réponses.';
    } finally {
        saving.value = false;
    }
};

const selectClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head :title="`Questionnaire — ${entreprise.name}`" />
    <PageHeader title="Questionnaire de mise en relation" :subtitle="entreprise.name">
        <template #actions>
            <Button :href="returnUrl" icon="arrow_back">Retour à la fiche</Button>
            <Button variant="primary" icon="save" :disabled="saving" @click="save">Enregistrer les réponses</Button>
        </template>
    </PageHeader>

    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs">
        <div class="mb-2 flex items-center justify-between text-xs">
            <span class="font-semibold text-on-surface">{{ totals.answered }} / {{ totals.total }} questions répondues</span>
            <span class="font-bold text-secondary">{{ percent }} %</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-surface-container-high">
            <div class="h-full rounded-full bg-secondary transition-all" :style="{ width: `${percent}%` }"></div>
        </div>
    </section>

    <div v-if="error" class="rounded-xl border border-error/30 bg-error-container p-4 text-xs text-on-error-container">{{ error }}</div>
    <div v-if="saved" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-800">Réponses enregistrées.</div>

    <div v-if="sections.length" class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <nav class="space-y-4 lg:col-span-4 xl:col-span-3">
            <div v-for="(g, gi) in groups" :key="gi" class="space-y-1">
                <p v-if="g.title" class="px-3 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ gi + 1 }}. {{ g.title }}</p>
                <template v-for="(s, si) in sections" :key="s.id">
                    <button
                        v-if="s.gi === gi" type="button"
                        class="flex w-full items-center justify-between gap-2 rounded-lg px-3 py-2 text-left text-xs"
                        :class="active === si ? 'bg-surface-container-lowest font-bold text-on-surface shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low'"
                        @click="active = si"
                    >
                        <span>{{ s.name }}</span>
                        <span class="shrink-0 rounded-full px-1.5 text-[10px] font-bold" :class="count(s.questions).answered === count(s.questions).total ? 'bg-secondary/15 text-secondary' : 'bg-surface-container-high text-on-surface-variant'">
                            {{ count(s.questions).answered }}/{{ count(s.questions).total }}
                        </span>
                    </button>
                </template>
            </div>
        </nav>

        <div class="space-y-5 lg:col-span-8 xl:col-span-9">
            <Card :title="current.group ? `${current.group} — ${current.name}` : current.name">
                <div class="divide-y divide-surface-container-highest">
                    <div v-for="q in current.questions" :key="q.id" class="grid grid-cols-1 items-center gap-3 py-3 md:grid-cols-12">
                        <label :for="`q-${q.id}`" class="text-xs font-semibold text-on-surface md:col-span-7">{{ q.name }}</label>
                        <select :id="`q-${q.id}`" v-model.number="values[q.id]" class="md:col-span-5" :class="selectClass">
                            <option :value="0">Choisir une réponse…</option>
                            <option v-for="c in q.choices" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <p v-if="!current.questions.length" class="py-4 text-xs text-on-surface-variant">Aucune question configurée pour cette section.</p>
                </div>
            </Card>
            <div class="flex justify-between">
                <Button :disabled="active === 0" icon="chevron_left" @click="active--">Section précédente</Button>
                <Button v-if="active < sections.length - 1" icon-right="chevron_right" @click="active++">Section suivante</Button>
                <Button v-else variant="primary" icon="save" :disabled="saving" @click="save">Enregistrer les réponses</Button>
            </div>
        </div>
    </div>
    <Card v-else>
        <p class="flex items-center gap-2 text-xs text-on-surface-variant"><Icon name="info" class="text-base" /> Aucun critère de questionnaire n'est configuré. Contactez l'administrateur.</p>
    </Card>
</template>
