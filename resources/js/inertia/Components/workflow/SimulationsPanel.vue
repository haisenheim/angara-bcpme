<script setup>
import { ref } from 'vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';

defineProps({
    simulations: { type: Object, required: true },
});

const menu = ref(false);
</script>

<template>
    <Card title="Simulations de crédit">
        <template #actions>
            <div class="relative">
                <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-container px-3 py-2 text-xs font-semibold text-white hover:bg-black" @click="menu = !menu">
                    <Icon name="add" class="text-base" /> Nouvelle simulation <Icon name="expand_more" class="text-base" />
                </button>
                <div v-if="menu" class="absolute right-0 z-20 mt-1 w-72 rounded-lg border border-surface-container-highest bg-white py-1 shadow-lg" @mouseleave="menu = false">
                    <a :href="simulations.newDossierUrl" class="block px-3 py-2 text-xs text-on-surface hover:bg-surface-container-low">Pour le dossier complet</a>
                    <template v-if="simulations.newLines.length">
                        <div class="border-t border-surface-container-highest px-3 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Pour une ligne programme</div>
                        <a v-for="l in simulations.newLines" :key="l.url" :href="l.url" class="block px-3 py-2 text-xs text-on-surface hover:bg-surface-container-low">{{ l.label }}</a>
                    </template>
                </div>
            </div>
        </template>
        <div>
            <p v-if="!simulations.scenarios.length" class="py-6 text-center text-xs text-on-surface-variant">Aucune simulation enregistrée pour ce dossier.</p>
            <table v-else class="w-full text-left text-xs">
                <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr>
                        <th class="py-2">Nom</th><th class="py-2">Cible</th><th class="py-2 text-right">Capital</th><th class="py-2 text-right">Taux</th>
                        <th class="py-2 text-right">Échéance max</th><th class="py-2 text-right">TEG</th><th class="py-2">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="s in simulations.scenarios" :key="s.url">
                        <td class="py-2"><a :href="s.url" class="font-semibold text-on-surface hover:text-secondary">{{ s.name }}</a></td>
                        <td class="py-2">{{ s.target }}</td>
                        <td class="py-2 text-right">{{ s.principal }}</td>
                        <td class="py-2 text-right">{{ s.rate }}</td>
                        <td class="py-2 text-right">{{ s.maxPayment }}</td>
                        <td class="py-2 text-right">{{ s.teg ?? '—' }}</td>
                        <td class="py-2"><StatusBadge :variant="s.variant">{{ s.status }}</StatusBadge></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>
</template>
