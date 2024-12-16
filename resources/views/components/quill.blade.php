<!--
<div id="toolbar-container">
    <span class="ql-formats">
      <select class="ql-font"></select>
      <select class="ql-size"></select>
    </span>
    <span class="ql-formats">
      <button class="ql-bold"></button>
      <button class="ql-italic"></button>
      <button class="ql-underline"></button>
      <button class="ql-strike"></button>
    </span>
    <span class="ql-formats">
      <select class="ql-color"></select>
      <select class="ql-background"></select>
    </span>
    <span class="ql-formats">
      <button class="ql-script" value="sub"></button>
      <button class="ql-script" value="super"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-header" value="1"></button>
      <button class="ql-header" value="2"></button>
      <button class="ql-blockquote"></button>
     <button class="ql-code-block"></button>
    </span>
    <span class="ql-formats">
      <button class="ql-list" value="ordered"></button>
      <button class="ql-list" value="bullet"></button>
      <button class="ql-indent" value="-1"></button>
      <button class="ql-indent" value="+1"></button>
    </span>
    <span class="ql-formats">
     <button class="ql-direction" value="rtl"></button>
      <select class="ql-align"></select>
    </span>
    <span class="ql-formats">
      <button class="ql-link"></button>
    <button class="ql-image"></button>
      <button class="ql-video"></button>
      <button class="ql-formula"></button>
    </span>
   <span class="ql-formats">
      <button class="ql-clean"></button>
    </span> 
</div>
-->
<div>
    <div id="quill-editor" class="mb-3" style="height: 100px;"></div>
    <textarea rows="3" class="mb-3 d-none" name="{{$name}}" id="quill-editor-area"></textarea>
</div>

<script>

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('quill-editor-area')) {
                var editor = new Quill('#quill-editor', {
                    theme: 'snow',
                    modules: { toolbar: [ [{ 'header': [1, 2, false] }], ['bold', 'italic', 'underline', 'strike'], ['blockquote', 'code-block'], [{ 'list': 'ordered' }, { 'list': 'bullet' }], ['link'], ['clean'] ] }
                 });
               /*  const quill = new Quill('#quill-editor', {
                    modules: {
                        syntax: true,
                        toolbar: '#toolbar-container',
                    },
                    placeholder: 'Compose an epic...',
                    theme: 'snow',
                }); */
                var quillEditor = document.getElementById('quill-editor-area');
                editor.on('text-change', function() { quillEditor.value = editor.root.innerHTML; });
                quillEditor.addEventListener('input', function() { editor.root.innerHTML = quillEditor.value; });
            }
        });
</script>
