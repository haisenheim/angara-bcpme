<script setup>
import { computed } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    flash: { type: Object, default: () => ({}) },
    errors: { type: Object, default: () => ({}) },
});

const errorList = computed(() => Object.values(props.errors ?? {}).flat());
</script>

<template>
    <div v-if="flash?.success" class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
        <Icon name="check_circle" class="text-emerald-700" />
        <span>{{ flash.success }}</span>
    </div>
    <div v-if="flash?.error || errorList.length" class="flex items-start gap-3 rounded-xl border border-error/30 bg-error-container p-4 text-sm text-on-error-container">
        <Icon name="error" class="text-error" />
        <div class="space-y-1">
            <p v-if="flash?.error">{{ flash.error }}</p>
            <p v-for="(message, i) in errorList" :key="i">{{ message }}</p>
        </div>
    </div>
</template>
