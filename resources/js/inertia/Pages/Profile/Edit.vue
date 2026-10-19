<script setup>
/**
 * Mon profil : nom, e-mail, photo et mot de passe (route existante profile.store, UpdateProfileRequest).
 */
import { computed, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';

const props = defineProps({
    user: { type: Object, required: true },
    links: { type: Object, required: true },
});

const form = useForm({
    name: props.user.name ?? '',
    email: props.user.email ?? '',
    photo: null,
    password: '',
    password_confirmation: '',
});

const preview = ref(null);
const photoSrc = computed(() => preview.value ?? props.user.photo);

const onPhoto = (e) => {
    const file = e.target.files[0] ?? null;
    form.photo = file;
    preview.value = file ? URL.createObjectURL(file) : null;
};

const submit = () => {
    form.post(props.links.store, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation', 'photo'),
    });
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
const labelClass = 'block text-xs font-semibold text-on-surface';
const sectionClass = 'text-xs font-semibold uppercase tracking-wider text-on-surface-variant';
</script>

<template>
    <Head title="Mon profil" />
    <PageHeader title="Mon profil" subtitle="Mettez à jour vos informations d’affichage et, si besoin, votre mot de passe." />

    <div class="mx-auto max-w-5xl rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs">
        <div class="grid grid-cols-1 gap-8 p-6 lg:grid-cols-3 lg:p-8">
            <aside class="text-center lg:border-r lg:border-surface-container-highest lg:pr-8">
                <img :src="photoSrc" alt="Photo de profil" class="mx-auto h-28 w-28 rounded-full border border-surface-container-highest bg-surface-container-low object-cover">
                <div class="mt-4 text-sm font-bold text-on-surface">{{ user.name }}</div>
                <div v-if="user.role" class="mt-1 inline-flex rounded bg-brand-subtle px-2 py-0.5 text-[11px] font-semibold text-secondary">{{ user.role }}</div>
                <div v-if="user.agence" class="mt-1 text-xs text-on-surface-variant">{{ user.agence }}</div>
                <div v-else-if="user.entite" class="mt-1 text-xs text-on-surface-variant">{{ user.entite }}</div>
                <dl class="mt-6 hidden border-t border-surface-container-highest pt-4 text-left text-xs lg:block">
                    <dt class="text-on-surface-variant">Compte</dt>
                    <dd class="break-all font-semibold text-on-surface">{{ user.email }}</dd>
                </dl>
            </aside>

            <form class="space-y-6 lg:col-span-2" novalidate @submit.prevent="submit">
                <section class="space-y-4">
                    <h2 :class="sectionClass">Coordonnées</h2>
                    <div class="space-y-1.5">
                        <label for="profile-name" :class="labelClass">Nom affiché</label>
                        <input id="profile-name" v-model="form.name" type="text" autocomplete="name" required :class="inputClass">
                        <p v-if="form.errors.name" class="text-xs text-error">{{ form.errors.name }}</p>
                    </div>
                    <div class="space-y-1.5">
                        <label for="profile-email" :class="labelClass">Adresse e-mail</label>
                        <input id="profile-email" v-model="form.email" type="email" autocomplete="email" required :class="inputClass">
                        <p v-if="form.errors.email" class="text-xs text-error">{{ form.errors.email }}</p>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 :class="sectionClass">Photo</h2>
                    <div class="space-y-1.5">
                        <label for="profile-photo" :class="labelClass">Remplacer la photo</label>
                        <input
                            id="profile-photo" type="file" accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif"
                            class="block w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-surface-container file:px-3 file:py-2 file:text-xs file:font-semibold"
                            @input="onPhoto"
                        >
                        <p v-if="form.errors.photo" class="text-xs text-error">{{ form.errors.photo }}</p>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 :class="sectionClass">Mot de passe</h2>
                    <p class="text-xs text-on-surface-variant">Ne remplissez ces champs que si vous souhaitez changer votre mot de passe.</p>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="profile-password" :class="labelClass">Nouveau mot de passe</label>
                            <input id="profile-password" v-model="form.password" type="password" autocomplete="new-password" :class="inputClass">
                            <p v-if="form.errors.password" class="text-xs text-error">{{ form.errors.password }}</p>
                        </div>
                        <div class="space-y-1.5">
                            <label for="profile-password-confirmation" :class="labelClass">Confirmation</label>
                            <input id="profile-password-confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password" :class="inputClass">
                        </div>
                    </div>
                </section>

                <div class="flex flex-wrap items-center gap-2 border-t border-surface-container-highest pt-4">
                    <Button type="submit" variant="primary" :disabled="form.processing">Enregistrer</Button>
                    <a :href="links.home" class="rounded-lg px-4 py-2.5 text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low">Retour</a>
                </div>
            </form>
        </div>
    </div>
</template>
