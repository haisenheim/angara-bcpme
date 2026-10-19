<script setup>
/**
 * Connexion (Fortify) — écran sans layout, en deux colonnes : visuel BCPME à gauche, formulaire à droite.
 * Le formulaire est envoyé en POST classique (pas de visite Inertia) vers la route `login` existante :
 * après authentification, /home redirige vers des espaces qui ne sont pas tous en Inertia.
 * Les erreurs de validation Fortify reviennent dans `errors` (partagé par le middleware Inertia).
 */
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';

// Page sans layout : le résolveur d'app.js n'applique AppLayout que si `layout` est absent.
defineOptions({ layout: (h, page) => page });

const props = defineProps({
    loginUrl: { type: String, required: true },
    email: { type: String, default: '' },
    warning: { type: String, default: null },
    status: { type: String, default: null },
});

const page = usePage();
const errors = computed(() => page.props.errors ?? {});
const csrf = typeof document !== 'undefined' ? document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '' : '';

const showPassword = ref(false);
const submitting = ref(false);

const slides = [
    { src: '/img/new/slides/slide-1.jpg', text: 'Le système expert en finances pour fédérer tous les secteurs d’activités' },
    { src: '/img/new/slides/slide-2.jpg', text: 'Recevez en temps réel les informations financières de votre secteur d’activité.' },
    { src: '/img/new/slides/slide-3.jpg', text: 'Mettez à niveau votre entreprise au niveau structurel et financier' },
    { src: '/img/new/slides/slide-4.jpg', text: 'Optimisez votre entreprise pour vos besoins d’investissement' },
];
const current = ref(0);
let timer = null;
onMounted(() => {
    timer = setInterval(() => (current.value = (current.value + 1) % slides.length), 6000);
});
onBeforeUnmount(() => clearInterval(timer));

const inputClass = 'w-full rounded-lg border-surface-container-highest bg-white py-2.5 pl-10 text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head title="Connexion" />
    <div class="flex min-h-screen bg-surface">
        <!-- Visuel -->
        <aside class="relative hidden w-1/2 overflow-hidden bg-secondary lg:block">
            <div
                v-for="(s, i) in slides"
                :key="s.src"
                class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
                :class="i === current ? 'opacity-100' : 'opacity-0'"
                :style="{ backgroundImage: `url(${s.src})` }"
            ></div>
            <div class="absolute inset-0 bg-black/45"></div>
            <div class="relative flex h-full flex-col justify-end p-12 text-white">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-brand">ANGARA</span>
                <p class="mt-3 max-w-md text-2xl font-bold leading-snug">{{ slides[current].text }}</p>
                <div class="mt-8 flex gap-2">
                    <button
                        v-for="(s, i) in slides"
                        :key="`dot-${i}`"
                        type="button"
                        class="h-1.5 rounded-full transition-all"
                        :class="i === current ? 'w-8 bg-brand' : 'w-3 bg-white/50'"
                        :aria-label="`Visuel ${i + 1}`"
                        @click="current = i"
                    ></button>
                </div>
            </div>
        </aside>

        <!-- Formulaire -->
        <main class="flex w-full items-center justify-center p-6 lg:w-1/2">
            <div class="w-full max-w-sm">
                <img src="/img/logo-bcpme.png" alt="Angara — Banque Camerounaise des PME" class="mx-auto h-20 w-auto">
                <h1 class="mt-8 text-center text-xl font-bold tracking-tight text-on-surface">Connectez-vous</h1>
                <p class="mt-1 text-center text-xs text-on-surface-variant">Accédez à votre espace de travail ANGARA.</p>

                <div v-if="status" class="mt-6 flex items-start gap-2 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-900">
                    <Icon name="check_circle" class="text-base text-emerald-700" /> {{ status }}
                </div>
                <div v-if="warning" class="mt-6 flex items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                    <Icon name="warning" class="text-base text-amber-700" /> {{ warning }}
                </div>
                <div v-if="errors.email || errors.password" class="mt-6 flex items-start gap-2 rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">
                    <Icon name="error" class="text-base text-error" />
                    <div>
                        <p v-if="errors.email">{{ errors.email }}</p>
                        <p v-if="errors.password">{{ errors.password }}</p>
                    </div>
                </div>

                <form method="post" :action="loginUrl" class="mt-6 space-y-4" @submit="submitting = true">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="space-y-1.5">
                        <label for="login-email" class="block text-xs font-semibold text-on-surface">Adresse e-mail</label>
                        <div class="relative">
                            <Icon name="mail" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline" />
                            <input id="login-email" type="email" name="email" :value="email" required autofocus autocomplete="username" placeholder="Email" :class="inputClass">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="login-password" class="block text-xs font-semibold text-on-surface">Mot de passe</label>
                        <div class="relative">
                            <Icon name="lock" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline" />
                            <input id="login-password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Mot de passe" :class="inputClass" class="pr-10">
                            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-outline hover:text-on-surface" :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'" @click="showPassword = !showPassword">
                                <Icon :name="showPassword ? 'visibility_off' : 'visibility'" class="text-lg" />
                            </button>
                        </div>
                    </div>
                    <button type="submit" :disabled="submitting" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-3 text-sm font-semibold text-white shadow-xs hover:bg-black disabled:opacity-60">
                        <Icon name="login" class="text-base" /> Connexion
                    </button>
                </form>

                <p class="mt-10 text-center text-[11px] text-on-surface-variant">
                    © {{ new Date().getFullYear() }} Banque Camerounaise des PME (BCPME) — propulsé par ANGARA SOLUTIONS
                </p>
            </div>
        </main>
    </div>
</template>
