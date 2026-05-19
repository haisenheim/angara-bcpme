{{-- Modal notation critères — routes diffèrent selon l’espace (analyste / CA). --}}
@php
    $critereChoicesRoute = $critereChoicesRoute ?? '';
    $critereReponseRoute = $critereReponseRoute ?? '';
@endphp
@if($critereChoicesRoute !== '' && $critereReponseRoute !== '')
@php
    $critereChoicesUrl = route($critereChoicesRoute);
@endphp
<div class="modal fade" id="critereModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choix de la valeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route($critereReponseRoute) }}" method="post">
                    @csrf
                    <input type="hidden" id="_dossier_id" name="dossier_id">
                    <input type="hidden" id="_programme_id" name="programme_id">
                    <input type="hidden" id="critere_id_input" name="critere_id">
                    <div class="mb-3">
                        <label id="critere_name" class="form-label"></label>
                        <select required name="choice_id" id="critere_choices" class="form-select"></select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .grille-souscritere-row { scroll-margin-top: 6rem; }
    .grille-souscritere-row.grille-souscritere-row--flash {
        animation: grille-souscritere-flash 1.8s ease-out;
    }
    @keyframes grille-souscritere-flash {
        0%, 100% { background-color: transparent; }
        25% { background-color: rgba(var(--bs-primary-rgb), 0.12); }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function scrollToGrilleAnchor() {
        var hash = window.location.hash;
        if (!hash || hash.length < 2) {
            return;
        }
        var target = document.querySelector(hash);
        if (!target) {
            return;
        }
        requestAnimationFrame(function() {
            var scrollParent = target.closest('.overflow-auto');
            if (scrollParent && scrollParent.scrollHeight > scrollParent.clientHeight) {
                var parentRect = scrollParent.getBoundingClientRect();
                var targetRect = target.getBoundingClientRect();
                var top = targetRect.top - parentRect.top + scrollParent.scrollTop - (parentRect.height / 2) + (targetRect.height / 2);
                scrollParent.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
            } else {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            if (target.classList.contains('grille-souscritere-row')) {
                target.classList.add('grille-souscritere-row--flash');
                window.setTimeout(function() {
                    target.classList.remove('grille-souscritere-row--flash');
                }, 1800);
            }
        });
    }

    scrollToGrilleAnchor();

    var choicesUrlBase = @json($critereChoicesUrl);
    document.querySelectorAll('.btn-critere').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var sep = choicesUrlBase.indexOf('?') === -1 ? '?' : '&';
            var name = this.dataset.name;
            var dossierId = this.dataset.dossier_id;
            var programmeId = this.dataset.programme_id;
            var id = this.dataset.id;

            document.getElementById('critere_name').textContent = name;
            document.getElementById('_dossier_id').value = dossierId;
            document.getElementById('_programme_id').value = programmeId;
            document.getElementById('critere_id_input').value = id;

            fetch(choicesUrlBase + sep + 'id=' + encodeURIComponent(id))
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var select = document.getElementById('critere_choices');
                    select.innerHTML = '<option value="">Choisir...</option>';
                    data.forEach(function(choice) {
                        var opt = document.createElement('option');
                        opt.value = choice.id;
                        opt.textContent = choice.valeur;
                        select.appendChild(opt);
                    });
                });
        });
    });
});
</script>
@endif
