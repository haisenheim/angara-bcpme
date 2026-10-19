<script setup>
/**
 * Formulaire d'administration en page (création / édition) : champs, URL et méthode décrits par le serveur.
 * Les validations restent celles des contrôleurs / FormRequest existants.
 */
import { Head, Link, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import FormFields from '@/Components/admin/FormFields.vue';
import ActionButtons from '@/Components/admin/ActionButtons.vue';

const props = defineProps({
    title: String,
    subtitle: { type: String, default: null },
    notice: { type: String, default: null },
    form: { type: Object, required: true }, // { fields, submit: { url, method, label }, cancel: { href, label }, title, narrow }
    actions: { type: Array, default: () => [] },
});

const data = useForm(Object.fromEntries(props.form.fields.filter((f) => f.type !== 'section' && !f.readonly).map((f) => [f.name, f.value ?? ''])));
const hasFile = props.form.fields.some((f) => f.type === 'file');

const submit = () => {
    const method = (props.form.submit.method ?? 'post').toLowerCase();
    data.transform((d) => (method === 'post' ? d : { ...d, _method: method })).post(props.form.submit.url, {
        preserveScroll: true,
        forceFormData: hasFile,
    });
};
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="actions.length" #actions>
            <ActionButtons :actions="actions" />
        </template>
    </PageHeader>

    <p v-if="notice" class="rounded-xl border border-surface-container-highest bg-surface-container-low p-4 text-xs leading-relaxed text-on-surface-variant">{{ notice }}</p>

    <form :class="form.narrow ? 'max-w-2xl' : 'max-w-5xl'" @submit.prevent="submit">
        <Card :title="form.title ?? null">
            <FormFields :form="data" :fields="form.fields" />
            <div class="mt-6 flex flex-wrap items-center justify-end gap-2 border-t border-surface-container-highest pt-4">
                <Link v-if="form.cancel" :href="form.cancel.href" class="rounded-lg px-4 py-2.5 text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low">
                    {{ form.cancel.label ?? 'Annuler' }}
                </Link>
                <Button type="submit" variant="primary" :disabled="data.processing">{{ form.submit.label ?? 'Enregistrer' }}</Button>
            </div>
        </Card>
    </form>
</template>
