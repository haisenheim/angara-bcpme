<script setup>
defineProps({
    events: { type: Array, default: () => [] },
});

const dot = (kind) => ({
    validation: 'bg-emerald-600',
    transmission: 'bg-blue-600',
    avis: 'bg-secondary',
    affectation: 'bg-amber-500',
}[kind] ?? 'bg-surface-container-highest');
</script>

<template>
    <ol class="relative ml-2 space-y-4 border-l border-surface-container-highest text-xs">
        <li v-for="(event, i) in events" :key="i" class="ml-4">
            <div class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full border-2 border-white" :class="dot(event.kind)"></div>
            <div class="flex items-center justify-between gap-2">
                <span class="font-semibold text-on-surface">{{ event.label }}</span>
                <time class="shrink-0 text-[10px] text-on-surface-variant">{{ event.at }}</time>
            </div>
            <p v-if="event.actor" class="text-[11px] text-on-surface-variant">{{ event.actor }}</p>
        </li>
        <li v-if="!events.length" class="ml-4 text-on-surface-variant">Aucun événement enregistré.</li>
    </ol>
</template>
