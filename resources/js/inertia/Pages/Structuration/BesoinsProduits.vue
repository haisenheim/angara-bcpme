<script setup>
/**
 * Mise à jour par le chef de filière des besoins exprimés (appuis financiers / non financiers),
 * du produit principal et des produits secondaires d'un client.
 */
import { computed, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import OptionPicker from '@/Components/structuration/OptionPicker.vue';

const props = defineProps({
    entreprise: Object,
    options: Object,
    values: Object,
    urls: Object,
});

const form = useForm({ ...props.values });
const errorList = computed(() => Object.values(form.errors));
const principal = computed(() => (form.produit_id ? [Number(form.produit_id)] : []));

const save = () => {
    form.transform((data) => ({
        ...data,
        // Le produit principal ne peut pas figurer parmi les produits secondaires (règle reprise du serveur).
        autres: data.autres.filter((id) => id !== Number(data.produit_id)),
    })).post(props.urls.update, { preserveScroll: true });
};

// Ancres #produit-principal, #produits-secondaires, #besoins (liens de l'ancienne fiche).
onMounted(() => {
    const target = window.location.hash && document.querySelector(window.location.hash);
    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head :title="`Besoins et produits — ${entreprise.name}`" />
    <PageHeader title="Besoins et produits" :subtitle="`${entreprise.name} — mettez à jour les appuis souhaités et la gamme de produits.`">
        <template #actions>
            <Button :href="urls.client" icon="arrow_back">Retour à la fiche client</Button>
        </template>
    </PageHeader>

    <form class="space-y-5" @submit.prevent="save">
        <div v-if="errorList.length" class="rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">
            <p v-for="(message, i) in errorList" :key="i">{{ message }}</p>
        </div>

        <Card id="produit-principal" title="Produit principal" class="scroll-mt-24">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="space-y-1.5 md:col-span-2">
                    <label for="produit_id" class="block text-xs font-semibold text-on-surface">Produit principal</label>
                    <select id="produit_id" v-model="form.produit_id" :class="inputClass">
                        <option value="">— Aucun —</option>
                        <option v-for="p in options.produits" :key="p.value" :value="p.value">{{ p.label }}<template v-if="p.hint"> — {{ p.hint }}</template></option>
                    </select>
                    <p v-if="form.errors.produit_id" class="text-xs text-error">{{ form.errors.produit_id }}</p>
                </div>
                <div class="space-y-1.5">
                    <label for="produit_year_start" class="block text-xs font-semibold text-on-surface">Ancienneté (années)</label>
                    <input id="produit_year_start" v-model="form.produit_year_start" type="number" min="0" max="200" placeholder="ex. 5" :class="inputClass">
                    <p v-if="form.errors.produit_year_start" class="text-xs text-error">{{ form.errors.produit_year_start }}</p>
                </div>
            </div>
        </Card>

        <Card id="produits-secondaires" title="Produits secondaires" class="scroll-mt-24">
            <p class="-mt-2 mb-3 text-xs text-on-surface-variant">Un ou plusieurs produits distincts du produit principal.</p>
            <OptionPicker v-model="form.autres" :options="options.produits" :excluded="principal" placeholder="Rechercher un produit…" />
            <p v-if="form.errors.autres" class="mt-1 text-xs text-error">{{ form.errors.autres }}</p>
        </Card>

        <Card id="besoins" title="Besoins exprimés (appuis souhaités)" class="scroll-mt-24">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-on-surface">Appuis financiers</div>
                    <OptionPicker v-model="form.appuisf" :options="options.appuisFinanciers" placeholder="Rechercher un appui financier…" />
                    <p v-if="form.errors.appuisf" class="text-xs text-error">{{ form.errors.appuisf }}</p>
                </div>
                <div class="space-y-1.5">
                    <div class="text-xs font-semibold text-on-surface">Appuis non financiers</div>
                    <OptionPicker v-model="form.appuisnf" :options="options.appuisNonFinanciers" placeholder="Rechercher un appui non financier…" />
                    <p v-if="form.errors.appuisnf" class="text-xs text-error">{{ form.errors.appuisnf }}</p>
                </div>
            </div>
        </Card>

        <div class="flex flex-wrap gap-2.5">
            <Button type="submit" variant="primary" icon="check" :disabled="form.processing">Enregistrer</Button>
            <Button :href="urls.client">Annuler</Button>
        </div>
    </form>
</template>
