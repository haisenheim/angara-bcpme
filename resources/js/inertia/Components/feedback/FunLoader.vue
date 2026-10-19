<script setup>
/**
 * Loader « tirelire » : des pièces tombent dans une tirelire BCPME pendant que des messages
 * humoristiques défilent. Piloté par le store global `loader` (resources/js/inertia/loader.js).
 */
import { onBeforeUnmount, ref, watch } from 'vue';
import { loader } from '@/loader';

const messages = [
    'On compte les FCFA un par un…',
    'Le comité de crédit délibère…',
    'Tamponnage des dossiers en cours…',
    'Le chef d’agence finit son café…',
    'On réveille l’analyste risques…',
    'Vérification des garanties (et des signatures)…',
    'La tirelire se remplit, patience…',
    'Calcul du TEG à la main… presque fini !',
];
const index = ref(Math.floor(Math.random() * messages.length));
let timer = null;

watch(() => loader.visible, (visible) => {
    clearInterval(timer);
    if (visible) {
        index.value = Math.floor(Math.random() * messages.length);
        timer = setInterval(() => (index.value = (index.value + 1) % messages.length), 1800);
    }
});
onBeforeUnmount(() => clearInterval(timer));
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        leave-active-class="transition duration-150 ease-in"
        leave-to-class="opacity-0"
    >
        <div v-if="loader.visible" class="fixed inset-0 z-[100] flex items-center justify-center bg-surface/70 backdrop-blur-[2px]" role="status" aria-live="polite">
            <div class="flex w-72 flex-col items-center rounded-2xl border border-surface-container-highest bg-surface-container-lowest px-6 pb-6 pt-8 shadow-lg">
                <!-- Scène : pièces qui tombent dans la tirelire -->
                <div class="relative h-28 w-28" aria-hidden="true">
                    <span v-for="n in 3" :key="n" class="coin" :style="{ animationDelay: `${(n - 1) * 0.45}s`, left: `${30 + (n - 2) * 14}px` }">F</span>
                    <div class="piggy">
                        <span class="material-symbols-outlined fill-1 piggy-icon text-secondary">savings</span>
                    </div>
                    <div class="shadow"></div>
                </div>

                <p class="mt-3 min-h-10 text-center text-sm font-semibold text-on-surface">
                    <Transition mode="out-in" enter-active-class="transition duration-300" enter-from-class="translate-y-1 opacity-0" leave-active-class="transition duration-200" leave-to-class="-translate-y-1 opacity-0">
                        <span :key="loader.message ?? index">{{ loader.message ?? messages[index] }}</span>
                    </Transition>
                </p>
                <div class="mt-3 h-1 w-full overflow-hidden rounded-full bg-surface-container-high">
                    <div class="bar h-full w-1/3 rounded-full bg-brand"></div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.piggy {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    animation: wiggle 1.35s ease-in-out infinite;
    transform-origin: 50% 100%;
}
.piggy-icon {
    font-size: 76px;
    line-height: 1;
    font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 48;
}
.shadow {
    position: absolute;
    bottom: 4px;
    left: 50%;
    width: 56px;
    height: 8px;
    margin-left: -28px;
    border-radius: 9999px;
    background: rgb(0 0 0 / 0.08);
    animation: squash 1.35s ease-in-out infinite;
}
.coin {
    position: absolute;
    top: -4px;
    width: 22px;
    height: 22px;
    border-radius: 9999px;
    background: radial-gradient(circle at 35% 35%, #f8e08a, #e0b23a 60%, #b88a1b);
    border: 2px solid #c9971f;
    color: #7a5a0c;
    font: 800 11px/18px Inter, sans-serif;
    text-align: center;
    opacity: 0;
    animation: drop 1.35s cubic-bezier(0.55, 0, 0.8, 0.4) infinite;
}
.bar {
    animation: slide 1.2s ease-in-out infinite;
}
@keyframes drop {
    0% { transform: translateY(0) rotateY(0deg); opacity: 0; }
    15% { opacity: 1; }
    70% { transform: translateY(46px) rotateY(540deg); opacity: 1; }
    80%, 100% { transform: translateY(54px) rotateY(720deg) scale(0.4); opacity: 0; }
}
@keyframes wiggle {
    0%, 60%, 100% { transform: translateX(-50%) rotate(0deg) scaleY(1); }
    70% { transform: translateX(-50%) rotate(-6deg) scaleY(0.94); }
    80% { transform: translateX(-50%) rotate(5deg) scaleY(1.03); }
    90% { transform: translateX(-50%) rotate(-2deg); }
}
@keyframes squash {
    0%, 60%, 100% { transform: scaleX(1); }
    70% { transform: scaleX(1.15); }
}
@keyframes slide {
    0% { transform: translateX(-110%); }
    100% { transform: translateX(310%); }
}
@media (prefers-reduced-motion: reduce) {
    .coin, .piggy, .shadow, .bar { animation-duration: 3s; }
}
</style>
