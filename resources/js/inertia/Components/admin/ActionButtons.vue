<script setup>
/**
 * Boutons d'actions décrites par le serveur (App\Services\Inertia\Admin\CrudKit) :
 *  - `href`    : lien GET (consultation, activation / verrouillage) ;
 *  - `request` : requête directe vers une route existante (suppression…), avec confirmation ;
 *  - `fields`  : formulaire ouvert dans ActionDialog.
 * Mode `menu` : menu compact pour les lignes de tableau.
 */
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

const props = defineProps({
    actions: { type: Array, default: () => [] },
    menu: { type: Boolean, default: false },
    autoOpen: { type: String, default: null },
});

const opened = ref(null);
const menuOpen = ref(false);

const run = (action) => {
    menuOpen.value = false;
    if (action.fields) {
        opened.value = action;
        return;
    }
    if (action.request) {
        const { url, method, confirm } = action.request;
        if (confirm && !window.confirm(confirm)) return;
        router.visit(url, { method: (method ?? 'post').toLowerCase(), preserveScroll: true });
        return;
    }
    if (action.href) {
        if (action.external) {
            window.open(action.href, '_blank', 'noopener');
            return;
        }
        if (action.download) {
            window.location.href = action.href;
            return;
        }
        router.visit(action.href, { preserveScroll: true });
    }
};

onMounted(() => {
    if (props.autoOpen) {
        const a = props.actions.find((x) => x.key === props.autoOpen);
        if (a) opened.value = a;
    }
});

const itemClass = (a) => (['danger', 'ghost-danger'].includes(a.variant) ? 'text-error' : 'text-on-surface');
</script>

<template>
    <template v-if="!menu">
        <Button v-for="a in actions" :key="a.key" :variant="a.variant ?? 'secondary'" :icon="a.icon" @click="run(a)">{{ a.label }}</Button>
    </template>

    <div v-else-if="actions.length" class="relative inline-block text-left" @click.stop>
        <button
            v-if="actions.length === 1"
            type="button"
            class="inline-flex items-center gap-1 rounded-lg px-2 py-1.5 text-[11px] font-semibold hover:bg-surface-container-high"
            :class="itemClass(actions[0])"
            :title="actions[0].label"
            @click="run(actions[0])"
        >
            <Icon :name="actions[0].icon ?? 'chevron_right'" class="text-base" /> {{ actions[0].label }}
        </button>
        <template v-else>
            <button type="button" class="rounded-lg p-1.5 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface" title="Actions" @click="menuOpen = !menuOpen">
                <Icon name="more_vert" />
            </button>
            <div v-if="menuOpen" class="absolute right-0 z-30 mt-1 w-52 rounded-lg border border-surface-container-highest bg-white py-1 shadow-lg" @mouseleave="menuOpen = false">
                <button
                    v-for="a in actions"
                    :key="a.key"
                    type="button"
                    class="flex w-full items-center gap-2 px-3 py-2 text-left text-xs hover:bg-surface-container-low"
                    :class="itemClass(a)"
                    @click="run(a)"
                >
                    <Icon :name="a.icon ?? 'chevron_right'" class="text-base" /> {{ a.label }}
                </button>
            </div>
        </template>
    </div>

    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />
</template>
