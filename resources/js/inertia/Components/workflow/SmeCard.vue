<script setup>
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';

defineProps({
    sme: { type: Object, default: null },
    title: { type: String, default: 'Notation PME' },
});
</script>

<template>
    <Card v-if="sme" :title="title">
        <div class="space-y-2 text-xs">
            <p v-if="sme.noteFinale !== null && sme.noteFinale !== undefined" class="text-on-surface-variant">
                Note pondérée finale : <strong class="text-on-surface">{{ sme.noteFinale }}</strong>
            </p>
            <p v-if="sme.name" class="text-sm font-bold text-on-surface">{{ sme.name }}</p>
            <div v-if="sme.mention">
                <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Mention</div>
                <StatusBadge :variant="sme.mentionVariant">{{ sme.mention }}</StatusBadge>
            </div>
            <div v-if="sme.description">
                <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Avis SME</div>
                <p class="leading-relaxed text-on-surface">{{ sme.description }}</p>
            </div>
            <slot />
        </div>
    </Card>
</template>
