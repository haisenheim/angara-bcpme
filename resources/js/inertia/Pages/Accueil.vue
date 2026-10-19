<script setup>
import { Head } from '@inertiajs/vue3';

// Page publique : pas de layout applicatif.
defineOptions({ layout: (h, page) => page });

defineProps({
    loginUrl: { type: String, required: true },
    appName: { type: String, default: 'ANGARA' },
});
</script>

<template>
    <Head title="Accueil" />
    <div class="flex min-h-screen items-center justify-center bg-surface px-4 py-8">
        <div class="w-full max-w-md rounded-xl border border-surface-container-highest bg-surface-container-lowest px-8 py-10 text-center shadow-xs">
            <img src="/img/logo-bcpme.png" :alt="appName" class="mx-auto mb-6 h-auto max-w-[220px]">
            <h1 class="mb-3 text-2xl font-bold tracking-tight text-on-surface">Bienvenue</h1>
            <p class="mb-7 text-sm text-on-surface-variant">Accédez à votre espace en vous connectant.</p>
            <a :href="loginUrl" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-container px-6 py-3 text-sm font-semibold text-white shadow-xs hover:bg-black">
                <span class="material-symbols-outlined text-base">login</span>
                Connexion
            </a>
        </div>
    </div>
</template>
