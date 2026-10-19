<script setup>
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import BrandShowcase from '@/Components/layout/BrandShowcase.vue';

// Page publique : pas de layout applicatif.
defineOptions({ layout: (h, page) => page });

defineProps({
    loginUrl: { type: String, required: true },
    appName: { type: String, default: 'ANGARA' },
});

const features = [
    { icon: 'how_to_reg', title: 'Entrée en relation', text: 'Prospects, avis juridique et conformité, passage client.' },
    { icon: 'account_tree', title: 'Structuration', text: 'Besoins des clients et dossiers d’instruction par programme.' },
    { icon: 'fact_check', title: 'Instruction', text: 'Circuit des pôles jusqu’à la décision, délégation de pouvoir.' },
    { icon: 'monitoring', title: 'Pilotage', text: 'Tableaux de bord du portefeuille, des risques et des programmes.' },
];
</script>

<template>
    <Head title="Accueil" />
    <div class="grid min-h-screen grid-cols-1 bg-surface lg:grid-cols-2">
        <!-- Présentation -->
        <main class="flex flex-col px-6 py-8 sm:px-12 xl:px-20">
            <header class="flex items-center justify-between">
                <img src="/img/logo-bcpme.png" :alt="appName" class="h-12 w-auto">
                <a :href="loginUrl" class="inline-flex items-center gap-2 rounded-lg border border-surface-container-highest bg-white px-4 py-2 text-xs font-semibold text-on-surface shadow-2xs hover:bg-surface-container-low">
                    <Icon name="login" class="text-base" /> Connexion
                </a>
            </header>

            <section class="flex flex-1 flex-col justify-center py-12">
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-brand-subtle px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-secondary">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand"></span> Plateforme de financement des PME
                </span>
                <h1 class="mt-5 max-w-xl text-3xl font-bold leading-tight tracking-tight text-on-surface xl:text-4xl">
                    Bienvenue sur l’espace de travail ANGARA de la BCPME
                </h1>
                <p class="mt-4 max-w-lg text-sm leading-relaxed text-on-surface-variant">
                    Gestionnaires, chefs d’agence, pôles d’instruction et direction travaillent sur le même dossier,
                    de l’entrée en relation jusqu’à la décision de crédit.
                </p>
                <div class="mt-8">
                    <a :href="loginUrl" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-6 py-3 text-sm font-semibold text-white shadow-xs hover:bg-black">
                        Accéder à mon espace <Icon name="arrow_forward" class="text-base" />
                    </a>
                </div>

                <div class="mt-12 grid max-w-xl grid-cols-1 gap-3 sm:grid-cols-2">
                    <div v-for="f in features" :key="f.title" class="flex items-start gap-3 rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-2xs">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-subtle text-brand">
                            <Icon :name="f.icon" class="text-lg" />
                        </span>
                        <div>
                            <div class="text-xs font-bold text-on-surface">{{ f.title }}</div>
                            <p class="mt-0.5 text-[11px] leading-relaxed text-on-surface-variant">{{ f.text }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="text-[11px] text-on-surface-variant">
                © {{ new Date().getFullYear() }} Banque Camerounaise des PME (BCPME) — propulsé par ANGARA SOLUTIONS
            </footer>
        </main>

        <!-- Visuel -->
        <aside class="hidden lg:block">
            <BrandShowcase />
        </aside>
    </div>
</template>
