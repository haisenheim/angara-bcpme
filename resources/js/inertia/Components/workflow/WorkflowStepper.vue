<script setup>
import { computed } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    steps: { type: Array, required: true },
});

const progress = computed(() => {
    const done = props.steps.filter((s) => s.state === 'done').length;
    return Math.round((done / props.steps.length) * 100);
});

const box = {
    done: 'bg-surface-container-low/40',
    current: 'border-2 border-secondary bg-secondary/10 shadow-2xs',
    rejected: 'border-2 border-error/40 bg-error-container/40',
    pending: 'opacity-60',
};
const bullet = {
    done: 'bg-emerald-100 text-emerald-700',
    current: 'bg-secondary text-white ring-4 ring-secondary/20',
    rejected: 'bg-error text-white',
    pending: 'bg-surface-container-high text-on-surface-variant',
};
const caption = {
    done: ['text-emerald-700', 'Validé'],
    current: ['text-secondary', 'En cours'],
    rejected: ['text-error', 'Rejeté'],
    pending: ['text-on-surface-variant', 'En attente'],
};
</script>

<template>
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs">
        <div class="mb-4 flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-on-surface-variant">
            <span>Workflow d'instruction et chaîne de décision</span>
            <span class="text-[11px] font-bold text-secondary">Progression : {{ progress }} %</span>
        </div>
        <div class="overflow-x-auto pb-2">
            <ol class="grid min-w-[840px] gap-2" :style="{ gridTemplateColumns: `repeat(${steps.length}, minmax(0, 1fr))` }">
                <li v-for="step in steps" :key="step.key" class="flex flex-col items-center rounded-lg p-2 text-center" :class="box[step.state]">
                    <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold" :class="bullet[step.state]">
                        <Icon v-if="step.state === 'done'" name="check" class="text-base" />
                        <Icon v-else-if="step.state === 'rejected'" name="close" class="text-base" />
                        <Icon v-else-if="step.state === 'current'" name="pending" class="text-base" />
                        <span v-else>{{ step.key }}</span>
                    </div>
                    <span class="text-xs font-bold" :class="step.state === 'current' ? 'text-secondary' : 'text-on-surface'">{{ step.key }}. {{ step.label }}</span>
                    <span class="mt-0.5 text-[10px] font-semibold" :class="caption[step.state][0]">{{ caption[step.state][1] }}</span>
                    <span v-if="step.actor || step.at" class="text-[10px] text-on-surface-variant">
                        {{ step.actor }}<template v-if="step.actor && step.at"> • </template>{{ step.at }}
                    </span>
                </li>
            </ol>
        </div>
    </section>
</template>
