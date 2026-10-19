<script setup>
import { computed, ref } from 'vue';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

const props = defineProps({
    actions: { type: Array, default: () => [] },
    delegation: { type: Object, default: () => ({}) },
    stepLabel: { type: String, default: null },
});

const opened = ref(null);

// Les rejets sont regroupés dans l'encart rouge (charte : rouge réservé aux rejets).
const main = computed(() => props.actions.filter((a) => a.variant !== 'danger'));
const rejects = computed(() => props.actions.filter((a) => a.variant === 'danger'));
</script>

<template>
    <div class="space-y-4 rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs">
        <div class="flex items-center justify-between border-b border-surface-container-highest pb-3">
            <h2 class="text-sm font-bold text-on-surface">Actions d'instruction</h2>
            <span v-if="stepLabel" class="rounded bg-surface-container px-2 py-0.5 text-[11px] font-bold text-on-surface-variant">{{ stepLabel }}</span>
        </div>

        <div v-if="actions.length" class="space-y-2.5">
            <Button
                v-for="action in main"
                :key="action.key"
                block
                :variant="action.variant"
                :icon="action.variant === 'primary' ? null : action.icon"
                :icon-right="action.variant === 'primary' ? action.icon : null"
                @click="opened = action"
            >
                {{ action.label }}
            </Button>

            <div v-if="rejects.length" class="border-t border-surface-container-highest pt-2">
                <div class="mb-2 flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wider text-error">
                    <Icon name="error_outline" class="text-sm" />
                    Options de rejet ou de retour
                </div>
                <div class="space-y-2 rounded-lg border border-error-container bg-error-container/30 p-3">
                    <Button v-for="action in rejects" :key="action.key" block variant="ghost-danger" :icon="action.icon" @click="opened = action">
                        {{ action.label }}
                    </Button>
                </div>
            </div>
        </div>
        <p v-else class="text-xs text-on-surface-variant">Aucune action attendue de votre part sur ce dossier à cette étape.</p>

        <div v-if="delegation.rule || delegation.status" class="rounded-lg border border-surface-container-highest bg-surface-container-low p-3 text-[11px] leading-relaxed text-on-surface-variant">
            <div class="mb-1 flex items-center gap-1.5 font-bold text-on-surface">
                <Icon name="info" class="text-xs text-secondary" />
                Délégation de pouvoir
            </div>
            <p v-if="delegation.rule">{{ delegation.rule }}</p>
            <p v-if="delegation.status" class="mt-1">{{ delegation.status }}</p>
        </div>

        <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />
    </div>
</template>
