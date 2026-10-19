<script setup>
/**
 * Affiche du HTML saisi par les utilisateurs (avis, analyses) après assainissement DOMPurify.
 * À utiliser à la place de v-html pour tout contenu métier.
 *
 * `multiline` : contenus mixtes (texte avec retours à la ligne + fragments HTML issus de l'éditeur,
 * ex. avis consolidés de l'analyse critique). Les retours à la ligne sont conservés, le texte brut
 * est échappé et les balises sont interprétées.
 */
import { computed } from 'vue';
import DOMPurify from 'dompurify';

const props = defineProps({
    html: { type: String, default: '' },
    tag: { type: String, default: 'div' },
    multiline: { type: Boolean, default: false },
});

const escape = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
const hasMarkup = (s) => /<\/?[a-z][\s\S]*?>/i.test(s);

const clean = computed(() => {
    let source = props.html ?? '';
    if (props.multiline) {
        source = (hasMarkup(source) ? source : escape(source))
            .replace(/\r\n?/g, '\n')
            // Pas de saut de ligne superflu autour des blocs HTML (div, p, listes, titres).
            .replace(/\n*(<\/?(?:div|p|ul|ol|li|h[1-6]|table|tr|blockquote)\b[^>]*>)\n*/gi, '$1')
            .replace(/\n/g, '<br>');
    }

    return DOMPurify.sanitize(source);
});
</script>

<template>
    <component :is="tag" class="rich-text" v-html="clean" />
</template>
