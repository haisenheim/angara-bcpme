<?php

return [
    'title' => 'Simulateur de credit',
    'subtitle' => 'Calcul d\'echeancier et integration au dossier d\'instruction',

    'fields' => [
        'name' => 'Nom du scenario',
        'principal' => 'Capital emprunte',
        'annual_rate' => 'Taux annuel (%)',
        'term_periods' => 'Duree (nombre de periodes)',
        'periodicity' => 'Periodicite',
        'amortization_type' => 'Type d\'amortissement',
        'deferral_type' => 'Type de differe',
        'deferral_periods' => 'Nombre de periodes de differe',
        'first_period_date' => 'Date de la premiere echeance',
        'currency' => 'Devise',
        'fx_rate' => 'Taux de change',
        'dossier_fee_fixed' => 'Frais de dossier (montant fixe)',
        'dossier_fee_pct' => 'Frais de dossier (% du capital)',
        'insurance_pct' => 'Taux annuel d\'assurance (%)',
        'insurance_basis' => 'Assiette de l\'assurance',
        'vat_rate' => 'TVA / TAF (%)',
    ],

    'kpis' => [
        'first_payment' => 'Premiere echeance',
        'max_payment' => 'Echeance max',
        'total_interest' => 'Total interets',
        'total_fees' => 'Total frais',
        'total_insurance' => 'Total assurance',
        'total_vat' => 'Total TVA',
        'total_due' => 'Total a rembourser',
        'computed_teg' => 'TEG calcule (%)',
    ],

    'schedule' => [
        'period' => 'Periode',
        'date' => 'Date',
        'capital_due_start' => 'Capital du debut',
        'principal_paid' => 'Capital rembourse',
        'interest_paid' => 'Interets',
        'insurance_paid' => 'Assurance',
        'fees_paid' => 'Frais',
        'vat_paid' => 'TVA',
        'total_payment' => 'Echeance totale',
        'capital_due_end' => 'Capital du fin',
        'is_deferred' => 'Differe',
    ],

    'actions' => [
        'simulate' => 'Lancer la simulation',
        'reset' => 'Reinitialiser',
        'export_pdf' => 'Exporter PDF',
        'export_xlsx' => 'Exporter Excel',
        'persist' => 'Enregistrer & lier au dossier',
        'submit' => 'Soumettre',
        'validate' => 'Valider',
        'reject' => 'Rejeter',
        'detach' => 'Detacher du dossier',
        'delete' => 'Supprimer',
    ],

    'workflow' => [
        'status' => [
            'draft' => 'Brouillon',
            'submitted' => 'Soumis',
            'validated' => 'Valide',
            'rejected' => 'Rejete',
        ],
        'locked' => 'Verrouille (parametres figes)',
        'submitted_by' => 'Soumis par :nom le :date',
        'validated_by' => 'Valide par :nom le :date',
        'rejected_by' => 'Rejete par :nom le :date',
    ],

    'attachment' => [
        'none' => 'Scenario libre (non rattache)',
        'dossier' => 'Rattache au dossier',
        'programme' => 'Rattache a la ligne dossier-programme',
        'choose' => 'Type de rattachement',
        'choose_dossier' => 'Rattacher au dossier global',
        'choose_programme' => 'Rattacher a une ligne programme',
    ],

    'usury_warning' => 'Le taux saisi depasse le seuil d\'usure indicatif (:rate %).',

    'breadcrumb' => [
        'home' => 'Accueil',
        'simulator' => 'Simulateur',
        'scenarios' => 'Scenarios enregistres',
        'detail' => 'Detail',
    ],
];
