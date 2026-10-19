<script setup>
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    collapsed: { type: Boolean, default: false },
    navigation: { type: Array, default: () => [] },
    urls: { type: Object, default: () => ({}) },
});

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-40 flex flex-col border-r border-outline-variant/30 bg-secondary text-on-secondary shadow-sm transition-[width]"
        :class="collapsed ? 'w-20' : 'w-64'"
    >
        <a :href="urls.home" class="flex items-center gap-3 border-b border-white/10 px-5 py-4">
            <div class="flex h-10 shrink-0 items-center justify-center rounded-lg bg-surface-container-lowest px-1.5 shadow-xs" :class="collapsed ? 'w-10' : 'w-14'">
                <img src="/img/logo-bcpme.png" alt="BCPME" class="max-h-8 w-full object-contain">
            </div>
            <div v-if="!collapsed">
                <div class="text-base font-bold leading-tight tracking-tight">BCPME ANGARA</div>
                <div class="text-[11px] font-medium text-white/70">Financement des PME</div>
            </div>
        </a>

        <!-- Les entrées sont calculées côté serveur selon le profil (App\Support\Navigation\WorkspaceNavigation). -->
        <nav class="flex-1 space-y-4 overflow-y-auto px-3 py-4">
            <div v-for="section in navigation" :key="section.label" class="space-y-1">
                <div v-if="!collapsed" class="px-3 pb-1 text-[10px] font-semibold uppercase tracking-wider text-white/70">
                    {{ section.label }}
                </div>
                <a
                    v-for="item in section.items"
                    :key="item.href"
                    :href="item.href"
                    :title="collapsed ? item.label : null"
                    class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm transition-colors"
                    :class="item.active
                        ? 'bg-surface-container-lowest font-semibold text-on-surface shadow-sm'
                        : 'font-medium text-on-secondary/90 hover:bg-black/15 hover:text-white'"
                >
                    <Icon :name="item.icon" :filled="item.active" :class="item.active ? 'text-brand-active-icon' : 'text-white/80'" />
                    <span v-if="!collapsed" class="truncate">{{ item.label }}</span>
                </a>
            </div>
        </nav>

        <div class="space-y-1 border-t border-white/10 px-3 py-3">
            <a :href="urls.profile" class="flex items-center gap-3 rounded-lg px-3.5 py-2 text-xs font-medium text-white/80 transition-colors hover:bg-black/15">
                <Icon name="manage_accounts" class="text-base" />
                <span v-if="!collapsed">Mon profil</span>
            </a>
            <form method="post" :action="urls.logout">
                <input type="hidden" name="_token" :value="csrf">
                <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3.5 py-2 text-xs font-medium text-white/80 transition-colors hover:bg-black/15">
                    <Icon name="logout" class="text-base" />
                    <span v-if="!collapsed">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>
</template>
