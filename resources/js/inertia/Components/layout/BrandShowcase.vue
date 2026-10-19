<script setup>
/**
 * Visuel de marque BCPME (pages publiques : accueil, connexion) — diaporama sous voile vert sidebar.
 */
import { onBeforeUnmount, onMounted, ref } from 'vue';

defineProps({
    compact: { type: Boolean, default: false },
});

const slides = [
    { src: '/img/new/slides/slide-1.jpg', text: 'Financer et accompagner les PME camerounaises, de l’entrée en relation à la décision de crédit.' },
    { src: '/img/new/slides/slide-2.jpg', text: 'Un circuit d’instruction tracé de bout en bout : exploitation, juridique, engagements, risques, direction.' },
    { src: '/img/new/slides/slide-3.jpg', text: 'Structurer les clients et leurs besoins pour mobiliser les bons programmes d’appui.' },
    { src: '/img/new/slides/slide-4.jpg', text: 'Piloter le portefeuille et les engagements en temps réel.' },
];
const current = ref(0);
let timer = null;
onMounted(() => {
    timer = setInterval(() => (current.value = (current.value + 1) % slides.length), 6500);
});
onBeforeUnmount(() => clearInterval(timer));
</script>

<template>
    <div class="relative h-full overflow-hidden bg-secondary">
        <div
            v-for="(s, i) in slides"
            :key="s.src"
            class="absolute inset-0 scale-105 bg-cover bg-center transition-all duration-[1500ms]"
            :class="i === current ? 'scale-100 opacity-100' : 'opacity-0'"
            :style="{ backgroundImage: `url(${s.src})` }"
        ></div>
        <div class="absolute inset-0 bg-gradient-to-t from-secondary via-secondary/60 to-secondary/10"></div>

        <div class="relative flex h-full flex-col justify-between p-10 text-white xl:p-14">
            <div class="flex items-center gap-3">
                <span class="flex h-11 items-center rounded-lg bg-white px-2 shadow-xs">
                    <img src="/img/logo-bcpme.png" alt="BCPME" class="h-8 w-auto">
                </span>
                <div class="leading-tight">
                    <div class="text-sm font-bold tracking-tight">BCPME ANGARA</div>
                    <div class="text-[11px] text-white/70">Banque Camerounaise des PME</div>
                </div>
            </div>

            <div>
                <slot />
                <p class="max-w-lg text-2xl font-bold leading-snug" :class="compact ? 'xl:text-2xl' : 'xl:text-3xl'">{{ slides[current].text }}</p>
                <div class="mt-8 flex gap-2">
                    <button
                        v-for="(s, i) in slides"
                        :key="`dot-${i}`"
                        type="button"
                        class="h-1.5 rounded-full transition-all"
                        :class="i === current ? 'w-8 bg-brand' : 'w-3 bg-white/40 hover:bg-white/70'"
                        :aria-label="`Visuel ${i + 1}`"
                        @click="current = i"
                    ></button>
                </div>
            </div>
        </div>
    </div>
</template>
