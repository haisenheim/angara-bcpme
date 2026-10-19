<script setup>
import { ref } from 'vue';
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import RejectionBanner from '@/Components/workflow/RejectionBanner.vue';

const props = defineProps({
    pole: { type: Object, required: true },
});

// Les pôles franchis sont repliés sur l'avis du responsable ; le pôle en cours est déplié.
const expanded = ref(props.pole.state === 'current');

const stateBadge = {
    done: ['success', 'Transmis'],
    current: ['info', 'En cours'],
    pending: ['neutral', 'Non atteint'],
};
</script>

<template>
    <article
        class="relative rounded-xl bg-surface-container-lowest p-5 shadow-xs transition-shadow hover:shadow-sm"
        :class="pole.state === 'current' ? 'border-2 border-secondary/40' : 'border border-surface-container-highest'"
    >
        <div v-if="pole.state === 'current'" class="absolute -top-3 left-6 rounded bg-secondary px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">
            Étape en cours
        </div>

        <header class="flex items-start justify-between gap-3" :class="{ 'mt-1': pole.state === 'current', 'border-b border-surface-container-highest pb-3': pole.state !== 'pending' }">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full"
                     :class="pole.state === 'done' ? 'bg-emerald-50 text-secondary' : pole.state === 'current' ? 'bg-secondary/15 text-secondary' : 'bg-surface-container-low text-on-surface-variant'">
                    <Icon :name="pole.icon" class="text-lg" />
                </div>
                <div>
                    <h3 class="text-sm font-bold text-on-surface">{{ pole.title }}</h3>
                    <p v-if="pole.analyst" class="text-[11px] text-on-surface-variant">
                        {{ pole.analystRole }} : {{ pole.analyst.name }}
                        <template v-if="pole.analyst.assignedBy"> • affecté par {{ pole.analyst.assignedBy }}</template>
                        <template v-if="pole.analyst.assignedAt"> le {{ pole.analyst.assignedAt }}</template>
                    </p>
                    <p v-else-if="pole.analystRole && pole.state !== 'pending'" class="text-[11px] italic text-on-surface-variant">Aucun {{ pole.analystRole.toLowerCase() }} affecté</p>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                <StatusBadge v-if="pole.decision" :variant="pole.decision.variant">{{ pole.decision.label }}</StatusBadge>
                <StatusBadge :variant="stateBadge[pole.state][0]">{{ stateBadge[pole.state][1] }}</StatusBadge>
            </div>
        </header>

        <div v-if="pole.state !== 'pending'" class="mt-4 space-y-3 text-xs">
            <RejectionBanner v-for="(r, i) in pole.rejections" :key="i" :rejection="r" />

            <!-- Avis du responsable (toujours visible) -->
            <div v-if="pole.responsableAvis" class="space-y-1">
                <div class="font-semibold text-on-surface">
                    {{ pole.responsableRole }}
                    <span class="font-normal text-on-surface-variant">— {{ pole.responsableAvis.by }}<template v-if="pole.responsableAvis.at">, {{ pole.responsableAvis.at }}</template></span>
                </div>
                <SafeHtml :html="pole.responsableAvis.html" class="leading-relaxed text-on-surface" />
            </div>
            <p v-if="pole.decision?.comment" class="rounded-lg bg-surface-container-low/60 p-2.5 text-on-surface-variant">
                <span class="font-semibold text-on-surface">Commentaire :</span> {{ pole.decision.comment }}
            </p>

            <!-- Travail de l'analyste (repliable) -->
            <div v-if="pole.analystSections.length" class="rounded-lg border border-surface-container-highest">
                <button type="button" class="flex w-full items-center justify-between px-3 py-2 text-left font-semibold text-on-surface hover:bg-surface-container-low/60" @click="expanded = !expanded">
                    <span class="flex items-center gap-1.5">
                        <Icon name="edit_note" class="text-sm text-secondary" />
                        Travail de l'analyste
                        <span v-if="pole.analystSubmitted" class="font-normal text-on-surface-variant">— soumis le {{ pole.analystSubmitted.at }}</span>
                    </span>
                    <Icon :name="expanded ? 'expand_less' : 'expand_more'" class="text-base" />
                </button>
                <div v-if="expanded" class="space-y-3 border-t border-surface-container-highest p-3">
                    <div v-for="section in pole.analystSections" :key="section.label">
                        <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ section.label }}</div>
                        <SafeHtml :html="section.html" class="leading-relaxed text-on-surface" />
                    </div>
                </div>
            </div>

            <div v-if="pole.transmitted" class="flex items-center gap-2 rounded-lg border border-surface-container-highest bg-surface-container-low/40 p-2.5 text-[11px] text-on-surface-variant">
                <Icon name="lock" class="text-xs text-emerald-700" />
                <span>Transmis le {{ pole.transmitted.at }}<template v-if="pole.transmitted.by"> par {{ pole.transmitted.by }}</template> — contenu verrouillé</span>
            </div>
            <p v-else-if="!pole.responsableAvis && !pole.analystSections.length" class="italic text-on-surface-variant">Instruction en cours dans ce pôle.</p>
        </div>
    </article>
</template>
