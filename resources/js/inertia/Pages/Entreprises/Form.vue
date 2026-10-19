<script setup>
/**
 * Formulaire entreprise / prospect / tiers décrit par le serveur (EntrepriseFormSchema).
 */
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import SchemaForm from '@/Components/forms/SchemaForm.vue';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: null },
    // Message d'information affiché au-dessus du formulaire.
    notice: { type: String, default: null },
    // Formulaire non modifiable (ex. prospect déjà soumis) : schema = null.
    locked: { type: String, default: null },
    backHref: { type: String, default: null },
    schema: { type: Object, default: null },
    stacked: { type: Boolean, default: false },
});
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="backHref" #actions>
            <Button :href="backHref" icon="arrow_back">Retour</Button>
        </template>
    </PageHeader>

    <div v-if="notice" class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
        <Icon name="info" class="text-blue-700" />
        <span>{{ notice }}</span>
    </div>
    <div v-if="locked" class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        <Icon name="lock" class="text-amber-700" />
        <span>{{ locked }}</span>
    </div>

    <SchemaForm v-if="schema" :schema="schema" :stacked="stacked" />
</template>
