<script setup>
/**
 * Création / modification d'un programme (admin). Contrat : ProgrammePagePresenter::form.
 * Les noms de champs POST sont ceux du formulaire Blade (routes store / save existantes) :
 * les sélections multiples partent en listes d'identifiants séparés par des virgules.
 */
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import MultiPicker from '@/Components/programmes/MultiPicker.vue';

const props = defineProps({
    title: String,
    mode: String,
    submitUrl: String,
    cancelUrl: String,
    values: Object,
    options: Object,
});

const form = useForm({ ...props.values });

const submit = () => {
    form.transform((data) => {
        const payload = {
            ...data,
            bailleurs: data.bailleurs.join(','),
            produits: data.produits.join(','),
            appuisf: data.appuisf.join(','),
            appuisnf: data.appuisnf.join(','),
        };
        // En création, l'identifiant n'existe pas (le contrôleur enregistre tous les champs reçus).
        if (props.mode !== 'edit') delete payload.id;
        return payload;
    }).post(props.submitUrl, { preserveScroll: true });
};

const toggleIn = (field, value) => {
    form[field] = form[field].includes(value) ? form[field].filter((v) => v !== value) : [...form[field], value];
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
const labelClass = 'mb-1.5 block text-xs font-semibold text-on-surface';
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" subtitle="Identification de la convention, budgets, bénéficiaires, secteurs, bailleurs et appuis proposés." />

    <form class="mx-auto max-w-5xl space-y-5" @submit.prevent="submit">
        <div v-if="Object.keys(form.errors).length" class="rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">
            <p v-for="(message, key) in form.errors" :key="key">{{ message }}</p>
        </div>

        <!-- 1. Identification & budgets -->
        <Card title="1 · Identification de la convention">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="name" :class="labelClass">Dénomination <span class="text-error">*</span></label>
                    <input id="name" v-model="form.name" type="text" required placeholder="Nom du programme" autocomplete="off" :class="inputClass">
                </div>
                <div>
                    <label for="convention" :class="labelClass">N° référence convention cadre <span class="text-error">*</span></label>
                    <input id="convention" v-model="form.convention" type="text" required placeholder="Référence officielle" autocomplete="off" :class="inputClass">
                </div>
                <div>
                    <label for="dt_sig_conv" :class="labelClass">Date de signature <span class="text-error">*</span></label>
                    <input id="dt_sig_conv" v-model="form.dt_sig_conv" type="date" required :class="inputClass">
                </div>
                <div>
                    <label for="signataire" :class="labelClass">Institution signataire <span class="text-error">*</span></label>
                    <input id="signataire" v-model="form.signataire" type="text" required placeholder="Institution nationale signataire" :class="inputClass">
                </div>
            </div>
        </Card>

        <Card title="Budgets (FCFA)">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="budget_af" :class="labelClass">Appuis financiers <span class="text-error">*</span></label>
                    <input id="budget_af" v-model="form.budget_af" type="number" min="0" step="1" required :class="inputClass">
                </div>
                <div>
                    <label for="budget_anf" :class="labelClass">Appuis non financiers <span class="text-error">*</span></label>
                    <input id="budget_anf" v-model="form.budget_anf" type="number" min="0" step="1" required :class="inputClass">
                </div>
                <div>
                    <label for="budget_coord" :class="labelClass">Coordination <span class="text-error">*</span></label>
                    <input id="budget_coord" v-model="form.budget_coord" type="number" min="0" step="1" required :class="inputClass">
                </div>
            </div>
        </Card>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <Card title="Bénéficiaires — personnes morales">
                <p class="mb-3 text-xs text-on-surface-variant">Cochez les catégories éligibles.</p>
                <div class="flex flex-wrap gap-2">
                    <label
                        v-for="opt in options.typesEntreprise"
                        :key="opt.value"
                        class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                        :class="form.type_entreprise.includes(opt.value) ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'"
                    >
                        <input type="checkbox" :checked="form.type_entreprise.includes(opt.value)" class="rounded text-secondary focus:ring-secondary" @change="toggleIn('type_entreprise', opt.value)">
                        {{ opt.label }}
                    </label>
                </div>
            </Card>
            <Card title="Bénéficiaires — personnes physiques">
                <p class="mb-3 text-xs text-on-surface-variant">Cochez les publics éligibles.</p>
                <div class="flex flex-wrap gap-2">
                    <label
                        v-for="opt in options.typesPersonne"
                        :key="opt.value"
                        class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                        :class="form.type_personne.includes(opt.value) ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'"
                    >
                        <input type="checkbox" :checked="form.type_personne.includes(opt.value)" class="rounded text-secondary focus:ring-secondary" @change="toggleIn('type_personne', opt.value)">
                        {{ opt.label }}
                    </label>
                </div>
            </Card>
        </div>

        <Card title="Calendrier & contact">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="dt_start" :class="labelClass">Début des activités <span class="text-error">*</span></label>
                    <input id="dt_start" v-model="form.dt_start" type="date" required :class="inputClass">
                </div>
                <div>
                    <label for="contact" :class="labelClass">Personnes ressources & contact <span class="text-error">*</span></label>
                    <input id="contact" v-model="form.contact" type="text" required placeholder="Coordonnées du point de contact programme" :class="inputClass">
                </div>
            </div>
        </Card>

        <!-- 2. Secteurs & bailleurs -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <Card title="2 · Bailleurs de fonds signataires">
                <MultiPicker v-model="form.bailleurs" :options="options.organismes" placeholder="Rechercher un bailleur…" empty-label="Aucun bailleur sélectionné." />
            </Card>
            <Card title="Secteurs cibles (produits / services)">
                <MultiPicker v-model="form.produits" :options="options.produits" placeholder="Rechercher un secteur ou un produit…" empty-label="Aucun secteur sélectionné." />
            </Card>
        </div>

        <!-- 3. Appuis proposés -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <Card title="3 · Appuis financiers">
                <MultiPicker v-model="form.appuisf" :options="options.afs" placeholder="Rechercher un appui financier…" empty-label="Aucun appui financier retenu." />
            </Card>
            <Card title="Appuis non financiers">
                <MultiPicker v-model="form.appuisnf" :options="options.anfs" placeholder="Rechercher un appui non financier…" empty-label="Aucun appui non financier retenu." />
            </Card>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-2.5 border-t border-surface-container-highest pt-5">
            <Link :href="cancelUrl" class="inline-flex items-center justify-center rounded-lg border border-surface-container-highest bg-white px-4 py-2.5 text-xs font-semibold text-on-surface shadow-2xs hover:bg-surface-container-low">Annuler</Link>
            <Button type="submit" variant="primary" icon="save" :disabled="form.processing">
                {{ mode === 'edit' ? 'Enregistrer les modifications' : 'Créer le programme' }}
            </Button>
        </div>
    </form>
</template>
