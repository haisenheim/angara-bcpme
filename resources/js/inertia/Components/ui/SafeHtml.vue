<script setup>
/**
 * Affiche du HTML saisi par les utilisateurs (avis, analyses) après assainissement DOMPurify.
 * À utiliser à la place de v-html pour tout contenu métier.
 */
import { computed } from 'vue';
import DOMPurify from 'dompurify';

const props = defineProps({
    html: { type: String, default: '' },
    tag: { type: String, default: 'div' },
});

const clean = computed(() => DOMPurify.sanitize(props.html ?? ''));
</script>

<template>
    <component :is="tag" class="rich-text" v-html="clean" />
</template>
