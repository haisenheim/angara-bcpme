<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/layout/Sidebar.vue';
import Topbar from '@/Components/layout/Topbar.vue';
import FlashMessages from '@/Components/layout/FlashMessages.vue';

const page = usePage();
const collapsed = ref(false);
const breadcrumb = computed(() => page.props.breadcrumb ?? []);
</script>

<template>
    <div class="flex min-h-full">
        <Sidebar :collapsed="collapsed" :navigation="page.props.navigation" :urls="page.props.urls" />

        <div class="flex min-h-screen min-w-0 flex-1 flex-col transition-[margin]" :class="collapsed ? 'ml-20' : 'ml-64'">
            <Topbar :auth="page.props.auth" :breadcrumb="breadcrumb" @toggle-sidebar="collapsed = !collapsed" />

            <main class="mx-auto w-full max-w-[1520px] flex-1 space-y-6 p-8">
                <FlashMessages :flash="page.props.flash" :errors="page.props.errors" />
                <slot />
            </main>

            <footer class="mt-auto flex items-center justify-between border-t border-surface-container-highest bg-surface-container-lowest px-8 py-4 text-xs text-on-surface-variant">
                <span>© {{ new Date().getFullYear() }} Banque Camerounaise des PME (BCPME) — propulsé par ANGARA SOLUTIONS</span>
                <span class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] text-on-surface">Système connecté</span>
                </span>
            </footer>
        </div>
    </div>
</template>
