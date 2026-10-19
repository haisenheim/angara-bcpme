<script setup>
/**
 * Éditeur riche léger (gras, italique, listes) produisant du HTML, compatible avec les avis
 * déjà saisis via Summernote côté Blade. Le HTML affiché est assaini par <SafeHtml> (DOMPurify).
 */
import { onMounted, ref, watch } from 'vue';
import DOMPurify from 'dompurify';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    minHeight: { type: String, default: '10rem' },
});
const emit = defineEmits(['update:modelValue']);

const editor = ref(null);

onMounted(() => {
    editor.value.innerHTML = DOMPurify.sanitize(props.modelValue ?? '');
});
watch(() => props.modelValue, (value) => {
    if (editor.value && value !== editor.value.innerHTML) editor.value.innerHTML = DOMPurify.sanitize(value ?? '');
});

const sync = () => emit('update:modelValue', editor.value.innerHTML);
const exec = (command) => {
    editor.value.focus();
    document.execCommand(command, false, null);
    sync();
};

const tools = [
    ['bold', 'format_bold', 'Gras'],
    ['italic', 'format_italic', 'Italique'],
    ['underline', 'format_underlined', 'Souligné'],
    ['insertUnorderedList', 'format_list_bulleted', 'Liste à puces'],
    ['insertOrderedList', 'format_list_numbered', 'Liste numérotée'],
];
</script>

<template>
    <div class="overflow-hidden rounded-lg border border-surface-container-highest focus-within:border-secondary focus-within:ring-1 focus-within:ring-secondary">
        <div class="flex items-center gap-1 border-b border-surface-container-highest bg-surface-container-low px-2 py-1">
            <button
                v-for="[command, icon, label] in tools"
                :key="command"
                type="button"
                :title="label"
                class="rounded p-1 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface"
                @mousedown.prevent="exec(command)"
            >
                <Icon :name="icon" class="text-lg" />
            </button>
        </div>
        <div
            ref="editor"
            contenteditable="true"
            class="rich-text max-h-[28rem] overflow-y-auto bg-white px-3 py-2 text-sm text-on-surface outline-none"
            :style="{ minHeight }"
            @input="sync"
            @blur="sync"
        ></div>
    </div>
</template>
