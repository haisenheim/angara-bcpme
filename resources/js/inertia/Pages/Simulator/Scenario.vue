<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

defineProps({
    scenario: Object,
    actions: { type: Array, default: () => [] },
    links: { type: Array, default: () => [] },
});

const opened = ref(null);
</script>

<template>
    <Head :title="scenario.name" />
    <PageHeader :title="scenario.name" :subtitle="scenario.rattachement">
        <template #actions>
            <Button v-for="l in links" :key="l.href" :href="l.href" :icon="l.icon">{{ l.label }}</Button>
        </template>
    </PageHeader>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <Card title="Paramètres" class="lg:col-span-8">
            <template #actions>
                <div class="flex items-center gap-2">
                    <StatusBadge :variant="scenario.status.variant">{{ scenario.status.badge }}</StatusBadge>
                    <StatusBadge v-if="scenario.locked">Verrouillé</StatusBadge>
                </div>
            </template>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-xs md:grid-cols-4">
                <div v-for="[label, value] in scenario.parametres" :key="label">
                    <dt class="text-on-surface-variant">{{ label }}</dt>
                    <dd class="font-semibold text-on-surface">{{ value ?? '—' }}</dd>
                </div>
            </dl>
        </Card>

        <Card title="Workflow" class="lg:col-span-4">
            <ul class="mb-4 space-y-1.5 text-xs">
                <li v-for="e in scenario.events" :key="e.label">
                    <span class="text-on-surface-variant">{{ e.label }}</span> <span class="font-semibold text-on-surface">{{ e.at }}</span>
                    <p v-if="e.motif" class="mt-0.5 text-error">Motif : {{ e.motif }}</p>
                </li>
            </ul>
            <div class="space-y-2">
                <Button v-for="a in actions" :key="a.key" block :variant="a.variant === 'danger' ? 'ghost-danger' : a.variant" :icon="a.icon" @click="opened = a">{{ a.label }}</Button>
            </div>
        </Card>
    </div>

    <Card title="Échéancier" :padded="false">
        <div class="max-h-[70vh] overflow-auto px-5 pb-5">
            <table class="w-full text-xs tabular-nums">
                <thead class="sticky top-0 bg-surface-container-low text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr>
                        <th class="px-2 py-2 text-left">Période</th><th class="px-2 py-2 text-left">Date</th>
                        <th class="px-2 py-2 text-right">Capital début</th><th class="px-2 py-2 text-right">Capital remboursé</th>
                        <th class="px-2 py-2 text-right">Intérêts</th><th class="px-2 py-2 text-right">Assurance</th>
                        <th class="px-2 py-2 text-right">Frais</th><th class="px-2 py-2 text-right">TVA</th>
                        <th class="px-2 py-2 text-right">Échéance</th><th class="px-2 py-2 text-right">Capital fin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="l in scenario.lines" :key="l.period" :class="l.deferred ? 'bg-amber-50/70' : ''">
                        <td class="px-2 py-1.5">{{ l.period }}</td><td class="px-2 py-1.5">{{ l.date ?? '—' }}</td>
                        <td class="px-2 py-1.5 text-right">{{ l.capitalStart }}</td><td class="px-2 py-1.5 text-right">{{ l.principal }}</td>
                        <td class="px-2 py-1.5 text-right">{{ l.interest }}</td><td class="px-2 py-1.5 text-right">{{ l.insurance }}</td>
                        <td class="px-2 py-1.5 text-right">{{ l.fees }}</td><td class="px-2 py-1.5 text-right">{{ l.vat }}</td>
                        <td class="px-2 py-1.5 text-right font-semibold">{{ l.total }}</td><td class="px-2 py-1.5 text-right">{{ l.capitalEnd }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>

    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />
</template>
