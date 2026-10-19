<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    rejection: { type: Object, required: true },
});
</script>

<template>
    <div class="flex items-start gap-2 rounded-lg border border-error/30 bg-error-container/60 p-2.5 text-xs">
        <Icon name="undo" class="mt-0.5 text-sm text-error" />
        <div>
            <span class="font-bold text-error">{{ rejection.label }}</span>
            <span class="text-on-surface-variant"> — {{ rejection.at }}<template v-if="rejection.by"> par {{ rejection.by }}</template></span>
            <p v-if="rejection.motif" class="mt-1 whitespace-pre-line text-on-surface">{{ rejection.motif }}</p>
        </div>
    </div>
</template>
