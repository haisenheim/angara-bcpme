/**
 * Angara — utilitaires DataTables (i18n FR, options par défaut).
 * Charge dépendances DataTables avant les scripts de page.
 */
(function (global) {
    'use strict';

    function frLanguage() {
        return {
            decimal: ',',
            thousands: ' ',
            emptyTable: 'Aucune donnée disponible dans le tableau',
            info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
            infoEmpty: 'Affichage de 0 à 0 sur 0 entrées',
            infoFiltered: '(filtrées depuis un total de _MAX_ entrées)',
            lengthMenu: 'Afficher _MENU_ entrées',
            loadingRecords: 'Chargement...',
            processing: 'Traitement...',
            search: '',
            searchPlaceholder: 'Rechercher...',
            zeroRecords: 'Aucune entrée correspondante trouvée',
            paginate: {
                first: 'Première',
                last: 'Dernière',
                next: 'Suivante',
                previous: 'Précédente',
            },
            aria: {
                sortAscending: ' : activer pour trier la colonne par ordre croissant',
                sortDescending: ' : activer pour trier la colonne par ordre décroissant',
            },
        };
    }

    function mergeDefaults(options) {
        var base = {
            language: frLanguage(),
            pagingType: 'simple_numbers',
            searchDelay: 250,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            // DataTables 2 layout (fallback-friendly; ignored by DT1)
            layout: {
                topStart: 'pageLength',
                topEnd: 'search',
                bottomStart: 'info',
                bottomEnd: 'paging',
            },
        };
        if (!options || typeof options !== 'object') {
            return base;
        }
        if (options.serverSide) {
            base.processing = true;
        }
        return Object.assign({}, base, options);
    }

    global.AngaraDataTables = {
        frLanguage: frLanguage,
        mergeDefaults: mergeDefaults,
    };
})(typeof window !== 'undefined' ? window : this);
