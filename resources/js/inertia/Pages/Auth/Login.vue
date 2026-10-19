<script setup>
/**
 * Connexion (Fortify) — écran sans layout : visuel de marque à gauche, formulaire à droite.
 * Le formulaire est envoyé en POST classique vers la route `login` (Fortify), qui redirige ensuite
 * vers l'espace du profil. Les erreurs de validation reviennent dans `errors` (partagé par le middleware Inertia).
 */
import { computed, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import BrandShowcase from '@/Components/layout/BrandShowcase.vue';

// Page sans layout : le résolveur d'app.js n'applique AppLayout que si `layout` est absent.
defineOptions({ layout: (h, page) => page });

defineProps({
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

const inputClass = 'w-full rounded-lg border-surface-container-highest bg-white py-2.5 pl-10 text-sm text-on-surface placeholder:text-outline focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head title="Connexion" />
    <div class="grid min-h-screen grid-cols-1 bg-surface lg:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)]">
        <!-- Visuel -->
        <aside class="hidden lg:block">
            <BrandShowcase compact>
                <span class="mb-3 block text-xs font-semibold uppercase tracking-[0.2em] text-brand">Espace sécurisé</span>
            </BrandShowcase>
        </aside>

        <!-- Formulaire -->
        <main class="flex flex-col px-6 py-8 sm:px-12">
            <div class="flex items-center justify-between">
                <a href="/" class="inline-flex items-center gap-1 text-xs font-semibold text-on-surface-variant hover:text-on-surface">
                    <Icon name="arrow_back" class="text-base" /> Accueil
                </a>
                <img src="/img/logo-bcpme.png" alt="BCPME" class="h-9 w-auto lg:hidden">
            </div>

            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-sm">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-subtle text-brand">
                        <Icon name="lock_person" class="text-2xl" />
                    </span>
                    <h1 class="mt-5 text-2xl font-bold tracking-tight text-on-surface">Connexion</h1>
                    <p class="mt-1 text-sm text-on-surface-variant">Accédez à votre espace de travail ANGARA.</p>

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

                    <form method="post" :action="loginUrl" class="mt-7 space-y-4" @submit="submitting = true">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="space-y-1.5">
                            <label for="login-email" class="block text-xs font-semibold text-on-surface">Adresse e-mail</label>
                            <div class="relative">
                                <Icon name="mail" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline" />
                                <input id="login-email" type="email" name="email" :value="email" required autofocus autocomplete="username" placeholder="prenom.nom@bcpme.cm" :class="inputClass">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label for="login-password" class="block text-xs font-semibold text-on-surface">Mot de passe</label>
                            <div class="relative">
                                <Icon name="lock" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-outline" />
                                <input id="login-password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••" :class="inputClass" class="pr-10">
                                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-outline hover:text-on-surface" :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'" @click="showPassword = !showPassword">
                                    <Icon :name="showPassword ? 'visibility_off' : 'visibility'" class="text-lg" />
                                </button>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-xs text-on-surface-variant">
                            <input type="checkbox" name="remember" value="1" class="rounded border-surface-container-highest text-secondary focus:ring-secondary">
                            Se souvenir de moi
                        </label>
                        <button type="submit" :disabled="submitting" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-3 text-sm font-semibold text-white shadow-xs transition-colors hover:bg-black disabled:opacity-60">
                            <span v-if="submitting" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                            <Icon v-else name="login" class="text-base" />
                            {{ submitting ? 'Connexion…' : 'Se connecter' }}
                        </button>
                    </form>

                    <p class="mt-6 flex items-start gap-2 rounded-lg bg-surface-container-low p-3 text-[11px] leading-relaxed text-on-surface-variant">
                        <Icon name="support_agent" class="text-base text-secondary" />
                        Mot de passe oublié ou compte bloqué : contactez l’administrateur de la plateforme.
                    </p>
                </div>
            </div>

            <footer class="text-center text-[11px] text-on-surface-variant">
                © {{ new Date().getFullYear() }} Banque Camerounaise des PME (BCPME) — propulsé par ANGARA SOLUTIONS
            </footer>
        </main>
    </div>
</template>
