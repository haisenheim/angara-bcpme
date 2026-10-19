<script setup>
import { onBeforeUnmount, onMounted } from 'vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: '' },
    wide: { type: Boolean, default: false },
});
const emit = defineEmits(['close']);

const onKey = (e) => {
    if (e.key === 'Escape' && props.show) emit('close');
};
onMounted(() => document.addEventListener('keydown', onKey));
onBeforeUnmount(() => document.removeEventListener('keydown', onKey));
</script>

<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/40 p-6 pt-20" @mousedown.self="emit('close')">
            <div class="w-full rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-lg" :class="wide ? 'max-w-4xl' : 'max-w-lg'" role="dialog" aria-modal="true">
                <header class="flex items-center justify-between border-b border-surface-container-highest px-5 py-4">
                    <h2 class="text-sm font-bold text-on-surface">{{ title }}</h2>
                    <button type="button" class="rounded-full p-1 text-on-surface-variant hover:bg-surface-container-low" aria-label="Fermer" @click="emit('close')">
                        <Icon name="close" />
                    </button>
                </header>
                <div class="px-5 py-4">
                    <slot />
                </div>
                <footer v-if="$slots.footer" class="flex flex-wrap items-center justify-end gap-2 border-t border-surface-container-highest px-5 py-3">
                    <slot name="footer" />
                </footer>
            </div>
        </div>
    </Teleport>
</template>
